<div id="form_widget_menu">
	<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( 'Widget general options', 'thrive-leads' ) ?></span>
	<ul class="tve_menu">
		<?php $hide_default_colors = isset( $config['element_menu']['hide_default_colors'] ) ? $config['element_menu']['hide_default_colors'] : 1;
		include dirname( __FILE__ ) . '/_form_box.php' ?>
	</ul>
</div>