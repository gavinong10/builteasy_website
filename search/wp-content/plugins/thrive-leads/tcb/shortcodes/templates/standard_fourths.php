<?php /*
 <div class="thrv_wrapper thrv_columns tve_clearfix">
	<div class="tve_colm tve_foc"><p><?php echo sprintf( __( "Column %s", "thrive-cb" ), "1" ) ?></p></div>
	<div class="tve_colm tve_foc"><p><?php echo sprintf( __( "Column %s", "thrive-cb" ), "2" ) ?></p></div>
	<div class="tve_colm tve_foc"><p><?php echo sprintf( __( "Column %s", "thrive-cb" ), "3" ) ?></p></div>
	<div class="tve_colm tve_foc tve_lst"><p><?php echo sprintf( __( "Column %s", "thrive-cb" ), "4" ) ?></p></div>
</div>
 */ ?>
<div class="thrv_wrapper tcb-flex-row tcb--cols--4">
	<?php foreach ( range( 1, 4 ) as $i ) : ?>
		<div class="tcb-flex-col"><p><?php echo esc_html( sprintf( __( 'Column %s', 'thrive-cb' ), $i ) ) ?></p></div>
	<?php endforeach ?>
</div>