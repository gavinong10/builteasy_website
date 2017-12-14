<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-form-box element */
?>

<div class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_two_set_v2 tve_white">
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
		<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;">

			<div class="tve_colm tve_oth">
				<div style="margin-top: 50px;width: 430px;" class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set02_widget_img_big.png' ?>"
                             style="width: 430px"/>
                    </span>
				</div>
			</div>
			<div class="tve_colm tve_tth tve_lst">
				<h2 class="rft"
				    style="color: #78ac49; font-size: 60px;line-height: 70px; margin-top: 20px;margin-bottom: 0;">GET
					INSTANT ACCESS</h2>

				<h3 class="rft"
				    style="color: #333333; font-size: 44px; line-height: 50px;margin-top: 0;margin-bottom: 15px;">to Our
					Awesome Product now!</h3>

				<h6 style="color: #999999; font-size: 25px; margin-top: 0;">
					Duis sed odio sit amet nibh vulputate cursus a sit amet
					mauris aenean sollicitudin, lorem bibendum?
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
								<button type="Submit">Get INSTANT Acces Now</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
