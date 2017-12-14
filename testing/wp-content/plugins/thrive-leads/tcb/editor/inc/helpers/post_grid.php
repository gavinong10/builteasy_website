<?php

/**
 * Created by PhpStorm.
 * User: Danut
 * Date: 10/16/2014
 * Time: 9:54 AM
 */
class PostGridHelper {
	protected $_template = 'sc_post_grid.php';
	protected $_config;
	public $wrap = true;

	public static $render_post_grid = true;

	public function __construct( $config ) {
		$defaults = array(
			'item_container'    => '', //custom color
			'item_text'         => '', //custom color
			'item_headline'     => '', //custom color
			'font-size'         => '',
			'custom-font-class' => '',
			'font-family'       => '',
			'image-height'      => '',
			'text-line-height'  => '',
			'read_more_color'   => '',
			'read-more-text'    => ''
		);

		if ( ! empty( $config['currentConfig'] ) ) {
			foreach ( $defaults as $option => $value ) {
				if ( ! empty( $config['currentConfig'][ $option ] ) ) {
					$config[ $option ] = $config['currentConfig'][ $option ];
				}
			}
			unset( $config['currentConfig'] );
		}

		$config = stripslashes_deep( $config );

		$this->_config = $config;

		$this->_template = ! empty( $this->_config['grid_layout'] ) && $this->_config['grid_layout'] === 'vertical' ? "sc_post_grid_vertical.php" : $this->_template;
	}

	public function render() {
		if ( ! self::$render_post_grid ) {
			return '';
		}
		ob_start();
		$this->_getView( $this->_template );
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	protected function _getView( $template ) {
		$path = dirname( __FILE__ ) . '/../../../shortcodes/templates/' . $template;
		include $path;
	}


	/**
	 * @param string|array $types
	 * @param $filters array
	 * @param $post_per_page int
	 * @param $posts_start int
	 * @param $order string ASC|DESC
	 * @param $orderby string
	 *
	 * @return array
	 */
	function tve_get_post_grid_posts( $types = 'any', $filters = array(), $post_per_page = 3, $posts_start = 0, $order = "ASC", $orderby = 'title' ) {
		if ( empty( $this->_config['exclude'] ) ) {
			$this->_config['exclude'] = 0;
		}
		$args = array(
			'post_type'      => $types,
			'offset'         => $posts_start,
			'posts_per_page' => $post_per_page == 0 ? - 1 : $post_per_page,
			'order'          => $order,
			'orderby'        => $orderby,
			'post_status'    => 'publish',
			'post__not_in'   => array( $this->_config['exclude'] ),
		);

		if ( ! empty( $filters['category'] ) ) {
			$filters['category'] = trim( $filters['category'], "," );
			$category_names      = explode( ",", $filters['category'] );
			$category_IDs        = array();
			if ( ! empty( $category_names ) ) {
				foreach ( $category_names as $name ) {
					$name           = stripslashes( $name );
					$category_IDs[] = get_cat_ID( $name );
				}
			}
			$args['tax_query'] = array(
				'relation' => 'AND',
				array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $category_IDs,
						'operator' => 'IN',
					),
					array(
						'taxonomy' => 'apprentice',
						'field'    => 'name',
						'terms'    => $filters['category'],
						'operator' => 'IN',
					)
				)
			);
		}

