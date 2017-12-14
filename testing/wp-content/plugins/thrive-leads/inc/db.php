<?php
/**
 * handles database operations
 */

global $tvedb;

/**
 * encapsulates the global $wpdb object
 *
 * Class Thrive_Leads_DB
 */
class Thrive_Leads_DB {
	/**
	 * @var wpdb the $wpdb instance
	 */
	protected $wpdb = null;

	/**
	 * class constructor
	 */
	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	/**
	 * forward the call to the $wpdb object
	 *
	 * @param $methodName
	 * @param $args
	 *
	 * @return mixed
	 */
	public function __call( $methodName, $args ) {
		return call_user_func_array( array( $this->wpdb, $methodName ), $args );
	}

	/**
	 * unserialize fields from an array
	 *
	 * @param array $array where to search the fields
	 * @param array $fields fields to be unserialized
	 *
	 * @return array the modified array containing the unserialized fields
	 */
	protected function _unserialize_fields( $array, $fields = array() ) {

		foreach ( $fields as $field ) {
			if ( ! isset( $array[ $field ] ) ) {
				continue;
			}
			/* the serialized fields should be trigger_config and tcb_fields */
			$array[ $field ] = empty( $array[ $field ] ) ? array() : unserialize( $array[ $field ] );
			$array[ $field ] = wp_unslash( $array[ $field ] );

			/* extra checks to ensure we'll have consistency */
			if ( ! is_array( $array[ $field ] ) ) {
				$array[ $field ] = array();
			}
		}

		return $array;
	}

	/**
	 *
	 * replace table names in form of {table_name} with the prefixed version
	 *
	 * @param $sql
	 * @param $params
	 *
	 * @return false|null|string
	 */
	public function prepare( $sql, $params ) {
		$prefix = tve_leads_table_name( '' );
		$sql    = preg_replace( '/\{(.+?)\}/', '`' . $prefix . '$1' . '`', $sql );

		if ( strpos( $sql, '%' ) === false ) {
			return $sql;
		}

		return $this->wpdb->prepare( $sql, $params );
	}

	/**
	 * Insert a new event in the log table and automatically update the related cached entries for the related Lead Group, Form Type and form variations
	 *
	 * @param array $data
	 * @param int $active_test , if any
	 * @param bool $cache_variation_data whether or not to increase the cached number of impressions / conversions for the variation. this will be true for all variations that have cached data, and false if there is no cache for the variation
	 *
	 * @return int
	 */
	public function insert_event( $data, $active_test, $cache_variation_data = false ) {
		if ( ! isset( $data['date'] ) ) {
			$data['date'] = date( 'Y-m-d H:i:s' );
		}

		$this->wpdb->insert( tve_leads_table_name( 'event_log' ), $data );
		$log_id = $this->wpdb->insert_id;

		if ( $active_test ) {
			$this->update_test_item_data( $data, $active_test, '+' );
		}

		$field = $data['event_type'] === TVE_LEADS_UNIQUE_IMPRESSION ? 'impressions' : 'conversions';
		if ( $cache_variation_data ) {
			$this->wpdb->query( $this->prepare( "UPDATE {form_variations} SET `cache_{$field}` = `cache_{$field}` + 1 WHERE `key` = %d", array( $data['variation_key'] ) ) );
		}

		/**
		 * increase the impressions / conversions for the Lead Group / Form Type / Shortcode
		 */
		$main_group_id = $data['main_group_id'];
		$form_type_id  = $data['form_type_id'];

		if ( ! empty( $main_group_id ) ) {
			$count = tve_leads_get_post_tracking_data( $main_group_id, $data['event_type'], false );
			if ( $count !== '' ) { // this means we have a cached value for the Lead Group, it's ok to increment that
				$count ++;
				tve_leads_set_post_tracking_data( $main_group_id, $count, $data['event_type'] );
			}
		}
		if ( ! empty( $form_type_id ) && $form_type_id != $main_group_id ) {
			$count = tve_leads_get_post_tracking_data( $form_type_id, $data['event_type'], false );
			if ( $count !== '' ) { // this means we have a cached value for the form type, it's ok to increment that
				$count ++;
				tve_leads_set_post_tracking_data( $form_type_id, $count, $data['event_type'] );
			}
		}

		return $log_id;
	}

	/**
	 * get an event log by id
	 *
	 * @param int $event_id
	 *
	 * @return mixed
	 */
	public function get_event( $event_id ) {
		return $this->wpdb->get_row( $this->prepare( "SELECT * FROM {event_log} WHERE id = %d", array( $event_id ) ), ARRAY_A );
	}

	/**
	 * increment / decrement a test item number of unique_impressions|conversions|impressions
	 *
	 * @param array $data tracking data -> the field that needs updating is calculated based on the event_type field from data
	 * @param mixed $test_model
	 * @param string $use_case can be either "+" or "-" for increment and decrement
	 */
	public function update_test_item_data( $data, $test_model, $use_case = '-' ) {
		$params = array();
		switch ( $data['event_type'] ) {
			case TVE_LEADS_IMPRESSION:
				$field = '`impressions`';
				break;
			case TVE_LEADS_UNIQUE_IMPRESSION:
				$field = '`unique_impressions`';
				break;
			case TVE_LEADS_CONVERSION:
				$field = '`conversions`';
				break;
			default:
				return;
		}

		if ( ! in_array( $use_case, array( '+', '-' ) ) ) {
			return;
		}

		if ( $use_case == '-' ) {
			$operation = "{$field} = IF( {$field} = 0, 0, {$field} - 1 )";
		} else {
			$operation = "{$field} = {$field} {$use_case} 1";
		}

		$sql = "UPDATE {split_test_items} SET {$operation} WHERE test_id = %d";

		$id        = is_object( $test_model ) ? $test_model->id : ( is_array( $test_model ) ? $test_model['id'] : $test_model );
		$params [] = intval( $id );

		/* actually, this should always be filled in */
		if ( ! empty( $data['variation_key'] ) ) {
			$sql .= " AND variation_key = %d";
			$params [] = $data['variation_key'];
		}
		if ( ! empty( $data['main_group_id'] ) ) {
			$sql .= " AND main_group_id = %d";
			$params [] = $data['main_group_id'];
		}
		if ( ! empty( $data['form_type_id'] ) ) {
			$sql .= " AND form_type_id = %d";
			$params [] = $data['form_type_id'];
		}

		$this->wpdb->query( $this->prepare( $sql, $params ) );
	}

	/**
	 * delete event log by id
	 *
	 * @param int $id
	 */
	public function delete_event( $id ) {
		$id = intval( $id );

		$this->wpdb->delete( tve_leads_table_name( 'event_log' ), array( 'id' => $id ) );
	}

