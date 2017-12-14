<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
$_path           = TVE_TCB_ROOT_PATH . 'shortcodes/templates/';
$ignore_elements = array(
	'sc_post_grid_vertical.php',
	'sc_thrive_leads_shortcode.php',
	'sc_thrive_ultimatum_shortcode.php',
	'sc_thrive_optin.php',
	'sc_thrive_posts_list.php',
	'thrv_image_load.php',
	'thrv_image_load_feature_gallery.php',
);

$elements = array_diff( scandir( $_path, 1 ), array_merge( array( '.', '..' ), $ignore_elements ) );

$onpage_element = true;
?>

<div style="display: none" id="tve_static_elements">
	<?php foreach ( $elements as $_elem ) : ?>
		<?php if ( ! in_array( $_elem, $ignore_elements, true ) ) : ?>
			<div data-elem="<?php echo esc_attr( str_replace( '.php', '', $_elem ) ) ?>">
				<?php include $_path . $_elem ?>
			</div>
		<?php endif ?>
	<?php endforeach ?>

	<?php // TODO: this should be moved from here using the filter below ?>
	<div data-elem="sc_thrive_ultimatum_shortcode">
		<div class="tve_custom_html_placeholder code_placeholder thrv_wrapper">
			<a class="tve_click tve_green_button clearfix" data-lb="lb_ultimatum_shortcode" data-ctrl="controls.lb_open">
				<i class="tve_icm tve-ic-code"></i>
				<span><?php echo __( "Insert Ultimatum Countdown", "thrive-cb" ) ?></span>
			</a>
		</div>
	</div>

	<?php
	/**
	 * Include wrapper for tcb elements
	 *
	 * @since: 1.200.5
	 */
	do_action( 'tcb_add_elements_wrapper' );
	?>
</div>
