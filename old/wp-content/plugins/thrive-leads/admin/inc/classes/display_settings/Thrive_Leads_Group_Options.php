<?php

/**
 * Class GroupOptions
 * JSON options saved by user in database
 * Mapper model over database table
 */
class Thrive_Leads_Group_Options {
	private $table_name = 'group_options';
	private $group;
	private $description;
	public $show_group_options;
	public $hide_group_options;
	private $db;

	protected $flags = array();

	public function __construct( $group, $show_group_options = '', $hide_group_options = '' ) {
		/**
		 * @var $wpdb wpdb
		 */
		global $wpdb;
		$this->db                 = $wpdb;
		$this->table_name         = tve_leads_table_name( $this->table_name );
		$this->group              = $group;
		$this->show_group_options = $show_group_options;
		$this->hide_group_options = $hide_group_options;
	}

	protected function _processPreSave( $jsonOptions ) {
		$options = @json_decode( stripcslashes( $jsonOptions ), true );
		if ( empty( $options ) || empty( $options['tabs'] ) ) {
			return json_encode( array( 'identifier' => $jsonOptions['identifier'] ) );
		}

		$clean_options = array();

		foreach ( $options['tabs'] as $index => $tabOptions ) {
			$clean_options['tabs'][ $index ]['options'] = $tabOptions;
		}

		return json_encode( $clean_options );
	}

	public function save() {
		if ( $this->delete() === false ) {
			return $this->db->last_error;
		}

		$this->db->suppress_errors();
		$show_options = $this->_processPreSave( $this->show_group_options );
		$hide_options = $this->_processPreSave( $this->hide_group_options );

		return $this->db->insert( $this->table_name, array(
			'group'              => $this->group,
			'description'        => $this->description,
			'show_group_options' => $show_options,
			'hide_group_options' => $hide_options
		) ) !== false ? true : $this->db->last_error;
	}

	/**
	 * Deletes Group
	 *
	 * @return false|int Affected rows on success or false on error
	 */
	public function delete() {
		//old code for WP 4.1.1
		//$this->db->delete($this->table_name, array('`group`' => $this->group));

		//new code for WP 4.1.2
		$result = $this->db->query(
			$this->db->prepare( "DELETE FROM `{$this->table_name}` WHERE `group` = %d", $this->group )
		);

		return $result;
	}