	/**
	 * counts event logs based on a filter
	 *
	 * @param array $filter
	 *
	 * @return mixed
	 */
	public function count_events( $filter ) {
		$sql = "SELECT COUNT( DISTINCT log.id ) FROM {event_log} AS `log` WHERE 1 ";

		$params = array();

		//when the filter is_unique is set to 1, we don't filter by event type
		if ( ! empty( $filter['event_type'] ) && empty( $filter['is_unique'] ) ) {
			$sql .= "AND `event_type` = %d ";
			$params [] = $filter['event_type'];
		}

		if ( ! empty( $filter['main_group_id'] ) ) {
			$sql .= "AND `main_group_id` = %d ";
			$params [] = $filter['main_group_id'];
		}

		if ( ! empty( $filter['form_type_id'] ) ) {
			$sql .= "AND `form_type_id` = %d ";
			$params [] = $filter['form_type_id'];
		}

		if ( ! empty( $filter['variation_key'] ) ) {
			$sql .= "AND `variation_key` = %d ";
			$params [] = $filter['variation_key'];
		}

		if ( isset( $filter['archived_log'] ) ) {
			$sql .= "AND `archived` = %d ";
			$params [] = $filter['archived_log'];
		}

		if ( ! empty( $filter['is_unique'] ) ) {
			$sql .= "AND `is_unique` = %d ";
			$params [] = $filter['is_unique'];
		}

		if ( ! empty( $filter['date'] ) ) {
			switch ( $filter['date'] ) {
				case 'today':
					$midnight = current_time( 'Y-m-d 00:00:00' );
					$now      = current_time( 'Y-m-d H:i:s' );
					$sql .= " AND `date` BETWEEN '{$midnight}' AND '{$now}'";
					break;
			}
		}

		if ( isset( $filter['archived'] ) ) {
			$sql .= " AND `archived` = %d";
			$params [] = $filter['archived'];
		}

		return $this->wpdb->get_var( $this->prepare( $sql, $params ) );
	}

	/**
	 * Add contact info from a conversion for the contact view
	 *
	 * @param $log_id
	 * @param string $name
	 * @param string $email
	 * @param array $custom_fields
	 *
	 * @return false|int
	 */
	function tve_leads_register_contact( $log_id, $name = '', $email = '', $custom_fields = array() ) {
		$data = array(
			'log_id'        => $log_id,
			'name'          => $name,
			'email'         => $email,
			'date'          => date( 'Y-m-d H:i:s' ),
			'custom_fields' => json_encode( $custom_fields )
		);

		return $this->wpdb->insert( tve_leads_table_name( 'contacts' ), $data );
	}

	/**
	 * Returns a count of event_types from a group in a time period
	 *
	 * @param $filter Array of filters for the result
	 *
	 * @return Array with number of conversions per group_id in a period of time
	 */
	public function tve_leads_get_report_data_count_event_type( $filter ) {
		$date_interval = '';
		switch ( $filter['interval'] ) {
			case 'month':
				$date_interval = 'CONCAT(MONTHNAME(`log`.`date`)," ", YEAR(`log`.`date`)) as date_interval';
				break;
			case 'week':
				$year          = "IF( WEEKOFYEAR(`log`.`date`) = 1 AND MONTH(`log`.`date`) = 12, 1 + YEAR(`log`.`date`), YEAR(`log`.`date`) )";
				$date_interval = "CONCAT('Week ', WEEKOFYEAR(`log`.`date`), ', ', {$year}) as date_interval";
				break;
			case 'day':
				$date_interval = 'DATE(`log`.`date`) as date_interval';
				break;
		}

		$sql = "SELECT IFNULL(COUNT( DISTINCT log.id ), 0) AS log_count, event_type, log." . $filter['data_group'] . " AS data_group, {$date_interval} ";

		if ( ! empty( $filter['unique_email'] ) && $filter['unique_email'] == 1 ) {
			/* count if this email is added for the first time. if so, this is a lead, else it's just a simple conversion */
			$sql .= ", SUM( IF( t_log.id IS NOT NULL , 1, 0) ) AS leads ";
		}

		$sql .= " FROM " . tve_leads_table_name( 'event_log' ) . " AS `log` ";

		if ( ! empty( $filter['unique_email'] ) && $filter['unique_email'] == 1 ) {
			/* t_logs - temporary select to see if an email is added for the first time or not */
			$sql .= " LEFT JOIN (SELECT user, MIN(id) AS id FROM " . tve_leads_table_name( 'event_log' ) . " GROUP BY user) AS t_log ON log.user=t_log.user AND log.id=t_log.id ";
		}

		$sql .= "  WHERE 1 ";

		$params = array();

		if ( ! empty( $filter['event_type'] ) ) {
			$sql .= "AND `event_type` = %d ";
			$params [] = $filter['event_type'];
		}

		if ( ! empty( $filter['main_group_id'] ) && $filter['main_group_id'] > 0 ) {
			$sql .= "AND `main_group_id` = %d ";
			$params [] = $filter['main_group_id'];
		}

		if ( ! empty( $filter['form_type_id'] ) ) {
			$sql .= "AND `form_type_id` = %d ";
			$params [] = $filter['form_type_id'];
		}

		if ( ! empty( $filter['variation_key'] ) ) {
			$sql .= "AND `variation_key` = %d ";
			$params [] = $filter['variation_key'];
		}

		//we filter the log data and retrieve only from the specified data group, form_type or variation ids
		if ( ! empty( $filter['group_ids'] ) && ! empty( $filter['data_group'] ) ) {
			$sql .= "AND `" . $filter['data_group'] . "` IN (" . implode( ', ', $filter['group_ids'] ) . ") ";
		}

		if ( ! empty( $filter['start_date'] ) && ! empty( $filter['end_date'] ) ) {
			$filter['end_date'] .= ' 23:59:59';

			$sql .= "AND `date` BETWEEN %s AND %s ";
			$params [] = $filter['start_date'];
			$params [] = $filter['end_date'];
		}

		if ( ! empty( $filter['is_unique'] ) ) {
			$sql .= " AND ( is_unique = 1 OR event_type = %d ) ";
			$params [] = TVE_LEADS_CONVERSION;
		} else if ( is_array( $filter['group_by'] ) && in_array( 'event_type', $filter['group_by'] ) ) {
			$sql .= " AND ( event_type = %d OR event_type = %d ) ";
			$params [] = TVE_LEADS_UNIQUE_IMPRESSION;
			$params [] = TVE_LEADS_CONVERSION;
		}

		if ( isset( $filter['archived_log'] ) ) {
			$sql .= "AND `archived` = %d ";
			$params [] = $filter['archived_log'];
		}

		if ( ! empty( $filter['group_by'] ) && count( $filter['group_by'] ) > 0 ) {
			$sql .= 'GROUP BY ' . implode( ', ', $filter['group_by'] );
		}

		$sql .= ' ORDER BY `log`.`date` DESC';

		return $this->wpdb->get_results( $this->prepare( $sql, $params ) );
	}

