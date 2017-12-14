<?php
$menu_id = 0;
if ( ! empty( $onpage_element ) ) {
	if ( ! empty( $menus ) ) {
		$_first  = reset( $menus );
		$menu_id = $_first['id'];
	}
} else {
	$menu_id = isset( $_POST['menu_id'] ) ? $_POST['menu_id'] : 0;
}

$attributes = array(
	'menu_id'       => $menu_id,
	'color'         => isset( $_POST['colour'] ) ? $_POST['colour'] : 'tve_red',
	'dir'           => isset( $_POST['dir'] ) ? $_POST['dir'] : 'tve_horizontal',
	'font_class'    => isset( $_POST['font_class'] ) ? $_POST['font_class'] : '',
	'font_size'     => isset( $_POST['font_size'] ) ? $_POST['font_size'] : '',
	'ul_attr'       => isset( $_POST['ul_attr'] ) ? $_POST['ul_attr'] : '',
	'link_attr'     => isset( $_POST['link_attr'] ) ? $_POST['link_attr'] : '',
	'top_link_attr' => isset( $_POST['top_link_attr'] ) ? $_POST['top_link_attr'] : '',
	'trigger_attr'  => isset( $_POST['trigger_attr'] ) ? $_POST['trigger_attr'] : '',
	'primary'       => empty( $_POST['primary'] ) ? '' : 1,
);

$attributes['font_class'] .= ( ! empty( $_POST['custom_class'] ) ? ' ' . $_POST['custom_class'] : '' );

?>
<?php if ( empty( $_POST['nowrap'] ) ) : ?>
	<div class="thrv_wrapper thrv_widget_menu" data-tve-style="<?php echo $attributes['dir'] ?>">
	<h2 class="tve_menu_title"></h2>
<?php endif ?>
	<div class="thrive-shortcode-config"
	     style="display: none !important"><?php echo '__CONFIG_widget_menu__' . json_encode( $attributes ) . '__CONFIG_widget_menu__' ?></div>
<?php echo tve_render_widget_menu( $attributes ) ?>
<?php if ( empty( $_POST['nowrap'] ) ) : ?></div><?php endif ?>