<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_six_set tve_white tve_brdr_solid thrv-leads-in-content">
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
		<div class="tve_colm tve_oth">
			<div style="width: 242px;margin-top: -20px;margin-bottom: -20px;margin-left: -20px;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                 <span class="tve_image_frame">
                    <img class="tve_image tve_set6_rounded_corners"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set6_postfooter_img.jpg' ?>"
                         style="width: 242px"/>
                </span>
			</div>
		</div>
		<div class="tve_colm tve_tth tve_lst">
			<h5 style="color: #505050; font-size: 16px;margin-top: 0;margin-bottom: 15px;" class="tve_p_center">
				Duis aute irure dolor in reprehenderit in</h5>
			<h3 style="color: #505050; font-size: 24px;margin-top: 0;margin-bottom: 17px;">
				100 BEST RECIPES EVER: <font color="#de4e40">FOOD & WINE</font>
			</h3>
			<p style="color: #505050; font-size: 16px;margin-top: 0;margin-bottom: 20px;">
				Duis aute irure dolor in <font color="#de4e40">reprehenderit</font> in voluptate.
			</p>
			<div
				class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_red tve_3"
				data-inputs-count="3" data-tve-style="1" style="margin-top: 20px; margin-bottom: 0;">
				<div class="thrv_lead_generation_code" style="display: none;"></div>
				<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
				<div class="thrv_lead_generation_container tve_clearfix">
					<div class="tve_lead_generated_inputs_container tve_clearfix">
						<div class="tve_lead_fields_overlay"></div>
						<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
							<input type="text" data-placeholder="" value="" name="name"
							       placeholder="Full Name"/>
						</div>
						<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
							<input type="text" data-placeholder="" value="" name="email"
							       placeholder="Your email address"/>
						</div>
						<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
							<button type="Submit">Download</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>