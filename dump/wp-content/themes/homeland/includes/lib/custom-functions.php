<?php
	/*
		Table of Contents

		Theme Logo
		Theme Menu
		FlexSlider
		FlexSlider Small
		Advance Search
		Advance Search Div
		Advance Search Form
		Homepage Video
		Services List
		Property List
		Blog List
		Welcome Text
		Agent List
		Featured Properties List
		Testimonials
		Social Share Icons
		Header Social Icons
		Header Information
		Partners List
		Portfolio List
	*/


	/*---------------------------------------------
	Theme Logo
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_theme_logo' ) ) :
		function homeland_theme_logo() {
			$homeland_logo = esc_attr( get_option('homeland_logo') ); 
			$homeland_blog_name = esc_attr( get_bloginfo('name') ); 
			$homeland_logo_path = get_template_directory_uri() . "/img/logo.png";

			$homeland_logo_image = empty( $homeland_logo ) ? $homeland_logo_path : $homeland_logo;
			?>

			<!--LOGO-->		
			<aside class="logo clear">
				<h1>
					<a href="<?php echo esc_url( home_url() ); ?>">
						<img src="<?php echo $homeland_logo_image; ?>" alt="<?php echo $homeland_blog_name; ?>" title="<?php echo $homeland_blog_name; ?>" />
					</a>
				</h1>
			</aside><?php
		}
	endif;


	/*---------------------------------------------
	Theme Menu
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_theme_menu' ) ) :
		function homeland_theme_menu() {
			?>
				<!--MENU-->
				<nav class="clear">
					<?php
						wp_nav_menu( array( 
							'theme_location' => 'primary-menu', 
							'fallback_cb' => 'homeland_menu_fallback', 
							'container_class' => 'theme-menu', 
							'container_id' => 'dropdown', 
							'menu_id' => 'main-menu', 
							'menu_class' => 'sf-menu' 
						) );
					?>
				</nav>	
			<?php
		}
	endif;
	

	/*---------------------------------------------
	FlexSlider
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_slider' ) ) :
		function homeland_slider() {
			global $post;

			$homeland_slider_order = esc_attr( get_option('homeland_slider_order') );
			$homeland_slider_orderby = esc_attr( get_option('homeland_slider_orderby') );
			$homeland_slider_limit = esc_attr( get_option('homeland_slider_limit') );
			$homeland_slider_button = esc_attr( get_option('homeland_slider_button') );
			$homeland_hide_properties_details = esc_attr( get_option('homeland_hide_properties_details') );
			$homeland_slider_display_list = esc_attr( get_option('homeland_slider_display_list') );
			$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
			$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') );
			$homeland_theme_header = esc_attr( get_option('homeland_theme_header') );
			$homeland_slider_button_label = !empty( $homeland_slider_button ) ? $homeland_slider_button : __( 'More Details', 'codeex_theme_name' );

			if($homeland_slider_display_list == 'Properties') :
				$args = array( 
					'post_type' => 'homeland_properties', 
					'orderby' => $homeland_slider_orderby, 
					'order' => $homeland_slider_order, 
					'posts_per_page' => $homeland_slider_limit
				);
			elseif($homeland_slider_display_list == 'Blog') :
				$args = array( 
					'post_type' => 'post', 
					'orderby' => $homeland_slider_orderby, 
					'order' => $homeland_slider_order, 
					'posts_per_page' => $homeland_slider_limit
				);
			elseif($homeland_slider_display_list == 'Portfolio') :
				$args = array( 
					'post_type' => 'homeland_portfolio', 
					'orderby' => $homeland_slider_orderby, 
					'order' => $homeland_slider_order, 
					'posts_per_page' => $homeland_slider_limit
				);
			else :
				$args = array( 
					'post_type' => 'homeland_properties', 
					'orderby' => $homeland_slider_orderby, 
					'order' => $homeland_slider_order, 
					'posts_per_page' => $homeland_slider_limit, 
					'meta_query' => array( array( 
						'key' => 'homeland_featured', 
						'value' => 'on', 
						'compare' => '==' 
					)) 
				);	
			endif;	

			$wp_query = new WP_Query( $args );

			if ($wp_query->have_posts()) : ?>
				<!--SLIDER-->
				<section class="slider-block">
					<div class="home-flexslider flex-loading">
						<ul class="slides"><?php
							while ($wp_query->have_posts()) : 
								$wp_query->the_post(); 
								$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
								$homeland_price = esc_attr( get_post_meta($post->ID, 'homeland_price', true ) );
								$homeland_price_format = esc_attr( get_option('homeland_price_format') ); 

								if($homeland_theme_header == "Header 4") : $homeland_slider_image = "full";
								else : $homeland_slider_image = "homeland_slider"; 
								endif;

								?>
								<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class() ); ?>>
									<div class="slide-image">
										<?php 
											if ( has_post_thumbnail() ) : the_post_thumbnail($homeland_slider_image); 
											else :
												echo '<img src="'. get_template_directory_uri() .'/img/no-property-image-slider.png" title="" alt="" />';
											endif; 
										?>
									</div>
									<?php
										if(empty( $homeland_hide_properties_details )) : ?>
											<div class="inside">
												<div class="slider-actions">
													<div class="portfolio-slide-desc">
														<?php 
															the_title( '<h2>', '</h2>' ); 
															the_excerpt(); 
														?>
													</div>	
													<div class="pactions clear">
														<?php 
															if(!empty($homeland_price)) : ?>
																<label>
																	<i class="fa fa-tag"></i>
																	<span><?php homeland_property_price_format(); ?></span>
																</label><?php
															endif;
														?>
														<a href="<?php the_permalink(); ?>">
															<span><?php echo $homeland_slider_button_label; ?></span><i class="fa fa-plus-circle"></i>
														</a>
													</div>
												</div>
											</div><?php
										endif;
									?>
								</li><?php
							endwhile; ?>
						</ul>	
					</div>	
				</section><?php
			endif;	
		}
	endif;


	/*---------------------------------------------
	FlexSlider Small
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_slider_thumb' ) ) :
		function homeland_slider_thumb() {
			global $post;

			$homeland_slider_order = esc_attr( get_option('homeland_slider_order') );
			$homeland_slider_orderby = esc_attr( get_option('homeland_slider_orderby') );
			$homeland_slider_limit = esc_attr( get_option('homeland_slider_limit') );
			$homeland_slider_button = esc_attr( get_option('homeland_slider_button') );
			$homeland_hide_properties_details = esc_attr( get_option('homeland_hide_properties_details') );
			$homeland_slider_display_list = esc_attr( get_option('homeland_slider_display_list') );
			$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
			$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') );
			$homeland_slider_button_label = !empty( $homeland_slider_button ) ? $homeland_slider_button : __( 'More Details', 'codeex_theme_name' );

			if($homeland_slider_display_list == 'Properties') :
				$args = array( 
					'post_type' => 'homeland_properties', 
					'orderby' => $homeland_slider_orderby, 
					'order' => $homeland_slider_order, 
					'posts_per_page' => $homeland_slider_limit
				);
			elseif($homeland_slider_display_list == 'Blog') :
				$args = array( 
					'post_type' => 'post', 
					'orderby' => $homeland_slider_orderby, 
					'order' => $homeland_slider_order, 
					'posts_per_page' => $homeland_slider_limit
				);
			elseif($homeland_slider_display_list == 'Portfolio') :
				$args = array( 
					'post_type' => 'homeland_portfolio', 
					'orderby' => $homeland_slider_orderby, 
					'order' => $homeland_slider_order, 
					'posts_per_page' => $homeland_slider_limit
				);
			else :
				$args = array( 
					'post_type' => 'homeland_properties', 
					'orderby' => $homeland_slider_orderby, 
					'order' => $homeland_slider_order, 
					'posts_per_page' => $homeland_slider_limit, 
					'meta_query' => array( array( 
						'key' => 'homeland_featured', 
						'value' => 'on', 
						'compare' => '==' 
					)) 
				);	
			endif;	

			$wp_query = new WP_Query( $args );

			if ($wp_query->have_posts()) : ?>
				<!--SLIDER-->
				<section class="slider-block-thumb">
					<div class="home-flexslider flex-loading">
						<ul class="slides"><?php
							while ($wp_query->have_posts()) : 
								$wp_query->the_post(); 
								$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
								$homeland_price = esc_attr( get_post_meta($post->ID, 'homeland_price', true ) );
								$homeland_price_format = esc_attr( get_option('homeland_price_format') );
								$homeland_thumb_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'homeland_property_thumb' ); 
								?>
								<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class() ); ?> data-thumb="<?php echo $homeland_thumb_image_url[0]; ?>">
									<div class="slide-image">
										<?php 
											if ( has_post_thumbnail() ) : the_post_thumbnail( 'homeland_portfolio_large' ); 
											else :
												echo '<img src="'. get_template_directory_uri() .'/img/no-property-image-slider.png" title="" alt="" />';
											endif; 
										?>
									</div>
									<?php
										if(empty( $homeland_hide_properties_details )) : ?>
											<div class="inside">
												<div class="slider-actions">
													<div class="portfolio-slide-desc">
														<?php 
															the_title( '<h2>', '</h2>' ); 
															the_excerpt(); 
														?>
													</div>	
													<div class="pactions clear">
														<?php 
															if(!empty($homeland_price)) : ?>
																<label>
																	<i class="fa fa-tag"></i>
																	<span><?php homeland_property_price_format(); ?></span>
																</label><?php
															endif;
														?>
														<a href="<?php the_permalink(); ?>">
															<span><?php echo $homeland_slider_button_label; ?></span><i class="fa fa-plus-circle"></i>
														</a>
													</div>													
												</div>
											</div><?php
										endif;
									?>	
								</li><?php
							endwhile; ?>
						</ul>	
					</div>
				</section><?php
			endif;	
		}
	endif;


	/*---------------------------------------------
	Advance Search
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_advance_search' ) ) :
		function homeland_advance_search() {
			$homeland_disable_advance_search = esc_attr( get_option('homeland_disable_advance_search') );
			$homeland_hide_advance_search = esc_attr( get_option('homeland_hide_advance_search') );	

			if(is_front_page()) : 
				if(empty($homeland_hide_advance_search)) : homeland_advance_search_divs(); endif;
			elseif(is_page() || is_single() || is_archive() || is_author() || is_404() || is_search() ) :
				if(empty($homeland_disable_advance_search)) : homeland_advance_search_divs(); endif;
			endif;
		}
	endif;


	/*---------------------------------------------
	Advance Search Div
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_advance_search_divs' ) ) :
		function homeland_advance_search_divs() {
			if(is_page_template('template-homepage.php') || is_page_template('template-homepage2.php') || is_page_template('template-homepage3.php') || is_page_template('template-homepage4.php') || is_page_template('template-homepage-video.php') || is_page_template('template-homepage-revslider.php') || is_page_template('template-homepage-gmap.php') || is_page_template('template-homepage-builder.php')) : 
				$homeland_search_class = "advance-search-block";
			else : $homeland_search_class = "advance-search-block advance-search-block-page";
			endif;

			echo '<section class="' . $homeland_search_class . '"><div class="inside">';
				if ( is_active_sidebar( 'homeland_search_type' ) ) : dynamic_sidebar( 'homeland_search_type' );
				else : homeland_advance_search_form();
				endif;
			echo '</div></section>';
		}
	endif;


	/*---------------------------------------------
	Advance Search Form
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_advance_search_form' ) ) :
		function homeland_advance_search_form() {
			global $homeland_advance_search_page_url;

			$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
			$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 
			$homeland_location_label = esc_attr( get_option('homeland_location_label') );
			$homeland_pid_label = esc_attr( get_option('homeland_pid_label') );
			$homeland_status_label = esc_attr( get_option('homeland_status_label') );
			$homeland_property_type_label = esc_attr( get_option('homeland_property_type_label') );
			$homeland_bed_label = esc_attr( get_option('homeland_bed_label') );
			$homeland_bath_label = esc_attr( get_option('homeland_bath_label') );
			$homeland_min_price_label = esc_attr( get_option('homeland_min_price_label') );
			$homeland_max_price_label = esc_attr( get_option('homeland_max_price_label') );
			$homeland_search_button_label = esc_attr( get_option('homeland_search_button_label') );
			$homeland_hide_location = esc_attr( get_option('homeland_hide_location') );
			$homeland_hide_pid = esc_attr( get_option('homeland_hide_pid') );
			$homeland_hide_status = esc_attr( get_option('homeland_hide_status') );
			$homeland_hide_property_type = esc_attr( get_option('homeland_hide_property_type') );
			$homeland_hide_bed = esc_attr( get_option('homeland_hide_bed') );
			$homeland_hide_bath = esc_attr( get_option('homeland_hide_bath') );
			$homeland_hide_min_price = esc_attr( get_option('homeland_hide_min_price') );
			$homeland_hide_max_price = esc_attr( get_option('homeland_hide_max_price') );
			$homeland_price_format = esc_attr( get_option('homeland_price_format') );
			$homeland_property_decimal = esc_attr( get_option('homeland_property_decimal') );
			$homeland_property_decimal = !empty($homeland_property_decimal) ? $homeland_property_decimal : 0;
			$homeland_prefix = "-- ";

			$homeland_pid_label = !empty($homeland_pid_label) ? $homeland_pid_label : __( 'Property ID', 'codeex_theme_name' );
			$homeland_location_label = !empty($homeland_location_label) ? $homeland_location_label : __( 'Location', 'codeex_theme_name' );
			$homeland_property_type_label = !empty($homeland_property_type_label) ? $homeland_property_type_label : __( 'Type', 'codeex_theme_name' );
			$homeland_status_label = !empty($homeland_status_label) ? $homeland_status_label : __( 'Status', 'codeex_theme_name' );
			$homeland_bed_label = !empty($homeland_bed_label) ? $homeland_bed_label : __( 'Bedrooms', 'codeex_theme_name' );
			$homeland_bath_label = !empty($homeland_bath_label) ? $homeland_bath_label : __( 'Bathrooms', 'codeex_theme_name' );
			$homeland_min_price_label = !empty($homeland_min_price_label) ? $homeland_min_price_label : __( 'Minimum Price', 'codeex_theme_name' );
			$homeland_max_price_label = !empty($homeland_max_price_label) ? $homeland_max_price_label : __( 'Maximum Price', 'codeex_theme_name' );
			$homeland_search_button_label = !empty($homeland_search_button_label) ? $homeland_search_button_label : __( 'Search', 'codeex_theme_name' );

			?>
			<form action="<?php echo $homeland_advance_search_page_url; ?>" method="get" id="searchform">
				<ul class="clear">
					<?php
						if(empty( $homeland_hide_pid )) : 
							$homeland_search_term = @$_GET['pid']; ?>
							<li>
								<input type="text" name="pid" class="property-id" value="<?php if($homeland_search_term) : echo $_GET['pid']; endif; ?>" placeholder="<?php echo $homeland_pid_label; ?>" />
							</li><?php
						endif;

						if(empty( $homeland_hide_location )) : ?>
							<li>
								<select name="location">
									<option value="" selected="selected"><?php echo $homeland_location_label; ?>
									</option>
									<?php
										$homeland_search_term = @$_GET['location'];


										$args = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => 0 );
										$homeland_terms = get_terms('homeland_property_location', $args);

										foreach ($homeland_terms as $homeland_plocation) : ?>
										   <option value="<?php echo $homeland_plocation->slug; ?>" <?php if($homeland_search_term == $homeland_plocation->slug) : echo "selected='selected'"; endif; ?>>
										   	<?php echo $homeland_plocation->name; ?>
										   </option><?php

										   //Child

										   $args_child = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => $homeland_plocation->term_id );
											$homeland_terms_child = get_terms('homeland_property_location', $args_child);

											foreach ($homeland_terms_child as $homeland_plocation_child) : ?>
											   <option value="<?php echo $homeland_plocation_child->slug; ?>" <?php if($homeland_search_term == $homeland_plocation_child->slug) : echo "selected='selected'"; endif; ?>>
											   	<?php echo $homeland_prefix . $homeland_plocation_child->name; ?>
											   </option><?php
											endforeach;
										endforeach;
									?>						
								</select>									
							</li><?php
						endif;

						if(empty( $homeland_hide_property_type )) : ?>
							<li>
								<select name="type">
									<option value="" selected="selected"><?php echo $homeland_property_type_label; ?></option>
									<?php
										$homeland_search_term = @$_GET['type'];

										$args = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => 0 );
										$homeland_terms = get_terms('homeland_property_type', $args);

										if(!empty($homeland_terms)) :
											foreach ($homeland_terms as $homeland_ptype) : ?>
											   <option value="<?php echo $homeland_ptype->slug; ?>" <?php if($homeland_search_term == $homeland_ptype->slug) : echo "selected='selected'"; endif; ?>>
											   	<?php echo $homeland_ptype->name; ?>
											   </option><?php

											   //Child

											   $args_child = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => $homeland_ptype->term_id );
												$homeland_terms_child = get_terms('homeland_property_type', $args_child);

												foreach ($homeland_terms_child as $homeland_ptype_child) : ?>
												   <option value="<?php echo $homeland_ptype_child->slug; ?>" <?php if($homeland_search_term == $homeland_ptype_child->slug) : echo "selected='selected'"; endif; ?>>
												   	<?php echo $homeland_prefix . $homeland_ptype_child->name; ?>
												   </option><?php
												endforeach;
											endforeach;
										endif;
									?>
								</select>
							</li><?php
						endif;

						if(empty( $homeland_hide_status )) : ?>
							<li>
								<select name="status">
									<option value="" selected="selected"><?php echo $homeland_status_label; ?></option>
									<?php
										$homeland_search_term = @$_GET['status'];

										$args = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => 0 );
										$homeland_terms = get_terms('homeland_property_status', $args);

										foreach ($homeland_terms as $homeland_pstatus) : ?>
										   <option value="<?php echo $homeland_pstatus->slug; ?>" <?php if($homeland_search_term == $homeland_pstatus->slug) : echo "selected='selected'"; endif; ?>>
										   	<?php echo $homeland_pstatus->name; ?>
										   </option><?php

										   //Child

										   $args_child = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => $homeland_pstatus->term_id );
											$homeland_terms_child = get_terms('homeland_property_status', $args_child);

											foreach ($homeland_terms_child as $homeland_pstatus_child) : ?>
											   <option value="<?php echo $homeland_pstatus_child->slug; ?>" <?php if($homeland_search_term == $homeland_pstatus_child->slug) : echo "selected='selected'"; endif; ?>>
											   	<?php echo $homeland_prefix . $homeland_pstatus_child->name; ?>
											   </option><?php
											endforeach;
										endforeach;
									?>						
								</select>
							</li><?php
						endif;

						if(empty( $homeland_hide_bed )) : ?>
							<li>
								<select name="bed" class="small">
									<option value="" selected="selected"><?php echo $homeland_bed_label; ?></option>
									<?php
										$homeland_search_term = @$_GET['bed'];
										$homeland_bed_number = get_option('homeland_bed_number');
										$homeland_array = explode(", ", $homeland_bed_number);

										foreach($homeland_array as $homeland_number_option) : ?>
						               <option value="<?php echo $homeland_number_option; ?>" <?php if($homeland_search_term == $homeland_number_option) : echo "selected='selected'"; endif; ?>>
						               	<?php echo $homeland_number_option; ?>
						               </option><?php
						            endforeach;
									?>						
								</select>
							</li><?php
						endif;

						if(empty( $homeland_hide_bath )) : ?>
							<li>
								<select name="bath" class="small">
									<option value="" selected="selected"><?php echo $homeland_bath_label; ?></option>
									<?php
										$homeland_search_term = @$_GET['bath'];
										$homeland_bath_number = get_option('homeland_bath_number');
										$homeland_array = explode(", ", $homeland_bath_number);

										foreach($homeland_array as $homeland_number_option) : ?>
						               <option value="<?php echo $homeland_number_option; ?>" <?php if($homeland_search_term == $homeland_number_option) : echo "selected='selected'"; endif; ?>>
						               	<?php echo $homeland_number_option; ?>
						               </option><?php
						            endforeach;
									?>		
								</select>
							</li><?php
						endif;

						if(empty( $homeland_hide_min_price )) : ?>
							<li>
								<select name="min-price" class="small">
									<option value="" selected="selected"><?php echo $homeland_min_price_label; ?></option>			
									<?php
										$homeland_search_term = @$_GET['min-price'];
										$homeland_min_price_value = get_option('homeland_min_price_value');
										$homeland_array = explode(", ", $homeland_min_price_value);
										$homeland_property_currency_after = "";
										$homeland_property_currency_before = "";

										foreach($homeland_array as $homeland_number_option) : 
											//Currency Position
											if( $homeland_property_currency_sign == "After" ) : $homeland_property_currency_after = $homeland_currency; 
											else : $homeland_property_currency_before = $homeland_currency; 
											endif;

											//Price Format
											if($homeland_price_format == "Dot") :
												$homeland_price_format_result = number_format ( $homeland_number_option, $homeland_property_decimal, ".", "." );
											elseif($homeland_price_format == "Comma") : 
												$homeland_price_format_result = number_format ( $homeland_number_option, $homeland_property_decimal );
											elseif($homeland_price_format == "Brazil" || $homeland_price_format == "Europe") :
												$homeland_price_format_result = number_format( $homeland_number_option, $homeland_property_decimal, ",", "." );
											else : 
												$homeland_price_format_result = $homeland_number_option;
											endif;

											?>
											<option value="<?php echo $homeland_number_option; ?>" <?php if($homeland_search_term == $homeland_number_option) : echo "selected='selected'"; endif; ?>>
												<?php 
													echo $homeland_property_currency_before . $homeland_price_format_result . $homeland_property_currency_after;
												?>
											</option><?php
						            endforeach;
									?>					
								</select>
							</li><?php
						endif;

						if(empty( $homeland_hide_max_price )) : ?>
							<li>
								<select name="max-price" class="small">
									<option value="" selected="selected"><?php echo $homeland_max_price_label; ?></option>
									<?php
										$homeland_search_term = @$_GET['max-price'];
										$homeland_max_price_value = get_option('homeland_max_price_value');
										$homeland_array = explode(", ", $homeland_max_price_value);
										$homeland_property_currency_after = "";
										$homeland_property_currency_before = "";

										foreach($homeland_array as $homeland_number_option) : 
											//Currency Position
											if( $homeland_property_currency_sign == "After" ) : $homeland_property_currency_after = $homeland_currency; 
											else : $homeland_property_currency_before = $homeland_currency; 
											endif;

											//Price Format
											if($homeland_price_format == "Dot") :
												$homeland_price_format_result = number_format ( $homeland_number_option, $homeland_property_decimal, ".", "." );
											elseif($homeland_price_format == "Comma") : 
												$homeland_price_format_result = number_format ( $homeland_number_option, $homeland_property_decimal );
											elseif($homeland_price_format == "Brazil" || $homeland_price_format == "Europe") :
												$homeland_price_format_result = number_format( $homeland_number_option, $homeland_property_decimal, ",", "." );
											else : 
												$homeland_price_format_result = $homeland_number_option;
											endif;

											?>
											<option value="<?php echo $homeland_number_option; ?>" <?php if($homeland_search_term == $homeland_number_option) : echo "selected='selected'"; endif; ?>>
												<?php 
													echo $homeland_property_currency_before . $homeland_price_format_result . $homeland_property_currency_after;
												?>
											</option><?php
						            endforeach;
									?>	
								</select>
							</li><?php
						endif;
					?>
					<li class="last"><input type="submit" value="<?php echo $homeland_search_button_label; ?>" /></li>
				</ul>

			</form><?php
		}
	endif;


	/*---------------------------------------------
	Homepage Video
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_video_fullwidth' ) ) :
		function homeland_video_fullwidth() {
			$homeland_video_url = esc_attr( get_option('homeland_video_url') );

			echo '<section class="home-video-block">';
				echo '<iframe width="100%" height="700" src="' . $homeland_video_url . '" frameborder="0" allowfullscreen class="sframe"></iframe>';
			echo '</section>';
		}
	endif;


	/*---------------------------------------------
	Services List
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_services_list' ) ) :
		function homeland_services_list() {
			global $post;

			$homeland_services_order = esc_attr( get_option('homeland_services_order') );
			$homeland_services_orderby = esc_attr( get_option('homeland_services_orderby') );
			$homeland_services_limit = esc_attr( get_option('homeland_services_limit') );
			$homeland_services_button = esc_attr( get_option('homeland_services_button') );
			$homeland_services_button_label = !empty($homeland_services_button) ? $homeland_services_button : __('More Details', 'codeex_theme_name');
			
			$args = array( 
				'post_type' => 'homeland_services', 
				'orderby' => $homeland_services_orderby, 
				'order' => $homeland_services_order, 
				'posts_per_page' => $homeland_services_limit 
			);

			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--SERVICES-->
				<section class="services-block">
					<div class="inside services-list-box clear"><?php
						for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
							$wp_query->the_post();		

							$homeland_custom_link = esc_url( get_post_meta( $post->ID, 'homeland_custom_link', true ) );	
							$homeland_icon = esc_html( get_post_meta( $post->ID, "homeland_icon", true ) );
							$homeland_custom_icon = esc_html( get_post_meta( $post->ID, "homeland_custom_icon", true ) );	
							
							$homeland_columns = 3;	
							$homeland_class = 'services-list clear ';
							$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : ''; ?>
							
							<div id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class($homeland_class) ); ?>>
								<?php
									if(!empty($homeland_custom_link)) :
										?><a href="<?php echo $homeland_custom_link; ?>" target="_blank"><?php
									else :
										?><a href="<?php the_permalink(); ?>"><?php
									endif;
								?>
									<span class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
										<?php
											if(!empty($homeland_icon)) : ?><i class="hi-icon fa <?php echo $homeland_icon; ?>"></i><?php
											else : ?><img src="<?php echo $homeland_custom_icon; ?>" alt="" title="" /><?php
											endif;
										?>
									</span>
								</a>
								<div class="services-desc">
									<?php 
										the_title( '<h5>', '</h5>' ); 
										the_excerpt();

										if(!empty($homeland_custom_link)) :
											?><a href="<?php echo $homeland_custom_link; ?>" class="more" target="_blank"><?php
										else :
											?><a href="<?php the_permalink(); ?>" class="more"><?php
										endif;

											echo $homeland_services_button_label;
										?>
									</a>
								</div>
							</div><?php
						} ?>				
					</div>
				</section><?php
			endif;
		}
	endif;

	if ( ! function_exists( 'homeland_services_list_two' ) ) :
		function homeland_services_list_two() {
			global $post;

			$homeland_services_order = esc_attr( get_option('homeland_services_order') );
			$homeland_services_orderby = esc_attr( get_option('homeland_services_orderby') );
			$homeland_services_limit = esc_attr( get_option('homeland_services_limit') );
			$homeland_services_button = esc_attr( get_option('homeland_services_button') );
			$homeland_services_button_label = !empty($homeland_services_button) ? $homeland_services_button : __('More Details', 'codeex_theme_name');
			
			$args = array( 
				'post_type' => 'homeland_services', 
				'orderby' => $homeland_services_orderby, 
				'order' => $homeland_services_order, 
				'posts_per_page' => $homeland_services_limit 
			);

			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--SERVICES-->
				<section class="services-block-two">
					<div class="inside services-list-box clear"><?php
						for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
							$wp_query->the_post();		

							$homeland_custom_link = esc_url( get_post_meta( $post->ID, 'homeland_custom_link', true ) );	
							$homeland_icon = esc_html( get_post_meta( $post->ID, "homeland_icon", true ) );
							$homeland_custom_icon = esc_html( get_post_meta( $post->ID, "homeland_custom_icon", true ) );	

							$homeland_columns = 3;	
							$homeland_class = 'services-list clear ';
							$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : ''; ?>
							
							<div id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class($homeland_class) ); ?>>
								<div class="services-icon">
									<?php
										if(!empty($homeland_icon)) : ?><i class="fa <?php echo $homeland_icon; ?> fa-4x"></i><?php
										else : ?><img src="<?php echo $homeland_custom_icon; ?>" alt="" title="" /><?php
										endif;
									?>
								</div>
								<div class="services-desc">
									<?php 
										the_title( '<h5>', '</h5>' ); 
										the_excerpt();

										if(!empty($homeland_custom_link)) :
											?><a href="<?php echo $homeland_custom_link; ?>" class="more" target="_blank"><?php
										else :
											?><a href="<?php the_permalink(); ?>" class="more"><?php
										endif;
											echo $homeland_services_button_label;
										?>
									</a>
								</div>
							</div><?php
						} ?>				
					</div>
				</section><?php
			endif;
		}
	endif;

	if ( ! function_exists( 'homeland_services_list_bg' ) ) :
		function homeland_services_list_bg() {
			global $post;

			$homeland_services_order = esc_attr( get_option('homeland_services_order') );
			$homeland_services_orderby = esc_attr( get_option('homeland_services_orderby') );
			$homeland_services_limit = esc_attr( get_option('homeland_services_limit') );
			$homeland_services_button = esc_attr( get_option('homeland_services_button') );
			$homeland_services_button_label = !empty($homeland_services_button) ? $homeland_services_button : __('More Details', 'codeex_theme_name');
			
			$args = array( 
				'post_type' => 'homeland_services', 
				'orderby' => $homeland_services_orderby, 
				'order' => $homeland_services_order, 
				'posts_per_page' => $homeland_services_limit 
			);

			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--SERVICES-->
				<section class="services-block-bg">
					<div class="inside services-list-box clear"><?php
						for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
							$wp_query->the_post();		

							$homeland_custom_link = esc_url( get_post_meta( $post->ID, 'homeland_custom_link', true ) );	
							$homeland_icon = esc_html( get_post_meta( $post->ID, "homeland_icon", true ) );
							$homeland_custom_icon = esc_html( get_post_meta( $post->ID, "homeland_custom_icon", true ) );	

							$homeland_columns = 3;	
							$homeland_class = 'services-list clear ';
							$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : ''; ?>
							
							<div id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class($homeland_class) ); ?>>
								<?php
									if(!empty($homeland_custom_link)) :
										?><a href="<?php echo $homeland_custom_link; ?>" target="_blank"><?php
									else :
										?><a href="<?php the_permalink(); ?>"><?php
									endif;
								?>
									<span class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">
										<?php
											if(!empty($homeland_icon)) : ?><i class="hi-icon fa <?php echo $homeland_icon; ?>"></i><?php
											else : ?><img src="<?php echo $homeland_custom_icon; ?>" alt="" title="" /><?php
											endif;
										?>
									</span>
								</a>
								<div class="services-desc">
									<?php 
										the_title( '<h5>', '</h5>' ); 
										the_excerpt();

										if(!empty($homeland_custom_link)) :
											?><a href="<?php echo $homeland_custom_link; ?>" class="more" target="_blank"><?php
										else :
											?><a href="<?php the_permalink(); ?>" class="more"><?php
										endif;
											echo $homeland_services_button_label;
										?>
									</a>
								</div>
							</div><?php
						} ?>				
					</div>
				</section><?php
			endif;
		}
	endif;


	/*---------------------------------------------
	Property List
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_property_list' ) ) :
		function homeland_property_list() {
			global $post, $homeland_class;

			$homeland_album_order = esc_attr( get_option('homeland_album_order') );
			$homeland_album_orderby = esc_attr( get_option('homeland_album_orderby') );
			$homeland_property_limit = esc_attr( get_option('homeland_property_limit') );
			$homeland_property_header = esc_attr( get_option('homeland_property_header') );
			$homeland_property_header_label = !empty($homeland_property_header) ? $homeland_property_header : __('Latest Property', 'codeex_theme_name' );
			
			$args = array( 
				'post_type' => 'homeland_properties', 
				'orderby' => $homeland_album_orderby, 
				'order' => $homeland_album_order, 
				'posts_per_page' => $homeland_property_limit
			);

			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--PROPERTY-->
				<section class="property-block">
					<div class="inside property-list-box clear">
						<h2><span><?php echo $homeland_property_header_label; ?></span></h2>
						<div id="carousel" class="es-carousel-wrapper">
							<div class="es-carousel">
								<div class="grid cs-style-3">	
									<ul class="clear">
										<?php
											while ($wp_query->have_posts()) : $wp_query->the_post();
												$homeland_class = 'property-home';
												get_template_part( 'loop', 'property-home' );
											endwhile;								
										?>
									</ul>
								</div>
							</div>	
						</div>

					</div>
				</section><?php	
			endif;
		}
	endif;

	if ( ! function_exists( 'homeland_property_list_grid' ) ) :
		function homeland_property_list_grid() {
			global $post, $homeland_class;

			$homeland_album_order = esc_attr( get_option('homeland_album_order') );
			$homeland_album_orderby = esc_attr( get_option('homeland_album_orderby') );
			$homeland_property_limit = esc_attr( get_option('homeland_property_limit') );
			$homeland_property_header = esc_attr( get_option('homeland_property_header') );
			$homeland_property_header_label = !empty($homeland_property_header) ? $homeland_property_header : __('Latest Property', 'codeex_theme_name' );
			
			$args = array( 
				'post_type' => 'homeland_properties', 
				'orderby' => $homeland_album_orderby, 
				'order' => $homeland_album_order, 
				'posts_per_page' => $homeland_property_limit 
			);

			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--PROPERTY-->
				<section class="property-block">
					<div class="inside property-list-box clear">
						<h2><span><?php echo $homeland_property_header_label; ?></span></h2>
						<div class="grid cs-style-3 masonry">	
							<ul class="clear">
								<?php
									for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
										$wp_query->the_post();			
										$homeland_columns = 3;	
										$homeland_class = 'property-home masonry-item ';
										$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
										
										get_template_part( 'loop', 'property-home' );
									}
								?>
							</ul>
						</div>		
					</div>
				</section><?php	
			endif;
		}
	endif;


	/*---------------------------------------------
	Blog List
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_blog_latest' ) ) :
		function homeland_blog_latest() {
			global $post;

			$homeland_blog_limit = esc_attr( get_option('homeland_blog_limit') );
			$homeland_blog_header = esc_attr( get_option('homeland_blog_header') );
			$homeland_blog_category = esc_attr( get_option('homeland_blog_category') );
			$homeland_blog_header_label = !empty( $homeland_blog_header ) ? $homeland_blog_header : __( 'Latest News', 'codeex_theme_name' );

			if($homeland_blog_category == __('Choose a category', 'codeex_theme_name')) :
				$args = array( 'post_type' => 'post', 'posts_per_page' => $homeland_blog_limit );
			else :
				$args = array( 'post_type' => 'post', 'posts_per_page' => $homeland_blog_limit, 'category_name' => $homeland_blog_category );
			endif;					
				
			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--BLOG-->
				<div class="blog-block">
					<h3><span><?php echo $homeland_blog_header_label; ?></span></h3>
					<ul>
						<?php
							while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
								<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('latest-list clear') ); ?>>
									<div class="bimage">
										<a href="<?php the_permalink(); ?>">
											<?php if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_news_thumb'); endif; ?>
										</a>
									</div>
									<div class="bdesc">
										<?php the_title( '<h5><a href="' . get_permalink() . '">', '</a></h5>' );  ?>
										<label>
											<?php esc_attr( _e( 'Posted by:', 'codeex_theme_name' ) ); echo "&nbsp;"; the_author_meta('display_name'); echo "&nbsp;|&nbsp;"; the_time(get_option('date_format')); ?>
										</label>
									</div>
								</li><?php
							endwhile;								
						?>
					</ul>
				</div><?php
			else :
				_e( 'You have no blog post yet!', 'codeex_theme_name' );
			endif;
		}
	endif;


	/*---------------------------------------------
	Welcome Text
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_welcome_text' ) ) :
		function homeland_welcome_text() {
			$homeland_welcome_button = esc_attr( get_option('homeland_welcome_button') );	
			$homeland_welcome_header = stripslashes( esc_attr( get_option('homeland_welcome_header') ) );
			$homeland_welcome_text = stripslashes( esc_attr( get_option('homeland_welcome_text') ) );
			$homeland_welcome_link = esc_attr( get_option('homeland_welcome_link') );
			$homeland_welcome_button_label = !empty($homeland_welcome_button) ? $homeland_welcome_button : __('View Properties', 'codeex_theme_name');
			?>

				<section class="welcome-block">
					<div class="inside">
						<h2><?php echo $homeland_welcome_header; ?></h2>
						<label><?php echo $homeland_welcome_text; ?></label>
						<?php
							if(!empty($homeland_welcome_link)) :
								echo "<a href='" . $homeland_welcome_link . "' class='view-property'>" . $homeland_welcome_button_label . "</a>";
							endif;
						?>
					</div>
				</section>
			<?php
		}
	endif;

	if ( ! function_exists( 'homeland_welcome_text_top' ) ) :
		function homeland_welcome_text_top() {
			$homeland_welcome_button = esc_attr( get_option('homeland_welcome_button') );	
			$homeland_welcome_header = stripslashes( esc_attr( get_option('homeland_welcome_header') ) );
			$homeland_welcome_text = stripslashes( esc_attr( get_option('homeland_welcome_text') ) );
			$homeland_welcome_link = esc_attr( get_option('homeland_welcome_link') );
			$homeland_welcome_button_label = !empty($homeland_welcome_button) ? $homeland_welcome_button : __('View Properties', 'codeex_theme_name');
			?>

				<section class="welcome-block-top">
					<div class="inside">
						<h2><?php echo $homeland_welcome_header; ?></h2>
						<label><?php echo $homeland_welcome_text; ?></label>
						<?php
							if(!empty($homeland_welcome_link)) :
								echo "<a href='" . $homeland_welcome_link . "' class='view-property'>" . $homeland_welcome_button_label . "</a>";
							endif;
						?>
					</div>
				</section>
			<?php
		}
	endif;


	/*---------------------------------------------
	Agent List
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_agent_list' ) ) :
		function homeland_agent_list() {
			global $post;

			$homeland_agent_limit = esc_attr( get_option('homeland_agent_limit') ); 
			$homeland_agents_header = esc_attr( get_option('homeland_agents_header') );
			$homeland_agent_order = esc_attr( get_option('homeland_agent_order') );
			$homeland_agent_orderby = esc_attr( get_option('homeland_agent_orderby') );
			$homeland_all_agents = esc_attr( get_option('homeland_all_agents') );
			$homeland_agents_header_label = !empty( $homeland_agents_header ) ? $homeland_agents_header : __( 'Agents', 'codeex_theme_name' );

			if(empty($homeland_all_agents)) : ?>	
				<div class="agent-block">
					<h3><span><?php echo $homeland_agents_header_label; ?></span></h3>
					<ul>
						<?php
							$args = array( 
								'role' => 'contributor', 
								'order' => $homeland_agent_order, 
								'orderby' => $homeland_agent_orderby, 
								'number' => $homeland_agent_limit 
							);

						   $homeland_agents = new WP_User_Query( $args );

						   if (!empty( $homeland_agents->results )) :
								foreach ($homeland_agents->results as $homeland_user) :
									global $wpdb;

									$homeland_post_author = $homeland_user->ID;
									$homeland_custom_avatar = get_the_author_meta('homeland_custom_avatar', $homeland_post_author);

									$homeland_count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'homeland_properties' AND post_status = 'publish' AND post_author = %d", $homeland_post_author ) );
									?>
										<li class="clear">
											<a href="<?php echo esc_url( get_author_posts_url( $homeland_post_author ) ); ?>">
												<?php 
					    							if(!empty($homeland_custom_avatar)) : 
					    								echo '<img src="' . $homeland_custom_avatar . '" class="avatar" style="width:70px; height:70px;" />';
						    						else : echo get_avatar( $homeland_post_author, 70 );
						    						endif;
					    						?>
											</a>
											<h4>
												<a href="<?php echo esc_url( get_author_posts_url( $homeland_post_author ) ); ?>">
													<?php echo $homeland_user->display_name; ?>
												</a>
											</h4>
											<label>
												<i class="fa fa-home fa-lg"></i> <?php esc_attr( _e( 'Listed:', 'codeex_theme_name' ) ); ?>
												<span>
													<?php echo intval($homeland_count); echo "&nbsp;"; esc_attr( _e( 'Properties', 'codeex_theme_name' ) ); ?>
												</span>

											</label>
										</li>	
									<?php
								endforeach;
							else : _e( 'No Agents found!', 'codeex_theme_name' );
							endif;
						?>
					</ul>
				</div><?php	
			endif;
		}
	endif;


	/*---------------------------------------------
	Featured Properties List
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_featured_list' ) ) :
		function homeland_featured_list() {
			global $post;

			$homeland_album_order = esc_attr( get_option('homeland_album_order') );
			$homeland_album_orderby = esc_attr( get_option('homeland_album_orderby') );
			$homeland_featured_property_limit = esc_attr( get_option('homeland_featured_property_limit') );
			$homeland_featured_property_header = esc_attr( get_option('homeland_featured_property_header') );
			$homeland_price_format = esc_attr( get_option('homeland_price_format') );
			$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
			$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 
			$homeland_preferred_size = esc_attr( get_option('homeland_preferred_size') ); 
			$homeland_featured_property_header_label = !empty($homeland_featured_property_header) ? $homeland_featured_property_header : __('Featured Property', 'codeex_theme_name');
			?>
				<div class="featured-block">
					<h3><span><?php echo $homeland_featured_property_header_label; ?></span></h3>
					<?php
						$args = array( 
							'post_type' => 'homeland_properties', 
							'orderby' => $homeland_album_orderby, 
							'order' => $homeland_album_order, 
							'posts_per_page' => $homeland_featured_property_limit, 
							'meta_query' => array( array( 
								'key' => 'homeland_featured', 
								'value' => 'on', 
								'compare' => '==' 
							))
						);		

						$wp_query = new WP_Query( $args );

						if ($wp_query->have_posts()) : ?>
							<div class="grid cs-style-3">	
								<ul>
									<?php
										while ($wp_query->have_posts()) : 
											$wp_query->the_post(); 
											$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
											$homeland_price = esc_attr( get_post_meta($post->ID, 'homeland_price', true ) );
											$homeland_area = esc_attr( get_post_meta($post->ID, 'homeland_area', true) );
											$homeland_area_unit = esc_attr( get_post_meta( $post->ID, 'homeland_area_unit', true ) );
											$homeland_floor_area = esc_attr( get_post_meta( $post->ID, 'homeland_floor_area', true ) );
											$homeland_floor_area_unit = esc_attr( get_post_meta( $post->ID, 'homeland_floor_area_unit', true ) );
											$homeland_bedroom = esc_attr( get_post_meta($post->ID, 'homeland_bedroom', true) );
											$homeland_bathroom = esc_attr( get_post_meta($post->ID, 'homeland_bathroom', true) );
											$homeland_garage = esc_attr( get_post_meta($post->ID, 'homeland_garage', true) );
											$homeland_property_status = get_the_term_list( $post->ID, 'homeland_property_status', ' ', ', ', '' );

											?>
											<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('featured-list clear') ); ?>>
												<?php
													if ( post_password_required() ) : 
														echo '<div class="password-protect-thumb featured-pass-thumb">';
															echo '<i class="fa fa-lock fa-2x"></i>';
														echo '</div>';
													else : ?>
														<figure class="feat-thumb">
															<a href="<?php the_permalink(); ?>">
																<?php 
																	if ( has_post_thumbnail() ) : the_post_thumbnail(); 
																	else :
																		echo '<img src="'. get_template_directory_uri() .'/img/no-property-image.png" title="" alt="" />';
																	endif; 
																?>
															</a>
															<figcaption>
																<a href="<?php the_permalink(); ?>"><i class="fa fa-link fa-lg"></i></a>
															</figcaption>
															<?php
																if(!empty( $homeland_property_status )) : 
																	echo '<h4>' . $homeland_property_status . '</h4>';
																endif; 
															?>	
														</figure><?php	
													endif;
												?>
												<div class="feat-desc">
													<?php the_title( '<h5><a href="' . get_permalink() . '">', '</a></h5>' ); ?>
													<span>
														<?php 
															if($homeland_preferred_size == "Floor Area") :
																if(!empty($homeland_floor_area)) :
																	echo $homeland_floor_area . "&nbsp;" . $homeland_floor_area_unit . ", "; 
																endif;
															else :
																if(!empty($homeland_area)) :
																	echo $homeland_area . "&nbsp;" . $homeland_area_unit . ", "; 
																endif;
															endif;
															if(!empty($homeland_bedroom)) :
																echo $homeland_bedroom . "&nbsp;"; _e( 'Bedrooms', 'codeex_theme_name' ); echo ", "; 
															endif;
															if(!empty($homeland_bathroom)) :
																echo $homeland_bathroom . "&nbsp;"; _e( 'Bathrooms', 'codeex_theme_name' ); echo ", "; 
															endif;
															if(!empty($homeland_garage)) :
																echo $homeland_garage . "&nbsp;"; _e( 'Garage', 'codeex_theme_name' ); 
															endif;
														?>
													</span>
													<?php
														if( !empty($homeland_price) ) : 
															echo '<span class="price">';
																homeland_property_price_format();
															echo '</span>';
														endif;
													?>
												</div>
											</li><?php
										endwhile;
									?>							
								</ul>
							</div><?php
						endif;
					?>
				</div>
			<?php	
		}
	endif;

	if ( ! function_exists( 'homeland_featured_list_large' ) ) :
		function homeland_featured_list_large() {
			global $post;

			$homeland_property_order = esc_attr( get_option('homeland_property_order') );
			$homeland_property_orderby = esc_attr( get_option('homeland_property_orderby') );
			$homeland_featured_property_limit = esc_attr( get_option('homeland_featured_property_limit') );
			$homeland_featured_property_header = esc_attr( get_option('homeland_featured_property_header') );
			$homeland_price_format = esc_attr( get_option('homeland_price_format') );
			$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
			$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 
			$homeland_preferred_size = esc_attr( get_option('homeland_preferred_size') ); 
			$homeland_featured_property_header_label = !empty($homeland_featured_property_header) ? $homeland_featured_property_header : __('Featured Property', 'codeex_theme_name');

			?>
				<div class="featured-block-two-cols">
					<h3><span><?php echo $homeland_featured_property_header_label; ?></span></h3>
					<?php
						$args = array( 
							'post_type' => 'homeland_properties', 
							'orderby' => $homeland_property_orderby, 
							'order' => $homeland_property_order, 
							'posts_per_page' => $homeland_featured_property_limit, 
							'meta_query' => array( array( 
								'key' => 'homeland_featured', 
								'value' => 'on', 
								'compare' => '==' 
							)) 
						);		

						$wp_query = new WP_Query( $args );

						if ($wp_query->have_posts()) : ?>
							<div class="grid cs-style-3">	
								<ul>
									<?php
										while ($wp_query->have_posts()) :
											$wp_query->the_post(); 
											$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
											$homeland_price = esc_attr( get_post_meta($post->ID, 'homeland_price', true ) );
											$homeland_area = esc_attr( get_post_meta($post->ID, 'homeland_area', true) );
											$homeland_area_unit = esc_attr( get_post_meta( $post->ID, 'homeland_area_unit', true ) );
											$homeland_floor_area = esc_attr( get_post_meta( $post->ID, 'homeland_floor_area', true ) );
											$homeland_floor_area_unit = esc_attr( get_post_meta( $post->ID, 'homeland_floor_area_unit', true ) );
											$homeland_bedroom = esc_attr( get_post_meta($post->ID, 'homeland_bedroom', true) );
											$homeland_bathroom = esc_attr( get_post_meta($post->ID, 'homeland_bathroom', true) );
											$homeland_garage = esc_attr( get_post_meta($post->ID, 'homeland_garage', true) );

											?>
											<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class('featured-list clear') ); ?>>
												<?php
													if ( post_password_required() ) : 
														echo "<div class='password-protect-thumb featured-pass-thumb'><i class='fa fa-lock fa-2x'></i></div>";
													else : ?>
														<figure class="feat-medium">
															<a href="<?php the_permalink(); ?>">
																<?php 
																	if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_property_medium'); 
																	else :
																		echo '<img src="' . get_template_directory_uri() . '/img/no-property-image.png" title="" alt="" />';
																	endif; 
																?>
															</a>
															<figcaption>
																<a href="<?php the_permalink(); ?>"><i class="fa fa-link fa-lg"></i></a>
															</figcaption>
														</figure><?php	
													endif;
												?>
												<div class="feat-desc">
													<?php the_title( '<h5><a href="' . get_permalink() . '">', '</a></h5>' );  ?>
													<span>
														<?php 
															if($homeland_preferred_size == "Floor Area") :
																if(!empty($homeland_floor_area)) :
																	echo $homeland_floor_area . "&nbsp;" . $homeland_floor_area_unit . ", "; 
																endif;
															else :
																if(!empty($homeland_area)) :
																	echo $homeland_area . "&nbsp;" . $homeland_area_unit . ", "; 
																endif;
															endif;
															if(!empty($homeland_bedroom)) :
																echo $homeland_bedroom . "&nbsp;"; _e( 'Bedrooms', 'codeex_theme_name' ); echo ", "; 
															endif;
															if(!empty($homeland_bathroom)) :
																echo $homeland_bathroom . "&nbsp;"; _e( 'Bathrooms', 'codeex_theme_name' ); echo ", "; 
															endif;
															if(!empty($homeland_garage)) :
																echo $homeland_garage . "&nbsp;"; _e( 'Garage', 'codeex_theme_name' ); 
															endif;
														?>
													</span>
													<?php
														if( !empty($homeland_price) ) : ?>
															<span class="price"><?php homeland_property_price_format(); ?></span><?php
														endif;
													?>
												</div>
											</li><?php
										endwhile;
									?>							
								</ul>
							</div><?php
						endif;
					?>
				</div>
			<?php	
		}
	endif;


	/*---------------------------------------------
	Testimonials
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_testimonials' ) ) :
		function homeland_testimonials() {
			global $post;

			$homeland_testi_limit = esc_attr( get_option('homeland_testi_limit') );
			$homeland_testi_header = esc_attr( get_option('homeland_testi_header') );
			$homeland_testi_header_label = !empty($homeland_testi_header) ? $homeland_testi_header : __('Our Customer Says', 'codeex_theme_name');
									
			$args = array( 
				'post_type' => 'homeland_testimonial', 
				'posts_per_page' => $homeland_testi_limit 
			);

			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--TESTIMONIALS-->
				<div class="testimonial-block">
					<div class="inside">
						<h3><?php echo $homeland_testi_header_label; ?></h3>
						<div class="testimonial-flexslider">	
							<ul class="slides">
								<?php
									while ($wp_query->have_posts()) : 
										$wp_query->the_post(); 
										$homeland_position = esc_attr( get_post_meta( $post->ID, 'homeland_position', true ) );
									?>
										<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class() ); ?>>
											<?php 
												the_content();
												if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_theme_thumb'); endif;
												the_title( '<h4>', '</h4>' ); 
											?>	
											<h5><?php echo $homeland_position; ?></h5>
										</li><?php							
									endwhile;								
								?>
							</ul>	
						</div>
					</div>
				</div><?php
			endif;
		}
	endif;


	/*---------------------------------------------
	Social Share Icons
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_social_share' ) ) :
		function homeland_social_share() {
			?>
				<!--SHARE-->
				<div class="share clear">
					<span><?php esc_attr( _e( 'Share', 'codeex_theme_name' ) ); ?><i class="fa fa-share fa-lg"></i></span>
					<ul class="clear">	
						<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>&amp;title=<?php the_title(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;" target="_blank" title="Facebook"><i class="fa fa-facebook fa-lg"></i></a></li>
						<li><a href="http://twitter.com/share?url=<?php the_permalink();?>&amp;title=<?php the_title(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;" target="_blank" title="Twitter"><i class="fa fa-twitter fa-lg"></i></a></li>
						<li><a href="https://plus.google.com/share?url={<?php the_permalink();?>}&amp;title=<?php the_title(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;" target="_blank" title="Google+"><i class="fa fa-google-plus fa-lg"></i></a></li>
						<li><a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;" target="_blank" title="Pinterest"><i class="fa fa-pinterest fa-lg"></i></a></li>
						<li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink();?>&amp;title=<?php the_title(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;" target="_blank" title="LinkedIn"><i class="fa fa-linkedin fa-lg"></i></a></li>
					</ul>
				</div>
			<?php
		}
	endif;


	/*---------------------------------------------
	Header Social Icons
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_social_share_header' ) ) :
		function homeland_social_share_header() {	
			$homeland_brand_color = esc_attr( get_option('homeland_brand_color') );
			$homeland_twitter = esc_attr( get_option('homeland_twitter') );
			$homeland_facebook = esc_attr( get_option('homeland_facebook') );
			$homeland_pinterest = esc_attr( get_option('homeland_pinterest') );
			$homeland_dribbble = esc_attr( get_option('homeland_dribbble') );
			$homeland_instagram = esc_attr( get_option('homeland_instagram') );
			$homeland_rss = esc_url( get_option('homeland_rss') );
			$homeland_youtube = esc_url( get_option('homeland_youtube') );
			$homeland_gplus = esc_url( get_option('homeland_gplus') );
			$homeland_linkedin = esc_url( get_option('homeland_linkedin') );
			?>
				<!--SOCIAL-->
				<div class="<?php if(!empty($homeland_brand_color)) : echo 'social-colors'; endif; ?> social">
					<ul class="clear">
						<?php 	
							if(!empty( $homeland_twitter )) :
								echo "<li class='twitter'><a href='http://twitter.com/" . $homeland_twitter . "' target='_blank'><i class='fa fa-twitter'></i></a></li>";
							endif; 
							if(!empty( $homeland_facebook )) : 
								echo "<li class='facebook'><a href='http://facebook.com/" . $homeland_facebook ."' target='_blank'><i class='fa fa-facebook'></i></a></li>";
							endif; 
							if(!empty( $homeland_youtube )) :
								echo "<li class='youtube'><a href='" . $homeland_youtube . "' target='_blank'><i class='fa fa-youtube'></i></a></li>";
							endif; 
							if(!empty( $homeland_linkedin )) : 
								echo "<li class='linkedin'><a href='" . $homeland_linkedin . "' target='_blank'><i class='fa fa-linkedin'></i></a></li>"; 
							endif; 
							if(!empty( $homeland_pinterest )) : 
								echo "<li class='pinterest'><a href='http://pinterest.com/" . $homeland_pinterest . "' target='_blank'><i class='fa fa-pinterest'></i></a></li>";
							endif; 
							if(!empty( $homeland_dribbble )) : 
								echo "<li class='dribbble'><a href='http://dribbble.com/" . $homeland_dribbble . "' target='_blank'><i class='fa fa-dribbble'></i></a></li>";
							endif; 
							if(!empty( $homeland_gplus )) : 	
								echo "<li class='gplus'><a href='" . $homeland_gplus . "' target='_blank'><i class='fa fa-google-plus'></i></a></li>"; 
							endif;
							if(!empty( $homeland_instagram )) : 
								echo "<li class='instagram'><a href='http://instagram.com/" . $homeland_instagram . "' target='_blank'><i class='fa fa-instagram'></i></a></li>"; 
							endif; 
							if(!empty( $homeland_rss )) : 
								echo "<li class='rss'><a href='" . $homeland_rss . "' target='_blank'><i class='fa fa-rss'></i></a></li>";
							endif;  
						?>	
					</ul>
				</div>
			<?php
		}
	endif;


	/*---------------------------------------------
	Header Information
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_call_info_header' ) ) :
		function homeland_call_info_header() {

			$homeland_call_us_label = esc_attr( get_option('homeland_call_us_label') );
			$homeland_login_label = esc_attr( get_option('homeland_login_label') );
			$homeland_logout_label = esc_attr( get_option('homeland_logout_label') );
			$homeland_hide_login = esc_attr( get_option('homeland_hide_login') );
			$homeland_login_link = esc_url( get_option('homeland_login_link') );
			$homeland_register_label = esc_attr( get_option('homeland_register_label') );
			$homeland_hide_register = esc_attr( get_option('homeland_hide_register') );
			$homeland_register_link = esc_url( get_option('homeland_register_link') );
			$homeland_search_label = esc_attr( get_option('homeland_search_label') );
			$homeland_phone_number = esc_attr( get_option('homeland_phone_number') ); 
			$homeland_call_us_label = !empty( $homeland_call_us_label ) ? $homeland_call_us_label : __( 'Call us', 'codeex_theme_name' );
			$homeland_login_label = !empty( $homeland_login_label ) ? $homeland_login_label : __( 'Login', 'codeex_theme_name' );
			$homeland_logout_label = !empty( $homeland_logout_label ) ? $homeland_logout_label : __( 'Logout', 'codeex_theme_name' );
			$homeland_register_label = !empty( $homeland_register_label ) ? $homeland_register_label : __( 'Register', 'codeex_theme_name' );
			$homeland_search_label = !empty( $homeland_search_label ) ? $homeland_search_label : __( 'Enter Keyword and hit enter...', 'codeex_theme_name' );

			if(!empty($homeland_login_link)) :
				$homeland_login_link_value = $homeland_login_link;
			else :
				$homeland_login_link_value = wp_login_url(admin_url());
			endif;

			if(!empty($homeland_register_link)) :
				$homeland_register_link_value = $homeland_register_link;
			else :
				$homeland_register_link_value = wp_registration_url();
			endif;

			?>
				<!--CALL INFO.-->
				<div class="call-info clear">
					<span class="call-us"><i class="fa fa-phone"></i>
						<?php 
							if ( ! is_user_logged_in() ) :
								echo $homeland_call_us_label . ": " . $homeland_phone_number; 
							else : echo $homeland_phone_number; 
							endif;
						?>
					</span>
					<?php
						if(empty( $homeland_hide_login )) : 
							if ( ! is_user_logged_in() ) :
								echo '<a href="' . $homeland_login_link_value . '" class="login" target="_blank"><i class="fa fa-sign-in"></i>' . $homeland_login_label . '</a>';
							else :
								echo '<a href="' . wp_logout_url() . '" class="login"><i class="fa fa-sign-out"></i>' . $homeland_logout_label . '</a>';
							endif;
						endif;

						if(empty( $homeland_hide_register )) : 
							if ( ! is_user_logged_in() ) :
								echo '<a href="' . $homeland_register_link_value . '" class="register login" target="_blank"><i class="fa fa-pencil"></i>' . $homeland_register_label . '</a>';
							else :
								$homeland_current_user = wp_get_current_user();
								?>
									<a href="<?php echo get_edit_user_link(); ?>" class="login" target="_blank">
										<i class="fa fa-user"></i>
										<?php 
											_e('Hi', 'codeex_theme_name'); 
											echo ",&nbsp;". $homeland_current_user->user_firstname; 
										?>
									</a>
								<?php
							endif;
						endif;
					?>
				</div>
				<div class="sb-search">
					<span class="sb-icon-search"></span>
					<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" id="searchform" class="header-search">
						<input class="sb-search-input" placeholder="<?php echo $homeland_search_label; ?>" type="text" value="" id="s" name="s">
						<input class="sb-search-submit" type="submit" value="">
					</form>
				</div>
			<?php
		}
	endif;


	/*---------------------------------------------
	Partners List
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_partners_list' ) ) :
		function homeland_partners_list() {
			global $post;

			$homeland_partners_limit = esc_attr( get_option('homeland_partners_limit') );
			$homeland_partners_header = esc_attr( get_option('homeland_partners_header') );
			$homeland_partner_order = esc_attr( get_option('homeland_partner_order') );
			$homeland_partner_orderby = esc_attr( get_option('homeland_partner_orderby') );
			$homeland_partners_header_label = !empty( $homeland_partners_header ) ? $homeland_partners_header : __( 'Our Trusted Partners', 'codeex_theme_name' );
									
			$args = array( 
				'post_type' => 'homeland_partners', 
				'order' => $homeland_partner_order,
				'orderby' => $homeland_partner_orderby,
				'posts_per_page' => $homeland_partners_limit 
			);

			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--PARTNERS-->
				<div class="partners-block">
					<div class="inside">
						<h3><?php echo $homeland_partners_header_label; ?></h3>
						<div class="partners-flexslider clear">	
							<ul class="slides">
								<?php
									while ($wp_query->have_posts()) : 
										$wp_query->the_post(); 
										$homeland_url = esc_url( get_post_meta( $post->ID, 'homeland_url', true ) );
										?>
										<li id="post-<?php the_ID(); ?>" <?php sanitize_html_class( post_class() ); ?>>
											<a href="<?php echo $homeland_url; ?>" target="_blank">
												<?php if ( has_post_thumbnail() ) : the_post_thumbnail('full'); endif; ?>	
											</a>
										</li><?php							
									endwhile;								
								?>
							</ul>	
						</div>
					</div>
				</div><?php
			endif;
		}
	endif;


	/*---------------------------------------------
	Portfolio List
	----------------------------------------------*/

	if ( ! function_exists( 'homeland_portfolio_list_grid' ) ) :
		function homeland_portfolio_list_grid() {
			global $post, $homeland_class;

			$homeland_portfolio_home_order = esc_attr( get_option('homeland_portfolio_home_order') );
			$homeland_portfolio_home_orderby = esc_attr( get_option('homeland_portfolio_home_orderby') );
			$homeland_portfolio_limit = esc_attr( get_option('homeland_portfolio_limit') );
			$homeland_portfolio_header = esc_attr( get_option('homeland_portfolio_header') );
			$homeland_portfolio_header_label = !empty( $homeland_portfolio_header ) ? $homeland_portfolio_header : __( 'Our Works', 'codeex_theme_name' );
			
			$args = array( 
				'post_type' => 'homeland_portfolio', 
				'orderby' => $homeland_portfolio_home_orderby, 
				'order' => $homeland_portfolio_home_order, 
				'posts_per_page' => $homeland_portfolio_limit 
			);

			$wp_query = new WP_Query( $args );	

			if ($wp_query->have_posts()) : ?>
				<!--PROPERTY-->
				<section class="property-block">
					<div class="inside property-list-box clear">
						<h2><span><?php echo $homeland_portfolio_header_label; ?></span></h2>
						<div class="grid cs-style-3 masonry">	
							<ul class="clear">
								<?php
									for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
										$wp_query->the_post();			
										$homeland_columns = 3;	
										$homeland_class = 'property-home portfolio-cols masonry-item ';
										$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
										
										get_template_part( 'loop', 'portfolio' );
									}
								?>
							</ul>
						</div>		
					</div>
				</section><?php	
			endif;
		}
	endif;
?>