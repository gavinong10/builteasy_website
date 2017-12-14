	<div class="wd-opacity wd-<?php echo $wd_options->prefix; ?>-opacity"></div>
	<div class="wd-deactivate-popup wd-<?php echo $wd_options->prefix; ?>-deactivate-popup">
		<div class="wd-deactivate-popup-opacity wd-deactivate-popup-opacity-<?php echo $wd_options->prefix; ?>">
			<img src="<?php echo $wd_options->wd_url_img . '/spinner.gif'; ?>" class="wd-img-loader" >
		</div>
		<form method="post" id="<?php echo $wd_options->prefix; ?>_deactivate_form">
			<div class="wd-deactivate-popup-header">
				<?php _e( "Please let us know why you are deactivating. Your answer will help us to serve you better", $wd_options->prefix ); ?>:
			</div>
			
			<div class="wd-deactivate-popup-body">
				<?php foreach( $deactivate_reasons as $deactivate_reason_slug => $deactivate_reason ) { ?>
					<div class="wd-<?php echo $wd_options->prefix; ?>-reasons">
						<input type="radio" value="<?php echo $deactivate_reason["id"];?>" id="<?php echo $wd_options->prefix . "-" .$deactivate_reason["id"]; ?>" name="<?php echo $wd_options->prefix; ?>_reasons" >
						<label for="<?php echo $wd_options->prefix . "-" . $deactivate_reason["id"]; ?>"><?php echo $deactivate_reason["text"];?></label>
					</div>
				<?php } ?>
				<div class="<?php echo $wd_options->prefix; ?>_additional_details_wrap"></div>
			</div>		
			<div class="wd-btns">
				<a href="#" data-val="1" class="button button-secondary button-close wd-<?php echo $wd_options->prefix; ?>-deactivate" id="wd-<?php echo $wd_options->prefix; ?>-deactivate"><?php _e( "Deactivate" , $wd_options->prefix ); ?></a>
				<a href="#" data-val="2" class="button button-secondary button-close wd-<?php echo $wd_options->prefix; ?>-deactivate" id="wd-<?php echo $wd_options->prefix; ?>-submit-and-deactivate" style="display:none;"><?php _e( "Submit and deactivate" , $wd_options->prefix ); ?></a>
				<a href="#" class="button button-primary  wd-<?php echo $wd_options->prefix; ?>-cancel"><?php _e( "Cancel" , $wd_options->prefix ); ?></a>				
			</div>
			<input type="hidden" name="<?php echo $wd_options->prefix . "_submit_and_deactivate"; ?>" value="" >
			<?php wp_nonce_field( $wd_options->prefix . '_save_form', $wd_options->prefix . '_save_form_fild'); ?>	
		</form>
	</div>