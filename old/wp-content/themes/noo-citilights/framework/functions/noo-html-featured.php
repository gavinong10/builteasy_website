<?php

if (!function_exists('noo_get_featured_content')):
	function noo_get_featured_content($post_id = null, $post_type = '', $post_format = '') {
		
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$post_type = ('' === $post_type) ? get_post_type($post_id) : $post_type;
		$prefix = '';
		
		if ($post_type == 'post') {
			$prefix = '_noo_wp_post';
			$post_format = ('' === $post_format) ? get_post_format($post_id) : $post_format;
		}
		
		if ($post_type == 'portfolio_project') {
			$prefix = '_noo_portfolio';
			$post_format = ('' === $post_format) ? noo_get_post_meta($post_id, "{$prefix}_media_type", 'image') : $post_format;
		}
		
		switch ($post_format) {
			case 'image':
				return noo_get_featured_image($prefix, $post_id);
			case 'gallery':
				return noo_get_featured_gallery($prefix, $post_id);
			case 'video':
				return noo_get_featured_video($prefix, $post_id);
			case 'audio':
				return noo_get_featured_audio($prefix, $post_id);
			case 'quote':
				return noo_get_featured_quote($prefix, $post_id);
			case 'link':
				return noo_get_featured_link($prefix, $post_id);
			default: // standard post format
				return noo_get_featured_default($post_id);
		}
		
		return '';
	}
endif;

if (!function_exists('noo_featured_content')):
	function noo_featured_content($post_id = null, $post_type = '', $post_format = '') {
		echo noo_get_featured_content( $post_id, $post_type, $post_format );
	}
endif;

if (!function_exists('noo_get_featured_image')):
	function noo_get_featured_image($prefix = '_noo_wp_post', $post_id = null, $lightbox=false, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		$thumb = '';
		$post_thumbnail_id = 0;
		$main_image = noo_get_post_meta($post_id, "{$prefix}_main_image", 'featured');
		if( $main_image == 'featured') {
			//$thumb = get_the_post_thumbnail($post_id, get_thumbnail_width());
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		} else {
			if (!is_singular() || $is_shortcode) {
				$preview_content = noo_get_post_meta($post_id, "{$prefix}_image_preview", 'image');
				if ($preview_content == 'featured') {
					$post_thumbnail_id = get_post_thumbnail_id( $post_id );
					//$thumb = get_the_post_thumbnail($post_id, get_thumbnail_width());
				}
			}

			if(empty($thumb)) {
				$post_thumbnail_id = (int) noo_get_post_meta($post_id, "{$prefix}_image", '');
				
			}
		}
		
		$thumb = !empty($post_thumbnail_id) ? wp_get_attachment_image( $post_thumbnail_id, get_thumbnail_width()) : '';
		$post_thumbnail_src= '';
		if(!empty($post_thumbnail_id)){
			$image = wp_get_attachment_image_src($post_thumbnail_id,'full');
			$post_thumbnail_src = @$image[0];
		}
		if(!empty($thumb)) {
			if (!is_singular() || $lightbox) {
				$html[] = '<a class="content-thumb'.( $lightbox ? ' noo-lightbox-item':'').'" '.($lightbox ? 'data-lightbox-gallery="portfolio-gallery"':'').'  href="' .($lightbox ? $post_thumbnail_src : esc_url(get_permalink())) . '" title="' . esc_attr(sprintf(__('Permalink to: "%s"', 'noo') , the_title_attribute('echo=0'))) . '">';
				$html[] = $thumb;
				$html[] = '</a>';
			} else {
				$html[] = '<div class="content-thumb">';
				$html[] = $thumb;
				$html[] = '</div>';
			}
		}
		
		return implode($html, "\n");
	}
endif;

if (!function_exists('noo_featured_image')):
	function noo_featured_image($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_get_featured_image($prefix, $post_id);
	}
endif;

