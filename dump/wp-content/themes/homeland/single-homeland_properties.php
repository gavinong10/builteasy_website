<?php get_header(); ?>
	
<?php 
	$homeland_advance_search = get_post_meta( $post->ID, 'homeland_advance_search', true );
	$homeland_single_property_layout = esc_attr( get_option('homeland_single_property_layout') );

	if(empty($homeland_advance_search)) :
		homeland_advance_search(); //modify function in "includes/lib/custom-functions.php"...
	endif;
?>

	<!--PROPERTY LIST-->
	<section class="theme-pages">

		<div class="inside clear">

			<!--CONTAINER-->	
			<?php
				if($homeland_single_property_layout =="Fullwidth") :
					$homeland_single_layout_class = "theme-fullwidth";
					$homeland_property_slide_image_size = "homeland_portfolio_large";
				elseif($homeland_single_property_layout =="Left Sidebar") :
					$homeland_single_layout_class = "left-container right";
					$homeland_property_class_sidebar = "sidebar left";
					$homeland_property_slide_image_size = "homeland_theme_large";
				else :
					$homeland_single_layout_class = "left-container";
					$homeland_property_class_sidebar = "sidebar";
					$homeland_property_slide_image_size = "homeland_theme_large";
 				endif;
			?>		
			<div class="<?php echo $homeland_single_layout_class; ?>">				
				<div class="property-list-page single-property clear">
					<?php
						$homeland_attachment_order = esc_attr( get_option('homeland_attachment_order') );
						$homeland_attachment_orderby = esc_attr( get_option('homeland_attachment_orderby') );
						$homeland_agent_info = esc_attr( get_option('homeland_agent_info') );
						$homeland_other_properties = esc_attr( get_option('homeland_other_properties') );
						$homeland_property_map_header = esc_attr( get_option('homeland_property_map_header') ); 
						$homeland_properties_thumb_slider = esc_attr( get_option('homeland_properties_thumb_slider') );
						$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
						$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 
						$homeland_agent_button = esc_attr( get_option('homeland_agent_button') ); 
						$homeland_agent_form = esc_attr( get_option('homeland_agent_form') ); 
						$homeland_other_properties_header = esc_attr( get_option('homeland_other_properties_header') ); 
						$homeland_price_format = esc_attr( get_option('homeland_price_format') );
						$homeland_hide_map = esc_attr( get_option('homeland_hide_map') );
						$homeland_show_street_view = esc_attr( get_option('homeland_show_street_view') );
						$homeland_other_property_limit = esc_attr( get_option('homeland_other_property_limit') );
						$homeland_hide_property_comments = esc_attr( get_option('homeland_hide_property_comments') );
						$homeland_all_agents = esc_attr( get_option('homeland_all_agents') );
						$homeland_property_amenities_header = esc_attr( get_option('homeland_property_amenities_header') );
						$homeland_clickable_amenities = esc_attr( get_option('homeland_clickable_amenities') );
						$homeland_property_amenities_header_label = !empty($homeland_property_amenities_header) ? $homeland_property_amenities_header : __('Amenities', 'codeex_theme_name');
						$homeland_property_map_header_label = !empty($homeland_property_map_header) ? $homeland_property_map_header : __('Property Map', 'codeex_theme_name');
						$homeland_agent_form_label = !empty($homeland_agent_form) ? $homeland_agent_form : __('Contact Agent', 'codeex_theme_name');
						$homeland_other_properties_header_label = !empty($homeland_other_properties_header) ? $homeland_other_properties_header : __('Other Properties', 'codeex_theme_name');

						if (have_posts()) : 
							if ( post_password_required() ) :
								?><div class="password-protect-content"><?php the_content(); ?></div><?php
							else :
								while (have_posts()) : the_post(); ?>
									<article id="post-<?php the_ID(); ?>" <?php post_class('plist'); ?>>
										<?php 
											$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
											$homeland_price = esc_attr( get_post_meta( $post->ID, 'homeland_price', true ) );
											$homeland_thumbnails = esc_attr( get_post_meta( $post->ID, 'homeland_thumbnails', true ) );
											$homeland_area = esc_attr( get_post_meta($post->ID, 'homeland_area', true) );
											$homeland_area_unit = esc_attr( get_post_meta( $post->ID, 'homeland_area_unit', true ) );
											$homeland_floor_area = esc_attr( get_post_meta( $post->ID, 'homeland_floor_area', true ) );
											$homeland_floor_area_unit = esc_attr( get_post_meta( $post->ID, 'homeland_floor_area_unit', true ) );
											$homeland_room = esc_attr( get_post_meta($post->ID, 'homeland_room', true) );
											$homeland_bedroom = esc_attr( get_post_meta($post->ID, 'homeland_bedroom', true) );
											$homeland_bathroom = esc_attr( get_post_meta($post->ID, 'homeland_bathroom', true) );
											$homeland_garage = esc_attr( get_post_meta($post->ID, 'homeland_garage', true) );
											$homeland_year_built = esc_attr( get_post_meta($post->ID, 'homeland_year_built', true) );
											$homeland_stories = esc_attr( get_post_meta($post->ID, 'homeland_stories', true) );
											$homeland_basement = esc_attr( get_post_meta($post->ID, 'homeland_basement', true) );
											$homeland_structure_type = esc_attr( get_post_meta($post->ID, 'homeland_structure_type', true) );
											$homeland_roofing = esc_attr( get_post_meta($post->ID, 'homeland_roofing', true) );
											$homeland_address = esc_attr( get_post_meta($post->ID, 'homeland_address', true) );
											$homeland_property_id = esc_attr( get_post_meta($post->ID, 'homeland_property_id', true) );
											$homeland_zip = esc_attr( get_post_meta($post->ID, 'homeland_zip', true) );
											$homeland_property_hide_map = esc_attr( get_post_meta($post->ID, 'homeland_property_hide_map', true) );
											$homeland_featured = esc_attr( get_post_meta($post->ID, 'homeland_featured', true) );
											$homeland_lat = esc_attr( get_post_meta( $post->ID, 'homeland_property_lat', true ) );
											$homeland_lng = esc_attr( get_post_meta( $post->ID, 'homeland_property_lng', true ) );
											$homeland_rev_slider = esc_attr( get_post_meta($post->ID, 'homeland_rev_slider', true) );
											$homeland_property_type = get_the_term_list( $post->ID, 'homeland_property_type', ' ', ', ', '' ); 
											$homeland_property_status = get_the_term_list( $post->ID, 'homeland_property_status', ' ', ', ', '');
											$homeland_property_amenities = get_the_term_list( $post->ID, 'homeland_property_amenities', ' ', ' ', '');
											$homeland_agent_mobile = get_the_author_meta( 'homeland_mobile' );
											$homeland_agent_telno = get_the_author_meta( 'homeland_telno' );
											$homeland_agent_fax = get_the_author_meta( 'homeland_fax' );
											$homeland_agent_id = get_the_author_meta( 'ID' );
											$homeland_agent_desc = get_the_author_meta( 'description' );
											$homeland_agent_fname = get_the_author_meta( 'user_firstname' );
											$homeland_agent_lname = get_the_author_meta( 'user_lastname' ); 
											$homeland_agent_facebook = get_the_author_meta( 'homeland_facebook' );
											$homeland_agent_gplus = get_the_author_meta( 'homeland_gplus' );
											$homeland_agent_linkedin= get_the_author_meta( 'homeland_linkedin' );
											$homeland_agent_twitter = get_the_author_meta( 'homeland_twitter' );
											$homeland_agent_email = get_the_author_meta( 'user_email' );
											$homeland_agent_desc_trim = wp_trim_words( $homeland_agent_desc, $homeland_num_words = 60, $homeland_more = null );
											$homeland_custom_avatar = get_the_author_meta( 'homeland_custom_avatar', $wp_query->ID );
											$homeland_thumb_id = get_post_thumbnail_id( get_the_ID() );
											$homeland_large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
											$homeland_feature_image_caption = get_post( get_post_thumbnail_id())->post_excerpt;
												
											$args = array( 
												'post_type' => 'attachment', 
												'numberposts' => -1, 
												'order' => $homeland_attachment_order, 
												'orderby' => $homeland_attachment_orderby, 
												'post_status' => null, 
												'post_parent' => $post->ID, 
												'exclude' => $homeland_thumb_id
											);
											
											$homeland_attachments = get_posts( $args );									
										?>

										<div class="property-page-info clear">
											<?php
												if(!empty($homeland_property_id)) : ?>
													<div class="property-page-id">
														<?php _e( 'Property ID : ', 'codeex_theme_name' ); ?>
														<span><?php echo $homeland_property_id; ?></span>
													</div><?php
												endif;

												if(!empty( $homeland_property_type )) : ?>
													<div class="property-page-type"><?php echo $homeland_property_type; ?></div><?php
												endif;
												
												if(!empty( $homeland_property_status )) : ?>
													<div class="property-page-status">
														<span><?php echo $homeland_property_status; ?></span>
													</div><?php
												endif; 
											?>	
											<div class="print-property">
												<a href="javascript:window.print()" class="property-print" title="Print"><i class="fa fa-print"></i></a>
											</div>
										</div>

										<!--calling revolution slider or flexslider-->
										<?php
											if(shortcode_exists("rev_slider") && !empty($homeland_rev_slider)) : 
												echo(do_shortcode('[rev_slider '.$homeland_rev_slider.']'));
											else : ?>
												<div class="properties-flexslider flex-loading">
													<?php
														if(!empty($homeland_featured)) :
															?><div class="featured-ribbon"><i class="fa fa-star" title="Featured"></i></div><?php
														endif;
													?>
													<ul class="slides">
														<?php
															if( empty($homeland_properties_thumb_slider) ) :
																if ( has_post_thumbnail() ) : ?>
																	<li>
																		<a href="<?php echo esc_url( $homeland_large_image_url[0] ); ?>" rel="gallery" title="<?php _e( 'View Fullscreen', 'codeex_theme_name' ); ?>">
																			<?php the_post_thumbnail($homeland_property_slide_image_size); ?>
																		</a>
																		<?php
																			if(!empty($homeland_feature_image_caption)) :
																				echo "<span class='flex-caption'>". $homeland_feature_image_caption ."</span>";
																			endif;
																		?>
																	</li><?php
																endif;
															endif;

															if ( $homeland_attachments ) :						
																foreach ( $homeland_attachments as $homeland_attachment ) :
																	$homeland_attachment_id = $homeland_attachment->ID;
																	$homeland_attachment_caption = $homeland_attachment->post_excerpt;
																	$homeland_large_image_url = wp_get_attachment_image_src( $homeland_attachment_id, 'full' ); 
																	?>
																		<li>
																			<a href="<?php echo esc_url( $homeland_large_image_url[0] ); ?>" rel="gallery" title="<?php _e( 'View Fullscreen', 'codeex_theme_name' ); ?>">
																				<?php 
																					echo wp_get_attachment_image( $homeland_attachment->ID, $homeland_property_slide_image_size ); 
																				?>
																			</a>
																			<?php
																				if(!empty($homeland_attachment_caption)) :
																					echo "<span class='flex-caption'>" . $homeland_attachment_caption . "</span>";
																				endif;
																			?>
																		</li>
																	<?php	
																endforeach;
															endif; 
														?>
													</ul>	
												</div>

												<?php
													if(empty($homeland_thumbnails)) : ?>
														<div id="carousel-flex" class="properties-flexslider">
															<ul class="slides">
																<?php
																	if( empty($homeland_properties_thumb_slider) ) :
																		if ( has_post_thumbnail() ) : ?>
																			<li><?php the_post_thumbnail('homeland_property_thumb'); ?></li><?php
																		endif;
																	endif;

																	if ( $homeland_attachments ) :						
																		foreach ( $homeland_attachments as $homeland_attachment ) : 
																			echo '<li>' . wp_get_attachment_image( $homeland_attachment->ID, 'homeland_property_thumb' ) . '</li>';
																		endforeach;
																	endif;
																?>		
															</ul>
														</div><?php
													else :
														echo "<div style='margin-bottom: 30px;'></div>";
													endif;
											endif;

											if(!empty($homeland_price)) : ?>
												<span class="property-page-price"><?php homeland_property_price_format(); ?></span><?php
											endif;	
										?>

										<!--property agent informations-->
										<div class="property-info-agent clear">
											<?php
												if(!empty($homeland_area)) : ?>
													<span>
														<i class="fa fa-expand"></i>
														<strong><?php _e( 'Lot Area', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_area; echo "&nbsp;" . $homeland_area_unit; ?>
													</span><?php
												endif;
												if(!empty($homeland_floor_area)) : ?>
													<span>
														<i class="fa fa-arrows-alt"></i>
														<strong><?php _e( 'Floor Area', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_floor_area; echo "&nbsp;" . $homeland_floor_area_unit; ?>
													</span><?php
												endif;
												if(!empty($homeland_room)) : ?>
													<span>
														<i class="fa fa-home"></i>
														<strong><?php _e( 'Rooms', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_room; ?>
													</span><?php
												endif;
												if(!empty($homeland_bedroom)) : ?>
													<span>
														<i class="fa fa-bed"></i>
														<strong><?php _e( 'Bedrooms', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_bedroom; ?>
													</span><?php
												endif;
												if(!empty($homeland_bathroom)) : ?>
													<span>
														<i class="fa fa-male"></i>
														<strong><?php _e( 'Bathrooms', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_bathroom; ?> 
													</span><?php
												endif;
												if(!empty($homeland_garage)) : ?>
													<span>
														<i class="fa fa-car"></i>
														<strong><?php _e( 'Garage', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_garage; ?> 
													</span><?php
												endif;
												if(!empty($homeland_year_built)) : ?>
													<span>
														<i class="fa fa-calendar"></i>
														<strong><?php _e( 'Year Built', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_year_built; ?> 
													</span><?php
												endif;
												if(!empty($homeland_stories)) : ?>
													<span>
														<i class="fa fa-building"></i>
														<strong><?php _e( 'Stories', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_stories; ?>
													</span><?php
												endif;
												if(!empty($homeland_basement)) : ?>
													<span>
														<i class="fa fa-cubes"></i>
														<strong><?php _e( 'Basement', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_basement; ?>
													</span><?php
												endif;
												if(!empty($homeland_structure_type)) : ?>
													<span>
														<i class="fa fa-sitemap"></i>
														<strong><?php _e( 'Structure Type', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_structure_type; ?>
													</span><?php
												endif;
												if(!empty($homeland_roofing)) : ?>
													<span>
														<i class="fa fa-sort-asc"></i>
														<strong><?php _e( 'Roofing', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_roofing; ?>
													</span><?php
												endif;
												if(!empty($homeland_zip)) : ?>
													<span>
														<i class="fa fa-map-marker"></i>
														<strong><?php _e( 'Zip Code', 'codeex_theme_name' ); echo ":"; ?></strong>
														<?php echo $homeland_zip; ?>
													</span><?php
												endif;
											?>
										</div>

										<?php 
											the_content(); 
											
											//Amenities
											if(!empty($homeland_property_amenities)) : ?>
												<div class="property-amenities">
													<h4><?php echo $homeland_property_amenities_header_label; ?></h4>
													<?php 
														if(empty($homeland_clickable_amenities)) :
															$homeland_terms = get_the_terms( $post->ID, 'homeland_property_amenities');
															$homeland_count = count($homeland_terms);
															echo "<div class='property-amenities'>";
															if ( $homeland_count > 0 ) :
																foreach ( $homeland_terms as $homeland_term ) :
															    	echo "<span class='amenities-list'>" . $homeland_term->name . "</span>";
															   endforeach;
															endif;
															echo "</div>";
														else :
															echo $homeland_property_amenities; 
														endif;
													?>
												</div><?php
											endif;

											//Google Map
											if(empty($homeland_property_hide_map)) :
												if(empty($homeland_hide_map)) : 
													echo "<h4>" . $homeland_property_map_header_label . "</h4><section id='map-property'></section>";

													if(!empty($homeland_show_street_view)) :
														echo "<section id='map-property-street'></section>";
													endif;
												endif;
											endif;
										?>
									</article><?php 
									
									homeland_social_share(); //modify function in "includes/lib/custom-functions.php"...
								endwhile; 

								if(empty($homeland_all_agents)) :
									if(empty($homeland_agent_info)) :
										?>
											<div class="clear">
												<!--AGENT-->
												<div class="agent-list clear">
										    		<!--AGENT IMAGE and SOCIAL-->
										    		<div class="agent-image">
										    			<div class="grid cs-style-3">	
										    				<figure class="pimage">
										    					<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>">
										    						<?php 
										    							if(!empty($homeland_custom_avatar)) : 
																			echo '<img src="'.$homeland_custom_avatar.'" />';
																		else : echo get_avatar( $homeland_agent_id, 230 );
																		endif;
																	?>
										    					</a>
										    					<figcaption>
										    						<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>"><i class="fa fa-link fa-lg"></i></a>
										    					</figcaption>
										    				</figure>
										    			</div>
										    			<div class="agent-social">
										    				<ul class="clear">
										    					<?php
										    						if(!empty($homeland_agent_facebook)) : ?>
										    							<li>
										    								<a href="<?php echo $homeland_agent_facebook; ?>" target="_blank">
										    									<i class="fa fa-facebook fa-lg"></i>
										    								</a>
										    							</li><?php
										    						endif;

										    						if(!empty($homeland_agent_gplus)) : ?>
										    							<li>
										    								<a href="<?php echo $homeland_agent_gplus; ?>" target="_blank">
										    									<i class="fa fa-google-plus fa-lg"></i>
										    								</a>
										    							</li><?php
										    						endif;

										    						if(!empty($homeland_agent_linkedin)) : ?>
										    							<li>
										    								<a href="<?php echo $homeland_agent_linkedin; ?>" target="_blank">
										    									<i class="fa fa-linkedin fa-lg"></i>
										    								</a>
										    							</li><?php
										    						endif;

										    						if(!empty($homeland_agent_twitter)) : ?>
										    							<li>
										    								<a href="<?php echo $homeland_agent_twitter; ?>" target="_blank">
										    									<i class="fa fa-twitter fa-lg"></i>
										    								</a>
										    							</li><?php
										    						endif;

										    						if(!empty($homeland_agent_email)) : ?>
										    							<li class="last">
										    								<a href="mailto:<?php echo $homeland_agent_email; ?>" target="_blank">
										    									<i class="fa fa-envelope-o fa-lg"></i>
										    								</a>
										    							</li><?php
										    						endif;
										    					?>
										    				</ul>
										    			</div>
										    		</div>

										    		<!--AGENT DESCRIPTIONS-->
										    		<div class="agent-desc">
										    			<h4>
										    				<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>">
										    					<?php 
										    						echo $homeland_agent_fname; echo "&nbsp;"; 
										    						echo $homeland_agent_lname;
										    					?>
										    				</a>
										    			</h4>
										    			<?php echo wpautop ( $homeland_agent_desc_trim ); ?>
										    			<label class="more-info">
										    				<?php
										    					if(!empty($homeland_agent_telno)) : ?>
										    						<span>
												    					<i class="fa fa-phone"></i>
												    					<strong>
												    						<?php _e( 'Tel no', 'codeex_theme_name' ); echo ":"; ?>
												    					</strong> <?php echo $homeland_agent_telno; ?>
												    				</span><?php
										    					endif;

										    					if(!empty($homeland_agent_mobile)) : ?>
										    						<span>
												    					<i class="fa fa-mobile"></i>
												    					<strong>
												    						<?php _e( 'Mobile', 'codeex_theme_name' ); echo ":"; ?>
												    					</strong> <?php echo $homeland_agent_mobile; ?>
												    				</span><?php
										    					endif;

										    					if(!empty($homeland_agent_fax)) : ?>
										    						<span>
												    					<i class="fa fa-print"></i>
												    					<strong>
												    						<?php _e( 'Fax', 'codeex_theme_name' ); echo ":"; ?>
												    					</strong> <?php echo $homeland_agent_fax; ?>
												    				</span><?php
										    					endif;
										    				?>
										    			</label>
											    		<a href="<?php echo esc_url( get_author_posts_url( $homeland_agent_id ) ); ?>" class="view-profile">
											    			<?php 
											    				if(!empty( $homeland_agent_button )) : echo $homeland_agent_button;
											    				else : _e( 'View Profile', 'codeex_theme_name' ); 
											    				endif;
											    			?>
											    		</a>
										    		</div>
										    	</div>	

										    	<!--AGENT FORM-->
										    	<div class="agent-form">
										    		<h4><?php echo $homeland_agent_form_label; ?></h4>
										    		<?php
										    			//if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) :
										    			if(isset($_POST['btsend'])) :
										    				$homeland_property_link = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
										    				$homeland_receiver = get_the_author_meta( 'email' );
										    				
															if(!isset($hasError)) :
																$fname = trim($_POST['fname']);
																$email = trim($_POST['email']);
																if(function_exists('stripslashes')) : $message = stripslashes(trim($_POST['message']));
																else : $message = trim($_POST['message']);
																endif;

																$emailTo = $homeland_receiver; 
																$subject = "Prospect Clients";
																$body = "Name: " . $fname . "\n\n";
																$body .= "Email: " . $email . "\n\n";
																$body .= "Message: " . $message . "\n\n";
																$body .= "Property Link: " . $homeland_property_link . "\n\n";
																$headers = "From: " . $fname . " <" . $email . ">\n";
																$headers .= "Content-Type: text/plain; charset=UTF-8\n";
													        	$headers .= "Content-Transfer-Encoding: 8bit\n";
																
																wp_mail( $emailTo, $subject, $body, $headers );		
																$homeland_emailSent = true;

															endif;
														endif;

														if(isset($homeland_emailSent) && $homeland_emailSent == true) : ?>
															<label class="sent">
																<?php _e( 'You have successfully send your message to this agent!', 'codeex_theme_name' ); ?>
															</label><?php 
														endif; 

										    		?>
										    		<form id="agent-form" action="<?php the_permalink(); ?>#agent-form" method="post">
										    			<input name="permalink" type="hidden" value="<?php the_permalink(); ?>" />
										    			<ul>
										    				<li><input name="fname" type="text" class="required" placeholder="<?php _e( 'Name', 'codeex_theme_name' ); ?>" /></li>
										    				<li><input name="email" type="email" class="required email" placeholder="<?php _e( 'Email Address', 'codeex_theme_name' ); ?>" /></li>
										    				<li><textarea name="message" class="required" placeholder="<?php _e( 'Message', 'codeex_theme_name' ); ?>" /></textarea></li>
										    				<li><input name="btsend" type="submit" value="<?php _e( 'Send', 'codeex_theme_name' ); ?>" /></li>
										    			</ul>	
										    		</form>
										    	</div>
											</div>
										<?php
									endif;
								endif;

								if(empty($homeland_other_properties)) :
									echo "<div class='property-list clear'><h4>" . $homeland_other_properties_header_label . "</h4>";

										echo '<div class="property-four-cols clear">';
											$homeland_exclude_post = $post->ID;
											$args = array( 
												'post_type' => 'homeland_properties', 
												'orderby' => 'rand', 
												'post__not_in' => array($homeland_exclude_post), 
												'posts_per_page' => $homeland_other_property_limit 
											);
											$wp_query = new WP_Query( $args );	

											if ($wp_query->have_posts()) :
												echo '<div class="grid cs-style-3 masonry">';
													echo '<ul class="clear">';
														for($homeland_i = 1; $wp_query->have_posts(); $homeland_i++) {
															$wp_query->the_post();		

															if($homeland_single_property_layout == "Fullwidth") : $homeland_columns = 4;	
															else : $homeland_columns = 3;	
															endif;

															$homeland_class = 'property-cols masonry-item ';
															$homeland_class .= ($homeland_i % $homeland_columns == 0) ? 'last' : '';
															
															get_template_part( 'loop', 'property-4cols' );
														}
													echo '</ul>';
												echo '</div>';
											endif;
											wp_reset_query();	
										echo '</div>';
									echo '</div>';
								endif;

								
								if(empty($homeland_hide_property_comments)) :
									comments_template(); 
								endif;

								?>

								<!--NEXT/PREV NAV-->
						    	<div class="post-link-blog clear">
									<span class="prev">
										<?php 
											previous_post_link( '%link', '&larr;&nbsp;' . __( 'Previous Property', 'codeex_theme_name' ), '' ); 
										?>
									</span>
									<span class="next">
										<?php next_post_link( '%link', __( 'Next Property', 'codeex_theme_name' ) . '&nbsp;&rarr;', '' ); ?>
									</span>
								</div><?php
							endif;
						endif;	
					?>	
				</div>
			</div>

			<?php
				//Sidebar
				if($homeland_single_property_layout != "Fullwidth") : 
					?><div class="<?php echo $homeland_property_class_sidebar; ?>"><?php get_sidebar(); ?></div><?php
				endif;
			?>
		</div>
	</section>

<?php get_footer(); ?>