	/**
	 * Read options from database
	 * @return $this
	 */
	public function initOptions() {
		$sql = "SELECT * FROM {$this->table_name} WHERE `group` = '{$this->group}'";
		$row = $this->db->get_row( $sql );
		if ( $row ) {
			$this->show_group_options = $row->show_group_options;
			$this->hide_group_options = $row->hide_group_options;
			$this->description        = $row->description;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getShowGroupOptions() {
		return $this->show_group_options;
	}

	/**
	 * @return string
	 */
	public function getHideGroupOptions() {
		return $this->hide_group_options;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	// get current URL
	public function get_current_URL() {
		$requested_url = is_ssl() ? 'https://' : 'http://';
		$requested_url .= $_SERVER['HTTP_HOST'];
		$requested_url .= $_SERVER['REQUEST_URI'];

		return $requested_url;
	}

	/**
	 * Check if any option is checked
	 * @return bool
	 */
	public function checkForAnyOptionChecked() {
		$showingOptions = @json_decode( stripcslashes( $this->getShowGroupOptions() ) );
		if ( empty( $showingOptions ) ) {
			return false;
		}

		$optionsChecked = strpos( $this->getShowGroupOptions(), "true" );
		if ( $optionsChecked ) {
			return true;
		}
		foreach ( $showingOptions->tabs as $tab ) {
			if ( ! empty( $tab->options ) ) {
				foreach ( $tab->options as $opt ) {
					if ( ! is_object( $opt ) ) {
						return true;
					}
				}
			}
		}

		if ( empty( $showingOptions->tabs[7] ) ) {
			return false;
		}

		foreach ( $showingOptions->tabs[7]->options as $item ) {
			if ( is_object( $item ) ) {
				if ( $item->isChecked || $item->type == 'direct_url' ) {
					return true;
				}
			} else {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function displayGroup() {
		$display = true;

		/**
		 * if none of the options is not selected keep displaying the group
		 * and let the Wordpress apply its logic on it
		 */
		if ( ! $this->checkForAnyOptionChecked() ) {
			return false;
		}

		if ( is_front_page() ) {

			/* @var $otherScreensTab OtherScreensTab */
			$otherScreensTab = Thrive_Leads_Tab_Factory::build( 'other_screens' );
			$otherScreensTab->setSavedOptions( $this );

			/* @var $directUrlsTab DirectUrlsTab */
			$directUrlsTab = Thrive_Leads_Tab_Factory::build( 'direct_urls' );
			$directUrlsTab->setSavedOptions( $this );

			/* @var $visitorsStatusTab VisitorsStatusTab */
			$visitorsStatusTab = Thrive_Leads_Tab_Factory::build( 'visitors_status' );
			$visitorsStatusTab->setSavedOptions( $this );
			$visitorsStatus = is_user_logged_in() ? 'logged_in' : 'logged_out';

			$inclusion = $otherScreensTab->isScreenAllowed( 'front_page' )
			             || $directUrlsTab->isUrlAllowed( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusAllowed( $visitorsStatus );

			if ( $inclusion === false ) {
				return false;
			}

			$exclusion = $otherScreensTab->isScreenDenied( 'front_page' )
			             || $directUrlsTab->isUrlDenied( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusDenied( $visitorsStatus );

			if ( $exclusion === true ) {
				$display = false;
			}

			//endif is_front_page

		} else if ( is_home() ) {

			/* @var $otherScreensTab OtherScreensTab */
			$otherScreensTab = Thrive_Leads_Tab_Factory::build( 'other_screens' );
			$otherScreensTab->setSavedOptions( $this );

			/* @var $directUrlsTab DirectUrlsTab */
			$directUrlsTab = Thrive_Leads_Tab_Factory::build( 'direct_urls' );
			$directUrlsTab->setSavedOptions( $this );

			/* @var $visitorsStatusTab VisitorsStatusTab */
			$visitorsStatusTab = Thrive_Leads_Tab_Factory::build( 'visitors_status' );
			$visitorsStatusTab->setSavedOptions( $this );
			$visitorsStatus = is_user_logged_in() ? 'logged_in' : 'logged_out';

			$inclusion = $otherScreensTab->isScreenAllowed( 'blog_index' )
			             || $directUrlsTab->isUrlAllowed( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusAllowed( $visitorsStatus );

			if ( $inclusion === false ) {
				return false;
			}

			$exclusion = $otherScreensTab->isScreenDenied( 'blog_index' )
			             || $directUrlsTab->isUrlDenied( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusDenied( $visitorsStatus );

			if ( $exclusion === true ) {
				$display = false;
			}

		} else if ( is_page() ) {

			/* @var $post WP_Post */
			global $post;

			/** @var Thrive_Leads_Other_Screens_Tab $otherScreensTab */
			$otherScreensTab = Thrive_Leads_Tab_Factory::build( 'other_screens' );
			$otherScreensTab->setSavedOptions( $this );

			/* @var $pagesTab PagesTab */
			$pagesTab = Thrive_Leads_Tab_Factory::build( 'pages' );
			$pagesTab->setSavedOptions( $this );

			/* @var $pageTemplatesTab PageTemplatesTab */
			$pageTemplatesTab = Thrive_Leads_Tab_Factory::build( 'page_templates' );
			$pageTemplatesTab->setSavedOptions( $this );

			/* @var $postTypesTab PostTypesTab */
			$postTypesTab = Thrive_Leads_Tab_Factory::build( 'post_types' );
			$postTypesTab->setSavedOptions( $this );

			/* @var $directUrlsTab DirectUrlsTab */
			$directUrlsTab = Thrive_Leads_Tab_Factory::build( 'direct_urls' );
			$directUrlsTab->setSavedOptions( $this );

			/* @var $visitorsStatusTab VisitorsStatusTab */
			$visitorsStatusTab = Thrive_Leads_Tab_Factory::build( 'visitors_status' );
			$visitorsStatusTab->setSavedOptions( $this );
			$visitorsStatus = is_user_logged_in() ? 'logged_in' : 'logged_out';

			/* @var $taxonomyTermsTab Thrive_Leads_Taxonomy_Terms_Tab */
			$taxonomyTermsTab = Thrive_Leads_Tab_Factory::build( 'taxonomy_terms' );
			$taxonomyTermsTab->setSavedOptions( $this );

			$inclusion = $otherScreensTab->allTypesAllowed( get_post_type() ) || $pagesTab->isPageAllowed( $post )
			             || $postTypesTab->isTypeAllowed( get_post_type() )
			             || $directUrlsTab->isUrlAllowed( $this->get_current_URL() )
			             || $pageTemplatesTab->isTemplateAllowed( basename( get_page_template() ) )
			             || $visitorsStatusTab->isStatusAllowed( $visitorsStatus )
			             || $taxonomyTermsTab->isPostAllowed( $post );

			if ( $inclusion === false ) {
				return false;
			}

			$exclusion = $otherScreensTab->allTypesDenied( get_post_type() ) || $pagesTab->isPageDenied( $post )
			             || $postTypesTab->isDeniedType( get_post_type() )
			             || $directUrlsTab->isUrlDenied( $this->get_current_URL() )
			             || $pageTemplatesTab->isTemplateDenied( basename( get_page_template() ) )
			             || $visitorsStatusTab->isStatusDenied( $visitorsStatus )
			             || $taxonomyTermsTab->isPostDenied( $post );

			if ( $exclusion === true ) {
				$display = false;
			}

			//endif is_page

		} else if ( is_single() ) {

			/* @var $post WP_Post */
			global $post;

			/** @var Thrive_Leads_Other_Screens_Tab $otherScreensTab */
			$otherScreensTab = Thrive_Leads_Tab_Factory::build( 'other_screens' );
			$otherScreensTab->setSavedOptions( $this );

			/* @var $postsTab PostsTab */
			$postsTab = Thrive_Leads_Tab_Factory::build( 'posts' );
			$postsTab->setSavedOptions( $this );

			/* @var $postTypesTab PostTypesTab */
			$postTypesTab = Thrive_Leads_Tab_Factory::build( 'post_types' );
			$postTypesTab->setSavedOptions( $this );

			/* @var $directUrlsTab DirectUrlsTab */
			$directUrlsTab = Thrive_Leads_Tab_Factory::build( 'direct_urls' );
			$directUrlsTab->setSavedOptions( $this );

			/* @var $visitorsStatusTab VisitorsStatusTab */
			$visitorsStatusTab = Thrive_Leads_Tab_Factory::build( 'visitors_status' );
			$visitorsStatusTab->setSavedOptions( $this );
			$visitorsStatus = is_user_logged_in() ? 'logged_in' : 'logged_out';

			/* @var $taxonomyTermsTab Thrive_Leads_Taxonomy_Terms_Tab */
			$taxonomyTermsTab = Thrive_Leads_Tab_Factory::build( 'taxonomy_terms' );
			$taxonomyTermsTab->setSavedOptions( $this );

			$inclusion = $otherScreensTab->allTypesAllowed( get_post_type() ) || $postsTab->isPostAllowed( $post )
			             || $postTypesTab->isTypeAllowed( get_post_type() )
			             || $directUrlsTab->isUrlAllowed( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusAllowed( $visitorsStatus )
			             || $taxonomyTermsTab->isPostAllowed( $post );

			if ( $inclusion === false ) {
				return false;
			}
			
			$this->flag( 'direct_url_match', $directUrlsTab->isUrlAllowed( $this->get_current_URL() ) );
			
			$exclusion = $otherScreensTab->allTypesDenied( get_post_type() ) || $postsTab->isPostDenied( $post )
			             || $postTypesTab->isDeniedType( get_post_type() )
			             || $directUrlsTab->isUrlDenied( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusDenied( $visitorsStatus )
			             || $taxonomyTermsTab->isPostDenied( $post );

			if ( $exclusion === true ) {
				$display = false;
			}

			//endif is_single

		} else if ( is_archive() ) {

			$taxonomy = get_queried_object();

			/* @var $taxonomyArchivesTab TaxonomyArchivesTab */
			$taxonomyArchivesTab = Thrive_Leads_Tab_Factory::build( 'taxonomy_archives' );
			$taxonomyArchivesTab->setSavedOptions( $this );

			/* @var $directUrlsTab DirectUrlsTab */
			$directUrlsTab = Thrive_Leads_Tab_Factory::build( 'direct_urls' );
			$directUrlsTab->setSavedOptions( $this );

			/* @var $visitorsStatusTab VisitorsStatusTab */
			$visitorsStatusTab = Thrive_Leads_Tab_Factory::build( 'visitors_status' );
			$visitorsStatusTab->setSavedOptions( $this );
			$visitorsStatus = is_user_logged_in() ? 'logged_in' : 'logged_out';

			$inclusion = $taxonomyArchivesTab->isTaxonomyAllowed( $taxonomy )
			             || $directUrlsTab->isUrlAllowed( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusAllowed( $visitorsStatus );

			if ( $inclusion === false ) {
				return false;
			}

			$exclusion = $taxonomyArchivesTab->isTaxonomyDenied( $taxonomy )
			             || $directUrlsTab->isUrlDenied( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusDenied( $visitorsStatus );

			if ( $exclusion === true ) {
				$display = false;
			}

			//endif is_archive

		} else if ( is_404() ) {

			/* @var $otherScreensTab Thrive_Leads_Other_Screens_Tab */
			$otherScreensTab = Thrive_Leads_Tab_Factory::build( 'other_screens' );
			$otherScreensTab->setSavedOptions( $this );

			/* @var $directUrlsTab DirectUrlsTab */
			$directUrlsTab = Thrive_Leads_Tab_Factory::build( 'direct_urls' );
			$directUrlsTab->setSavedOptions( $this );

			/* @var $visitorsStatusTab VisitorsStatusTab */
			$visitorsStatusTab = Thrive_Leads_Tab_Factory::build( 'visitors_status' );
			$visitorsStatusTab->setSavedOptions( $this );
			$visitorsStatus = is_user_logged_in() ? 'logged_in' : 'logged_out';

			$inclusion = $otherScreensTab->isScreenAllowed( '404_error_page' )
			             || $directUrlsTab->isUrlAllowed( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusAllowed( $visitorsStatus );

			if ( $inclusion === false ) {
				return false;
			}

			$exclusion = $otherScreensTab->isScreenDenied( '404_error_page' )
			             || $directUrlsTab->isUrlDenied( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusDenied( $visitorsStatus );

			if ( $exclusion === true ) {
				$display = false;
			}

			//endif is_404

		} else if ( is_search() ) {

			/* @var $otherScreensTab OtherScreensTab */
			$otherScreensTab = Thrive_Leads_Tab_Factory::build( 'other_screens' );
			$otherScreensTab->setSavedOptions( $this );

			/* @var $directUrlsTab DirectUrlsTab */
			$directUrlsTab = Thrive_Leads_Tab_Factory::build( 'direct_urls' );
			$directUrlsTab->setSavedOptions( $this );

			/* @var $visitorsStatusTab VisitorsStatusTab */
			$visitorsStatusTab = Thrive_Leads_Tab_Factory::build( 'visitors_status' );
			$visitorsStatusTab->setSavedOptions( $this );
			$visitorsStatus = is_user_logged_in() ? 'logged_in' : 'logged_out';

			$inclusion = $otherScreensTab->isScreenAllowed( 'search_page' )
			             || $directUrlsTab->isUrlAllowed( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusAllowed( $visitorsStatus );

			if ( $inclusion === false ) {
				return false;
			}

			$exclusion = $otherScreensTab->isScreenDenied( 'search_page' )
			             || $directUrlsTab->isUrlDenied( $this->get_current_URL() )
			             || $visitorsStatusTab->isStatusDenied( $visitorsStatus );

			if ( $exclusion === true ) {
				$display = false;
			}

			//endif is_search
		} else {
			$current_url = $this->get_current_URL();
			/* @var $directUrlsTab Thrive_Leads_Direct_Urls_Tab */
			$directUrlsTab = Thrive_Leads_Tab_Factory::build( 'direct_urls' );
			$directUrlsTab->setSavedOptions( $this );

			$display = $directUrlsTab->isUrlAllowed( $current_url ) && ! $directUrlsTab->isUrlDenied( $current_url );
			$this->flag( 'direct_url_match', $display );
		}

		return $display;
	}

	public function getTabSavedOptions( $tabIndex, $hanger ) {
		$options = json_decode( stripcslashes( $this->$hanger ) );

		if ( empty( $options ) || empty( $options->tabs[ $tabIndex ] ) || empty( $options->tabs[ $tabIndex ]->options ) ) {
			return array();
		}
		$opts   = $options->tabs[ $tabIndex ]->options;
		$return = array();
		foreach ( $opts as $option ) {
			if ( is_object( $option ) ) {
				if ( ! $option->isChecked && $option->type != 'direct_url' ) {
					continue;
				}
				$return [] = $option->id;
			} else {
				$return [] = $option;
			}
		}

		return $return;
	}

	public function flag( $set = null, $value = null ) {
		if ( null === $value ) {
			return isset( $this->flags[ $set ] ) ? $this->flags[ $set ] : null;
		}
		$this->flags[ $set ] = $value;
	}
}
