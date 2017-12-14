<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package ThemeMove
 */

/**
 * ============================================================================
 * Header Class
 * ============================================================================
 *
 */
function header_class( $class = '' ) {
	echo 'class="' . join( ' ', get_header_class( $class ) ) . '"';
}

function get_header_class( $class = '' ) {
	$classes = array();

	$classes[] = 'header';

	$classes = array_map( 'esc_attr', $classes );

	$classes = apply_filters( 'header_class', $classes, $class );

	return array_unique( $classes );
}

/**
 * ============================================================================
 * Footer Class
 * ============================================================================
 *
 */
function footer_class( $class = '' ) {
	echo 'class="' . join( ' ', get_footer_class( $class ) ) . '"';
}

function get_footer_class( $class = '' ) {
	$classes = array();

	$classes[] = 'footer';

	$classes = array_map( 'esc_attr', $classes );

	$classes = apply_filters( 'footer_class', $classes, $class );

	return array_unique( $classes );
}

/**
 * ============================================================================
 * Check if the current view is rendering in the Customizer preview pane.
 * ============================================================================
 *
 * @return bool    True if in the preview pane.
 */
function thememove_is_preview() {
	global $wp_customize;

	return ( isset( $wp_customize ) && $wp_customize->is_preview() );
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function thememove_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}

add_filter( 'wp_page_menu_args', 'thememove_page_menu_args' );

/**
 * ============================================================================
 * Adds custom classes to the array of body classes.
 * ============================================================================
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function thememove_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	global $thememove_header_preset;
	if ( $thememove_header_preset == 'default' || $thememove_header_preset == '' ) {
		$classes[] = get_theme_mod( 'header_preset', header_preset );
	} elseif ( $thememove_header_preset != 'default' ) {
		$classes[] = $thememove_header_preset;
	} else {
		$classes[] = get_theme_mod( 'header_preset', header_preset );
	};

	if ( get_theme_mod( 'box_mode_enable' ) ) {
		$classes[] = 'boxed';
	}

	global $thememove_uncover_enable, $wc_thememove_uncover_enable;
	if ( ( get_theme_mod( 'footer_uncovering_enable', footer_uncovering_enable ) || $thememove_uncover_enable == 'enable' || $wc_thememove_uncover_enable == 'enable' ) && $thememove_uncover_enable != 'disable' && $wc_thememove_uncover_enable != 'disable' ) {
		$classes[] = 'uncover';
	}

	global $thememove_disable_title;
	if ( $thememove_disable_title == 'on' ) {
		$classes[] = 'disable-title';
	}

	global $thememove_bread_crumb_enable;
	if ( $thememove_bread_crumb_enable == 'disable' ) {
		$classes[] = 'disable-breadcrumb';
	}

	if ( get_post_meta( get_the_ID(), "thememove_contact_address", true ) && is_page_template() ) {
		$classes[] = 'map-enable';
	}

	global $thememove_sticky_header, $wc_thememove_sticky_header;
	if ( ( get_theme_mod( 'header_sticky_enable', header_sticky_enable ) || $thememove_sticky_header == 'enable' || $wc_thememove_sticky_header == 'enable' ) && $thememove_sticky_header != 'disable' && $wc_thememove_sticky_header != 'disable' ) {
		$classes[] = 'header-sticky';
	}

	global $thememove_header_top, $wc_thememove_header_top;
	if ( ( get_theme_mod( 'header_top_enable', header_top_enable ) || $thememove_header_top == 'enable' || $wc_thememove_header_top == 'enable' ) && $thememove_header_top != 'disable' && $wc_thememove_header_top != 'disable' ) {
		$classes[] = 'top-area-enable';
	}

	global $thememove_enable_page_layout_private, $thememove_page_layout_private;
	if ( get_theme_mod( 'site_layout', site_layout ) == 'full-width' || $thememove_page_layout_private == 'full-width' ) {
		$classes[] = 'full-width';
	} elseif ( get_theme_mod( 'site_layout', site_layout ) == 'content-sidebar' || $thememove_page_layout_private == 'content-sidebar' ) {
		$classes[] = 'content-sidebar';
	} elseif ( get_theme_mod( 'site_layout', site_layout ) == 'sidebar-content' || $thememove_page_layout_private == 'sidebar-content' ) {
		$classes[] = 'sidebar-content';
	}

	global $thememove_custom_class;
	if ( $thememove_custom_class ) {
		$classes[] = $thememove_custom_class;
	}

	$classes[] = 'scheme';

	if ( defined( 'TM_CORE_VERSION' ) ) {
		$classes[] = 'core_' . str_replace( ".", "", TM_CORE_VERSION );
	}

	return $classes;
}

add_filter( 'body_class', 'thememove_body_classes' );

/**
 * ============================================================================
 * Enable HTML code in WordPress Widget Titles
 * ============================================================================
 *
 */
