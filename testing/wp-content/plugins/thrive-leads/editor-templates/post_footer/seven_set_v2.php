<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_seven_set_v2 tve_white tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_rounded_top_cb" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin-top: -20px;margin-left: -20px;margin-right: -20px;">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
					<div class="tve_colm tve_foc tve_df tve_ofo">
						<div style="width: 138px;margin-top: 0;margin-bottom: 0;"
						     class="thrv_wrapper tve_image_caption aligncenter">
                             <span class="tve_image_frame">
                                <img class="tve_image"
                                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_seven_widget3_phone.png' ?>"
                                     style="width: 138px"/>
                            </span>
						</div>
					</div>
					<div class="tve_colm tve_tfo tve_df tve_lst">
						<h2 style="color: #333333; font-size: 36px;line-height: 40px;margin-top: 10px;margin-bottom: 0;">FIND THE BEST
							PLACES TO TRAVEL</h2>

						<div class="thrv_wrapper" style="margin-top: 10px;margin-bottom: 20px;">
							<hr class="tve_sep tve_sep1"/>
						</div>
						<p style="color: #666666; font-size: 20px; margin-top: 0;margin-bottom: 20px;">
							Aenean sollicitudin, lorem <span class="bold_text">quis bibendum auctor,</span> nisi elit
							consequat ipsum, nec sagittis sem nibh id elit oficina.
						</p>

						<div
							class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_green tve_3"
							data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 17px;">
							<div class="thrv_lead_generation_code" style="display: none;"></div>
							<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
							<div class="thrv_lead_generation_container tve_clearfix">
								<div class="tve_lead_generated_inputs_container tve_clearfix">
									<div class="tve_lead_fields_overlay"></div>
									<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
										<input type="text" data-placeholder="" value="" name="name"
										       placeholder="name*"/>
									</div>
									<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
										<input type="text" data-placeholder="" value="" name="email"
										       placeholder="email*"/>
									</div>
									<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
										<button type="Submit">SIGN UP</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="width: 800px;margin-top: 0;margin-bottom: 0;"
	     class="thrv_wrapper tve_image_caption aligncenter">
         <span class="tve_image_frame">
            <img class="tve_image"
                 src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_seven_logos.jpg' ?>"
                 style="width: 800px"/>
        </span>
	</div>
</div>