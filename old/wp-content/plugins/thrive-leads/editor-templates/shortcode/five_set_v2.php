<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_five_set tve_brdr_solid tve_green" style="border-width: 3px; background: #fff; border-color: #45bf55">
	<div class="thrv_wrapper thrv_columns" style="margin-bottom: 0px !important;">
		<div class="tve_colm tve_foc tve_df tve_fft">
			<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px; border-radius: 0px;">
				<span data-tve-icon="icon-lock" class="tve_sc_icon five-set-icon-lock tve_green" style="padding: 0px; border-radius: 0px; font-size: 70px; width: 70px; height: 70px;"></span>
			</div>
		</div>
		<div class="tve_colm tve_twc"><h1 style="color: rgb(63, 63, 63); font-size: 30px; margin-top: 0px; margin-bottom: 0px !important;" class="tve_p_center" contenteditable="false">THIS CONTENT IS LOCKED</h1></div>
		<div class="tve_colm tve_foc tve_df tve_fft tve_lst">
			<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px; border-radius: 0px;">
				<span data-tve-icon="icon-lock" class="tve_sc_icon five-set-icon-lock tve_green" style="padding: 0px; border-radius: 0px; font-size: 70px; width: 70px; height: 70px;"></span>
			</div>
		</div>
	</div>
	<p style="color: rgb(63, 63, 63); font-size: 20px; margin-top: 20px !important; margin-bottom: 0px; line-height: 30px;" class="tve_p_center" contenteditable="false"><span class="bold_text"><font color="#7bc542"></font></span><span class="tve_custom_font_size  rft" style="font-size: 30px;"><font color="#7bc542">Get free and instant access </font></span><br>Simply enter your details into the form below:-<br></p>

	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
		<div class="tve_cb tve_cb6 tve_white">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_green tve_2" data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<div style="display: none;" class="thrv_lead_generation_errors">__CONFIG_lead_generation__<?php echo json_encode( $config ) ?>__CONFIG_lead_generation__</div>
					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
								<input data-placeholder="" value="" name="email" placeholder="Email Address" type="text">
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
								<button type="Submit">GET INSTANT ACCESS</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>