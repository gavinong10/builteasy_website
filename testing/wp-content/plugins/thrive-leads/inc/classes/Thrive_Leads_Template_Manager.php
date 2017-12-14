<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 28.01.2015
 * Time: 14:39
 */
class Thrive_Leads_Template_Manager extends Thrive_Leads_Request_Handler {

	const OPTION_TPL_META = 'tve_leads_saved_tpl_meta';
	const OPTION_TPL_CONTENT = 'tve_leads_saved_tpl';

	/**
	 * two_step_lightboxes use the same templates as lightboxes
	 *
	 * @var array
	 */
	protected static $type_maps = array(
		'two_step_lightbox' => 'lightbox',
		'php_insert'        => 'post_footer'
	);

	/**
	 * @var array
	 */
	protected $variation;

	/**
	 * @var Thrive_Leads_Template_Manager
	 */
	protected static $instance = null;

	/**
	 *
	 * singleton implementation
	 *
	 * @param array $variation the variation being edited
	 *
	 * @return Thrive_Leads_Template_Manager
	 */
	public static function instance( $variation ) {
		return new self( $variation );
	}

	/**
	 * get all templates available for a form variation
	 */
	public static function for_variation( $variation ) {
		return self::instance( $variation )->get_all();
	}

	/**
	 * get all templates available for a form type
	 *
	 * @param mixed $form_type
	 * @param bool $get_multi_steps whether or not to include templates designed for the multi-step forms
	 *
	 * @return array the list of templates
	 */
	public static function for_form_type( $form_type, $get_multi_steps = true ) {
		if ( $form_type instanceof WP_Post ) {
			$form_type = get_post_meta( $form_type->ID, 'tve_form_type', true );
		}

		return self::instance( array() )->get_all( $form_type, $get_multi_steps );
	}

	/**
	 * get all templates available for a multi-state form
	 *
	 * @param mixed $form_type
	 *
	 * @return array the list of templates
	 */
	public static function for_multi_step( $form_type ) {
		if ( $form_type instanceof WP_Post ) {
			$form_type = get_post_meta( $form_type->ID, 'tve_form_type', true );
		}

		return self::instance( array() )->get_multi_step_templates( $form_type );
	}

	/**
	 * get a template type map for the $form_type
	 * used when editing form types that use templates from different categories (e.g. two_step_lightbox - lightbox)
	 *
	 * @param string $form_type one of: ribbon, lightbox, two_step_lightbox, shortcode etc
	 *
	 * @return string the corresponding form_type
	 */
	public static function tpl_type_map( $form_type ) {
		return isset( self::$type_maps[ $form_type ] ) ? self::$type_maps[ $form_type ] : $form_type;
	}

	/**
	 *
	 * @param array $variation
	 */
	private function __construct( $variation ) {
		$this->variation = $variation;
	}

	/**
	 * forward the call based on the $action parameter
	 * API entry-point for the template chooser lightbox (from the editor)
	 *
	 * @param string $action
	 */
	public function api( $action ) {
		$method = 'api_' . $action;

		$result = call_user_func( array( $this, $method ) );

		if ( is_array( $result ) ) {
			$result = json_encode( $result );
		}

		exit( $result );
	}

	/**
	 * get the current template type (ribbon, post_footer etc)
	 *
	 * @param string|array $template_key optional array with TVE_LEADS_FIELD_TEMPLATE or directly the string key
	 *
	 * @return string
	 */
	public function type( $template_key = null ) {
		if ( is_array( $template_key ) ) {
			$template_key = $template_key[ TVE_LEADS_FIELD_TEMPLATE ];
		}

		$variation_template = isset( $this->variation[ TVE_LEADS_FIELD_TEMPLATE ] ) ? $this->variation[ TVE_LEADS_FIELD_TEMPLATE ] : '|';
		list( $type, $key ) = explode( '|', $template_key ? $template_key : $variation_template );

		return $type;
	}

	/**
	 * get the current template key (one_set, two_set etc)
	 *
	 * @param string|array $template_key optional array with TVE_LEADS_FIELD_TEMPLATE or directly the string key
	 *
	 * @return string
	 */
	public function key( $template_key = null ) {
		if ( is_array( $template_key ) ) {
			$template_key = $template_key[ TVE_LEADS_FIELD_TEMPLATE ];
		}

		list( $type, $key ) = explode( '|', $template_key ? $template_key : $this->variation[ TVE_LEADS_FIELD_TEMPLATE ] );

		return $key;
	}

