<div class="thrv_wrapper thrv_social thrv_social_default" data-tve-style="default">
	<?php $defaults = array(
		'selected' => array(
			'fb_share',
			'g_share',
			't_share'
		),
		'href'     => '',
		'type'     => 'default',
		'btn_type' => 'btn'
	) ?>
	<div class="thrive-shortcode-config"
	     style="display: none !important"><?php echo '__CONFIG_social_default__' . json_encode( $defaults ) . '__CONFIG_social_default__' ?></div>
	<?php echo tve_social_render_default( $defaults ) ?>
	<div class="tve_social_overlay"></div>
</div>