	/**
	 * Returns date info from the log table
	 *
	 * @param $filter Array of filters for the result
	 * @param $return_count Boolean If true, this function will return only the count of the query
	 *
	 * @return Requested info from the log table
	 */
	public function tve_leads_get_log_data_info( $filter, $return_count = false ) {

		$sql    = "SELECT " .
		          ( $return_count ? "COUNT(*) AS count" : implode( ', ', $filter['select_fields'] ) ) .
		          " FROM " . tve_leads_table_name( 'event_log' ) . " AS `log` WHERE 1 ";
		$params = array();

		if ( ! empty( $filter['event_type'] ) ) {
			$sql .= "AND `event_type` = %d ";
			$params [] = $filter['event_type'];
		}

		if ( ! empty( $filter['main_group_id'] ) && $filter['main_group_id'] > 0 ) {
			$sql .= "AND `main_group_id` = %d ";
			$params [] = $filter['main_group_id'];
		}

		if ( ! empty( $filter['form_type_id'] ) ) {
			$sql .= "AND `form_type_id` = %d ";
			$params [] = $filter['form_type_id'];
		}

		if ( ! empty( $filter['variation_key'] ) ) {
			$sql .= "AND `variation_key` = %d ";
			$params [] = $filter['variation_key'];
		}

		if ( ! empty( $filter['start_date'] ) && ! empty( $filter['end_date'] ) ) {
			$filter['end_date'] .= ' 23:59:59';
			$sql .= "AND DATE(`date`) BETWEEN  %s AND %s ";
			$params [] = $filter['start_date'];
			$params [] = $filter['end_date'];
		}

		if ( isset( $filter['archived_log'] ) ) {
			$sql .= "AND `archived` = %d ";
			$params [] = $filter['archived_log'];
		}

		$sql .= ' ORDER BY `log`.`date` DESC';
		if ( ! $return_count && ! empty( $filter['itemsPerPage'] ) && ! empty( $filter['page'] ) ) {
			$sql .= " LIMIT %d, %d ";
			$params [] = $filter['itemsPerPage'] * ( $filter['page'] - 1 );
			$params [] = $filter['itemsPerPage'];
		}

		if ( $return_count == true ) {
			return $this->wpdb->get_row( $this->prepare( $sql, $params ) )->count;
		} else {
			return $this->wpdb->get_results( $this->prepare( $sql, $params ) );
		}
	}

	public function tve_leads_get_top_referring_links( $filter, $return_count = false ) {
		$sql = "SELECT COUNT(DISTINCT id) as conversions, referrer as referring_url
                FROM " . tve_leads_table_name( 'event_log' ) . "
                WHERE referrer!='' ";

		if ( ! empty( $filter['event_type'] ) ) {
			$sql .= "AND `event_type` = %d ";
			$params [] = $filter['event_type'];
		}

		if ( ! empty( $filter['main_group_id'] ) && $filter['main_group_id'] > 0 ) {
			$sql .= "AND `main_group_id` = %d ";
			$params [] = $filter['main_group_id'];
		}

		if ( ! empty( $filter['start_date'] ) && ! empty( $filter['end_date'] ) ) {
			$filter['end_date'] .= ' 23:59:59';
			$sql .= "AND DATE(`date`) BETWEEN  %s AND %s ";
			$params [] = $filter['start_date'];
			$params [] = $filter['end_date'];
		}

		if ( isset( $filter['archived_log'] ) ) {
			$sql .= "AND `archived` = %d ";
			$params [] = $filter['archived_log'];
		}

		$sql .= "GROUP BY referrer";
		if ( ! $return_count && ! empty( $filter['itemsPerPage'] ) && ! empty( $filter['page'] ) ) {
			$sql .= " ORDER BY conversions DESC";
			$sql .= " LIMIT %d, %d ";
			$params [] = $filter['itemsPerPage'] * ( $filter['page'] - 1 );
			$params [] = $filter['itemsPerPage'];
		}

		if ( $return_count ) {
			$sql = "SELECT COUNT(*) AS count FROM (" . $sql . " ) as rows ";
		}

		if ( $return_count == true ) {
			return $this->wpdb->get_row( $this->prepare( $sql, $params ) )->count;
		} else {
			return $this->wpdb->get_results( $this->prepare( $sql, $params ) );
		}
	}

	/**
	 * saves or creates a test
	 *
	 * @param array|stdClass $model the test to be saved
	 *
	 * @return bool|int
	 */
	public function save_test( $model ) {
		if ( ! is_array( $model ) ) {
			$model = (array) $model;
		}

		$_columns = array(
			'id',
			'test_type',
			'main_group_id',
			'date_added',
			'date_started',
			'date_completed',
			'title',
			'notes',
			'auto_win_enabled',
			'auto_win_min_conversions',
			'auto_win_min_duration',
			'auto_win_chance_original',
			'status'
		);

		foreach ( $model as $key => $data ) {
			if ( ! in_array( $key, $_columns ) ) {
				unset( $model[ $key ] );
			}
		}

		if ( ! empty( $model['id'] ) ) {
			$update_rows = $this->wpdb->update( tve_leads_table_name( 'split_test' ), $model, array( 'id' => $model['id'] ) );

			return $update_rows !== false;
		}
		$this->wpdb->insert( tve_leads_table_name( 'split_test' ), $model );
		$id = $this->wpdb->insert_id;

		return $id;
	}

	/**
	 * Get test model based on filter
	 *
	 * @param $filter
	 *
	 * @return mixed
	 */
	public function tve_leads_get_test( $filter ) {
		$sql = "SELECT * FROM " . tve_leads_table_name( 'split_test' ) . " WHERE 1 ";

		if ( ! empty( $filter['ID'] ) ) {
			$sql .= "AND `id` = %d ";
			$params [] = $filter['ID'];
		}

		if ( ! empty( $filter['test_type'] ) ) {
			$sql .= "AND `test_type` = %d ";
			$params [] = $filter['test_type'];
		}

		if ( ! empty( $filter['main_group_id'] ) && $filter['main_group_id'] > 0 ) {
			$sql .= "AND `main_group_id` = %d ";
			$params [] = $filter['main_group_id'];
		}

		if ( ! empty( $filter['status'] ) ) {
			$sql .= "AND `status` = %s ";
			$params [] = $filter['status'];
		}

		$sql .= " LIMIT 1";

		return $this->wpdb->get_row( $this->prepare( $sql, $params ) );
	}

