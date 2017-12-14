<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_two_set_v1 tve_white tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin: -36px -20px 0;">
			<div class="tve_cb_cnt">
				<h5 class="tve_p_center" style="color: #333333; font-size: 34px; line-height: 35px;margin-top: 0;margin-bottom: 0;">
					Fill out the form to get your <span class="bold_text">free report.</span>
				</h5>
			</div>
		</div>
	</div>
	<div style="width: 220px;margin-top: 30px;" class="thrv_wrapper tve_image_caption aligncenter">
         <span class="tve_image_frame">
            <img class="tve_image"
                 src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set02_widget_img.png' ?>"
                 style="width: 220px"/>
        </span>
	</div>
	<p class="tve_p_center" style="color: #666666;font-size: 16px;margin-top: 0;margin-bottom: 30px;">
		This is Photoshop's version of Lorem Ipsum. Proin gravida ad nibh vel velit nam elitis aeneam.
	</p>
	<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_vertical tve_3" data-inputs-count="3" draggable="true" style="margin-top: 0;margin-bottom: 0;">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
					<input type="text" name="name" value="" data-placeholder="Your Name:" placeholder="Your Name:">
				</div>
				<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
					<input type="text" name="email" value="" data-placeholder="Your Email:" placeholder="Your Email:">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
					<button type="Submit">Subscribe Now</button>
				</div>
			</div>
		</div>
	</div>
</div>