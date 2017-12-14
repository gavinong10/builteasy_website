<h4><?php echo __( "Save User Template", 'thrive-cb' ) ?></h4>
<hr class="tve_lightbox_line"/>
<?php if ( empty( $_POST['element'] ) ) : ?>
	<p><?php echo __( "You can save your work as a template for use on another post/page on your site.", "thrive-cb" ) ?></p>
<?php else : ?>
	<p><?php echo __( "You can save the current element as a template for use on another post / page on your site", "thrive-cb" ) ?></p>
	<input type="hidden" name="element" value="1"/>
<?php endif ?>
<input type="hidden" name="tve_lb_type" value="user_template">
<input class="tve_lightbox_input" name="template_name" id="template_name" placeholder="<?php echo __( "Enter Content Template name", "thrive" ) ?>"/>
<br/><br/>
<script type="text/javascript">
	jQuery('#template_name').focus();
</script>