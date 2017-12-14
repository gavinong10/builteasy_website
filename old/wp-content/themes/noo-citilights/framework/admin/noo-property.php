<?php
if(!class_exists('NooPropertyFilterDropdown')):
	class NooPropertyFilterDropdown extends Walker {

		var $tree_type = 'category';
		var $db_fields = array ('parent' => 'parent', 'id' => 'term_id', 'slug' => 'slug' );

		public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {

			if ( ! empty( $args['hierarchical'] ) )
				$pad = str_repeat('-', $depth * 2);
			else
				$pad = '';

			$cat_name = $cat->name;

			$value = isset( $args['value'] ) && $args['value'] == 'id' ? $cat->term_id : $cat->slug;

			$output .= "\t<option class=\"level-$depth\" value=\"" . $value . "\"";

			if ( $value == $args['selected'] || ( is_array( $args['selected'] ) && in_array( $value, $args['selected'] ) ) )
				$output .= ' selected="selected"';

			$output .= '>';

			$output .= $pad . $cat_name;

			// if ( ! empty( $args['show_count'] ) ) {
			// 	$output .= '&nbsp;(' . $cat->count . ')';
			// }

			$output .= "</option>\n";
		}

		public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
			if ( ! $element || 0 === $element->count ) {
				return;
			}
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}
	}
endif;

if(!class_exists('NooPropertySearchDropdown')):
	class NooPropertySearchDropdown extends Walker {

		var $tree_type = 'category';
		var $db_fields = array ('parent' => 'parent', 'id' => 'term_id', 'slug' => 'slug' );

		public function start_el( &$output, $term, $depth = 0, $args = array(), $current_object_id = 0 ) {

			if ( ! empty( $args['hierarchical'] ) ) {
				$pad = str_repeat('-', $depth * 2);
				$pad = !empty( $pad ) ? $pad . '&nbsp;' : '';
			} else {
				$pad = '';
			}

			$cat_name = $term->name;

			$value = isset( $args['value'] ) && $args['value'] == 'id' ? $term->term_id : $term->slug;
			$parent = '';
			if( $args['taxonomy'] == 'property_sub_location' ) {
				$parent_data = get_option( 'noo_sub_location_parent' );
				if( isset( $parent_data[$term->term_id] ) ) {
					$parent_location = get_term_by('id',$parent_data[$term->term_id],'property_location');
					$parent .= ' data-parent-location="' . $parent_location->slug . '"';
				}
			}

			$output .= "\t<li class=\"level-$depth\" $parent><a href=\"#\" data-value=\"" . $value . "\">";
			$output .= $pad . $cat_name;
			// if ( ! empty( $args['show_count'] ) ) {
			// 	$output .= '&nbsp;(' . $term->count . ')';
			// }
			$output .= "</a></li>\n";
		}

		public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
			if ( ! $element || 0 === $element->count ) {
				return;
			}
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}
	}
endif;

if(!function_exists('noo_dropdown_search')):
	function noo_dropdown_search($args = ''){
		$defaults = array(
			'show_option_all' => '', 'show_option_none' => '',
			'orderby' => 'name', 'order' => 'ASC',
			'show_count' => 1,
			'hide_empty' => 1, 'child_of' => 0,
			'exclude' => '', 'echo' => 1,
			'hierarchical' => 1,
			'depth' => 0,
			'taxonomy' => 'category',
			'hide_if_empty' => false,
			'option_none_value' => '',
			'meta' => '',
			'walker'=>new NooPropertySearchDropdown
		);
		$defaults['selected'] = ( is_category() ) ? get_query_var( 'cat' ) : 0;
		$r = wp_parse_args( $args, $defaults );
		$taxonomies = get_terms( $r['taxonomy'], $r );
		if ( ! $r['hide_if_empty'] || ! empty( $taxonomies ) ) {
			$output = "<ul class=\"dropdown-menu\">\n";
		} else {
			$output = '';
		}
		
		if ( empty( $taxonomies ) && ! $r['hide_if_empty'] && ! empty( $r['show_option_none'] ) ) {
			$show_option_none = $r['show_option_none'];
			$output .= "\t<li><a data-value=\"\" href=\"#\">$show_option_none</a></li>\n";
		}
		if ( $r['show_option_none'] ) {
			$show_option_none = $r['show_option_none'];
			$output .= "\t<li><a data-value=\"\" href=\"#\">$show_option_none</a></li>\n";
		}
		
		if ( $r['hierarchical'] ) {
			$depth = $r['depth'];  // Walk the full depth.
		} else {
			$depth = -1; // Flat.
		}
		$output .= walk_category_dropdown_tree( $taxonomies, $depth, $r );
		
		if ( ! $r['hide_if_empty'] || ! empty( $taxonomies ) ) {
			$output .= "</ul>\n";
		}
		if ( $r['echo'] ) {
			echo $output;
		}
		return $output;
	}
endif;

/* -------------------------------------------------------
 * Create functions noo_dropdown_taxonomy
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_dropdown_taxonomy' ) ) :
	
	function noo_dropdown_taxonomy( $type = '', $hide_id = null, $tag = 'ul', $tag_child = 'li', $class = null, $class_child = null ) {
		
		if ( empty( $type ) ) return false;
		$args = array(
		    'hide_empty' => 0 
		); 
		$terms = get_terms( $type, $args);
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		    echo "<{$tag}" . ( ( $class != null ) ? " class=\"{$class}\"" : "" ) . ">";
		    echo "	<{$tag_child}>";
		    echo "		<a href=\"#\" data-value=\"\">None</a>";
		    echo "	</{$tag_child}>";
		    foreach ( $terms as $term ) {
		    	if ( $term->term_id != $hide_id ) {
			    	echo "<{$tag_child}" . ( ( $class_child != null ) ? " class=\"{$class_child}\"" : "" ) . ">";
			       	echo "<a href=\"#\" data-value=\"$term->slug\">$term->name</a>";
			        echo "</{$tag_child}>";
			    }
		    }
		     echo "</{$tag}>";
		 }

	}

endif;

/** ====== END noo_dropdown_taxonomy ====== **/

