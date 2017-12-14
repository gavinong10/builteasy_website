<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_three_set_v1 tve_white">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_teal">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
					<div class="tve_colm tve_foc tve_df tve_ofo ">
						<div style="width: 172px; margin-top: 0;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter img_style_lifted_style2">
                             <span class="tve_image_frame">
                                <img class="tve_image"
                                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set3_play_image.jpg' ?>"
                                     style="width: 172px"/>
                            </span>
						</div>
					</div>
					<div class="tve_colm tve_tfo tve_df tve_lst">
						<h3 style="color: #333; font-size: 28px;margin-bottom: 15px;margin-top: 10px;">Market Research in a Week: <span class="bold_text">Teach Yourself</span></h3>
						<p style="color: #333; font-size: 18px; line-height: 25px; margin-top: 0;margin-bottom: 0;">Aenean sollicitudin, lorem quis bibendum auctor, nisi elit ipsum sagittis.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
		<div class="tve_cb tve_cb6 tve_red" style="margin-top: 15px;">
			<div class="tve_cb_cnt">
				<div data-tve-style="1"
				     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_teal thrv_lead_generation_horizontal tve_2"
				     data-inputs-count="2" style="margin-top: 0;margin-bottom: 0;">
					<div style="display: none;" class="thrv_lead_generation_code"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class="tve_lg_input_container tve_lg_2 tve_lg_input">
								<input type="text" name="email" value="" data-placeholder="Enter your email..." placeholder="Enter your email...">
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