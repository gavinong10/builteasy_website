<?php
	/**********************************************
	CUSTOM POST TYPE: SERVICES
	***********************************************/	
	
	if ( ! function_exists( 'homeland_services_post_type' ) ) :
		function homeland_services_post_type() {

			register_post_type( 'homeland_services',
				array(
					'labels' => array(
						'name' => __( 'Services', 'codeex_theme_name' ),
						'singular_name' => __( 'Services', 'codeex_theme_name' ),
						'add_new' => __( 'Add New', 'codeex_theme_name' ),
						'add_new_item' => __( 'Add New Services', 'codeex_theme_name' ),
						'edit_item' => __( 'Edit Services', 'codeex_theme_name' ),
						'search_items' => __( 'Search Services', 'codeex_theme_name' ),
						'not_found' => __( 'No services found.', 'codeex_theme_name' ),
						'not_found_in_trash' => __( 'No services found in Trash.', 'codeex_theme_name' ),
					),
					'public' => true,
					'has_archive' => true,	
					'exclude_from_search' => true,
					'rewrite' => array('slug' => __( 'services-item', 'codeex_theme_name' ), 'with_front' => TRUE),
					'supports' => array('title','editor', 'author', 'page-attributes', 'thumbnail', 'custom-fields', 'excerpt'),
					'menu_icon' => 'dashicons-admin-generic',			
				)
			);
		}	
	endif;
	add_action( 'init', 'homeland_services_post_type' );


	/*----------------------------
	MetaBoxes
	----------------------------*/

	if ( ! function_exists( 'homeland_services_meta' ) ) :
		function homeland_services_meta() {
			global $post;

			$homeland_advance_search = sanitize_text_field( get_post_meta($post->ID, 'homeland_advance_search', TRUE) );
			$homeland_icon = sanitize_text_field( get_post_meta($post->ID, 'homeland_icon', TRUE) );
			$homeland_hdimage = sanitize_text_field( get_post_meta($post->ID, 'homeland_hdimage', TRUE) );
			$homeland_bgimage = sanitize_text_field( get_post_meta($post->ID, 'homeland_bgimage', TRUE) );
			$homeland_custom_link = sanitize_text_field( get_post_meta($post->ID, 'homeland_custom_link', TRUE) );
			$homeland_custom_icon = sanitize_text_field( get_post_meta($post->ID, 'homeland_custom_icon', TRUE) );

			?>

				<div class="mabuc-form-wrap">

					<!-- Tabs -->
					<ul class="mabuc-tabs">
						<li class="mabuc-tab-link current" data-tab="tab-1">
							<i class="fa fa-home"></i><?php _e( 'Main Information', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-2">
							<i class="fa fa-circle"></i><?php _e( 'Icon', 'codeex_theme_name' ); ?>
						</li>
						<li class="mabuc-tab-link" data-tab="tab-3">
							<i class="fa fa-image"></i><?php _e( 'Images', 'codeex_theme_name' ); ?>
						</li>
					</ul>

					<!-- Main Information -->
					<div id="tab-1" class="mabuc-tab-content current">
						<ul>
							<li>
								<label for="homeland_advance_search"><?php esc_attr( _e( 'Hide Search', 'codeex_theme_name' ) ); ?></label>
								<input name="homeland_advance_search" type="checkbox" id="homeland_advance_search" <?php if( $homeland_advance_search == true ) { ?>checked="checked"<?php } ?> /><br>
								<span class="desc"><?php esc_attr( _e( 'Tick the box to hide advance search in this post', 'codeex_theme_name' ) ); ?></span>
							</li>
							<li>
								<label for="homeland_custom_link">
									<?php _e( 'Custom Link', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_custom_link" type="text" id="homeland_custom_link" value="<?php echo esc_attr( $homeland_custom_link ); ?>" /><br>
								<span class="desc"><?php _e( 'Provide your custom services link here', 'codeex_theme_name' ); ?></span>
							</li>
						</ul>
					</div>

					<!-- Retina Icons -->
					<div id="tab-2" class="mabuc-tab-content">
						<ul>
							<li>
								<label for="homeland_icon"><?php _e( 'Retina Icon', 'codeex_theme_name' ); ?></label>
								<input id="homeland_icon" name="homeland_icon" type="text" value="<?php echo esc_attr( $homeland_icon ); ?>" /><br>
								<span class="desc"><?php _e( 'Select your services icon below, just click on the icons', 'codeex_theme_name' ); ?></span>
							</li>
							<li>
								<?php
									$homeland_fontawesome_icons = array('fa-500px','fa-amazon','fa-balance-scale','fa-battery-0','fa-battery-1','fa-battery-2','fa-battery-3','fa-battery-4','fa-battery-empty','fa-battery-full','fa-battery-half','fa-battery-quarter','fa-battery-three-quarters','fa-black-tie','fa-calendar-check-o','fa-calendar-minus-o','fa-calendar-plus-o','fa-calendar-times-o','fa-cc-diners-club','fa-cc-jcb','fa-chrome','fa-clone','fa-commenting','fa-commenting-o','fa-contao','fa-creative-commons','fa-expeditedssl','fa-firefox','fa-fonticons','fa-get-pocket','fa-gg','fa-gg-circle','fa-hand-grab-o','fa-hand-lizard-o','fa-hand-paper-o','fa-hand-peace-o','fa-hand-pointer-o','fa-hand-rock-o','fa-hand-scissors-o','fa-hand-spock-o','fa-hand-stop-o','fa-hourglass','fa-hourglass-1','fa-hourglass-2','fa-hourglass-3','fa-hourglass-end','fa-hourglass-half','fa-hourglass-o','fa-hourglass-start','fa-houzz','fa-i-cursor','fa-industry','fa-internet-explorer','fa-map','fa-map-o','fa-map-pin','fa-map-signs','fa-mouse-pointer','fa-object-group','fa-object-ungroup','fa-odnoklassniki','fa-odnoklassniki-square','fa-opencart','fa-opera','fa-optin-monster','fa-registered','fa-safari','fa-sticky-note','fa-sticky-note-o','fa-television','fa-trademark','fa-tripadvisor','fa-tv','fa-vimeo','fa-wikipedia-w','fa-y-combinator','fa-yc','fa-glass','fa-music','fa-search','fa-envelope-o','fa-heart','fa-star','fa-star-o','fa-user','fa-film','fa-th-large','fa-th','fa-th-list','fa-check','fa-remove','fa-close','fa-times','fa-search-plus','fa-search-minus','fa-power-off','fa-signal','fa-gear','fa-cog','fa-trash-o','fa-home','fa-file-o','fa-clock-o','fa-road','fa-download','fa-arrow-circle-o-down','fa-arrow-circle-o-up','fa-inbox','fa-play-circle-o','fa-rotate-right','fa-repeat','fa-refresh','fa-list-alt','fa-lock','fa-flag','fa-headphones','fa-volume-off','fa-volume-down','fa-volume-up','fa-qrcode','fa-barcode','fa-tag','fa-tags','fa-book','fa-bookmark','fa-print','fa-camera','fa-font','fa-bold','fa-italic','fa-text-height','fa-text-width','fa-align-left','fa-align-center','fa-align-right','fa-align-justify','fa-list','fa-dedent','fa-outdent','fa-indent','fa-video-camera','fa-photo','fa-image','fa-picture-o','fa-pencil','fa-map-marker','fa-adjust','fa-tint','fa-edit','fa-pencil-square-o','fa-share-square-o','fa-check-square-o','fa-arrows','fa-step-backward','fa-fast-backward','fa-backward','fa-play','fa-pause','fa-stop','fa-forward','fa-fast-forward','fa-step-forward','fa-eject','fa-chevron-left','fa-chevron-right','fa-plus-circle','fa-minus-circle','fa-times-circle','fa-check-circle','fa-question-circle','fa-info-circle','fa-crosshairs','fa-times-circle-o','fa-check-circle-o','fa-ban','fa-arrow-left','fa-arrow-right','fa-arrow-up','fa-arrow-down','fa-mail-forward','fa-share','fa-expand','fa-compress','fa-plus','fa-minus','fa-asterisk','fa-exclamation-circle','fa-gift','fa-leaf','fa-fire','fa-eye','fa-eye-slash','fa-warning','fa-exclamation-triangle','fa-plane','fa-calendar','fa-random','fa-comment','fa-magnet','fa-chevron-up','fa-chevron-down','fa-retweet','fa-shopping-cart','fa-folder','fa-folder-open','fa-arrows-v','fa-arrows-h','fa-bar-chart-o','fa-bar-chart','fa-twitter-square','fa-facebook-square','fa-camera-retro','fa-key','fa-gears','fa-cogs','fa-comments','fa-thumbs-o-up','fa-thumbs-o-down','fa-star-half','fa-heart-o','fa-sign-out','fa-linkedin-square','fa-thumb-tack','fa-external-link','fa-sign-in','fa-trophy','fa-github-square','fa-upload','fa-lemon-o','fa-phone','fa-square-o','fa-bookmark-o','fa-phone-square','fa-twitter','fa-facebook-f','fa-facebook','fa-github','fa-unlock','fa-credit-card','fa-rss','fa-hdd-o','fa-bullhorn','fa-bell','fa-certificate','fa-hand-o-right','fa-hand-o-left','fa-hand-o-up','fa-hand-o-down','fa-arrow-circle-left','fa-arrow-circle-right','fa-arrow-circle-up','fa-arrow-circle-down','fa-globe','fa-wrench','fa-tasks','fa-filter','fa-briefcase','fa-arrows-alt','fa-group','fa-users','fa-chain','fa-link','fa-cloud','fa-flask','fa-cut','fa-scissors','fa-copy','fa-files-o','fa-paperclip','fa-save','fa-floppy-o','fa-square','fa-navicon','fa-reorder','fa-bars','fa-list-ul','fa-list-ol','fa-strikethrough','fa-underline','fa-table','fa-magic','fa-truck','fa-pinterest','fa-pinterest-square','fa-google-plus-square','fa-google-plus','fa-money','fa-caret-down','fa-caret-up','fa-caret-left','fa-caret-right','fa-columns','fa-unsorted','fa-sort','fa-sort-down','fa-sort-desc','fa-sort-up','fa-sort-asc','fa-envelope','fa-linkedin','fa-rotate-left','fa-undo','fa-legal','fa-gavel','fa-dashboard','fa-tachometer','fa-comment-o','fa-comments-o','fa-flash','fa-bolt','fa-sitemap','fa-umbrella','fa-paste','fa-clipboard','fa-lightbulb-o','fa-exchange','fa-cloud-download','fa-cloud-upload','fa-user-md','fa-stethoscope','fa-suitcase','fa-bell-o','fa-coffee','fa-cutlery','fa-file-text-o','fa-building-o','fa-hospital-o','fa-ambulance','fa-medkit','fa-fighter-jet','fa-beer','fa-h-square','fa-plus-square','fa-angle-double-left','fa-angle-double-right','fa-angle-double-up','fa-angle-double-down','fa-angle-left','fa-angle-right','fa-angle-up','fa-angle-down','fa-desktop','fa-laptop','fa-tablet','fa-mobile-phone','fa-mobile','fa-circle-o','fa-quote-left','fa-quote-right','fa-spinner','fa-circle','fa-mail-reply','fa-reply','fa-github-alt','fa-folder-o','fa-folder-open-o','fa-smile-o','fa-frown-o','fa-meh-o','fa-gamepad','fa-keyboard-o','fa-flag-o','fa-flag-checkered','fa-terminal','fa-code','fa-mail-reply-all','fa-reply-all','fa-star-half-empty','fa-star-half-full','fa-star-half-o','fa-location-arrow','fa-crop','fa-code-fork','fa-unlink','fa-chain-broken','fa-question','fa-info','fa-exclamation','fa-superscript','fa-subscript','fa-eraser','fa-puzzle-piece','fa-microphone','fa-microphone-slash','fa-shield','fa-calendar-o','fa-fire-extinguisher','fa-rocket','fa-maxcdn','fa-chevron-circle-left','fa-chevron-circle-right','fa-chevron-circle-up','fa-chevron-circle-down','fa-html5','fa-css3','fa-anchor','fa-unlock-alt','fa-bullseye','fa-ellipsis-h','fa-ellipsis-v','fa-rss-square','fa-play-circle','fa-ticket','fa-minus-square','fa-minus-square-o','fa-level-up','fa-level-down','fa-check-square','fa-pencil-square','fa-external-link-square','fa-share-square','fa-compass','fa-toggle-down','fa-caret-square-o-down','fa-toggle-up','fa-caret-square-o-up','fa-toggle-right','fa-caret-square-o-right','fa-euro','fa-eur','fa-gbp','fa-dollar','fa-usd','fa-rupee','fa-inr','fa-cny','fa-rmb','fa-yen','fa-jpy','fa-ruble','fa-rouble','fa-rub','fa-won','fa-krw','fa-bitcoin','fa-btc','fa-file','fa-file-text','fa-sort-alpha-asc','fa-sort-alpha-desc','fa-sort-amount-asc','fa-sort-amount-desc','fa-sort-numeric-asc','fa-sort-numeric-desc','fa-thumbs-up','fa-thumbs-down','fa-youtube-square','fa-youtube','fa-xing','fa-xing-square','fa-youtube-play','fa-dropbox','fa-stack-overflow','fa-instagram','fa-flickr','fa-adn','fa-bitbucket','fa-bitbucket-square','fa-tumblr','fa-tumblr-square','fa-long-arrow-down','fa-long-arrow-up','fa-long-arrow-left','fa-long-arrow-right','fa-apple','fa-windows','fa-android','fa-linux','fa-dribbble','fa-skype','fa-foursquare','fa-trello','fa-female','fa-male','fa-gittip','fa-gratipay','fa-sun-o','fa-moon-o','fa-archive','fa-bug','fa-vk','fa-weibo','fa-renren','fa-pagelines','fa-stack-exchange','fa-arrow-circle-o-right','fa-arrow-circle-o-left','fa-toggle-left','fa-caret-square-o-left','fa-dot-circle-o','fa-wheelchair','fa-vimeo-square','fa-turkish-lira','fa-try','fa-plus-square-o','fa-space-shuttle','fa-slack','fa-envelope-square','fa-wordpress','fa-openid','fa-institution','fa-bank','fa-university','fa-mortar-board','fa-graduation-cap','fa-yahoo','fa-google','fa-reddit','fa-reddit-square','fa-stumbleupon-circle','fa-stumbleupon','fa-delicious','fa-digg','fa-pied-piper','fa-pied-piper-alt','fa-drupal','fa-joomla','fa-language','fa-fax','fa-building','fa-child','fa-paw','fa-spoon','fa-cube','fa-cubes','fa-behance','fa-behance-square','fa-steam','fa-steam-square','fa-recycle','fa-automobile','fa-car','fa-cab','fa-taxi','fa-tree','fa-spotify','fa-deviantart','fa-soundcloud','fa-database','fa-file-pdf-o','fa-file-word-o','fa-file-excel-o','fa-file-powerpoint-o','fa-file-photo-o','fa-file-picture-o','fa-file-image-o','fa-file-zip-o','fa-file-archive-o','fa-file-sound-o','fa-file-audio-o','fa-file-movie-o','fa-file-video-o','fa-file-code-o','fa-vine','fa-codepen','fa-jsfiddle','fa-life-bouy','fa-life-buoy','fa-life-saver','fa-support','fa-life-ring','fa-circle-o-notch','fa-ra','fa-rebel','fa-ge','fa-empire','fa-git-square','fa-git','fa-hacker-news','fa-tencent-weibo','fa-qq','fa-wechat','fa-weixin','fa-send','fa-paper-plane','fa-send-o','fa-paper-plane-o','fa-history','fa-genderless','fa-circle-thin','fa-header','fa-paragraph','fa-sliders','fa-share-alt','fa-share-alt-square','fa-bomb','fa-soccer-ball-o','fa-futbol-o','fa-tty','fa-binoculars','fa-plug','fa-slideshare','fa-twitch','fa-yelp','fa-newspaper-o','fa-wifi','fa-calculator','fa-paypal','fa-google-wallet','fa-cc-visa','fa-cc-mastercard','fa-cc-discover','fa-cc-amex','fa-cc-paypal','fa-cc-stripe','fa-bell-slash','fa-bell-slash-o','fa-trash','fa-copyright','fa-at','fa-eyedropper','fa-paint-brush','fa-birthday-cake','fa-area-chart','fa-pie-chart','fa-line-chart','fa-lastfm','fa-lastfm-square','fa-toggle-off','fa-toggle-on','fa-bicycle','fa-bus','fa-ioxhost','fa-angellist','fa-cc','fa-shekel','fa-sheqel','fa-ils','fa-meanpath','fa-buysellads','fa-connectdevelop','fa-dashcube','fa-forumbee','fa-leanpub','fa-sellsy','fa-shirtsinbulk','fa-simplybuilt','fa-skyatlas','fa-cart-plus','fa-cart-arrow-down','fa-diamond','fa-ship','fa-user-secret','fa-motorcycle','fa-street-view','fa-heartbeat','fa-venus','fa-mars','fa-mercury','fa-transgender','fa-transgender-alt','fa-venus-double','fa-mars-double','fa-venus-mars','fa-mars-stroke','fa-mars-stroke-v','fa-mars-stroke-h','fa-neuter','fa-facebook-official','fa-pinterest-p','fa-whatsapp','fa-server','fa-user-plus','fa-user-times','fa-hotel','fa-bed','fa-viacoin','fa-train','fa-subway','fa-medium');
								?>
								<ul class="the-icons">	
									<?php
										foreach ($homeland_fontawesome_icons as $homeland_service_icons) : 
											echo "<li><span><i class='fa ". $homeland_service_icons ."'></i>" . $homeland_service_icons . "</span></li>";
										endforeach;
									?>
								</ul>
							</li>
							<li>
								<label for="homeland_custom_icon">
									<?php _e( 'Custom Icon', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_custom_icon" type="text" id="homeland_custom_icon" value="<?php echo esc_attr( $homeland_custom_icon ); ?>" /> <input id="upload_image_button_homeland_custom_icon" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc"><?php _e( 'Please upload custom icon by clicking upload button', 'codeex_theme_name' ); ?></span>
							</li>
						</ul>
					</div>

					<!-- Images -->
					<div id="tab-3" class="mabuc-tab-content">
						<ul>
							<li>
								<label for="homeland_hdimage">
									<?php esc_attr( _e( 'Header Image', 'codeex_theme_name' ) ); ?>
								</label>
								<input name="homeland_hdimage" type="text" id="homeland_hdimage" value="<?php echo esc_attr( $homeland_hdimage ); ?>" /> <input id="upload_image_button_homeland_hdimage" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc"><?php esc_attr( _e( 'Please upload header image. Otherwise default header image from theme options will be displayed', 'codeex_theme_name' ) ); ?></span>
							</li>
							<li>
								<label for="homeland_bgimage">
									<?php _e( 'Background Image', 'codeex_theme_name' ); ?>
								</label>
								<input name="homeland_bgimage" type="text" id="homeland_bgimage" value="<?php echo esc_attr( $homeland_bgimage ); ?>" /> <input id="upload_image_button_homeland_bgimage" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" /><br>
								<span class="desc"><?php esc_attr( _e( 'Please upload background image. Otherwise default background image from theme options will be displayed', 'codeex_theme_name' ) ); ?></span>
							</li>
						</ul>
					</div>
				</div>	

				<script type="text/javascript">
					(function($) {
						"use strict";	

						$('.the-icons li span').click(function(){  
							var thisValue = $(this).text(); 
							var thisTarget = $('#homeland_icon');  
							thisTarget.val(thisValue);      
							return false;                 
						});

					})(jQuery);
				</script>
			<?php
		}
	endif;


	/*----------------------------
	Custom Columns
	----------------------------*/

	if ( ! function_exists( 'homeland_edit_services_columns' ) ) :
		function homeland_edit_services_columns( $columns ) {
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => __( 'Title', 'codeex_theme_name' ),		
				'link' => __( 'Link', 'codeex_theme_name' ),		
				'icon' => __( 'Icon', 'codeex_theme_name' ),		
				'author' => __( 'Author', 'codeex_theme_name' ),
				'date' => __( 'Date', 'codeex_theme_name' )
			);
			return $columns;
		}
	endif;
	add_filter( 'manage_edit-homeland_services_columns', 'homeland_edit_services_columns' );


	if ( ! function_exists( 'homeland_manage_services_columns' ) ) :	
		function homeland_manage_services_columns( $column ) {
			global $post;

			$homeland_services_icon = get_post_meta( $post->ID, 'homeland_icon', true );
			$homeland_services_custom_icon = get_post_meta( $post->ID, 'homeland_custom_icon', true );
			$homeland_services_link = get_post_meta( $post->ID, 'homeland_custom_link', true );

			switch($column) {
				case 'icon' : 
					if(empty($homeland_services_custom_icon)) :
						echo "<i class='fa ". $homeland_services_icon ." fa-2x'></i>";
					else :
						echo "<img src='". $homeland_services_custom_icon ."' />";
					endif;
	   		break;

	   		case 'link' : 
					if(!empty($homeland_services_link)) :
						echo "<a href=". $homeland_services_link ." target='_blank'>". $homeland_services_link ."</a>";
					else :
						?><a href="<?php the_permalink(); ?>" target="_blank"><?php the_permalink(); ?></a><?php
					endif;
	   		break;

	   		default :
				break;
			}
		}
	endif;
	add_action( 'manage_homeland_services_posts_custom_column', 'homeland_manage_services_columns', 10, 2 );


	/*----------------------------
	Save and Update
	----------------------------*/
	
	if ( ! function_exists( 'homeland_custom_posts_services' ) ) :
		function homeland_custom_posts_services(){
			add_meta_box(
				"homeland_services_meta", 
				__( 'Services Options', 'codeex_theme_name' ), 
				"homeland_services_meta", 
				"homeland_services", 
				"normal", 
				"low"
			);	
		}
	endif;
	add_action( 'add_meta_boxes', 'homeland_custom_posts_services' );	
	
	
	if ( ! function_exists( 'homeland_custom_posts_save_services' ) ) :
		function homeland_custom_posts_save_services( $post_id ){
			if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX)) return;
			if ( 'page' == isset($_POST['post_type']) ) { if ( !current_user_can( 'edit_page', $post_id ) ) return;
			} else { if ( !current_user_can( 'edit_post', $post_id ) ) return; }

			$homeland_fields = array( 'homeland_advance_search', 'homeland_icon', 'homeland_hdimage', 'homeland_bgimage', 'homeland_custom_link', 'homeland_custom_icon' );

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
	add_action('save_post', 'homeland_custom_posts_save_services');
?>