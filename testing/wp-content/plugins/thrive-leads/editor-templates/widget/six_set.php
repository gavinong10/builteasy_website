<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_two_set_v3 tve_brdr_solid">
	<h5 style="color: #505050; font-size: 16px;margin-top: 0;margin-bottom: 25px;" class="tve_p_center">
		Duis aute irure dolor in
	</h5>
	<h3 style="color: #505050;font-size: 24px;margin-top: 0;margin-bottom: 15px;" class="tve_p_center">
		100 BEST RECIPES EVER:</h3>
	<h2 style="color: #de4e40; font-size: 36px; margin-top: 0;margin-bottom: 25px;"
	    class="tve_p_center">FOOD & WINE</h2>
	<div style="width: 81px;margin-top: 0;margin-bottom: -95px;" class="thrv_wrapper tve_image_caption tve_set6_widget_bookmark">
         <span class="tve_image_frame">
            <img class="tve_image"
                 src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set6_bookmark.png' ?>"
                 style="width: 81px"/>
        </span>
	</div>
	<div class="thrv_wrapper thrv_content_container_shortcode">
		<div class="tve_clear"></div>
		<div class="tve_center tve_content_inner" style="width: 300px;min-width:50px; min-height: 2em;">
			<div style="width: 300px;margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set6_widget_img.jpg' ?>"
                         style="width: 300px"/>
                </span>
			</div>
		</div>
		<div class="tve_clear"></div>
	</div>
	<h4 class="tve_p_center" style="color: #505050; font-size: 18px;margin-top: 30px;margin-bottom: 30px;">JOIN OUR COMMUNITY!</h4>
	<div
		class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_red tve_2"
		data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
		<div class="thrv_lead_generation_code" style="display: none;"></div>
		<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
					<input type="text" data-placeholder="" value="" name="email"
					       placeholder="Your email address"/>
				</div>
				<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
					<button type="Submit">Get instant access!</button>
				</div>
			</div>
		</div>
	</div>
</div>