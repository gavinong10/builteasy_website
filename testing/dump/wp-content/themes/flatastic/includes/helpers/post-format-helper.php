<?php

// -----------------------  Single Format ------------------------- //

add_filter( 'entry-format-single', 'mad_single_post_filter', 11, 1 );

// ----------------------- Template Post Format ------------------------- //

add_filter( 'entry-format-template', 'mad_template_post_filter', 11, 1 );

// ----------------------- Standard Post Format ------------------------- //

add_filter( 'entry-format-standard', 'mad_standard_post_filter', 11, 1 );

// ------------------------ Gallery Post Format ------------------------- //

add_filter( 'entry-format-gallery', 'mad_gallery_post_filter', 11, 1 );

// ------------------------- Video Post Format -------------------------- //

add_filter( 'entry-format-video', 'mad_video_post_filter', 11, 1 );

// ------------------------- Audio Post Format -------------------------- //

add_filter( 'entry-format-audio', 'mad_audio_post_filter', 11, 1 );

// ------------------------- Quote Post Format -------------------------- //

add_filter( 'entry-format-quote', 'mad_quote_post_filter', 11, 1 );

//  Single Post Filter									//
// ==================================================== //

if (!function_exists('mad_single_post_filter')) {

	function mad_single_post_filter ($this_post) {
		preg_match("!\[(?:)?gallery.+?\]!", $this_post['content'], $match_gallery);

		if (!empty($match_gallery)) {

			$gallery = $match_gallery[0];

			if (strpos($gallery, 'vc_') === false) {
				$gallery = str_replace("gallery", 'mad_gallery', $gallery);
			}

			$this_post['content'] = str_replace($match_gallery[0], $gallery, $this_post['content']);
		}
		return $this_post;
	}
}

//  Template Filter										//
// ==================================================== //

if (!function_exists('mad_template_post_filter')) {

	function mad_template_post_filter($this_post) {
		$thumbnail = $before = '';
		$this_id = $this_post['post_id'];
		$image_size = $this_post['image_size'];

		$thumbnail_atts = array(
			'class'	=> "tr_all_long_hover",
			'alt'	=> trim(strip_tags(get_the_excerpt())),
			'title'	=> trim(strip_tags(get_the_title()))
		);

		if (has_post_thumbnail($this_id)) {
			$thumbnail = MAD_HELPER::get_the_post_thumbnail($this_id, $image_size, $thumbnail_atts);
			$before = $thumbnail;
		} else {
			$image = mad_regex($this_post['content'], 'image', "");
			if (is_array($image)) {
				$get_image = $image[0];
				$before = $get_image;
			} else {
				$image = mad_regex($this_post['content'], '<img />', "");
				if (is_array($image)) {
					$before = $image[0];
				}
			}
		}

		if (is_string($before) && !empty($before)) {
			if ($thumbnail){
				$this_post['content'] = str_replace($thumbnail, "", $this_post['content']);
			}
			$this_post['before_content'] = $before;
		}
		$this_post['content'] = apply_filters('the_content', $this_post['content']);
		return $this_post;
	}
}

//  Standard Filter										//
// ==================================================== //

if (!function_exists('mad_standard_post_filter')) {

	function mad_standard_post_filter($this_post) {
		$thumbnail = $before = $jackbox = '';
		$this_id = $this_post['post_id'];
		$image_size = $this_post['image_size'];
		$zoom_image = mad_custom_get_option('zoom_image', '');

		$thumbnail_atts = array(
			'class'	=> "tr_all_long_hover",
			'alt'	=> trim(strip_tags(get_the_excerpt())),
			'title'	=> trim(strip_tags(get_the_title()))
		);

		if (is_single()) {
			$link = MAD_HELPER::get_post_featured_image($this_id, '');
			$jackbox = 'jackbox';
		} else {
			$link = $this_post['url'];
		}

		$link = esc_url($link);

		if (has_post_thumbnail($this_id)) {
			$thumbnail = MAD_HELPER::get_the_post_thumbnail($this_id, $image_size, $thumbnail_atts);
			$before = "<div class='image-overlay'><a href='{$link}' data-group='entry-". $this_id ."' title='". sprintf(esc_attr__('%s', MAD_BASE_TEXTDOMAIN), the_title_attribute('echo=0')) ."' class='single-image entry-media photoframe {$jackbox} {$zoom_image}'>{$thumbnail}</a></div>";
		}

		if (is_string($before) && !empty($before)) {
			if ($thumbnail){
				$this_post['content'] = str_replace($thumbnail, "", $this_post['content']);
			}
			$this_post['before_content'] = $before;
		}
		$this_post['content'] = apply_filters('the_content', $this_post['content']);
		return $this_post;
	}
}

