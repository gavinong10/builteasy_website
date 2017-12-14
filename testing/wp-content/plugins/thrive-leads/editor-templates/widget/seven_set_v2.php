<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_seven_set_v2 tve_brdr_solid"
     style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set_seven_widget_bg.png" ?>');background-size:cover;background-position: center center;">
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_bubble" data-tve-style="6">
		<div class="tve_cb tve_cb6 tve_blue">
			<div class="tve_cb_cnt">
				<p style="color: #fff;font-size: 17px; margin-top: 0;margin-bottom: 0;padding-bottom: 0;">FREE APP</p>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_rounded_top_cb" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="background: #e8e8e8;margin-top: -20px;margin-left: -20px;margin-right: -20px;">
			<div class="tve_cb_cnt">
				<h2 style="color: #333; font-size: 32px;margin-top: 20px;margin-bottom: 0;padding-right: 50px;">
					<span class="bold_text">BEST</span> PLACES TO TRAVEL
				</h2>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper" style="margin: 0 -20px;">
		<hr class="tve_sep tve_sep2"/>
	</div>
	<div style="width: 252px;margin-top: 50px;margin-bottom: -90px;"
	     class="thrv_wrapper tve_image_caption aligncenter">
         <span class="tve_image_frame">
            <img class="tve_image"
                 src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_seven_widget_phone2.png' ?>"
                 style="width: 252px"/>
        </span>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_over_cb tve_seven_clear_div" data-tve-style="6">
		<div class="tve_cb tve_cb6 tve_black" style="background: rgba(0, 0, 0, 0.81); margin-left: -20px;margin-right: -20px;">
			<div class="tve_cb_cnt">
				<h4 style="color: #fff; font-size: 24px;margin-top: 0;margin-bottom: 0;">Sign up to download this <font color="#90b348">FREE</font>App!</h4>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_rounded_cb" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin-left: -20px;margin-right: -20px;margin-bottom: -20px;">
			<div class="tve_cb_cnt">
				<div
					class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_green tve_3"
					data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
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