	public function save_test_item( $model ) {
		if ( ! empty( $model['id'] ) ) {
			$toUpdate = array(
				'id'            => $model['id'],
				'test_id'       => isset( $model['test_id'] ) ? $model['test_id'] : '',
				'main_group_id' => isset( $model['main_group_id'] ) ? $model['main_group_id'] : '',
				'form_type_id'  => isset( $model['form_type_id'] ) ? $model['form_type_id'] : '',
				'variation_key' => isset( $model['variation_key'] ) ? $model['variation_key'] : '',
				'is_control'    => isset( $model['is_control'] ) ? $model['is_control'] : '',
				'is_winner'     => isset( $model['is_winner'] ) ? $model['is_winner'] : 0,
				'impressions'   => isset( $model['impressions'] ) ? $model['impressions'] : 0,
				'conversions'   => isset( $model['conversions'] ) ? $model['conversions'] : 0,
			);
			$rows     = $this->wpdb->update( tve_leads_table_name( 'split_test_items' ), $toUpdate, array( 'id' => $toUpdate['id'] ) );

			return $rows !== false;
		}
		$this->wpdb->insert( tve_leads_table_name( 'split_test_items' ), $model );
		$id = $this->wpdb->insert_id;

		return $id;
	}

	public function get_test_items( $filters ) {
		$sql = "SELECT * FROM " . tve_leads_table_name( 'split_test_items' ) . " WHERE 1";

		$params = array();

		if ( ! empty( $filters['form_type_id'] ) ) {
			$sql .= " AND form_type_id = '%d' ";
			$params[] = $filters['form_type_id'];
		}

		if ( ! empty( $filters['test_id'] ) ) {
			$sql .= " AND `test_id` = %d ";
			$params [] = $filters['test_id'];
		}

		if ( ! empty( $filters['main_group_id'] ) ) {
			$sql .= " AND main_group_id = '%d' ";
			$params[] = $filters['main_group_id'];
		}

		if ( isset( $filters['active'] ) && is_numeric( $filters['active'] ) && in_array( $filters['active'], array( 0, 1 ) ) ) {
			$sql .= " AND active = '%d' ";
			$params[] = $filters['active'];
		}

		//make sure that the control is the first one.
		$sql .= " ORDER BY `is_control` DESC, id ASC";

		//TODO: implement more filters if applied

		return $this->wpdb->get_results( $this->prepare( $sql, $params ) );
	}

	public function tve_leads_get_tests( $filters ) {
		$sql = "SELECT * FROM " . tve_leads_table_name( 'split_test' ) . " WHERE 1";

		$params = array();

		if ( ! empty( $filters['test_type'] ) ) {
			$sql .= " AND test_type = '%d'";
			$params[] = $filters['test_type'];
		}

		if ( ! empty( $filters['main_group_id'] ) && $filters['main_group_id'] > 0 ) {
			$sql .= " AND main_group_id = '%d'";
			$params[] = $filters['main_group_id'];
		}

		if ( ! empty( $filters['status'] ) ) {
			$sql .= " AND status = '%s'";
			$params[] = $filters['status'];
		}

		if ( isset( $filters['auto_win_enabled'] ) && is_numeric( $filters['auto_win_enabled'] ) && in_array( $filters['auto_win_enabled'], array( 0, 1 ) ) ) {
			$sql .= " AND auto_win_enabled = '%s'";
			$params[] = $filters['auto_win_enabled'];
		}

		if ( ! empty( $filters['start_date'] ) && ! empty( $filters['end_date'] ) ) {
			$sql .= " AND DATE(`date_started`) BETWEEN  %s AND %s ";
			$params [] = $filters['start_date'];
			$params [] = $filters['end_date'];
		}

		return $this->wpdb->get_results( $this->prepare( $sql, $params ) );

	}

	/**
	 * Get top tracking links for the Lead Source Report
	 *
	 * @param $filter
	 *
	 * @return mixed
	 */
	public function tve_leads_get_tracking_links( $filter, $return_count = false ) {
		if ( ! empty( $filter['tracking_type'] ) ) {
			switch ( $filter['tracking_type'] ) {
				case 'source':
					$select   = ' utm_source AS source ';
					$group_by = 'utm_source';
					break;
				case 'campaign':
					$select   = ' utm_campaign AS name ';
					$group_by = 'utm_campaign';
					break;
				case 'medium':
					$select   = 'utm_medium AS medium ';
					$group_by = 'utm_medium';
					break;
				case 'all':
				default:
					$select   = ' utm_source AS source, utm_campaign AS name, utm_medium AS medium ';
					$group_by = 'utm_campaign, utm_medium, utm_source';
			}
		} else {
			$select   = ' utm_source AS source, utm_campaign AS name, utm_medium AS medium ';
			$group_by = 'utm_campaign, utm_medium, utm_source';
		}

		$sql = "SELECT COUNT(DISTINCT id) AS conversions, " . $select .
		       "FROM " . tve_leads_table_name( 'event_log' ) . " WHERE (utm_source!='' OR utm_campaign!='' OR utm_medium!='') ";

		if ( ! empty( $filter['event_type'] ) ) {
			$sql .= "AND `event_type` = %d ";
			$params [] = $filter['event_type'];
		}

		if ( ! empty( $filter['main_group_id'] ) && $filter['main_group_id'] > 0 ) {
			$sql .= "AND `main_group_id` = %d ";
			$params [] = $filter['main_group_id'];
		}

		if ( ! empty( $filter['start_date'] ) && ! empty( $filter['end_date'] ) ) {
			$filter['end_date'] .= ' 23:59:59';
			$sql .= "AND DATE(`date`) BETWEEN  %s AND %s ";
			$params [] = $filter['start_date'];
			$params [] = $filter['end_date'];
		}

		if ( isset( $filter['archived_log'] ) ) {
			$sql .= "AND `archived` = %d ";
			$params [] = $filter['archived_log'];
		}

		$sql .= "GROUP BY " . $group_by;
		if ( ! $return_count && ! empty( $filter['itemsPerPage'] ) && ! empty( $filter['page'] ) ) {
			$sql .= " ORDER BY conversions DESC";
			$sql .= " LIMIT %d, %d ";
			$params [] = $filter['itemsPerPage'] * ( $filter['page'] - 1 );
			$params [] = $filter['itemsPerPage'];
		}

		if ( $return_count ) {
			$sql = "SELECT COUNT(*) AS count FROM (" . $sql . " ) as rows ";
		}

		if ( $return_count == true ) {
			return $this->wpdb->get_row( $this->prepare( $sql, $params ) )->count;
		} else {
			return $this->wpdb->get_results( $this->prepare( $sql, $params ) );
		}
	}