function html_widget_title( $title ) {
//HTML tag opening/closing brackets
	$title = str_replace( '[', '<', $title );
	$title = str_replace( '[/', '</', $title );
// bold -- changed from 's' to 'strong' because of strikethrough code
	$title = str_replace( 'strong]', 'strong>', $title );
	$title = str_replace( 'b]', 'b>', $title );
// italic
	$title = str_replace( 'em]', 'em>', $title );
	$title = str_replace( 'i]', 'i>', $title );
// underline
// $title = str_replace( 'u]', 'u>', $title ); // could use this, but it is deprecated so use the following instead
	$title = str_replace( '<u]', '<span style="text-decoration:underline;">', $title );
	$title = str_replace( '</u]', '</span>', $title );
// superscript
	$title = str_replace( 'sup]', 'sup>', $title );
// subscript
	$title = str_replace( 'sub]', 'sub>', $title );
// del
	$title = str_replace( 'del]', 'del>', $title ); // del is like strike except it is not deprecated, but strike has wider browser support -- you might want to replace the following 'strike' section to replace all with 'del' instead
// strikethrough or <s></s>
	$title = str_replace( 'strike]', 'strike>', $title );
	$title = str_replace( 's]', 'strike>', $title ); // <s></s> was deprecated earlier than so we will convert it
	$title = str_replace( 'strikethrough]', 'strike>', $title ); // just in case you forget that it is 'strike', not 'strikethrough'
// tt
	$title = str_replace( 'tt]', 'tt>', $title ); // Will not look different in some themes, like Twenty Eleven -- FYI: http://reference.sitepoint.com/html/tt
// marquee
	$title = str_replace( 'marquee]', 'marquee>', $title );
// blink
	$title = str_replace( 'blink]', 'blink>', $title ); // only Firefox and Opera support this tag
// wtitle1 (to be styled in style.css using .wtitle1 class)
	$title = str_replace( '<wtitle1]', '<span class="wtitle1">', $title );
	$title = str_replace( '</wtitle1]', '</span>', $title );
// wtitle2 (to be styled in style.css using .wtitle2 class)
	$title = str_replace( '<wtitle2]', '<span class="wtitle2">', $title );
	$title = str_replace( '</wtitle2]', '</span>', $title );

	return $title;
}

add_filter( 'widget_title', 'html_widget_title' );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function thememove_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'thememove_setup_author' );

//custom comment form
function ThemeMove_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, $size = '100' ); ?>
		</div>
		<div class="comment-content">
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _e( 'Your comment is awaiting moderation.', 'thememove' ) ?></em>
				<br/>
			<?php endif; ?>
			<div class="metadata">
				<?php printf( __( '<cite class="fn">%s</cite>','thememove' ), get_comment_author_link() ) ?> <br/>
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
					<?php printf( __( '%1$s', 'thememove' ), get_comment_date(), get_comment_time() ) ?></a>
				<?php edit_comment_link( __( '(Edit)', 'thememove' ), '  ', '' ) ?>
			</div>
			<?php comment_text() ?>
			<?php comment_reply_link( array_merge( $args, array( 'depth'     => $depth,
			                                                     'max_depth' => $args['max_depth']
			) ) ) ?>
		</div>
	</div>
	<?php
}

function is_tree( $pid ) {      // $pid = The ID of the page we're looking for pages underneath
	global $post;         // load details about this page
	if ( is_page() && ( $post->post_parent == $pid || is_page( $pid ) ) ) {
		return true;
	}   // we're at the page or at a sub page
	else {
		return false;
	}  // we're elsewhere
}

;

function new_projects_fields( $fields ) {
	$fields['location'] = array(
		'name'        => __( 'Location', 'projects' ),
		'description' => __( 'Enter a location for this project.', 'projects' ),
		'type'        => 'text',
		'default'     => '',
		'section'     => 'info'
	);

	$fields['surface_area'] = array(
		'name'        => __( 'Surface Area', 'projects' ),
		'description' => __( 'Enter a surface area for this project.', 'projects' ),
		'type'        => 'text',
		'default'     => '',
		'section'     => 'info'
	);

	$fields['year_completed'] = array(
		'name'        => __( 'Year Completed', 'projects' ),
		'description' => __( 'Enter a year Completed for this project.', 'projects' ),
		'type'        => 'text',
		'default'     => '',
		'section'     => 'info'
	);

	$fields['value'] = array(
		'name'        => __( 'Value', 'projects' ),
		'description' => __( 'Enter a value for this project.', 'projects' ),
		'type'        => 'text',
		'default'     => '',
		'section'     => 'info'
	);

	$fields['architect'] = array(
		'name'        => __( 'Architect', 'projects' ),
		'description' => __( 'Enter a architect for this project.', 'projects' ),
		'type'        => 'text',
		'default'     => '',
		'section'     => 'info'
	);

	$pt_array = ( $pt_array = vc_editor_post_types() ) ? ( $pt_array ) : vc_default_editor_post_types(); // post type array
	if ( in_array( 'project', $pt_array ) ) {
		$fields['use_vc'] = array(
			'name'        => __( 'Use Visual Composer', 'projects' ),
			'description' => __( 'Use Visual Composer for this project', 'projects' ),
			'type'        => 'checkbox',
			'default'     => 'no',
			'section'     => 'info'
		);
	}

	return $fields;
}

