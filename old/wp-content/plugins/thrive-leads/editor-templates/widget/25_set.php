<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_25_set tve_brdr_solid">
	<div style="width: 137px;" class="thrv_wrapper tve_image_caption aligncenter">
                             <span class="tve_image_frame">
                                <img class="tve_image"
                                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_25_icon.png' ?>"
                                     style="width: 137px;"/>
                            </span>
	</div>
	<h2 style="color: #262f37; font-size: 30px; margin-top: 25px;margin-bottom: 15px;" class="rft tve_p_center">
		Subscribe to our Newsletter!
	</h2>

	<p style="color: #444b52; font-size: 18px; margin-top: 0;">
		Aenean sollicitudin, lorem quis bibendum auctor, nisi elit
		ipsum, nec sagittis sem nibh id elit.
	</p>
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_red" style="margin: 40px -10px -10px -10px;">
			<div class="tve_cb_cnt">
				<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_white thrv_lead_generation_vertical tve_2" data-inputs-count="2" style="margin-top: 0;margin-bottom: 0;">
					<div style="display: none;" class="thrv_lead_generation_code"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class="tve_lg_input_container  tve_lg_2 tve_lg_input">
								<input type="text" name="email" value="" data-placeholder="email" placeholder="Enter Email Address">
							</div>
							<div class="tve_lg_input_container tve_submit_container tve_lg_2 tve_lg_submit">
								<button type="Submit">SUBSCRIBE</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>