	/**
	 * Get source data for each conversion.
	 * We also count if this email is added for the first time. If so, this is a lead, else it's just a simple conversion
	 *
	 * @param $filter
	 * @param bool|false $return_count
	 *
	 * @return array|null|object
	 */
	function tve_leads_get_lead_source_data( $filter, $return_count = false ) {
		/* Screen type can be null for the conversions that happened before the release of this feature. We will mark the source as Unknown */
		$sql = "SELECT IF(screen_type IS NULL, 0, screen_type) AS screen_type, IF(screen_id IS NULL, 0,screen_id ) AS screen_id,
                    SUM(IF(event_type=" . TVE_LEADS_CONVERSION . ",1,0)) AS conversions,
                    SUM(IF(event_type=" . TVE_LEADS_UNIQUE_IMPRESSION . ",1,0)) AS impressions,
                    SUM(IF(event_type=" . TVE_LEADS_CONVERSION . ",1,0))/SUM(IF(event_type=" . TVE_LEADS_UNIQUE_IMPRESSION . ",1,0)) AS conversion_rate,
                    SUM( IF( t_log.id IS NOT NULL , 1, 0) ) AS leads
                FROM " . tve_leads_table_name( 'event_log' ) . " logs
                LEFT JOIN (SELECT user, MIN(id) AS id FROM " . tve_leads_table_name( 'event_log' ) . " GROUP BY user) AS t_log ON logs.user=t_log.user AND logs.id=t_log.id
                WHERE 1 ";

		$params = array();
		if ( ! empty( $filter['main_group_id'] ) && $filter['main_group_id'] > 0 ) {
			$sql .= "AND `main_group_id` = %d ";
			$params [] = $filter['main_group_id'];
		}

		if ( ! empty( $filter['source_type'] ) && $filter['source_type'] > 0 ) {
			$sql .= "AND `screen_type` = %d ";
			$params [] = $filter['source_type'];
		}

		if ( ! empty( $filter['start_date'] ) && ! empty( $filter['end_date'] ) ) {
			$filter['end_date'] .= ' 23:59:59';
			$sql .= "AND DATE(`date`) BETWEEN  %s AND %s ";
			$params [] = $filter['start_date'];
			$params [] = $filter['end_date'];
		}

		if ( isset( $filter['archived_log'] ) ) {
			$sql .= "AND `archived` = %d ";
			$params [] = $filter['archived_log'];
		}

		$sql .= "GROUP BY screen_type, screen_id";

		if ( ! empty( $filter['order_by'] ) && ! empty( $filter['order_dir'] ) ) {
			$sql .= " ORDER BY " . $filter['order_by'] . " " . $filter['order_dir'] . " ";
		}

		if ( ! $return_count && ! empty( $filter['itemsPerPage'] ) && ! empty( $filter['page'] ) ) {
			$sql .= " LIMIT %d, %d ";
			$params [] = $filter['itemsPerPage'] * ( $filter['page'] - 1 );
			$params [] = $filter['itemsPerPage'];
		}

		return $this->wpdb->get_results( $this->prepare( $sql, $params ) );
	}

	/**
	 * check if the identified variation is included in a running test
	 *
	 * @param int $form_type_or_shortcode_id
	 * @param int $variation_key
	 *
	 * @return array
	 */
	public function check_if_test_exists( $form_type_or_shortcode_id, $variation_key ) {
		$sql = "SELECT COUNT(ti.id) FROM {split_test_items} AS ti
            INNER JOIN {split_test} AS t ON t.id = ti.test_id
            WHERE `form_type_id` = %d AND variation_key = %d AND t.status = %s";

		$params = array(
			$form_type_or_shortcode_id,
			$variation_key,
			TVE_LEADS_TEST_STATUS_RUNNING
		);

		return $this->wpdb->get_var( $this->prepare( $sql, $params ) );
	}

	/**
	 * get a form variation by key (primary id)
	 * this also handles un serialization of any data that looks serialized
	 *
	 * @param int $key
	 *
	 * @return mixed
	 */
	public function get_form_variation( $key ) {
		$sql = "SELECT * FROM {form_variations} WHERE `key` = %d";

		$variation = $this->wpdb->get_row( $this->prepare( $sql, array( $key ) ), ARRAY_A );

		if ( empty( $variation ) ) {
			return null;
		}

		$variation = $this->_unserialize_fields( $variation, array( 'trigger_config', 'tcb_fields' ) );

		/* assign each field from the tcb_fields in the main variation array, so they can be accessed directly */
		foreach ( $variation['tcb_fields'] as $k => $v ) {
			$variation[ $k ] = $v;
		}

		return $variation;
	}

	/**
	 * @param array $filters should contain at least post_parent
	 * @param bool $return_count if true, returns the count of the variations matching the filters
	 *
	 * @return array the list of form variations matching the filters
	 */
	public function get_form_variations( $filters = array(), $return_count = false ) {
		$select = $return_count ? 'COUNT( `key` )' : '{form_variations}.*';
		$sql    = "SELECT {$select} FROM {form_variations} ";
		$params = array();

		if ( ! empty( $filters['active_for_test_id'] ) ) {
			$sql .= ' INNER JOIN {split_test_items} ON ( {form_variations}.key = {split_test_items}.variation_key AND {split_test_items}.active = 1 AND {split_test_items}.test_id = %s )';
			$params [] = $filters['active_for_test_id'];
		}

		$sql .= " WHERE 1";

		if ( ! empty( $filters['post_parent'] ) ) {
			$sql .= " AND `post_parent` = %d";
			$params [] = $filters['post_parent'];
		}

		if ( ! empty( $filters['post_status'] ) ) {
			if ( ! is_array( $filters['post_status'] ) ) {
				$filters['post_status'] = array( $filters['post_status'] );
			}
			$sql .= " AND ( ";
			foreach ( $filters['post_status'] as $post_status ) {
				$sql .= isset( $first ) ? " OR " : "";
				$sql .= "`post_status` = %s";
				$params [] = $post_status;
				$first     = true;
			}
			$sql .= " )";
		}

		if ( ! empty( $filters['parent_id'] ) ) {
			$sql .= " AND `parent_id` = %d";
			$params [] = $filters['parent_id'];
		} else {
			$sql .= " AND `parent_id` = 0";
		}

		if ( ! empty( $filters['order'] ) ) {
			list( $col, $dir ) = explode( ' ', $filters['order'] );
			if ( strpos( $col, '.' ) ) {
				list( $table, $col ) = explode( '.', $col );
				$table = $table ? "`" . str_replace( '`', '', '{' . $table . '}' ) . "`" : '`{form_variations}`';
			} else {
				$table = '{form_variations}';
			}
			$col = "`" . str_replace( '`', '', $col ) . "`";
			$sql .= " ORDER BY {$table}.{$col} {$dir}";
		}

		if ( ! empty( $filters['limit'] ) ) {
			$sql .= " LIMIT " . ( ! empty( $filters['offset'] ) ? intval( $filters['offset'] ) . ',' : '' );
			$sql .= intval( $filters['limit'] );
		}

		if ( $return_count ) {
			return $this->wpdb->get_var( $this->prepare( $sql, $params ) );
		}

		$results = $this->wpdb->get_results( $this->prepare( $sql, $params ), ARRAY_A );
		if ( empty( $results ) ) {
			return array();
		}

		foreach ( $results as & $item ) {
			$item = $this->_unserialize_fields( $item, array( 'trigger_config', 'tcb_fields' ) );
			/* assign each field from the tcb_fields in the main variation array, so they can be accessed directly */
			foreach ( $item['tcb_fields'] as $k => $v ) {
				$item[ $k ] = $v;
			}
		}

		return $results;
	}