		if ( ! empty( $filters['tag'] ) ) {
			$tag_names  = explode( ",", trim( $filters['tag'], ',' ) );
			$tag_names  = array_unique( $tag_names );
			$query_tags = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'name',
					'terms'    => $tag_names,
					'operator' => 'IN',
				),
				array(
					'taxonomy' => 'apprentice-tag',
					'field'    => 'name',
					'terms'    => $tag_names,
					'operator' => 'IN',
				)
			);
			if ( ! empty( $filters['category'] ) ) {
				$args['tax_query'][] = $query_tags;
			} else {
				$args['tax_query'] = array(
					'relation' => 'AND',
					$query_tags,
				);
			}
		}

		if ( ! empty( $filters['tax'] ) ) {
			$tax_names = explode( ",", trim( $filters['tax'], "," ) );
			$tax_names = array_unique( $tax_names );
			$tax_query = array();
			//foreach taxonomy name get all its terms and build tax_query for it
			foreach ( $tax_names as $tax_name ) {
				$termsObj = get_terms( $tax_name );
				if ( empty( $termsObj ) || $termsObj instanceof WP_Error ) {
					continue;
				}
				$tax_terms = array();
				foreach ( $termsObj as $term ) {
					$tax_terms[] = $term->slug;
				}
				$tax_query[] = array(
					'taxonomy' => $tax_name,
					'field'    => 'slug',
					'terms'    => $tax_terms
				);
			}
			if ( ! empty( $tax_query ) ) {
				$tax_query['relation'] = 'OR';
				$args['tax_query']     = $tax_query;
			}
		}

		if ( ! empty( $filters['author'] ) ) {
			$author_names = explode( ",", trim( $filters['author'], "," ) );
			$author_names = array_unique( $author_names );
			$author_ids   = array();
			foreach ( $author_names as $name ) {
				$author = get_user_by( 'slug', $name );
				if ( $author ) {
					$author_ids[] = $author->ID;
				}
			}
			if ( ! empty( $author_ids ) ) {
				$args['author'] = implode( ",", $author_ids );
			}
		}

		if ( ! empty( $filters['posts'] ) ) {
			$post_ids         = explode( ",", trim( $filters['posts'], "," ) );
			$post_ids         = array_unique( $post_ids );
			$args['post__in'] = $post_ids;
		}

		if ( ! empty( $this->_config['recent_days'] ) ) {
			$args['date_query'] = array(
				'after' => date( "Y-m-d", strtotime( "-" . intval( $this->_config['recent_days'] ) . " days", strtotime( date( 'Y-m-d' ) ) ) )
			);
		}

		$args['ignore_sticky_posts'] = 1;
		remove_filter( 'pre_get_posts', 'thrive_exclude_category' );
		$results = new WP_Query( $args );

		return $results->posts;
	}

	function tve_get_post_types( $config ) {
		$types = array();
		if ( isset( $config['post_types'] ) && is_array( $config['post_types'] ) && ! empty( $config['post_types'] ) ) {
			foreach ( $config['post_types'] as $type => $checked ) {
				if ( $checked === 'true' ) {
					$types[] = $type;
				}
			}
		}

		return $types;
	}

	function tve_post_grid_get_config() {
		$config = $_POST;

		unset( $config['tve_lb_type'] );
		unset( $config['security'] );

		return $config;
	}

	function tve_post_grid_display_post_featured_image( $post, array $config ) {
		if ( ! has_post_thumbnail( $post->ID ) ) {
			return;
		}
		$src    = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
		$height = ! empty( $config['image-height'] ) ? "height: {$config['image-height']}px" : '';

		?>
		<a href="<?php echo get_permalink( $post ) ?>">
			<div class="tve_post_grid_image_wrapper"
			     style="background-image: url('<?php echo $src; ?>'); <?php echo $height ?>">
				<div class="tve_pg_img_overlay">
					<span class="thrv-icon thrv-icon-forward"></span>
				</div>
			</div>
		</a>
		<?php
	}

	function tve_post_grid_display_post_text( $post_item, array $config ) {
		global $post;

		//save temporary the current post where the post grid is rendered
		$old_post = $post;

		//set as global post the current post in loop: $post_item
		$post = $post_item;

		//set flag as not rendering the post grid
		//we just need the $post_item as global $post
		//see the else if case for the excerpt few lines below
		self::$render_post_grid = false;

		//get whole the content
		$content = $post->post_content;

		//strip all the shortcodes from the content
		$content = strip_shortcodes( $content );

		if ( $config['text_type'] === 'summary' ) {
			$content = $this->get_summary( $content );
		} else if ( $config['text_type'] === 'excerpt' ) {
			//get the excerpt of the post
			//$content = get_the_excerpt(); //this function cannot be called because it applies other filters that is not needed

			//get post excerpt or it's summary
			$content = empty( $post->post_excerpt ) ? $this->get_summary( $content ) : $post->post_excerpt;
		} else {
			//full text with exceptions
			$content = $this->strip_all_tags( $content, '<p><h1><h2><h3><h4><h5><h6><a><strong><b>', true );
		}

		//set the flag as we are rendering back the post that holds the post grid
		self::$render_post_grid = true;

		//set as global back the current post that hold the post grid
		$post = $old_post;

		if ( empty( $content ) ) {
			return;
		}

		//apply some style
		$text_key   = array_search( 'text', $config['layout'] );
		$text_style = '';
		if ( $text_key > 0 && $config['layout'][ -- $text_key ] === 'title' ) {
			$text_style = 'border-top-width: 1px;';
		}

		$custom_item_text = ! empty( $config['item_text'] ) ? ' data-tve-custom-colour="' . $config['item_text'] . '"' : '';
		?>
		<div class="tve-post-grid-text" style="<?php echo $text_style; ?>" <?php echo $custom_item_text ?>><?php echo $content; ?></div><?php
	}

	function tve_post_grid_display_post_title( $post, $config ) {
		if ( empty( $post->post_title ) ) {
			return;
		}
		$font_size            = ! empty( $config['font-size'] ) ? "font-size: {$config['font-size']}px;" : '';
		$custom_font_class    = ! empty( $config['custom-font-class'] ) ? $config['custom-font-class'] : '';
		$custom_item_headline = ! empty( $config['item_headline'] ) ? ' data-tve-custom-colour="' . $config['item_headline'] . '"' : '';
		$item_title_class     = ! empty( $config['item_title_class'] ) ? $config['item_title_class'] : '';

		$item_title_class  = trim( implode( ' ', array( $custom_font_class, $item_title_class ) ) );
		$style_line_height = ! empty( $config['text-line-height'] ) ? "line-height: {$config['text-line-height']};" : '';
		$font_family       = ! empty( $config['font-family'] ) ? "font-family:" . stripslashes( $config['font-family'] ) . ";" : '';

		?><span class="tve-post-grid-title <?php echo $item_title_class; ?>"
		        style="<?php echo $font_size . $style_line_height . $font_family; ?>"><a <?php echo $custom_item_headline ?>
			href="<?php echo get_permalink( $post ) ?>"><?php echo get_the_title( $post->ID ); ?></a></span><?php
	}

	function tve_post_grid_display_post_read_more( $post, $config ) {
		$custom_color   = ! empty( $config['read_more_color'] ) ? ' data-tve-custom-colour="' . $config['read_more_color'] . '"' : '';
		$read_more_text = ! empty( $config['read-more-text'] ) ? $config['read-more-text'] : __( 'Read More' );
		?>
		<div class="tve_pg_more"<?php echo $custom_color ?>>
			<a href="<?php echo get_permalink( $post ) ?>"<?php echo $custom_color ?>><?php echo $read_more_text ?></a>
			<span class="thrv-icon thrv-icon-uniE602"></span>
		</div>
		<?php
	}

	/**
	 * Generate css classes for the item of the post grid identified by its index
	 *
	 * @param $index
	 *
	 * @return string
	 */
	function postGridClasses( $index ) {
		$classes = array();
		if ( $index === 1 || ( $index - 1 ) % $this->_config['columns'] === 0 ) {
			$classes[] = 'tve_first';
		}
		if ( $index % $this->_config['columns'] === 0 ) {
			$classes[] = 'tve_last';
		}


		return implode( " ", $classes );
	}

	function strip_all_tags( $string, $allowable_tags = '', $remove_breaks = false ) {
		$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
		$string = strip_tags( $string, ! empty( $allowable_tags ) ? $allowable_tags : null );

		if ( $remove_breaks ) {
			$string = preg_replace( '/[\r\n\t ]+/', ' ', $string );
		}

		return trim( $string );
	}

	function get_summary( $content ) {
		//summary
		$content = wp_strip_all_tags( $content, true );
		$words   = explode( " ", $content );
		$content = implode( " ", array_splice( $words, 0, 20 ) );
		$content .= strlen( $content ) ? "&#91;...&#93;" : "";

		return $content;
	}
}
