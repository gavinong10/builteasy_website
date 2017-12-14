<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_seven_set_v1 tve_black tve_brdr_solid" style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set_seven_postfooter_bg.png" ?>');background-size:cover;background-position: center center;">
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
		<div class="tve_colm tve_tth">
			<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0px; margin-bottom: 11px !important;">
				<div class="tve_colm tve_oth">
					<div style="width: 160px; margin-top: 0px; margin-bottom: 0px;" class="thrv_wrapper tve_image_caption">
                        <span class="tve_image_frame">
                            <img class="tve_image" src="http://plugins.thrive-dev.bitstoneint.com/wp-content/plugins/thrive-leads/editor-templates/_form_css/images/set_seven_widget3_phone.png" style="width: 160px;">
                        </span>
					</div>
				</div>
				<div class="tve_colm tve_tth tve_lst">
					<h2 style="color: #fff; font-size: 32px;margin-top: 20px;margin-bottom: 20px;padding-right: 11px;" class="">
						FIND <span class="bold_text">THE BEST</span> PLACES TO TRAVEL
					</h2>

					<div class="thrv_wrapper" style="">
						<hr class="tve_sep tve_sep3">
					</div>
					<p style="color: #fff; font-size: 20px; margin-top: 0;margin-bottom: 10px;" class="">
						Aenean sollicitudin, lorem <span class="bold_text">quis bibendum auctor,</span> nisi elit consequat ipsum, nec sagittis sem nibh id elit oficina.
					</p>
				</div>
			</div>
		</div>
		<div class="tve_colm tve_oth tve_lst">
			<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_right_brd" data-tve-style="5">
				<div class="tve_cb tve_cb5 tve_white" style="margin-top: -20px;margin-right: -20px;margin-bottom: -30px;">
					<div class="tve_cb_cnt">
						<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px;">
							<span data-tve-icon="icon-lock" class="tve_sc_icon seven-set-icon-lock"></span>
						</div>
						<h4 style="color: rgb(51, 51, 51); font-size: 24px; margin-top: 5px !important; margin-bottom: 35px;" class="tve_p_center" contenteditable="false">UNLOCK THIS CONTENT NOW</h4>

						<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_green tve_3" data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 17px;">
							<div class="thrv_lead_generation_code" style="display: none;"></div>
							<div style="display: none;" class="thrv_lead_generation_errors">
								__CONFIG_lead_generation__<?php echo json_encode( $config ) ?>__CONFIG_lead_generation__
							</div>
							<div class="thrv_lead_generation_container tve_clearfix">
								<div class="tve_lead_generated_inputs_container tve_clearfix">
									<div class="tve_lead_fields_overlay"></div>
									<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
										<input data-placeholder="" value="" name="name" placeholder="name*" type="text">
									</div>
									<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
										<input data-placeholder="" value="" name="email" placeholder="email*" type="text">
									</div>
									<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
										<button type="Submit">Get Instant Access</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>