if (!function_exists('noo_get_featured_gallery')):
	function noo_get_featured_gallery($prefix = '_noo_wp_post', $post_id = null,$lightbox=false, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		$post_thumbnail_id = 0;
		
		if (!is_singular() || $is_shortcode) {
			$preview_content = noo_get_post_meta($post_id, "{$prefix}_gallery_preview", 'slideshow');
			if ($preview_content == 'featured' && has_post_thumbnail( $post_id )) {
				//$thumb = get_the_post_thumbnail($post_id, get_thumbnail_width());
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				
				$thumb = !empty($post_thumbnail_id) ? wp_get_attachment_image( $post_thumbnail_id, get_thumbnail_width()) : '';
				
				$post_thumbnail_src= '';
				if(!empty($post_thumbnail_id)){
					$image = wp_get_attachment_image_src($post_thumbnail_id,'full');
					$post_thumbnail_src = @$image[0];
				}
				
				if(!empty($thumb)) {
					$html[] = '<a class="content-thumb '.( $lightbox ? ' noo-lightbox-item':'').'" '.($lightbox ? 'data-lightbox-gallery="portfolio-gallery"':'').'  href="' .($lightbox ? $post_thumbnail_src : esc_url(get_permalink())) . '" title="' . esc_attr(sprintf(__('Permalink to: "%s"', 'noo') , the_title_attribute('echo=0'))) . '">';
					$html[] = $thumb;
					$html[] = '</a>';
				}

				echo implode($html, "\n");

				return;
			}

			// if( $preview_content == 'first_image' ) {
			// 	$gallery_ids = noo_get_post_meta($post_id, "{$prefix}_gallery", '');
			// 	if(!empty($gallery_ids)) {
			// 		$gallery_arr = explode(',', $gallery_ids);
			// 		$image_id = (int) $gallery_arr[0];
					
			// 		$thumb = !empty($image_id) ? wp_get_attachment_image( $image_id, get_thumbnail_width()) : '';
					
			// 		$post_thumbnail_src= '';
			// 		if(!empty($image_id)){
			// 			$image = wp_get_attachment_image_src($image_id,'full');
			// 			$post_thumbnail_src = @$image[0];
			// 		}
					
			// 		if(!empty($thumb)) {
			// 			$html[] = '<a class="content-thumb '.( $lightbox ? ' noo-lightbox-item':'').'" '.($lightbox ? 'data-lightbox-gallery="portfolio-gallery"':'').'  href="' .($lightbox ? $post_thumbnail_src : esc_url(get_permalink())) . '" title="' . esc_attr(sprintf(__('Permalink to: "%s"', 'noo') , the_title_attribute('echo=0'))) . '">';
			// 			$html[] = $thumb;
			// 			$html[] = '</a>';
			// 		}

			// 		echo implode($html, "\n");

			// 		return;
			// 	}
			// }
		}

		$gallery_ids = noo_get_post_meta($post_id, "{$prefix}_gallery", '');
		if(!empty($gallery_ids)) {			
			
			$html[] = '<div id="noo-gallery-' . $post_id . '" class="noo-slider">';
			$html[] = '<ul class="sliders">';
			$gallery_arr = explode(',', $gallery_ids);
			foreach ($gallery_arr as $index => $image_id) {
				$thumb = !empty($image_id) ? wp_get_attachment_image( $image_id, get_thumbnail_width()) : '';
				
				$post_thumbnail_src= '';
				if(!empty($image_id)){
					$image = wp_get_attachment_image_src($image_id,'full');
					$post_thumbnail_src = @$image[0];
				}
				
				$active = ($index == 0) ? 'active' : '';
				if(!empty($thumb)) {
					$html[] = '<li class="slide-item">';
					if($lightbox)
						$html[] = '<a class="'.( $lightbox ? ' noo-lightbox-item':'').'" '.($lightbox ? 'data-lightbox-gallery="portfolio-gallery-'.$post_id.'"':'').'  href="' .($lightbox ? $post_thumbnail_src : '') . '" title="' . esc_attr(sprintf(__('Permalink to: "%s"', 'noo') , the_title_attribute('echo=0'))) . '">';
					$html[] = $thumb;
					if($lightbox)
						$html[] = '</a>';
					
					$html[] = '</li>';
				}
			}
			
			$html[] = '</ul>';
			$html[] = '<div id="noo-gallery-' . $post_id . '-pagination" class="slider-indicators"></div>';
			$html[] = '<a id="noo-gallery-' . $post_id . '-prev" class="slider-control prev-btn" role="button" href="#"><span class="slider-icon-prev"></span></a>';
			$html[] = '<a id="noo-gallery-' . $post_id . '-next" class="slider-control next-btn" role="button" href="#"><span class="slider-icon-next"></span></a>';
			$html[] = '</div>';
			
			wp_enqueue_script( 'vendor-carouFredSel' );

			// carouse script
			$html[] = "<script>";
			$html[] = "jQuery('document').ready(function ($) {";
			$html[] = " $('#noo-gallery-{$post_id} .sliders').carouFredSel({";
			$html[] = "infinite: true,";
			$html[] = "circular: true,";
			$html[] = "auto: false,";
			$html[] = "responsive: true,";
			$html[] = 'prev: {
						button: "#noo-gallery-' . $post_id . '-prev"
						},
						next: {
						button: "#noo-gallery-' . $post_id . '-next"
						},
						pagination: {
						container: "#noo-gallery-' . $post_id . '-pagination"
					   }';
			$html[] = '  },{debug : false});';
			$html[] = '});';
			$html[] = '</script>';
		}

		return implode($html, "\n");
	}