//  Gallery Post Filter									//
// ==================================================== //

if (!function_exists('mad_gallery_post_filter')) {

	function mad_gallery_post_filter ($this_post) {
		preg_match("!\[(?:)?gallery.+?\]!", $this_post['content'], $match_gallery);

		if (!empty($match_gallery)) {
			$gallery = $match_gallery[0];
            if (strpos($gallery, 'vc_') === false) {
				$gallery = str_replace("gallery", 'mad_gallery image_size="'. $this_post['image_size'] .'" post_id="'. $this_post['post_id'] .'"', $gallery);
			}
            $this_post['before_content'] = do_shortcode($gallery);
			$this_post['content'] = str_replace($match_gallery[0], '', $this_post['content']);
			$this_post['content'] = apply_filters('the_content', $this_post['content']);
		}
		return $this_post;
	}
}

//  Audio Post Filter									//
// ==================================================== //

if (!function_exists('mad_audio_post_filter')) {

	function mad_audio_post_filter($this_post) {
		$this_post['content'] = preg_replace( '|^\s*(http?://[^\s"]+)\s*$|im', "[audio src='$1']", strip_tags($this_post['content']) );
		preg_match("!\[audio.+?\]!", $this_post['content'], $match_audio);
		preg_match("!\[embed.+?\]!", $this_post['content'], $match_embed);

		if (!empty($match_embed) && strpos($match_embed[0], 'soundcloud.com') !== false) {
			global $wp_embed;
			$alias = $this_post['image_size'];
			$embed = $match_embed[0];
			$embed = str_replace('[embed]', '[embed width="'. $alias[0] .'" height="'. $alias[1] .'"]', $embed);

			$this_post['before_content'] = "<div class='entry-media image-overlay'>";
				$this_post['before_content'] .= $wp_embed->run_shortcode($embed);
			$this_post['before_content'] .= "</div>";
			$this_post['content'] = str_replace($match_embed[0], "", $this_post['content']);
			return $this_post;
		} else if (!empty($match_audio)) {
			$this_post['before_content'] = "<div class='entry-media image-overlay'>";
				$this_post['before_content'] .= do_shortcode($match_audio[0]);
			$this_post['before_content'] .= "</div>";
			$this_post['content'] = str_replace($match_audio[0], "", $this_post['content']);
		}
		$this_post['content'] = apply_filters('the_content', $this_post['content']);
		return $this_post;
	}
}

//  Video Post Filter									//
// ==================================================== //

if (!function_exists('mad_video_post_filter')) {

	function mad_video_post_filter($this_post) {
		$this_post['content'] = preg_replace( '|^\s*(https?://[^\s"]+)\s*$|im', "[embed]$1[/embed]", strip_tags($this_post['content']));
		preg_match("!\[embed.+?\]|\[video.+?\]!", $this_post['content'], $match_video);

		if (!empty($match_video)) {
			global $wp_embed;

			$alias = $this_post['image_size'];
			$video = $match_video[0];
			$video = str_replace('[embed]', '[embed width="'. $alias[0] .'" height="'. $alias[1] .'"]', $video);

			$this_post['before_content'] = "<div class='entry-media image-overlay'>";
				$this_post['before_content'] .= do_shortcode($wp_embed->run_shortcode($video));
			$this_post['before_content'] .= "</div>";
			$this_post['content'] = str_replace($match_video[0], "", $this_post['content']);
			$this_post['content'] = apply_filters('the_content', $this_post['content']);
		}
		return $this_post;
	}
}

//  Quote Post Filter									//
// ==================================================== //

if (!function_exists('mad_quote_post_filter')) {

	function mad_quote_post_filter($this_post) {
		$post_id = $this_post['post_id'];

		$output = '<div class="post-quote entry-media image-overlay">';
			$output .= is_single() ? '' : '<a href="'. esc_url($this_post['url']) .'" class="whole-link" title="' . sprintf( esc_attr__('Permalink to %s', MAD_BASE_TEXTDOMAIN), the_title_attribute('echo=0') ) .'"></a>';
			$output .= '<blockquote>'. rwmb_meta('mad_quote', '', $post_id) .'</blockquote>';
		$output .= '</div><!--/ .post-quote-->';

		$this_post['before_content'] = $output;
		$this_post['content'] = apply_filters('the_content', $this_post['content']);
		return $this_post;
	}
}