	/**
	 * serialize everything that's needed and save a form variation
	 *
	 * @param array $data the variation model data
	 *
	 * @return array the inserted variation
	 */
	public function save_form_variation( $data ) {
		$columns = array(
			'key',
			'date_added',
			'date_modified',
			'post_parent',
			'post_status',
			'post_title',
			'content',
			'trigger',
			'trigger_config',
			'display_frequency',
			'position',
			'display_animation',
			'tcb_fields',
			'form_state',
			'parent_id',
			'state_order',
			'cache_impressions',
			'cache_conversions'
		);

		if ( is_array( $data['trigger_config'] ) ) {
			$data['trigger_config'] = serialize( $data['trigger_config'] );
		}
		if ( is_array( $data['tcb_fields'] ) ) {
			$data['tcb_fields'] = serialize( $data['tcb_fields'] );
		}

		foreach ( $data as $key => $value ) {
			if ( ! in_array( $key, $columns ) ) {
				unset( $data[ $key ] );
			}
		}
		$data['content'] = wp_unslash( $data['content'] );
		if ( empty( $data['key'] ) ) {
			unset( $data['key'] );
			$data['date_added'] = $data['date_modified'] = date( 'Y-m-d H:i:s' );
			$this->wpdb->insert( tve_leads_table_name( 'form_variations' ), $data );
			$data['key'] = $this->wpdb->insert_id;
		} else {
			$data['date_modified'] = date( 'Y-m-d H:i:s' );
			$this->wpdb->update( tve_leads_table_name( 'form_variations' ), $data, array( 'key' => $data['key'] ) );
		}

		return tve_leads_get_form_variation( null, $data['key'] );
	}

	/**
	 * mass update a field in a table
	 *
	 * @param string $table the table name
	 * @param string $field the field name needed to be updated
	 * @param mixed $field_value the new field value
	 * @param array $keys what IDs to update
	 * @param string $key_field the name of the ID field
	 *
	 * @return int
	 */
	public function mass_update_field( $table, $field, $field_value, $keys = array(), $key_field = 'id' ) {
		if ( empty( $keys ) ) {
			return 0;
		}

		$table     = '{' . $table . '}';
		$field     = '`' . $field . '`';
		$sql       = "UPDATE {$table} SET {$field} = %s WHERE 1";
		$params [] = $field_value;

		$or = '';
		foreach ( $keys as $key ) {
			$or .= isset( $first ) ? " OR " : "";
			$or .= "`{$key_field}` = %s";
			$params [] = $key;
			$first     = true;
		}
		$sql .= $or ? " AND ({$or})" : "";

		return $this->wpdb->query( $this->prepare( $sql, $params ) );
	}

	/**
	 * mass update ALL entries from a table, setting a list of fields to corresponding values
	 *
	 * @param string $table
	 * @param array $column_values associative array with column_name => value
	 *
	 *
	 */
	public function update_all_fields( $table, $column_values ) {
		$table = '{' . $table . '}';
		$sql   = "UPDATE {$table} SET";

		$params = array();

		if ( empty( $column_values ) ) {
			return 0;
		}

		foreach ( $column_values as $field => $value ) {
			$sql .= "`{$field}` = ";

			if ( is_null( $value ) ) {
				$sql .= 'NULL';
			} else {
				$sql .= '%s';
				$params [] = $value;
			}

			$sql .= ', ';
		}

		$sql = rtrim( $sql, ', ' );

		return $this->wpdb->query( $this->prepare( $sql, $params ) );
	}

	/**
	 * archive event logs based on a filter
	 *
	 * @param array $filter
	 *
	 * @return int|false number of affected entries or false for error or invalid parameters
	 */
	public function archive_logs( $filter = array() ) {
		/* prevent accidental updates */
		if ( empty( $filter ) ) {
			return false;
		}

		$sql    = "UPDATE {event_log} SET `archived` = 1 WHERE 1 AND ";
		$params = array();

		if ( ! empty( $filter['variation_key'] ) ) {
			$sql .= " `variation_key` = %d";
			$params [] = $filter['variation_key'];
		}

		if ( ! empty( $filter['form_type_id'] ) ) {
			$sql .= " `form_type_id` = %d";
			$params [] = $filter['form_type_id'];
		}

		if ( ! empty( $filter['main_group_id'] ) ) {
			$sql .= " `main_group_id` = %d";
			$params [] = $filter['main_group_id'];
		}

		if ( empty( $params ) ) {
			return false;
		}

		return $this->wpdb->query( $this->prepare( $sql, $params ) );
	}

	/**
	 * Delete display settings based on $args
	 *
	 * @param $args
	 *
	 * @return false|int number of rows affected
	 */
	public function delete_display_settings( $args ) {
		return $this->wpdb->delete( tve_leads_table_name( 'group_options' ), $args );
	}

	/**
	 * Check if a group has display settings
	 *
	 * @param $group_id
	 *
	 * @return mixed
	 */
	public function has_display_settings( $group_id ) {
		return $this->wpdb->get_row( $this->prepare( "SELECT id FROM {group_options} WHERE `group` = %d", array( $group_id ) ) );
	}

	/**
	 * Delete logs based on $args
	 *
	 * @param $args
	 *
	 * @return false|int number of rows affected
	 */
	public function delete_logs( $args ) {
		return $this->wpdb->delete( tve_leads_table_name( 'event_log' ), $args );
	}

	/**
	 * Delete tests base on $args
	 *
	 * @param array $args used in where clause
	 * @param $filters array
	 *
	 * @return false|int number of rows affected
	 */
	public function delete_tests( $args, $filters = array() ) {
		$defaults = array(
			'delete_items' => false
		);

		$filters = array_merge( $defaults, $filters );

		if ( ! empty( $filters['delete_items'] ) ) {
			$this->delete_test_items( $args );
		}

		return $this->wpdb->delete( tve_leads_table_name( 'split_test' ), $args );
	}

