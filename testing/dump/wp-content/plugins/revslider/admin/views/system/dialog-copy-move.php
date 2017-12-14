<?php if( !defined( 'ABSPATH') ) exit(); ?>

<div id="dialog_copy_move" data-textclose="<?php _e("Close",REVSLIDER_TEXTDOMAIN)?>" data-textupdate="<?php _e("Do It!",REVSLIDER_TEXTDOMAIN)?>" title="<?php _e("Copy / move slide",REVSLIDER_TEXTDOMAIN)?>" style="display:none">
	
	<br>
	
	<?php _e("Choose Slider",REVSLIDER_TEXTDOMAIN)?>:
	<?php echo $selectSliders; ?>
	
	<br><br>
	
	<?php _e("Choose Operation",REVSLIDER_TEXTDOMAIN)?>:
	
	<input type="radio" id="radio_copy" value="copy" name="copy_move_operation" checked />
	<label for="radio_copy" style="cursor:pointer;"><?php _e("Copy",REVSLIDER_TEXTDOMAIN)?></label>
	&nbsp; &nbsp;
	<input type="radio" id="radio_move" value="move" name="copy_move_operation" />
	<label for="radio_move" style="cursor:pointer;"><?php _e("Move",REVSLIDER_TEXTDOMAIN)?></label>		
	
</div>