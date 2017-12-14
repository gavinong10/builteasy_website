<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
	<div class="tve_cb tve_cb5 tve_black" style="margin: -21px -1px 0;">
		<div class="tve_cb_cnt">
			<h6 style="color: #fff; font-size: 18px;margin-top: 0;margin-bottom: 0;">Sign up to download this <font color="#6bbe65">FREE</font> Ebook</h6>
		</div>
	</div>
</div>
<h2 class="tve_p_center" style="color: #000; font-size: 48px;margin-top: 50px;margin-bottom: 50px;">Sign up to download this <font color="#6bbe65">FREE</font> Ebook</h2>
<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 750px;min-width:50px; min-height: 2em;">
		<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
			<div class="tve_colm tve_oth">
				<div style="width: 240px; margin: 0;" class="thrv_wrapper tve_image_caption alignright">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/uiuxpatterns.png' ?>"
                         style="width: 240px"/>
                </span>
				</div>
			</div>
			<div class="tve_colm tve_tth tve_lst">
				<h3 style="color: #000; font-size: 36px;line-height: 50px;margin-top: 25px; margin-bottom: 25px;">What UI design patterns do they use and why?</h3>
				<p style="color: #3a3a3a; font-size: 15px;">Amazon, Kickstarter, AirBnB, Quora, LinkedIn, Eventbrite, Asana, Mailchimp - what web UI design patterns do they use and why? It’s all explained in this ebook.</p>
			</div>
		</div>
	</div>
	<div class="tve_clear"></div>
</div>

<div class="thrv_wrapper thrv_content_container_shortcode">
	<div class="tve_clear"></div>
	<div class="tve_center tve_content_inner" style="width: 980px;min-width:50px; min-height: 2em;">
		<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
			<div class="tve_cb tve_cb6 tve_white">
				<div class="tve_cb_cnt">
					<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_horizontal tve_3" data-inputs-count="3" draggable="true" style="margin-top: 0;margin-bottom: 0;">
						<div style="display: none;" class="thrv_lead_generation_code"></div>
						<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
						<div class="thrv_lead_generation_container tve_clearfix">
							<div class="tve_lead_generated_inputs_container tve_clearfix">
								<div class="tve_lead_fields_overlay"></div>
								<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
									<input type="text" name="name" value="" data-placeholder="">
								</div>
								<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
									<input type="text" name="email" value="" data-placeholder="">
								</div>
								<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
									<button type="Submit">Download Ebook</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tve_clear"></div>
</div>