	/**
	 * Delete test items based on $args
	 *
	 * @param $args
	 *
	 * @return false|int number of rows affected
	 */
	public function delete_test_items( $args ) {
		return $this->wpdb->delete( tve_leads_table_name( 'split_test_items' ), $args );
	}

	/**
	 * @param $filters
	 *
	 * @return int the count matching the filters
	 */
	public function count_form_variations( $filters ) {
		unset( $filters['order'], $filters['limit'] );

		return $this->get_form_variations( $filters, true );
	}

	/**
	 *
	 * increment each state order for variations having the same parent ID and state_order >= $new_order
	 *
	 * @param int $parent_id
	 * @param int $new_order
	 */
	public function variation_increment_state_order( $parent_id, $new_order ) {
		$sql    = "UPDATE {form_variations} SET state_order = state_order + 1 WHERE parent_id = %d AND state_order >= %d";
		$params = array(
			$parent_id,
			$new_order
		);

		$this->wpdb->query( $this->prepare( $sql, $params ) );
	}

	/**
	 * completely delete a form variation
	 *
	 * @param int $variation_key
	 *
	 * @return bool
	 */
	public function delete_form_variation( $variation_key ) {
		$sql = "DELETE FROM {form_variations} WHERE `key` = %d";

		return $this->wpdb->query( $this->prepare( $sql, array( $variation_key ) ) );
	}

	/**
	 * get the highest state_order from a set of variation states (children of $parent_id)
	 *
	 * @param int $parent_id
	 *
	 * @return null|string
	 */
	public function variation_get_max_state_order( $parent_id ) {
		$sql = "SELECT MAX( state_order ) FROM {form_variations} WHERE parent_id = %d";

		return $this->wpdb->get_var( $this->prepare( $sql, array( $parent_id ) ) );
	}

	/**
	 * find the already_subscribed state for a variation
	 *
	 * @param int $parent_id
	 *
	 * @return array|null
	 */
	public function get_variation_already_subscribed_state( $parent_id ) {
		$sql   = "SELECT * FROM {form_variations} WHERE parent_id = %d AND `form_state` = %s";
		$state = $this->wpdb->get_row( $this->prepare( $sql, array( $parent_id, 'already_subscribed' ) ), ARRAY_A );

		if ( empty( $state ) ) {
			return null;
		}

		$state = $this->_unserialize_fields( $state, array( 'trigger_config', 'tcb_fields' ) );

		/* assign each field from the tcb_fields in the main variation array, so they can be accessed directly */
		foreach ( $state['tcb_fields'] as $k => $v ) {
			$state[ $k ] = $v;
		}

		/* return empty content when the form is hidden */
		if ( tve_leads_check_variation_visibility( $state ) ) {
			$state['content'] = '';
		}

		return $state;
	}

	/**
	 * completely delete all child states for a variation
	 *
	 * @param int $variation_key
	 * @param array $where extra where conditions
	 *
	 * @return bool
	 */
	public function variation_delete_states( $variation_key, $where = array() ) {
		$sql    = "DELETE FROM {form_variations} WHERE `parent_id` = %d";
		$params = array( $variation_key );

		foreach ( $where as $field => $v ) {
			$sql .= " AND `{$field}` = %s";
			$params [] = $v;
		}

		return $this->wpdb->query( $this->prepare( $sql, $params ) );
	}

	/**
	 * count event logs that are recorded as "non-unique impressions"
	 */
	public function count_non_unique_impressions() {
		return $this->count_events( array(
			'event_type' => TVE_LEADS_IMPRESSION
		) );
	}

	/**
	 *
	 * update some particular fields for a variation
	 *
	 * @param array|int|string $variation variation or variation key
	 *
	 * @param array $data key => value pairs with data to be saved for the variation
	 */
	public function update_variation_fields( $variation, $data = array() ) {
		if ( is_array( $variation ) ) {
			if ( empty( $variation['key'] ) ) {
				return false;
			}
			$variation_key = $variation['key'];
		} else {
			$variation_key = $variation;
		}

		return $this->wpdb->update( tve_leads_table_name( 'form_variations' ), $data, array( 'key' => $variation_key ) );

	}

	/**
	 * Get one contact from DB. The search should be done by id, but we can use this function to see if we have or not a contact by another field.
	 *
	 * @param $field
	 * @param $value
	 *
	 * @return array|null|object|void
	 */
	public function tve_get_contact( $field, $value ) {
		$sql    = '
            SELECT  `contacts`.name, `contacts`.email, `contacts`.custom_fields, `contacts`.date, `posts`.post_title as source
            FROM ' . tve_leads_table_name( 'contacts' ) . ' AS `contacts`
            LEFT JOIN ' . tve_leads_table_name( 'event_log' ) . ' `logs` ON `contacts`.log_id=`logs`.id
            LEFT JOIN ' . $this->wpdb->posts . ' AS `posts` ON `logs`.main_group_id=`posts`.ID
            WHERE `contacts`.' . $field . '=%s';
		$params = array( $value );

		return $this->wpdb->get_row( $this->prepare( $sql, $params ) );
	}

	/**
	 *  Get the contacts for contact storage download manager
	 *
	 * @param $source string
	 * @param $filters array
	 *
	 * @return mixed
	 */
	public function tve_leads_get_contacts_stored( $source, $filters = array() ) {
		$table_name = tve_leads_table_name( 'contacts' );
		$sql        = "SELECT * FROM {$table_name} ";
		$params     = array();
		switch ( $source ) {
			case 'last_download':
				$latest_download = $this->tve_leads_get_contacts_last_download_date();
				$sql .= " WHERE date > %s ORDER BY date";
				$params[] = $latest_download->date;

				break;
			case 'current_report':
				if ( $filters['source'] > 0 ) {
					$sql .= " AS `contacts` JOIN " . tve_leads_table_name( 'event_log' ) . " `logs` ON `logs`.id=`contacts`.`log_id` WHERE `logs`.`main_group_id`=%s ";
					$params[] = $filters['source'];
				} else {
					$sql .= " AS `contacts` WHERE 1";
				}

				if ( ! empty( $filters['start_date'] ) && ! empty( $filters['end_date'] ) ) {
					$sql .= " AND `contacts`.`date` BETWEEN %s AND %s ";
					$params [] = $filters['start_date'];
					$params [] = $filters['end_date'] . ' 23:59:59';
				}

				$sql .= " ORDER BY `contacts`.date DESC";

				break;
			case 'all':
			default:
				$sql .= " ORDER BY date DESC;";
		}

		ini_set( 'memory_limit', '1024M' );

		return $this->wpdb->get_results( $this->prepare( $sql, $params ) );
	}