	/**
	 * exchange data from $template to this->variation or vice-versa
	 *
	 * @param array $template
	 * @param string $dir can either be left-right or right-left
	 *
	 * @return array
	 */
	protected function interchange_data( $template, $dir = 'left -> right' ) {
		$fields = array(
			TVE_LEADS_FIELD_SAVED_CONTENT,
			TVE_LEADS_FIELD_INLINE_CSS,
			TVE_LEADS_FIELD_USER_CSS,
			TVE_LEADS_FIELD_GLOBALS,
			TVE_LEADS_FIELD_CUSTOM_FONTS,
			TVE_LEADS_FIELD_ICON_PACK,
			TVE_LEADS_FIELD_HAS_MASONRY,
			TVE_LEADS_FIELD_HAS_TYPEFOCUS,
		);

		foreach ( $fields as $field ) {
			if ( strpos( $dir, 'left' ) === 0 ) {
				$this->variation[ $field ] = $template[ $field ];
			} else {
				$template[ $field ] = $this->variation[ $field ];
			}
		}

		return $template;
	}

	protected function config() {
		$config = include dirname( dirname( dirname( __FILE__ ) ) ) . '/editor-templates/_config.php';

		$cloud = tve_leads_get_downloaded_templates( $this->type() );
		$type  = isset( $config[ $this->type() ] ) && is_array( $config[ $this->type() ] ) ? $config[ $this->type() ] : array();

		$config[ $this->type() ] = array_merge( $type, $cloud );

		return $config;
	}

	/**
	 * get all templates available for a Form
	 *
	 * @param string $str_form_type the form type
	 * @param bool $get_multi_step whether or not to return also templates designed (included) for the multi-step / state forms
	 *
	 * @return array
	 */
	public function get_all( $str_form_type = '', $get_multi_step = true ) {
		$config = $this->config();

		$type = $str_form_type ? $str_form_type : $this->type();

		$type = self::tpl_type_map( $type );

		if ( ! isset( $config[ $type ] ) ) {
			return array();
		}

		foreach ( $config[ $type ] as $key => $template ) {
			if ( ! $get_multi_step && ! empty( $template['multi_step'] ) ) {
				unset( $config[ $type ][ $key ] );
				continue;
			}
			$config[ $type ][ $key ]['key'] = $type . '|' . $key;
			/** if is  NOT from cloud */
			if ( ! isset( $config[ $type ][ $key ]['API_VERSION'] ) ) {
				$config[ $type ][ $key ]['thumbnail'] = plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'editor-templates/' . $type . '/thumbnails/' . $key . '.png';
			}
		}

		return $config[ $type ];
	}

	/**
	 * get all multi-step templates available for a Form
	 *
	 * @param string $str_form_type the form type
	 *
	 * @return array
	 */
	public function get_multi_step_templates( $str_form_type = '' ) {
		$config = $this->config();

		$type = $str_form_type ? $str_form_type : $this->type();

		$type = self::tpl_type_map( $type );

		$config = $config['multi_step'];

		if ( ! isset( $config[ $type ] ) ) {
			return array();
		}

		foreach ( $config[ $type ] as $key => $template ) {
			$config[ $type ][ $key ]['key']       = 'multi_step|' . $type . '|' . $key;
			$config[ $type ][ $key ]['thumbnail'] = plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'editor-templates/_multi_step/thumbnails/' . $type . '/' . $key . '.png';
		}

		return $config[ $type ];
	}

