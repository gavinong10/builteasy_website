<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_eight_set tve_white tve_brdr_solid">
	<h6 style="color: #505050; font-size: 15px;margin-top: 0;margin-bottom: 10px;">SED UT PERSPICIATIS UNDE OMNIS ISTE NATUS ERROR SIT VOLUPTATEM ACCUSA</h6>
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 0;">
		<div class="tve_colm tve_foc tve_df tve_ofo ">
			<div style="width: 88px; margin-top: 0;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/eight_set_postfooter_img.png' ?>"
                             style="width: 88px;"/>
                    </span>
			</div>
		</div>
		<div class="tve_colm tve_tfo tve_df tve_lst">
			<h4 style="color: #252525; font-size: 21px;margin-top: 0;margin-bottom: 15px;">LEARN HOW TO MAKE A <font color="#1c81a5">WEBSITE WITH WORDPRESS</font></h4>
			<p style="color: #464646; font-size: 16px;margin-top: 0;margin-bottom: 15px;">Lorem ipsum dolor sit amet, <font color="#fe2872">consectetur adipisicing</font> elit, sed do eiusmod.</p>
			<div
				class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_blue tve_3"
				data-inputs-count="4" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
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
							       placeholder="Email Address"/>
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