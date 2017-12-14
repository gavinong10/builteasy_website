    <div class="wd-subscribe">
		<div class="wd-subscribe-content">
			<div class="imgs">
				<img src="<?php echo $wd_options->wd_url_img . '/wp_logo.png'; ?>">
				<span>+</span>
				<img src="<?php echo $wd_options->wd_url_img . '/' . $wd_options->prefix . '_main_plugin.png'; ?>">
			</div>  
			<div class="texts">
				<p><?php _e( "Hi there", $wd_options->prefix ); ?>,</p>
				<p>
					<?php echo sprintf( __( "Allow %s to collect some usage data. This will allow  you to get more out of your plugin experience – get awesome customer support, receive exclusive deals and discounts on  premium products and more. You can choose to skip this step, %s will still work just fine.", $wd_options->prefix ), '<strong>Web-Dorado</strong>', $wd_options->plugin_title ); ?>
				</p>
			</div>
			<div class="btns">
				<a href="<?php echo "admin.php?page=" . $wd_options->prefix . "_subscribe&". $wd_options->prefix . "_sub_action=allow" ;?>" class="allow_and_continue"></a>
				<img src="<?php echo $wd_options->wd_url_img . '/loader.gif';?>" class="wd_loader">
				<a href="<?php echo "admin.php?page=" . $wd_options->prefix . "_subscribe&" . $wd_options->prefix . "_sub_action=skip" ;?>" class="skip" ></a>
			</div>
			<a href="#" class="permissions"><?php _e( "What data is being collected?" , $wd_options->prefix ); ?></a>
			<div class="list">
				<?php foreach( $list as $list_item ) { ?>
					<div class="list_item">
						<div class="list_img_wrap"><img src="<?php echo $list_item["img"]; ?>"></div>
						<div class="list_text_wrap">
						   <div class="list_title"><?php echo $list_item["title"]; ?></div>
						   <div class="list_text"><?php echo $list_item["small_text"]; ?></div>
						</div>
					</div>
				<?php } ?>
			</div>
        </div>
		<div class="wd-subscribe-footer">
			<ul class="wd-footer-menu">
				<li>
					<a href="https://web-dorado.com/web-dorado-privacy-statement.html" target="_blank">
						<?php _e( "Privacy Policy", $wd_options->prefix ); ?>
					</a>
					<span>.</span>
				</li>
				<li>
					<a href="https://web-dorado.com/terms-of-service.html" target="_blank">
						<?php _e( "Terms of Use", $wd_options->prefix ); ?>
					</a>
				</li>
			</ul>
		</div>
    </div>
