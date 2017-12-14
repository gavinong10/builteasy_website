<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 17.11.2014
 * Time: 10:15
 */

/**
 * should handle all AJAX requests coming from BackboneJS
 *
 * implemented as a singleton
 *
 * Class Thrive_Leads_Ajax_Controller
 */
class Thrive_Leads_Ajax_Controller extends Thrive_Leads_Request_Handler {
	/**
	 * @var Thrive_Leads_Ajax_Controller
	 */
	private static $instance;

	/**
	 * singleton implementation
	 *
	 * @return Thrive_Leads_Ajax_Controller
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new Thrive_Leads_Ajax_Controller();
		}

		/**
		 * Remove these actions
		 * Because some other plugins have hook on these actions and some errors may occur
		 */
		remove_all_actions( 'wp_insert_post' );
		remove_all_actions( 'save_post' );

		return self::$instance;
	}

	protected function error( $message, $status = '404 Not Found' ) {
		header( $_SERVER['SERVER_PROTOCOL'] . " " . $status );
		exit( $message );
	}

	/**
	 * entry-point for each ajax request
	 * this should dispatch the request to the appropriate method based on the "route" parameter
	 *
	 * @return array|object
	 */
	public function handle() {
		$route = $this->param( 'route' );

		$route      = preg_replace( '#([^a-zA-Z0-9-])#', '', $route );
		$methodName = $route . 'Action';

		return $this->{$methodName}();
	}

	/**
	 * save global settings for the plugin
	 */
	public function globalSettingsAPIAction() {
		$allowed = array(
			'ajax_load',
			'tve_load_annotations'
		);
		$field   = $this->param( 'field' );
		$value   = $this->param( 'value' );

		if ( ! in_array( $field, $allowed ) ) {
			exit();
		}

		if ( $field == 'ajax_load' ) {
			$cache_plugin = tve_dash_detect_cache_plugin();
			if ( $cache_plugin ) {
				tve_dash_cache_plugin_clear( $cache_plugin );
			}
		}
		tve_leads_update_option( $field, $value );
		exit();
	}

	/**
	 * Lead Shortcodes API for CRUD
	 *
	 * @return mixed based on the handled HTTP operation
	 */
	public function shortcodeAPIAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		if ( $this->param( 'get_empty_variations' ) ) {
			return array( 'ids' => tve_leads_get_form_empty_variations( $this->param( 'ID' ) ) );
		}

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_save_shortcode( $model );
			case 'DELETE':
				return tve_leads_delete_post( $this->param( 'ID', 0 ) );
			case 'GET':
				$shortcode = tve_leads_get_form_type( $_GET['ID'], array(
					'completed_tests' => true,
				) );
				if ( ! $shortcode ) {
					$this->error( 'Shortcode not found' );
				}
				$test = tve_leads_get_form_active_test( $shortcode->ID, array(
					'test_type' => TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE,
					'get_items' => false
				) );
				if ( $test ) {
					$shortcode->redirect_to = '#test/' . $test->id;
				}
				$shortcode->completed_tests        = tve_leads_get_completed_form_test( $shortcode, TVE_LEADS_SHORTCODE_TEST_TYPE );
				$shortcode->has_animation_settings = false;

				$shortcode->content_locking = get_post_meta( $_GET['ID'], 'tve_content_locking', true );
				$shortcode->content_locking = $shortcode->content_locking == '' ? 0 : intval( $shortcode->content_locking );
				$shortcode->shortcode_code  = ( $shortcode->content_locking == 1 ) ? '[thrive_lead_lock id=\'' . $_GET['ID'] . '\']Hidden Content[/thrive_lead_lock]' : '[thrive_leads id=\'' . $_GET['ID'] . '\']';

				return $shortcode;
				break;
		}
	}

	/**
	 * 2 Step Lightbox API for CRUD (new name: ThriveBox)
	 *
	 * @return mixed based on the handled HTTP operation
	 */
	public function twoStepLightboxAPIAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		if ( $this->param( 'get_empty_variations' ) ) {
			return array( 'ids' => tve_leads_get_form_empty_variations( $this->param( 'ID' ) ) );
		}

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_save_two_step_lightbox( $model );
			case 'DELETE':
				return tve_leads_delete_post( $this->param( 'ID', 0 ) );
			case 'GET':
				$two_step_lightbox = tve_leads_get_form_type( $_GET['ID'], array(
					'completed_tests' => true,
				) );
				if ( ! $two_step_lightbox ) {
					$this->error( 'ThriveBox not found' );
				}
				$test = tve_leads_get_form_active_test( $two_step_lightbox->ID, array(
					'test_type' => TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE,
					'get_items' => false
				) );
				if ( $test ) {
					$two_step_lightbox->redirect_to = '#test/' . $test->id;
				}
				$two_step_lightbox->completed_tests        = tve_leads_get_completed_form_test( $two_step_lightbox, TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE );
				$two_step_lightbox->has_animation_settings = true;
				$two_step_lightbox->content_locking        = 0;

				return $two_step_lightbox;
				break;
		}
	}

	/**
	 * Asset Groups for CRUD (new name: ThriveBox)
	 *
	 * @return mixed based on the handled HTTP operation
	 */
	public function assetsAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_save_asset_group( $model );
			case 'DELETE':
				return tve_leads_delete_post( $this->param( 'ID', 0 ) );
			case 'GET':
				if ( $this->param( 'custom' ) ) {
					switch ( $this->param( 'custom' ) ) {
						case 'update_service':
							$connection = $this->param( 'new_connection', array() );

							return update_option( 'tve_api_delivery_service', $connection );
						case 'test_service':
							$connection = $this->param( 'test_connection', array() );
							$api        = Thrive_List_Manager::connectionInstance( $connection );
							$test       = $api->testConnection();
							if ( $test === true ) {
								$class = "updated";

								return "<div class='" . $class . "'><p>" . __( 'Connection was made successfully', 'thrive-leads' ) . "</p></div>";
							} else {
								$class = "error";

								return "<div class='" . $class . "'><p>" . $test . "</p></div>";
							}
					}
				}
				break;
		}

	}

	/**
	 * Asset Group Files for CRUD (new name: Asset Delivery)
	 *
	 * @return mixed based on the handled HTTP operation
	 */
	public function filesAddAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_update_asset_files( $model );
			case 'DELETE':
				return tve_leads_delete_asset_file( $this->param( 'parent_ID', 0 ), $this->param( 'ID', 0 ) );
			case 'GET':
				break;
		}

	}

	/**
	 * Asset Group Files for CRUD (new name: Asset Delivery)
	 *
	 * @return mixed based on the handled HTTP operation
	 */
	public function wizardAddAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_add_wizard_group( $model );
			case 'DELETE':
				return tve_leads_delete_post( $this->param( 'ID', 0 ) );
			case 'GET':
				break;
		}
	}

	/**
	 * One Click Signup API for CRUD (new name: Signup Segue)
	 *
	 * @return mixed based on the handled HTTP operation
	 */
	public function oneClickSignupAPIAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		if ( $this->param( 'get_empty_variations' ) ) {
			return array( 'ids' => tve_leads_get_form_empty_variations( $this->param( 'ID' ) ) );
		}

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_save_one_click_signup( $model );
			case 'DELETE':
				return tve_leads_delete_post( $this->param( 'ID', 0 ) );
			case 'GET':
				//doesn't have form types or variations so it donesn't come here
				break;
		}
	}

	/**
	 * Lead Groups API for CRUD
	 *
	 * @return mixed based on the handled HTTP operation
	 */
	public function groupAPIAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		if ( $this->param( 'get_empty_variations' ) ) {
			return array( 'ids' => tve_leads_get_group_empty_form_variations( $this->param( 'ID' ) ) );
		}
		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_save_group( $model );
			case 'DELETE':
				return tve_leads_delete_post( $this->param( 'ID', 0 ) );
			case 'GET':
				if ( $this->param( 'custom' ) ) {
					switch ( $this->param( 'custom' ) ) {
						case 'update_order':
							$ordered = $this->param( 'new_order', array() );
							foreach ( $ordered as $post_id => $order ) {
								update_post_meta( $post_id, 'tve_group_order', $order );
							}

							return array();
					}
				}
				break;
		}
	}

	/**
	 * CRUD operations for Form Types
	 *
	 */
	public function formTypeAPIAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		$custom_action = $this->param( 'custom_action' );

		if ( ! empty( $custom_action ) ) {
			switch ( $custom_action ) {
				// reset all logs associated with this form type
				case 'reset_statistics':

					$post = tve_leads_get_form_type( $this->param( 'ID', 0 ), array(
						'get_variations' => false
					) );

					if ( is_null( $post ) ) {
						$this->error( "Form Type not found !" );
					}

					/**
					 * reset tve_leads_signups meta tag for one click signup (new name: Signup Segue)
					 */
					if ( $post->post_type && $post->post_type === TVE_LEADS_POST_ONE_CLICK_SIGNUP ) {
						update_post_meta( $post->ID, 'tve_leads_signups', '0' );
					}

					global $tvedb;
					$result = $tvedb->archive_logs( array(
						'form_type_id' => $this->param( 'ID' )
					) );

					/*
					 * also clear out the cached impressions and conversions for the Form Type
					 */
					tve_leads_reset_post_tracking_data( $post );

					if ( $result === false ) {
						$this->error( __( "Error on resetting form type statistics", "thrive-leads" ) );
					}

					return array(
						'impressions'     => 0,
						'conversions'     => 0,
						'conversion_rate' => 'N/A'
					);
					break;
			}
		}

		if ( $this->param( 'get_empty_variations' ) ) {
			return array( 'ids' => tve_leads_get_form_empty_variations( $this->param( 'ID' ) ) );
		}

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_save_form_type( $model );
			case 'DELETE':
				return tve_leads_save_form_type( array(
					'ID'          => $this->param( 'ID', 0 ),
					'post_status' => 'trash'
				) );
			case 'GET':
				//get post form type
				$post = tve_leads_get_form_type( $this->param( 'ID', 0 ) );
				if ( is_null( $post ) ) {
					$this->error( "Form Type not found !" );
				}

				//get post group parent for form type
				$post->parent = get_post( $post->post_parent );
				if ( empty( $post->parent ) || $post->parent->post_status === 'trash' ) {
					$this->error( "Form Type with no group !" );
				}

				//all info for active test is returned and only ID is needed
				//TODO: find a way to get only active test ID
				$post->active_test = tve_leads_get_form_active_test( $this->param( 'ID', 0 ) );
				if ( ! $post->active_test ) {
					$post->completed_tests = tve_leads_get_completed_form_test( $post, TVE_LEADS_VARIATION_TEST_TYPE );
				} else {
					$post->active_test = $post->active_test->id;
				}
				$post->has_frequency_settings = tve_leads_form_type_has_frequency_settings( $post );
				$post->has_position_settings  = tve_leads_form_type_has_position_settings( $post );
				$post->has_animation_settings = tve_leads_form_type_has_animation_settings( $post );
				$post->has_trigger_settings   = tve_leads_form_type_has_trigger_settings( $post );
				$post->content_locking        = 0;
				/**
				 * get all the tests that run at group level and make sure this form type is not included in one
				 */
				$group_level_tests = tve_leads_get_group_active_tests( $post->post_parent );
				foreach ( $group_level_tests as $test ) {
					if ( in_array( $post->ID, $test->item_ids ) ) {
						$post->redirect_to = '#test/' . $test->id;
					}
				}
				/**
				 * make sure this form does not have a test running at variation level
				 */
				$test = tve_leads_get_form_active_test( $post->ID, array( 'get_items' => false ) );
				if ( ! empty( $test ) ) {
					$post->redirect_to = '#test/' . $test->id;
				}

				return $post;
		}
	}

	/**
	 * CRUD operations for form variations
	 *
	 */
	public function formVariationAPIAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		$custom_action = $this->param( 'custom_action' );

		if ( ! empty( $custom_action ) ) {
			switch ( $custom_action ) {
				// reset all logs associated with this form type
				case 'reset_statistics':
					global $tvedb;

					$variation = tve_leads_get_form_variation( null, $this->param( 'key' ), array( 'tracking_data' => true ) );

					$tvedb->archive_logs( array(
						'variation_key' => $this->param( 'key' )
					) );

					tve_leads_reset_variation_tracking_data( $variation );

					$variation['impressions']     = $variation['conversions'] = $variation['unique_impressions'] = 0;
					$variation['conversion_rate'] = 'N/A';

					return $variation;
			}
		}

		if ( ! empty( $model['post_parent'] ) ) {
			/**
			 * check if there is a test running at form type level or at group level and it contains this form type
			 */
			$test = tve_leads_get_form_active_test( $model['post_parent'] );
			if ( ! $test ) {
				/**
				 * check for tests at group level
				 */
				$post              = tve_leads_get_form_type( $model['post_parent'], array( 'get_variations' => false ) );
				$group_level_tests = tve_leads_get_group_active_tests( $post->post_parent );
				foreach ( $group_level_tests as $test_obj ) {
					if ( in_array( $post->ID, $test_obj->item_ids ) ) {
						$test = $test_obj;
						break;
					}
				}
			}
			if ( $test ) {
				$test_link = '<a href="' . admin_url( 'admin.php?page=thrive_leads_dashboard#test/' . $test->id ) . '">' . __( 'here', 'thrive-leads' ) . '</a>';
				$this->error( sprintf( __( 'A test is currently running at this level. You cannot make any modifications to this form until the test is completed. Click %s to view the test', 'thrive-leads' ), $test_link ), '403 Forbidden' );
			}
		}

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				/**
				 * we need to also re-add the child states for the form
				 */
				if ( ! empty( $model['readd_from'] ) ) {
					$model['form_child_states'] = tve_leads_get_form_child_states( $model['readd_from'] );
				}
				if ( ! empty( $model['clone_from'] ) ) { /* clone from an existing form */
					$new_form               = tve_leads_get_form_variation( $model['post_parent'], $model['clone_from'] );
					$new_form['post_title'] = $model['post_title'];
					$new_form['is_control'] = 0;

					/**
					 * we need to also clone the child states for the form
					 */
					$new_form['form_child_states'] = tve_leads_get_form_child_states( $model['clone_from'] );

					unset( $new_form['key'] );
					$model               = $new_form;
					$copy_of             = __( 'Copy of', 'thrive-leads' );
					$model['post_title'] = ( strpos( $model['post_title'], $copy_of ) === false ? $copy_of . ' ' : '' ) . $model['post_title'];

				} else {
					if ( empty( $model['key'] ) ) {
						unset( $model['display_frequency'] );
					} else {
						foreach ( tve_leads_get_editor_fields() as $field ) {
							unset( $model[ $field ] );
						}
					}
				}

				$variation = tve_leads_save_form_variation( $model );
				if ( empty( $model['key'] ) ) {
					$variation['impressions']     = $variation['unique_impressions'] = $variation['conversions'] = '0';
					$variation['conversion_rate'] = tve_leads_conversion_rate( $variation['unique_impressions'], $variation['conversions'] );
				}
				$variation['tcb_edit_url']    = tve_leads_get_editor_url( $variation['post_parent'], $variation['key'] );
				$variation['tcb_preview_url'] = tve_leads_get_preview_url( $variation['post_parent'], $variation['key'] );
				unset( $variation['is_control'] );

				return $variation;
			case 'DELETE':
				$model                = tve_leads_get_form_variation( $this->param( 'form_type' ), $this->param( 'key' ) );
				$model['post_status'] = 'trash';

				return tve_leads_save_form_variation( $model );
			case 'GET':
				return null;
		}
	}

	public function completedTestsAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		switch ( $method ) {
			case 'GET':
				$post  = tve_leads_get_form_type( $this->param( 'id', 0 ), array( 'get_variations' => false ) );
				$tests = array();
				if ( is_null( $post ) ) {
					return $tests;
				}
				$tests = tve_leads_get_completed_form_test( $post );

				return $tests;
				break;
		}
	}

	/**
	 * CRUD for tests
	 * @return array|int
	 */
	public function testAPIAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				return tve_leads_save_test( $model );
				break;
			case 'GET':
				$test = tve_leads_get_test( $this->param( 'id' ), array(
					'load_test_items' => true,
					'load_form_data'  => true,
				) );

				if ( empty( $test ) ) {
					$this->error( "Test not found" );
				}

				$another_test_running = null;

				//we don't want to check for another running test at group level cos there might be more than 1
				if ( get_post_type( $test->main_group_id ) !== TVE_LEADS_POST_GROUP_TYPE ) {
					$another_test_running = tve_leads_get_running_tests( $test->main_group_id );
				}

				$test->another_test_running = $another_test_running ? $another_test_running->id : null;

				return $test;
				break;
			case 'DELETE':
				return tve_leads_delete_test( $this->param( 'id', 0 ) );
				break;
		}
	}

	public function chartAPIAction() {
		$chartType = $this->param( 'chartType', '' );
		switch ( $chartType ) {
			case 'testChart':
				$chart_data = tve_leads_get_test_chart_data( $this->param( 'ID', 0 ), $this->param( 'interval', 'day' ) );

				return $chart_data;
				break;
		}
	}

	/**
	 * CRUD for test item
	 * @return array|int
	 */
	public function testItemAPIAction() {
		$model  = json_decode( file_get_contents( 'php://input' ), true );
		$method = empty( $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ) ? 'GET' : $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];;

		switch ( $method ) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				$id = tve_leads_save_test_item( $model );
				if ( ! empty( $model['id'] ) ) {
					echo json_encode( tve_leads_get_groups() );
					die;
				}

				return $id;
				break;
			case 'GET':
				return 0;
				break;
		}
	}

	/**
	 * Get data for the report chart and table
	 */
	public function reportAction() {
		$filters = array(
			'interval'         => $this->param( 'tve-chart-interval' ),
			'main_group_id'    => $this->param( 'tve-chart-source', - 1 ),
			'start_date'       => $this->param( 'tve-report-start-date' ),
			'end_date'         => $this->param( 'tve-report-end-date' ),
			'order_by'         => $this->param( 'order_by' ),
			'order_dir'        => $this->param( 'order_dir' ),
			'referral_type'    => $this->param( 'tve-referral-type' ),
			'source_type'      => $this->param( 'tve-source-type' ),
			'tracking_type'    => $this->param( 'tve-tracking-type' ),
			'load_annotations' => $this->param( 'tve_load_annotations' ),
			'archived_log'     => 0
		);

		if ( $this->param( 'type' ) == 'table' ) {
			$filters['itemsPerPage'] = $this->param( 'itemsPerPage' );
			$filters['page']         = $this->param( 'page' );
			switch ( $this->param( 'report_type' ) ) {
				case 'Conversion':
				case 'CumulativeConversion':
				case 'ListGrowth':
				case 'CumulativeListGrowth':
					die( json_encode( tve_leads_get_conversion_report_table_data( $filters ) ) );
				case 'ConversionRate':
					die( json_encode( tve_leads_get_conversion_rate_report_table_data( $filters ) ) );
				case 'LeadReferral':
					$filters['count'] = false;
					die( json_encode( tve_leads_get_lead_referral_report_data( $filters ) ) );
				case 'LeadTracking':
					$filters['count'] = false;
					die( json_encode( tve_leads_get_lead_tracking_report_data( $filters ) ) );
				case 'LeadSource':
					$filters['count'] = false;
					die( json_encode( tve_leads_get_lead_source_report_data( $filters ) ) );
				default:
					die;
			}
		}

		switch ( $this->param( 'report_type' ) ) {
			case 'Conversion':
				die( json_encode( tve_leads_get_conversion_report_data( $filters ) ) );
			case 'CumulativeConversion':
				die( json_encode( tve_leads_get_cumulative_conversion_report_data( $filters ) ) );
			case 'ConversionRate':
				die( json_encode( tve_leads_get_conversion_rate_report_chart( $filters ) ) );
			case 'ListGrowth':
				die( json_encode( tve_leads_get_list_growth( $filters, false ) ) );
			case 'CumulativeListGrowth':
				die( json_encode( tve_leads_get_list_growth( $filters, true ) ) );
			case 'ComparisonChart':
				die( json_encode( tve_leads_get_comparison_report_data( $filters ) ) );
			case 'LeadReferral':
				die( json_encode( tve_leads_get_lead_referral_report_data( $filters ) ) );
			case 'LeadTracking':
				die( json_encode( tve_leads_get_lead_tracking_report_data( $filters ) ) );
			case 'LeadSource':
				die( json_encode( tve_leads_get_lead_source_report_data( $filters ) ) );
		}
	}

	public function displayGroupSettingsAction() {
		$memory_limit = (int) ini_get( 'memory_limit' );
		if ( $memory_limit < 256 ) {
			@ini_set( 'memory_limit', '256M' );
		}
		require_once plugin_dir_path( __FILE__ ) . '../inc/classes/display_settings/Thrive_Leads_Display_Settings_Manager.php';
		$displaySettingsManager = new Thrive_Leads_Display_Settings_Manager( TVE_LEADS_VERSION );

		return $displaySettingsManager->get_popup_data();
	}

	public function saveGroupSettingsAction() {
		require_once plugin_dir_path( __FILE__ ) . '../inc/classes/display_settings/Thrive_Leads_Display_Settings_Manager.php';
		$displaySettingsManager = new Thrive_Leads_Display_Settings_Manager( TVE_LEADS_VERSION );
		$result                 = $displaySettingsManager->save_options();

		$cache_plugin = tve_dash_detect_cache_plugin();
		if ( $cache_plugin ) {
			tve_dash_cache_plugin_clear( $cache_plugin );
		}

		exit( json_encode( array(
			'success' => $result === true ? true : false,
			'message' => $result !== true ? 'Error while saving: ' . $result : ''
		) ) );

	}

	public function saveGroupTemplateAction() {
		require_once plugin_dir_path( __FILE__ ) . '../inc/classes/display_settings/Thrive_Leads_Display_Settings_Manager.php';
		$displaySettingsManager = new Thrive_Leads_Display_Settings_Manager( TVE_LEADS_VERSION );
		$result                 = $displaySettingsManager->save_template();

		exit( json_encode( array(
			'success'   => $result === true ? true : false,
			'message'   => $result !== true ? 'Error while saving: ' . $result : '',
			'templates' => $result === true ? apply_filters( 'thrive_display_options_get_templates', array() ) : array()
		) ) );
	}

	public function loadGroupTemplateAction() {
		require_once plugin_dir_path( __FILE__ ) . '../inc/classes/display_settings/Thrive_Leads_Display_Settings_Manager.php';
		$displaySettingsManager = new Thrive_Leads_Display_Settings_Manager( TVE_LEADS_VERSION );

		$displaySettingsManager->load_template();
	}

	/**
	 * display a trigger settings popup for a Form Variation
	 */
	public function triggerSettingsAction() {
		$variation                  = tve_leads_get_form_variation( $this->param( 'form_type_id' ), $this->param( 'variation_key' ) );
		$form_type                  = get_post_meta( $this->param( 'form_type_id' ), 'tve_form_type', true );
		$variation['tve_form_type'] = $form_type;

		include dirname( dirname( __FILE__ ) ) . '/views/trigger_settings.php';

		exit();
	}

	/**
	 * display a trigger settings popup for a Form Variation
	 */
	public function positionSettingsAction() {
		$variation                       = tve_leads_get_form_variation( $this->param( 'form_type_id' ), $this->param( 'variation_key' ) );
		$form_type                       = get_post_meta( $this->param( 'form_type_id' ), 'tve_form_type', true );
		$variation['tve_form_type']      = $form_type;
		$variation['tve_form_type_name'] = str_replace( "_", " ", $form_type );
		$form_type_position              = tve_leads_get_available_positions( $form_type );

		include dirname( dirname( __FILE__ ) ) . '/views/position_settings.php';

		exit();
	}

	/**
	 * delete log entries correlated to Non-unique impressions
	 *
	 */
	public function optimizeDbAPIAction() {
		global $tvedb;
		$tvedb->delete_logs( array(
			'event_type' => TVE_LEADS_IMPRESSION
		) );
	}

	/**
	 * searches a tag by keywords, used in Display Lead Group settings
	 */
	public function tagSearchAction() {
		if ( ! $this->param( 'tax' ) ) {
			wp_die( 0 );
		}

		$taxonomy = sanitize_key( $this->param( 'tax' ) );
		$tax      = get_taxonomy( $taxonomy );
		if ( ! $tax ) {
			wp_die( 0 );
		}

		if ( ! current_user_can( $tax->cap->assign_terms ) ) {
			wp_die( - 1 );
		}

		$s = wp_unslash( $this->param( 'q' ) );

		$comma = _x( ',', 'tag delimiter' );
		if ( ',' !== $comma ) {
			$s = str_replace( $comma, ',', $s );
		}
		if ( false !== strpos( $s, ',' ) ) {
			$s = explode( ',', $s );
			$s = $s[ count( $s ) - 1 ];
		}
		$s = trim( $s );

		if ( strlen( $s ) < 2 ) {
			wp_die();
		}

		$results = get_terms( $taxonomy, array( 'name__like' => $s, 'fields' => 'id=>name', 'number' => 10 ) );

		$json = array();
		foreach ( $results as $id => $name ) {
			$json [] = array(
				'label' => $name,
				'id'    => $id,
				'value' => $name
			);
		}
		wp_send_json( $json );
	}

	/**
	 * searches a post by keywords
	 */
	public function postSearchAction() {
		if ( ! $this->param( 'q' ) ) {
			wp_die( 0 );
		}
		$s = wp_unslash( $this->param( 'q' ) );

		/** @var WP_Post[] $posts */
		$posts = get_posts( array(
			'posts_per_page' => 10,
			's'              => $s,
		) );

		$json = array();
		foreach ( $posts as $post ) {
			$json [] = array(
				'label' => $post->post_title,
				'id'    => $post->ID,
				'value' => $post->post_title
			);
		}

		wp_send_json( $json );
	}

	/**
	 * clear all cached impression and conversion counts for all Lead Groups, Form Types, Form Variations and shortcodes
	 */
	public function clearCacheStatisticsAction() {
		$this->_purgeCache();

		die( 'done' );
	}

	private function _purgeCache() {
		global $tvedb;

		/**
		 * for Groups, Form Types, Shortcodes, and 2 step lightboxes (new name: ThriveBox)
		 */
		delete_post_meta_by_key( 'tve_leads_impressions' );
		delete_post_meta_by_key( 'tve_leads_conversions' );

		/**
		 * form variations
		 */
		return $tvedb->update_all_fields( 'form_variations', array( 'cache_impressions' => null, 'cache_conversions' => null ) );
	}

	/**
	 * display inboundLink Builder lightbox
	 */
	public function displayInboundLinkBuilderAction() {
		include dirname( dirname( __FILE__ ) ) . '/views/inbound_link_builder.php';
		die;
	}

	public function addAssetAPIAction() {
		$connection      = $this->param( 'api', array() );
		$api             = Thrive_List_Manager::connectionInstance( $connection );
		$connection_type = get_option( 'tve_api_delivery_service', false );

		if ( $connection_type == false ) {
			update_option( 'tve_api_delivery_service', $connection );
		}

		$connect = $api->readCredentials();

		return $connect;

	}

	/**
	 * get the default connection for asset delivery
	 */
	public function getDefaultConnectionAction() {

		return get_option( 'tve_api_delivery_service', false );

	}

	public function inconclusivetestsAction() {
		$test_id = $this->param( 'test_id' );
		$return  = array( 'result' => false );

		if ( ! empty( $test_id ) && is_numeric( $test_id ) ) {
			$inconclusive_tests_option = get_option( 'tve_inconclusive_tests', false );
			if ( $inconclusive_tests_option ) {
				$inconclusive_tests = explode( ',', $inconclusive_tests_option );
			}

			if ( ! in_array( $test_id, $inconclusive_tests ) ) {
				$inconclusive_tests[] = $test_id;
			}

			$result = update_option( 'tve_inconclusive_tests', implode( ',', $inconclusive_tests ) );

			$return = array_merge( $return, array( 'result' => $result, 'redirect_url' => admin_url( 'admin.php?page=thrive_leads_dashboard#test/' . $test_id ) ) );
		}

		wp_send_json( $return );
	}

	/**
	 * Handle actions for the Contacts view.
	 * @return array
	 */
	public function contactsAction() {
		switch ( $this->param( 'actionType' ) ) {
			case 'send-email':
				$data = $this->param( 'data' );

				$result = tve_send_contacts_email( $data['id'], $data['email_address'], $data['save_email'] );
				break;

			case 'delete-download':
				global $tvedb;
				$id = $this->param( 'id' );

				$result = $tvedb->tve_leads_delete_download_item( $id );
				break;

			case 'download':
				$source = $this->param( 'source' );
				$type   = $this->param( 'type' );
				$params = $this->param( 'params' );

				$result = tve_leads_process_contact_download( $source, $type, $params );
				break;

			default:
				$result = '';
		}

		return $result;
	}

	public function setEmailTemplateAction() {
		tve_leads_set_email_template( $_POST );

		return true;
	}

	public function setWizardCompleteAction() {
		return update_option( 'tve_leads_asset_wizard_complete', 1 );
	}

	public function deleteLogsAction() {
		global $tvedb;
		$v_ids = $tvedb->get_variation_ids();
		$t_ids = $tvedb->get_split_test_ids();

		$v_result = 0;
		if ( ! empty( $v_ids ) ) {
			$v_result = $tvedb->delete_conversion_logs( $v_ids );
		}

		$t_result = 0;
		if ( ! empty( $t_ids ) ) {
			$t_result = $tvedb->delete_split_logs( $t_ids );
		}

		$this->_purgeCache();

		return $v_result + $t_result;
	}
}
