<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_two_set_v3 tve_brdr_solid" style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set02_widget_bg.jpg" ?>');">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_green" style="margin: -36px -15px 0;">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_columns tve_clearfix">
					<div class="tve_colm tve_oth">
						<div style="color: #7db347;" class="thrv_wrapper thrv_icon aligncenter">
							<span style="font-size: 60px;" class="tve_sc_icon two-set-airplane" data-tve-icon="two-set-airplane"></span>
						</div>
					</div>
					<div class="tve_colm tve_tth tve_lst">
						<h6 style="color: #fff; font-size: 26px;line-height: 30px;margin-top: 0;margin-bottom: 0;" class="tve_p_left">Download our <span class="bold_text">free product</span> instantly!</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<p style="color: #cccccc; font-size: 16px;margin-top: 0;margin-bottom: 30px;" class="tve_p_center">This is Photoshop's version dolor of lorem ipsum gravida ad nibh.</p>
	<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_blue tve_draggable thrv_lead_generation_vertical tve_3" data-inputs-count="3" draggable="true" style="margin-top: 0;margin-bottom: 0;">
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