add_filter( 'projects_custom_fields', 'new_projects_fields' );

/***
 * Get mini cart HTML
 * @return string
 */
if ( class_exists( 'WooCommerce' ) ) {
	function thememove__minicart() {

		$cart_html = '';
		$qty       = WC()->cart->get_cart_contents_count();

		$cart_html .= '<div class="mini-cart__button" title="' . __( 'View your shopping cart', 'thememove' ) . '">';
		$cart_html .= '<span class="mini-cart-icon"' . 'data-count="' . $qty . '"></span>';
		$cart_html .= '</div>';

		return $cart_html;
	}

	add_filter( 'woocommerce_add_to_cart_fragments', 'thememove_header_add_to_cart_fragment' );
}
/**
 * ======================================================================================
 * Ensure cart contents update when products are added to the cart via AJAX
 *
 * @param $fragments
 *
 * @return mixed
 * ======================================================================================
 */
if ( class_exists( 'WooCommerce' ) ) {
	function thememove_header_add_to_cart_fragment( $fragments ) {
		ob_start();

		$cart_html = thememove__minicart();

		echo $cart_html;

		$fragments['.mini-cart__button'] = ob_get_clean();

		return $fragments;
	}
}

/**
 * ============================================================================
 * Query post type
 * ============================================================================
 */
function thememove_posttype_query( $syntax ) {
	$query      = new WP_Query( $syntax );
	$record_set = array();
	$i          = 0;
	if ( $query->have_posts() ):
		while ( $query->have_posts() ):
			$query->the_post();
			if ( array_key_exists( get_the_title(), $record_set ) ) {
				$i ++;
				$record_set[ get_the_title() . ' - ' . $i ] = get_the_ID();
			} else {
				$record_set[ get_the_title() ] = get_the_ID();
			}
		endwhile;
	endif;

	return $record_set;
}

/**
 * ============================================================================
 * Job Manager
 * ============================================================================
 */
function display_job_date() {
	global $post;

	$expired = get_post_meta( $post->ID, '_job_expires', true );

	if ( $expired ) {
		echo $expired;
	}
}

function display_job_department() {
	global $post;

	$department = get_post_meta( $post->ID, '_job_department', true );

	if ( $department ) {
		echo $department;
	}
}

add_filter( 'job_manager_job_listing_data_fields', 'job_department' );
function job_department( $fields ) {
	$fields['_job_department'] = array(
		'label'       => __( 'Department', 'job_manager' ),
		'type'        => 'text',
		'placeholder' => '',
		'description' => ''
	);

	return $fields;
}

/**
 * Extra Info
 * =============
 */
function extra_info() {
	global $wp_version, $woocommerce;
	$parent_theme       = wp_get_theme( 'structure' );
	$child_theme        = wp_get_theme();
	$child_theme_in_use = false;
	if ( $parent_theme->name != $child_theme->name ) {
		$child_theme_in_use = true;
	}
	$vc_version = "Not activated";
	if ( defined( 'WPB_VC_VERSION' ) ) {
		$vc_version = "v" . WPB_VC_VERSION;
	}
	$tm_core_version = "Not activated";
	if ( defined( 'TM_CORE_VERSION' ) ) {
		$tm_core_version = "v" . TM_CORE_VERSION;
	}
	?>
	<!--
    * WordPress: v<?php echo $wp_version . "\n"; ?>
    * ThemMove Core: <?php echo $tm_core_version; ?><?php echo "\n"; ?>
    <?php if ( class_exists( 'WooCommerce' ) ) : ?>* WooCommerce: v<?php echo $woocommerce->version . "\n"; ?><?php else : ?>* WooCommerce: Not Installed <?php echo "\n"; ?><?php endif; ?>
    * Visual Composer: <?php echo $vc_version; ?><?php echo "\n"; ?>
    * Theme: <?php echo $parent_theme->name; ?> v<?php echo $parent_theme->version; ?> by <?php echo $parent_theme->get( 'Author' ) . "\n"; ?>
    * Child Theme: <?php if ( $child_theme_in_use == true ) { ?>Activated<?php } else { ?>Not activated<?php } ?><?php echo "\n"; ?>
    -->
<?php }

add_action( 'wp_head', 'extra_info', 9999 );