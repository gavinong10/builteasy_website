<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-form-box element */
?>

<div class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_two_set_v3 tve_white">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div style="margin-top: 0;margin-bottom: 0;width: 399px;" class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/thrive_leads_logo.png' ?>"
                     style="width: 399px"/>
            </span>
		</div>
		<div class="thrv_wrapper" style="margin-top: 0;margin-bottom: 30px;">
			<hr class="tve_sep tve_sep1"/>
		</div>
		<div class="thrv_wrapper thrv_columns tve_clearfix">
			<div class="tve_colm tve_foc tve_df tve_ofo ">
				<div style="margin-top: 30px;margin-bottom: 0;width: 111px;" class="thrv_wrapper tve_image_caption aligncenter wp-caption">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_two_screenfiller_video.png' ?>"
                             style="width: 111px;"/>
                              <p style="margin-bottom: 0;" class="wp-caption-text underline_text">&#x000BB; Watch Now</p>
                    </span>
				</div>
			</div>
			<div class="tve_colm tve_tfo tve_df tve_lst">
				<h2 class="rft tve-line-header" style="color: #333333; font-size: 60px;line-height: 65px; margin-top: 0;margin-bottom: 0;">
					<span class="bold_text">Class Aptent Taciti ad Lorem Litora</span> (Torquent per Conubia)
				</h2>
			</div>
		</div>
		<div class="thrv_wrapper " style="margin-top: 30px;margin-bottom: 30px;">
			<hr class="tve_sep tve_sep2"/>
		</div>

		<h6 class="tve_p_center" style="color: #999999; font-size: 18px; margin-top: 0;margin-bottom: 40px;">
			Enter your Name and Email Address below to <span class="bold_text">Get Started</span> right away!
		</h6>

		<div data-tve-style="1"
		     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_vertical tve_3"
		     data-inputs-count="3"
		     draggable="true" style="margin-top: 0;margin-bottom: 0;">
			<div style="display: none;" class="thrv_lead_generation_code"></div>
			<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
			<div class="thrv_lead_generation_container tve_clearfix">
				<div class="tve_lead_generated_inputs_container tve_clearfix">
					<div class="tve_lead_fields_overlay"></div>
					<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
						<div class="thrv_wrapper thrv_icon tve_brdr_solid">
                                    <span class="tve_sc_icon two-set-user tve_white" data-tve-icon="two-set-user"
                                          style="color: #b1b1b1;"></span>
						</div>
						<input type="text" name="name" value="" data-placeholder="Name" placeholder="Name">
					</div>
					<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
						<div class="thrv_wrapper thrv_icon tve_brdr_solid">
                                    <span class="tve_sc_icon two-set-envelope tve_white"
                                          data-tve-icon="two-set-envelope" style="color: #b1b1b1;"></span>
						</div>
						<input type="text" name="email" value="" data-placeholder="Email" placeholder="Email">
					</div>
					<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
						<button type="Submit">Yes,I Want to Subscribe Now</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
