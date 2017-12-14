<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
	<div class="tve_colm tve_oth">
		<div style="width: 198px;margin-top: 30px;" class="thrv_wrapper tve_image_caption aligncenter">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_27_icon.png' ?>"
                         style="width: 198px;"/>
                </span>
		</div>
	</div>
	<div class="tve_colm tve_tth tve_lst">
		<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_blue" style="margin: -15px -15px -40px 0;">
				<div class="tve_cb_cnt">
					<h2 style="color: #fff; font-size: 32px; margin-top: 25px;margin-bottom: 20px;" class="rft">
						Subscribe to our Newsletter!
					</h2>
					<p style="color: #fff; font-size: 18px; margin-top: 0;">
						Aenean sollicitudin, lorem quis bibendum auctor, nisi elit
						ipsum, nec sagittis sem nibh id elit.
					</p>
					<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_red thrv_lead_generation_horizontal tve_2" data-inputs-count="2" style="margin-top: 0;margin-bottom: 20px;">
						<div style="display: none;" class="thrv_lead_generation_code"></div>
						<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
						<div class="thrv_lead_generation_container tve_clearfix">
							<div class="tve_lead_generated_inputs_container tve_clearfix">
								<div class="tve_lead_fields_overlay"></div>
								<div class="tve_lg_input_container  tve_lg_2 tve_lg_input">
									<input type="text" name="email" value="" data-placeholder="email" placeholder="Your Email">
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
</div>