endif;

if (!function_exists('noo_featured_gallery')):
	function noo_featured_gallery( $prefix = '_noo_wp_post', $post_id = null ) {
		echo noo_get_featured_gallery( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_get_featured_video')):
	function noo_get_featured_video($prefix = '_noo_wp_post', $post_id = null,$lightbox=false,  $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		$preview_content = noo_get_post_meta($post_id, "{$prefix}_preview_video", 'both');
		
		$output = '';
		$lightbox_preview = false;
		if (!is_singular() || $is_shortcode) {
			if ($preview_content == 'featured' && has_post_thumbnail( $post_id )) {
				//$thumb = get_the_post_thumbnail($post_id, get_thumbnail_width());
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				
				$thumb = !empty($post_thumbnail_id) ? wp_get_attachment_image( $post_thumbnail_id, get_thumbnail_width()) : '';
				
				$post_thumbnail_src= '';
				if(!empty($post_thumbnail_id)){
					$image = wp_get_attachment_image_src($post_thumbnail_id,'full');
					$post_thumbnail_src = @$image[0];
				}
				
				if(!empty($thumb)) {
					$html[] = '<a class="content-thumb '.( $lightbox ? ' noo-lightbox-item':'').'" '.($lightbox ? 'data-lightbox-type="inline"':'').'  href="' .($lightbox ? '#noo-video-container'.$post_id : esc_url(get_permalink())) . '" title="' . esc_attr(sprintf(__('Permalink to: "%s"', 'noo') , the_title_attribute('echo=0'))) . '">';
					$html[] = $thumb;
					$html[] = '</a>';
				}

				$output .= implode($html, "\n");
				if(!$lightbox)
					return $output;
				
				if($lightbox)
					$lightbox_preview = true;
			}
		}

		$m4v   	= noo_get_post_meta( $post_id, "{$prefix}_video_m4v", '' );
		$ogv   	= noo_get_post_meta( $post_id, "{$prefix}_video_ogv", '' );
		$embed 	= noo_get_post_meta( $post_id, "{$prefix}_video_embed", '' );

		$ratio 	= noo_get_post_meta( $post_id, "{$prefix}_video_ratio", '' );
		$ratio_class = '';
		switch($ratio) {
			case '16:9':
				$ratio_class = '16-9-ratio';
				break;
			case '5:3':
				$ratio_class = '5-3-ratio';
				break;
			case '5:4':
				$ratio_class = '5-4-ratio';
				break;
			case '4:3':
				$ratio_class = '4-3-ratio';
				break;
			case '3:2':
				$ratio_class = '3-2-ratio';
				break;
		}
		if($lightbox)
			$html[] = '<a class="hide'.( $lightbox ? ' noo-lightbox-item':'').'" '.($lightbox ? 'data-lightbox-type="inline"':'').'  href="#noo-video-container'.$post_id.'" title="' . esc_attr(sprintf(__('Permalink to: "%s"', 'noo') , the_title_attribute('echo=0'))) . '"></a>';
		// @TODO: add poster to embedded video.
		if ( $embed != '' ) {

			$html[] = '<div id="noo-video-container'.$post_id.'" class="noo-video-container ' . $ratio_class . '" '.($lightbox_preview ? 'style="display: none;"' : '').'>';
			$html[] = '	<div class="video-inner">';
			if ($preview_content == 'both' && has_post_thumbnail( $post_id )) {
				$html[] = '    <div class="embed-poster">';
				$html[] = get_the_post_thumbnail($post_id, get_thumbnail_width());
				$html[] = '    </div>';
			}
			$html[] = stripslashes( htmlspecialchars_decode( $embed ) );
				
			$html[] = '	</div>';
			$html[] = '</div>';

		} elseif ( $m4v || $ogv ) {

			ob_start();
			?>
			<script>
				jQuery(document).ready(function($){
					if($().jPlayer) {
						$('#jplayer_<?php echo $post_id; ?>').jPlayer({
							ready: function () {
								$(this).jPlayer('setMedia', {
									<?php if ( $m4v != '' ) : ?>
									m4v: '<?php echo $m4v; ?>',
									<?php endif; ?>
									<?php if ( $ogv != '' ) : ?>
									ogv: '<?php echo $ogv; ?>',
									<?php endif; ?>
									<?php 
									if ($preview_content == 'both' && has_post_thumbnail( $post_id )) :
										$poster = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), get_thumbnail_width(), false );
									?>
									poster: '<?php echo $poster[0]; ?>'
									<?php endif; ?>
								});
							},
							size: {
								width: '100%',
								height: '100%'
							},
							swfPath: '<?php echo get_template_directory_uri(); ?>/framework/vendor/jplayer',
							cssSelectorAncestor: '#jp_interface_<?php echo $post_id; ?>',
							supplied: '<?php if( $m4v != "" ) echo 'm4v,'; ?><?php if ( $ogv != "" ) echo 'ogv,'; ?>'
						});
					}
				});
			</script>
			<div id="noo-video-container<?php echo $post_id; ?>" class="noo-video-container <?php echo $ratio_class; ?>" <?php if($lightbox_preview):?> style="display: none;"<?php endif;?>>
				<div class="video-inner">
					<div id="jplayer_<?php echo $post_id; ?>" class="jp-jplayer jp-jplayer-video"></div>
					<div class="jp-controls-container jp-video">
						<div id="jp_interface_<?php echo $post_id; ?>" class="jp-interface">
							<ul class="jp-controls">
								<li><a href="#" class="jp-play" tabindex="1"><span><?php echo __('Play','noo') ?></span></a></li>
								<li><a href="#" class="jp-pause" tabindex="1"><span><?php echo __('Pause','noo') ?></span></a></li>
								<li><a href="#" class="jp-mute" tabindex="1"><span><?php echo __('Mute','noo') ?></span></a></li>
								<li><a href="#" class="jp-unmute" tabindex="1"><span><?php echo __('UnMute','noo') ?></span></a></li>
							</ul>
							<div class="jp-progress-container">
								<div class="jp-progress">
									<div class="jp-seek-bar">
										<div class="jp-play-bar"></div>
									</div>
								</div>
								<div class="jp-volume-bar">
									<div class="jp-volume-bar-value"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$html[] = ob_get_contents();
			ob_end_clean();
		}
		
		
		$output .= implode($html, "\n");
		return $output;
	}