if(!class_exists('NooProperty')):
	class NooProperty{
		public function __construct(){
			add_action('init', array(&$this,'register_post_type'));
			
			add_filter( 'template_include', array( $this, 'template_loader' ) );
		
			if(!is_admin())
				add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
			
			add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );
			add_filter( 'parse_query', array(&$this, 'properties_filter') );
			
			add_shortcode('noo_recent_properties', array(&$this,'recent_properties_shortcode'));
			add_shortcode('noo_single_property', array(&$this,'single_property_shortcode'));
			add_shortcode('noo_advanced_search_property', array(&$this,'advanced_search_property_shortcode'));
			add_shortcode('property_slider', array(&$this,'property_slider_shortcode'));  
			add_shortcode('property_slide', array(&$this,'property_slide_shortcode'));
			
			//Ajax Contact Agent
			add_action('wp_ajax_noo_contact_agent', array(&$this,'ajax_contact_agent'));
			add_action('wp_ajax_nopriv_noo_contact_agent', array(&$this,'ajax_contact_agent'));
			add_action('wp_ajax_noo_contact_agent_property', array(&$this,'ajax_contact_agent_property'));
			add_action('wp_ajax_nopriv_noo_contact_agent_property', array(&$this,'ajax_contact_agent_property'));
			
			//Ajax Contact Agent
			add_action('wp_ajax_noo_agent_ajax_property', array(&$this,'ajax_agent_property'));
			add_action('wp_ajax_nopriv_noo_agent_ajax_property', array(&$this,'ajax_agent_property'));
			
			if(is_admin()):
				add_action('admin_init', array(&$this,'admin_init'));
				
				add_action ( 'add_meta_boxes', array (&$this, 'add_meta_boxes' ), 30 );
				
				add_action('admin_menu',array(&$this,'admin_menu'));
				//Property
				add_filter( 'manage_edit-noo_property_columns', array($this,'property_columns') );
				add_filter( 'manage_noo_property_posts_custom_column',  array($this,'property_column'), 2 );
				
				//Label
				add_action('property_label_add_form_fields',array(&$this,'add_property_label_color'));
				add_action('property_label_edit_form_fields',array(&$this,'edit_property_label_color'),10,3);
				add_action( 'created_term', array($this,'save_label_color'), 10,3 );
				add_action( 'edit_term', array($this,'save_label_color'), 10,3 );
				
				//Map Marker
				add_action('property_category_add_form_fields',array(&$this,'add_category_map_marker'));
				add_action('property_category_edit_form_fields',array(&$this,'edit_category_map_marker'),10,3);
				add_action( 'created_term', array($this,'save_category_map_marker'), 10,3 );
				add_action( 'edit_term', array($this,'save_category_map_marker'), 10,3 );
				
				//Location
				add_action('property_location_add_form_fields',array(&$this,'add_location'));
				add_action('property_location_edit_form_fields',array(&$this,'edit_location'),10,3);
				
				//Status
				add_action('property_status_add_form_fields',array(&$this,'add_status'));
				add_action('property_status_edit_form_fields',array(&$this,'edit_status'),10,3);
				
				//Sub location 
				add_action('property_sub_location_add_form_fields',array(&$this,'add_sub_location'));
				add_action('property_sub_location_edit_form_fields',array(&$this,'edit_sub_location'),10,3);
				add_action( 'created_term', array($this,'save_sub_location_callback'), 10,3 );
				add_action( 'edit_term', array($this,'save_sub_location_callback'), 10,3 );
				add_filter( 'manage_edit-property_sub_location_columns', array($this,'sub_location_columns') );
				add_filter( 'manage_property_sub_location_custom_column',  array($this,'sub_location_column'), 10, 3 );
				
				add_action( 'admin_print_scripts-post.php', array( &$this, 'enqueue_map_scripts' ) );
				add_action( 'admin_print_scripts-post-new.php', array( &$this, 'enqueue_map_scripts' ) );
				
				add_action( 'admin_enqueue_scripts', array(&$this,'enqueue_scripts'));
			endif;
		}
		
		public static function enqueue_gmap_js( $load_map_data = false ,$ids) {
			static $has_map_data = false;
            
			if( wp_script_is( 'noo-property-map', 'enqueued' ) ) {
				// return if loaded and no need for reload
				if( $has_map_data || !$load_map_data ) {
					return;
				} else {
					wp_dequeue_script( 'google-map');
					wp_dequeue_script( 'noo-property-map');
				}
			}

			if( !$has_map_data ) {
				$has_map_data = $load_map_data;
			}
         
			$latitude = self::get_google_map_option('latitude','40.714398');
			$longitude = self::get_google_map_option('longitude','-74.005279');
			$nooGmapL10n = array(
				'ajax_url'        => admin_url( 'admin-ajax.php', 'relative' ),
				'home_url'		  => get_site_url(),
				'theme_dir'		  => get_template_directory(),
				'theme_uri'		  => get_template_directory_uri(),
				'latitude'=>$latitude,
				'longitude'=>$longitude,
				'maxZoom_MarkerClusterer'=>5,
				//'zoom'=>self::get_google_map_option('zoom',16),
				'zoom'=>11,
				'fitbounds'=>self::get_google_map_option('fitbounds','1') ? true : false,
				'draggable'=>self::get_google_map_option('draggable','1') ? true : false,
				'area_unit' => self::get_general_option('area_unit'),
				'thousands_sep' => wp_specialchars_decode( stripslashes(self::get_general_option('price_thousand_sep')),ENT_QUOTES),
				'decimal_sep' => wp_specialchars_decode( stripslashes(self::get_general_option('price_decimal_sep')),ENT_QUOTES),
				'num_decimals' => self::get_general_option('price_num_decimals'),
				'currency'=>self::get_currency_symbol(self::get_general_option('currency')),
				'currency_position'=>self::get_general_option('currency_position','left'),
				'default_label'=>'',
				'fullscreen_label'=>'',
				'no_geolocation_pos'=>__("The browser couldn't detect your position!",'noo'),
				'no_geolocation_msg'=>__("Geolocation is not supported by this browser.",'noo'),
				'markers'=> ( $has_map_data ? self::get_properties_markers($ids) : json_encode(array()) ),
				'ajax_finishedMsg'=>__('All posts displayed','noo'),
			);

            //echo "<pre>"; print_r($nooGmapL10n); exit;
			wp_localize_script('noo-property-map', 'nooGmapL10n', $nooGmapL10n);

			// Remove conflict with dsIDXpress
			if( !wp_script_is( 'googlemaps3', 'enqueued' ) ) {
				wp_enqueue_script('google-map');
			}
			wp_enqueue_script( 'noo-property-map' );
		}
		
		public function restrict_manage_posts(){
			global $typenow, $wp_query;
			switch ( $typenow ) {
				case 'noo_property' :
					$this->property_filters();
				break;
			}
		}
		
		public function property_filters(){
			global $wp_query;
			$current_property_category = isset( $wp_query->query['property_category'] ) ? $wp_query->query['property_category'] : '';
			wp_dropdown_categories(array(
				'taxonomy'=>'property_category',
				'name'=>'property_category',
				'echo'=>true,
				'show_count'=>true,
				'show_option_none'=>__('All Types','noo'),
				'option_none_value'=>0,
				'selected'=>$current_property_category,
				'walker'=>new NooPropertyFilterDropdown
			));
			
			
			$current_property_location = isset( $wp_query->query['property_location'] ) ? $wp_query->query['property_location'] : '';
			wp_dropdown_categories(array(
				'taxonomy'=>'property_location',
				'name'=>'property_location',
				'echo'=>true,
				'show_count'=>true,
				'show_option_none'=>__('All Locations','noo'),
				'option_none_value'=>0,
				'selected'=>$current_property_location,
				'walker'=>new NooPropertyFilterDropdown
			));
			
			$current_property_sub_location = isset( $wp_query->query['property_sub_location'] ) ? $wp_query->query['property_sub_location'] : '';
			wp_dropdown_categories(array(
				'taxonomy'=>'property_sub_location',
				'name'=>'property_sub_location',
				'echo'=>true,
				'show_count'=>true,
				'show_option_none'=>__('All Sub-Locations','noo'),
				'option_none_value'=>0,
				'hierarchical'=>true,
				'selected'=>$current_property_sub_location,
				'walker'=>new NooPropertyFilterDropdown
			));
			
			$current_property_status = isset( $wp_query->query['property_status'] ) ? $wp_query->query['property_status'] : '';
			wp_dropdown_categories(array(
				'taxonomy'=>'property_status',
				'name'=>'property_status',
				'echo'=>true,
				'show_count'=>true,
				'show_option_none'=>__('All Statuses','noo'),
				'option_none_value'=>0,
				'selected'=>$current_property_status,
				'walker'=>new NooPropertyFilterDropdown
			));
			
			// Agents
			global $wpdb;
			$agent_ids = $wpdb->get_col(
						$wpdb->prepare('
							SELECT DISTINCT meta_value
							FROM %1$s
							WHERE meta_key = \'%2$s\' AND meta_value IS NOT NULL
							', $wpdb->postmeta, '_agent_responsible'));
			$agent_ids = array_merge(array_map( 'intval', $agent_ids ), array(0) );
			$agents = get_posts( array(
				'post_type' => 'noo_agent',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'include' => $agent_ids));
			?>
			<select name="agent">
				<option value=""><?php _e('All Agents', 'noo'); ?></option>
				<?php
				$current_v = isset($_GET['agent'])? $_GET['agent']:'';
				foreach ($agents as $agent) {
					printf
					(
						'<option value="%s"%s>%s</option>',
						$agent->ID,
						$agent->ID == $current_v ? ' selected="selected"':'',
						$agent->post_title
					);
				}
				?>
			</select>
			<?php
		}

		public function properties_filter( $query ) {
			global $pagenow;
			$type = 'post';
			if (isset($_GET['post_type'])) {
				$type = $_GET['post_type'];
			}
			if ( 'noo_property' == $type && is_admin() && $pagenow=='edit.php' ) {
				if( !isset($query->query_vars['post_type']) || $query->query_vars['post_type'] == 'noo_property' ) {
					if( isset($_GET['agent']) && !empty( $_GET['agent'] ) ) {
						$agent_id = $_GET['agent'];

						$meta_query = isset( $query->meta_query ) && !empty( $query->meta_query ) ? $query->meta_query : array();
						$meta_query[] = array(
								'key' => '_agent_responsible',
								'value' => $agent_id,
							);

						$query->set('meta_query', $meta_query);
					}
				}
			}
		}
		
		/**
		 * Hook into pre_get_posts
		 *
		 * @param WP_Query $q query object
		 * @return void
		 */
		public function pre_get_posts($q){
			global $wpdb,$noo_show_sold;

			if( $q->is_main_query() && $q->is_singular ) {
				return;
			}

			if( self::is_noo_property_query( $q ) ){
				if(apply_filters('noo_hide_sold_property', true) && empty($noo_show_sold) ) {
					if( !( $q->is_tax() && $q->is_main_query() && is_tax('property_status') ) ) {
						$sold = get_option('default_property_status');
						$tax_query = array(
								'taxonomy' => 'property_status',
								'terms'    => array( $sold ),
								'operator' => 'NOT IN',
						);
						$q->tax_query->queries[] = $tax_query;
						$q->query_vars['tax_query'] = $q->tax_query->queries;
					}
				}
				// if(isset($_GET['orderby'])){
					$default_orderby = isset( $q->query_vars['orderby'] ) ? $q->query_vars['orderby'] : noo_get_option('noo_property_listing_orderby_default');
					$default_orderby = !empty( $default_orderby ) ? $default_orderby : 'date';
					
					$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : $default_orderby;
					$orderby = strtolower( $orderby );
					$order   = isset( $q->query_vars['order'] ) ? $q->query_vars['order'] : 'DESC';
					$args    = array();
					$args['orderby']  = $orderby;
					$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
					$args['meta_key'] = '';
					
					switch ( $orderby ) {
						case 'rand' :
							$args['orderby']  = 'rand';
							break;
						case 'date' :
							$args['orderby']  = 'date';
							$args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
							break;
						case 'bath' :
							$args['orderby']  = "meta_value_num meta_value";
							$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
							$args['meta_key'] = '_bathrooms';
							break;
						case 'bed' :
							$args['orderby']  = "meta_value_num meta_value";
							$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
							$args['meta_key'] = '_bedrooms';
							break;
						case 'area' :
							$args['orderby']  = "meta_value_num meta_value";
							$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
							$args['meta_key'] = '_area';
							break;
						case 'price' :
							$args['orderby']  = "meta_value_num meta_value";
							$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
							$args['meta_key'] = '_price';
							break;
						case 'featured' :
							$args['orderby']  = "meta_value";
							$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
							$args['meta_key'] = '_featured';
							break;
						case 'name' :
							$args['orderby']  = 'title';
							$args['order']    = 'ASC'; // $order == 'DESC' ? 'DESC' : 'ASC';
							break;
					}
					$q->set( 'orderby', $args['orderby'] );
					$q->set( 'order', $args['order'] );

					if ( isset( $args['meta_key'] ) && !empty( $args['meta_key'] ) ) {
						$q->set( 'meta_key', $args['meta_key'] );
					}
					if ( isset( $args['meta_value'] ) && !empty( $args['meta_value'] ) ) {
						$q->set( 'meta_value', $args['meta_value'] );
					}
				// }
			}
		}

		public static function is_noo_property_query( $query = null ) {
			if( empty( $query ) ) return false;

			if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'noo_property' )
				return true;

			if( $query->is_tax ) {
				if( ( isset( $query->query_vars['property_category'] ) && !empty( $query->query_vars['property_category'] ) )
					|| ( isset( $query->query_vars['property_status'] ) && !empty( $query->query_vars['property_status'] ) )
					|| ( isset( $query->query_vars['property_location'] ) && !empty( $query->query_vars['property_location'] ) )
					|| ( isset( $query->query_vars['property_sub_location'] ) && !empty( $query->query_vars['property_sub_location'] ) ) ) {
					return true;
				}
			}

			return false;
		}
		
		public function template_loader($template){
			if(is_tax('property_category') || is_tax('property_status') || is_tax('property_location') || is_tax('property_sub_location')){
				$template       = locate_template( 'taxonomy-property_category.php' );
			}
			return $template;
		}
		
		public static function get_general_option($id,$default = null){
			$options = get_option('noo_property_general');
			if (isset($options[$id])) {
				return $options[$id];
			}
			return $default;
		}
		
		public static function get_custom_field_option($id,$default = null){
			$options = get_option('noo_property_custom_filed');
			if (isset($options[$id])) {
				if ( function_exists('icl_object_id') ){
					if( is_array($options[$id]) ) {
						foreach ($options[$id] as $index => $custom_field) {
							if( !is_array($custom_field) ) continue;
							$options[$id][$index]['label_translated'] = apply_filters('wpml_translate_single_string', $custom_field['label'], 'Property Custom Fields', 'noo_property_custom_fields_'. sanitize_title(@$custom_field['name']), apply_filters( 'wpml_current_language', null ) );
						}
					}
                } elseif (function_exists('pll__')) {
                	if( is_array($options[$id]) ) {
						foreach ($options[$id] as $index => $custom_field) {
							if( !is_array($custom_field) ) continue;
							$options[$id][$index]['label_translated'] = pll__(@$custom_field['label']);
						}
					}
                }
				return $options[$id];
			}
			return $default;
		}
		
		public static function get_feature_option($id,$default = null){
			$options = get_option('noo_property_feature');
			if (isset($options[$id])) {
				return $options[$id];
			}
			return $default;
		}

		public static function get_custom_features( $translated = true ) {
			$features = self::get_feature_option('features');
			$translated_features = array();	
			if( $translated && !empty( $features ) && count( $features ) > 0 ) {
				foreach ($features as $feature){
					$key = sanitize_title( $feature );
					$translated_features[$key] = apply_filters('wpml_translate_single_string', $feature, 'Property Custom Features', 'noo_property_features_' . $key, apply_filters( 'wpml_current_language', null ) );
				}
				$features = $translated_features;
			}

			return $features;
		}
		
		public static function get_advanced_search_option($id,$default = null){
			$options = get_option('noo_property_advanced_search');
			if (isset($options[$id])) {
				return $options[$id];
			}
			return $default;
		}
		
		public static function get_google_map_option($id,$default = null){
			$options = get_option('noo_property_google_map');
			if (isset($options[$id])) {
				return $options[$id];
			}
			return $default;
		}
		
		public function admin_init(){
			register_setting('noo_property_general','noo_property_general');
			register_setting('noo_property_custom_filed','noo_property_custom_filed');
			register_setting('noo_property_feature','noo_property_feature');
			register_setting('noo_property_advanced_search','noo_property_advanced_search');
			register_setting('noo_property_google_map','noo_property_google_map');
			
			add_action('noo_property_settings_general', array(&$this,'settings_general'));
			add_action('noo_property_settings_custom_field', array(&$this,'settings_custom_field'));
			add_action('noo_property_settings_feature', array(&$this,'settings_feature'));
			add_action('noo_property_settings_advanced_search', array(&$this,'settings_advanced_search'));
			add_action('noo_property_settings_google_map', array(&$this,'settings_google_map'));
			
			$this->feature_property();
		}
		
		public function add_meta_boxes(){
			$property_labels = array();
			$property_labels[] = array('value'=>'','label'=>__('Select a label','noo'));
			$property_labe_terms = (array) get_terms('property_label',array('hide_empty'=>0));

			foreach ($property_labe_terms as $label){
				$property_labels[] = array('value'=>$label->term_id,'label'=>$label->name);
			}
			$meta_box = array(
					'id' => "property_detail",
					'title' => __('Property Details', 'noo') ,
					'page' => 'noo_property',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
								'id'=>'_label',
								'label'=>__('Property Label','noo'),
								'type'=>'select',
								'options'=>$property_labels
							),
							array(
									'id' => '_address',
									'label' => __('Address','noo'),
									'type' => 'text',
							),
							array(
									'id' => '_price',
									'label' => __('Price','noo') . ' (' . NooProperty::get_currency_symbol(NooProperty::get_general_option('currency')) . ')',
									'type' => 'text',
							),
							array(
									'id' => '_price_label',
									'label' => __('After Price Label','noo'),
									'type' => 'text',
							),
							array(
									'id' => '_area',
									'label' => __('Area','noo') . ' (' . NooProperty::get_general_option('area_unit') . ')',
									'type' => 'text',
							),
							array(
									'id' => '_bedrooms',
									'label' => __('Bedrooms','noo'),
									'type' => 'text',
							),
							array(
									'id' => '_bathrooms',
									'label' => __('Bathrooms','noo'),
									'type' => 'text',
							),
							array(
									'id' => '_parking',
									'label' => __('Parking','noo'),
									'type' => 'text',
							)
					)
			);
			
			// Create a callback function
			$callback = create_function( '$post,$meta_box', 'noo_create_meta_box( $post, $meta_box["args"] );' );
			add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
				
				
			
			$custom_fields = self::get_custom_field_option('custom_field');
			$property_detail_fields = array();
			if($custom_fields){
				foreach ($custom_fields as $custom_field){
					$id = '_noo_property_field_'.sanitize_title(@$custom_field['name']);
					$property_detail_fields[] = array(
						'label' => isset( $custom_field['label_translated'] ) ? $custom_field['label_translated'] : @$custom_field['label'] ,
						'id' => $id,
						'type' => 'text',
					);
				}
				

				$meta_box = array(
						'id' => "property_custom",
						'title' => __('Property Custom', 'noo') ,
						'page' => 'noo_property',
						'context' => 'normal',
						'priority' => 'high',
						'fields' => $property_detail_fields
				);
					
				// Create a callback function
				$callback = create_function( '$post,$meta_box', 'noo_create_meta_box( $post, $meta_box["args"] );' );
				add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
					
			}
			
			$features = self::get_custom_features();
			$property_feature_fields = array();
			if($features){
				foreach ($features as $key => $feature){
						
					$property_feature_fields[] = array(
							'label' => $feature,
							'id' => '_noo_property_feature_' . $key,
							'type' => 'checkbox',
					);
				}
			}
			if( !empty( $property_feature_fields ) ) {
				$meta_box = array(
						'id' => "property_feature",
						'title' => __('Property Features', 'noo') ,
						'page' => 'noo_property',
						'context' => 'normal',
						'priority' => 'high',
						'fields' => $property_feature_fields
				);

				// Create a callback function
				$callback = create_function( '$post,$meta_box', 'noo_create_meta_box( $post, $meta_box["args"] );' );
				add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
			}

			$meta_box = array(
					'id' => "property_map",
					'title' => __('Place in Map', 'noo') ,
					'page' => 'noo_property',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
									'id' => '_noo_property_gmap',
									'type' => 'gmap',
									'callback'=>array(&$this,'meta_box_google_map')
							),
							array(
									'label' =>__('Latitude','noo'),
									'id' => '_noo_property_gmap_latitude',
									'type' => 'text',
									'std'=> self::get_google_map_option('latitude','40.714398')
							),
							array(
									'label' =>__('Longitude','noo'),
									'id' => '_noo_property_gmap_longitude',
									'type' => 'text',
									'std' => self::get_google_map_option('longitude','-74.005279')
							),
							array(
								'label' =>__('Map Zoom Level','noo'),
								'id' => '_noo_property_gmap_zoom',
								'type' => 'text',
								'std' => '16'
							),
					)
			);
			$callback = create_function( '$post,$meta_box', 'noo_create_meta_box( $post, $meta_box["args"] );' );
			add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
				
			$meta_box = array(
					'id' => "property_video",
					'title' => __('Property Video', 'noo') ,
					'page' => 'noo_property',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
									'label' => __('Video Embedded', 'noo'),
									'desc' => __('Enter a Youtube, Vimeo, Soundcloud, etc... URL. See supported services at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'noo'),
									'id' => '_video_embedded',
									'type' => 'text',
							),
					),
			);
			// Create a callback function
			$callback = create_function( '$post,$meta_box', 'noo_create_meta_box( $post, $meta_box["args"] );' );
			add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
			
				
			
			$meta_box = array(
					'id' => "property_gallery",
					'title' => __('Gallery', 'noo') ,
					'page' => 'noo_property',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
									'label' =>__('Gallery','noo'),
									'id' => '_gallery',
									'type' => 'gallery',
							),
					),
			);
			// Create a callback function
			$callback = create_function( '$post,$meta_box', 'noo_create_meta_box( $post, $meta_box["args"] );' );
			add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
			
			
			
			$meta_box = array(
				'id' => 'agent_responsible',
				'title' => __('Agent Responsible', 'noo'),
				'page' => 'noo_property',
				'context' => 'side',
				'priority' => 'default',
				'fields' => array(
					array(
						'label' => __('Agent Responsible', 'noo'),
						'id'    => '_agent_responsible',
						'type'  => 'agents',
						'callback' => 'NooAgent::render_metabox_fields'
					)
				)
			);
			// Create a callback function
			$callback = create_function( '$post,$meta_box', 'noo_create_meta_box( $post, $meta_box["args"] );' );
			add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
		}
		
		public function meta_box_google_map($post,$meta_box){
			?>
			<style>
			<!--
			.noo-form-group._gallery > label{
				display: none;
			}
			.noo-form-group._gallery .noo-thumb-wrapper img{
				max-width: 112px;
				max-height: 112px;
				width: 112px;
				height: 112px;
			}
			._noo_property_gmap .noo-control{float: none;width: 100%;}
			-->
			</style>
			<div class="noo_property_google_map">
				<div id="noo_property_google_map" class="noo_property_google_map" style="height: 380px; margin-bottom: 30px; overflow: hidden;position: relative;width: 100%;">
				</div>
				<div class="noo_property_google_map_search">
					<input placeholder="<?php echo __('Search your map','noo')?>" type="text" autocomplete="off" id="noo_property_google_map_search_input">
				</div>
			</div>
			<?php
		}
		
		public function admin_menu(){
			add_submenu_page('edit.php?post_type=noo_property',  __('Settings','noo'),   __('Settings','noo'), 'edit_posts', 'noo-property-setting',array(&$this,'settings_page'));			
		}
		
		public function settings_page(){
			$current_tab     = empty( $_GET['tab'] ) ? 'general' : sanitize_title( $_GET['tab'] );
			$tabs = apply_filters( 'noo_property_settings_tabs_array', array(
				'general'=>__('General','noo'),
				'custom_field'=>__('Custom Fields','noo'),
				'feature'	=>__('Listings Features & Amenities','noo'),
				'advanced_search'	=>__('Advanced Search','noo'),
				'google_map'	=>__('Google Map','noo')
			));
			
			?>
			<div class="wrap">
				<form action="options.php" method="post">
					<h2 class="nav-tab-wrapper">
						<?php
							foreach ( $tabs as $name => $label )
								echo '<a href="' . admin_url( 'edit.php?post_type=noo_property&page=noo-property-setting&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
						?>
					</h2>
					<?php 
					do_action( 'noo_property_settings_' . $current_tab );
					?>
					<p class="submit">
						<input type="submit" value="<?php echo __('Save Changes','noo') ?>" class="button button-primary" id="submit" name="submit">
					</p>
				</form>
			</div>			
			<?php
		}
		
		public function settings_general() {
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
			{
				flush_rewrite_rules();
			}
			$currency_code_options = self::get_currencies();
			$archive_slug = self::get_general_option('archive_slug','properties');
			$area_unit = self::get_general_option('area_unit');
			$currency = self::get_general_option('currency');
			$currency_position = self::get_general_option('currency_position');
			$price_thousand_sep = self::get_general_option('price_thousand_sep');
			$price_decimal_sep = self::get_general_option('price_decimal_sep');
			$price_num_decimals = self::get_general_option('price_num_decimals');
			foreach ( $currency_code_options as $code => $name ) {
				$currency_code_options[ $code ] = $name . ' (' . self::get_currency_symbol( $code ) . ')';
			}
			?>
			<?php settings_fields('noo_property_general'); ?>
			<h3><?php echo __('General Options','noo')?></h3>
			<table class="form-table" cellspacing="0">
				<tbody>
					<tr>
						<th>
							<?php esc_html_e('Property Archive base (slug)','noo')?>
						</th>
						<td>
							<input type="text" name="noo_property_general[archive_slug]" value="<?php echo ($archive_slug ? $archive_slug :'properties') ?>">
							<p><small><?php echo sprintf( __( 'This option will affect the URL structure on your site. If you made change on it and see an 404 Error, you will have to go to <a href="%s" target="_blank">Permalink Settings</a><br/> and click "Save Changes" button for reseting WordPress link structure.', 'noo' ), admin_url( '/options-permalink.php' ) ); ?></small></p>
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Area Unit','noo')?>
						</th>
						<td>
							<input type="text" name="noo_property_general[area_unit]" value="<?php echo ($area_unit ? $area_unit :'m') ?>">
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Currency','noo')?>
						</th>
						<td>
							<select name="noo_property_general[currency]">
								<?php foreach ($currency_code_options as $key=>$label):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($currency,$key)?>><?php echo esc_html($label)?></option>
								<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Currency Position','noo')?>
						</th>
						<td>
							<?php 
							$position = array(
									'left'        => __( 'Left', 'noo' ) . ' (' . self::get_currency_symbol() . '99.99)',
									'right'       => __( 'Right', 'noo' ) . ' (99.99' . self::get_currency_symbol() . ')',
									'left_space'  => __( 'Left with space', 'noo' ) . ' (' . self::get_currency_symbol() . ' 99.99)',
									'right_space' => __( 'Right with space', 'noo' ) . ' (99.99 ' . self::get_currency_symbol() . ')'
							)
							?>
							<select name="noo_property_general[currency_position]">
								<?php foreach ($position as $key=>$label):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($currency_position,$key)?>><?php echo esc_html($label)?></option>
								<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Thousand Separator','noo')?>
						</th>
						<td>
							<input type="text" name="noo_property_general[price_thousand_sep]" value="<?php echo ($price_thousand_sep ? $price_thousand_sep :',') ?>">
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Decimal Separator','noo')?>
						</th>
						<td>
							<input type="text" name="noo_property_general[price_decimal_sep]" value="<?php echo ($price_decimal_sep ? $price_decimal_sep :'.') ?>">
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Number of Decimals','noo')?>
						</th>
						<td>
							<input type="number" step="1" min="0" name="noo_property_general[price_num_decimals]" value="<?php echo ($price_num_decimals !=='' && $price_num_decimals !== null && $price_num_decimals !== array() ? $price_num_decimals :'2') ?>">
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
		
		public function settings_custom_field(){
		
			$fields = self::get_custom_field_option('custom_field');
			if(isset($_GET['settings-updated']) && $_GET['settings-updated']) {
				if( function_exists('icl_object_id') ) {
					foreach ($fields as $custom_field) {
						do_action( 'wpml_register_single_string', 'Property Custom Fields', 'noo_property_custom_fields_'.sanitize_title(@$custom_field['name']), @$custom_field['label'] );
					}
				}
				if (function_exists('pll_register_string')) {
					foreach ($fields as $custom_field) {
						if( !is_array($custom_field) ) continue;
						pll_register_string('noo_property_custom_fields_'. sanitize_title(@$custom_field['name']), @$custom_field['label'], 'noo' );
					}
	            }
			}
			?>
			<?php settings_fields('noo_property_custom_filed'); ?>
			<h3><?php echo __('Custom Fields','noo')?></h3>
			<table class="form-table" cellspacing="0">
				<tbody>
					<tr>
						<th>
							<?php esc_html_e('Fields','noo')?>
						</th>
						<td>
							<?php 
								$num_arr = count($fields) ? array_map( 'absint', array_keys($fields) ) : array();
								$num = !empty($num_arr) ? end($num_arr) : 1;
							?>
							<table class="widefat noo_property_custom_field_table" data-num="<?php echo $num ?>" cellspacing="0" >
								<thead>
									<tr>
										<th style="padding: 9px 7px">
											<?php esc_html_e('Field Name','noo')?>
										</th>
										<th style="padding: 9px 7px">
											<?php esc_html_e('Field Label','noo')?>
										</th>
										<th style="padding: 9px 7px">
											<?php esc_html_e('Action','noo')?>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php  if(!empty($fields)): ?>
									<?php foreach ($fields as $key=>$field):?>
									<tr data-stt = "<?php echo esc_attr($key)?>">
										<td>
											<input type="text" value="<?php echo esc_attr($field['name'])?>" placeholder="<?php esc_attr_e('Field Name','noo')?>" name="noo_property_custom_filed[custom_field][<?php echo $key?>][name]">
										</td>
										<td>
											<input type="text" value="<?php echo esc_attr($field['label'])?>" placeholder="<?php esc_attr_e('Field Label','noo')?>" name="noo_property_custom_filed[custom_field][<?php echo $key?>][label]">
										</td>
										<td>
											<input class="button button-primary" onclick="return delete_noo_property_custom_field(this);" type="button" value="<?php esc_attr_e('Delete','noo')?>">
										</td>
									</tr>
									<?php endforeach;?>
									<?php endif;?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="4">
											<input class="button button-primary" id="add_noo_property_custom_field" type="button" value="<?php esc_attr_e('Add','noo')?>">
										</td>
									</tr>
								</tfoot>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
		
		public function settings_feature(){
		
			$features = self::get_custom_features(false);
			if(isset($_GET['settings-updated']) && $_GET['settings-updated']) {
				if( function_exists('icl_object_id') ) {
					foreach ($features as $k=>$feature) {
						do_action( 'wpml_register_single_string', 'Property Custom Features', 'noo_property_features_'.sanitize_title($feature), $feature );
					}
				}
			}
			?>
			<?php settings_fields('noo_property_feature'); ?>
			<h3><?php echo __('Listings Features & Amenities','noo')?></h3>
			<table class="form-table" cellspacing="0">
				<tbody>
					<tr>
						<th>
							<?php esc_html_e('Add New Element in Features and Amenities ','noo')?>
						</th>
						<td>
							<table class="widefat noo_property_feature_table" cellspacing="0" >
								<thead>
									<tr>
										<th style="padding: 9px 7px">
											<?php esc_html_e('Feature Name','noo')?>
										</th>
										<th style="padding: 9px 7px">
											<?php esc_html_e('Action','noo')?>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php  if(!empty($features)): ?>
									<?php foreach ($features as $k=>$feature):?>
									<tr>
										<td>
											<input type="text" value="<?php echo esc_attr($feature)?>" placeholder="<?php esc_attr_e('Feature Name','noo')?>" name="noo_property_feature[features][]">
										</td>
										<td>
											<input class="button button-primary" onclick="return delete_noo_property_feature(this);" type="button" value="<?php esc_attr_e('Delete','noo')?>">
										</td>
									</tr>
									<?php endforeach;?>
									<?php endif;?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2">
											<input class="button button-primary" id="add_noo_property_feature" type="button" value="<?php esc_attr_e('Add','noo')?>">
										</td>
									</tr>
								</tfoot>
							</table>
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Show the Features and Amenities that are not available','noo')?>
						</th>
						<td>
							<?php $show_no_feature = self::get_feature_option('show_no_feature')?>
							<select name="noo_property_feature[show_no_feature]">
								<option <?php selected($show_no_feature,'yes')?> value="yes"><?php esc_html_e("Yes",'noo')?></option>
								<option <?php selected($show_no_feature,'no')?> value="no"><?php esc_html_e("No",'noo')?></option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
		
		public function settings_advanced_search(){
			$fields = array(
				''=>__('None', 'noo'),
				'property_location'=>__('Property Location','noo'),
				'property_sub_location'=>__('Property Sub Location','noo'),
				'property_status'=>__('Property Status','noo'),
				'property_category'=>__('Property Types','noo'),
				'_bedrooms'=>__('Bedrooms Meta','noo'),
				'_bathrooms'=>__('Bathrooms Meta','noo'),
				'_parking'=>__('Parking Meta','noo'),
				'_price'=>__('Price Meta','noo'),
				'_area'=>__('Area Meta','noo'),
				'keyword'=>__('Keyword','noo'),
			);
			$custom_fields = self::get_custom_field_option('custom_field');
			if($custom_fields){
				foreach ($custom_fields as $k=>$custom_field){
					$label = __('Custom Field: ','noo').( isset( $custom_field['label_translated'] ) ? $custom_field['label_translated'] : (isset($custom_field['label']) ? $custom_field['label'] : $k));
					$id = '_noo_property_field_'.sanitize_title(@$custom_field['name']).'|'.(isset($custom_field['label']) ? $custom_field['label'] : $k);
					$fields[$id] = $label;
				}
			}
			$pos1 = self::get_advanced_search_option('pos1','property_location');
			$pos2 = self::get_advanced_search_option('pos2','property_sub_location');
			$pos3 = self::get_advanced_search_option('pos3','property_status');
			$pos4 = self::get_advanced_search_option('pos4','property_category');
			$pos5 = self::get_advanced_search_option('pos5','_bedrooms');
			$pos6 = self::get_advanced_search_option('pos6','_bathrooms');
			$pos7 = self::get_advanced_search_option('pos7','_price');
			$pos8 = self::get_advanced_search_option('pos8','_area');
			$pos9 = self::get_advanced_search_option('pos9','_parking');
			wp_enqueue_style('vendor-chosen-css');
			wp_enqueue_script('vendor-chosen-js');
			
			?>
			<?php settings_fields('noo_property_advanced_search'); ?>
			<h3><?php echo __('Search Field Position','noo')?></h3>
			<table class="form-table" cellspacing="0">
				<tbody>
					<tr>
						<th>
							<?php _e('Position #1','noo')?>
						</th>
						<td>
							<select name="noo_property_advanced_search[pos1]">
							<?php foreach ($fields as $key=>$field):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($pos1,esc_attr($key))?>><?php echo $field?></option>
							<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Position #2','noo')?>
						</th>
						<td>
							<select name="noo_property_advanced_search[pos2]">
							<?php foreach ($fields as $key=>$field):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($pos2,esc_attr($key))?>><?php echo $field?></option>
							<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Position #3','noo')?>
						</th>
						<td>
							<select name="noo_property_advanced_search[pos3]">
							<?php foreach ($fields as $key=>$field):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($pos3,esc_attr($key))?>><?php echo $field?></option>
							<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Position #4','noo')?>
						</th>
						<td>
							<select name="noo_property_advanced_search[pos4]">
							<?php foreach ($fields as $key=>$field):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($pos4,esc_attr($key))?>><?php echo $field?></option>
							<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Position #5','noo')?>
						</th>
						<td>
							<select name="noo_property_advanced_search[pos5]">
							<?php foreach ($fields as $key=>$field):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($pos5,esc_attr($key))?>><?php echo $field?></option>
							<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Position #6','noo')?>
						</th>
						<td>
							<select name="noo_property_advanced_search[pos6]">
							<?php foreach ($fields as $key=>$field):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($pos6,esc_attr($key))?>><?php echo $field?></option>
							<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Position #7','noo')?>
						</th>
						<td>
							<select name="noo_property_advanced_search[pos7]">
							<?php foreach ($fields as $key=>$field):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($pos7,esc_attr($key))?>><?php echo $field?></option>
							<?php endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php _e('Position #8','noo')?>
						</th>
						<td>
							<select name="noo_property_advanced_search[pos8]">
							<?php foreach ($fields as $key=>$field):?>
								<option value="<?php echo esc_attr($key)?>" <?php selected($pos8,esc_attr($key))?>><?php echo $field?></option>
							<?php endforeach;?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<h3><?php echo __('Advanced Search Field','noo')?></h3>
			<?php 
			$features = self::get_custom_features();
			$feature_selected = self::get_advanced_search_option('advanced_search_field',array());
			?>
			<table class="form-table" cellspacing="0">
				<tbody>
					<tr>
						<th>
							<?php _e('Select Advanced Search Field','noo')?>
						</th>
						<td>
							<?php if($features): ?>
								<select class="advanced_search_field" name="noo_property_advanced_search[advanced_search_field][]" multiple="multiple" style="min-width: 300px;">
									<?php foreach ((array)$features as $key=>$feature): ?>
										<option value="<?php echo esc_attr($key)?>" <?php if(in_array($key, $feature_selected)):?> selected<?php endif;?>><?php echo ucfirst($feature)?></option>
									<?php endforeach;?>
								</select>
								<script type="text/javascript">
									jQuery(document).ready(function(){
										jQuery("select.advanced_search_field").chosen({
											"disable_search_threshold":10
										});
									});
								</script>
								<style type="text/css">
								.chosen-container input[type="text"]{
									height: auto !important;
								}
								</style>
							<?php else : ?>
								<p><?php _e('You have no Amenities ( Listing Features ). Please create some if you want to search with Amenities.', 'noo'); ?></p>
								<p><a href="<?php echo admin_url( 'edit.php?post_type=noo_property&page=noo-property-setting&tab=' . $name ); ?>"><?php _e('Switch to Listings Features & Amenities', 'noo'); ?></a></p>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}

		public function settings_google_map(){
			?>
			<?php settings_fields('noo_property_google_map'); ?>
			<h3><?php echo __('Google Map','noo')?></h3>
			<table class="form-table" cellspacing="0">
				<tbody>
					<tr>
						<th>
							<?php esc_html_e('Starting Point Latitude','noo')?>
						</th>
						<td>
							<input type="text" class="regular-text" value="<?php echo self::get_google_map_option('latitude','40.714398')?>" name="noo_property_google_map[latitude]">
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Starting Point Longitude','noo')?>
						</th>
						<td>
							<input type="text" class="regular-text"  value="<?php echo self::get_google_map_option('longitude','-74.005279')?>" name="noo_property_google_map[longitude]">
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Default Zoom Level','noo')?>
						</th>
						<td>
							<input type="text" class="regular-text"  value="<?php echo self::get_google_map_option('zoom','16')?>" name="noo_property_google_map[zoom]">
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Automatically Fit all Properties','noo')?>
						</th>
						<td>
							<input type="hidden" value="0" name="noo_property_google_map[fitbounds]">
							<input type="checkbox" value="1" <?php checked(self::get_google_map_option('fitbounds','1'), '1'); ?> name="noo_property_google_map[fitbounds]">
							<small><?php _e('Enable this option and all your listings will fit into your map automatically. Sometimes, the above options will be disregarded.', 'noo'); ?></small>
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Default Map Height (px)','noo')?>
						</th>
						<td>
							<input type="text" class="regular-text"  value="<?php echo self::get_google_map_option('height','700')?>" name="noo_property_google_map[height]">
						</td>
					</tr>
					<tr>
						<th>
							<?php esc_html_e('Map Draggable','noo')?>
						</th>
						<td>
							<input type="hidden" value="0" name="noo_property_google_map[draggable]">
							<input type="checkbox" value="1" <?php checked(self::get_google_map_option('draggable','1'), '1'); ?> name="noo_property_google_map[draggable]">
							<small><?php _e('Turn this option off to disable the map draggable.', 'noo'); ?></small>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
		
		public function property_slider_shortcode ( $atts, $content = null ) {
			self::enqueue_gmap_js($load_map_data = false,$ids='');
			wp_enqueue_script('noo-property');
			extract( shortcode_atts( array(
				'visibility'         => '',
				'class'              => '',
				'id'                 => '',
				'custom_style'       => '',
				'animation'          => 'slide',
				'visible_items'      => '1',
				'slider_time'        => '3000',
				'slider_speed'       => '600',
				'slider_height'      => '700',
				'auto_play'          => '',
				'indicator'          => '',
				'prev_next_control'  => '',
				'show_search_form'   => '',
				'advanced_search'    => '',
				'show_search_info'   => 'true',
				'search_info_title'  => null,
				'search_info_content'=> null,
				), $atts ) );
		
			wp_enqueue_script( 'vendor-carouFredSel' );
		
			$show_search_form = ( $show_search_form == 'true' );
			if( !$show_search_form ) {
				$search_info_title = '';
				$search_info_content = '';
			}
			$show_search_info = $show_search_form ? ( $show_search_info == 'true' ) : false;
			$class            = ( $class              != '' ) ? esc_attr( $class ) : '' ;
			$visibility       = ( $visibility         != '' ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
			$class           .= noo_visibility_class( $visibility );
		
			$html  = array();
		
			$id    = ( $id    != '' ) ? esc_attr( $id ) : 'noo-slider-' . noo_vc_elements_id_increment();
			
			$class .=' property-slider';
			
			$class = ( $class != '' ) ? 'class="' . $class . '"' : '';
			$custom_style   = ( $custom_style  != '' ) ? 'style="' . $custom_style . '"' : '';
		
			$indicator_html = array();
			$indicator_js   = array();
			if( $indicator == 'true') {
				$indicator_js[] = '    pagination: {';
				$indicator_js[] = '      container: "#' . $id . '-pagination"';
				$indicator_js[] = '    },';
		
				$indicator_html[] = '  <div id="' . $id . '-pagination" class="slider-indicators"></div>';
			}
		
			$prev_next_control_html = array();
			$prev_next_control_js   = array();
			if( $prev_next_control == 'true') {
				$prev_next_control_js[]   = '    prev: {';
				$prev_next_control_js[]   = '      button: "#' . $id . '-prev"';
				$prev_next_control_js[]   = '    },';
				$prev_next_control_js[]   = '    next: {';
				$prev_next_control_js[]   = '      button: "#' . $id . '-next"';
				$prev_next_control_js[]   = '    },';
		
				$prev_next_control_html[] = '  <a id="' . $id . '-prev" class="slider-control prev-btn" role="button" href="#"><span class="slider-icon-prev"></span></a>';
				$prev_next_control_html[] = '  <a id="' . $id . '-next" class="slider-control next-btn" role="button" href="#"><span class="slider-icon-next"></span></a>';
			}
		
			$swipe  = $pause_on_hover = 'true';
			$animation = ( $animation == 'slide' ) ? 'scroll' : $animation; // Not allow fading with carousel
		
		
			$html[] = '<div '.$class.' '.$custom_style.'>';
			$html[] = "<div id=\"{$id}\" class=\"noo-slider noo-property-slide-wrap\">";
			$html[] = '  <ul class="sliders">';
			$html[] = do_shortcode( $content );
			$html[] = '  </ul>';
			$html[] = '  <div class="clearfix"></div>';
			$html[] = implode( "\n", $indicator_html );
			$html[] = implode( "\n", $prev_next_control_html );
			$html[] = '</div>';
			if( $show_search_form ) {
				ob_start();
				$args = array(
					'gmap' => false,
					'search_info' => $show_search_info,
					'show_advanced_search_field' => !!$advanced_search,
					'search_info_title'			=> $search_info_title,
					'search_info_content'		=> $search_info_content,
				);
				self::advanced_map($args);
				$html[] = ob_get_clean();
			}
			$html[] = '</div>';
		
			// slider script
			$html[] = '<script>';
			$html[] = "jQuery('document').ready(function ($) {";
			$html[] = "  $('#{$id} .sliders').carouFredSel({";
			$html[] = "    infinite: true,";
			$html[] = "    circular: true,";
			$html[] = "    responsive: true,";
			$html[] = "    debug : false,";
			$html[] = '    scroll: {';
			$html[] = '      items: 1,';
			$html[] = ( $slider_speed   != ''         ) ? '      duration: ' . $slider_speed . ',' : '';
			$html[] = ( $pause_on_hover == 'true'     ) ? '      pauseOnHover: "resume",' : '';
			$html[] = '      fx: "' . $animation . '"';
			$html[] = '    },';
			$html[] = '    auto: {';
			$html[] = ( $slider_time    != ''     ) ? '      timeoutDuration: ' . $slider_time . ',' : '';
			$html[] = ( $auto_play      == 'true' ) ? '      play: true' : '      play: false';
			$html[] = '    },';
			$html[] = implode( "\n", $prev_next_control_js );
			$html[] = implode( "\n", $indicator_js );
			$html[] = '    swipe: {';
			$html[] = "      onTouch: {$swipe},";
			$html[] = "      onMouse: {$swipe}";
			$html[] = '    }';
			$html[] = '  });';
			$html[] = '});';
			$html[] = '</script>';
			if( !empty( $slider_height ) ) {
				$html[] = '<style>';
				$html[] = "  #{$id}.noo-slider .caroufredsel_wrapper .sliders .slide-item.noo-property-slide { max-height: {$slider_height}px; }";
				$html[] = '</style>';
			}
		
			return implode( "\n", $html );
		}
		
		public function property_slide_shortcode($atts, $content = null){
			extract( shortcode_atts( array(
				'property_id'=>'',
				'background_type'=>'thumbnail',
				'image'=>'',
				
			), $atts ) );
			if(empty($property_id))
				return '';
				
			
			$property = get_post($property_id);
			if(empty($property))
				return '';
			
			ob_start();
			include(locate_template("layouts/shortcode-property-slide.php"));
			return ob_get_clean();
		}

		public function advanced_search_property_shortcode($atts, $content = null){
		 //print_r($atts);
			extract( shortcode_atts( array(
				'title'                     => '',
				'source'					=> 'property',
				'map_height'                => '',
				'style'                     => 'horizontal',
				'idx_map_search_form'       => '',
				'disable_map'               => '',
				'disable_search_form'		=> '',
				'advanced_search'           => '',
				'no_search_container'       => '',
				'visibility'                => '',
				'class'                     => '',
				'custom_style'              => '',
				'ids'                       => ''
			), $atts ) );
			$style = !!$disable_search_form ? '' : $style;
			$show_advanced_search_field = ($style == 'horizontal') ? !!$advanced_search : false;
			$map_class = ( $style == 'vertical' ) ? 'search-vertical' : '';
			$disable_map          = ( $disable_map == 'true' );
			self::enqueue_gmap_js( !$disable_map && $source == 'property',$atts['ids'] );

			$no_search_container  = $disable_map ? ( $no_search_container == 'true' ) : false;
			if( $source == 'IDX' ) {
				$disable_search_form = true;
				$advanced_search = false;
				if ( $idx_map_search_form == 'true' ) $idx_map_search_form = true;
				else $idx_map_search_form = false;
			}

			$visibility           = ( $visibility  != ''     ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
			$class                = ( $class       != ''     ) ? esc_attr( $class ) : '';
			$class           	 .= noo_visibility_class( $visibility );
			$custom_style = ( $custom_style != '' ) ? ' style="' . $custom_style . '"' : '';
			ob_start();
			?>
			<div class="noo_advanced_search_property <?php echo $style.' '.$class?>" <?php echo $custom_style?>>
				<?php 
				$args = array(
					'gmap' => !$disable_map,
					'map_class' => $map_class,
					'show_status' => true,
					'no_search_container' => $no_search_container,
					'source' => $source,
					'idx_map_search_form' => $idx_map_search_form,
					'disable_search_form' => $disable_search_form,
					'show_advanced_search_field' => $show_advanced_search_field,
					'map_height'			=> $map_height
				);
				self::advanced_map($args);
				?>
			</div>
			<?php
			return ob_get_clean();
		}
		
		public function recent_properties_shortcode($atts, $content = null){
			wp_enqueue_script('noo-property');
			extract( shortcode_atts( array(
				'title'             => '',
				'type'				=> 'list',
				'property_id'		=> '',
				'property_category'	=> '',
				'property_status'	=> '',
				'property_label'	=> '',
				'property_location'	=> '',
				'property_sub_location'	=>'',
				'number'            => '6',
				'show'				=> '',
				'style'				=> 'grid',
				'slider_time'		=> '3000',
				'slider_speed'		=> '600',
				'show_auto_play'	=> 'true',
				'show_control'		=> 'no',
				'show_pagination'	=> 'no',
				'visibility'        => '',
				'class'             => '',
				'order_by'           => 'featured',
				'order'          	=> 'desc',
				'custom_style'      => ''
			), $atts ) );
			
			$visibility       = ( $visibility      != ''     ) && ( $visibility != 'all' ) ? esc_attr( $visibility ) : '';
			$class            = ( $class           != ''     ) ? 'recent-properties ' . esc_attr( $class ) : 'recent-properties';
			$class           .= noo_visibility_class( $visibility );
			
			$class = ( $class != '' ) ? ' class="' . esc_attr( $class ) . '"' : '';
			$custom_style = ( $custom_style != '' ) ? ' style="' . $custom_style . '"' : '';
			if( is_front_page() || is_home()) {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
			} else {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			}
			global $noo_show_sold;
			
				$args = array(
					'paged'          => $paged,
					'posts_per_page' => $number,
					'post_status'    => 'publish',
					'post_type'      =>'noo_property',
				);
			if($type == 'list'){

				$args['tax_query'] = array('relation' => 'AND');
				if(!empty($property_category)){
					$args['tax_query'][] = array(
							'taxonomy'     => 'property_category',
							'field'        => 'slug',
							'terms'        => $property_category
						);
				}
				if(!empty($property_status)){
					$noo_show_sold = true;
					$args['tax_query'][] = array(
							'taxonomy'     => 'property_status',
							'field'        => 'term_id',
							'terms'        => $property_status
					);
				}
				if( !empty($property_location)){
					$args['tax_query'][] = array(
							'taxonomy'     => 'property_location',
							'field'        => 'slug',
							'terms'        => $property_location
						);
				}
				if( !empty($property_sub_location)){
					$args['tax_query'][] = array(
							'taxonomy'     => 'property_sub_location',
							'field'        => 'slug',
							'terms'        => $property_sub_location
						);
				}
				if(!empty($property_label)){
				$property_label = absint($property_label);
					$args['meta_query'][] = array(
						'key'   => '_label',
						'value' => $property_label
					);
				}
				if($show === 'featured'){
					$args['meta_query'][] = array(
							'key'   => '_featured',
							'value' => 'yes'
					);
				}
			
				$show_pagination  = $show_pagination ==='yes';
				$show_control = $show_control==='yes';
			
			}elseif ($type == 'single'){
				$args['p'] = absint($property_id);
			
				$show_pagination  = false;
				$show_control = false;
			} 
			if ( $style == 'slider' || $style == 'featured' ) {
				$args['orderby']  = $order_by;
				$order    = strtoupper($order);
				$args['meta_key'] = '';
				switch ( $order_by ) {
					case 'rand' :
						$args['orderby']  = 'rand';
						break;
					case 'date' :
						$args['orderby']  = 'date';
						$args['order']    = $order;
						break;
					case 'bath' :
						$args['orderby']  = "meta_value_num meta_value";
						$args['order']    = $order;
						$args['meta_key'] = '_bathrooms';
						break;
					case 'bed' :
						$args['orderby']  = "meta_value_num meta_value";
						$args['order']    = $order;
						$args['meta_key'] = '_bedrooms';
						break;
					case 'area' :
						$args['orderby']  = "meta_value_num meta_value";
						$args['order']    = $order;
						$args['meta_key'] = '_area';
						break;
					case 'price' :
						$args['orderby']  = "meta_value_num meta_value";
						$args['order']    = $order;
						$args['meta_key'] = '_price';
						break;
					case 'featured' :
						$args['orderby']  = "meta_value";
						$args['order']    = $order;
						$args['meta_key'] = '_featured';
						break;
					case 'name' :
						$args['orderby']  = 'title';
						$args['order']    = $order;
						break;
				}
			}
			$q = new WP_Query($args);
			$noo_show_sold = false;
			
			ob_start();
			include(locate_template("layouts/shortcode-recent-properties.php"));
			return ob_get_clean();
		}
		
		public function single_property_shortcode($atts, $content = null){
			$atts = wp_parse_args( $atts, array(
				'title'             => '',
				'type'				=> 'single',
				'property_id'		=> '',
				'style'				=> 'featured',
				'visibility'        => '',
				'class'             => '',
				'custom_style'      => ''
			) );

			return $this->recent_properties_shortcode( $atts, $content );
		}
		
		public static function get_similar_property(){
			ob_start();
	        include(locate_template("layouts/noo-property-similar.php"));
	        echo ob_get_clean();
			wp_reset_query();
			wp_reset_postdata();
		}
		
		public static function get_price_html($post_id,$label = true){
			$price			= trim( noo_get_post_meta($post_id,'_price') );
			$price			= (preg_match ("/^([0-9]+)$/", $price)) ? self::format_price($price) : esc_html( $price );
			$price_label    = esc_html(noo_get_post_meta($post_id,'_price_label'));
			if($label)
				return $price.' '.$price_label;
			else 
				return $price;
		}
		
		public static function get_area_html($post_id){
			$area = noo_get_post_meta($post_id,'_area');
			$area_unit = self::get_general_option('area_unit');
			return empty( $area ) ? '' : $area.' '.$area_unit;
		}
		
		/**
		 * Format the price with a currency symbol.
		 * @param float $price
		 * @return string
		 */
		public static function format_price($price,$html = true){
			$return               = '';
			$currency_code        = self::get_general_option('currency');
			$currency_symbol   = self::get_currency_symbol($currency_code);
			$currency_position = self::get_general_option('currency_position');
			switch ( $currency_position ) {
				case 'left' :
					$format = '%1$s%2$s';
					break;
				case 'right' :
					$format = '%2$s%1$s';
					break;
				case 'left_space' :
					$format = '%1$s&nbsp;%2$s';
					break;
				case 'right_space' :
					$format = '%2$s&nbsp;%1$s';
					break;
				default:
					$format = '%1$s%2$s';
			}
			
			$thousands_sep = wp_specialchars_decode( stripslashes(self::get_general_option('price_thousand_sep')),ENT_QUOTES);
			$decimal_sep = wp_specialchars_decode( stripslashes(self::get_general_option('price_decimal_sep')),ENT_QUOTES);
			$num_decimals = self::get_general_option('price_num_decimals');
			
			$price  = floatval( $price );
			
			if(!$html) {
				return self::number_format( $price, $num_decimals, '.', '', $currency_code );
			}
			
			$price 	= self::number_format( $price, $num_decimals, $decimal_sep, $thousands_sep, $currency_code );
			if('text' === $html) {
				return sprintf( $format, $currency_symbol, $price );
			}

			if('number' === $html) {
				return $price;
			}

			//$price = preg_replace( '/' . preg_quote( self::get_general_option('price_decimal_sep'), '/' ) . '0++$/', '', $price );
			$return = '<span class="amount">' . sprintf( $format, $currency_symbol, $price ) . '</span>';
			
			return $return;
		}

		//
		private static function inr_comma($input, $thousands_sep = ',') {
		    // This function is written by some anonymous person – I got it from Google
			if(strlen($input)<=2)
				{ return $input; }
			$length=substr($input,0,strlen($input)-2);
			$formatted_input = self::inr_comma($length, $thousands_sep).$thousands_sep.substr($input,-2);
			return $formatted_input;
		}

		// Create custom function because some currency need special treat
		private static function number_format($num, $num_decimals = 2, $decimal_sep = '.', $thousands_sep = ',', $currency_code = '' ) {
			if( empty( $currency_code ) || $currency_code != 'INR' ) {
				return number_format( $num, $num_decimals, $decimal_sep, $thousands_sep );
			}

		    // Special format for Indian Rupee
			$pos = strpos((string)$num, '.');
			if ($pos === false) {
				$decimalpart = str_repeat("0", $num_decimals);
			}
			else {
				$decimalpart = substr($num, $pos+1, $num_decimals);
				$num = substr($num, 0, $pos);
			}

			$decimalpart = !empty($decimalpart) ? $decimal_sep . $decimalpart : '';

			if(strlen($num) > 3 & strlen($num) <= 12) {
				$last3digits = substr($num, -3 );
				$numexceptlastdigits = substr($num, 0, -3 );
				$formatted = self::inr_comma($numexceptlastdigits, $thousands_sep);
				$stringtoreturn = $formatted.$thousands_sep.$last3digits.$decimalpart ;
			} elseif(strlen($num)<=3) {
				$stringtoreturn = $num.$decimalpart ;
			} elseif(strlen($num)>12) {
				$stringtoreturn = number_format( $num, $num_decimals, $decimal_sep, $thousands_sep );
			}

			if(substr($stringtoreturn,0,2) == ( '-' . $decimal_sep ) ) {
				$stringtoreturn = '-'.substr( $stringtoreturn, 2 );
			}

			return $stringtoreturn;
		}
		
		public static function get_properties_markers($args = array()){
		   $exclude_ids=explode(',',$args);
		   
		  	$defaults = array(
					'post_type'     =>  'noo_property',
					'post_status'   =>  'publish',
					'nopaging'      =>  'true',
					'post__in'       =>  $exclude_ids
			);

			//print_r($defaults);
			$markers = array();
			//$args = wp_parse_args($args,$defaults);
			global $noo_show_sold;
			$noo_show_sold  = true;
			//print_r($defaults);
			$properties = new WP_Query($defaults);
			//echo $num = $properties->post_count;
			//print_r($properties);echo '<br/>';
			$noo_show_sold = false;
			if($properties->have_posts()){
				while ($properties->have_posts()): $properties->the_post();
					$post_id =  get_the_ID();
					$lat     =  esc_html(get_post_meta($post_id, '_noo_property_gmap_latitude', true));
					$long    =  esc_html(get_post_meta($post_id, '_noo_property_gmap_longitude', true));
					$title   =  wp_trim_words(get_the_title($post_id),7);
					$image   = get_template_directory_uri().'/assets/images/no-image.png';
					if(has_post_thumbnail($post_id))
						$image   =  get_the_post_thumbnail($post_id,'property-infobox');
					
					//$area    		= noo_get_post_meta(get_the_ID(),'_area');
					$bedrooms	 	= noo_get_post_meta(get_the_ID(),'_bedrooms');
					$bathrooms		= noo_get_post_meta(get_the_ID(),'_bathrooms');
					$price			= noo_get_post_meta($post_id,'_price');
					
					$property_location     = array();
					$property_sub_location = array();
					$property_status       = array();
					$property_category     = array();
					$property_location_terms   		=   get_the_terms($post_id,'property_location' );
					if($property_location_terms && !is_wp_error($property_location_terms)){
						foreach($property_location_terms as $location_term){
							if(empty($location_term->slug))
								continue;
							$property_location[] = $location_term->slug;
							// break;
						}
					}
					$property_sub_location_terms   	=   get_the_terms($post_id,'property_sub_location' );
					if($property_sub_location_terms && !is_wp_error($property_sub_location_terms)){
						foreach($property_sub_location_terms as $sub_location_term){
							if(empty($sub_location_term->slug))
								continue;
							$property_sub_location[] = $sub_location_term->slug;
							// break;
						}
					}
					
					$property_status_terms   		=   get_the_terms($post_id,'property_status' );
					if($property_status_terms && !is_wp_error($property_status_terms)){
						foreach($property_status_terms as $status_term){
							if(empty($status_term->slug))
								continue;
							$property_status[] = $status_term->slug;
							// break;
						}
					}
					$property_category_terms          =   get_the_terms($post_id,'property_category' );
					$property_category_marker = '';
					if($property_category_terms && !is_wp_error($property_category_terms)){
						$map_markers = get_option( 'noo_category_map_markers' );
						foreach($property_category_terms as $category_term){
							if(empty($category_term->slug))
								continue;
							$property_category[] = $category_term->slug;
							if(isset($map_markers[$category_term->term_id]) && !empty($map_markers[$category_term->term_id])){
								$property_category_marker = wp_get_attachment_url($map_markers[$category_term->term_id]);
							}
							// break;
						}
					}
					
					$marker = array(
						'latitude'=>$lat,
						'longitude'=>$long,
						'image'=>$image,
						'title'=>$title,
						'area'=>self::get_area_html($post_id),
						'bedrooms'=>absint($bedrooms),
						'bathrooms'=>absint($bathrooms),

						'price'=>self::format_price($price,false),
						'price_html'=>self::get_price_html($post_id),
						'url'=> get_permalink($post_id), 
						'location'=>$property_location,
						'sub_location'=>$property_sub_location,
						'status'=>$property_status,
						'category'=>$property_category,
						'icon'=>$property_category_marker,
					);
					$markers[] = $marker;
				endwhile;
			}
			
			wp_reset_query();
			wp_reset_postdata();
			return json_encode($markers);
		}
		
		public static function advanced_map_search_field($field='',$show_status=false){
			if(empty($field) )
				return '';
			
			global $wpdb;
			switch ($field){
				case 'property_location':
					
					$g_location = isset( $_GET['location'] ) ? esc_attr( $_GET['location'] ) : '';
					$g_location = ( empty($g_location) && is_tax('property_location') ) ? get_query_var( 'term' ) : $g_location;
					if( empty( $g_location ) && is_tax('property_sub_location') ) {
						$sub_location = get_query_var( 'term' );
						$sub_location_term = get_term_by('slug',$sub_location,'property_sub_location');
						$parent_data = get_option( 'noo_sub_location_parent' );
						if( isset( $parent_data[$sub_location_term->term_id] ) ) {
							$parent_location = get_term_by('id',$parent_data[$sub_location_term->term_id],'property_location');
							$g_location = $parent_location->slug;
						}
					}
					?>
					
					<div class="form-group glocation">
					<span class="gprice-label"><?php _e('Property Locations','noo')?></span>
			   			<div class="dropdown">
	   					
	   						<?php /*?>if($show_status && !empty($g_location) && ($g_location_term = get_term_by('slug',$g_location,'property_location'))):
	   						?>
	   						<span class="glocation-label" data-toggle="dropdown"><?php echo esc_html($g_location_term->name)?></span>
	   						<?php
	   						else:
	   						?>
	   						<span class="glocation-label" data-toggle="dropdown"><?php _e('Any','noo')?></span>
	   						<?php
	   						endif;
	   						?>
	   						<?php 
	   						noo_dropdown_search(array(
		   						'taxonomy'=>'property_location',
		   						'show_option_none'=>__('Any','noo'),
		   					));<?php */?>
<?php $sub_urb_arr = get_terms( 'property_location', array( 'orderby' =>  'term_order', 'hide_empty' => true ) );?>
<select id="lstFruits" multiple="multiple">
<?php foreach ($sub_urb_arr as $child) {?>
<option value="<?php echo $child->slug;?>" <?php if($_GET['sub_location']==$child->slug) echo 'selected';?>><?php echo $child->name;?></option>
<?php }?>
</select>
		<input type="hidden" class="glocation_input" name="location" id="location" value="<?php echo $g_location?>">
			   			</div>
			   		</div>
					<?php 
					return ;
				break;
				
				case '_bedrooms':
					$g_bedroom = isset( $_GET['bedroom'] ) ? esc_attr( $_GET['bedroom'] ) : '';
					$min_bedroom = $max_bedroom = 0;
					$min_bedroom = ceil( $wpdb->get_var(
							$wpdb->prepare('
						SELECT min(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
						', $wpdb->posts, $wpdb->postmeta, '_bedrooms', 'noo_property', 'publish')
					) );
					$max_bedroom = ceil( $wpdb->get_var(
							$wpdb->prepare('
						SELECT max(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
						', $wpdb->posts, $wpdb->postmeta, '_bedrooms', 'noo_property', 'publish')
					) );
							
					?>
					<div class="form-group gbed">
					<span class="gprice-label"><?php _e('Bedrooms','noo')?></span>
			   			<div class="dropdown">
			   				<?php 
	   						if($show_status && !empty($g_bedroom)):
	   						?>
	   						<span class="gbed-label" data-toggle="dropdown"><?php echo $g_bedroom?></span>
	   						<?php
	   						else:
	   						?>
	   						<span class="gbed-label" data-toggle="dropdown"><?php _e('Any','noo')?></span>
	   						<?php
	   						endif;
			   				?>
			   				<ul class="dropdown-menu">
			   					<li>
			   						<a href="#" data-value="" ><?php _e('Any','noo')?></a>
			   					</li>
			   					<?php foreach (range(absint($min_bedroom),absint($max_bedroom)) as $step):?>
			   					<li>
			   						<a href="#" data-value="<?php echo $step?>" ><?php echo $step ?></a>
			   					</li>
			   					<?php endforeach;?>
			   				</ul>
			   				<input type="hidden" class="gbedroom_input" name="bedroom" value="<?php echo $g_bedroom?>">
			   			</div>
			   		</div>
					<?php
					return;
				break;
				case '_bathrooms':
					$g_bathroom = isset( $_GET['bathroom'] ) ? esc_attr( $_GET['bathroom'] ) : '';
					$min_bathroom = $max_bathroom = 0;
					$min_bathroom = ceil( $wpdb->get_var(
						$wpdb->prepare('
						SELECT min(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
					', $wpdb->posts, $wpdb->postmeta, '_bathrooms', 'noo_property', 'publish')
					) );
					$max_bathroom = ceil( $wpdb->get_var(
						$wpdb->prepare('
								SELECT max(meta_value + 0)
								FROM %1$s
								LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
								WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
							', $wpdb->posts, $wpdb->postmeta, '_bathrooms', 'noo_property', 'publish')
					) );
					?>
					<div class="form-group gbath">
					<span class="gprice-label"><?php _e('Bathrooms','noo')?></span>
			   			<div class="dropdown">
			   				<?php 
	   						if($show_status && !empty($g_bathroom)):
	   						?>
	   						<span class="gbath-label" data-toggle="dropdown"><?php echo $g_bathroom?></span>
	   						<?php
	   						else:
	   						?>
	   						<span class="gbath-label" data-toggle="dropdown"><?php _e('Any','noo')?></span>
			   				<?php
	   						endif;
			   				?>
			   				<ul class="dropdown-menu">
			   					<li>
			   						<a href="#" data-value=""><?php _e('Any','noo')?></a>
			   					</li>
			   					<?php foreach (range(absint($min_bathroom),absint($max_bathroom)) as $step):?>
			   					<li>
			   						<a href="#" data-value="<?php echo $step?>"><?php echo $step ?></a>
			   					</li>
			   					<?php endforeach;?>
			   				</ul>
			   				<input type="hidden" class="gbathroom_input" name="bathroom" value="<?php echo $g_bathroom?>">
			   			</div>
			   		</div>
					<?php
					return;
				break;
				
				case '_parking':
					$g_parking = isset( $_GET['parking'] ) ? esc_attr( $_GET['parking'] ) : '';
					$min_parking = $max_parking = 0;
					$min_parking = ceil( $wpdb->get_var(
						$wpdb->prepare('
						SELECT min(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
					', $wpdb->posts, $wpdb->postmeta, '_parking', 'noo_property', 'publish')
					) );
					$max_parking = ceil( $wpdb->get_var(
						$wpdb->prepare('
								SELECT max(meta_value + 0)
								FROM %1$s
								LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
								WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
							', $wpdb->posts, $wpdb->postmeta, '_parking', 'noo_property', 'publish')
					) );
					?>
					<div class="form-group gbath">
					<span class="gprice-label"><?php _e('Parking','noo')?></span>
			   			<div class="dropdown">
			   				<?php 
	   						if($show_status && !empty($g_parking)):
	   						?>
	   						<span class="gbath-label" data-toggle="dropdown"><?php echo $g_parking?></span>
	   						<?php
	   						else:
	   						?>
	   						<span class="gbath-label" data-toggle="dropdown"><?php _e('Any','noo')?></span>
			   				<?php
	   						endif;
			   				?>
			   				<ul class="dropdown-menu">
			   					<li>
			   						<a href="#" data-value=""><?php _e('Any','noo')?></a>
			   					</li>
			   					<?php foreach (range(absint($min_parking),absint($max_parking)) as $step):?>
			   					<li>
			   						<a href="#" data-value="<?php echo $step?>"><?php echo $step ?></a>
			   					</li>
			   					<?php endforeach;?>
			   				</ul>
			   				<input type="hidden" class="gbathroom_input" name="parking" value="<?php echo $g_parking?>">
			   			</div>
			   		</div>
					<?php
					return;
				break;
				
				case '_price':
					$min_price = $max_price = 0;
					$min_price = ceil( $wpdb->get_var(
						$wpdb->prepare('
						SELECT min(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
						', $wpdb->posts, $wpdb->postmeta, '_price', 'noo_property', 'publish')
					) );
					$max_price = ceil( $wpdb->get_var(
							$wpdb->prepare('
						SELECT max(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
						', $wpdb->posts, $wpdb->postmeta, '_price', 'noo_property', 'publish')
					) );
					//$g_min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : $min_price;
					//$g_max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : $max_price;
					$g_min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : 0;
					$g_max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : 1000000;		
					?>
					<div class="form-group gprice">
					
			   			<span class="gprice-label"><?php _e('Price','noo')?></span>
			   			<div class="gprice-slider-range"></div>
						  <div class="g_ranges">
								<span class="g_min" id="min_price"><?php echo self::format_price($g_min_price); ?></span>
								<span class="g_max" id="max_price"><?php echo self::format_price($g_max_price); ?>+</span>
							</div>
			   			<input type="hidden" name="min_price" class="gprice_min" data-min="<?php echo $g_min_price;//$min_price ?>" value="<?php echo $g_min_price ?>">
			   			<input type="hidden" name="max_price" class="gprice_max" data-max="<?php echo $g_max_price;//$max_price ?>" value="<?php echo $g_max_price ?>">
			   		</div>
					<?php
					return;
				break;
				case '_area':
					$min_area = $max_area = 0;
					$min_area = ceil( $wpdb->get_var(
					$wpdb->prepare('
						SELECT min(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id AND post_status = \'%5$s\'
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\'
					', $wpdb->posts, $wpdb->postmeta, '_area', 'noo_property', 'publish')
						) );
					$max_area = ceil( $wpdb->get_var(
							$wpdb->prepare('
						SELECT max(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id AND post_status = \'%5$s\'
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\'
					', $wpdb->posts, $wpdb->postmeta, '_area', 'noo_property', 'publish')
						) );
							
					//$g_min_area = isset( $_GET['min_area'] ) ? esc_attr( $_GET['min_area'] ) : $min_area;
					//$g_max_area = isset( $_GET['max_area'] ) ? esc_attr( $_GET['max_area'] ) : $max_area;
					 $g_min_area = isset( $_GET['min_area'] ) ? esc_attr( $_GET['min_area'] ) : 0;
					 $g_max_area = isset( $_GET['max_area'] ) ? esc_attr( $_GET['max_area'] ) : 1500;	
							
					?>
						<div class="form-group garea">
						
				   			<span class="garea-label"><?php _e('Land size','noo')?></span>
				   			<div class="garea-slider-range"></div>
							 <div class="g_ranges">
								<span class="g_min" id="min_area"><?php echo number_format($g_min_area);?> m<sup>2</sup></span>
								<span class="g_max" id="max_area"><?php echo number_format($g_max_area); ?>+ m<sup>2</sup></span>
							</div>
				   			<input type="hidden" class="garea_min" name="min_area" data-min="<?php echo $g_min_area;?>" value="<?php echo $g_min_area ?>">
				   			<input type="hidden" class="garea_max" name="max_area" data-max="<?php echo $g_max_area;?>" value="<?php echo $g_max_area ?>">
				   		</div>
					<?php
					return;
				break;
				case 'keyword':
					?>
					<div class="form-group keyword">
					<span class="gprice-label"><?php _e('Keywords','noo')?></span>
					<div class="dropdown">
					<span class="keyword-label">
<input type="text" name="keyword" value="<?php echo $_GET['keyword'];?>" placeholder="Keyword" class="keyword-search">
                   </span>
			   		</div>
					</div>
					<?php
					return;
				break;
				default:
				break;
			}
			if(strstr( $field, '_noo_property_field_' )){
				$field_arr = explode('|', $field);
				$field_id = @$field_arr[0];
				$get_field = isset($_GET[$field_id]) ? $_GET[$field_id] : '';
				$field_label = @$field_arr[1];
				$field_values = $wpdb->get_results(
					$wpdb->prepare('
						SELECT meta_value
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.post_id = %2$s.ID
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\' ORDER BY meta_value ASC
						', $wpdb->postmeta, $wpdb->posts, $field_id, 'noo_property', 'publish'),OBJECT_K);
				if ( $field_values ) {
					?>
					<div class="form-group <?php echo $field_id?>">
			   			<div class="dropdown">
			   				<?php 
	   						if($show_status && !empty($get_field)):
	   						?>
	   							<span data-toggle="dropdown" class="<?php echo $field_id?>-label"><?php echo $get_field ?></span>
	   						<?php
	   						else:
	   						?>
	   							<span data-toggle="dropdown" class="<?php echo $field_id?>-label"><?php echo __('All','noo').' '.$field_label ?></span>
	   						<?php
	   						endif;
	   						?>
			   				
			   				<ul class="dropdown-menu">
			   					<li>
			   						<a data-value="" href="#"><?php echo __('All','noo').' '.$field_label ?></a>
			   					</li>
			   					<?php foreach ($field_values as $key=>$field_value):?>
			   					<?php 
			   					if(empty($key))
			   						continue;
			   					?>
			   					<li>
			   						<a data-value="<?php echo esc_attr($key)?>" href="#"><?php echo esc_html($key)?></a>
			   					</li>
			   					<?php endforeach;?>
			   				</ul>
			   				<input type="hidden" value="<?php echo $get_field ?>" name="<?php echo $field_id?>" class="<?php echo $field_id ?>_input">
			   			</div>
			   		</div>
					<?php
				}
				return;
			}
			return;
		}
		
		public static function advanced_map($args = array()){
			$defaults = array( 
				'gmap'						=> true,
				'btn_label'					=> '',
				'show_status'				=> false, 
				'map_class'					=> '',
				'search_info'				=> false,
				'no_search_container'		=> false, 
				'source'					=> 'property', 
				'idx_map_search_form'		=> false,
				'disable_search_form'		=> false,
				'show_advanced_search_field'=> false,
				'map_height'				=> '',
				'search_info_title'			=> null,
				'search_info_content'		=> null,
			);
			$p = wp_parse_args($args,$defaults);
			extract($p);
			$result_pages = get_pages(
				array(
						'meta_key' => '_wp_page_template',
						'meta_value' => 'search-property-result.php'
				)
			);
			if($result_pages){
				$first_page = reset($result_pages);
				$result_page_url = get_permalink($first_page->ID);
				if(is_page($first_page->ID)){
					$show_status = true;
				}
			}else{
				$result_page_url = '';
			}
			
			if(empty($btn_label))
				$btn_label=__('Search Property','noo');

			$map_class = !$gmap ? 'no-map ' . $map_class : $map_class;
			$map_class = $no_search_container ? 'no-container ' . $map_class : $map_class;
			$map_height = empty( $map_height ) ? self::get_google_map_option('height', 700) : $map_height;
			?>
			<div class="noo-map <?php echo esc_attr($map_class)?>">
				<?php if($gmap): ?>
				
					<div id="gmap" data-source="<?php echo $source?>" style="height: <?php echo $map_height; ?>px;" ></div>
					<div class="gmap-search">
						<input placeholder="<?php echo __('Search your map','noo')?>" type="text" autocomplete="off" id="gmap_search_input">
					</div>
					<div class="gmap-control">
						<a class="gmap-mylocation" href="#"><i class="fa fa-map-marker"></i><?php echo __('My Location','noo')?></a>
						<a class="gmap-full" href="#"><i class="fa fa-expand"></i></a>
						<a class="gmap-prev" href="#"><i class="fa fa-angle-left"></i></a>
						<a class="gmap-next" href="#"><i class="fa fa-angle-right"></i></a>
					</div>
					<div class="gmap-zoom">
						<a href="#" class="zoom-in"><i class="fa fa-plus"></i></a>
						<a href="#" class="zoom-out"><i class="fa fa-minus"></i></a>
					</div>
					<div class="gmap-loading"><?php _e('Loading Maps','noo');?>
				         <div class="gmap-loader">
				            <div class="rect1"></div>
				            <div class="rect2"></div>
				            <div class="rect3"></div>
				            <div class="rect4"></div>
				            <div class="rect5"></div>
				        </div>
				   </div>
				<?php endif;?>
				<?php if(!$disable_search_form && $source != 'IDX') :
					ob_start();
					include(locate_template("layouts/noo-property-search.php"));
					echo ob_get_clean();
				endif;?>
				<?php if( $source == "IDX" && $idx_map_search_form == true) :
					ob_start();
					include(locate_template("layouts/noo-property-idx-search.php"));
					echo ob_get_clean();
				endif;?>
			</div>
			<?php
		}
		
		public function register_post_type(){
			if(post_type_exists('noo_property'))
				return ;
			
			$noo_icon = NOO_FRAMEWORK_ADMIN_URI . '/assets/images/noo20x20.png';
			if ( floatval( get_bloginfo( 'version' ) ) >= 3.8 ) {
				$noo_icon = 'dashicons-location';
			}

			register_post_type('noo_property',array(
				'labels' => array(
					'name'                  => __('Properties','noo'),
					'singular_name'         => __('Property','noo'),
					'add_new'               => __('Add New Property','noo'),
					'add_new_item'          => __('Add Property','noo'),
					'edit'                  => __('Edit','noo'),
					'edit_item'             => __('Edit Property','noo'),
					'new_item'              => __('New Property','noo'),
					'view'                  => __('View','noo'),
					'view_item'             => __('View Property','noo'),
					'search_items'          => __('Search Property','noo'),
					'not_found'             => __('No Properties found','noo'),
					'not_found_in_trash'    => __('No Properties found in Trash','noo'),
					'parent'                => __('Parent Property','noo')
				),
				'public' => true,
				'has_archive' => self::get_general_option('archive_slug','properties'),
				'menu_icon'=>$noo_icon,
				'rewrite' => array('slug' => self::get_general_option('archive_slug','properties'),'with_front' => false),
				'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
				'can_export' => true,
				)
			);
			
			register_taxonomy ( 'property_category', 'noo_property', array (
					'labels' => array (
							'name' => __ ( 'Property Type', 'noo' ),
							'add_new_item' => __ ( 'Add New Property Type', 'noo' ),
							'new_item_name' => __ ( 'New Property Type', 'noo' ) 
					),
					'hierarchical' => true,
					'query_var' => true,
					'rewrite' => array ('slug' => 'listings' ) 
			) );
			
			
			register_taxonomy ( 'property_label', 'noo_property', array (
				'labels' => array (
					'name' => __ ( 'Property Label', 'noo' ),
					'add_new_item' => __ ( 'Add New Property Label', 'noo' ),
					'new_item_name' => __ ( 'New Property Label', 'noo' )
				),
				'show_ui'               => true,
				'query_var'             => true,
				'show_in_nav_menus'     => false,
				'meta_box_cb'			=>false,
			) );
			
			
			register_taxonomy ( 'property_location', 'noo_property', array (
					'labels' => array (
							'name' => __ ( 'Property Location', 'noo' ),
							'add_new_item' => __ ( 'Add New Property Location', 'noo' ),
							'new_item_name' => __ ( 'New Property Location', 'noo' ) 
					),
					'hierarchical' => true,
					'query_var' => true,
					'rewrite' => array ('slug' => 'property-location') 
			) );
			
			register_taxonomy ( 'property_sub_location', 'noo_property', array (
				'labels' => array (
					'name' => __ ( 'Property Sub-location', 'noo' ),
					'add_new_item' => __ ( 'Add New Property Sub-location', 'noo' ),
					'new_item_name' => __ ( 'New Property Sub-location', 'noo' )
				),
				'hierarchical' => true,
				'query_var' => true,
				'show_ui'               => true,
				'rewrite' => array ('slug' => 'property-sub-location')
			) );
				
			register_taxonomy ( 'property_status', 'noo_property', array (
				'labels' => array (
					'name' => __ ( 'Property Status', 'noo' ),
					'add_new_item' => __ ( 'Add New Property Status', 'noo' ),
					'new_item_name' => __ ( 'New Property Status', 'noo' )
				),
				'hierarchical' => true,
				'query_var' => true,
				'rewrite' => array ('slug' => 'status' )
			) );
			//delete_option('default_property_status');
			$default_property_status = get_option('default_property_status');
			if(empty($default_property_status)){
				$slug = sanitize_title(__('sold','noo'));
				$args = array(
						'slug' => $slug,
						'description' => __( 'This status is a predefined status, used for properties that is sold or rented. Properties with this status WON\'T display on some pages so you should be careful if you want to use this status for something else.', 'noo')
					);
				$ret = wp_insert_term(esc_html(__('Sold','noo')),'property_status',$args);
				if ( $ret && !is_wp_error( $ret ) && ($term = get_term_by('slug', $slug, 'property_status')) ){
					$r  = update_option('default_property_status', $term->term_id);
				}
			}	
		}

		public function property_columns( $columns ) {
			$part1 = array_slice($columns, 0, 1);
			$part2 = array_slice($columns, 1, 1);
			$part3 = array_slice($columns, 2);
			$add1 = $add2 = $add3 = array();
			$add1['featured'] = __( 'Featured', 'noo' );

			$add2['type'] = _( 'Type', 'noo' );
			$add2['location'] = _( 'Location', 'noo' );
			$add2['sub-location'] = _( 'Sub-Location', 'noo' );
			$add2['status'] = _( 'Status', 'noo' );
			$add2['agent_responsible'] = __('Agent', 'noo');

			$add3['property_id'] = __( 'ID', 'noo' );
		
			return array_merge( $part1, $add1, $part2, $add2, $part3, $add3 );
		}
		
		public function property_column( $column) {
			global $post;
		
			if ( $column == 'featured' ) {
				$featured = noo_get_post_meta($post->ID,'_featured');
				$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=noo_property_feature&property_id=' . $post->ID ), 'noo-property-feature' );
				echo '<a href="' . esc_url( $url ) . '" title="'. __( 'Toggle featured', 'noo' ) . '">';
				if ( 'yes' === $featured ) {
					echo '<span class="noo-property-feature" title="'.esc_attr__('Yes','noo').'"><i class="dashicons dashicons-star-filled "></i></span>';
				} else {
					echo '<span class="noo-property-feature not-featured"  title="'.esc_attr__('No','noo').'"><i class="dashicons dashicons-star-empty"></i></span>';
				}
				echo '</a>';
			} elseif ($column == 'type'){
				if ( ! $terms = get_the_terms( $post->ID, 'property_category' ) ) {
					echo '<span class="na">&ndash;</span>';
				} else {
					$types = array();
					foreach( $terms as $term ) {
						$types[] = edit_term_link( $term->name, '', '', $term, false );
					}
					echo implode(', ', $types);
				}
			} elseif ($column == 'location'){
				if ( ! $terms = get_the_terms( $post->ID, 'property_location' ) ) {
					echo '<span class="na">&ndash;</span>';
				} else {
					$locations = array();
					foreach( $terms as $term ) {
						$locations[] = edit_term_link( $term->name, '', '', $term, false );
					}
					echo implode(', ', $locations);
				}
			} elseif ($column == 'sub-location'){
				if ( ! $terms = get_the_terms( $post->ID, 'property_sub_location' ) ) {
					echo '<span class="na">&ndash;</span>';
				} else {
					$sub_locations = array();
					foreach( $terms as $term ) {
						$sub_locations[] = edit_term_link( $term->name, '', '', $term, false );
					}
					echo implode(', ', $sub_locations);
				}
			} elseif ($column == 'status'){
				if ( ! $terms = get_the_terms( $post->ID, 'property_status' ) ) {
					echo '<span class="na">&ndash;</span>';
				} else {
					$status = array();
					foreach( $terms as $term ) {
						$status[] = edit_term_link( $term->name, '', '', $term, false );
					}
					echo implode(', ', $status);
				}
			} elseif ($column == 'agent_responsible'){
				$agent_id = noo_get_post_meta( $post->ID, '_agent_responsible' );
				if( !empty( $agent_id ) ) {
					$agent = get_post( $agent_id );
					edit_post_link( $agent->post_title, '', '', $agent );
				} else {
					echo '<span class="na">&ndash;</span>';
				}
			} elseif ($column == 'property_id'){
				echo $post->ID;
			}
			return $column;
		}
		
		public function feature_property(){
			if(isset($_GET['action']) && $_GET['action'] == 'noo_property_feature'){
				if ( ! current_user_can( 'edit_posts' ) ) {
					wp_die( __( 'You do not have sufficient permissions to access this page.', 'noo' ), '', array( 'response' => 403 ) );
				}
				
				if ( ! check_admin_referer( 'noo-property-feature' ) ) {
					wp_die( __( 'You have taken too long. Please go back and retry.', 'noo' ), '', array( 'response' => 403 ) );
				}
				
				$post_id = ! empty( $_GET['property_id'] ) ? (int) $_GET['property_id'] : '';
				
				if ( ! $post_id || get_post_type( $post_id ) !== 'noo_property' ) {
					die;
				}
				
				$featured = noo_get_post_meta( $post_id, '_featured', true );
				
				if ( 'yes' === $featured ) {
					update_post_meta( $post_id, '_featured', 'no' );
				} else {
					update_post_meta( $post_id, '_featured', 'yes' );
				}
				
				
				wp_safe_redirect( esc_url_raw( remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) ) );
				die();
			}
		}
		
		public function sub_location_columns( $columns ) {
			$new_columns = array();
			$new_columns['cb'] = $columns['cb'];
			$new_columns['location_id'] = __( 'Location', 'noo' );
		
			unset( $columns['cb'] );
		
			return array_merge( $new_columns, $columns );
		}
		
		public function sub_location_column( $columns, $column, $id ) {
			if ( $column == 'location_id' ) {
				$sub_location_parent_options = get_option('noo_sub_location_parent');
				$selected = isset($sub_location_parent_options[$id]) ? $sub_location_parent_options[$id] : '';
				if($selected && $location = get_term($selected, 'property_location')){
					echo $location->name;
				}
			}
			return $columns;
		}
		
		public function add_location(){
			?>
			<script type="text/javascript">
			<!--
			jQuery(document).ready(function($){
			$('#parent').closest('.form-field').hide();
			});
			//-->
			</script>
			<?php
		}

		public function edit_location($term, $taxonomy){
			?>
			<script type="text/javascript">
			<!--
			jQuery(document).ready(function($){
			$('#parent').closest('.form-field').hide();
			});
			//-->
			</script>
			<?php
		}
		
		public function add_status(){
			?>
			<script type="text/javascript">
			<!--
			jQuery(document).ready(function($){
			$('#parent').closest('.form-field').hide();
			});
			//-->
			</script>
			<?php
			}
			public function edit_status($term, $taxonomy){
			?>
			<script type="text/javascript">
			<!--
			jQuery(document).ready(function($){
			$('#parent').closest('.form-field').hide();
			});
			//-->
			</script>
			<?php
		}
		
		public function add_sub_location(){
			$locations = get_terms('property_location', array( 'hide_empty' => false ));
			?>
			<div class="form-field">
				<label><?php _e('Location','noo')?></label>
				<select name="noo_location_parent">
				<?php foreach ((array)$locations as $location):?>
				<option value="<?php echo $location->term_id ?>"><?php echo $location->name?></option>
				<?php endforeach;?>
				</select>
			</div>
			<?php
		}
		
		public function edit_sub_location($term, $taxonomy){
			$locations = get_terms('property_location');
			$sub_location_parent_options = get_option('noo_sub_location_parent');
			$selected = isset($sub_location_parent_options[$term->term_id]) ? $sub_location_parent_options[$term->term_id] : 0;
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php _e('Location', 'noo'); ?></label></th>
				<td>
					<select name="noo_location_parent">
						<?php foreach ((array)$locations as $location):?>
						<option value="<?php echo $location->term_id ?>" <?php selected($selected,$location->term_id)?>><?php echo $location->name?></option>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
			<?php
		}
		
		public function save_sub_location_callback($term_id, $tt_id, $taxonomy){
			if ( isset( $_POST['noo_location_parent'] ) ){
				$parents = get_option( 'noo_sub_location_parent' );
				if ( ! $parents )
					$parents = array();
				$parents[$term_id] = absint($_POST['noo_location_parent']);
				update_option('noo_sub_location_parent', $parents);
			}
		}
		
		public function add_property_label_color(){
			wp_enqueue_style( 'wp-color-picker');
			wp_enqueue_script( 'wp-color-picker');
			?>
			<div class="form-field">
				<label><?php _e( 'Color', 'noo' ); ?></label>
				<input id="noo_property_label_color" type="text" size="40" value="" name="noo_property_label_color">
				<script type="text/javascript">
					jQuery(document).ready(function($){
					    $("#noo_property_label_color").wpColorPicker();
					});
				 </script>
			</div>
			<?php
		}
		
		public function edit_property_label_color($term, $taxonomy){
			wp_enqueue_style( 'wp-color-picker');
			wp_enqueue_script( 'wp-color-picker');
			$noo_property_label_colors = get_option('noo_property_label_colors');
			$color 	= isset($noo_property_label_colors[$term->term_id]) ? $noo_property_label_colors[$term->term_id] : '';
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php _e('Color', 'noo'); ?></label></th>
				<td>
					<input id="noo_property_label_color" type="text" size="40" value="<?php echo $color?>" name="noo_property_label_color">
					<script type="text/javascript">
						jQuery(document).ready(function($){
						    $("#noo_property_label_color").wpColorPicker();
						});
					 </script>
				</td>
			</tr>
			<?php
		}
		
		public function save_label_color($term_id, $tt_id, $taxonomy){
			if ( isset( $_POST['noo_property_label_color'] ) ){
				$noo_property_label_colors = get_option( 'noo_property_label_colors' );
				if ( ! $noo_property_label_colors )
					$noo_property_label_colors = array();
				$noo_property_label_colors[$term_id] = $_POST['noo_property_label_color'];
				update_option('noo_property_label_colors', $noo_property_label_colors);
			}
		}
		
		public function add_category_map_marker(){
			if(function_exists( 'wp_enqueue_media' )){
				wp_enqueue_media();
			}else{
				wp_enqueue_style('thickbox');
				wp_enqueue_script('media-upload');
				wp_enqueue_script('thickbox');
			}
			?>
			<div class="form-field">
				<label><?php _e( 'Map Marker Icon', 'noo' ); ?></label>
				<div id="category_map_marker_icon" style="float:left;margin-right:10px;">
					<img src="<?php echo NOO_FRAMEWORK_ADMIN_URI . '/assets/images/placeholder.png'; ?>" width="60px" height="60px" />
				</div>
				<div style="line-height:60px;">
					<input type="hidden" id="category_map_marker_icon_id" name="category_map_marker_icon_id" />
					<button type="button" class="upload_image_button button"><?php _e('Upload/Add image', 'noo'); ?></button>
					<button type="button" class="remove_image_button button"><?php _e('Remove image', 'noo'); ?></button>
				</div>
				<script type="text/javascript">
					
					 // Only show the "remove image" button when needed
					 if ( ! jQuery('#category_map_marker_icon_id').val() )
						 jQuery('.remove_image_button').hide();
			
					// Uploading files
					var file_frame;
			
					jQuery(document).on( 'click', '.upload_image_button', function( event ){
			
						event.preventDefault();
			
						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}
			
						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: "<?php _e( 'Choose an image', 'noo' ); ?>",
							button: {
								text: "<?php _e( 'Use image', 'noo' ); ?>",
							},
							multiple: false
						});
			
						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							attachment = file_frame.state().get('selection').first().toJSON();
			
							jQuery('#category_map_marker_icon_id').val( attachment.id );
							jQuery('#category_map_marker_icon img').attr('src', attachment.url );
							jQuery('.remove_image_button').show();
						});
			
						// Finally, open the modal.
						file_frame.open();
					});
			
					jQuery(document).on( 'click', '.remove_image_button', function( event ){
						jQuery('#category_map_marker_icon img').attr('src', '<?php echo NOO_FRAMEWORK_ADMIN_URI . '/assets/images/placeholder.png'; ?>');
						jQuery('#category_map_marker_icon_id').val('');
						jQuery('.remove_image_button').hide();
						return false;
					});
			
				</script>
				<div class="clear"></div>
			</div>
			<?php
		}
		
		public function edit_category_map_marker($term, $taxonomy){
			if(function_exists( 'wp_enqueue_media' )){
				wp_enqueue_media();
			}else{
				wp_enqueue_style('thickbox');
				wp_enqueue_script('media-upload');
				wp_enqueue_script('thickbox');
			}
			$map_markers = get_option( 'noo_category_map_markers' );
			$image 			= '';
			$category_map_marker_icon_id 	= isset($map_markers[$term->term_id]) ? $map_markers[$term->term_id] : '';
			if ($category_map_marker_icon_id) :
				$image = wp_get_attachment_url( $category_map_marker_icon_id );
			else :
				$image = NOO_FRAMEWORK_ADMIN_URI . '/assets/images/placeholder.png';
			endif;
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php _e('Map Marker Icon', 'noo'); ?></label></th>
				<td>
					<div id="category_map_marker_icon" style="float:left;margin-right:10px;"><img src="<?php echo $image; ?>" width="60px" height="60px" /></div>
					<div style="line-height:60px;">
						<input type="hidden" id="category_map_marker_icon_id" name="category_map_marker_icon_id" value="<?php echo $category_map_marker_icon_id; ?>" />
						<button type="button" class="upload_image_button button"><?php _e('Upload/Add image', 'noo'); ?></button>
						<button type="button" class="remove_image_button button"><?php _e('Remove image', 'noo'); ?></button>
					</div>
					<script type="text/javascript">
		
						jQuery(function(){
		
							 // Only show the "remove image" button when needed
							 if ( ! jQuery('#category_map_marker_icon_id').val() )
								 jQuery('.remove_image_button').hide();
		
							// Uploading files
							var file_frame;
		
							jQuery(document).on( 'click', '.upload_image_button', function( event ){
		
								event.preventDefault();
		
								// If the media frame already exists, reopen it.
								if ( file_frame ) {
									file_frame.open();
									return;
								}
		
								// Create the media frame.
								file_frame = wp.media.frames.downloadable_file = wp.media({
									title: "<?php _e( 'Choose an image', 'noo' ); ?>",
									button: {
										text: "<?php _e( 'Use image', 'noo' ); ?>",
									},
									multiple: false
								});
		
								// When an image is selected, run a callback.
								file_frame.on( 'select', function() {
									attachment = file_frame.state().get('selection').first().toJSON();
		
									jQuery('#category_map_marker_icon_id').val( attachment.id );
									jQuery('#category_map_marker_icon img').attr('src', attachment.url );
									jQuery('.remove_image_button').show();
								});
		
								// Finally, open the modal.
								file_frame.open();
							});
		
							jQuery(document).on( 'click', '.remove_image_button', function( event ){
								jQuery('#category_map_marker_icon img').attr('src', '<?php echo NOO_FRAMEWORK_ADMIN_URI . '/assets/images/placeholder.png'; ?>');
								jQuery('#category_map_marker_icon_id').val('');
								jQuery('.remove_image_button').hide();
								return false;
							});
						});
		
					</script>
					<div class="clear"></div>
				</td>
			</tr>
			<?php
		}
		
		public function save_category_map_marker($term_id, $tt_id, $taxonomy ){
			if ( isset( $_POST['category_map_marker_icon_id'] ) ){
				$map_markers = get_option( 'noo_category_map_markers' );
				if ( ! $map_markers )
					$map_markers = array();
				$map_markers[$term_id] = absint($_POST['category_map_marker_icon_id']);
				update_option('noo_category_map_markers', $map_markers);
			}	
		}
		
		public function enqueue_map_scripts(){
			global $post;
			if(get_post_type() === 'noo_property'){
				$latitude = self::get_google_map_option('latitude','-27.4710107');
				if($lat = noo_get_post_meta($post->ID,'_noo_property_gmap_latitude'))
					$latitude = $lat;
				
				$longitude = self::get_google_map_option('longitude','153.02344889999995');
				if($long = noo_get_post_meta($post->ID,'_noo_property_gmap_longitude'))
					$longitude = $long;
				
				$nooGoogleMap = array(
					'latitude'=>$latitude,
					'longitude'=>$longitude,
				);
				wp_register_script('google-map','http'.(is_ssl() ? 's':'').'://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places',array('jquery'), '1.0', false);
				wp_register_script( 'noo-property-google-map', NOO_ASSETS_URI . '/js/map-picker.js', array( 'google-map'), null, true );
				
				wp_localize_script('noo-property-google-map', 'nooGoogleMap', $nooGoogleMap);
				wp_enqueue_script('noo-property-google-map');
			}
		}
		
		public function enqueue_scripts(){

			$custom_field_type= apply_filters('noo_property_custom_field_type', array(
				'text'=>__('Short text','noo'),
				'textarea'	=>__('Long text','noo'),
				'date'		=>__('Date','noo')
			
			));
			/*
			ob_start();
			?>
			<select name="noo_property_custom_filed[custom_field][__i__][type]">
				<?php foreach ($custom_field_type as $value=>$type):?>
					<option value="<?php echo esc_attr($value)?>"><?php esc_html($type)?></option>
				<?php endforeach;?>
			</select>
			<?php
			$type_html = ob_get_clean();
			*/
			$feature_tmpl='';
			$feature_tmpl .= '<tr>';
			$feature_tmpl .= '<td>';
			$feature_tmpl .= '<input type="text" value="" placeholder="'.esc_attr__('Feature Name','noo').'" name="noo_property_feature[features][]">';
			$feature_tmpl .= '</td>';
			$feature_tmpl .= '<td>';
			$feature_tmpl .= '<input class="button button-primary" onclick="return delete_noo_property_feature(this);" type="button" value="'.esc_attr__('Delete','noo').'">';
			$feature_tmpl .= '</td>';
			$feature_tmpl .= '</tr>';
			
			$custom_field_tmpl = '';
			$custom_field_tmpl.= '<tr>';
			$custom_field_tmpl.= '<td>';
			$custom_field_tmpl.= '<input type="text" value="" placeholder="'.esc_attr__('Field Name','noo').'" name="noo_property_custom_filed[custom_field][__i__][name]">';
			$custom_field_tmpl.= '</td>';
			$custom_field_tmpl.= '<td>';
			$custom_field_tmpl.= '<input type="text" value="" placeholder="'.esc_attr__('Field Label','noo').'" name="noo_property_custom_filed[custom_field][__i__][label]">';
			$custom_field_tmpl.= '</td>';
			// $custom_field_tmpl.= '<td>';
			// $custom_field_tmpl.= ''.$type_html;
			// $custom_field_tmpl.= '</td>';
			$custom_field_tmpl.= '<td>';
			$custom_field_tmpl.= '<input class="button button-primary" onclick="return delete_noo_property_custom_field(this);" type="button" value="'.esc_attr__('Delete','noo').'">';
			$custom_field_tmpl.= '</td>';
			$custom_field_tmpl.= '</tr>';
			
			$noopropertyL10n = array(
				'feature_tmpl'=>$feature_tmpl,
				'custom_field_tmpl'=>$custom_field_tmpl,
			);
			wp_enqueue_style( 'noo-property', NOO_FRAMEWORK_ADMIN_URI . '/assets/css/noo-property-admin.css');
			wp_register_script( 'noo-property', NOO_FRAMEWORK_ADMIN_URI . '/assets/js/noo-property-admin.js', array( 'jquery','jquery-ui-sortable'), null, true );
			wp_localize_script('noo-property', 'noopropertyL10n', $noopropertyL10n);
			wp_enqueue_script('noo-property');
		}
		
		public static function get_currencies() {
			return array_unique(
					apply_filters( 'noo_property_currencies',
							array(
									'AED' => __( 'United Arab Emirates Dirham', 'noo' ),
									'EUR' => __( 'Euros', 'noo' ),
									'AUD' => __( 'Australian Dollars', 'noo' ),
									'BDT' => __( 'Bangladeshi Taka', 'noo' ),
									'BRL' => __( 'Brazilian Real', 'noo' ),
									'BGN' => __( 'Bulgarian Lev', 'noo' ),
									'CAD' => __( 'Canadian Dollars', 'noo' ),
									'CLP' => __( 'Chilean Peso', 'noo' ),
									'CNY' => __( 'Chinese Yuan', 'noo' ),
									'COP' => __( 'Colombian Peso', 'noo' ),
									'HRK' => __( 'Croatia kuna', 'noo' ),
									'CZK' => __( 'Czech Koruna', 'noo' ),
									'DKK' => __( 'Danish Krone', 'noo' ),
									'HKD' => __( 'Hong Kong Dollar', 'noo' ),
									'HUF' => __( 'Hungarian Forint', 'noo' ),
									'ISK' => __( 'Icelandic krona', 'noo' ),
									'IDR' => __( 'Indonesia Rupiah', 'noo' ),
									'INR' => __( 'Indian Rupee', 'noo' ),
									'ILS' => __( 'Israeli Shekel', 'noo' ),
									'JPY' => __( 'Japanese Yen', 'noo' ),
									'KES' => __( 'Kenyan Shilling', 'noo' ),
									'MYR' => __( 'Malaysian Ringgits', 'noo' ),
									'MXN' => __( 'Mexican Peso', 'noo' ),
									'NGN' => __( 'Nigerian Naira', 'noo' ),
									'NOK' => __( 'Norwegian Krone', 'noo' ),
									'NZD' => __( 'New Zealand Dollar', 'noo' ),
									'PHP' => __( 'Philippine Pesos', 'noo' ),
									'PKR' => __( 'Pakistani Rupees', 'noo' ),
									'PLN' => __( 'Polish Zloty', 'noo' ),
									'GBP' => __( 'Pounds Sterling', 'noo' ),
									'RON' => __( 'Romanian Leu', 'noo' ),
									'RUB' => __( 'Russian Ruble', 'noo' ),
									'SGD' => __( 'Singapore Dollar', 'noo' ),
									'ZAR' => __( 'South African rand', 'noo' ),
									'KRW' => __( 'South Korean Won', 'noo' ),
									'SEK' => __( 'Swedish Krona', 'noo' ),
									'CHF' => __( 'Swiss Franc', 'noo' ),
									'TWD' => __( 'Taiwan New Dollars', 'noo' ),
									'THB' => __( 'Thai Baht', 'noo' ),
									'TRY' => __( 'Turkish Lira', 'noo' ),
									'USD' => __( 'US Dollars', 'noo' ),
									'VND' => __( 'Vietnamese Dong', 'noo' ),
									'CLN' => __( 'Colones', 'noo' ),
							)
					)
			);
		}
		
		public static function get_currency_symbol( $currency = '' ) {
			if ( ! $currency ) {
				$currency = self::get_general_option('currency');
			}
		
			switch ( $currency ) {
				case 'AED' :
					$currency_symbol = 'د.إ';
					break;
				case 'BDT':
					$currency_symbol = '&#2547;&nbsp;';
					break;
				case 'BRL' :
					$currency_symbol = '&#82;&#36;';
					break;
				case 'BGN' :
					$currency_symbol = '&#1083;&#1074;.';
					break;
				case 'AUD' :
				case 'CAD' :
				case 'CLP' :
				case 'MXN' :
				case 'NZD' :
				case 'HKD' :
				case 'SGD' :
				case 'USD' :
					$currency_symbol = '&#36;';
					break;
				case 'EUR' :
					$currency_symbol = '&euro;';
					break;
				case 'CNY' :
				case 'RMB' :
				case 'JPY' :
					$currency_symbol = '&yen;';
					break;
				case 'RUB' :
					$currency_symbol = '&#1088;&#1091;&#1073;.';
					break;
				case 'KRW' : $currency_symbol = '&#8361;'; break;
				case 'TRY' : $currency_symbol = '&#84;&#76;'; break;
				case 'NOK' : $currency_symbol = '&#107;&#114;'; break;
				case 'ZAR' : $currency_symbol = '&#82;'; break;
				case 'CZK' : $currency_symbol = '&#75;&#269;'; break;
				case 'MYR' : $currency_symbol = '&#82;&#77;'; break;
				case 'DKK' : $currency_symbol = 'kr.'; break;
				case 'HUF' : $currency_symbol = '&#70;&#116;'; break;
				case 'IDR' : $currency_symbol = 'Rp'; break;
				case 'INR' : $currency_symbol = '&#8377;'; break;
				case 'ISK' : $currency_symbol = 'Kr.'; break;
				case 'ILS' : $currency_symbol = '&#8362;'; break;
				case 'PHP' : $currency_symbol = '&#8369;'; break;
				case 'PKR' : $currency_symbol = 'Rs'; break;
				case 'PLN' : $currency_symbol = '&#122;&#322;'; break;
				case 'SEK' : $currency_symbol = '&#107;&#114;'; break;
				case 'CHF' : $currency_symbol = '&#67;&#72;&#70;'; break;
				case 'TWD' : $currency_symbol = '&#78;&#84;&#36;'; break;
				case 'THB' : $currency_symbol = '&#3647;'; break;
				case 'GBP' : $currency_symbol = '&pound;'; break;
				case 'RON' : $currency_symbol = 'lei'; break;
				case 'VND' : $currency_symbol = '&#8363;'; break;
				case 'NGN' : $currency_symbol = '&#8358;'; break;
				case 'HRK' : $currency_symbol = 'Kn'; break;
				case 'KES' : $currency_symbol = 'KSh'; break;
				case 'CLN' : $currency_symbol = '&#8353;'; break;
				default    : $currency_symbol = ''; break;
			}
		
			return apply_filters( 'noo_property_currency_symbol', $currency_symbol, $currency );
		}
		
		public function ajax_agent_property(){
			global $noo_show_sold;
			$noo_show_sold = true;
			$agent_id = $_POST['agent_id'];
			$page = $_POST['page'];
			$args = array(
					'paged'=>$page,
					'posts_per_page' =>4,
					'post_type'=>'noo_property',
					'meta_query' => array(
							array(
									'key' => '_agent_responsible',
									'value' => $agent_id,
							),
					),
			);
			$r = new WP_Query($args);
			ob_start();
			self::display_content($r,__('My Properties','noo'),true,'',true,true,false,true);
			$ouput = ob_get_clean();
			wp_reset_query();
			$noo_show_sold = false;
			wp_send_json(array('content'=>trim($ouput)));
		}
		
		public function ajax_contact_agent( $is_property_contact = false ){
			$response = '';
			$_POST = stripslashes_deep($_POST);
			$no_html	= array();

			$nonce = $_POST['security'];
			$agent_id = isset( $_POST['agent_id'] ) ? wp_kses( $_POST['agent_id'], $no_html ) : '';
			$property_id = isset( $_POST['property_id'] ) ? wp_kses( $_POST['property_id'], $no_html ) : '';
			$verify = wp_verify_nonce( $nonce, 'noo-contact-agent-'.$agent_id );
			if( $is_property_contact && ( empty( $property_id ) || !is_numeric( $property_id ) ) ) {
				$verify = false;
			}

			if(false != $verify){
				$error = array();
				$name = isset( $_POST['name'] ) ? wp_kses( $_POST['name'], $no_html ) : '';
				$email = isset( $_POST['email'] ) ? wp_kses( $_POST['email'], $no_html ) : '';
				$message = isset( $_POST['message'] ) ? wp_kses( $_POST['message'], $no_html ) : '';
				if($name===null || $name===array() || $name==='' || empty($name) && is_scalar($name) && trim($name)===''){
					$error[] = array(
						'field'=>'name',
						'message'=>__("Please fill the required field.",'noo')
					);
				}
				if($email===null || $email===array() || $email==='' || empty($email) && is_scalar($email) && trim($email)===''){
					$error[] = array(
							'field'=>'email',
							'message'=>__("Please fill the required field.",'noo')
					);
				}else{
					$pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
					$valid=is_string($email) && strlen($email)<=254 && (preg_match($pattern,$email));
					if(!$valid){
						$error[] = array(
							'field'=>'email',
							'message'=>__("Email address seems invalid.",'noo')
						);
					}
				}
				if($message===null || $message===array() || $message==='' || empty($message) && is_scalar($message) && trim($message)===''){
					$error[] = array(
						'field'=>'message',
						'message'=>__("Please fill the required field.",'noo')
					);
				}
				$response = array('error'=>$error,'msg'=>'');
				if(!empty($error)){
					wp_send_json($response);
				}
				if($agent = get_post($agent_id)){
					$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
					$agent_email = get_post_meta($agent_id,'_noo_agent_email');

					$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
					$email_content = '';
						
					if( $is_property_contact ) {
						$property_title = get_the_title( $property_id );
						$property_link = get_permalink( $property_id );

						$email_content = sprintf( __("%s just sent you a message via %s's page", 'noo'), $name, $property_title) . "<br/><br/>";
						$email_content .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$email_content .= $message . "<br/><br/>";
						$email_content .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$email_content .= sprintf( __("You can reply to this email to respond or send email to %s", 'noo'), $email) . "<br/><br/>";
						$email_content .= sprintf( __("Check %s's details at %s", 'noo'), $property_title, $property_link) . "<br/><br/>";
					} else {
						$agent_link = get_permalink( $agent_id );

						$email_content = sprintf( __("%s just sent you a message via your profile", 'noo'), $name) . "<br/><br/>";
						$email_content .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$email_content .= $message . "<br/><br/>";
						$email_content .= __("----------------------------------------------", 'noo') . "<br/><br/>";
						$email_content .= sprintf( __("You can reply to this email to respond or send email to %s", 'noo'), $email) . "<br/><br/>";
						$email_content .= sprintf( __("Check your details at %s", 'noo'), $agent_link) . "<br/><br/>";
					}

					$email_content = apply_filters('noo_agent_contact_message', $email_content, $agent_id, $name, $email, $message);
						
					do_action('before_noo_agent_contact_send_mail', $agent_id, $name, $email, $message);

					noo_mail($agent_email,
						sprintf( __("[%s] New message from [%s]", 'noo'), $blogname, $name),
						$email_content, $headers);

					do_action('after_noo_agent_contact_send_mail', $agent_id, $name, $email, $message);
				}

				$response['msg'] = __('Your message was sent successfully. Thanks.','noo');
				wp_send_json($response);
			}
			die;
		}
		
		public function ajax_contact_agent_property(){
			$this->ajax_contact_agent( true );
		}
		
		public static function contact_agent(){
			$property_id = get_the_ID();
			$agent_id = noo_get_post_meta($property_id,'_agent_responsible');
			if(empty($agent_id))
				return '';
			
			ob_start();
	        include(locate_template("layouts/noo-property-contact.php"));
	        echo ob_get_clean();
		}

		public static function get_single_category($post_id){
			$terms = get_the_terms( $post_id, 'property_category' );
			if ( is_wp_error( $terms ) )
				return false;
			
			if ( empty( $terms ) )
				return false;
			
			foreach ( $terms as $term ) {
				return $term;
				break;
			}
		}

		public static function social_share( $post_id = null ) {
			$post_id = (null === $post_id) ? get_the_id() : $post_id;
			$post_type =  get_post_type($post_id);

			if( $post_type != 'noo_property' ) {
				echo '';
				return false;
			}

			$prefix        = 'noo_property';

			$share_url     = urlencode( get_permalink() );
			$share_title   = urlencode( get_the_title() );
			$share_source  = urlencode( get_bloginfo( 'name' ) );
			$share_content = urlencode( get_the_content() );
			$share_media   = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
			$popup_attr    = 'resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0';

			$facebook     = noo_get_option( "{$prefix}_social_facebook", true );
			$twitter      = noo_get_option( "{$prefix}_social_twitter", true );
			$google		  = noo_get_option( "{$prefix}_social_google", true );
			$pinterest    = noo_get_option( "{$prefix}_social_pinterest", false );
			$linkedin     = noo_get_option( "{$prefix}_social_linkedin", false );


			$html = array();

			if ( $facebook || $twitter || $google || $pinterest || $linkedin ) {
				$html[] = '<div class="property-share clearfix">';

				if($facebook) {
					$html[] = '<a href="#share" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" class="noo-social-facebook"'
								. ' title="' . __( 'Share on Facebook', 'noo' ) . '"'
								. ' onclick="window.open(' 
									. "'http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}','popupFacebook','width=650,height=270,{$popup_attr}');"
									. ' return false;">';
					$html[] = '</a>';
				}

				if($twitter) {
					$html[] = '<a href="#share" class="noo-social-twitter"'
								. ' title="' . __( 'Share on Twitter', 'noo' ) . '"'
								. ' onclick="window.open('
									. "'https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}','popupTwitter','width=500,height=370,{$popup_attr}');"
									. ' return false;">';
					$html[] = '</a>';
				}

				if($google) {
					$html[] = '<a href="#share" class="noo-social-googleplus"'
								. ' title="' . __( 'Share on Google+', 'noo' ) . '"'
								. ' onclick="window.open('
									. "'https://plus.google.com/share?url={$share_url}','popupGooglePlus','width=650,height=226,{$popup_attr}');"
									. ' return false;">';
					$html[] = '</a>';
				}

				if($pinterest) {
					$html[] = '<a href="#share" class="noo-social-pinterest"'
								. ' title="' . __( 'Share on Pinterest', 'noo' ) . '"'
								. ' onclick="window.open('
									. "'http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}','popupPinterest','width=750,height=265,{$popup_attr}');"
									. ' return false;">';
					$html[] = '</a>';
				}

				if($linkedin) {
					$html[] = '<a href="#share" class="noo-social-linkedin"'
								. ' title="' . __( 'Share on LinkedIn', 'noo' ) . '"'
								. ' onclick="window.open('
									. "'http://www.linkedin.com/shareArticle?mini=true&amp;url={$share_url}&amp;title={$share_title}&amp;summary={$share_content}&amp;source={$share_source}','popupLinkedIn','width=610,height=480,{$popup_attr}');"
									. ' return false;">';
					$html[] = '</a>';
				}

				$html[] = '</div>'; // .agent-social
			}

			echo implode("\n", $html);
		}
		
		public static function display_detail($query=null){
			self::enqueue_gmap_js($load_map_data = false,$ids='');
			wp_enqueue_script('noo-property');
			wp_enqueue_script( 'vendor-nivo-lightbox-js' );
			wp_enqueue_style( 'vendor-nivo-lightbox-default-css' );

			if(empty($query)){
				global $wp_query;
				$query = $wp_query;
			}
			
			ob_start();
	        include(locate_template("layouts/noo-property-detail.php"));
	        echo ob_get_clean();
			wp_reset_query();
		}

		public static function display_content($query='',$title='',$display_mode  = true,$default_mode = '',$show_pagination = false,$ajax_pagination=false,$show_orderby=false,$ajax_content=false,$default_orderby='date'){
			self::enqueue_gmap_js($load_map_data = false,$ids='');
			wp_enqueue_script('noo-property');
			global $wp_query,$wp_rewrite;
			if(!empty($query)){
				$wp_query = $query;
			}
			//print_r($wp_query);
			if ($wp_query->is_main_query())
				$show_orderby = noo_get_option('noo_property_listing_orderby', 1);
			if(empty($default_mode)){
				$default_mode = noo_get_option('noo_property_listing_layout','grid');
			}
			$mode = (isset($_GET['mode']) ? $_GET['mode'] : $default_mode);
			$is_fullwidth = false;
			if(is_post_type_archive('noo_property')
					|| is_tax('property_status')
					|| is_tax('property_sub_location')
					|| is_tax('property_location')
					|| is_tax('property_category')){
				$noo_property_layout =  noo_get_option('noo_property_layout','fullwidth');
				if($noo_property_layout == 'fullwidth'){
					$is_fullwidth = true;
				}
			}
			
			ob_start();
	        include(locate_template("layouts/noo-property-loop.php"));
	        echo ob_get_clean();
			wp_reset_query();
		}

		public static function get_property_summary( $args = '' ) {

			$defaults = array( 
				'property'			=>'',
				'return_type'		=>'html',
				'container_class'	=> ''
			);
			extract(wp_parse_args($args,$defaults));

			if( empty( $property_id ) ) $property_id = get_the_ID();
            
            $terms=get_the_terms ( get_the_ID(), 'property_status');
            //echo "<pre>";
            //print_r($terms);
            //echo $terms->name;
            //exit;

			$area = self::get_area_html(get_the_ID());
           
            $bathrooms = noo_get_post_meta(get_the_ID(),'_bathrooms');
			$bedrooms = noo_get_post_meta(get_the_ID(),'_bedrooms');
            //$parking = noo_get_post_meta(get_the_ID(),'_parking');
			
			if( strtolower($return_type) == 'array' ) {
				return compact( 'area', 'bathrooms', 'bedrooms' );
			}

			if( empty( $area ) && empty( $bathrooms ) && empty( $bedrooms ) ) {
				return '';
			}

			$html = array();
			if( !empty( $container_class ) ) {
				$html[] = '<div class="' . $container_class . '">';
			}

			if( !empty( $area ) ) {
				$html[] = '<div class="size"><span>' . $area . '</span></div>';
			}
			if( !empty( $terms[0]->name ) ) {
				$html[] = '<div class="bathroomsss" style="border-left:1px solid #841618;"><span>' . $terms[0]->name . '</span></div>';
			}
			/*
			if( !empty( $parking ) ) {
				$html[] = '<div class="parking"><span>' . esc_html($parking) . '</span></div>';
			}*/
            /*if( !empty( $area ) ) {
				$html[] = '<div class="size"><span>' . esc_html($area) . '</span></div>';
			}*/
			if( !empty( $container_class ) ) {
				$html[] = '</div>';
			}

			return implode("\n", $html);
		}
	}
	new NooProperty();	
endif;