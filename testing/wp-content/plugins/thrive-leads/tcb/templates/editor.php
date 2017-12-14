<?php
/**
 * The template for displaying the main editor page
 *
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" style="height: 100%;overflow-y:hidden">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php echo get_the_title() . ' | ' . __( 'Thrive Visual Editor', 'thrive-cb' ); ?></title>
	<?php wp_head(); ?>
	<?php if ( tve_is_post_type_editable( get_post_type( get_the_ID() ) ) && is_admin_bar_showing() && ( is_single() || is_page() ) ) : ?>
		<style type="text/css">.thrive-adminbar-icon {
				padding-left: 25px !important;
				width: 20px !important;
				height: 20px !important;
				background: url('<?php echo tve_editor_css(); ?>/images/<?php echo isset( $_GET[ TVE_EDITOR_FLAG ] ) ? 'tcb-close-icon.png' : 'admin-bar-logo.png' ?>') no-repeat 0px 0px;
			}
		</style>
	<?php endif; ?>
</head>
<?php $cpanel_attr = tve_cpanel_attributes(); ?>
<body class="tcb-editor-main<?php echo $cpanel_attr['position'] === 'left' ? ' tve_cpanelFlip' : '' ?>" style="padding: 0;margin: 0;height: 100%;overflow-y:hidden;">
<div class="tcb-wrap-all" id="tve-main-frame">
	<div id="tve_page_loader" class="tve_page_loader tve-open">
		<div class="tve_loader_inner">
			<img src="<?php echo tve_editor_css() ?>/images/loader.gif" alt=""/>
		</div>
	</div>
	<?php include TVE_TCB_ROOT_PATH . 'editor/control_panel.php' ?>
	<div id="tcb-frame-container" class="tve-admin-position-top">
		<iframe id="tve-editor-frame" src="<?php echo $this->inner_frame_url() ?>"></iframe>
	</div>
</div>
<?php wp_footer() ?>
</body>
</html>
