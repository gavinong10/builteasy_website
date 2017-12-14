<?php

if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );


if ( ! class_exists( 'EG_Attachments_Form_List_Table' ) ) {

	class EG_Attachments_Form_List_Table extends WP_List_Table {

		var $textdomain;
		var $date_format;

		function __construct() {
			$this->textdomain = EGA_TEXTDOMAIN;
			$this->date_format = get_option('date_format');
			parent::__construct( array(
				'singular' 	=> __('Template', $this->textdomain),
				'plural' 	=> __('Templates', $this->textdomain),
				'ajax' 		=> false ) );
		}

		function get_columns(){
			$columns = array(
				'cb' 			=> '<input type="checkbox" />',
				'title' 		=> __( 'Title', 	  $this->textdomain ),
				'slug' 			=> __( 'Slug', 		  $this->textdomain ),
				'description' 	=> __( 'Description', $this->textdomain ),
//				'author' 		=> __( 'Author', 	  $this->textdomain ),
				'date' 			=> __( 'Date', 		  $this->textdomain )
			);
			return $columns;
		} // End of get_columns

		function get_sortable_columns() {
			$columns = array(
				'title'  => array( 'title',  false ),
				'slug'   => array( 'name',  false ),
				'date' 	 => array( 'date', 	 false )
			);

			return $columns;
		} // End of get_sortable_columns

		function prepare_items() {

			$per_page = 10;

			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);

/* --- suppressed 2.1.0
			$args = array(
				'post_type' 		=> EGA_TEMPLATE_POST_TYPE,
				'posts_per_page' 	=> $per_page,
				'orderby' 			=> 'title',
				'order' 			=> 'ASC',
				'offset' 			=> ( $this->get_pagenum() - 1 ) * $per_page 
			);

			if (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array('title', 'date'))) {
				$args['orderby'] = $_REQUEST['orderby'];
			}
			if (isset($_REQUEST['order']) && in_array(strtoupper($_REQUEST['order']), array('ASC', 'DESC'))) {
				$args['order'] = $_REQUEST['order'];
			}
--- */
			$this->items = EG_Attachments_Common::get_templates(get_option(EGA_OPTIONS_ENTRY), 'all', FALSE);

			$total_items = (array)wp_count_posts( EGA_TEMPLATE_POST_TYPE );
			$this->set_pagination_args( array(
					'total_items' => $total_items['publish'],
					'per_page'    => $per_page,
					'total_pages' => ceil($total_items['publish'] / $per_page)
				)
			);
		} // End of prepare_items

		function get_bulk_actions() {
			if (current_user_can(EGA_DELETE_TEMPLATES)) {
				$actions = array(
					'delete' => __( 'Delete', $this->textdomain )
				);
			}
			return $actions;
		}

//		function current_action() {
//			if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['templates'] ) && ( 'delete' == $_REQUEST['action'] || 'delete' == $_REQUEST['action2'] ) )
//				return 'bulk-del';
//
//			return parent::current_action();
//		} // End of current_action

		function column_default( $item, $column_name ) {
			return '';
		}

		function column_cb( $item ) {

			return sprintf(
				'<input type="checkbox" name="%1$s[]" value="%2$s" />',
				'templates',
				$item->ID );
		} // End of column_cb

		function column_title( $item ) {

			$url = add_query_arg( array( 'id' => $item->ID), menu_page_url( 'ega_templates', false ));
			
			if (current_user_can(EGA_EDIT_TEMPLATES, $item->ID)) {
				$edit_link 	= add_query_arg( array('action' => 'edit'), $url);
				$copy_link 	= wp_nonce_url( add_query_arg( array('action' => 'copy'), $url),'ega_shortcodes_edit_nonce_field-'.absint($item->ID));
				$actions = array(
					'edit'   => sprintf('<a href="%1s" title="%2s">%3s</a>', $edit_link, __('Edit template', $this->textdomain), __('Edit', $this->textdomain)),
					'copy'   => sprintf('<a href="%1s" title="%2s">%3s</a>', $copy_link, __('Copy template', $this->textdomain), __('Copy', $this->textdomain))
				);
			}
			else {
				if (current_user_can(EGA_READ_TEMPLATES, $item->ID)) {
					$edit_link 	= add_query_arg( array('action' => 'view'), $url);			
					$actions['view'] = sprintf('<a href="%1s" title="%2s">%3s</a>', $edit_link, __('View template', $this->textdomain), __('View', $this->textdomain));
				}
			}
			if (current_user_can(EGA_DELETE_TEMPLATES, $item->ID)) {
				$del_link  	= wp_nonce_url( add_query_arg( array('action' => 'del'), $url),'ega_shortcodes_edit_nonce_field-'.absint($item->ID));
				$actions['delete'] = sprintf('<a href="%1s" title="%2s">%3s</a>', $del_link, __('Delete template', $this->textdomain), __('Delete', $this->textdomain));
			}

			$a = sprintf( '<a class="row-title" href="%1$s" title="%2$s">%3$s</a>',
				$edit_link,
				esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', $this->textdomain ), $item->post_title ) ),
				esc_html( $item->post_title ));

			return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );

		} // End of column_id

		function column_slug( $item ) {
			$output = esc_attr( $item->post_name );

			return trim( $output );
		}

//		function column_author( $item ) {
//			$author = get_userdata( $item->post_author );
//
//			return esc_html( $author->nickname);
//		} // End of column_author

		function column_description( $item ) {
			$output = esc_attr( $item->post_excerpt );

			return trim( $output );
		}

		function column_date( $item ) {
			return mysql2date($this->date_format, $item->post_modified);
		}
	} // End of class

} // End of class_exists

?>