	/**
	 * remove and re-create all variation states based on the ones contained in the multi-step template
	 *
	 * @param string $template in the form of multi_step|{type}|{tpl}
	 */
	public function set_multi_step_template( $template ) {

		$config = $this->config();
		$config = $config['multi_step'];

		list( $ms, $type, $key ) = explode( '|', $template );

		/** append the cloud config too */
		$cloud_config = tve_leads_get_downloaded_templates( $type );
		if ( ! empty( $cloud_config['multi_step'] ) ) {
			$config = array_merge_recursive( $config, $cloud_config['multi_step'] );
		}

		if ( ! isset( $config[ $type ][ $key ] ) ) {
			return;
		}

		/**
		 * remove all variation child states
		 */
		global $tvedb;
		$tvedb->variation_delete_states( $this->variation['key'] );

		$data = $config[ $type ][ $key ];

		$to_save = array();

		$this->variation[ TVE_LEADS_FIELD_SELECTED_TEMPLATE ] = $key;

		/**
		 * step 1 - save each state and save the ID <=> index map
		 * hold references from state's index in the states array to the actual variation IDs (to be used in event manager when switching between states)
		 */
		$state_order = - 1;
		foreach ( $data['states'] as $index => $state_config ) {
			$state_order ++;
			if ( $state_order == 0 ) {
				// parent variation - this will always be the first state
				$this->variation[ TVE_LEADS_FIELD_TEMPLATE ]    = $state_config['tpl'];
				$this->variation[ TVE_LEADS_FIELD_STATE_INDEX ] = $index;
				$data['states'][ $index ]['state_key']          = $this->variation['key'];
				$data['states'][ $index ]['state_type']         = $this->type( $state_config['tpl'] );
				continue;
			}

			$child_state = $this->variation;
			unset( $child_state['key'] );

			$child_state['state_order']                   = $state_order;
			$child_state['parent_id']                     = $this->variation['key'];
			$child_state['form_state']                    = $state_config['state'];
			$child_state[ TVE_LEADS_FIELD_TEMPLATE ]      = $state_config['tpl'];
			$child_state[ TVE_LEADS_FIELD_SAVED_CONTENT ] = ''; // for now, no content
			$child_state[ TVE_LEADS_FIELD_STATE_INDEX ]   = $index;

			$child_state = tve_leads_save_form_variation( $child_state );

			$data['states'][ $index ]['state_key']  = $child_state['key'];
			$data['states'][ $index ]['state_type'] = $this->type( $state_config['tpl'] );

			$to_save [] = $child_state;

		}

		/**
		 * step 2 prepare Event Manager configuration related to the transition between states
		 */
		foreach ( $to_save as $variation ) {
			$state_type                                 = $this->type( $variation[ TVE_LEADS_FIELD_TEMPLATE ] );
			$content                                    = tve_leads_get_editor_template_content( $variation );
			$variation[ TVE_LEADS_FIELD_SAVED_CONTENT ] = $this->parse_state_events( $content, $state_type, $data['states'] );
			tve_leads_save_form_variation( $variation );
		}

		$content                                          = tve_leads_get_editor_template_content( $this->variation );
		$this->variation[ TVE_LEADS_FIELD_SAVED_CONTENT ] = $this->parse_state_events( $content, $type, $data['states'] );
	}

	/**
	 * parse and prepare event manager configuration for the 3 state-related actions : close lightbox, switch state, open lightbox state
	 *
	 * @param string $content variation template content
	 * @param string $state_type the current type of variation
	 * @param array $state_config array of all states containing
	 *
	 * @return string the parsed content
	 */
	protected function parse_state_events( $content, $state_type, $state_config ) {
		$close_screen_filler = '__TCB_EVENT_[{"t":"click","a":"tl_state_sf_close","config":{}}]_TNEVE_BCT__';
		$close_lightbox      = '__TCB_EVENT_[{"t":"click","a":"tl_state_lb_close","config":{}}]_TNEVE_BCT__';
		$close_form          = '__TCB_EVENT_[{"t":"click","a":"thrive_leads_form_close","config":{}}]_TNEVE_BCT__';
		$switch_state        = '__TCB_EVENT_[{"t":"click","a":"tl_state_switch","config":{"s":"%s"}}]_TNEVE_BCT__';
		$open_lightbox       = '__TCB_EVENT_[{"t":"click","a":"tl_state_lightbox","config":{"s":"%s","a":"zoom_in"}}]_TNEVE_BCT__';

		$content = str_replace( '|close_screen_filler|', htmlspecialchars( $close_screen_filler ), $content );
		$content = str_replace( '|close_lightbox|', htmlspecialchars( $close_lightbox ), $content );
		$content = str_replace( '|close_form|', htmlspecialchars( $close_form ), $content );

		foreach ( $state_config as $state_index => $data ) {
			/**
			 * on Lightboxes and all other non-lightbox states - the action should be 'switch_state'
			 */
			if ( $state_type == 'lightbox' || $data['state_type'] != 'lightbox' ) {
				$action = $switch_state;
			} else {
				$action = $open_lightbox;
			}
			$content = str_replace(
				'|open_state_' . $state_index . '|',
				htmlspecialchars( sprintf( $action, $data['state_key'] ) ),
				$content
			);
		}

		return $content;
	}


