<?php
$cb_icon = empty( $cb_icon ) ? ( empty( $_POST['cb_icon'] ) ? false : true ) : true;
if ( empty( $_POST['icon_class'] ) ) : ?>
	<div class="image_placeholder thrv_wrapper<?php if ( ! empty( $cb_icon ) )
		echo ' thrv_icon tve_no_icons aligncenter' ?>">
		<a class="tve_click tve_green_button clearfix" href="javascript:void(0)"
		   data-ctrl="controls.lb_open" id="lb_icon"
		   data-wpapi="lb_icon"
		   data-btn-text="Insert Icon" data-load="1">
			<i class="tve_icm tve-ic-upload"></i>
			<span>Add Icon</span>
			<input type="hidden" name="cb_icon" value="<?php echo ! empty( $cb_icon ) ? '1' : '' ?>"/>
		</a>
	</div>
<?php else : ?>
	<div class="thrv_wrapper thrv_icon aligncenter<?php if ( ! empty( $cb_icon ) ) : ?> tve_no_drag tve_no_icons<?php endif ?>" style="font-size: 40px;">
		<span data-tve-icon="<?php echo $_POST['icon_class'] ?>" class="tve_sc_icon <?php echo $_POST['icon_class'] ?>"></span>
	</div>
<?php endif ?>