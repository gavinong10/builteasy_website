<?php
	/**********************************************
	CUSTOM POST TYPE: PROPERTIES
	***********************************************/	

	if ( ! function_exists( 'homeland_properties_post_type' ) ) :
		function homeland_properties_post_type() { 

			register_post_type( 'homeland_properties',
				array(
					'labels' => array(
						'name' => __( 'Properties', 'codeex_theme_name' ),
						'singular_name' => __( 'Properties', 'codeex_theme_name' ),
						'add_new' => __( 'Add New', 'codeex_theme_name' ),
						'add_new_item' => __( 'Add New Property', 'codeex_theme_name' ),
						'edit_item' => __( 'Edit Property', 'codeex_theme_name' ),
						'search_items' => __( 'Search Property', 'codeex_theme_name' ),
						'not_found' => __( 'No property found.', 'codeex_theme_name' ),
						'not_found_in_trash' => __( 'No property found in Trash.', 'codeex_theme_name' ),
					),
					'public' => true,
					'has_archive' => true,	
					'publicly_queryable' => true,
					'show_ui' => true, 
			    	'show_in_menu' => true, 
			   	'query_var' => true,	
				   'rewrite' => array( 'slug' => __( 'property-item', 'codeex_theme_name' ), 'with_front' => TRUE ),
				   'supports' => array('title', 'editor', 'author', 'comments', 'thumbnail', 'page-attributes', 'custom-fields', 'excerpt'),
					'menu_icon' => 'dashicons-screenoptions',
				)
			);
		}
	endif;
	add_action( 'init', 'homeland_properties_post_type' );

	
	/*----------------------------
	MetaBoxes
	----------------------------*/

	if ( ! function_exists( 'homeland_properties_meta' ) ) :
		function homeland_properties_meta() {
			global $post;

			$homeland_advance_search = sanitize_text_field( get_post_meta($post->ID, 'homeland_advance_search', TRUE ) );
			$homeland_featured = sanitize_text_field( get_post_meta($post->ID, 'homeland_featured', TRUE) );
			$homeland_hdimage = sanitize_text_field( get_post_meta($post->ID, 'homeland_hdimage', TRUE) );
			$homeland_bgimage = sanitize_text_field( get_post_meta($post->ID, 'homeland_bgimage', TRUE) );
			$homeland_thumbnails = sanitize_text_field( get_post_meta($post->ID, 'homeland_thumbnails', TRUE ) );
			$homeland_property_currency = sanitize_text_field( get_post_meta($post->ID, 'homeland_property_currency', TRUE ) );
			$homeland_price = sanitize_text_field( get_post_meta($post->ID, 'homeland_price', TRUE ) );
			$homeland_price_per = sanitize_text_field( get_post_meta($post->ID, 'homeland_price_per', TRUE ) );
			$homeland_property_id = sanitize_text_field( get_post_meta($post->ID, 'homeland_property_id', TRUE ) );
			$homeland_zip = sanitize_text_field( get_post_meta($post->ID, 'homeland_zip', TRUE ) );
			$homeland_address = sanitize_text_field( get_post_meta($post->ID, 'homeland_address', TRUE ) );
			$homeland_area = sanitize_text_field( get_post_meta($post->ID, 'homeland_area', TRUE ) );
			$homeland_area_unit = sanitize_text_field( get_post_meta($post->ID, 'homeland_area_unit', TRUE ) );
			$homeland_floor_area = sanitize_text_field( get_post_meta($post->ID, 'homeland_floor_area', TRUE ) );
			$homeland_floor_area_unit = sanitize_text_field( get_post_meta($post->ID, 'homeland_floor_area_unit', TRUE ) );
			$homeland_garage = sanitize_text_field( get_post_meta($post->ID, 'homeland_garage', TRUE ) );
			$homeland_room = sanitize_text_field( get_post_meta($post->ID, 'homeland_room', TRUE ) );
			$homeland_bedroom = sanitize_text_field( get_post_meta($post->ID, 'homeland_bedroom', TRUE ) );
			$homeland_bathroom = sanitize_text_field( get_post_meta($post->ID, 'homeland_bathroom', TRUE ) );
			$homeland_year_built = sanitize_text_field( get_post_meta($post->ID, 'homeland_year_built', TRUE ) );
			$homeland_stories = sanitize_text_field( get_post_meta($post->ID, 'homeland_stories', TRUE ) );
			$homeland_basement = sanitize_text_field( get_post_meta($post->ID, 'homeland_basement', TRUE ) );
			$homeland_structure_type = sanitize_text_field( get_post_meta($post->ID, 'homeland_structure_type', TRUE ) );
			$homeland_roofing = sanitize_text_field( get_post_meta($post->ID, 'homeland_roofing', TRUE ) );
			$homeland_rev_slider = sanitize_text_field( get_post_meta($post->ID, 'homeland_rev_slider', TRUE ) );

			$homeland_property_hide_map = sanitize_text_field( get_post_meta($post->ID, 'homeland_property_hide_map', TRUE) );
			$homeland_property_lat = sanitize_text_field( get_post_meta($post->ID, 'homeland_property_lat', TRUE) );
			$homeland_property_lng = sanitize_text_field( get_post_meta($post->ID, 'homeland_property_lng', TRUE) );
			$homeland_property_map_zoom = sanitize_text_field( get_post_meta($post->ID, 'homeland_property_map_zoom', TRUE) );
			
			if(empty($homeland_property_map_zoom)) :
				$homeland_property_map_zoom = 1;
			endif;

			?>

				<script type="text/javascript">
					function deleteSpecialChar(homeland_price) {
			        	if (homeland_price.value != '' && homeland_price.value.match(/^[\w ]+$/) == null) {
			            homeland_price.value = homeland_price.value.replace(/[\W]/g, '');
			        	}
			    	}

			    	(function($) {
    					"use strict";

				    	$(function() {
							$( "#slider-range-max" ).slider({
						      range: "max",
						      min: 1,
						      max: 20,
						      value: <?php echo $homeland_property_map_zoom; ?>,
						      slide: function( event, ui ) {
						        $( "#homeland_property_map_zoom" ).val( ui.value );
						      }
						   });
						   $( "#homeland_property_map_zoom" ).val( $( "#slider-range-max" ).slider( "value" ) );
						});

					})(jQuery);
				</script>

				<div class="mabuc-form-wrap">	

					<!-- Tabs -->
					<ul class="mabuc-tabs">
						<li class="mabuc-tab-link current" data-tab="tab-1">
							<i class="fa fa-home"></i><?php _e( 'Main Information', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-2">
							<i class="fa fa-image"></i><?php _e( 'Images', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-3">
							<i class="fa fa-sliders"></i><?php _e( 'Slider', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-4">
							<i class="fa fa-map-o"></i><?php _e( 'Google Map', 'codeex_theme_name' ); ?>
						</li>
					</ul>

					<!-- Main Information -->
					<div id="tab-1" class="mabuc-tab-content current">
						<ul>
							<li>
								<label for="homeland_advance_search">
									<?php _e( 'Hide Search', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_advance_search" type="checkbox" id="homeland_advance_search" <?php if( $homeland_advance_search == true ) { ?>checked="checked"<?php } ?> /><br>
								<span class="desc"><?php _e( 'Tick the box to hide advance search in this post', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_featured"><?php _e( 'Featured', 'codeex_theme_name' ); ?></label>
								<input name="homeland_featured" type="checkbox" id="homeland_featured" <?php if( $homeland_featured == true ) { ?>checked="checked"<?php } ?> />
								<span class="desc"><?php _e( 'Checking this box will display it in featured properties sections across the theme and also it will be included in your homepage slider.', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_property_id">
									<?php _e( 'Property ID', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_property_id" type="text" id="homeland_property_id" value="<?php echo esc_attr( $homeland_property_id ); ?>" style="width:100px;" /> <span class="desc"><?php _e( 'Provide your property id here', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_price"><?php _e( 'Price', 'codeex_theme_name' ); ?></label>
								<input name="homeland_property_currency" type="text" id="homeland_property_currency" value="<?php echo esc_attr( $homeland_property_currency ); ?>" style="width:40px;" placeholder="$" />
								<input name="homeland_price" type="text" id="homeland_price" value="<?php echo esc_attr( $homeland_price ); ?>" style="width:100px;" onkeyup="javascript:deleteSpecialChar(this)" />
								<input name="homeland_price_per" type="text" id="homeland_price_per" value="<?php echo esc_attr( $homeland_price_per ); ?>" style="width:100px;" placeholder="<?php _e( 'Per', 'codeex_theme_name' ); ?>" />
								&nbsp;<span class="desc"><?php _e( 'Provide your property price here. Please add number digits only (Example: 100000)', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_area"><?php _e( 'Lot Area', 'codeex_theme_name' ); ?></label>
								<input name="homeland_area" type="text" id="homeland_area" value="<?php echo esc_attr( $homeland_area ); ?>" style="width:100px;" />
								<input name="homeland_area_unit" type="text" id="homeland_area_unit" value="<?php echo esc_attr( $homeland_area_unit ); ?>" style="width:100px;" placeholder="<?php _e( 'Unit', 'codeex_theme_name' ); ?>" /> <span class="desc"><?php _e( 'Provide property lot area and unit here', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_floor_area">
									<?php _e( 'Floor Area', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_floor_area" type="text" id="homeland_floor_area" value="<?php echo esc_attr( $homeland_floor_area ); ?>" style="width:100px;" />
								<input name="homeland_floor_area_unit" type="text" id="homeland_floor_area_unit" value="<?php echo esc_attr( $homeland_floor_area_unit ); ?>" style="width:100px;" placeholder="<?php _e( 'Unit', 'codeex_theme_name' ); ?>" /> <span class="desc"><?php _e( 'Provide property floor area and unit here', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_year_built">
									<?php _e( 'Year Built', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_year_built" type="text" id="homeland_year_built" value="<?php echo esc_attr( $homeland_year_built ); ?>" maxlength="4" style="width:100px;" /> <span class="desc"><?php _e( 'Provide property year built. Please add number digits only (Example: 2015)', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_garage"><?php _e( 'Garage', 'codeex_theme_name' ); ?></label>
								<input name="homeland_garage" type="text" id="homeland_garage" value="<?php echo esc_attr( $homeland_garage ); ?>" maxlength="2" style="width:100px;" /> <span class="desc"><?php _e( 'Provide property garage. Please add number digits only', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_room"><?php _e( 'Rooms', 'codeex_theme_name' ); ?></label>
								<input name="homeland_room" type="text" id="homeland_room" value="<?php echo esc_attr( $homeland_room ); ?>" style="width:100px;" /> <span class="desc"><?php _e( 'Provide property room number. Please add number digits only', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_bedroom"><?php _e( 'Bedroom', 'codeex_theme_name' ); ?></label>
								<input name="homeland_bedroom" type="text" id="homeland_bedroom" value="<?php echo esc_attr( $homeland_bedroom ); ?>" style="width:100px;" /> <span class="desc"><?php _e( 'Provide property bedroom. Please add number digits only', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_bathroom"><?php _e( 'Bathroom', 'codeex_theme_name' ); ?></label>
								<input name="homeland_bathroom" type="text" id="homeland_bathroom" value="<?php echo esc_attr( $homeland_bathroom ); ?>" style="width:100px;" /> <span class="desc"><?php _e( 'Provide property bathroom. Please add number digits only', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_stories"><?php _e( 'Stories', 'codeex_theme_name' ); ?></label>
								<input name="homeland_stories" type="text" id="homeland_stories" value="<?php echo esc_attr( $homeland_stories ); ?>" style="width:100px;" /> <span class="desc"><?php _e( 'Provide property number of stories. Please add number digits only', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_basement"><?php _e( 'Basement', 'codeex_theme_name' ); ?></label>
								<input name="homeland_basement" type="text" id="homeland_basement" value="<?php echo esc_attr( $homeland_basement ); ?>" style="width:100px;" /> <span class="desc"><?php _e( 'Provide property basement. Please add number digits only', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_zip"><?php _e( 'Zip Code', 'codeex_theme_name' ); ?></label>
								<input name="homeland_zip" type="text" id="homeland_zip" value="<?php echo esc_attr( $homeland_zip ); ?>" style="width:100px;" /> <span class="desc"><?php _e( 'Provide property zip code', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_structure_type">
									<?php _e( 'Structure Type', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_structure_type" type="text" id="homeland_structure_type" value="<?php echo esc_attr( $homeland_structure_type ); ?>" /> <span class="desc"><?php _e( 'Provide property structure type.', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_roofing"><?php _e( 'Roofing', 'codeex_theme_name' ); ?></label>
								<input name="homeland_roofing" type="text" id="homeland_roofing" value="<?php echo esc_attr( $homeland_roofing ); ?>" /> <span class="desc"><?php _e( 'Provide property roofing.', 'codeex_theme_name' ); ?></span>
							</li>	
							<li>
								<label for="homeland_address"><?php _e( 'Address', 'codeex_theme_name' ); ?></label>
								<input name="homeland_address" type="text" id="homeland_address" value="<?php echo esc_attr( $homeland_address ); ?>" /><br>
								<span class="desc"><?php _e( 'Provide property address here (city/state/province/country)', 'codeex_theme_name' ); ?></span>
							</li>
						</ul>
					</div>		

					<!-- Images -->
					<div id="tab-2" class="mabuc-tab-content">
						<ul>
							<li>
								<label for="homeland_hdimage">
									<?php _e( 'Header Image', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_hdimage" type="text" id="homeland_hdimage" value="<?php echo esc_attr( $homeland_hdimage ); ?>" /> <input id="upload_image_button_homeland_hdimage" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc">
									<?php 
										_e( 'Please upload header image. Otherwise default header image from theme options will be displayed', 'codeex_theme_name' ); 
									?>
								</span>
							</li>
							<li>
								<label for="homeland_bgimage">
									<?php _e( 'Background Image', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_bgimage" type="text" id="homeland_bgimage" value="<?php echo esc_attr( $homeland_bgimage ); ?>" /> <input id="upload_image_button_homeland_bgimage" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc">
									<?php 
										_e( 'Please upload background image. Otherwise default background image from theme options will be displayed', 'codeex_theme_name' ); 
									?>
								</span>
							</li>
						</ul>
					</div>

					<!-- Slider -->
					<div id="tab-3" class="mabuc-tab-content">
						<ul>
							<li>
								<label for="homeland_thumbnails">
									<?php _e( 'Slider Thumbnails', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_thumbnails" type="checkbox" id="homeland_thumbnails" <?php if( $homeland_thumbnails == true ) { ?>checked="checked"<?php } ?> />
								<span class="desc"><?php _e( 'Checking this box will hide all thumbnails in property slider', 'codeex_theme_name' ); ?></span>
							</li>
							<?php
								if(shortcode_exists("rev_slider")) : ?>
								  	<li>
								  		<label for="homeland_rev_slider">
								  			<?php _e( 'Revolution Slider', 'codeex_theme_name' ); ?>
								  		</label>
							   		<select name="homeland_rev_slider" id="homeland_rev_slider">
									   	<?php
												$slider = new RevSlider();
												$revolution_sliders = $slider->getArrSliders();
												 
												echo "<option value=''>Select</option>";
												foreach ( $revolution_sliders as $revolution_slider ) {
										       	$checked="";
											       $alias = $revolution_slider->getAlias();
											       $title = $revolution_slider->getTitle();
											       if($alias==$homeland_rev_slider) $checked="selected";
											       echo "<option value='".$alias."' $checked>".$title."</option>";
												}
											?>
										</select><br>
										<span class="desc"><?php _e( 'Select your slider if you want to use revolution slider in property single page', 'codeex_theme_name' ); ?></span>
										</td>
									</tr><?php
								endif;
							?>	
						</ul>
					</div>

					<!-- Google Map -->
					<div id="tab-4" class="mabuc-tab-content">
						<span class="content-desc">
							<?php 
								$url = "http://latlong.net";
								printf( __( 'Get google map latitude and longitude value <a href="%s" target="_blank">here</a>', 'codeex_theme_name' ), $url );
							?>
						</span>
						<ul>
							<li>
								<label for="homeland_property_hide_map">
									<?php _e( 'Hide', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_property_hide_map" type="checkbox" id="homeland_property_hide_map" <?php if( $homeland_property_hide_map == true ) { ?>checked="checked"<?php } ?> /><br>
								<span class="desc"><?php _e( 'Check the box to hide map on properties', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_property_lat">
									<?php _e( 'Latitude', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_property_lat" type="text" id="homeland_property_lat" value="<?php echo esc_attr( $homeland_property_lat ); ?>" /><br>
								<span class="desc"><?php _e( 'Add your property latitude for google map', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_property_lng">
									<?php _e( 'Longitude', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_property_lng" type="text" id="homeland_property_lng" value="<?php echo esc_attr( $homeland_property_lng ); ?>" /><br>
								<span class="desc"><?php _e( 'Add your property longitude for google map', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<label for="homeland_property_map_zoom">
									<?php _e( 'Map Zoom', 'codeex_theme_name' ); ?>
								</label>
								<div id="slider-range-max" class="slider-range"></div>
								<input type="text" id="homeland_property_map_zoom" name="homeland_property_map_zoom" readonly class="slide-amount" value="<?php echo $homeland_property_map_zoom; ?>">
								<span class="desc"><?php _e( 'Add your map zoom value (1-20) only', 'codeex_theme_name' ); ?></span>
							</li>
						</ul>
					</div>

				</div>		
			<?php	
		}
	endif;

	
	/*----------------------------
	Taxonomies
	----------------------------*/

	if ( ! function_exists( 'homeland_properties_taxonomies' ) ) :
		function homeland_properties_taxonomies() {

			//Property Type
			$labels = array(
				'name'              => __( 'Property Type', 'codeex_theme_name' ),
				'singular_name'     => __( 'Property Type', 'codeex_theme_name' ),
				'search_items'      => __( 'Search Property Type', 'codeex_theme_name' ),
				'all_items'         => __( 'All Property Type', 'codeex_theme_name' ),
				'edit_item'         => __( 'Edit Property Type', 'codeex_theme_name' ),
				'update_item'       => __( 'Update Property Type', 'codeex_theme_name' ),
				'add_new_item'      => __( 'Add New Property Type', 'codeex_theme_name' ),
				'new_item_name'     => __( 'New Property Type', 'codeex_theme_name' ),
				'menu_name'         => __( 'Type', 'codeex_theme_name' ),
				'parent_item'       => __( 'Parent Property Type', 'codeex_theme_name' )
			);
			$args = array( 
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'property-type' ),
			);
			register_taxonomy( 'homeland_property_type', 'homeland_properties', $args );


			//Property Status
			$labels = array(
				'name'              => __( 'Property Status', 'codeex_theme_name' ),
				'singular_name'     => __( 'Property Status', 'codeex_theme_name' ),
				'search_items'      => __( 'Search Property Status', 'codeex_theme_name' ),
				'all_items'         => __( 'All Property Status', 'codeex_theme_name' ),
				'edit_item'         => __( 'Edit Property Status', 'codeex_theme_name' ),
				'update_item'       => __( 'Update Property Status', 'codeex_theme_name' ),
				'add_new_item'      => __( 'Add New Property Status', 'codeex_theme_name' ),
				'new_item_name'     => __( 'New Property Status', 'codeex_theme_name' ),
				'menu_name'         => __( 'Status', 'codeex_theme_name' ),
				'parent_item'       => __( 'Parent Property Status', 'codeex_theme_name' )
			);
			$args = array( 
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'property-status' ),
			);
			register_taxonomy( 'homeland_property_status', 'homeland_properties', $args );


			//Property Location
			$labels = array(
				'name'              => __( 'Property Location', 'codeex_theme_name' ),
				'singular_name'     => __( 'Property Location', 'codeex_theme_name' ),
				'search_items'      => __( 'Search Property Location', 'codeex_theme_name' ),
				'all_items'         => __( 'All Property Location', 'codeex_theme_name' ),
				'edit_item'         => __( 'Edit Property Location', 'codeex_theme_name' ),
				'update_item'       => __( 'Update Property Location', 'codeex_theme_name' ),
				'add_new_item'      => __( 'Add New Property Location', 'codeex_theme_name' ),
				'new_item_name'     => __( 'New Property Location', 'codeex_theme_name' ),
				'menu_name'         => __( 'Location', 'codeex_theme_name' ),
				'parent_item'       => __( 'Parent Property Location', 'codeex_theme_name' )
			);
			$args = array( 
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'property-location' ),
			);
			register_taxonomy( 'homeland_property_location', 'homeland_properties', $args );


			//Property Amenities
			$labels = array(
				'name'              => __( 'Property Amenities', 'codeex_theme_name' ),
				'singular_name'     => __( 'Property Amenities', 'codeex_theme_name' ),
				'search_items'      => __( 'Search Property Amenities', 'codeex_theme_name' ),
				'all_items'         => __( 'All Property Amenities', 'codeex_theme_name' ),
				'edit_item'         => __( 'Edit Property Amenities', 'codeex_theme_name' ),
				'update_item'       => __( 'Update Property Amenities', 'codeex_theme_name' ),
				'add_new_item'      => __( 'Add New Property Amenities', 'codeex_theme_name' ),
				'new_item_name'     => __( 'New Property Amenities', 'codeex_theme_name' ),
				'menu_name'         => __( 'Amenities', 'codeex_theme_name' ),
				'parent_item'       => __( 'Parent Property Amenities', 'codeex_theme_name' )
			);
			$args = array( 
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'property-amenities' ),
			);
			register_taxonomy( 'homeland_property_amenities', 'homeland_properties', $args );
		}
	endif;
	add_action( 'init', 'homeland_properties_taxonomies', 1 );


	/*----------------------------
	Custom Columns
	----------------------------*/

	if ( ! function_exists( 'homeland_edit_properties_columns' ) ) :
		function homeland_edit_properties_columns( $columns ) {
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => __( 'Name', 'codeex_theme_name' ),						
				'address' => __( 'Address', 'codeex_theme_name' ),
				'property_id' => __( 'Property ID', 'codeex_theme_name' ),						
				'type' => __( 'Type', 'codeex_theme_name' ),
				'status' => __( 'Status', 'codeex_theme_name' ),
				'thumbnail' => __( 'Thumbnail', 'codeex_theme_name' ),
				'agent' => __( 'Agent', 'codeex_theme_name' ),
				'price' => __( 'Price', 'codeex_theme_name' ),
				'date' => __( 'Date', 'codeex_theme_name' )
			);
			return $columns;
		}
	endif;
	add_filter( 'manage_edit-homeland_properties_columns', 'homeland_edit_properties_columns' );


	/*----------------------------
	Sortable Columns
	----------------------------*/

	if ( ! function_exists( 'homeland_properties_sortable_columns' ) ) :
		function homeland_properties_sortable_columns( $columns ) {
			$columns['price'] = 'homeland_price';
			$columns['address'] = 'homeland_address';
			return $columns;
		}
	endif;
	add_filter( 'manage_edit-homeland_properties_sortable_columns', 'homeland_properties_sortable_columns' );

	if ( ! function_exists( 'homeland_properties_load' ) ) :
		function homeland_properties_load() {
			add_filter( 'request', 'homeland_sort_properties' );
		}
	endif;
	add_action( 'load-edit.php', 'homeland_properties_load' );


	if ( ! function_exists( 'homeland_sort_properties' ) ) :
		function homeland_sort_properties( $vars ) {
			if ( isset( $vars['post_type'] ) && 'homeland_properties' == $vars['post_type'] ) :
				if ( isset( $vars['orderby'] ) && 'homeland_price' == $vars['orderby'] ) :
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => 'homeland_price',
							'orderby' => 'meta_value_num'
						)
					);
				elseif ( isset( $vars['orderby'] ) && 'homeland_address' == $vars['orderby'] ) :
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => 'homeland_address',
							'orderby' => 'meta_value'
						)
					);
				endif;
			endif;
			return $vars;
		}
	endif;


	/*----------------------------
	Custom Columns List
	----------------------------*/

	if ( ! function_exists( 'homeland_manage_properties_columns' ) ) :
		function homeland_manage_properties_columns( $column ) {
			global $post;

			switch($column) {
				case 'address' :
					$homeland_address = sanitize_text_field( get_post_meta( $post->ID, 'homeland_address', true ) );
	   			echo $homeland_address;
	   		break;

	   		case 'property_id' :
	   			$homeland_property_id = sanitize_text_field( get_post_meta( $post->ID, 'homeland_property_id', true ) );
	   			echo $homeland_property_id;
	   		break;

				case 'type' :
					$terms = get_the_terms( $post->ID, 'homeland_property_type' );
					if ( !empty( $terms ) ) {
						$out = array();
						foreach ( $terms as $term ) {
							$out[] = sprintf( '<a href="%s">%s</a>',
								esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'homeland_property_type' => $term->slug ), 'edit.php' ) ),
								esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'homeland_property_type', 'display' ) )
							);
						}
						echo join( ', ', $out );
					}		
					else { __( 'No Type', 'codeex_theme_name' ); }
				break;

				case 'agent' : 
	   			the_author_meta( 'user_firstname' );
	   		break;

				case 'thumbnail' : 
	   			echo the_post_thumbnail( array(80,80) );
	   		break;

	   		case 'price' :
	   			$homeland_price = sanitize_text_field( get_post_meta( $post->ID, 'homeland_price', true ) );
	   			$homeland_price_per = sanitize_text_field( get_post_meta( $post->ID, 'homeland_price_per', true ) );
	   			$homeland_currency = get_option('homeland_property_currency');

	   			if(!empty($homeland_price)) :
	   				echo $homeland_currency;
	   				echo number_format ($homeland_price); 
	      			if(!empty($homeland_price_per)) :
	      				echo "/" . $homeland_price_per;
	      			endif;
	   			endif;
	   		break;

	   		case 'status' :
	   			$terms = get_the_terms( $post->ID, 'homeland_property_status' );
					if ( !empty( $terms ) ) {
						$out = array();
						foreach ( $terms as $term ) {
							$out[] = sprintf( '<a href="%s">%s</a>',
								esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'homeland_property_status' => $term->slug ), 'edit.php' ) ),
								esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'homeland_property_status', 'display' ) )
							);
						}
						echo join( ', ', $out );
					}				
					else { __( 'No Status', 'codeex_theme_name' ); }
	   		break;
				
				default :
				break;
			}
		}
	endif;
	add_action( 'manage_homeland_properties_posts_custom_column', 'homeland_manage_properties_columns', 10, 2 );


	/*----------------------------
	Save and Update
	----------------------------*/
	
	if ( ! function_exists( 'homeland_custom_posts_properties' ) ) :
		function homeland_custom_posts_properties(){
			add_meta_box(
				"homeland_properties_meta", 
				__( 'Property Options', 'codeex_theme_name' ), 
				"homeland_properties_meta", 
				"homeland_properties", 
				"normal", 
				"low"
			);
		}	
	endif;
	add_action( 'add_meta_boxes', 'homeland_custom_posts_properties' );
	

	if ( ! function_exists( 'homeland_custom_posts_save_properties' ) ) :
		function homeland_custom_posts_save_properties( $post_id ){
			if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX)) return;
			if ( 'page' == isset($_POST['post_type']) ) { if ( !current_user_can( 'edit_page', $post_id ) ) return;
			} else { if ( !current_user_can( 'edit_post', $post_id ) ) return; }

			$homeland_fields = array(  'homeland_advance_search', 'homeland_featured', 'homeland_property_id', 'homeland_hdimage', 'homeland_bgimage', 'homeland_thumbnails', 'homeland_property_currency', 'homeland_price', 'homeland_price_per', 'homeland_zip', 'homeland_address', 'homeland_area', 'homeland_area_unit', 'homeland_floor_area', 'homeland_floor_area_unit', 'homeland_garage', 'homeland_year_built', 'homeland_room', 'homeland_bedroom', 'homeland_bathroom', 'homeland_property_hide_map', 'homeland_property_lat', 'homeland_property_lng', 'homeland_property_map_zoom', 'homeland_rev_slider', 'homeland_stories', 'homeland_basement', 'homeland_structure_type', 'homeland_roofing' );

			foreach ($homeland_fields as $homeland_value) {
	         if( isset($homeland_value) ) :

	            $homeland_new = false;
	            $homeland_old = get_post_meta( $post_id, $homeland_value, true );

	            if ( isset( $_POST[$homeland_value] ) ) :
	               $homeland_new = $_POST[$homeland_value];
	           	endif;

	            if ( isset( $homeland_new ) && '' == $homeland_new && $homeland_old ) :
	               delete_post_meta( $post_id, $homeland_value, $homeland_old );
	            elseif ( false === $homeland_new || !isset( $homeland_new ) ) :
	            	delete_post_meta( $post_id, $homeland_value, $homeland_old );
	            elseif ( isset( $homeland_new ) && $homeland_new != $homeland_old ) :
	            	update_post_meta( $post_id, $homeland_value, $homeland_new );
	           	elseif ( ! isset( $homeland_old ) && isset( $homeland_new ) ) :
	               add_post_meta( $post_id, $homeland_value, $homeland_new );
	            endif;

	         endif;
	      }
		}	
	endif;
	add_action('save_post', 'homeland_custom_posts_save_properties');
?>