	/**
	 * API-calls after this point
	 * --------------------------------------------------------------------
	 */
	/**
	 * choose a new template
	 */
	public function api_choose() {
		if ( ! ( $template = $this->param( 'tpl' ) ) ) {
			exit();
		}

		$parent_variation = empty( $this->variation['parent_id'] ) ? $this->variation : tve_leads_get_form_variation( null, $this->variation['parent_id'] );

		/**
		 * This is a user-saved template
		 */
		if ( strpos( $template, 'user-saved-template-' ) === 0 ) {
			/* at this point, the template is one of the previously saved templates (saved by the user) -
				it holds the index from the option array which needs to be loaded */
			$contents = get_option( self::OPTION_TPL_CONTENT );
			$meta     = get_option( self::OPTION_TPL_META );

			$template_index = intval( str_replace( 'user-saved-template-', '', $template ) );

			/* make sure we don't mess anything up */
			if ( empty( $contents ) || empty( $meta ) || ! isset( $contents[ $template_index ] ) ) {
				return;
			}
			$tpl_data = $contents[ $template_index ];
			$template = $meta[ $template_index ][ TVE_LEADS_FIELD_TEMPLATE ];

			$this->interchange_data( $tpl_data, 'left -> right' );

			$this->variation[ TVE_LEADS_FIELD_TEMPLATE ] = $template;
		} elseif ( strpos( $template, 'multi_step|' ) === 0 ) {
			/**
			 * use has selected a multi-step template, we need to:
			 *      - delete all existing variation states
			 *      - create each of the variation states that are included in the template
			 */
			$this->variation = $parent_variation;
			$this->set_multi_step_template( $template );

		} else {
			$chunks                                               = explode( '|', $template );
			$this->variation[ TVE_LEADS_FIELD_TEMPLATE ]          = $template;
			$this->variation[ TVE_LEADS_FIELD_SELECTED_TEMPLATE ] = end( $chunks );
			$this->variation[ TVE_LEADS_FIELD_SAVED_CONTENT ]     = tve_leads_get_editor_template_content( $this->variation, $template );
			$this->variation[ TVE_LEADS_FIELD_SAVED_CONTENT ]     = $this->parse_state_events( $this->variation[ TVE_LEADS_FIELD_SAVED_CONTENT ], self::type( $template ), array() );
		}

		tve_leads_save_form_variation( $this->variation );

		$stateManager = Thrive_Leads_State_Manager::instance( $parent_variation );

		return $stateManager->state_data( $this->variation );
	}

	/**
	 * reset to default content
	 */
	public function api_reset() {
		$state_type       = $this->type( $this->variation[ TVE_LEADS_FIELD_TEMPLATE ] );
		$variation_states = tve_leads_get_form_related_states( $this->variation );
		$state_config     = array();

		foreach ( $variation_states as $state ) {
			if ( empty( $state[ TVE_LEADS_FIELD_STATE_INDEX ] ) ) {
				continue;
			}
			$state_config[ $state[ TVE_LEADS_FIELD_STATE_INDEX ] ] = array(
				'state_type' => $this->type( $state[ TVE_LEADS_FIELD_TEMPLATE ] ),
				'state_key'  => $state['key']
			);
		}

		$content                                          = tve_leads_get_editor_template_content( $this->variation );
		$this->variation[ TVE_LEADS_FIELD_SAVED_CONTENT ] = $this->parse_state_events( $content, $state_type, $state_config );
		tve_leads_save_form_variation( $this->variation );

		$variation = empty( $this->variation['parent_id'] ) ? $this->variation : tve_leads_get_form_variation( null, $this->variation['parent_id'] );

		$stateManager = Thrive_Leads_State_Manager::instance( $variation );

		return $stateManager->state_data( $this->variation );
	}

