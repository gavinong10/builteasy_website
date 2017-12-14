<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_one_set tve_brdr_solid">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_green" style="margin: -20px -15px 70px;">
			<div class="tve_cb_cnt">
				<h4 style="color: #fff; font-size: 30px;margin-top: 0;margin-bottom: 25px;" class="tve_p_center">Web Ui/Ux Design patterns 2014</h4>
				<div style="width: 197px;" class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/uiuxpatterns-small.png' ?>"
                             style="width: 197px"/>
                    </span>
				</div>
				<p style="color: #e1f2e0; font-size: 15px;margin-bottom: 0;" class="tve_p_center">What web UI design patterns do they use and why? It’s all explained in this ebook.</p>
			</div>
		</div>
	</div>
	<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_vertical tve_3" data-inputs-count="3" draggable="true" style="margin-top: 0;margin-bottom: 0;">
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
					<button type="Submit">Get this Ebook</button>
				</div>
			</div>
		</div>
	</div>
</div>