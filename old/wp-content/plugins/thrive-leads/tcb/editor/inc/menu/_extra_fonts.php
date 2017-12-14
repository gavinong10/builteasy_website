<?php if ( ! empty( $extra_custom_fonts ) ) : ?>
	</ul>
	<strong><?php echo __( 'Imported fonts', 'thrive-cb' ) ?></strong>
	<div class="tve_clear"></div>
	<ul>
	<?php foreach ( $extra_custom_fonts as $font ) : ?>
		<li style="font-size:15px;line-height:28px" class="tve_click tve_font_selector <?php echo $font['font_class'] ?>"
		    data-cls="<?php echo $font['font_class'] ?>"><?php echo $font['font_name'] . ' ' . $font['font_size'] ?></li>
	<?php endforeach ?>
<?php endif ?>