	/**
	 * get user-saved templates
	 */
	public function api_get_saved() {
		$only_current_template = (int) $this->param( 'current_template' );
		$form_type             = get_post_meta( (int) $this->param( 'post_id' ), 'tve_form_type', true );
		$config                = $this->config();
		$variation_key         = (int) $this->param( '_key' );
		$variation             = tve_leads_get_form_variation( null, $variation_key );

		//for two step lightbox we have two types of forms: lightbox and screenfiller
		if ( $form_type == 'two_step_lightbox' ) {
			//if the current variation is the default one, then we display both screen filler and lightbox saved templates
			if ( $variation['parent_id'] == 0 ) {
				$form_type = array( 'screen_filler', 'lightbox' );
			} else {
				//if this is a secondary state, we display only the templates that are the same type as the parrent
				$parent_variation = tve_leads_get_form_variation( null, $variation['parent_id'] );
				$form_type        = array( $this->type( $parent_variation ) );
			}
		} else {
			/* if the user is editing a multi-step form (e.g. a shortcode) and the current state is a lightbox state, we need to return the saved lightbox templates */
			if ( ! empty( $variation['parent_id'] ) && $variation['form_state'] == 'lightbox' ) {
				$form_type = array( 'lightbox' );
			} else {
				$form_type = array( self::tpl_type_map( $form_type ) );
			}
		}

		$templates = get_option( self::OPTION_TPL_META );
		$templates = empty( $templates ) ? array() : array_reverse( $templates, true ); // order by date DESC

		$html = '';

		$input   = '<input type="hidden" class="lp_code" value="user-saved-template-%s"/>';
		$img     = '<img src="' . TVE_LEADS_URL . 'editor-templates/%s/thumbnails/%s" width="166" height="140"/>';
		$caption = '<span class="tve_cell_caption_holder"><span class="tve_cell_caption">%s</span></span><span class="tve_cell_check tve_icm tve-ic-checkmark"></span>';

		$item = '<span class="tve_grid_cell tve_landing_page_template tve_click" title="Choose %s">%s</span>';

		foreach ( $templates as $index => $template ) {
			/* make sure we only load the same type, e.g. ribbon */
			if ( ! in_array( $this->type( $template ), $form_type ) ) {
				continue;
			}

			if ( ! empty( $only_current_template ) && $this->variation[ TVE_LEADS_FIELD_TEMPLATE ] != $template[ TVE_LEADS_FIELD_TEMPLATE ] ) {
				continue;
			}
			$is_multi_step = ! empty( $config[ $this->type( $template ) ][ $this->key( $template ) ]['multi_step'] ) ? $config[ $this->type( $template ) ][ $this->key( $template ) ]['multi_step'] : false;
			if ( $is_multi_step ) {
				$img_name = explode( '_vms_step', $this->key( $template ) );
				$image    = sprintf( $img, '_multi_step', $this->type( $template ) . '/' . $img_name[0] . '.png' );
			} else {
				$image = sprintf( $img, $this->type( $template ), $this->key( $template ) . '.png' );
			}

			$_content = sprintf( $input, $index ) .
			            $image .
			            sprintf( $caption, $template['name'] . ' (' . strftime( '%d.%m.%y', strtotime( $template['date'] ) ) . ')' );
			$html .= sprintf( $item, $template['name'], $_content );
		}

		return $html ? $html : __( 'No saved templates found', 'thrive-leads' );
	}

	/**
	 * Save the current variation config and content as a template so that it can later be applied to other variation
	 */
	public function api_save() {
		/**
		 * we keep the template content separately from the template meta data (name and date)
		 */
		if ( empty( $this->variation[ TVE_LEADS_FIELD_GLOBALS ] ) ) {
			$this->variation[ TVE_LEADS_FIELD_GLOBALS ] = array( 'e' => 1 );
		}
		$template_content = $this->interchange_data( array(), 'right -> left' );

		$template_meta     = array(
			'name'                   => $this->param( 'name', '' ),
			TVE_LEADS_FIELD_TEMPLATE => $this->variation[ TVE_LEADS_FIELD_TEMPLATE ],
			'date'                   => date( 'Y-m-d' )
		);
		$templates_content = get_option( self::OPTION_TPL_CONTENT ); // this should get unserialized automatically
		$templates_meta    = get_option( self::OPTION_TPL_META ); // this should get unserialized automatically

		if ( empty( $templates_content ) ) {
			$templates_content = array();
			$templates_meta    = array();
		}
		$templates_content [] = $template_content;
		$templates_meta []    = $template_meta;

		// make sure these are not autoloaded, as it is a potentially huge array
		add_option( self::OPTION_TPL_CONTENT, null, '', 'no' );

		update_option( self::OPTION_TPL_CONTENT, $templates_content );
		update_option( self::OPTION_TPL_META, $templates_meta );

		return array(
			'message' => __( 'Template saved.', 'thrive_leads' ),
			'list'    => $this->api_get_saved()
		);
	}

	/**
	 * delete a previously - saved template
	 */
	public function api_delete() {
		$tpl_index = (int) str_replace( 'user-saved-template-', '', $this->param( 'tpl' ) );

		$contents = get_option( self::OPTION_TPL_CONTENT );
		$meta     = get_option( self::OPTION_TPL_META );

		if ( ! isset( $contents[ $tpl_index ] ) || ! isset( $meta[ $tpl_index ] ) ) {
			return $this->api_get_saved();
		}

		array_splice( $contents, $tpl_index, 1 );
		array_splice( $meta, $tpl_index, 1 );

		update_option( self::OPTION_TPL_CONTENT, array_values( $contents ) );
		update_option( self::OPTION_TPL_META, array_values( $meta ) );

		return $this->api_get_saved();
	}


}