<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Thrive_Leads_Contacts_List extends WP_List_Table {

	protected $table_name;
	protected $per_page = 20;
	protected $connections;
	protected $wpdb;

	public function __construct( $args ) {
		if ( ! is_array( $args ) ) {
			$args = array();
		}

		global $wpdb;
		$this->wpdb = $wpdb;

		$this->table_name = $wpdb->prefix . 'tve_leads_contacts';

		$args['plural'] = ! empty( $args['plural'] ) ? $args['plural'] : 'contacts';

		parent::__construct( $args );
	}

	public function get_columns() {
		$columns = array(
			'cb'            => '<input type="checkbox" />',
			'name'          => __( 'Name', "thrive-leads" ),
			'email'         => __( 'Email', "thrive-leads" ),
			'date'          => __( 'Date and time', "thrive-leads" ),
			'custom_fields' => __( 'Custom Data', "thrive-leads" ),
			'actions'       => 'Actions',
		);

		return $columns;
	}

	/**
	 * Columns that will have sortable links in table's header
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$columns = array(
			'date' => array( 'date', 'ASC' ),
			'name' => array( 'name', 'ASC' ),
		);

		return $columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Delete', "thrive-leads" )
		);

		return $actions;
	}

	public function prepare_items() {
		/* Process bulk action */
		$this->process_bulk_action();

		$this->per_page = $this->getContactFilter( 'per-page' );

		//get total items
		$total_items = $this->get_contacts();

		//init current page
		$current_page = ! empty( $_REQUEST['paged'] ) ? (int) $_REQUEST['paged'] : 1;
		$current_page = $current_page >= 1 ? $current_page : 1;

		//calculate total pages
		$total_pages = ceil( $total_items / $this->per_page );

		//calculate the offset from where to begin the query
		$offset      = ( $current_page - 1 ) * $this->per_page;
		$this->items = $this->get_contacts( false, $offset );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page'    => $this->per_page
		) );

		//init header columns
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
	}

	/**
	 * Get contacts from the database for the table
	 *
	 * @param bool|true $count
	 * @param int $offset
	 *
	 * @return array|false|int|null|object
	 */
	private function get_contacts( $count = true, $offset = 0 ) {
		$sql       = "SELECT " . ( $count ? "COUNT(*)" : "*" ) . " FROM {$this->table_name} `contacts` ";
		$post_data = $_REQUEST;

		$start_date = $this->getContactFilter( 'start-date' );
		$end_date   = $this->getContactFilter( 'end-date' );
		$source     = $this->getContactFilter( 'source' );

		$params = array();
		if ( $source > 0 ) {
			$sql .= "JOIN " . tve_leads_table_name( 'event_log' ) . " `logs` ON `logs`.id=`contacts`.`log_id` WHERE `logs`.`main_group_id`=%s ";
			$params[] = $source;
		} else {
			$sql .= "WHERE 1";
		}

		if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
			$sql .= " AND `contacts`.`date` BETWEEN %s AND %s ";
			$params [] = $start_date;
			$params [] = $end_date . ' 23:59:59';
		}

		if ( empty( $post_data['orderby'] ) ) {
			$sql .= " ORDER BY `contacts`.`date` DESC ";
		} else {
			$sql .= " ORDER BY `contacts`." . esc_sql( $post_data['orderby'] );
			if ( ! empty( $post_data['order'] ) ) {
				$sql .= " " . $post_data['order'];
			}
		}

		if ( $count ) {
			return $this->wpdb->get_var( $this->prepare( $sql, $params ) );
		} else {
			$sql .= " LIMIT {$offset}," . $this->per_page;

			return $this->wpdb->get_results( $this->prepare( $sql, $params ) );
		}
	}

	public function process_bulk_action() {

		switch ( $this->current_action() ) {
			case 'delete':
				// In our file that handles the request, verify the nonce.
				$nonce = esc_attr( $_REQUEST['_wpnonce'] );

				if ( ! wp_verify_nonce( $nonce, 'tl_delete_contact' ) ) {
					die( 'Not cool!' );
				} else {
					$this->delete_contact( absint( $_REQUEST['contact'] ) );
				}
				break;

			case 'bulk-delete':
				if ( empty( $_REQUEST['bulk-action'] ) ) {
					return;
				}

				$delete_ids = esc_sql( $_REQUEST['bulk-action'] );
				// loop over the array of record IDs and delete them
				foreach ( $delete_ids as $id ) {
					$this->delete_contact( $id );

				}
		}

	}

	/**
	 * Delete contact from database
	 *
	 * @param $id
	 */
	private function delete_contact( $id ) {
		$this->wpdb->delete(
			"{$this->table_name}",
			array( 'ID' => $id ),
			array( '%d' )
		);
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No contacts saved.', 'thrive-contacts' );
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-action[]" value="%s" />', $item->id
		);
	}

	/**
	 * This function is called for each column for each row
	 * If there is no specific function for column this is called
	 * And this is the default implementation for showing a value in each cell of the table
	 *
	 * @param $item object of entire row from db
	 * @param $column_name string column name to be displayed
	 *
	 * @return mixed
	 */
	protected function column_default( $item, $column_name ) {
		return $item->$column_name;
	}

	protected function column_name( $item ) {
		if ( empty( $item->name ) ) {
			return __( 'N/A', "thrive-leads" );
		} else {
			return $item->name;
		}
	}

	protected function column_date( $item ) {
		return date( 'd M, Y G:i', strtotime( $item->date ) );
	}

	protected function column_custom_fields( $item ) {
		$fields = json_decode( $item->custom_fields, true );

		$info = "";
		if ( empty( $fields ) ) {
			return __( 'N/A', "thrive-leads" );
		}

		foreach ( $fields as $name => $value ) {
			$info .= "<strong>" . $name . "</strong>: " . $value . "<br/>";
		}

		return sprintf( '%1$s',
			trim( $info )
		);
	}

	protected function column_actions( $item ) {
		// create a nonce
		$delete_nonce = wp_create_nonce( 'tl_delete_contact' );

		$actions = array(
			'email'  => sprintf( '<a class="tve-email tvd-btn-icon tvd-btn-icon-blue" href="javascript:void(0)" data-contact-id="%s" title="%s"><span class="tvd-icon-email"></span>%s</a>',
				$item->id,
				__( 'Email', "thrive-leads" ),
				__( 'Email', "thrive-leads" )
			),
			'delete' => sprintf( '<a class="tvd-delete tvd-btn-icon tvd-btn-icon-red" href="?page=thrive_leads_contacts&action=%s&contact=%s&paged=%s&_wpnonce=%s" title="%s"><span class="tvd-icon-trash-o"></span>%s</a>',
				'delete',
				$item->id,
				$this->get_pagenum(),
				$delete_nonce,
				__( 'Delete', "thrive-leads" ),
				__( 'Delete', "thrive-leads" )
			),
		);

		return $this->row_actions( $actions, true );
	}

	protected function extra_tablenav( $which ) {
		$start_date = $this->getContactFilter( 'start-date' );
		$end_date   = $this->getContactFilter( 'end-date' );
		$source     = $this->getContactFilter( 'source' );
		$per_page   = $this->getContactFilter( 'per-page' );

		include dirname( dirname( dirname( __FILE__ ) ) ) . '/views/contacts/contacts_filters.php';
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

	private function getContactFilter( $filter ) {
		switch ( $filter ) {
			case 'start-date':
				$value = empty( $_REQUEST['tve-start-date'] ) ? date( 'Y-m-d', strtotime( '-7 days' ) ) : $_REQUEST['tve-start-date'];
				break;

			case 'end-date':
				$value = empty( $_REQUEST['tve-end-date'] ) ? date( 'Y-m-d' ) : $_REQUEST['tve-end-date'];
				break;

			case 'source':
				$value = empty( $_REQUEST['tve-source'] ) ? - 1 : $_REQUEST['tve-source'];
				break;

			case 'per-page':
				$value = empty( $_REQUEST['tve-per-page'] ) ? 20 : $_REQUEST['tve-per-page'];
				break;

			default:
				$value = '';
		}

		return $value;
	}

}