endif;

if (!function_exists('noo_featured_video')):
	function noo_featured_video($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_get_featured_video( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_get_featured_audio')):
	function noo_get_featured_audio($prefix = '_noo_wp_post', $post_id = null) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;

		$mp3   = noo_get_post_meta( $post_id, "{$prefix}_audio_mp3", '' );
		$oga   = noo_get_post_meta( $post_id, "{$prefix}_audio_oga", '' );
		$embed = noo_get_post_meta( $post_id, "{$prefix}_audio_embed", '' );
		$html  = array();

		if ( $embed != '' ) :

			$html[] = '<div class="noo-audio-embed">';
			$html[] = stripslashes( htmlspecialchars_decode( $embed ) );
			$html[] = '</div>';

		elseif($mp3 || $oga) : // hosted audio
			ob_start();
		?>

		<script>
			jQuery(document).ready(function($){
				if($().jPlayer) {
					$('#jplayer_<?php echo $post_id; ?>').jPlayer({
						ready: function () {
							$(this).jPlayer('setMedia', {
								<?php if ( $mp3 != '' ) : ?>
								mp3: '<?php echo $mp3; ?>',
								<?php endif; ?>
								<?php if ( $oga != '' ) : ?>
								oga: '<?php echo $oga; ?>',
								<?php endif; ?>
								end: ''
							});
						},
						size: {
							width: '100%',
							height: '0'
						},
						swfPath: '<?php echo get_template_directory_uri(); ?>/framework/vendor/jplayer',
						cssSelectorAncestor: '#jp_interface_<?php echo $post_id; ?>',
						supplied: '<?php if( $mp3 != "" ) echo 'mp3,'; ?><?php if ( $oga != "" ) echo 'oga'; ?>'
					});
				}
			});
		</script>
		<div id="jplayer_<?php echo $post_id; ?>" class="jp-jplayer jp-jplayer-audio"></div>
		<div class="jp-controls-container jp-audio">
			<div id="jp_interface_<?php echo $post_id; ?>" class="jp-interface">
				<ul class="jp-controls">
					<li><a href="#" class="jp-play" tabindex="1"><span><?php echo __('Play','noo') ?></span></a></li>
					<li><a href="#" class="jp-pause" tabindex="1"><span><?php echo __('Pause','noo') ?></span></a></li>
					<li><a href="#" class="jp-mute" tabindex="1"><span><?php echo __('Mute','noo') ?></span></a></li>
					<li><a href="#" class="jp-unmute" tabindex="1"><span><?php echo __('UnMute','noo') ?></span></a></li>
				</ul>
				<div class="jp-progress-container">
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
				</div>
			</div>
		</div>
		<?php 
		$html[] = ob_get_contents();
		ob_end_clean();
		endif; // if - $embed

		return implode($html, "\n");
	}
endif;

if (!function_exists('noo_featured_audio')):
	function noo_featured_audio($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_get_featured_audio( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_get_featured_quote')):
	function noo_get_featured_quote($prefix = '_noo_wp_post', $post_id = null) {
		return noo_get_featured_default($post_id);
	}
endif;

if (!function_exists('noo_featured_quote')):
	function noo_featured_quote($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_get_featured_default( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_get_featured_link')):
	function noo_get_featured_link($prefix = '_noo_wp_post', $post_id = null) {
		return noo_get_featured_default($post_id);
	}
endif;

if (!function_exists('noo_featured_link')):
	function noo_featured_link($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_get_featured_link($prefix, $post_id);
	}
endif;

if (!function_exists('noo_get_featured_portfolio')):
	function noo_get_featured_portfolio($post_id = null, $post_format = '',$lightbox = false, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;

		$prefix = '_noo_portfolio';
		$post_format = ('' === $post_format) ? noo_get_post_meta($post_id, "{$prefix}_media_type", 'image') : $post_format;
		
		switch ($post_format) {
			case 'image':
				return noo_get_featured_image($prefix, $post_id, $lightbox, $is_shortcode);
			case 'gallery':
				return noo_get_featured_gallery($prefix, $post_id, $lightbox, $is_shortcode);
			case 'video':
				return noo_get_featured_video($prefix, $post_id,$lightbox, $is_shortcode);
			default: // no format portfolio item
				return noo_get_featured_default($post_id);
		}

		return '';
	}
endif;

if (!function_exists('noo_featured_portfolio')):
	function noo_featured_portfolio($post_id = null, $post_format = '',$lightbox=false,$is_shortcode = false) {
		echo noo_get_featured_portfolio( $post_id, $post_format, $lightbox, $is_shortcode);
	}
endif;

if(!function_exists('noo_featured_popup_portfolio')):
function noo_featured_popup_portfolio($post_id=null,$post_format = ''){
	$post_id = (null === $post_id) ? get_the_id() : $post_id;
	$prefix = '_noo_portfolio';
	$html = '';
	$post_format = ('' === $post_format) ? noo_get_post_meta($post_id, "{$prefix}_media_type", 'image') : $post_format;
	if($post_format == 'image'){
		$post_thumbnail_id = 0;

		$main_image = noo_get_post_meta($post_id, "{$prefix}_main_image", 'featured');
		if( $main_image == 'featured') {
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		} else {
			if (!is_singular()) {
				$preview_content = noo_get_post_meta($post_id, "{$prefix}_image_preview", 'image');
				if ($preview_content == 'featured') {
					$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				}
			}

			if(empty($thumb)) {
				$post_thumbnail_id = (int) noo_get_post_meta($post_id, "{$prefix}_image", '');
			}
		}
			
		if(!empty($post_thumbnail_id)){
			$thumb = wp_get_attachment_image_src( $post_thumbnail_id, 'full');
		}
		$html = '<a class="noo-share noo-lightbox-item" href="'.@$thumb[0].'"><i class="fa fa-eye"></i></a>';
		echo $html;
		return;
		
	}elseif ($post_format == 'gallery'){
		$preview_content = noo_get_post_meta($post_id, "{$prefix}_gallery_preview", 'slideshow');
		if ($preview_content == 'featured' && has_post_thumbnail( $post_id )) {
			$thumb = get_the_post_thumbnail($post_id, get_thumbnail_width());
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
			
			if(!empty($post_thumbnail_id)){
				$thumb = wp_get_attachment_image_src( $post_thumbnail_id, 'full');
			}
			$html = '<a class="noo-share noo-lightbox-item" href="'.@$thumb[0].'"><i class="fa fa-eye"></i></a>';
			echo $html;
			return;
		}
		
		if( $preview_content == 'first_image' ) {
			$gallery_ids = noo_get_post_meta($post_id, "{$prefix}_gallery", '');
			if(!empty($gallery_ids)) {
				$gallery_arr = explode(',', $gallery_ids);
				$image_id = (int) $gallery_arr[0];
				
				if(!empty($image_id)){
					$thumb = wp_get_attachment_image_src( $image_id, 'full');
				}
				$html = '<a class="noo-share noo-lightbox-item" href="'.@$thumb[0].'"><i class="fa fa-eye"></i></a>';
				echo $html;
				return;
			}
		}
	}
	echo $html;
	return;
}
endif;

if (!function_exists('noo_get_featured_default')):
	function noo_get_featured_default($post_id = null) {
		$html = array();
		
		if (has_post_thumbnail()) {
			$thumb = get_the_post_thumbnail($post_id, get_thumbnail_width());
			if (is_singular()) {
				$html[] = '<div class="content-thumb">';
				$html[] = $thumb;
				$html[] = '</div>';
			} else {
				$html[] = '<a class="content-thumb" href="' . esc_url(get_permalink()) . '" title="' . esc_attr(sprintf(__('Permalink to: "%s"', 'noo') , the_title_attribute('echo=0'))) . '">';
				$html[] = $thumb;
				$html[] = '</a>';
			}
		}
		
		return implode($html, "\n");
	}
endif;


if (!function_exists('noo_featured_default')):
	function noo_featured_default($post_id = null) {
		echo noo_get_featured_default( $post_id );
	}
endif;
