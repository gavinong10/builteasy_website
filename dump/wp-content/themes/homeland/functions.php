<?php
	/*
		Table of Contents

		Set Maximum Content Width
		Included Files
		Register Styles and Scripts
		Theme Setup
		Modern Menu jQuery
		Google Map for Homepage
		Google Map for Search Results
		Google Map for Property
		Google Map for Contact us
		Change Default login logo and link
		Change Default Site title
		Main menu fallback
		Get Page Custom Title
		Get Property Type
		Get Property Terms
		Get Page Template Link
		Custom Excerpt Length
	   Remove Default comment fields
		Custom Comment Style
		Custom Pagination
		Custom Next Previous Link
		Fix Pagination for Taxonomies
		For Pagination working on static homepage
		For Pagination working for author page
		Agent Contributor Upload files
		Agent Custom Columns (Admin)
		Advance Property Search
		Property Sort and Order
		Remove and Add New Field in User Profile
		Add post thumbnail size in media upload
		Add category field
		Convert Hex to RGBA
		Custom Resizable Background
		Custom Header Images
		Google Analytics code
		Property Filter
		Add lightbox for gallery shortcode
		Change label of authors to agent
		Custom Avatar
		Search for property CPT
		Sticky header jquery
		Remove revolution slider meta boxes
		Add odd/even post class
		Get Portfolio Categories
		Ability of contributor to edit post
		Property Price Format
	*/


	/*---------------------------------------------
	SET MAXIMUM CONTENT WIDTH
	----------------------------------------------*/

	if ( ! isset( $content_width ) ) 
		$content_width = 1920;


	/*---------------------------------------------
	INCLUDED FILES
	----------------------------------------------*/

	include get_template_directory() . '/includes/mabuc-panel/main.php';
	include get_template_directory() . '/includes/lib/custom-posts.php';
	include get_template_directory() . '/includes/lib/custom-fields.php';
	include get_template_directory() . '/includes/lib/custom-widgets.php';
	include get_template_directory() . '/includes/lib/custom-functions.php';
	include get_template_directory() . '/includes/lib/custom-css.php';
	include get_template_directory() . '/includes/lib/tgm/activation.php';


	/*---------------------------------------------
	REGISTER STYLES AND SCRIPTS
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_script_styles_reg' ) ) :
		function homeland_script_styles_reg () {
			$homeland_site_layout = esc_attr( get_option('homeland_site_layout') );	
			$homeland_bg_type = esc_attr( get_option('homeland_bg_type') );
			$homeland_hide_map_list = esc_attr( get_option('homeland_hide_map_list') );
			$homeland_theme_mobile_menu = esc_attr( get_option('homeland_theme_mobile_menu') );
			$homeland_theme_layout = esc_attr( get_option( 'homeland_theme_layout' ) );

			//Stylesheet
			wp_register_style( 'homeland_style', get_stylesheet_directory_uri() . '/style.css' );	
			wp_register_style( 'homeland_responsive', get_stylesheet_directory_uri() . '/responsive.css' );	
			wp_register_style( 'homeland_font-awesome', get_template_directory_uri() . '/includes/font-awesome/css/font-awesome.min.css' );
			
			//Script
			wp_register_script( 'homeland_mobile-menu', get_template_directory_uri() . '/js/jquery.mobilemenu.min.js', array(), '', true );	
			wp_register_script( 'homeland_slick_nav_js', get_template_directory_uri() . '/js/jquery.slicknav.min.js', array(), '', true );
			wp_register_script( 'homeland_easing', get_template_directory_uri() . '/js/jquery.easing-1.3.min.js', array(), '', true );	
			wp_register_script( 'homeland_retina', get_template_directory_uri() . '/js/retina.min.js', array(), '', true );	
			wp_register_script( 'homeland_backstretch', get_template_directory_uri() . '/js/jquery.backstretch.min.js', array(), '', true );
			wp_register_script( 'homeland_validation', get_template_directory_uri() . '/js/jquery.validate.min.js', array(), '', true );
			wp_register_script( 'homeland_tipsy', get_template_directory_uri() . '/js/tipsy/jquery.tipsy.min.js', array(), '', true );	
			wp_register_script( 'homeland_superfish', get_template_directory_uri() . '/js/superfish.min.js', array(), '', true );
			wp_register_script( 'homeland_touch', get_template_directory_uri() . '/js/touchTouch/touchTouch.min.js', array(), '', true );	
			wp_register_script( 'homeland_hover_modernizr', get_template_directory_uri() . '/js/modernizr.custom.js', array(), '', true );
			wp_register_script( 'homeland_elastic', get_template_directory_uri() . '/js/jquery.elastislide.min.js', array(), '', true );
			wp_register_script( 'homeland_selectbox', get_template_directory_uri() . '/js/jquery.selectBox.min.js', array(), '', true );	
			wp_register_script( 'homeland_custom-js', get_template_directory_uri() . '/js/custom.js', array(), '', true );	
			
			//Flexslider
			wp_register_style( 'homeland_flexslider-style', get_template_directory_uri() . '/js/flexslider/flexslider.css' );		
			wp_register_script( 'homeland_flexslider', get_template_directory_uri() . '/js/flexslider/jquery.flexslider-min.js', array(), '', true );

			//Video Player
			wp_register_style( 'homeland_videojs-css', get_template_directory_uri() . '/js/video/video-js.css' );	
			wp_register_script( 'homeland_videojs', get_template_directory_uri() . '/js/video/video.js', array(), '', true );

			//Google Map
			wp_register_script( 'homeland_gmap-sensor', 'http://maps.google.com/maps/api/js?sensor=true' );
			wp_register_script( 'homeland_gmap-marker-clusterer', 'http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/src/markerclusterer.js' );
			wp_register_script( 'homeland_gmap', get_template_directory_uri() . '/js/gmaps.min.js' );

			//Countdown
			wp_register_script( 'homeland_countdown_plugin', get_template_directory_uri() . '/js/countdown/jquery.plugin.min.js', array(), '', true );
			wp_register_script( 'homeland_countdown', get_template_directory_uri() . '/js/countdown/jquery.countdown.min.js', array(), '', true );
			
			//Enqueue Styles
			wp_enqueue_style( 'homeland_style' );
			wp_enqueue_style( 'homeland_font-awesome' );
			wp_enqueue_style( 'homeland_flexslider-style' );

			//Enqueue Script
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-masonry' ); 
			wp_enqueue_script( 'homeland_superfish' );
			wp_enqueue_script( 'homeland_easing' );
			wp_enqueue_script( 'homeland_retina' );
			wp_enqueue_script( 'homeland_flexslider' );
			wp_enqueue_script( 'homeland_hover_modernizr' );
			wp_enqueue_script( 'homeland_elastic' );
			wp_enqueue_script( 'homeland_selectbox' );
			wp_enqueue_script( 'homeland_touch' );
			
			//Homepage Templates
			if(is_page_template('template-homepage-gmap.php') || is_page_template('template-homepage-gmap-large.php')) : 
				wp_enqueue_script( 'homeland_gmap-sensor' );
				wp_enqueue_script( 'homeland_gmap-marker-clusterer' );	
				wp_enqueue_script( 'homeland_gmap' );	
				add_action( 'wp_head', 'homeland_google_map_homepage' ); 
			endif;

			//Property Pages and Taxonomies
			if(is_tax( 'homeland_property_type') || is_tax( 'homeland_property_status') || is_tax( 'homeland_property_location') || is_tax( 'homeland_property_amenities' ) || is_page_template('template-properties-1col.php') || is_page_template('template-properties-2cols.php') || is_page_template('template-properties-3cols.php') || is_page_template('template-properties-4cols.php') || is_page_template('template-properties-left-sidebar.php') || is_page_template('template-properties.php') || is_page_template('template-properties-featured.php') || is_page_template('template-properties-grid-sidebar.php') || is_page_template('template-properties-grid-left-sidebar.php') || is_post_type_archive('homeland_properties') || is_page_template('template-properties-dual-sidebar.php')) : 
				if($homeland_hide_map_list == "") :
					wp_enqueue_script( 'homeland_gmap-sensor' );
					wp_enqueue_script( 'homeland_gmap-marker-clusterer' );	
					wp_enqueue_script( 'homeland_gmap' );	
					add_action( 'wp_head', 'homeland_google_map_homepage' ); 
				endif;
			endif;

			if(is_singular()) wp_enqueue_script( "comment-reply" );
			
			if($homeland_bg_type == "Image" && ($homeland_theme_layout == "Boxed" || $homeland_theme_layout == "Boxed Left")) :
				wp_enqueue_script( 'homeland_backstretch' ); 
			endif;
			
			//Property Single Page
			if(is_singular('homeland_properties')) : 
				wp_enqueue_script( 'homeland_validation' );
				wp_enqueue_script( 'homeland_gmap-sensor' );
				wp_enqueue_script( 'homeland_gmap-marker-clusterer' );	
				wp_enqueue_script( 'homeland_gmap' );	
				add_action( 'wp_head', 'homeland_google_map_property' );
			endif;

			//Property Search
			if(is_page_template('template-property-search.php')) :
				wp_enqueue_script( 'homeland_gmap-sensor' );
				wp_enqueue_script( 'homeland_gmap-marker-clusterer' );	
				wp_enqueue_script( 'homeland_gmap' );	
			endif;
			
			//Blog Templates
			if(is_page_template('template-blog.php') || is_page_template('template-blog-3cols.php') || is_page_template('template-blog-4cols.php') || is_page_template('template-blog-fullwidth.php') || is_page_template('template-blog-grid-left-sidebar.php') || is_page_template('template-blog-grid.php') || is_page_template('template-blog-left-sidebar.php') || is_page_template('template-blog-timeline.php') || is_page_template('template-blog-2cols.php') || is_single() || is_archive()) :
				wp_enqueue_style( 'homeland_videojs-css' );	
				wp_enqueue_script( 'homeland_videojs' );
				wp_enqueue_script( 'homeland_tipsy' );
			endif;

			//Contact us Templates
			if(is_page_template('template-contact.php') || is_page_template('template-contact-alternate.php') || is_page_template('template-contact-alternate2.php')) :
				wp_enqueue_script( 'homeland_gmap-sensor' );
				wp_enqueue_script( 'homeland_gmap-marker-clusterer' );	
				wp_enqueue_script( 'homeland_gmap' );	
				add_action( 'wp_head', 'homeland_google_map' );
			endif;

			//Coming Soon Template
			if(is_page_template('template-coming-soon.php')) :
				wp_enqueue_script( 'homeland_countdown_plugin' );
				wp_enqueue_script( 'homeland_countdown' );
			endif;

			//Responsive Menu
			if(empty($homeland_site_layout)) :
				wp_enqueue_style( 'homeland_responsive' );		
			
				if($homeland_theme_mobile_menu == "Modern") :
					wp_enqueue_script( 'homeland_slick_nav_js' );	
				else :
					wp_enqueue_script( 'homeland_mobile-menu' );	
				endif;
			endif;

			//Custom jQueries
			wp_enqueue_script( 'homeland_custom-js' );
		}
	endif;
	add_action( 'wp_enqueue_scripts', 'homeland_script_styles_reg' );


	/*---------------------------------------------
	THEME SETUP
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_theme_setup' ) ) :
		function homeland_theme_setup() {
			//Localisation
			load_theme_textdomain( 'codeex_theme_name', get_template_directory() . '/languages' );

			//Register Menus
			register_nav_menus( array(
				'primary-menu' => __( 'Primary Menu', 'codeex_theme_name' ),
				'footer-menu' => __( 'Footer Menu', 'codeex_theme_name' )
			) );

			//Theme Support and Filter
			add_filter( 'widget_text', 'do_shortcode' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'post-formats', array( 'image', 'video', 'gallery', 'audio' ) );
			add_theme_support( 'post-thumbnails', 
				array( 'post', 'homeland_properties', 'homeland_testimonial', 'homeland_partners', 'homeland_portfolio' ) 
			);

			//Image Sizes
			set_post_thumbnail_size( 160, 120, true ); 
			add_image_size( 'homeland_slider', 1920, 664, true );
			add_image_size( 'homeland_property_thumb', 153, 115, true );		
			add_image_size( 'homeland_property_medium', 330, 230, true );
			add_image_size( 'homeland_property_large', 709, 407, true );
			add_image_size( 'homeland_property_2cols', 520, 350, true );
			add_image_size( 'homeland_property_4cols', 240, 230, true );
			add_image_size( 'homeland_news_thumb', 70, 70, true );
			add_image_size( 'homeland_widget_property', 230, 175, true );
			add_image_size( 'homeland_widget_thumb', 50, 50, true );
			add_image_size( 'homeland_header_bg', 1920, 300, true );
			add_image_size( 'homeland_theme_large', 770, 9999 );
			add_image_size( 'homeland_theme_thumb', 100, 100, true );
			add_image_size( 'homeland_portfolio_large', 1080, 9999 );
		}
	endif;
	add_action('after_setup_theme', 'homeland_theme_setup');


	/*---------------------------------------------
	MODERN MENU JQUERY
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_modern_menu_js' ) ) :
		function homeland_modern_menu_js() {
			$homeland_theme_mobile_menu = esc_attr( get_option('homeland_theme_mobile_menu') );
			
			if($homeland_theme_mobile_menu == "Modern") :
				?>
					<script type="text/javascript">
						(function($) {
							"use strict"; 
		   				$(document).ready(function(){ $('#main-menu').slicknav(); });
		   			})(jQuery);
					</script>
				<?php
			endif;
		}
	endif;
	add_action( 'wp_footer', 'homeland_modern_menu_js' );


	/*---------------------------------------------
	GOOGLE MAP FOR HOMEPAGE PROPERTIES
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_google_map_homepage' ) ) :
		function homeland_google_map_homepage() {
			global $post, $wp_query;

			$homeland_home_map_lat = esc_attr( get_option('homeland_home_map_lat') );
			$homeland_home_map_lng = esc_attr( get_option('homeland_home_map_lng') );
			$homeland_home_map_zoom = esc_attr( get_option('homeland_home_map_zoom') );
			$homeland_home_map_icon = esc_attr( get_option('homeland_map_pointer_icon') );
			$homeland_num_properties = esc_attr( get_option('homeland_num_properties') );
			$homeland_price_format = esc_attr( get_option('homeland_price_format') );
			$homeland_currency= esc_attr( get_option('homeland_property_currency') ); 
			$homeland_property_currency_sign= esc_attr( get_option('homeland_property_currency_sign') );
			$homeland_map_pointer_clusterer_icon= esc_attr( get_option('homeland_map_pointer_clusterer_icon') );

			$homeland_lat_main = esc_attr( get_post_meta( $post->ID, 'homeland_property_lat', true ) );
			$homeland_lng_main = esc_attr( get_post_meta( $post->ID, 'homeland_property_lng', true ) );

			$homeland_term = $wp_query->queried_object;
			?>
			<script type="text/javascript">
				(function($) {
				  	"use strict";
				  	var map;
				   $(document).ready(function(){
				    	<?php
				    		if(is_tax( 'homeland_property_location' ) || is_tax( 'homeland_property_type' ) || is_tax( 'homeland_property_status' ) || is_tax( 'homeland_property_amenities' ) || is_post_type_archive('homeland_properties')) : ?>
				    			map = new GMaps({
							      div: '#map-homepage',
							      scrollwheel: false,
							      lat: <?php echo $homeland_lat_main; ?>,
									lng: <?php echo $homeland_lng_main; ?>,
									zoom: <?php if($homeland_home_map_zoom!="") : echo $homeland_home_map_zoom; else : echo "8"; endif ?>,
									markerClusterer: function(map) {
								   	return new MarkerClusterer(map, [], {
								      	gridSize: 60,
								      	maxZoom: 14,
									      <?php
									      	if(!empty($homeland_map_pointer_clusterer_icon)) : ?>
									      		styles: [{
														width: 50,
														height: 50,
														url: "<?php echo $homeland_map_pointer_clusterer_icon; ?>"
													}] <?php
									      	endif;
									      ?>
								    	});
								 	}
						      });
						      map.setCenter(<?php echo $homeland_lat_main; ?>, <?php echo $homeland_lng_main; ?>); <?php
				    		else : ?>
				    			map = new GMaps({
							      div: '#map-homepage',
							      scrollwheel: false,
							      lat: <?php echo $homeland_home_map_lat; ?>,
									lng: <?php echo $homeland_home_map_lng; ?>,
									zoom: <?php if($homeland_home_map_zoom!="") : echo $homeland_home_map_zoom; else : echo "8"; endif ?>,
							      markerClusterer: function(map) {
								   	return new MarkerClusterer(map, [], {
								      	gridSize: 60,
								      	maxZoom: 14,
									      <?php
									      	if(!empty($homeland_map_pointer_clusterer_icon)) : ?>
									      		styles: [{
														width: 50,
														height: 50,
														url: "<?php echo $homeland_map_pointer_clusterer_icon; ?>"
													}] <?php
									      	endif;
									      ?>
								    	});
								 	}
						      });	
						      map.setCenter(<?php echo $homeland_home_map_lat; ?>, <?php echo $homeland_home_map_lng; ?>);	<?php
				    		endif;

		      			if(!empty($homeland_home_map_icon)) : ?>var image = '<?php echo $homeland_home_map_icon; ?>';<?php endif;

		      			if(is_tax( 'homeland_property_type') ):
		      				$args = array( 
		      					'post_type' => 'homeland_properties', 
		      					'taxonomy' => 'homeland_property_type', 
		      					'term' => $homeland_term->slug 
		      				);
		      			elseif(is_tax( 'homeland_property_status' )) :
		      				$args = array( 
		      					'post_type' => 'homeland_properties', 
		      					'taxonomy' => 'homeland_property_status', 
		      					'term' => $homeland_term->slug 
		      				);
		      			elseif(is_tax( 'homeland_property_location' )) :
		      				$args = array( 
		      					'post_type' => 'homeland_properties', 
		      					'taxonomy' => 'homeland_property_location', 
		      					'term' => $homeland_term->slug 
		      				);
		      			elseif(is_tax( 'homeland_property_amenities' )) :
		      				$args = array( 
		      					'post_type' => 'homeland_properties', 
		      					'taxonomy' => 'homeland_property_amenities', 
		      					'term' => $homeland_term->slug 
		      				);
		      			elseif(is_page_template( 'template-properties-featured.php' )) :
		      				$args = array( 
		      					'post_type' => 'homeland_properties', 
									'posts_per_page' => -1, 
									'meta_query' => array( array( 
										'key' => 'homeland_featured', 
										'value' => 'on', 
										'compare' => '==' 
									)) 
		      				);
		      			else :
		      				$args = array( 'post_type' => 'homeland_properties', 'posts_per_page' => -1 );
		      			endif;

		      			$args_map = apply_filters('homeland_properties_parameters', $args);
							$wp_query = new WP_Query( $args_map );	

							while ( $wp_query->have_posts() ) : 
								$wp_query->the_post(); 	

								$homeland_lat = esc_attr( get_post_meta( $post->ID, 'homeland_property_lat', true ) );
								$homeland_lng = esc_attr( get_post_meta( $post->ID, 'homeland_property_lng', true ) );
								$homeland_price = esc_attr( get_post_meta( $post->ID, 'homeland_price', true ) );
								$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
								
								?>
									map.addMarker({
										<?php
											if(empty($homeland_lat) && empty($homeland_lng)) : ?>
												lat: 37.0625, lng: -95.677068, <?php	
											else : ?>
												lat: <?php echo $homeland_lat; ?>,
												lng: <?php echo $homeland_lng; ?>,<?php
											endif;
										?>
								      title: '<?php the_title(); ?>',
								      <?php if(!empty($homeland_home_map_icon)) : ?>icon: image, <?php endif; ?>
								      infoWindow: {
									   content: '<div class="marker-window"><a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_property_thumb'); endif; ?></a><h6><?php the_title(); ?></h6><?php if(!empty($homeland_price)) : ?><h5><?php homeland_property_price_format(); ?></h5><?php endif; ?><a href="<?php the_permalink(); ?>" class="view-gmap">View More</a></div>'
									   }
								   });
								<?php
					    	endwhile; 
					    	wp_reset_query();
		      		?>			        
				   });
				})(jQuery);					
			</script>
			<?php
		}
	endif;


	/*---------------------------------------------
	GOOGLE MAP FOR SEARCH RESULTS
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_google_map_search' ) ) :
		function homeland_google_map_search() {
			global $post, $args_wp;
			
			$homeland_home_map_lat = esc_attr( get_option('homeland_home_map_lat') );
			$homeland_home_map_lng = esc_attr( get_option('homeland_home_map_lng') );
			$homeland_home_map_zoom = esc_attr( get_option('homeland_home_map_zoom') );
			$homeland_home_map_icon = esc_attr( get_option('homeland_map_pointer_icon') );
			$homeland_price_format = esc_attr( get_option('homeland_price_format') );
			$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
			$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') );
			$homeland_map_pointer_clusterer_icon = esc_attr( get_option('homeland_map_pointer_clusterer_icon') );

			?>
			<script type="text/javascript">
				(function($) {
				  	"use strict";
				  	var map;
				   $(document).ready(function(){
				   	map = new GMaps({
					      div: '#map-property-search',
					      scrollwheel: false,
					      lat: <?php echo $homeland_home_map_lat; ?>,
							lng: <?php echo $homeland_home_map_lng; ?>,
							zoom: <?php if($homeland_home_map_zoom!="") : echo $homeland_home_map_zoom; else : echo "8"; endif ?>,
							markerClusterer: function(map) {
						   	return new MarkerClusterer(map, [], {
						      	gridSize: 60,
						      	maxZoom: 14,
							      <?php
							      	if(!empty($homeland_map_pointer_clusterer_icon)) : ?>
							      		styles: [{
												width: 50,
												height: 50,
												url: "<?php echo $homeland_map_pointer_clusterer_icon; ?>"
											}] <?php
							      	endif;
							      ?>
						    	});
						 	}
				      });		      	

		      		<?php
		      			if(!empty($homeland_home_map_icon)) : ?>var image = '<?php echo $homeland_home_map_icon; ?>';<?php endif;

		      			$args_map = apply_filters('homeland_advance_search_parameters', $args_wp);
							$wp_query_map = new WP_Query( $args_map );

							while ( $wp_query_map->have_posts() ) :
								$wp_query_map->the_post(); 	

								$homeland_lat = esc_attr( get_post_meta( $post->ID, 'homeland_property_lat', true ) );
								$homeland_lng = esc_attr( get_post_meta( $post->ID, 'homeland_property_lng', true ) );
								$homeland_price = esc_attr( get_post_meta( $post->ID, 'homeland_price', true ) );
								$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );

								?>
									map.addMarker({
										<?php
											if(empty($homeland_lat) && empty($homeland_lng)) : ?>
												lat: 37.0625, lng: -95.677068, <?php	
											else : ?>
												lat: <?php echo $homeland_lat; ?>,
												lng: <?php echo $homeland_lng; ?>,<?php
											endif;
										?>
								      title: '<?php the_title(); ?>',
								      <?php if(!empty($homeland_home_map_icon)) : ?>icon: image, <?php endif; ?>
								      infoWindow: {
										   content: '<div class="marker-window"><a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_property_thumb'); endif; ?></a><h6><?php the_title(); ?></h6><?php if(!empty($homeland_price)) : ?><h5><?php homeland_property_price_format(); ?></h5><?php endif; ?><a href="<?php the_permalink(); ?>" class="view-gmap">View More</a></div>'
									   	}
								    	});
								<?php
					    	endwhile; 
					    	wp_reset_query();
		      		?>			        
				   });
				})(jQuery);					
			</script><?php
		}
	endif;


	/*---------------------------------------------
	GOOGLE MAP FOR PROPERTY
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_google_map_property' ) ) :
		function homeland_google_map_property() {
			global $post;

			$homeland_property_hide_map = esc_attr( get_post_meta( $post->ID, 'homeland_property_hide_map', true ) );
			$homeland_lat = esc_attr( get_post_meta( $post->ID, 'homeland_property_lat', true ) );
			$homeland_lng = esc_attr( get_post_meta( $post->ID, 'homeland_property_lng', true ) );
			$homeland_property_map_zoom = esc_attr( get_post_meta( $post->ID, 'homeland_property_map_zoom', true ) ); 
			$homeland_home_map_icon = esc_attr( get_option('homeland_map_pointer_icon') );
			$homeland_hide_map = esc_attr( get_option('homeland_hide_map') );

			if(empty($homeland_property_hide_map)) :
				if(empty($homeland_hide_map)) : ?>
					<script type="text/javascript">
						(function($) {
						  	"use strict";
						  	var map;
						  	var panorama;

						   $(document).ready(function(){
						   	map = new GMaps({
						        	div: '#map-property',
						        	scrollwheel: false,
									<?php
										if(empty($homeland_lat) && empty($homeland_lng)) : ?>
											lat: 37.0625, lng: -95.677068, <?php	
										else : ?>
											lat: <?php echo $homeland_lat; ?>,
											lng: <?php echo $homeland_lng; ?>,<?php
										endif;
									?>
									zoom: <?php if($homeland_property_map_zoom!="") : echo $homeland_property_map_zoom; else : echo "8"; endif ?>
					      	});
						    		
						    	<?php if(!empty($homeland_home_map_icon)) : ?>var image = '<?php echo $homeland_home_map_icon; ?>';<?php endif; ?>
						      	
					      	map.addMarker({
						        	<?php if(!empty($homeland_home_map_icon)) : ?>icon: image, <?php endif; ?>
									<?php
										if(empty($homeland_lat) && empty($homeland_lng)) : ?>
											lat: 37.0625, lng: -95.677068, <?php	
										else : ?>
											lat: <?php echo $homeland_lat; ?>,
											lng: <?php echo $homeland_lng; ?>,<?php
										endif;
									?>     
					      	});

					      	panorama = GMaps.createPanorama({
								  	el: '#map-property-street',
								  	scrollwheel: false,
								  	<?php
										if(empty($homeland_lat) && empty($homeland_lng)) : ?>
											lat: 37.0625, lng: -95.677068, <?php	
										else : ?>
											lat: <?php echo $homeland_lat; ?>,
											lng: <?php echo $homeland_lng; ?>,<?php
										endif;
									?>    
								});

						   });
						   	
						})(jQuery);					
					</script><?php
				endif;
			endif;
		}
	endif;


	/*---------------------------------------------
	GOOGLE MAP FOR CONTACT US
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_google_map' ) ) :
		function homeland_google_map() {
			$homeland_lat = esc_attr( get_option('homeland_map_lat') );
			$homeland_lng = esc_attr( get_option('homeland_map_lng') );
			$homeland_marker_tip = stripslashes( esc_attr( get_option('homeland_map_marker') ) );
			$homeland_marker_window = stripslashes( get_option('homeland_map_window' ) );		
			$homeland_map_zoom = esc_attr( get_option('homeland_map_zoom') );		
			$homeland_home_map_icon = esc_attr( get_option('homeland_map_pointer_icon') );
			?>
			<script type="text/javascript">
				(function($) {
				  	"use strict";
				  	var map;
				   $(document).ready(function(){
				    	map = new GMaps({
				        	div: '#map',
				        	scrollwheel: false,
					      lat: <?php echo $homeland_lat; ?>,
							lng: <?php echo $homeland_lng; ?>,
							zoom: <?php if($homeland_map_zoom!="") : echo $homeland_map_zoom; else : echo "8"; endif ?>
				      });
				    	<?php if(!empty($homeland_home_map_icon)) : ?>var image = '<?php echo $homeland_home_map_icon; ?>';<?php endif; ?>

			      	map.addMarker({
				        	lat: <?php echo $homeland_lat; ?>,
							lng: <?php echo $homeland_lng; ?>,
				        	title: '<?php echo $homeland_marker_tip; ?>',
				        	<?php if(!empty($homeland_home_map_icon)) : ?>icon: image, <?php endif; ?>
				        	infoWindow: {
					    		content: '<p><?php echo $homeland_marker_window; ?></p>'
					    	}
			      	});
				   });
				})(jQuery);					
			</script>
			<?php
		}
	endif;


	/*---------------------------------------------
	CHANGE DEFAULT LOGIN LOGO & LINK
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_login_image' ) ) :
		function homeland_login_image() {
			$homeland_logo = esc_attr( get_option('homeland_logo') );
			echo "
				<style>
					body.login #login h1 a {
						background: url('" . $homeland_logo . "') center top no-repeat transparent;
						width:100%; height:126px;
					}
				</style>
			";
		}
	endif;

	if ( ! function_exists( 'homeland_custom_login_url' ) ) :
		function homeland_custom_login_url() { 
			return home_url(); 
		}
	endif;

	$homeland_logo = esc_attr( get_option('homeland_logo') );
	
	if(!empty( $homeland_logo )) : 
		add_action( 'login_head', 'homeland_login_image' );
		add_filter( 'login_headerurl', 'homeland_custom_login_url' ); 
	endif;


	/*---------------------------------------------
	CHANGE DEFAULT SITE TITLE 
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_change_default_site_title' ) ) :
		function homeland_change_default_site_title( $homeland_title ){
			$homeland_screen = get_current_screen();
			if('homeland_properties' == $homeland_screen->post_type) :
				$homeland_title = __( 'Enter property name', 'codeex_theme_name' );
			elseif('homeland_portfolio' == $homeland_screen->post_type) :
				$homeland_title = __( 'Enter portfolio name', 'codeex_theme_name' );
			elseif('homeland_services' == $homeland_screen->post_type) :
				$homeland_title = __( 'Enter services name', 'codeex_theme_name' );
			elseif('homeland_testimonial' == $homeland_screen->post_type) :
				$homeland_title = __( 'Enter name', 'codeex_theme_name' );
			elseif('homeland_partners' == $homeland_screen->post_type) :
				$homeland_title = __( 'Enter partner name', 'codeex_theme_name' );
			endif;
			return $homeland_title;
		}
	endif;
	add_filter( 'enter_title_here', 'homeland_change_default_site_title' );


	/*---------------------------------------------
	MAIN MENU and FOOTER FALLBACK
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_menu_fallback' ) ) :
		function homeland_menu_fallback() {
			$homeland_class = "";
			if(is_front_page()) : $homeland_class="current_page_item"; endif;
			?>
				<div id="dropdown" class="theme-menu clear">
					<ul id="menu-main-nav" class="sf-menu">
						<li <?php sanitize_html_class( post_class( $homeland_class ) ); ?>>
							<a href="<?php echo home_url(); ?>"><?php _e( 'Home', 'codeex_theme_name' ); ?></a>
						</li>
						<?php wp_list_pages( 'title_li=&sort_column=menu_order' ); ?>
					</ul>
				</div>
			<?php
		}
	endif;

	if ( ! function_exists( 'homeland_footer_menu_fallback' ) ) :
		function homeland_footer_menu_fallback() {
			?>
				<div class="footer-menu">
					<ul id="menu-footer-menu" class="clear">
						<li><a href="<?php echo home_url(); ?>"><?php _e( 'Home', 'codeex_theme_name' ); ?></a></li>
						<?php wp_list_pages( 'title_li=&sort_column=menu_order' ); ?>
					</ul>
				</div>
			<?php
		}
	endif;


	/*---------------------------------------------
	GET PAGE CUSTOM TITLE
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_get_page_title' ) ) :
		function homeland_get_page_title() {
			global $post, $wp_query, $homeland_page, $homeland_ptitle, $homeland_theme_pages;

			$homeland_search_header = esc_attr( get_option('homeland_search_header') );
			$homeland_search_subtitle = esc_attr( get_option('homeland_search_subtitle') );
			$homeland_not_found_header = esc_attr( get_option('homeland_not_found_header') );
			$homeland_not_found_subtitle = esc_attr( get_option('homeland_not_found_subtitle') );
			$homeland_forum_header = esc_attr( get_option('homeland_forum_header') );
			$homeland_forum_subtitle = esc_attr( get_option('homeland_forum_subtitle') );
			$homeland_property_archive_header = esc_attr( get_option('homeland_property_archive_header') );
			$homeland_property_archive_subtitle = esc_attr( get_option('homeland_property_archive_subtitle') );
			$homeland_portfolio_archive_header = esc_attr( get_option('homeland_portfolio_archive_header') );
			$homeland_portfolio_archive_subtitle = esc_attr( get_option('homeland_portfolio_archive_subtitle') );
			$homeland_services_archive_header = esc_attr( get_option('homeland_services_archive_header') );
			$homeland_services_archive_subtitle = esc_attr( get_option('homeland_services_archive_subtitle') );
			$homeland_agent_profile_header = esc_attr( get_option('homeland_agent_profile_header') );
			$homeland_agent_profile_subtitle = esc_attr( get_option('homeland_agent_profile_subtitle') );

			$homeland_property_archive_header_label = !empty($homeland_property_archive_header) ? $homeland_property_archive_header : __('Our Properties', 'codeex_theme_name');
			$homeland_property_archive_subtitle_label = !empty($homeland_property_archive_subtitle) ? $homeland_property_archive_subtitle : __('Lorem ipsum dolor sit amet, consectetur adipisicing elit', 'codeex_theme_name');
			$homeland_portfolio_archive_header_label = !empty($homeland_portfolio_archive_header) ? $homeland_portfolio_archive_header : __('Our Portfolio', 'codeex_theme_name');
			$homeland_portfolio_archive_subtitle_label = !empty($homeland_portfolio_archive_subtitle) ? $homeland_portfolio_archive_subtitle : __('Lorem ipsum dolor sit amet, consectetur adipisicing elit', 'codeex_theme_name');
			$homeland_services_archive_header_label = !empty($homeland_services_archive_header) ? $homeland_services_archive_header : __('Our Services', 'codeex_theme_name');
			$homeland_services_archive_subtitle_label = !empty($homeland_services_archive_subtitle) ? $homeland_services_archive_subtitle : __('Lorem ipsum dolor sit amet, consectetur adipisicing elit', 'codeex_theme_name');
			$homeland_search_header_label = !empty($homeland_search_header) ? $homeland_search_header : __('Search Results', 'codeex_theme_name');
			$homeland_search_subtitle_label = !empty($homeland_search_subtitle) ? $homeland_search_subtitle : __('This is your search subtitle description', 'codeex_theme_name');
			$homeland_not_found_header_label = !empty($homeland_not_found_header) ? $homeland_not_found_header : __('404 Page', 'codeex_theme_name');
			$homeland_not_found_subtitle_label = !empty($homeland_not_found_subtitle) ? $homeland_not_found_subtitle : __('Lorem ipsum dolor sit amet, consectetur adipisicing elit', 'codeex_theme_name');
			$homeland_agent_profile_header_label = !empty($homeland_agent_profile_header) ? $homeland_agent_profile_header : __('Agent Profile', 'codeex_theme_name');
			$homeland_agent_profile_subtitle_label = !empty($homeland_agent_profile_subtitle) ? $homeland_agent_profile_subtitle : __('Lorem ipsum dolor sit amet, consectetur adipisicing elit', 'codeex_theme_name');

			$homeland_ptitle = esc_attr( get_post_meta( @$post->ID, "homeland_ptitle", true ) );
			$homeland_subtitle = esc_attr( get_post_meta( @$post->ID, "homeland_subtitle", true ) );
			$homeland_address = esc_attr( get_post_meta( @$post->ID, 'homeland_address', true) );

			//header archive properties
			if (is_post_type_archive('homeland_properties')) : 
				echo '<h2 class="ptitle">' . $homeland_property_archive_header_label . '</h2>';
				echo '<h4 class="subtitle"><label>' . $homeland_property_archive_subtitle_label . '</label></h4>';

			//header archive portfolio
			elseif (is_post_type_archive('homeland_portfolio')) : 
				echo '<h2 class="ptitle">' . $homeland_portfolio_archive_header_label . '</h2>';
				echo '<h4 class="subtitle"><label>' . $homeland_portfolio_archive_subtitle_label . '</label></h4>';

			//header archive services
			elseif (is_post_type_archive('homeland_services')) : 
				echo '<h2 class="ptitle">' . $homeland_services_archive_header_label . '</h2>';
				echo '<h4 class="subtitle"><label>' . $homeland_services_archive_subtitle_label . '</label></h4>';

			//header archive default
			elseif(is_archive()) : 
				echo '<h2 class="ptitle">';
					if (is_category()) : single_cat_title();
					elseif( is_tag() ) : printf( _e('Posts Tagged: ', 'codeex_theme_name'), single_tag_title() ); 
					elseif ( is_day() ) : printf( __( 'Daily Archives: %s', 'codeex_theme_name' ), get_the_date() ); 
					elseif ( is_month() ) : printf( __( 'Monthly Archives: %s', 'codeex_theme_name' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'codeex_theme_name' ) ) ); 
					elseif ( is_year() ) : printf( __( 'Yearly Archives: %s', 'codeex_theme_name' ), get_the_date( _x( 'Y', 'yearly archives date format', 'codeex_theme_name' ) ) ); 
					elseif ( is_tax() ) : echo get_queried_object()->name;
					elseif ( is_author() ) : echo $homeland_agent_profile_header_label;
					elseif(function_exists('is_bbpress')) :
						if ( bbp_is_forum_archive() ) :
							if(!empty($homeland_forum_header)) : echo $homeland_forum_header;
							else : the_title(); endif; 
						endif;
					endif;
				echo '</h2>';

				if( is_author() ) : 
					echo '<h4 class="subtitle"><label>'. $homeland_agent_profile_subtitle_label .'</label></h4>';
				elseif( is_category() ) : 
					$homeland_category_id = get_query_var('cat'); 
					$homeland_category_data = get_option("category_$homeland_category_id"); 
					
					if(!empty($homeland_category_data['homeland_subtitle'])) : 
						echo '<h4 class="subtitle"><label>' . @$homeland_category_data['homeland_subtitle'] . '</label></h4>';
					endif;
				elseif( is_tax() ) : 
					$homeland_term = $wp_query->queried_object;
					$homeland_category_id = $homeland_term->term_id;
					$homeland_category_data = get_option("category_$homeland_category_id"); 
					
					if(!empty($homeland_category_data['homeland_subtitle'])) : 
						echo '<h4 class="subtitle"><label>' . @$homeland_category_data['homeland_subtitle'] . '</label></h4>';
					endif;
				elseif(function_exists('is_bbpress')) :	
					if ( bbp_is_forum_archive() ) :
						if(!empty($homeland_forum_subtitle)) : 
							echo '<h4 class="subtitle"><label>' . stripslashes ( $homeland_forum_subtitle ) . '</label></h4>';
						endif;	
					endif;	
				endif; 

			//header search
			elseif (is_search()) : 
				echo '<h2 class="ptitle">' . $homeland_search_header_label . '</h2>';
				echo '<h4 class="subtitle"><label>' . $homeland_search_subtitle_label . '</label></h4>';

			//header services
			elseif (is_singular('homeland_services')) : the_title('<h2 class="ptitle">', '</h2>');

			//header properties
			elseif (is_singular('homeland_properties')) : 
				the_title('<h2 class="ptitle">', '</h2>');
				if(!empty($homeland_address)) :
					echo '<h4 class="subtitle"><label>' . $homeland_address . '</label></h4>';
				endif;

			//header portfolio
			elseif (is_singular('homeland_portfolio')) : the_title('<h2 class="ptitle">', '</h2>');

			//header single page
			elseif (is_single() || is_home()) : 
				if(function_exists('is_bbpress')) :
					if(bbp_is_single_forum() || bbp_is_single_topic() || bbp_is_topic_edit()) : 
						the_title('<h2 class="ptitle">', '</h2>'); 
					else :
						the_title('<h2 class="ptitle">', '</h2>');
					endif;
				else : 
					the_title('<h2 class="ptitle">', '</h2>');
				endif;
				
			//header 404		
			elseif (is_404()) :	
				echo '<h2 class="ptitle">' . $homeland_not_found_header_label . '</h2>';
				echo '<h4 class="subtitle"><label>' . $homeland_not_found_subtitle_label . '</label></h4>';

			//header default	
			else :
				if(!empty($homeland_ptitle)) : 
					echo '<h2 class="ptitle">' . $homeland_ptitle . '</h2>';
				else : the_title('<h2 class="ptitle">', '</h2>'); endif; 

				if(!empty($homeland_subtitle)) : 
					echo '<h4 class="subtitle"><label>' . stripslashes ( $homeland_subtitle ) . '</label></h4>';
				endif;
			endif;		
		}
	endif;

	//Subtitle description
	if ( ! function_exists( 'homeland_get_page_title_subtitle_desc' ) ) :
		function homeland_get_page_title_subtitle_desc() {
			global $homeland_theme_pages;

			foreach($homeland_theme_pages as $homeland_page) :
				$homeland_page_title = esc_attr( get_post_meta( $homeland_page->ID, "homeland_ptitle", true ) );
				$homepage_subtitle = esc_attr( get_post_meta( $homeland_page->ID, "homeland_subtitle", true ) );

				if($homeland_page_title != "") : $homeland_ptitle = $homeland_page_title;
				else : $homeland_ptitle = esc_attr( $homeland_page->post_title ); endif;
			endforeach; 

			echo '<h2 class="ptitle">' . stripslashes ( $homeland_ptitle ) . '</h2>';
			if(!empty($homepage_subtitle)) : 
				echo '<h4 class="subtitle"><label>' . stripslashes ( $homeland_subtitle ) . '</label></h4>';
			endif;
		}
	endif;


	/*---------------------------------------------
	GET PROPERTY TYPE
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_get_property_category' ) ) :
		function homeland_get_property_category() {
			global $homeland_properties_page_url, $homeland_properties_page_2cols_url, $homeland_properties_page_3cols_url, $homeland_properties_page_4cols_url;
			?>
				<div class="cat-toogles">
					<ul class="cat-list clear">
						<li class="<?php if(is_page_template('template-properties.php') || is_page_template('template-properties-2cols.php') || is_page_template('template-properties-3cols.php') || is_page_template('template-properties-4cols.php')) : echo 'current-cat'; endif; ?>"><a href="<?php echo $homeland_properties_page_url; ?>">
							<?php esc_attr( _e( 'All', 'codeex_theme_name' ) ); ?></a>
						</li>
						<?php
							$args = array( 'taxonomy' => 'homeland_property_type', 'style' => 'list', 'title_li' => '', 'hierarchical' => false, 'order' => 'ASC', 'orderby' => 'title' );
							wp_list_categories ( $args );
						?>	
					</ul>
				</div>
			<?php
		}
	endif;	


	/*---------------------------------------------
	GET PROPERTY TERMS
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_property_terms' ) ) :
		function homeland_property_terms() {
			global $post, $homeland_property_status;

			$homeland_terms = get_the_terms( @$post->ID, 'homeland_property_status' ); 
			if ( $homeland_terms && ! is_wp_error( $homeland_terms ) ) : 
				$homeland_property_status = array();
				foreach ( $homeland_terms as $homeland_term ) {
					$homeland_property_status[] = $homeland_term->name;
				}							
				$homeland_property_status = join( ", ", $homeland_property_status );																
			endif;
		}
	endif;


	/*---------------------------------------------
	GET PAGE TEMPLATE LINK
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_template_page_link' ) ) :
		function homeland_template_page_link() {
			global $homeland_blog_page_url, $homeland_contact_page_url, 
			$homeland_properties_page_url, $homeland_properties_page_2cols_url, 
			$homeland_properties_page_3cols_url, $homeland_properties_page_4cols_url, 
			$homeland_about_page_url, $homeland_agent_page_url, 
			$homeland_services_page_url, $homeland_advance_search_page_url, 
			$homeland_portfolio_page_url, $homeland_login_page_url;

			echo $homeland_login_page_url;

			$homeland_properties_pages = get_pages(array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-properties.php' ));
			foreach($homeland_properties_pages as $page){
				$homeland_properties_page_id = $page->ID;
				$homeland_properties_page_url = get_permalink($homeland_properties_page_id);
			}

			$homeland_properties_pages_4cols = get_pages(array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-properties-4cols.php' ));
			foreach($homeland_properties_pages_4cols as $page){
				$homeland_properties_page_4cols_id = $page->ID;
				$homeland_properties_page_4cols_url = get_permalink($homeland_properties_page_4cols_id);
			}

			$homeland_properties_pages_3cols = get_pages(array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-properties-3cols.php' ));
			foreach($homeland_properties_pages_3cols as $page){
				$homeland_properties_page_3cols_id = $page->ID;
				$homeland_properties_page_3cols_url = get_permalink($homeland_properties_page_3cols_id);
			}

			$homeland_properties_pages_2cols = get_pages(array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-properties-2cols.php' ));
			foreach($homeland_properties_pages_2cols as $page){
				$homeland_properties_page_2cols_id = $page->ID;
				$homeland_properties_page_2cols_url = get_permalink($homeland_properties_page_2cols_id);
			}

			$homeland_blog_pages = get_pages(array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-blog.php' ));
			foreach($homeland_blog_pages as $page){
				$homeland_blog_page_id = $page->ID;
				$homeland_blog_page_url = get_permalink($homeland_blog_page_id);
			}

			$homeland_contact_pages = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'template-contact.php'));
			foreach($homeland_contact_pages as $page){
				$homeland_contact_page_id = $page->ID;
				$homeland_contact_page_url = get_permalink($homeland_contact_page_id);
			}

			$homeland_about_pages = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'template-about.php'));
			foreach($homeland_about_pages as $page){
				$homeland_about_page_id = $page->ID;
				$homeland_about_page_url = get_permalink($homeland_about_page_id);
			}

			$homeland_agent_pages = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'template-agents.php'));
			foreach($homeland_agent_pages as $page){
				$homeland_agent_page_id = $page->ID;
				$homeland_agent_page_url = get_permalink($homeland_agent_page_id);
			}

			$homeland_services_pages = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'template-services.php'));
			foreach($homeland_services_pages as $page){
				$homeland_services_page_id = $page->ID;
				$homeland_services_page_url = get_permalink($homeland_services_page_id);
			}

			$homeland_advance_search_pages = get_pages(array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-property-search.php' ));
			foreach($homeland_advance_search_pages as $page){
				$homeland_advance_search_page_id = $page->ID;
				$homeland_advance_search_page_url = get_permalink($homeland_advance_search_page_id);
			}

			$homeland_portfolio_pages = get_pages(array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-portfolio.php' ));
			foreach($homeland_portfolio_pages as $page){
				$homeland_portfolio_page_id = $page->ID;
				$homeland_portfolio_page_url = get_permalink($homeland_portfolio_page_id);
			}

			$homeland_login_pages = get_pages(array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-login.php' ));
			foreach($homeland_login_pages as $page){
				$homeland_login_page_id = $page->ID;
				$homeland_login_page_url = get_permalink($homeland_login_page_id);
			}
		}
	endif;


	/*---------------------------------------------
	CUSTOM EXCERPT LENGTH
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_custom_excerpt_length' ) ) :
		function homeland_custom_excerpt_length( $length ) { return 30; }
	endif;
	add_filter( 'excerpt_length', 'homeland_custom_excerpt_length', 999 );

	if ( ! function_exists( 'homeland_custom_excerpt_more' ) ) :
		function homeland_custom_excerpt_more( $more ) { return ' ...'; }
	endif;
	add_filter( 'excerpt_more', 'homeland_custom_excerpt_more' );


	/*---------------------------------------------
	REMOVE DEFAULT COMMENT FIELDS
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_remove_comment_fields' ) ) :
		function homeland_remove_comment_fields($arg) {
		    $arg['url'] = '';
		    return $arg;
		}
	endif;
	add_filter('comment_form_default_fields', 'homeland_remove_comment_fields');

	
	/*---------------------------------------------
	CUSTOM COMMENT STYLE
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_theme_comment' ) ) :
		function homeland_theme_comment($comment, $args, $depth) {
			$GLOBALS['comment'] = $comment; ?>
				
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
					<div class="parent clear" id="comment-<?php comment_ID(); ?>">					
						<?php echo get_avatar( $comment, 60 ); ?>
						<div class="comment-details">
							<h5><?php comment_author_link(); ?>
								<span><?php echo human_time_diff( get_comment_time('U'), current_time('timestamp') ) . ' ago'; ?> <?php edit_comment_link('edit','&nbsp;',''); ?></span>	
							</h5> 
							<?php 
								comment_text(); 
								comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
								edit_comment_link('edit','&nbsp;',''); 
								if ($comment->comment_approved == '0') : ?><em><?php _e( 'Your comment is awaiting moderation.', 'codeex_theme_name' ); ?></em><?php 
								endif; 					
							?>
						</div>
					</div>	
				
				<?php
					$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
					paginate_comments_links();
		}
	endif;


	/*---------------------------------------------
	CUSTOM PAGINATION
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_pagination' ) ) :
		function homeland_pagination() {  
			global $wp_query, $homeland_max_num_pages;
			$big = 999999999;

			if(is_page_template('template-agents.php') || is_page_template('template-agents-fullwidth.php') || is_page_template('template-agents-list-fullwidth.php') || is_page_template('template-agents-left-sidebar.php')) :
				$max_num_pages_count = $homeland_max_num_pages;
			else :
				$max_num_pages_count = $wp_query->max_num_pages;
			endif;

			if($max_num_pages_count == '1' ) : else : echo "<div class=\"pagination clear\">"; endif;
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
				'format' => '?paged=%#%',
				'prev_text' => __( '&laquo;', 'codeex_theme_name' ),
	    		'next_text' => __( '&raquo;', 'codeex_theme_name' ),
				'current' => max( 1, get_query_var('paged') ),
				'total' => $max_num_pages_count,
				'type' => 'list'
			));
			if($max_num_pages_count == '1' ) : else : echo "</div>"; endif;
		}
	endif;


	/*---------------------------------------------
	CUSTOM NEXT PREVIOUS LINK
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_next_previous' ) ) :
		function homeland_next_previous() {
			?>
				<div class="pagination">
					<?php
					   global $wp_query, $paged, $homeland_max_num_pages;		

					   if(is_page_template('template-agents.php') || is_page_template('template-agents-fullwidth.php') || is_page_template('template-agents-list-fullwidth.php') || is_page_template('template-agents-left-sidebar.php')) :
							$max_num_pages_count = $homeland_max_num_pages;
						else :
							$max_num_pages_count = $wp_query->max_num_pages;
						endif;

					   if ($paged > 1) : ?>
					   	<div class="alignleft">
					   		<a href="<?php previous_posts(); ?>">&larr; <?php _e( 'Previous', 'codeex_theme_name' ); ?></a>
					   	</div><?php
					   endif;
					    
					   if ($max_num_pages_count == 1) :	    		
				    	elseif ($paged < $max_num_pages_count) : ?>
				    		<div class="alignright">
				    			<a href="<?php next_posts(); ?>"><?php _e( 'Next', 'codeex_theme_name' ); ?> &rarr;</a>
				    		</div><?php
				    	endif;
					?>
				</div>
			<?php
		}
	endif;


	/*---------------------------------------------
	FIX PAGINATION FOR TAXONOMIES
	----------------------------------------------*/

	$homeland_posts_per_page = get_option( 'posts_per_page' );
	
	if ( ! function_exists( 'homeland_modify_posts_per_page' ) ) :		
		function homeland_modify_posts_per_page() { 
			add_filter( 'option_posts_per_page', 'homeland_option_posts_per_page' ); 
		}
	endif;
	add_action( 'init', 'homeland_modify_posts_per_page', 0);

	if ( ! function_exists( 'homeland_option_posts_per_page' ) ) :	
		function homeland_option_posts_per_page( $value ) {
		   global $homeland_posts_per_page, $wp_query;
		   if ( is_tax( 'homeland_property_type') ) : return $wp_query->max_num_pages;
		   elseif ( is_tax( 'homeland_property_status') ) : return $wp_query->max_num_pages;
		   elseif ( is_tax( 'homeland_property_location') ) : return $wp_query->max_num_pages;
		   elseif ( is_tax( 'homeland_property_amenities') ) : return $wp_query->max_num_pages;
		   elseif ( is_author() ) : return $wp_query->max_num_pages;
		   elseif ( is_post_type_archive( 'homeland_properties') ) : return $wp_query->max_num_pages;
		   elseif ( is_post_type_archive( 'homeland_portfolio') ) : return $wp_query->max_num_pages;
		   elseif ( is_post_type_archive( 'homeland_services') ) : return $wp_query->max_num_pages;
		   else : return $homeland_posts_per_page; endif;
		}
	endif;


	/*---------------------------------------------
	FOR PAGINATION WORKING ON STATIC HOMEPAGE
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_get_home_pagination' ) ) :	
		function homeland_get_home_pagination() {
			global $paged, $wp_query, $wp;
			$args = wp_parse_args($wp->matched_query);
			if ( !empty ( $args['paged'] ) && 0 == $paged ) :
				$wp_query->set('paged', $args['paged']);
			  	$paged = $args['paged'];
			endif;
		}
	endif;
	

	/*---------------------------------------------
	FOR PAGINATION WORKING FOR AUTHOR PAGE
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_custom_agent_pagination' ) ) :	
		function homeland_custom_agent_pagination( &$query ) {
	    	if ($query->is_author) $query->set( 'post_type', array( 'homeland_properties', 'post' ) );
		}
		add_action( 'pre_get_posts', 'homeland_custom_agent_pagination' );
	endif;


	/*---------------------------------------------
	AGENT CONTRIBUTOR UPLOAD FILES
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_allow_contributor_uploads' ) ) :	
		function homeland_allow_contributor_uploads() {
	 		$contributor = get_role('contributor');
	 		$contributor->add_cap('upload_files');
	 		$contributor->add_cap('can_edit_posts');
		}
	endif;
	//if ( current_user_can('contributor') && !current_user_can('upload_files') )
 	add_action('admin_init', 'homeland_allow_contributor_uploads');


 	/*---------------------------------------------
	AGENT CUSTOM COLUMNS (ADMIN)
	----------------------------------------------*/
 	
	function homeland_users_properties_column( $homeland_cols ) {
	  	$homeland_cols['homeland_user_properties'] = __( 'Listings', 'codeex_theme_name' );   
	  	return $homeland_cols;
	}

	function homeland_user_properties_column_value( $homeland_value, $homeland_column_name, $homeland_id ) {
	  	if( $homeland_column_name == 'homeland_user_properties' ) {
	    	global $wpdb;
	    	$homeland_count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'homeland_properties' AND post_status = 'publish' AND post_author = %d", $homeland_id ) );
	    	return $homeland_count;
	  	}
	}

	add_filter( 'manage_users_custom_column', 'homeland_user_properties_column_value', 10, 3 );
	add_filter( 'manage_users_columns', 'homeland_users_properties_column' );


	/*---------------------------------------------
	ADVANCE PROPERTY SEARCH
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_advance_property_search' ) ) :	
		function homeland_advance_property_search( $homeland_search_args ) {
			$homeland_tax_query = array();
			$homeland_meta_query = array();

			if( (!empty($_GET['location'])) ) : 
				$homeland_tax_query[] = array('taxonomy' => 'homeland_property_location', 'field' => 'slug', 'terms' => $_GET['location']); 
			endif;

			if( (!empty($_GET['status'])) ) : 
				$homeland_tax_query[] = array('taxonomy' => 'homeland_property_status', 'field' => 'slug', 'terms' => $_GET['status']); 
			endif;

			if( (!empty($_GET['type'])) ) : 
				$homeland_tax_query[] = array('taxonomy' => 'homeland_property_type', 'field' => 'slug', 'terms' => $_GET['type']); 
			endif;

			if( (!empty($_GET['amenities'])) ) : 
				$homeland_tax_query[] = array('taxonomy' => 'homeland_property_amenities', 'field' => 'slug', 'terms' => $_GET['amenities']); 
			endif;

			if( (!empty($_GET['bed'])) ) : 
				$homeland_meta_query[] = array('key' => 'homeland_bedroom', 'value' => $_GET['bed'], 'type' => 'NUMERIC', 'compare' => '='); 
			endif;

			if( (!empty($_GET['bath'])) ) : 
				$homeland_meta_query[] = array(
					'key' => 'homeland_bathroom', 'value' => $_GET['bath'], 'type' => 'NUMERIC', 'compare' => '='
				);
			endif;

			if( (!empty($_GET['pid'])) ) : 
				$homeland_meta_query[] = array(
					'key' => 'homeland_property_id', 'value' => $_GET['pid'], 'type' => 'CHAR', 'compare' => '='
				); 
			endif;

			//Both Minimum and Maximum Price
	     	if(isset($_GET['min-price']) && isset($_GET['max-price'])) :
	       	$homeland_min_price = intval($_GET['min-price']);
	         $homeland_max_price = intval($_GET['max-price']);
	         
				if( $homeland_min_price >= 0 && $homeland_max_price > $homeland_min_price ) :
				  	$homeland_meta_query[] = array( 
				  		'key' => 'homeland_price', 
				  		'value' => array( $homeland_min_price, $homeland_max_price ), 
				  		'type' => 'NUMERIC', 
				  		'compare' => 'BETWEEN' 
				  	);
				endif;
	      
	      //Minimum Price
	      elseif(isset($_GET['min-price'])) :
	      	$homeland_min_price = intval($_GET['min-price']);   
	      	if( $homeland_min_price > 0 ) : 
	      		$homeland_meta_query[] = array( 
	      			'key' => 'homeland_price', 
	      			'value' => $homeland_min_price, 
	      			'type' => 'NUMERIC', 
	      			'compare' => '>=' 
	      		); 
	      	endif;

	      //Maximum Price
	      elseif(isset($_GET['max-price'])) :
	          $homeland_max_price = intval($_GET['max-price']);
	          if( $homeland_max_price > 0 ) : 
	          	$homeland_meta_query[] = array( 
	          		'key' => 'homeland_price', 
	          		'value' => $homeland_max_price, 
	          		'type' => 'NUMERIC', 
	          		'compare' => '<=' 
	          	); 
	          endif;
	 		endif;

			$homeland_tax_count = count( $homeland_tax_query );
			if( $homeland_tax_count > 1 ) : $homeland_tax_query['relation'] = 'AND'; endif;

			$homeland_meta_count = count( $homeland_meta_query );
			if( $homeland_meta_count > 1 ) : $homeland_meta_query['relation'] = 'AND'; endif;
			if( $homeland_tax_count > 0 ) : $homeland_search_args['tax_query'] = $homeland_tax_query; endif;
			if( $homeland_meta_count > 0 ) : $homeland_search_args['meta_query'] = $homeland_meta_query; endif;

			if(isset($_GET['filter-order'])) :
				if($_GET['filter-order'] == "ASC") :
					$homeland_search_args['order'] = 'ASC';
				else :
			   	$homeland_search_args['order'] = 'DESC';
			   endif;
			endif;

			if(isset($_GET['filter-sort'])) :
				if($_GET['filter-sort'] == "homeland_price") :
				   $homeland_search_args['meta_key'] = 'homeland_price';
				   $homeland_search_args['orderby'] = 'meta_value_num';
				elseif($_GET['filter-sort'] == "title") :
				   $homeland_search_args['orderby'] = 'title';
				else :
					$homeland_search_args['orderby'] = 'date';
				endif;
			endif;

			return $homeland_search_args;
	   }
	endif;
   add_filter('homeland_advance_search_parameters', 'homeland_advance_property_search');


  	/*---------------------------------------------
	PROPERTY SORT and ORDER
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_property_sort_order' ) ) :	
		function homeland_property_sort_order() {
			$homeland_filter_default = esc_attr( get_option('homeland_filter_default') );	
			$homeland_path = $_SERVER['REQUEST_URI']; ?>
			<div class="clear">
				<div class="filter-sort-order">
					<form action="<?php echo $homeland_path; ?>" method="get" class="form-sorting-order">

						<?php
							if(is_page_template('template-property-search.php')) : ?>
								<input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>">
								<input type="hidden" name="location" value="<?php echo $_GET['location']; ?>">
		                  <input type="hidden" name="status" value="<?php echo $_GET['status']; ?>">
		                  <input type="hidden" name="type" value="<?php echo $_GET['type']; ?>">
		                  <input type="hidden" name="bed" value="<?php echo $_GET['bed']; ?>">
		                  <input type="hidden" name="bath" value="<?php echo $_GET['bath']; ?>">
		                  <input type="hidden" name="min-price" value="<?php echo $_GET['min-price']; ?>">
		                  <input type="hidden" name="max-price" value="<?php echo $_GET['max-price']; ?>"><?php
							endif;
						?>

						<label for="input_order"><?php _e( 'Order', 'codeex_theme_name' ); ?></label>
					 	<select name="filter-order" id="input_order">
				         <?php
								$homeland_filter_order = @$_GET['filter-order'];
								$homeland_array = array( 'DESC' => __( 'Descending', 'codeex_theme_name' ), 'ASC' => __( 'Ascending', 'codeex_theme_name' ) );

								foreach($homeland_array as $homeland_order_option_value=>$homeland_order_option) : ?>
				               <option value="<?php echo $homeland_order_option_value; ?>" <?php if($homeland_filter_order == $homeland_order_option_value) : echo "selected='selected'"; endif; ?>>
				               	<?php echo $homeland_order_option; ?>
				               </option><?php
				            endforeach;
							?>		
				     	</select>
				     	<label for="input_sort"><?php _e( 'Sort By', 'codeex_theme_name' ); ?></label>
					 	<select name="filter-sort" id="input_sort">
							<?php
								$homeland_filter_sort = @$_GET['filter-sort'];

								if($homeland_filter_default == "Date") :
									$homeland_array = array( 'date' => __( 'Date', 'codeex_theme_name' ), 'title' => __( 'Name', 'codeex_theme_name' ), 'homeland_price' => __( 'Price', 'codeex_theme_name' ), );
								elseif($homeland_filter_default == "Name") :
									$homeland_array = array( 'title' => __( 'Name', 'codeex_theme_name' ), 'date' => __( 'Date', 'codeex_theme_name' ), 'homeland_price' => __( 'Price', 'codeex_theme_name' ), );
								elseif($homeland_filter_default == "Price") :
									$homeland_array = array( 'homeland_price' => __( 'Price', 'codeex_theme_name' ), 'title' => __( 'Name', 'codeex_theme_name' ), 'date' => __( 'Date', 'codeex_theme_name' ), );
								endif;
								
								foreach($homeland_array as $homeland_sort_option_value=>$homeland_sort_option) : ?>
				               <option value="<?php echo $homeland_sort_option_value; ?>" <?php if($homeland_filter_sort == $homeland_sort_option_value) : echo "selected='selected'"; endif; ?>>
				               	<?php echo $homeland_sort_option; ?>
				               </option><?php
				            endforeach;
							?>		
						</select>                                                                                        
				   </form>	
				</div>
			</div><?php
		}
	endif;


	/*---------------------------------------------
	REMOVE & ADD NEW FIELD IN USER PROFILE
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_add_new_contact_info' ) ) :	
		function homeland_add_new_contact_info( $homeland_contact_methods ) {
			$homeland_contact_methods['homeland_designation'] = __( 'Designation', 'codeex_theme_name' );
			$homeland_contact_methods['homeland_twitter'] = __( 'Twitter', 'codeex_theme_name' );
			$homeland_contact_methods['homeland_facebook'] = __( 'Facebook', 'codeex_theme_name' );
			$homeland_contact_methods['homeland_gplus'] = __( 'Google Plus', 'codeex_theme_name' );
			$homeland_contact_methods['homeland_linkedin'] = __( 'LinkedIn', 'codeex_theme_name' );
			$homeland_contact_methods['homeland_telno'] = __( 'Telephone', 'codeex_theme_name' );
			$homeland_contact_methods['homeland_mobile'] = __( 'Mobile', 'codeex_theme_name' );
			$homeland_contact_methods['homeland_fax'] = __( 'Fax', 'codeex_theme_name' );

			//remove fields
		   unset($homeland_contact_methods['aim']);
			unset($homeland_contact_methods['jabber']);
			unset($homeland_contact_methods['yim']);

		   return $homeland_contact_methods;
		}
	endif;
	add_filter('user_contactmethods','homeland_add_new_contact_info', 10, 1);


	/*---------------------------------------------
	ADD POST THUMBNAIL SIZE IN MEDIA UPLOAD
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_get_additional_image_sizes' ) ) :
		function homeland_get_additional_image_sizes() {
			$sizes = array();
			global $_wp_additional_image_sizes;
			if ( isset($_wp_additional_image_sizes) && count($_wp_additional_image_sizes) ) {
				$sizes = apply_filters( 'intermediate_image_sizes', $_wp_additional_image_sizes );
				$sizes = apply_filters( 'homeland_get_additional_image_sizes', $_wp_additional_image_sizes );
			}
			return $sizes;
		}
	endif;

	if ( ! function_exists( 'homeland_additional_image_size_input_fields' ) ) :
		function homeland_additional_image_size_input_fields( $fields, $post ) {
			if ( !isset($fields['image-size']['html']) || substr($post->post_mime_type, 0, 5) != 'image' )
				return $fields;

			$sizes = homeland_get_additional_image_sizes();
			if ( !count($sizes) )
				return $fields;

			$items = array();
			foreach ( array_keys($sizes) as $size ) {
				$downsize = image_downsize( $post->ID, $size );
				$enabled = $downsize[3];
				$css_id = "image-size-{$size}-{$post->ID}";
				$label = apply_filters( 'image_size_name', $size );

				$html  = "<div class='image-size-item'>\n";
				$html .= "<input type='radio' " . disabled( $enabled, false, false ) . "name='attachments[{$post->ID}][image-size]' id='{$css_id}' value='{$size}' />\n";
				$html .= "<label for='{$css_id}'>{$label}</label>\n";
				if ( $enabled )
					$html .= "<label for='{$css_id}' class='help'>" . sprintf( "(%d x %d)", $downsize[1], $downsize[2] ). "</label>\n";
				$html .= "</div>";

				$items[] = $html;
			}

			$items = join( "\n", $items );
			$fields['image-size']['html'] = "{$fields['image-size']['html']}\n{$items}";

			return $fields;
		}
	endif;
	add_filter( 'attachment_fields_to_edit', 'homeland_additional_image_size_input_fields', 11, 2 );


	/*---------------------------------------------
	ADD CATEGORY FIELD
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_create_category_fields' ) ) :
		function homeland_create_category_fields( $homeland_tag ) {    
	    	$homeland_extra_id = @$homeland_tag->term_id;
	    	$homeland_cat_meta = get_option( "category_$homeland_extra_id");
			?>
				<div class="form-field">
					<label for="homeland_cat_meta[homeland_subtitle]"><?php _e( 'Subtitle', 'codeex_theme_name' ); ?></label>
					<input type="text" name="homeland_cat_meta[homeland_subtitle]" id="homeland_cat_meta[homeland_subtitle]" value="<?php echo $homeland_cat_meta['homeland_subtitle'] ? $homeland_cat_meta['homeland_subtitle'] : ''; ?>">
				    <p><?php esc_attr( _e( 'Add your subtitle text here', 'codeex_theme_name' ) ); ?></p>			        
				</div>
			<?php
		}
	endif;

	if ( ! function_exists( 'homeland_edit_category_fields' ) ) :
		function homeland_edit_category_fields( $homeland_tag ) {    
	    	$homeland_extra_id = $homeland_tag->term_id;
	    	$homeland_cat_meta = get_option( "category_$homeland_extra_id");
			?>
				<tr class="form-field">
					<th valign="top" scope="row"><label for="homeland_cat_meta[homeland_subtitle]"><?php _e( 'Subtitle', 'codeex_theme_name' ); ?></label></th>
					<td>
						<input type="text" name="homeland_cat_meta[homeland_subtitle]" id="homeland_cat_meta[homeland_subtitle]" value="<?php echo $homeland_cat_meta['homeland_subtitle'] ? $homeland_cat_meta['homeland_subtitle'] : ''; ?>"><br>
						<span class="description"><?php esc_attr( _e( 'Edit your subtitle text here', 'codeex_theme_name' ) ); ?></span>
					</td>		        
				</tr>
			<?php
		}
	endif;

	add_action ( 'category_edit_form_fields', 'homeland_edit_category_fields');
	add_action ( 'category_add_form_fields', 'homeland_create_category_fields');

	add_action ( 'homeland_property_type_edit_form_fields', 'homeland_edit_category_fields');
	add_action ( 'homeland_property_type_add_form_fields', 'homeland_create_category_fields');
	add_action ( 'homeland_property_status_edit_form_fields', 'homeland_edit_category_fields');
	add_action ( 'homeland_property_status_add_form_fields', 'homeland_create_category_fields');
	add_action ( 'homeland_property_location_edit_form_fields', 'homeland_edit_category_fields');
	add_action ( 'homeland_property_location_add_form_fields', 'homeland_create_category_fields');
	add_action ( 'homeland_property_amenities_edit_form_fields', 'homeland_edit_category_fields');
	add_action ( 'homeland_property_amenities_add_form_fields', 'homeland_create_category_fields');

	add_action ( 'homeland_portfolio_category_edit_form_fields', 'homeland_edit_category_fields');
	add_action ( 'homeland_portfolio_category_add_form_fields', 'homeland_create_category_fields');


	if ( ! function_exists( 'homeland_save_extra_category_fields' ) ) :
		function homeland_save_extra_category_fields( $term_id ) {
			if ( isset( $_POST['homeland_cat_meta'] ) ) {
				$homeland_extra_id = $term_id;
				$homeland_cat_meta = get_option( "category_$homeland_extra_id");
				$homeland_cat_keys = array_keys($_POST['homeland_cat_meta']);
				   foreach ($homeland_cat_keys as $homeland_key){
				   if (isset($_POST['homeland_cat_meta'][$homeland_key])){
				      $homeland_cat_meta[$homeland_key] = $_POST['homeland_cat_meta'][$homeland_key];
				   }
				}
				update_option( "category_$homeland_extra_id", $homeland_cat_meta );        
			}
		}
	endif;

	add_action ( 'edited_category', 'homeland_save_extra_category_fields');
	add_action ( 'create_category', 'homeland_save_extra_category_fields');

	add_action ( 'edited_homeland_property_type', 'homeland_save_extra_category_fields');
	add_action ( 'create_homeland_property_type', 'homeland_save_extra_category_fields');
	add_action ( 'edited_homeland_property_status', 'homeland_save_extra_category_fields');
	add_action ( 'create_homeland_property_status', 'homeland_save_extra_category_fields');
	add_action ( 'edited_homeland_property_location', 'homeland_save_extra_category_fields');
	add_action ( 'create_homeland_property_location', 'homeland_save_extra_category_fields');
	add_action ( 'edited_homeland_property_amenities', 'homeland_save_extra_category_fields');
	add_action ( 'create_homeland_property_amenities', 'homeland_save_extra_category_fields');

	add_action ( 'edited_homeland_portfolio_category', 'homeland_save_extra_category_fields');
	add_action ( 'create_homeland_portfolio_category', 'homeland_save_extra_category_fields');


	/*---------------------------------------------
	CONVERT HEX TO RGBA
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_hex2rgba' ) ) :	
		function homeland_hex2rgba($homeland_color, $homeland_opacity = false) {
			$homeland_default = 'rgb(0,0,0)';
			if(empty($homeland_color))
				return $homeland_default; 
				if ($homeland_color[0] == '#' ) {
					$homeland_color = substr( $homeland_color, 1 );
				}

				if (strlen($homeland_color) == 6) :
				   $homeland_hex = array( $homeland_color[0] . $homeland_color[1], $homeland_color[2] . $homeland_color[3], $homeland_color[4] . $homeland_color[5] );
				elseif ( strlen( $homeland_color ) == 3 ) :
				   $homeland_hex = array( $homeland_color[0] . $homeland_color[0], $homeland_color[1] . $homeland_color[1], $homeland_color[2] . $homeland_color[2] );
				else :
				   return $homeland_default;
				endif;

				$homeland_rgb =  array_map('hexdec', $homeland_hex);

				if($homeland_opacity) :
					if(abs($homeland_opacity) > 1)
						$homeland_opacity = 1.0;
						$homeland_output = 'rgba('.implode(",",$homeland_rgb).','.$homeland_opacity.')';
				else :
					$homeland_output = 'rgb('.implode(",",$homeland_rgb).')';
				endif;

				return $homeland_output;
		}	
	endif;


	/*---------------------------------------------
	CUSTOM RESIZABLE BACKGROUND
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_theme_custom_background' ) ) :	
		function homeland_theme_custom_background() {
			$homeland_bg_type = esc_attr( get_option('homeland_bg_type') );
			$homeland_theme_layout = esc_attr( get_option('homeland_theme_layout') );

			$homeland_forum_bgimage = esc_attr( get_option('homeland_forum_bgimage') );
			$homeland_forum_single_bgimage = esc_attr( get_option('homeland_forum_single_bgimage') );
			$homeland_forum_single_topic_bgimage = esc_attr( get_option('homeland_forum_single_topic_bgimage') );
			$homeland_forum_topic_edit_bgimage = esc_attr( get_option('homeland_forum_topic_edit_bgimage') );
			$homeland_forum_search_bgimage = esc_attr( get_option('homeland_forum_search_bgimage') );
			$homeland_user_profile_bgimage = esc_attr( get_option('homeland_user_profile_bgimage') );

			if(function_exists('is_bbpress')) :
				if($homeland_bg_type == "Image" && ($homeland_theme_layout == "Boxed" || $homeland_theme_layout == "Boxed Left")) :
					if(bbp_is_single_forum()) :
						if(!empty($homeland_forum_single_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_forum_single_bgimage; ?>"); }); 
							</script><?php					
						else : homeland_default_img_bg(); endif;
					elseif(bbp_is_single_topic()) :
						if(!empty($homeland_forum_single_topic_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_forum_single_topic_bgimage; ?>"); }); 
							</script><?php					
						else : homeland_default_img_bg(); endif;	
					elseif(bbp_is_topic_edit()) :
						if(!empty($homeland_forum_topic_edit_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_forum_topic_edit_bgimage; ?>"); }); 
							</script><?php					
						else : homeland_default_img_bg(); endif;
					elseif(bbp_is_search()) :
						if(!empty($homeland_forum_search_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_forum_search_bgimage; ?>"); }); 
							</script><?php					
						else : homeland_default_img_bg(); endif;
					elseif(bbp_is_single_user()) :
						if(!empty($homeland_user_profile_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_user_profile_bgimage; ?>"); }); 
							</script><?php					
						else : homeland_default_img_bg(); endif;
					elseif(bbp_is_forum_archive() || is_bbpress()) :
						if(!empty($homeland_forum_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_forum_bgimage; ?>"); }); 
							</script><?php					
						else : homeland_default_img_bg(); endif;
					else :
						homeland_bg_conditions();
					endif;
				endif;
			else :
				homeland_bg_conditions();
			endif;
		}	
	endif;
	add_action( 'wp_footer', 'homeland_theme_custom_background' );


	if ( ! function_exists( 'homeland_bg_conditions' ) ) :	
		function homeland_bg_conditions() {
			global $post;
			$homeland_bg_type = esc_attr( get_option('homeland_bg_type') );
			$homeland_theme_layout = esc_attr( get_option('homeland_theme_layout') );

			$homeland_bgimage = esc_attr( get_post_meta(@$post->ID, "homeland_bgimage", true) );
			$homeland_archive_bgimage = esc_attr( get_option('homeland_archive_bgimage') );
			$homeland_search_bgimage = esc_attr( get_option('homeland_search_bgimage') );
			$homeland_notfound_bgimage = esc_attr( get_option('homeland_notfound_bgimage') );
			$homeland_taxonomy_bgimage = esc_attr( get_option('homeland_taxonomy_bgimage') );
			$homeland_agent_bgimage = esc_attr( get_option('homeland_agent_bgimage') );

			if($homeland_bg_type == "Image" && ($homeland_theme_layout == "Boxed" || $homeland_theme_layout == "Boxed Left")) :
				//Archive 
				if(is_archive()) :
					if(is_author()) :
						if(!empty($homeland_agent_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_agent_bgimage; ?>"); }); 
							</script><?php					
						else : homeland_default_img_bg(); endif;
					elseif(is_tax()) :
						if(!empty($homeland_taxonomy_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_taxonomy_bgimage; ?>"); }); 
							</script><?php					
						else : homeland_default_img_bg(); endif;
					else :
						if(!empty($homeland_archive_bgimage)) : ?>
							<script type="text/javascript"> 
								jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_archive_bgimage; ?>"); }); 
							</script><?php	
						else : homeland_default_img_bg(); endif;
					endif;

				//Search
				elseif(is_search()) :
					if(!empty($homeland_search_bgimage)) : ?>
						<script type="text/javascript"> 
							jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_search_bgimage; ?>"); }); 
						</script><?php					
					else : homeland_default_img_bg(); endif;

				//404 Page
				elseif(is_404()) :
					if(!empty($homeland_notfound_bgimage)) : ?>
						<script type="text/javascript"> 
							jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_notfound_bgimage; ?>"); }); 
						</script><?php						
					else : homeland_default_img_bg(); endif;	

				//Coming Soon
				elseif(is_page_template('template-coming-soon.php')) :

				//Page and Single Page
				elseif(is_page() || is_single()) :
					if(!empty($homeland_bgimage)) : ?>
						<script type="text/javascript">
							jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_bgimage; ?>"); });
						</script><?php					
					else : homeland_default_img_bg(); endif;	

				//Homepage
				elseif(is_home()) : 
					homeland_default_img_bg();
				else : 
					homeland_default_img_bg();
				endif;
			endif;
		}
	endif;


	//Background Default Image

	if ( ! function_exists( 'homeland_default_img_bg' ) ) :	
		function homeland_default_img_bg() {
			$homeland_default_bgimage = esc_attr( get_option('homeland_default_bgimage') );
			$homeland_empty_bg = "http://themecss.com/wp/Homeland/wp-content/uploads/2013/12/View-over-the-lake_www.LuxuryWallpapers.net_.jpg";

			if(!empty($homeland_default_bgimage)) : ?>
				<script type="text/javascript"> 
					jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_default_bgimage; ?>"); }); 
				</script><?php	
			else : ?>
				<script type="text/javascript"> 
					jQuery(window).load(function() { jQuery.backstretch("<?php echo $homeland_empty_bg; ?>");  }); 
				</script><?php
			endif;
		}	
	endif;


	/*---------------------------------------------
	CUSTOM HEADER IMAGES
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_header_image' ) ) :	
		function homeland_header_image() {
			global $post;

			$homeland_page_hd_image = esc_attr( get_post_meta( @$post->ID, 'homeland_hdimage', true ) );
			$homeland_page_hide_search = get_post_meta( @$post->ID, 'homeland_advance_search', true );
			$homeland_archive_hdimage = esc_attr( get_option('homeland_archive_hdimage') );
			$homeland_search_hdimage = esc_attr( get_option('homeland_search_hdimage') );
			$homeland_notfound_hdimage = esc_attr( get_option('homeland_notfound_hdimage') );
			$homeland_agent_hdimage = esc_attr( get_option('homeland_agent_hdimage') );
			$homeland_taxonomy_hdimage = esc_attr( get_option('homeland_taxonomy_hdimage') );
			$homeland_default_hdimage = esc_attr( get_option('homeland_default_hdimage') );
			$homeland_forum_hdimage = esc_attr( get_option('homeland_forum_hdimage') );
			$homeland_forum_single_hdimage = esc_attr( get_option('homeland_forum_single_hdimage') );
			$homeland_forum_single_topic_hdimage = esc_attr( get_option('homeland_forum_single_topic_hdimage') );
			$homeland_forum_topic_edit_hdimage = esc_attr( get_option('homeland_forum_topic_edit_hdimage') );
			$homeland_forum_search_hdimage = esc_attr( get_option('homeland_forum_search_hdimage') );
			$homeland_user_profile_hdimage = esc_attr( get_option('homeland_user_profile_hdimage') );
			$homeland_disable_advance_search = esc_attr( get_option('homeland_disable_advance_search') );
			$homeland_hide_ptitle_stitle = esc_attr( get_option('homeland_hide_ptitle_stitle') );

			//Agent
			if(!empty($homeland_agent_hdimage)) : $homeland_title_block_agent = "page-title-block-agent"; 
			else : $homeland_title_block_agent = "page-title-block-default"; endif;

			//Taxonomy
			if(!empty($homeland_taxonomy_hdimage)) : $homeland_title_block_taxonomy = "page-title-block-taxonomy"; 
			else : $homeland_title_block_taxonomy = "page-title-block-default"; endif;

			//Forum
			if(!empty($homeland_forum_hdimage)) : $homeland_title_block_forum = "page-title-block-forum"; 
			else : $homeland_title_block_forum = "page-title-block-default"; endif;

			//Archive
			if(!empty($homeland_archive_hdimage)) : $homeland_title_block_archive = "page-title-block-archive"; 
			else : $homeland_title_block_archive = "page-title-block-default"; endif;

			//Search
			if(!empty($homeland_search_hdimage)) : $homeland_title_block_search =  "page-title-block-search"; 
			else : $homeland_title_block_search = "page-title-block-default"; endif;

			//404
			if(!empty($homeland_notfound_hdimage)) : $homeland_title_block_notfound = "page-title-block-error"; 
			else : $homeland_title_block_notfound = "page-title-block-default"; endif;

			//Title
			if(!empty($homeland_page_hd_image)) : $homeland_title_block = "page-title-block"; 
			else : $homeland_title_block = "page-title-block-default"; endif;

			//Forum Single
			if(!empty($homeland_forum_single_hdimage)) : $homeland_title_block_forum_single =  "page-title-block-forum-single"; 
			else : $homeland_title_block_forum_single = "page-title-block-default"; endif;

			//Forum Single Topic
			if(!empty($homeland_forum_single_topic_hdimage)) : $homeland_title_block_forum_single_topic = "page-title-block-topic-single"; 
			else : $homeland_title_block_forum_single_topic = "page-title-block-default"; endif;

			//Forum Topic Edit
			if(!empty($homeland_forum_topic_edit_hdimage)) : $homeland_title_block_forum_topic_edit = "page-title-block-topic-edit"; 
			else : $homeland_title_block_forum_topic_edit = "page-title-block-default"; endif;

			//Forum Search
			if(!empty($homeland_forum_search_hdimage)) : $homeland_title_block_forum_search = "page-title-block-forum-search"; 
			else : $homeland_title_block_forum_search = "page-title-block-default"; endif;

			//Forum Single User
			if(!empty($homeland_user_profile_hdimage)) : $homeland_title_block_forum_single_user = "page-title-block-user-profile"; 
			else : $homeland_title_block_forum_single_user = "page-title-block-default"; endif;
			
			if(is_page_template('template-homepage.php') || is_page_template('template-homepage2.php') || is_page_template('template-homepage3.php') || is_page_template('template-homepage4.php') || is_page_template('template-homepage-video.php') || is_page_template('template-homepage-revslider.php') || is_page_template('template-homepage-gmap.php') || is_page_template('template-homepage-gmap-large.php') || is_page_template('template-page-builder.php')) :  
			else :
				if(is_archive()) :
					if(is_author()) : 
						echo "<section class='" . $homeland_title_block_agent . " header-bg'>";
					elseif(is_tax()) : 
						echo "<section class='" . $homeland_title_block_taxonomy . " header-bg'>";
					elseif(function_exists('is_bbpress')) :
						if( bbp_is_forum_archive() ) : 
							echo "<section class='" . $homeland_title_block_forum . " header-bg'>";
						else : 
							echo "<section class='" . $homeland_title_block_archive . " header-bg'>";
						endif;
					else : 
						echo "<section class='" . $homeland_title_block_archive . " header-bg'>"; 
					endif;
				elseif(is_search()) : 
					echo "<section class='" . $homeland_title_block_search . " header-bg'>";
				elseif(is_404()) : 
					echo "<section class='" . $homeland_title_block_notfound . " header-bg'>";
				elseif(is_page_template( 'template-contact.php' )) : 
					echo "<section class='" . $homeland_title_block . " header-bg'>";
				else : 
					if(function_exists('is_bbpress')) :
						if(bbp_is_single_forum()) : 
							echo "<section class='" . $homeland_title_block_forum_single . " header-bg'>";
						elseif(bbp_is_single_topic()) : 
							echo "<section class='" . $homeland_title_block_forum_single_topic . " header-bg'>";
						elseif(bbp_is_topic_edit()) : 
							echo "<section class='" . $homeland_title_block_forum_topic_edit . " header-bg'>";
						elseif(bbp_is_search()) : 
							echo "<section class='" . $homeland_title_block_forum_search . " header-bg'>";
						elseif(bbp_is_single_user()) : 
							echo "<section class='" . $homeland_title_block_forum_single_user . " header-bg'>";
						elseif( is_bbpress() ) : 
							echo "<section class='" . $homeland_title_block_forum . " header-bg'>";
						else : 
							echo "<section class='" . $homeland_title_block . " header-bg'>";
						endif;
					else : 
						echo "<section class='" . $homeland_title_block . " header-bg'>";
					endif;
				endif; ?>
				<div class="inside"><?php if(empty($homeland_hide_ptitle_stitle)) : homeland_get_page_title(); endif; ?></div></section><?php 
			endif; 
		}
	endif;


	/*---------------------------------------------
	GOOGLE ANALYTICS CODE
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_google_analytics' ) ) :	
		function homeland_google_analytics() {
			$homeland_ga_code = get_option( 'homeland_ga_code' );

			if(!empty( $homeland_ga_code )) : 
				?><script type="text/javascript"><?php echo stripslashes( $homeland_ga_code ); ?></script><?php
			endif;
		}
	endif;
	add_action('wp_footer', 'homeland_google_analytics', 100);


	/*---------------------------------------------
	PROPERTY FILTERS
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_property_listings' ) ) :
		function homeland_property_listings( $homeland_property_list_args ) {
			$homeland_filter_default = esc_attr( get_option('homeland_filter_default') );	

	   	if(isset($_GET['filter-order'])) :
				if($_GET['filter-order'] == "ASC") :
					$homeland_property_list_args['order'] = 'ASC';
				else :
			   	$homeland_property_list_args['order'] = 'DESC';
			   endif;
			endif;

			if(isset($_GET['filter-sort'])) :
				if($homeland_filter_default == "Date") :
					if($_GET['filter-sort'] == "homeland_price") :
					   $homeland_property_list_args['meta_key'] = 'homeland_price';
					   $homeland_property_list_args['orderby'] = 'meta_value_num';
					elseif($_GET['filter-sort'] == "title") :
					   $homeland_property_list_args['orderby'] = 'title';
					else :
						$homeland_property_list_args['orderby'] = 'date';
					endif;
				elseif($homeland_filter_default == "Name") :
					if($_GET['filter-sort'] == "homeland_price") :
					   $homeland_property_list_args['meta_key'] = 'homeland_price';
					   $homeland_property_list_args['orderby'] = 'meta_value_num';
					elseif($_GET['filter-sort'] == "date") :
					   $homeland_property_list_args['orderby'] = 'date';
					else :
						$homeland_property_list_args['orderby'] = 'title';
					endif;
				elseif($homeland_filter_default == "Price") :
					if($_GET['filter-sort'] == "date") :
					   $homeland_property_list_args['orderby'] = 'date';
					elseif($_GET['filter-sort'] == "title") :
					   $homeland_property_list_args['orderby'] = 'title';
					else :
						$homeland_property_list_args['meta_key'] = 'homeland_price';
					   $homeland_property_list_args['orderby'] = 'meta_value_num';
					endif;
				endif;
			endif;

			return $homeland_property_list_args;
	   }
	endif;
   add_filter('homeland_properties_parameters', 'homeland_property_listings');


   /*---------------------------------------------
	ADD LIGHTBOX FOR GALLERY SHORTCODE
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_add_rel_attribute' ) ) :
	   function homeland_add_rel_attribute($homeland_link) {
			global $post;
			return str_replace('<a href', '<a rel="gallery" href', $homeland_link);
		}
	endif;
	add_filter('wp_get_attachment_link', 'homeland_add_rel_attribute');    


	/*---------------------------------------------
	CHANGE LABEL OF AUTHORS TO AGENT
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_metabox_agent' ) ) :
		function homeland_metabox_agent() {
			remove_meta_box('authordiv', 'homeland_properties', 'normal');
			add_meta_box('homeland_authordiv', __( 'Agent', 'codeex_theme_name' ), 'post_author_meta_box', 'homeland_properties', 'normal', 'core');
		}
	endif;
	add_action( 'admin_menu',  'homeland_metabox_agent' );


	/*---------------------------------------------
	CUSTOM AVATAR
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_custom_avatar' ) ) :
		function homeland_custom_avatar( $user ) { 
			?>
				<h3><?php _e( 'Custom Avatar', 'codeex_theme_name' ); ?></h3> 
				<table class="form-table">
					<tr>
						<th><label for="homeland_custom_avatar"><?php _e( 'Avatar', 'codeex_theme_name' ); ?></label></th>
						<td>
							<input type="text" name="homeland_custom_avatar" id="homeland_custom_avatar" value="<?php echo esc_attr( get_the_author_meta( 'homeland_custom_avatar', $user->ID ) ); ?>" class="regular-text" /><input id="upload_image_button_avatar" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br />
							<span class="description">
								<?php _e( 'This will override your default Gravatar or show up if you dont have a Gravatar', 'codeex_theme_name' ); ?><br /><strong><?php _e( 'Image should be 240x240 pixels', 'codeex_theme_name' ); ?>.</strong>
							</span>
						</td>
					</tr>
				</table>
			<?php 
		}
	endif;
	add_action( 'show_user_profile', 'homeland_custom_avatar' );
	add_action( 'edit_user_profile', 'homeland_custom_avatar' );

	if ( ! function_exists( 'homeland_save_custom_avatar' ) ) :
		function homeland_save_custom_avatar( $user_id ) {
			if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
			update_user_meta( $user_id, 'homeland_custom_avatar', $_POST['homeland_custom_avatar'] );
		}
	endif;
	add_action( 'personal_options_update', 'homeland_save_custom_avatar' );
	add_action( 'edit_user_profile_update', 'homeland_save_custom_avatar' );


	/*---------------------------------------------
	SEARCH FOR CPT ADMIN FOR PROPERTIES
	----------------------------------------------*/

	global $homeland_postmeta_alias, $homeland_is_specials_search;
	$homeland_cpt_name = 'homeland_properties';
	$homeland_postmeta_alias = 'homeland_pm';
	$homeland_is_specials_search = is_admin() && $pagenow=='edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type']==$homeland_cpt_name && isset( $_GET['s'] );

	if ( $homeland_is_specials_search ) :
		add_filter( 'posts_join',      'homeland_description_search_join' );
		add_filter( 'posts_where',     'homeland_description_search_where' );
		add_filter( 'posts_groupby',   'homeland_search_dupe_fix' );
	endif;

	if ( ! function_exists( 'homeland_description_search_join' ) ) :
		function homeland_description_search_join ( $homeland_join ){
		  	global $homeland_pagenow, $wpdb, $homeland_postmeta_alias, $homeland_is_specials_search;

		  	if ( $homeland_is_specials_search )  
		    	$homeland_join .='LEFT JOIN '.$wpdb->postmeta. ' as ' . $homeland_postmeta_alias . ' ON '. $wpdb->posts . '.ID = ' . $homeland_postmeta_alias . '.post_id ';
		  	return $homeland_join;
		}
	endif;

	if ( ! function_exists( 'homeland_description_search_where' ) ) :
		function homeland_description_search_where( $homeland_where ){
			global $homeland_pagenow, $wpdb, $homeland_postmeta_alias, $homeland_is_specials_search;

			if ( $homeland_is_specials_search )
			 	$homeland_where = preg_replace("/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/", "(".$wpdb->posts.".post_title LIKE $1) OR (".$homeland_postmeta_alias.".meta_value LIKE $1)", $homeland_where );
			return $homeland_where;
		} 
	endif;

	if ( ! function_exists( 'homeland_search_dupe_fix' ) ) :
		function homeland_search_dupe_fix( $homeland_groupby ) {
			global $homeland_pagenow, $wpdb, $homeland_is_specials_search;

			if ( $homeland_is_specials_search ) $homeland_groupby = "$wpdb->posts.ID";
			return $homeland_groupby;
		} 
	endif;


	/*---------------------------------------------
	STICKY HEADER JQUERY
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_header_sticky_js' ) ) :
		function homeland_header_sticky_js() {
			$homeland_sticky_header = esc_attr( get_option('homeland_sticky_header') );
			$homeland_theme_header = esc_attr( get_option('homeland_theme_header') );

			if(!empty($homeland_sticky_header) || $homeland_theme_header == "Header 4") : ?>
				<script type="text/javascript">
					(function($) {
						"use strict";
						$(window).scroll(function() {
					      if ($(this).scrollTop() > 160){  
					        $('header').addClass("sticky-header-animate");
					      }else{
					        $('header').removeClass("sticky-header-animate");
					      }
					   });
				   })(jQuery);
				</script><?php
			endif;
		}
	endif;
	add_action( 'wp_footer', 'homeland_header_sticky_js' );


	/*---------------------------------------------
	REMOVE REVOLUTION SLIDER META BOXES
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_remove_revolution_slider_meta_boxes' ) ) :
		function homeland_remove_revolution_slider_meta_boxes() {
			if ( is_admin() ) :
				remove_meta_box( 'mymetabox_revslider_0', 'page', 'normal' );
				remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal' );
				remove_meta_box( 'mymetabox_revslider_0', 'homeland_properties', 'normal' );
				remove_meta_box( 'mymetabox_revslider_0', 'homeland_services', 'normal' );
				remove_meta_box( 'mymetabox_revslider_0', 'homeland_testimonial', 'normal' );
				remove_meta_box( 'mymetabox_revslider_0', 'homeland_partners', 'normal' );
				remove_meta_box( 'mymetabox_revslider_0', 'homeland_portfolio', 'normal' );
			endif;
		}
	endif;
	add_action( 'do_meta_boxes', 'homeland_remove_revolution_slider_meta_boxes' );


	/*---------------------------------------------
	ADD ODD/EVEN POST CLASS
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_oddeven_post_class' ) ) :
		function homeland_oddeven_post_class ( $homeland_classes ) {
		   global $homeland_current_class;
		   $homeland_classes[] = $homeland_current_class;
		   $homeland_current_class = ($homeland_current_class == 'odd') ? 'even' : 'odd';
		   return $homeland_classes;
		}
	endif;
	add_filter ( 'post_class' , 'homeland_oddeven_post_class' );
	global $homeland_current_class;
	$homeland_current_class = 'odd';


	/*---------------------------------------------
	GET PORTFOLIO CATEGORIES
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_get_portfolio_category' ) ) :
		function homeland_get_portfolio_category() {
			global $homeland_portfolio_page_url; 

			if(is_page_template('template-portfolio.php') || is_page_template('template-portfolio-right-sidebar.php') || is_page_template('template-portfolio-left-sidebar.php')) :
				$homeland_portfolio_current_class = "current-cat";
			endif;
			?>
			<div class="cat-toogles">
				<ul class="cat-list clear">
					<li class="<?php echo $homeland_portfolio_current_class; ?>">
						<a href="<?php echo $homeland_portfolio_page_url; ?>">
							<?php esc_attr( _e( 'All', 'codeex_theme_name' ) ); ?>
						</a>
					</li>
					<?php
						$args = array( 
							'taxonomy' => 'homeland_portfolio_category', 
							'style' => 'list', 
							'title_li' => '', 
							'hierarchical' => false, 
							'order' => 'ASC', 
							'orderby' => 'title' 
						);
						wp_list_categories ( $args );
					?>	
				</ul>
			</div><?php
		}
	endif;


	/*---------------------------------------------
	ABILITY OF CONTRIBUTOR TO EDIT POST
	----------------------------------------------*/

	$obj_existing_role = get_role( 'contributor' );
	$obj_existing_role->add_cap( 'edit_published_posts' );


	/*---------------------------------------------
	PROPERTY PRICE FORMAT
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_property_price_format' ) ) :
		function homeland_property_price_format() {
			global $post;

			$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
			$homeland_property_currency = get_post_meta($post->ID, 'homeland_property_currency', true);
			$homeland_price = get_post_meta($post->ID, 'homeland_price', true);
			$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 
			$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
			$homeland_price_format = esc_attr( get_option('homeland_price_format') );
			$homeland_property_decimal = esc_attr( get_option('homeland_property_decimal') );
			$homeland_property_decimal = !empty($homeland_property_decimal) ? $homeland_property_decimal : 0;

			$homeland_property_currency_before = "";
			$homeland_property_currency_after = "";
			$homeland_price_per_result = "";

			//Currency Position
			if( $homeland_property_currency_sign == "After" ) : 
				$homeland_property_currency_after = !empty($homeland_property_currency) ? $homeland_property_currency : $homeland_currency;
			else :
				$homeland_property_currency_before = !empty($homeland_property_currency) ? $homeland_property_currency : $homeland_currency;
			endif;

			//Price Format
			if($homeland_price_format == "Dot") :
				$homeland_price_format_result = number_format ( $homeland_price, $homeland_property_decimal, ".", "." );
			elseif($homeland_price_format == "Comma") : 
				$homeland_price_format_result = number_format ( $homeland_price, $homeland_property_decimal );
			elseif($homeland_price_format == "Brazil" || $homeland_price_format == "Europe") :
				$homeland_price_format_result = number_format( $homeland_price, $homeland_property_decimal, ",", "." );
			else : 
				$homeland_price_format_result = $homeland_price;
			endif;

			//Price Per
			$homeland_price_per_result = !empty($homeland_price_per) ? "/" . $homeland_price_per : '';

			//Price Results
			echo $homeland_property_currency_before . $homeland_price_format_result . $homeland_property_currency_after . $homeland_price_per_result;
		}
	endif;
?>