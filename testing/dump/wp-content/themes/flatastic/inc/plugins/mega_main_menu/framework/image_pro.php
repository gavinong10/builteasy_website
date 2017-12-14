<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */
	if ( !class_exists('mad_mm_image_pro') ) {
		class mad_mm_image_pro {

			public static function post_thumbnail_id ( $post_id = false ) {
				if ( $post_id != false && is_numeric( $post_id ) ) {
					$post_id = $post_id;
				} else {
					global $post;
					$post_id = ( get_the_ID() ) ? get_the_ID() : $post->ID;
				}
				return get_post_thumbnail_id( $post_id );
			}

			public static function post_image_src ( $post_id = false ) {
				if ( $post_id != false && is_numeric( $post_id ) ) {
					$attachment_id = self::post_thumbnail_id( $post_id );
				} else {
					$attachment_id = self::post_thumbnail_id();
				}
				if ( wp_get_attachment_image_src( $attachment_id, 'full' ) ) {
					$image_src = wp_get_attachment_image_src( $attachment_id, 'full' );
					$image_src = $image_src[0];
		/* for better times
					if ( mmpm_url_exist( $image_src ) == false ) {
						$image_src = MMPM_IMAGE_NOT_FOUND; 
					}
		*/
				} else {
					$image_src = false;
				};
				return $image_src;
			}

			public static function resized_image_src ( $original_image_url = false, $width = false, $height = false, $crop = true ) {
				$wp_upload_dir = wp_upload_dir();
		/*
				if ( empty( $original_image_url ) ){
					$original_image_url = MMPM_NO_IMAGE_AVAILABLE; 
				}
		*/
				$original_image_path = str_replace( $wp_upload_dir['baseurl'], $wp_upload_dir['basedir'], $original_image_url );
		/* for better times
				if ( mmpm_url_exist( $original_image_url ) == false ) {
					$original_image_url = MMPM_IMAGE_NOT_FOUND; 
				}
		*/
				$file_extension = strrchr( $original_image_url, '.' );
				$file_name = strrchr( $original_image_url, '/' );
				if ( is_numeric( $width ) || is_numeric( $height ) ) {
					$croped_file_name = str_replace( $file_extension, '', $file_name ) . '-' . $width . 'x' . $height . ( $crop == true ? '-croped' : '-uncroped' ) . $file_extension;
				} else {
					$croped_file_name = $file_name;
				}
				if ( substr_count( $original_image_url, $wp_upload_dir['baseurl'] ) ) {
					$subdir = str_replace( array( $wp_upload_dir['baseurl'], $file_name ), '', $original_image_url );
				} elseif ( substr_count( $original_image_url, home_url() ) ) {
					$subdir = '/undated_files';
				} else {
					$subdir = '/external_files';
				}
				$croped_img_path = $wp_upload_dir['basedir'] . $subdir . $croped_file_name;
				$croped_img_url = $wp_upload_dir['baseurl'] . $subdir . $croped_file_name;
				if ( !file_exists( $croped_img_path ) ) {
					$img = wp_get_image_editor( $original_image_path );
					if ( ! is_wp_error( $img ) ) {
						$img->resize( $width, $height, $crop );
						$img->save( $croped_img_path );
					}
				}
				return $croped_img_url;
			}

			public static function processed_image ( $args = array() ) {
				global $post;
				$defaults = array(
					'post_id' => false,
					'width' => false,
					'height' => false,
					'crop' => true,
					'class' => false,
					'echo' => false,
					'src' => true,
					'permalink' => true,
					'cover' => array('title','link'), // Available types: title, link, zoom, icon
					'title' => true,
					'icon' => true,
					'container' => true,
					'stack_id' => false,
				);
				$args = wp_parse_args( $args, $defaults );
				extract( $args );
				// check and set variablesz
				$out = '';
				$post_id = ( ( $post_id !== false ) 
					? $post_id
					: (
						( get_the_ID() != false ) 
							? get_the_ID()
							: $post->ID
					)
				);
				$src = ( ( is_string( $src ) && mad_mm_common::is_url( $src ) )
					? esc_url( $src )
					: self::post_image_src( $post_id )
				);
				$icon = ( ( is_string( $icon ) && !empty( $icon ) )
					? $icon 
					: ( get_post_meta( $post_id, 'mm_post_icon', true ) 
						? get_post_meta( $post_id, 'mm_post_icon', true ) 
						: 'im-icon-plus-circle' 
					) 
				);
				$title = ( is_string( $title ) 
					? $title 
					: ( get_the_title( $post_id ) 
						? get_the_title( $post_id ) 
						: false ) //__( 'More', 'image_cover_textdomain' ) ) 
				);
				$permalink = ( is_string( $permalink ) 
					? esc_url( $permalink )
					: ( ( $permalink != false && get_permalink( $post_id ) ) 
						? get_permalink( $post_id ) 
						: '' 
					) 
				);
				$attachment_object = get_post_thumbnail_id( $post_id ) 
					? (object) get_post( get_post_thumbnail_id( $post_id ) ) 
					: (object) 'image';
				$alt_attr = ( is_object( $attachment_object ) && isset( $attachment_object->ID ) && get_post_meta( $attachment_object->ID, '_wp_attachment_image_alt', true ) != '' ) 
					? get_post_meta( $attachment_object->ID, '_wp_attachment_image_alt', true ) 
					: $title ;
				$post_excerpt = ( isset( $attachment_object->post_excerpt ) && $attachment_object->post_excerpt != '' ) 
					? $attachment_object->post_excerpt
					: $title;
				// build image tag
				if ( $src !== false ) {
					$img = '<img src="' . self::resized_image_src( $src, $width, $height, $crop ) . '" alt="' . $alt_attr . '" title="' . $post_excerpt . '" />';
					// build additional containers
					if ( $container == true ) {
						$out .= mad_mm_common::ntab(1) . '<' . ( is_string( $container ) ? $container : 'div' ) . ' class="processed_image' . ( is_string( $class ) ? ' ' . $class : '' ) . '">'; //  style="max-width:' . $width . 'px; max-height:' . $height . 'px;"
						$out .= mad_mm_common::ntab(2) . $img;
						if ( $cover == true ) {
							$out .= mad_mm_common::ntab(2) . '<div class="cover' . ( is_string( $cover ) ? ' ' . $cover : ( is_array( $cover ) ? ' ' . implode( ' ', $cover ) : '') ) . '">';
							if ( 
								$icon == true && 
								( 
									$cover == 'icon' 
									|| ( 
										is_array( $cover ) 
										&& ( 
											in_array( 'icon', $cover ) 
											|| ( !in_array( 'zoom', $cover ) && !in_array( 'link', $cover ) ) 
										) 
									)
								) 
							) {
/*
								$link_href_atr = ( 
									( $permalink != '' && ( !is_array( $cover ) || ( is_array( $cover ) && !in_array( 'link', $cover ) ) ) ) 
									? 'href="' . $permalink . '"' 
									: '' 
								);
								$out .= mm_common::ntab(3) . '<a ' . $link_href_atr . ' class="icon">'; //' . ( is_array( $cover ) && ( in_array( 'zoom', $cover ) || in_array( 'link', $cover ) ) ? '' : ' without_controls' ) . '
								$out .= mm_common::ntab(4) . '<i class="' .$icon . '"></i>';					
								$out .= mm_common::ntab(3) . '</a>';
*/
								$out .= mad_mm_common::ntab(3) . ( ( $permalink != '' && ( ( is_array( $cover ) && !in_array( 'link', $cover ) ) || $cover != 'link' ) )
									? '<a href="' . $permalink . '"' . ' class="icon"><i class="' .$icon . '"></i></a>'
									: '<span class="icon"><i class="' .$icon . '"></i></span>'
								);
							}
							if ( $title == true && ( $cover == 'title' || ( is_array( $cover ) && in_array( 'title', $cover ) ) ) ) {
/*
								$link_href_atr = ( ( $permalink != '' && ( is_array( $cover ) && !in_array( 'link', $cover ) ) ) 
									? 'href="' . $permalink . '"' 
									: '' 
								);
								$out .= mm_common::ntab(3) . '<a ' . $link_href_atr . ' class="title' . ( $permalink == '' ? ' single' : '' ) . '" title="' . $title . '">';
								$out .= mm_common::ntab(4) . $title;					
								$out .= mm_common::ntab(3) . '</a>';
*/
								$out .= mad_mm_common::ntab(3) . ( ( $permalink != '' && ( is_array( $cover ) && !in_array( 'link', $cover ) ) )
									? '<a href="' . $permalink . '"' . ' class="title' . ( $permalink == '' ? ' single' : '' ) . '" title="' . $title . '">'. $title . '</a>'
									: '<span class="title' . ( $permalink == '' ? ' single' : '' ) . '">'. $title . '</span>'
								);
							}
							if ( $cover == 'zoom' || ( is_array( $cover ) && in_array( 'zoom', $cover ) ) ) {
								$out .= mad_mm_common::ntab(3) . '<a href="' . $src . '" title="' . $title . '" data-rel="photo' . ( ( $stack_id !== false ) ? '[pp_' . $stack_id . ']' : '' ) . '" class="controls full_image' . ( ( $permalink != '' && in_array( 'link', $cover ) ) ? '' : ' single' ) . '">';
								$out .= mad_mm_common::ntab(4) . '<i class="im-icon-zoom-in"></i>';
								$out .= mad_mm_common::ntab(3) . '</a>';
							}
							if ( $cover == 'link' || ( is_array( $cover ) && in_array( 'link', $cover ) ) ) {
								if ( $permalink != '' ) {
									$out .= mad_mm_common::ntab(3) . '<a href="' . $permalink . '" class="controls permalink' . ( ( in_array( 'zoom', $cover ) ) ? '' : ' single' ) . '">';
									$out .= mad_mm_common::ntab(4) . '<i class="im-icon-link"></i>';
									$out .= mad_mm_common::ntab(3) . '</a>';
								}
							}
							$out .= mad_mm_common::ntab(2) . '</div><!-- class="cover' . ( is_string( $cover ) ? ' ' . $cover : ( is_array( $cover ) ? ' ' . implode( ' ', $cover ) : '') ) . '" -->';
						}
						$out .= mad_mm_common::ntab(1) . '</' . ( is_string( $container ) ? $container : 'div' ) . '><!-- class="processed_image' . ( is_string( $class ) ? ' ' . $class : '' ) . '" -->';
					} else {
						$out .= mad_mm_common::ntab(1) . $img;
					}
				}

				// return echo or output
				if ( $echo != false ) {
					echo $out;
				} else {
					return $out;
				}
			}
		}
	}

?>
