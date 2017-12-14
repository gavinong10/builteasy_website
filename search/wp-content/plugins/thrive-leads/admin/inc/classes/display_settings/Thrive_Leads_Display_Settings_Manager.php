<?php

class Thrive_Leads_Display_Settings_Manager {
	protected $version;

	public function __construct( $version ) {
		$this->version = $version;
	}

	public function initHangers( $group ) {
		$hangers[] = new Thrive_Leads_Hanger( 'show_group_options', $group );
		$hangers[] = new Thrive_Leads_Hanger( 'hide_group_options', $group );

		/**
		 * @var $hanger Thrive_Leads_Hanger
		 */
		foreach ( $hangers as $hanger ) {
			$hanger->initTabs( array(
				'other_screens'     => __( 'Basic Settings', 'thrive-leads' ),
				'taxonomy_terms'    => __( 'Categories etc.', 'thrive-leads' ),
				'posts'             => __( 'Posts', 'thrive-leads' ),
				'pages'             => __( 'Pages', 'thrive-leads' ),
				'page_templates'    => __( 'Page Templates', 'thrive-leads' ),
				'post_types'        => __( 'Post Types', 'thrive-leads' ),
				'taxonomy_archives' => __( 'Archive Pages', 'thrive-leads' ),
				'others'            => __( 'Other', 'thrive-leads' )
			) );
		}

		return $hangers;
	}

	public function get_popup_data() {
		$this->load_dependencies();

		$group = $_GET['group'];

		try {
			$hangers = $this->initHangers( $group );

			$options = new Thrive_Leads_Group_Options( $group );
			$options->initOptions();

			//used in file included at the end of this function
			$saved_templates = apply_filters( 'thrive_display_options_get_templates', array() );

		} catch ( Exception $e ) {
			var_dump( $e->getMessage() );
			die;
		}

		return array(
			'hangers'        => $hangers,
			'savedTemplates' => $saved_templates
		);
	}

	public function load_template() {
		$this->load_dependencies();

		$templates = new Thrive_Leads_Saved_Options();
		$template_id = $_REQUEST['template_id'];
		if ( strpos( $template_id, 'TL-' ) === 0 ) {
			$template_id = str_replace( 'TL-', '', $template_id );
		} else {
			$template = apply_filters( 'thrive_display_options_get_template', array(), $template_id );
		}
		$templates->initOptions( isset( $template ) ? false : $template_id, isset( $template ) ? $template : null );

		$hangers = array(
			new Thrive_Leads_Hanger( 'show_group_options', $_REQUEST['group'] ),
			new Thrive_Leads_Hanger( 'hide_group_options', $_REQUEST['group'] ),
		);

		$identifiers = array(
			'other_screens'     => __( 'Basic Settings', 'thrive-leads' ),
			'taxonomy_terms'    => __( 'Categories etc.', 'thrive-leads' ),
			'posts'             => __( 'Posts', 'thrive-leads' ),
			'pages'             => __( 'Pages', 'thrive-leads' ),
			'page_templates'    => __( 'Page Templates', 'thrive-leads' ),
			'post_types'        => __( 'Post Types', 'thrive-leads' ),
			'taxonomy_archives' => __( 'Archive Pages', 'thrive-leads' ),
			'others'            => __( 'Other', 'thrive-leads' )
		);

		/**
		 * @var $hanger Thrive_Leads_Hanger
		 */
		foreach ( $hangers as $hanger ) {
			/**
			 * @var $tab Thrive_Leads_Tab
			 */
			foreach ( $identifiers as $identifier => $label ) {

				$tab = Thrive_Leads_Tab_Factory::build( $identifier );
				$tab->setGroup( $_REQUEST['group'] )
				    ->setIdentifier( $identifier )
				    ->setSavedOptions( new Thrive_Leads_Group_Options( $_REQUEST['group'], $templates->getShowGroupOptions(), $templates->getHideGroupOptions() ) )
				    ->setLabel( $label )
				    ->setHanger( $hanger->identifier )
				    ->initOptions()
				    ->initFilters();
				$hanger->tabs[] = $tab;
			}
		}
		wp_send_json( $hangers );

	}

	public function getSavedTemplates() {
		$savedTemplates = new Thrive_Leads_Saved_Options();
		$templates      = $savedTemplates->getAll();

		foreach ( $templates as $template ) {
			$template->id                 = TVE_Dash_Product_LicenseManager::TL_TAG . $template->id;
			$template->show_group_options = $this->processTpl( json_decode( stripcslashes( $template->show_group_options ), true ) );
			$template->hide_group_options = $this->processTpl( json_decode( stripcslashes( $template->hide_group_options ), true ) );
		}

		return $templates;
	}

	protected function processTpl( $savedOptions ) {
		$return = array();
		foreach ( $savedOptions['tabs'] as $index => $tab ) {
			$options  = $this->checkBackwardsComp( $tab['options'] );
			$return[] = array(
				'options' => $options,
				'index'   => $index
			);
		}

		return $return;
	}

	public function checkBackwardsComp( $options ) {
		$return = array();
		foreach ( $options as $o ) {
			if ( is_array( $o ) ) {
				if ( ! empty( $o['isChecked'] ) || ( ! empty( $o['type'] ) && $o['type'] == 'direct_url' ) ) {
					$return [] = $o['id'];
				}
			} else {
				$return [] = $o;
			}
		}

		return $return;
	}

	public function save_options() {
		if ( empty( $_POST['options'] ) || empty( $_POST['group'] ) ) {
			return __( 'Empty values', 'thrive-leads' );
		}

		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Group_Options.php';

		$group = new Thrive_Leads_Group_Options( $_POST['group'], $_POST['options'][0], $_POST['options'][1] );

		return $group->save();
	}

	public function save_template() {
		if ( empty( $_POST['options'] ) || empty( $_POST['name'] ) ) {
			return false;
		}

		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Saved_Options.php';

		$template = new Thrive_Leads_Saved_Options( $_POST['name'], $_POST['options'][0], $_POST['options'][1], '' );

		return $template->save();

	}

	/**
	 * Load all the dependencies that are needed for this manager
	 */
	public function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Filter.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Action.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Option.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Hanger.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Tab_Interface.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Tab_Factory.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Posts_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Pages_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Page_Templates_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Post_Types_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Taxonomy_Archives_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Taxonomy_Terms_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Other_Screens_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Direct_Urls_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Visitors_Status_Tab.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Group_Options.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Saved_Options.php';
		require_once plugin_dir_path( __FILE__ ) . 'Thrive_Leads_Others_Tab.php';
	}

}
