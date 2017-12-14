<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_three_set tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_teal">
			<div class="tve_cb_cnt">
				<h5 style="color: #333333; font-size: 28px;line-height: 30px;margin-top: 5px;margin-bottom: 0;">
					<font color="#ab3334">FREE</font> Video Course
				</h5>
				<div class="thrv_wrapper" style="margin: 15px -20px;">
					<hr class="tve_sep tve_sep1"/>
				</div>
				<h2 style="color: #333; font-size: 40px;line-height: 38px;margin-top: 0;margin-bottom: 30px;">
					Market Research in a Week: Teach Yourself
				</h2>
				<div style="width: 240px; margin-top: 20px;margin-bottom: 20px;" class="thrv_wrapper tve_image_caption aligncenter img_style_lifted_style2">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set3_play_image.jpg' ?>"
                             style="width: 240px"/>
                    </span>
				</div>
				<p class="tve_p_center" style="color: #333; line-height: 25px;font-size: 17px;margin-top: 0;margin-bottom: 0;">
					<span class="underline_text">Hurry! It’s a Limited Period Offer!</span>
				</p>
			</div>
		</div>
	</div>

	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
		<div class="tve_cb tve_cb6 tve_red" style="margin-top: 20px;padding: 10px 5px;">
			<div class="tve_cb_cnt">
				<div data-tve-style="1"
				     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_teal tve_draggable thrv_lead_generation_vertical tve_2" data-inputs-count="2"
				     draggable="true" style="margin-top: 0;margin-bottom: 0;">
					<div style="display: none;" class="thrv_lead_generation_code"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class="tve_lg_input_container tve_lg_2 tve_lg_input">
								<input type="text" name="email" value="" data-placeholder="Enter your email..."
								       placeholder="Enter your email...">
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