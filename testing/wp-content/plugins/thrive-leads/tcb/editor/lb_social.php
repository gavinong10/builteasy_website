<?php
$config   = isset( $_POST['config'] ) ? $_POST['config'] : array();
$element  = isset( $_POST['element'] ) ? explode( ' ', $_POST['element'] ) : array();
$selected = isset( $config['selected'] ) ? $config['selected'] : array();
$config   = stripslashes_deep( $config );
/**
 * {tcb_post_url} is just a placeholder that will get replaced at runtime, and it will also be automatically added from javascript
 * if any of the links are set to this, we need to clear it out, as the default post link is presented to the user as an empty input box
 */
foreach ( $config as $network => $settings ) {
	if ( isset( $settings['href'] ) && $settings['href'] == '{tcb_post_url}' ) {
		$config[ $network ]['href'] = '';
	}
}
$is_custom = isset( $config['type'] ) && $config['type'] == 'custom';


$available_networks = apply_filters( 'tcb_social_networks_visible', array(
	'fb_share'   => true,
	'g_share'    => true,
	't_share'    => true,
	'in_share'   => true,
	'pin_share'  => true,
	'xing_share' => true,
), $_POST );
?>
<h4><?php echo __( "Social Options", "thrive-cb" ) ?></h4>
<hr class="tve_lightbox_line"/>
<h6><?php if ( $config['type'] == 'custom' ) : ?><b><?php echo __( "Step 1.", 'thrive-cb' ) ?> </b><?php endif ?><?php echo __( "Select which buttons to display using the checkboxes below:", 'thrive-cb' ) ?></h6>
<div class="tve-social-items tve_gray_box">
	<?php if ( $available_networks['fb_share'] ) : ?>
		<span class="tve-social-item">
        <span class="thrv-icon-facebook2 thrv-social-option-icon"></span>
			<?php if ( ! $is_custom ) : ?>
				<div class="tve_lightbox_input_holder">
			    <input type="checkbox" class="config-enabled tve_change" id="fb_like"
					   data-ctrl="function:social.enabledChange"
					   value="fb_like"<?php echo in_array( 'fb_like', $selected ) ? ' checked' : '' ?>>
			    <label for="fb_like"><?php echo __( "Facebook like", "thrive-cb" ) ?></label>
		    </div>
			<?php endif ?>
			<div class="tve_lightbox_input_holder">
		    <input type="checkbox" class="config-enabled tve_change" id="fb_share"
				   data-ctrl="function:social.enabledChange"
				   value="fb_share"<?php echo in_array( 'fb_share', $selected ) ? ' checked' : '' ?>>
		    <label for="fb_share"><?php echo __( "Facebook share", "thrive-cb" ) ?></label>
	    </div>
    </span>
	<?php endif ?>
	<?php if ( $available_networks['g_share'] ) : ?>
		<span class="tve-social-item">
        <span class="thrv-icon-google-plus2 thrv-social-option-icon"></span>
			<?php if ( ! $is_custom ) : ?>
				<div class="tve_lightbox_input_holder">
			    <input type="checkbox" class="config-enabled tve_change" id="g_plus"
					   data-ctrl="function:social.enabledChange"
					   value="g_plus"<?php echo in_array( 'g_plus', $selected ) ? ' checked' : '' ?>>
			    <label for="g_plus"><?php echo __( "Google +1", "thrive-cb" ) ?></label>
		    </div>
			<?php endif ?>
			<div class="tve_lightbox_input_holder">
		    <input type="checkbox" class="config-enabled tve_change" id="g_share"
				   data-ctrl="function:social.enabledChange"
				   value="g_share"<?php echo in_array( 'g_share', $selected ) ? ' checked' : '' ?>>
		    <label for="g_share"><?php echo __( "Google share", "thrive-cb" ) ?></label>
	    </div>
    </span>
	<?php endif ?>
	<?php if ( $available_networks['t_share'] ) : ?>
		<span class="tve-social-item">
        <span class="thrv-icon-twitter2 thrv-social-option-icon"></span>
        <div class="tve_lightbox_input_holder">
	        <input type="checkbox" class="config-enabled tve_change" id="t_share"
				   data-ctrl="function:social.enabledChange"
				   value="t_share"<?php echo in_array( 't_share', $selected ) ? ' checked' : '' ?>>
	        <label for="t_share"><?php echo __( "Twitter tweet", "thrive-cb" ) ?></label>
        </div>
			<?php if ( ! $is_custom ) : ?>
				<div class="tve_lightbox_input_holder">
			    <input type="checkbox" class="config-enabled tve_change" id="t_follow"
					   data-ctrl="function:social.enabledChange"
					   value="t_follow"<?php echo in_array( 't_follow', $selected ) ? ' checked' : '' ?>>
			    <label for="t_follow"><?php echo __( "Twitter follow", "thrive-cb" ) ?></label>
		    </div>
			<?php endif ?>
    </span>
	<?php endif ?>
	<?php if ( $available_networks['in_share'] ) : ?>
		<span class="tve-social-item">
        <span class="thrv-icon-linkedin thrv-social-option-icon"></span>
        <div class="tve_lightbox_input_holder">
	        <input type="checkbox" class="config-enabled tve_change" id="in_share"
				   data-ctrl="function:social.enabledChange"
				   value="in_share"<?php echo in_array( 'in_share', $selected ) ? ' checked' : '' ?>>
	        <label for="in_share"><?php echo __( "Linked in share", "thrive-cb" ) ?></label>
        </div>
    </span>
	<?php endif ?>
	<?php if ( $available_networks['pin_share'] ) : ?>
		<span class="tve-social-item">
        <span class="thrv-icon-pinterest2 thrv-social-option-icon"></span>
            <div class="tve_lightbox_input_holder">
	            <input type="checkbox" class="config-enabled tve_change" id="pin_share"
					   data-ctrl="function:social.enabledChange"
					   value="pin_share"<?php echo in_array( 'pin_share', $selected ) ? ' checked' : '' ?>>
	            <label for="pin_share"><?php echo __( "Pinterest pin", "thrive-cb" ) ?></label>
            </div>
    </span>
	<?php endif ?>
	<?php if ( $available_networks['xing_share'] ) : ?>
		<span class="tve-social-item">
        <span class="thrv-icon-xing thrv-social-option-icon"></span>
        <div class="tve_lightbox_input_holder">
	        <input type="checkbox" class="config-enabled tve_change" id="xing_share"
				   data-ctrl="function:social.enabledChange"
				   value="xing_share"<?php echo in_array( 'xing_share', $selected ) ? ' checked' : '' ?>>
	        <label for="xing_share"><?php echo __( "Xing share", "thrive-cb" ) ?></label>
        </div>
    </span>
	<?php endif ?>
	<div class="tve_clear"></div>
