<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 5/29/2015
 * Time: 2:29 PM
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Thrive_Dash_List_LogsTable extends WP_List_Table {

	protected $table_name;
	protected $per_page = 10;
	protected $connections;
	protected $wpdb;

	public function __construct( $args ) {
		if ( ! is_array( $args ) ) {
			$args = array();
		}

		global $wpdb;
		$this->wpdb = $wpdb;

		$this->table_name = $wpdb->prefix . 'tcb_api_error_log';

		$args['plural'] = ! empty( $args['plural'] ) ? $args['plural'] : 'logs';

		parent::__construct( $args );
	}

	public function get_columns() {
		$columns = array(
			'api_data'      => __( 'Form Data', TVE_DASH_TRANSLATE_DOMAIN ),
			'connection'    => __( 'Service', TVE_DASH_TRANSLATE_DOMAIN ),
			'date'          => __( 'Date', TVE_DASH_TRANSLATE_DOMAIN ),
			'error_message' => __( 'Error Message', TVE_DASH_TRANSLATE_DOMAIN ),
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
			'date'       => array( 'date', 'ASC' ),
			'connection' => array( 'connection', 'ASC' ),
		);

		return $columns;
	}

	public function prepare_items() {
		$sql = "SELECT * FROM {$this->table_name}";

		$order_by = ! empty( $_GET['orderby'] ) ? $_GET['orderby'] : 'date';
		$order    = ! empty( $_GET['order'] ) ? $_GET['order'] : 'DESC';
		if ( ! empty( $order_by ) && ! empty( $order ) ) {
			$sql .= " ORDER BY {$order_by} {$order}";
		}

		//get total items
		$total_items = $this->wpdb->query( $sql );

		//init current page
		$current_page = ! empty( $_GET['paged'] ) ? (int) esc_sql( $_GET['paged'] ) : 1;
		$current_page = $current_page >= 1 ? $current_page : 1;

		//calculate total pages
		$total_pages = ceil( $total_items / $this->per_page );

		//calculate the offset from where to begin the query
		$offset = ( $current_page - 1 ) * $this->per_page;
		$sql .= " LIMIT {$offset}," . $this->per_page;

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page'    => $this->per_page
		) );

		//init header columns
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		$this->items = $this->wpdb->get_results( $sql );
	}

	public function no_items() {
		_e( 'No error logs.', TVE_DASH_TRANSLATE_DOMAIN );
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

	protected function column_api_data( $item ) {
		$data = unserialize( $item->api_data );


		$info = "";

		if ( ! empty( $data['email'] ) ) {
			$info .= "<strong>" . __( "Email", TVE_DASH_TRANSLATE_DOMAIN ) . "</strong>: {$data['email']}<br/>";
		}

		if ( ! empty( $data['name'] ) ) {
			$info .= " <strong>" . __( "Name", TVE_DASH_TRANSLATE_DOMAIN ) . "</strong>: {$data['name']}<br/>";
		}

		if ( ! empty( $data['phone'] ) ) {
			$info .= " <strong>" . __( "Phone", TVE_DASH_TRANSLATE_DOMAIN ) . "</strong>: {$data['phone']}";
		}

		return sprintf( '%1$s',
			trim( $info )
		);
	}

	public function errorLogEntryHtml( $data = array() ) {
		include  dirname( dirname( __FILE__ ) ) . '/views/error-log-entry.php';
	}

	protected function column_actions( $item ) {
		$actions    = array();
		$connection = Thrive_Dash_List_Manager::connectionInstance( $item->connection );

		if ( $connection->isConnected() ) {
			$actions['retry'] = sprintf( '<a href="javascript:void(0)" data-log-id="%s">%s</a>',
				$item->id,
				__( 'Retry', TVE_DASH_TRANSLATE_DOMAIN )
			);
		}

		$actions['delete'] = sprintf( '<a href="javascript:void(0)" data-log-id="%s">%s</a>',
			$item->id,
			__( 'Delete', TVE_DASH_TRANSLATE_DOMAIN )
		);

		$out = $this->row_actions( $actions, true );

		$data                    = unserialize( $item->api_data );
		$data['connection_name'] = $item->connection;
		$data['list_id']         = $item->list_id;
		$data['log_id']          = $item->id;

		$out .= '<div style="display: none" id="data-' . $item->id . '">' . json_encode( $data ) . '</div>';

		return $out;
	}

	protected function column_connection( $item ) {
		return $this->get_connection_title( $item->connection );
	}

	protected function get_table_classes() {
		return parent::get_table_classes();
	}

	private function get_connection_title( $key ) {
		if ( empty( $this->connections[ $key ] ) ) {
			$this->init_connections();
		}

		return $this->connections[ $key ]->getTitle();
	}

	private function init_connections() {
		$this->connections = Thrive_Dash_List_Manager::getAvailableAPIs();
	}
}