	/**
	 *  Get the the date of the last download
	 * @return mixed
	 */

	public function tve_leads_get_contacts_last_download_date() {
		$table_name = tve_leads_table_name( 'contact_download' );
		$sql        = "SELECT MAX(date) AS date FROM {$table_name}";

		return $this->wpdb->get_row( $sql );
	}

	/**
	 * Insert new download for the contacts
	 *
	 * @param $source
	 * @param $file_url
	 * @param $params array
	 *
	 * @return int
	 */
	function tve_leads_write_contact_download( $source, $file_url, $params = array() ) {
		switch ( $source ) {
			case 'all':
				$data['type'] = __( 'All Contacts in Database', 'thrive-leads' );
				break;
			case 'last_download':
				$latest_download = $this->tve_leads_get_contacts_last_download_date();
				$data['type']    = __( 'All Contacts since ', 'thrive-leads' ) . $latest_download->date;
				break;
			case 'current_report':
				$source_name = $params['source'] > 0 ? __( 'in ', 'thrive-leads' ) . get_the_title( $params['source'] ) : '';

				$data['type'] = __( 'All Contacts ', 'thrive-leads' ) . $source_name . ' ' .
				                __( 'from', 'thrive-leads' ) . ' ' . date( 'd M, Y', strtotime( $params['start_date'] ) ) . ' ' .
				                __( 'to', 'thrive-leads' ) . ' ' . date( 'd M, Y', strtotime( $params['end_date'] ) );

				break;
		}

		$data['status']        = 'pending';
		$data['date']          = date( 'Y-m-d H:i:s' );
		$data['download_link'] = $file_url;

		$this->wpdb->insert( tve_leads_table_name( 'contact_download' ), $data );

		return $this->wpdb->insert_id;
	}

	/**
	 * Update download status
	 *
	 * @param $id
	 * @param string $status
	 *
	 * @return false|int
	 */
	function tve_leads_update_contacts_download_status( $id, $status = 'pending' ) {
		return $this->wpdb->update( tve_leads_table_name( 'contact_download' ), array( 'status' => $status ), array( 'id' => $id ) );
	}

	/**
	 * Gets the list of downloads that have been generated
	 * @return array
	 */
	public function tve_leads_get_download_list() {
		$table_name = tve_leads_table_name( 'contact_download' );
		$sql        = "SELECT * FROM {$table_name} ORDER BY date DESC";

		$downloads = $this->wpdb->get_results( $sql );

		foreach ( $downloads as $d ) {
			/* If 10 minutes have passed since the download was started and the download still has status pending, then something wrong happened so we change the status. */
			if ( time() - strtotime( $d->date ) > 60 * 10 && $d->status == 'pending' ) {
				$this->tve_leads_update_contacts_download_status( $d->id, 'error' );
				$d->status = 'error';
			}

			switch ( $d->status ) {
				case 'complete':
					$d->status_title = __( 'Complete', 'thrive-leads' );
					break;
				case 'pending':
					$d->status_title = __( 'Pending', 'thrive-leads' );
					break;
				case 'error':
					$d->status_title = __( 'An error occurred while trying to prepare the download.', 'thrive-leads' );
					break;
			}
		}

		return $downloads;
	}

	/**
	 * Delete a download item
	 *
	 * @param $id
	 *
	 * @return false|int
	 */
	public function tve_leads_delete_download_item( $id ) {
		return $this->wpdb->delete( tve_leads_table_name( 'contact_download' ), array( 'id' => $id ) );
	}

	/*
	 * Get the ids for the archived or deleted variations
	 * @return array
	 */
	public function get_variation_ids() {
		$table_name = tve_leads_table_name( 'form_variations' );
		$sql        = "SELECT `key` FROM {$table_name} WHERE `post_status` = 'trash' OR `post_status` = 'archived'";

		return $this->wpdb->get_col( $sql );
	}

	/**
	 * Get the ids for the archived split tests
	 * @return array
	 */
	public function get_split_test_ids() {
		$table_name = tve_leads_table_name( 'split_test' );
		$sql        = "SELECT `id` FROM {$table_name} WHERE `status` = 'archived'";

		return $this->wpdb->get_col( $sql );
	}

	/**
	 * Delete the variation logs
	 *
	 * @param $ids
	 *
	 * @return mixed
	 */
	public function delete_conversion_logs( $ids ) {
		$table_name = tve_leads_table_name( 'event_log' );
		$v          = implode( "', '", $ids );
		$sql        = "DELETE FROM {$table_name} WHERE `variation_key` IN ('{$v}') OR archived = 1";

		return $this->wpdb->query( $sql );
	}

	/**
	 * Delete split test and split test items
	 *
	 * @param $ids
	 *
	 * @return bool
	 */
	public function delete_split_logs( $ids ) {
		$table_name   = tve_leads_table_name( 'split_test' );
		$table_name_i = tve_leads_table_name( 'split_test_items' );
		$v            = implode( "', '", $ids );

		$sql   = "DELETE FROM {$table_name} WHERE `id` IN ('{$v}')";
		$sql_i = "DELETE FROM {$table_name_i} WHERE `test_id` IN ('{$v}')";
		$s     = $this->wpdb->query( $sql );
		$i     = $this->wpdb->query( $sql_i );

		return $s + $i;
	}

	/**
	 * get the list of saved templates to be used in display settings
	 *
	 * @return array
	 */
	public function get_display_settings_templates() {
		$sql = 'SELECT * FROM {saved_group_options} ORDER BY `name` ASC';

		return $this->wpdb->get_results( $this->prepare( $sql, array() ) );
	}

	/**
	 * get display settings template by ID
	 *
	 * @param string $id
	 */
	public function get_display_settings_template( $id ) {
		$sql    = 'SELECT * FROM {saved_group_options} WHERE id = %d';
		$params = array( $id );

		return $this->wpdb->get_row( $this->prepare( $sql, $params ) );
	}

	/**
	 * Stops a test item
	 *
	 * @param $item_id
	 *
	 * @return false|int
	 */
	public function stop_test_item( $item_id ) {

		$sql    = 'UPDATE {split_test_items} SET active = 0, stopped_date ="' . date( 'Y-m-d H:i:s' ) . '" WHERE id = %d';
		$params = array( $item_id );

		return $this->wpdb->query( $this->prepare( $sql, $params ) );
	}

	/**
	 * @param $test_id
	 *
	 * @return array|null|object|void
	 */
	public function get_total_test_data( $test_id ) {

		$sql    = 'SELECT SUM(conversions) as total_conversions FROM {split_test_items} WHERE test_id = %d LIMIT 1';
		$params = array( $test_id );

		return $this->wpdb->get_row( $this->prepare( $sql, $params ) );
	}
}

$tvedb = new Thrive_Leads_DB();