</div>
<div class="tve_clear"></div>
<br/><br/>
<div id="tve-social-custom-config">
	<h6><b><?php echo __( "Step 2.", "thrive-cb" ) ?></b> <?php echo __( "Configure the options for each button:", "thrive-cb" ) ?></h6>

	<div id="tve-lb-form">
		<div class="thrv_wrapper thrv_tabs_shortcode">
			<div class="tve_scT tve_vtabs tve_green">
				<ul class="tve_clearfix">
					<?php if ( ! $is_custom ) : ?>
						<li data-ctrl="function:social.configTabClick"
							class="tve_click tve_tab_fb_like"<?php if ( ! in_array( 'fb_like', $selected ) )
							echo ' style="display:none"' ?>>
							<span class="tve_scTC1"><?php echo __( "Facebook Like", "thrive-cb" ) ?></span></li>
					<?php endif ?>
					<li data-ctrl="function:social.configTabClick"
						class="tve_click tve_tab_fb_share"<?php if ( ! in_array( 'fb_share', $selected ) )
						echo ' style="display:none"' ?>>
						<span class="tve_scTC1"><?php echo __( "Facebook Share", "thrive-cb" ) ?></span></li>
					<?php if ( ! $is_custom ) : ?>
						<li data-ctrl="function:social.configTabClick"
							class="tve_click tve_tab_g_plus"<?php if ( ! in_array( 'g_plus', $selected ) )
							echo ' style="display:none"' ?>>
							<span class="tve_scTC1"><?php echo __( "Google +1", "thrive-cb" ) ?></span></li>
					<?php endif ?>
					<li data-ctrl="function:social.configTabClick"
						class="tve_click tve_tab_g_share"<?php if ( ! in_array( 'g_share', $selected ) )
						echo ' style="display:none"' ?>>
						<span class="tve_scTC1"><?php echo __( "Google Share", "thrive-cb" ) ?></span></li>
					<li data-ctrl="function:social.configTabClick"
						class="tve_click tve_tab_t_share"<?php if ( ! in_array( 't_share', $selected ) )
						echo ' style="display:none"' ?>>
						<span class="tve_scTC1"><?php echo __( "Twitter Tweet", "thrive-cb" ) ?></span></li>
					<?php if ( ! $is_custom ) : ?>
						<li data-ctrl="function:social.configTabClick"
							class="tve_click tve_tab_t_follow"<?php if ( ! in_array( 't_follow', $selected ) )
							echo ' style="display:none"' ?>>
							<span class="tve_scTC1"><?php echo __( "Twitter Follow", "thrive-cb" ) ?></span></li>
					<?php endif ?>
					<li data-ctrl="function:social.configTabClick"
						class="tve_click tve_tab_in_share"<?php if ( ! in_array( 'in_share', $selected ) )
						echo ' style="display:none"' ?>>
						<span class="tve_scTC1"><?php echo __( "Linked In Share", "thrive-cb" ) ?></span></li>
					<li data-ctrl="function:social.configTabClick"
						class="tve_click tve_tab_pin_share"<?php if ( ! in_array( 'pin_share', $selected ) )
						echo ' style="display:none"' ?>>
						<span class="tve_scTC1"><?php echo __( "Pinterest Pin", "thrive-cb" ) ?></span></li>
					<li data-ctrl="function:social.configTabClick"
						class="tve_click tve_tab_xing_share"<?php if ( ! in_array( 'xing_share', $selected ) )
						echo ' style="display:none"' ?>>
						<span class="tve_scTC1"><?php echo __( "Xing Share", "thrive-cb" ) ?></span></li>

				</ul>

				<?php if ( ! $is_custom ) : ?>
					<div class="tve_scTC tve_tc_fb_like tve_social_config_container"
						 style="display: none;padding: 20px; min-height: 226px;" data-config="fb_like">
						<h4><?php echo __( "Facebook Like Settings", "thrive-cb" ) ?></h4>

						<div class="social-settings">

							<label class="tve_lightbox_label"><?php echo __( "URL to like", "thrive-cb" ) ?></label>
							<input type="text" class="tve_lightbox_input tve_lightbox_input_inline social-config-text"
								   name="href"
								   value="<?php echo isset( $config['fb_like']['href'] ) ? $config['fb_like']['href'] : '' ?>"/>
							<button data-ctrl="function:social.copyLink"
									class="tve_click tve-btn-inline tve_editor_button tve_editor_button_default"><span><?php echo __( "Use this URL for all social networks", "thrive-cb" ) ?></span>
							</button>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment"><?php echo __( "You can leave this empty to use the URL on which the button is located", 'thrive-cb' ) ?></span>

							<div class="tve-sp"></div>
						</div>
					</div>
				<?php endif ?>

				<div class="tve_scTC tve_tc_fb_share tve_social_config_container"
					 style="display: none;padding: 20px; min-height: 226px;" data-config="fb_share">
					<h4><?php echo __( "Facebook Share Settings", "thrive-cb" ) ?></h4>

					<div class="social-settings">

						<?php if ( $is_custom ) : ?>
							<div class="tve_message tve_success">
								<?php echo __( 'Please note: in order to use the Facebook "Feed Dialog" (this is the default option), you need to create a Facebook App for your website.', "thrive-cb" ) ?>
								<a class="tve_lightbox_link tve_lightbox_link_create" target="_blank" href="https://thrivethemes.com/tkb_item/facebook-app-id/"><?php echo __( "Click here to learn how to set up a Facebook App.", "thrive-cb" ) ?></a>
								<br>
								<?php echo sprintf( __( 'Alternatively, you can just use the "Share Dialog" from the %s', "thrive-cb" ), '<a href="javascript:void(0)" class="tve_click tve_lightbox_link tve_lightbox_link_info" data-target="#tve-s-fb-advanced" data-ctrl="function:social.toggleDisplay">' . __( "Advanced options", 'thrive-cb' ) . '</a>.' ) ?>
							</div>
							<div class="tve-sp"></div>

							<label class="tve_lightbox_label"><?php echo __( "App ID", "thrive-cb" ) ?></label>
							<input
									type="text"<?php echo ! empty( $_POST['social_fb_app_id'] ) ? ' readonly="readonly"' : '' ?>
									class="tve_lightbox_input tve_lightbox_input_inline tve_change" name="app_id"
									data-ctrl-change="function:social.syncValue"
									data-update-selector=".tve_tc_fb_like input[name='app_id']"
									value="<?php echo isset( $_POST['social_fb_app_id'] ) ? $_POST['social_fb_app_id'] : '' ?>"/>
							<a<?php echo ! empty( $_POST['social_fb_app_id'] ) ? ' style="display:none"' : '' ?>
									href="javascript:void(0)"
									class="tve_editor_button tve_editor_button_success tve_fb_id_invalid tve_click tve-inline"
									data-ctrl="function:networks.fb_share.validateAppID">
								<?php echo __( "Validate App ID", "thrive-cb" ) ?>
							</a>
							<a<?php echo empty( $_POST['social_fb_app_id'] ) ? ' style="display:none"' : '' ?>
									href="javascript:void(0)"
									class="tve_editor_button tve_editor_button_success tve_fb_id_valid tve_btn_success tve_click tve-inline"
									data-ctrl="function:networks.fb_share.changeAppID">
								<?php echo __( "Change", 'thrive-cb' ) ?>
							</a>
							<span<?php echo empty( $_POST['social_fb_app_id'] ) ? ' style="display:none"' : '' ?>
									class="tve_fb_id_valid tve_span_success"><?php echo __( "Facebook App ID validated.", "thrive-cb" ) ?></span>
							<div class="tve-sp"></div>
						<?php endif ?>
						<div class="tve-sp"></div>
						<?php if ( ! in_array( 'tcb-no-url', $element ) ): ?>
							<label class="tve_lightbox_label"><?php echo __( "URL to share", "thrive-cb" ) ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="href"
								   value="<?php echo isset( $config['fb_share']['href'] ) ? $config['fb_share']['href'] : '' ?>"/>
							<button data-ctrl="function:social.copyLink"
									class="tve_click tve-btn-inline tve_editor_button tve_editor_button_default"><span><?php echo __( "Use this URL for all social networks", "thrive-cb" ) ?></span>
							</button>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment"><?php echo __( "You can leave this empty to use the URL on which the button is located", "thrive-cb" ) ?></span>

							<div class="tve-sp"></div>
						<?php endif; ?>
						<?php if ( $is_custom ) : ?>
							<label class="tve_lightbox_label"><?php echo __( "Button label", "thrive-cb" ) ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="label"
								   value="<?php echo isset( $config['fb_share']['label'] ) ? $config['fb_share']['label'] : 'Share' ?>"/>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment"><?php echo __( "Give the text to be shown on the button", "thrive-cb" ) ?></span>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label"></label>
							<a href="javascript:void(0)" class="tve_click tve_lightbox_link tve_lightbox_link_toggle"
							   data-ctrl="function:social.toggleDisplay"
							   data-target="#tve-s-fb-advanced"><?php echo __( "Show / hide advanced options", "thrive-cb" ) ?></a>
							<div class="tve-sp"></div>
							<div id="tve-s-fb-advanced" style="display:none">
								<h6 class="tve_text_center">
									<?php echo __( "Which Sharing Method Would You Like To Use?", "thrive-cb" ) ?>
								</h6>

								<div class="tve-fb-share-types tve_clearfix">
									<div
											class="tve_text_center tve_click tve-half tve_selectable<?php if ( empty( $config['fb_share']['type'] ) || $config['fb_share']['type'] == 'feed' )
												echo ' tve_selected' ?>"
											data-ctrl="function:networks.fb_share.setup.type" data-type="feed">
										<img src="<?php echo tve_editor_css() . '/images/fb-share.png' ?>" alt=""/">
										<div class="tve-sp"></div>
										<h6>
											<?php echo __( "Feed Dialog", "thrive-cb" ) ?>
										</h6>

										<p><?php echo __( "This is the default Facebook sharing option. Using the feed dialog is the right choice in most cases.", "thrive-cb" ) ?></p>
									</div>
									<div
											class="tve_text_center tve_click tve-half tve_selectable<?php if ( ! empty( $config['fb_share']['type'] ) && $config['fb_share']['type'] == 'share' )
												echo ' tve_selected' ?>"
											data-ctrl="function:networks.fb_share.setup.type" data-type="share">
										<img src="<?php echo tve_editor_css() . '/images/fb-feed.png' ?>" alt=""/">
										<div class="tve-sp"></div>
										<h6><?php echo __( "Share Dialog", "thrive-cb" ) ?></h6>

										<p><?php echo __( "This is an older method of Facebook sharing. Use this if you have a specific reason not to use the feed dialog.", "thrive-cb" ) ?></p>
									</div>
								</div>
								<input type="hidden" name="type" id="social-config-fb_share-type"
									   class="social-config-text"
									   value="<?php echo isset( $config['fb_share']['type'] ) ? $config['fb_share']['type'] : 'feed' ?>">

								<div class="tve-sp"></div>
								<div
										id="fb-feed-options"<?php if ( ! empty( $config['fb_share']['type'] ) && $config['fb_share']['type'] == 'share' )
									echo ' style="display: none"' ?>>
									<div class="tve-sp"></div>
									<p><?php echo __( "By default, the shared message is extracted from the open graph meta tags in the page content. You can, however, override this by adding custom information below.", "thrive-cb" ) ?></p>

									<div class="tve-sp"></div>
									<label class="tve_lightbox_label"><?php echo __( "Link name", "thrive-cb" ) ?></label>
									<input type="text"
										   class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
										   name="name"
										   value="<?php echo isset( $config['fb_share']['name'] ) ? $config['fb_share']['name'] : '' ?>"/>

									<div class="tve-sp"></div>
									<label class="tve_lightbox_label">&nbsp;</label>
									<span class="tve_comment"><?php echo __( "This is the main title of the link that is to be shared. Quick tip: for maximum exposure, choose a name that gets the interest of people so that they click through to the URL that you are sharing.", "thrive-cb" ) ?></span>

									<div class="tve-sp"></div>
									<label class="tve_lightbox_label"><?php echo __( "Caption", "thrive-cb" ) ?></label>
									<input type="text"
										   class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
										   name="caption"
										   value="<?php echo isset( $config['fb_share']['caption'] ) ? $config['fb_share']['caption'] : '' ?>"/>

									<div class="tve-sp"></div>
									<label class="tve_lightbox_label">&nbsp;</label>
									<span class="tve_comment">
                                        <?php echo __( "This is shown immediately under the link name. If left blank this will be the URL of the link.", "thrive-cb" ) ?>
                                    </span>

									<div class="tve-sp"></div>
									<label class="tve_lightbox_label"><?php echo __( "Description", 'thrive-cb' ) ?></label>
									<textarea class="social-config-text tve_lightbox_textarea tve_lightbox_input_inline"
											  name="description"><?php echo isset( $config['fb_share']['description'] ) ? $config['fb_share']['description'] : '' ?></textarea>

									<div class="tve-sp"></div>
									<label class="tve_lightbox_label">&nbsp;</label>
									<span class="tve_comment">
                                        <?php echo __( "Give a description of the link. If not set then this is automatically populated using data from the linked page.", "thrive-cb" ) ?>
                                    </span>

									<?php if ( ! in_array( 'tcb-no-url', $element ) ): ?>
										<div class="tve-sp"></div>

										<div class="tve-field-value">
											<label for="" class="tve_lightbox_label"><?php echo __( "Image URL", "thrive-cb" ) ?></label>
											<a href="javascript:void(0)"
											   class="tve_editor_button tve_editor_button_success tve_mousedown"
											   data-ctrl="function:social.openMedia">
												<?php echo __( "Choose image", "thrive-cb" ) ?>
											</a>
											&nbsp;
											<?php $fb_image = isset( $config['fb_share']['image'] ) ? $config['fb_share']['image'] : '' ?>
											<a href="javascript:void(0)"<?php if ( ! $fb_image )
												echo ' style="display:none"' ?>
											   class="tve_editor_button tve_editor_button_cancel tve-s-remove-img tve_click"
											   data-ctrl="function:social.removeMedia">
												<?php echo __( "Remove image", "thrive-cb" ) ?>
											</a>
											<div class="tve-sp"></div>
											<label class="tve_lightbox_label">&nbsp;</label>
											<input type="hidden" class="social-config-text tve-image-value" name="image"
												   value="<?php echo $fb_image ?>"/>
											<img src="<?php echo $fb_image ?>" class="tve-s-custom-image"
												 style="max-width:200px;<?php if ( ! $fb_image )
												     echo ' display: none' ?>">

											<div class="tve-sp"></div>
											<label class="tve_lightbox_label">&nbsp;</label>
											<span class="tve_comment">
                                            	<?php echo __( "The URL of a picture attached to this post. Recommended image size is 200px x 200px with a maximum aspect ratio of 3:1.", 'thrive-cb' ) ?>
                                        	</span>
											<div class="tve-sp"></div>
										</div>
									<?php endif; ?>

								</div>
							</div>
						<?php endif ?>
					</div>
				</div>

				<?php if ( ! $is_custom ) : ?>
					<div class="tve_scTC tve_tc_g_plus tve_social_config_container"
						 style="display: none;padding: 20px; min-height: 226px;" data-config="g_plus">
						<div class="social-settings">
							<h4><?php echo __( "Google +1 Settings", "thrive-cb" ) ?></h4>

							<div class="tve-sp"></div>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">URL to +1</label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="href"
								   value="<?php echo isset( $config['g_plus']['href'] ) ? $config['g_plus']['href'] : '' ?>"/>
							<button data-ctrl="function:social.copyLink"
									class="tve_click tve-btn-inline tve_editor_button tve_editor_button_default">
								<?php echo __( "Use this URL for all social networks", "thrive-cb" ) ?>
							</button>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                    <?php echo __( "You can leave this empty to use the URL on which the button is located", "thrive-cb" ) ?>
                                </span>

							<div class="tve-sp"></div>
						</div>
					</div>
				<?php endif ?>

				<div class="tve_scTC tve_tc_g_share tve_social_config_container"
					 style="display: none;padding: 20px; min-height: 226px;" data-config="g_share">
					<div class="social-settings">
						<h4>
							<?php echo __( "Google Share Settings", "thrive-cb" ) ?>
						</h4>

						<div class="tve-sp"></div>
						<div class="tve-sp"></div>
						<?php if ( ! in_array( 'tcb-no-url', $element ) ): ?>
							<label class="tve_lightbox_label"><?php echo __( "URL to share", "thrive-cb" ) ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="href"
								   value="<?php echo isset( $config['g_share']['href'] ) ? $config['g_share']['href'] : '' ?>"/>
							<button data-ctrl="function:social.copyLink"
									class="tve_click tve_editor_button tve_editor_button_default">
								<?php echo __( "Use this URL for all social networks", "thrive-cb" ) ?>
							</button>

							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                <?php echo __( "You can leave this empty to use the URL on which the button is located.", "thrive-cb" ) ?>
                            </span>

							<div class="tve-sp"></div>
						<?php endif; ?>

						<?php if ( $is_custom ) : ?>
							<label class="tve_lightbox_label"><?php echo __( "Button label", "thrive-cb" ) ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="label"
								   value="<?php echo isset( $config['g_share']['label'] ) ? $config['g_share']['label'] : 'Share +1' ?>"/>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                    <?php echo __( "Give the text to be shown on the button", "thrive-cb" ) ?>
                                </span>
							<div class="tve-sp"></div>
						<?php endif ?>
					</div>
				</div>

				<div class="tve_scTC tve_tc_t_share tve_social_config_container"
					 style="display: none;padding: 20px; min-height: 226px;" data-config="t_share">
					<div class="social-settings">
						<h4>
							<?php echo __( "Twitter Tweet Settings", "thrive-cb" ) ?>
						</h4>
						<?php if ( $is_custom ) : ?>
							<div class="tve_message tve_warning" style="margin-top: -10px">
								<?php echo sprintf( __( 'Please note: Twitter has removed support for the public URL share counter, as stated %shere%s.', "thrive-cb" ), '<a href="https://blog.twitter.com/2015/hard-decisions-for-a-sustainable-platform" target="_blank">', '</a>' ) ?>
								<?php echo __( 'As a result, we are not able to display share counts for the URL - the individual Twitter counts will be hidden', "thrive-cb" ) ?>
							</div>
							<div class="tve-sp"></div>
						<?php endif ?>
						<?php if ( ! in_array( 'tcb-no-url', $element ) ): ?>
							<label class="tve_lightbox_label"><?php echo __( "URL to tweet", "thrive-cb" ) ?></label>
							<input id="ts-url" type="text" data-ctrl="function:networks.t_share.setup.tweetLength"
								   class="tve_keyup social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="href"
								   value="<?php echo isset( $config['t_share']['href'] ) ? $config['t_share']['href'] : '' ?>"/>
							<button data-ctrl="function:social.copyLink"
									class="tve_click tve-btn-inline tve_editor_button tve_editor_button_default">
								<?php echo __( "Use this URL for all social networks", "thrive-cb" ) ?>
							</button>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                <?php echo __( "You can leave this empty to use the URL on which the button is located", "thrive-cb" ) ?>
                            </span>

							<div class="tve-sp"></div>
						<?php endif; ?>
						<label class="tve_lightbox_label"><?php echo __( "Tweet", 'thrive-cb' ) ?></label>
						<textarea id="ts-tweet" data-ctrl="function:networks.t_share.setup.tweetLength"
								  class="tve_keyup social-config-text tve_lightbox_textarea tve_lightbox_input_inline"
								  name="tweet"><?php echo isset( $config['t_share']['tweet'] ) ? $config['t_share']['tweet'] : '' ?></textarea>

						<div class="tve-sp"></div>
						<label class="tve_lightbox_label">&nbsp;</label>
						<span class="tve_comment">
                            <span id="tve-s-t-counter" class="tve_comment"><?php echo sprintf( __( "%s  characters remaining", "thrive-cb" ), '<span class="c-cnt">140</span>' ) ?></span></span>

						<div class="tve-sp"></div>
						<div class="tve-sp"></div>
						<label class="tve_lightbox_label">&nbsp;</label>
						<span class="tve_comment">
                                <?php echo __( "Type a message to tweet. If left empty then it will use the default tweet text extracted from the URL to be tweeted. You can use the {tcb_post_title} shortcode to post a title into the tweet.", 'thrive-cb' ) ?>
                            </span>

						<div class="tve-sp"></div>
						<label class="tve_lightbox_label"><?php echo __( "Via", "thrive-cb" ) ?></label>
						<input id="ts-via" data-ctrl="function:networks.t_share.setup.tweetLength" type="text"
							   class="tve_keyup social-config-text tve_lightbox_input tve_lightbox_input_inline"
							   name="via"
							   value="<?php echo isset( $config['t_share']['via'] ) ? $config['t_share']['via'] : '' ?>"/>

						<div class="tve-sp"></div>
						<label class="tve_lightbox_label">&nbsp;</label>
						<span class="tve_comment">
                                <?php echo __( "Optional. Screen name of the user to attribute the Tweet to (without @)", "thrive-cb" ) ?>
                            </span>

						<div class="tve-sp"></div>
						<?php if ( $is_custom ) : ?>
							<?php
							/* Twitter removed the public API for retrieving counts. this option is not needed anymore
							<label class="tve_lightbox_label"><?php echo __("Counter URL", "thrive-cb") ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="counter"
								   value="<?php echo isset($config['t_share']['counter']) ? $config['t_share']['counter'] : '' ?>"/>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
								<?php echo __("Optional. If you've shortened your link, add the full URL for the share counter here.", 'thrive-cb') ?>
							</span>
							<div class="tve-sp"></div>
							*/ ?>
							<label class="tve_lightbox_label"><?php echo __( "Button Label", 'thrive-cb' ) ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="label"
								   value="<?php echo isset( $config['t_share']['label'] ) ? $config['t_share']['label'] : 'Tweet' ?>"/>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                <?php echo __( "Optional. Give the text to be shown on the button", "thrive-cb" ) ?>
                            </span>
							<div class="tve-sp"></div>
						<?php endif ?>
					</div>
				</div>

				<?php if ( ! $is_custom ) : ?>
					<div class="tve_scTC tve_tc_t_follow tve_social_config_container"
						 style="display: none;padding: 20px; min-height: 226px;" data-config="t_follow">

						<div class="social-settings">
							<h4>
								<?php echo __( "Twitter Follow Settings", "thrive-cb" ) ?>
							</h4>
							<label class="tve_lightbox_label"><?php echo __( "User to Follow", "thrive-cb" ) ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="username"
								   value="<?php echo isset( $config['t_follow']['username'] ) ? $config['t_follow']['username'] : '' ?>"/>

							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                    <?php echo __( "Add here the username you want to be follow. Example: myusername", "thrive-cb" ) ?>
                            </span>

							<div class="tve-sp"></div>
							<label class="tve_lightbox_label"><?php echo __( "Hide Username", "thrive-cb" ) ?></label>
							<label class="tve_switch">
								<input type="checkbox" class="social-config-text" name="hide_username"
									   value="1" <?php echo isset( $config['t_follow']['hide_username'] ) && $config['t_follow']['hide_username'] == 1 ? 'checked="checked"' : "" ?> />
								<span></span>
							</label>

							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                <?php echo __( "Set on to hide the username on the button", "thrive-cb" ) ?>
                            </span>

							<div class="tve-sp"></div>
						</div>
					</div>
				<?php endif ?>

				<div class="tve_scTC tve_tc_in_share tve_social_config_container"
					 style="display: none;padding: 20px; min-height: 226px;" data-config="in_share">

					<div class="social-settings">
						<h4>
							<?php echo __( "Linked In Share Settings", "thrive-cb" ) ?>
						</h4>
						<label class="tve_lightbox_label"><?php echo __( "Url to Share", "thrive-cb" ) ?></label>
						<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
							   name="href"
							   value="<?php echo isset( $config['in_share']['href'] ) ? $config['in_share']['href'] : '' ?>"/>
						<button data-ctrl="function:social.copyLink"
								class="tve_click tve-btn-inline tve_editor_button tve_editor_button_default">
							<?php echo __( "Use this URL for all social networks", "thrive-cb" ) ?>
						</button>
						<div class="tve-sp"></div>
						<label class="tve_lightbox_label">&nbsp;</label>
						<span class="tve_comment">
                                <?php echo __( "You can leave this empty to use the URL on which the button is located", "thrive-cb" ) ?>
                            </span>

						<div class="tve-sp"></div>

						<?php if ( $is_custom ) : ?>
							<label class="tve_lightbox_label"><?php echo __( "Button label", "thrive-cb" ) ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="label"
								   value="<?php echo isset( $config['in_share']['label'] ) ? $config['in_share']['label'] : 'Share' ?>"/>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                <?php echo __( "Give the text to be shown on the button", "thrive-cb" ) ?>
                            </span>
							<div class="tve-sp"></div>
						<?php endif ?>

					</div>
				</div>

				<div class="tve_scTC tve_tc_pin_share tve_social_config_container"
					 style="display: none;padding: 20px; min-height: 226px;" data-config="pin_share">

					<div class="social-settings">
						<h4>
							<?php echo __( "Pinterest Pin Settings", 'thrive-cb' ) ?>
						</h4>
						<label class="tve_lightbox_label"><?php echo __( "Url to Share", "thrive-cb" ) ?></label>
						<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
							   name="href"
							   value="<?php echo isset( $config['pin_share']['href'] ) ? $config['pin_share']['href'] : '' ?>"/>
						<button data-ctrl="function:social.copyLink"
								class="tve_click tve-btn-inline tve_editor_button tve_editor_button_default"><span><?php echo __( "Use this URL for all social networks", "thrive-cb" ) ?></span>
						</button>
						<div class="tve-sp"></div>
						<label class="tve_lightbox_label">&nbsp;</label>
						<span class="tve_comment">
                                <?php echo __( "You can leave this empty to use the URL on which the button is located", 'thrive-cb' ) ?>
                            </span>

						<div class="tve-sp"></div>
						<div class="tve-field-value">
							<label class="tve_lightbox_label"><?php echo __( "Image URL", "thrive-cb" ) ?></label>
							<a href="javascript:void(0)" class="tve_editor_button tve_editor_button_success tve_mousedown"
							   data-ctrl="function:social.openMedia">
								<?php echo __( "Choose image", "thrive-cb" ) ?>
							</a>
							&nbsp;
							<?php $pin_image = isset( $config['pin_share']['media'] ) && $config['pin_share']['media'] != '{tcb_post_image}' ? $config['pin_share']['media'] : '' ?>
							<a href="javascript:void(0)"<?php if ( ! $pin_image )
								echo ' style="display:none"' ?>
							   class="tve-s-remove-img tve_click tve_editor_button tve_editor_button_cancel"
							   data-ctrl="function:social.removeMedia">
								<?php echo __( "Remove image", "thrive-cb" ) ?>
							</a>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<input type="hidden" class="social-config-text tve-image-value"
								   data-value-if-empty="{tcb_post_image}" name="media"
								   value="<?php echo $pin_image ?>"/>
							<img src="<?php echo $pin_image ?>" class="tve-s-custom-image"
								 style="max-width:200px;<?php if ( ! $pin_image )
								     echo 'display: none' ?>">

						</div>
						<div class="tve-sp"></div>

						<label class="tve_lightbox_label"><?php echo __( "Description", 'thrive-cb' ) ?></label>
						<textarea class="social-config-text tve_lightbox_textarea tve_lightbox_input_inline"
								  name="description"><?php echo isset( $config['pin_share']['description'] ) ? $config['pin_share']['description'] : '' ?></textarea>

						<div class="tve-sp"></div>
						<label class="tve_lightbox_label">&nbsp;</label>
						<span class="tve_comment">
                                <?php echo __( "Give a description of the link. If not set then this is automatically populated using data from current link", 'thrive-cb' ) ?>
                            </span>

						<div class="tve-sp"></div>
					</div>
				</div>

				<div class="tve_scTC tve_tc_xing_share tve_social_config_container"
					 style="display: none;padding: 20px; min-height: 226px;" data-config="xing_share">

					<div class="social-settings">
						<h4>
							<?php echo __( "Xing Share Settings", 'thrive-cb' ) ?>
						</h4>
						<label class="tve_lightbox_label"><?php echo __( "Url to Share", 'thrive-cb' ) ?></label>
						<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
							   name="href"
							   value="<?php echo isset( $config['xing_share']['href'] ) ? $config['xing_share']['href'] : '' ?>"/>
						<button data-ctrl="function:social.copyLink"
								class="tve_click tve-btn-inline tve_editor_button tve_editor_button_default">
							<span><?php echo __( "Use this URL for all social networks", 'thrive-cb' ) ?></span>
						</button>
						<div class="tve-sp"></div>
						<label class="tve_lightbox_label">&nbsp;</label>
						<span class="tve_comment">
                                <?php echo __( "You can leave this empty to use the URL on which the button is located", 'thrive-cb' ) ?>
                            </span>

						<div class="tve-sp"></div>
						<?php if ( $is_custom ) : ?>
							<label class="tve_lightbox_label"><?php echo __( "Button label", 'thrive-cb' ) ?></label>
							<input type="text" class="social-config-text tve_lightbox_input tve_lightbox_input_inline"
								   name="label"
								   value="<?php echo isset( $config['xing_share']['label'] ) ? $config['xing_share']['label'] : 'Share' ?>"/>
							<div class="tve-sp"></div>
							<label class="tve_lightbox_label">&nbsp;</label>
							<span class="tve_comment">
                                    <?php echo __( "Give the text to be shown on the button", "thrive-cb" ) ?>
                            </span>
							<div class="tve-sp"></div>
						<?php endif ?>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class="tve_right right">
	<a href="javascript:void(0)" class="tve_click tve_editor_button tve_editor_button_success"
	   data-ctrl="function:social.save">
		<?php echo __( "Save", "thrive-cb" ) ?>
	</a>
	&nbsp;
	<a href="javascript:void(0)" class="tve_click tve_editor_button tve_editor_button_default"
	   data-ctrl="function:controls.lb_close">
		<?php echo __( "Cancel", 'thrive-cb' ) ?>
	</a>
</div>