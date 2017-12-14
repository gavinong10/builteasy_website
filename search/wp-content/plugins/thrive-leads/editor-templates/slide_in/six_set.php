<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-slide-in tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_six_set tve_brdr_solid">
	<div style="width: 81px;margin-top: 0;margin-bottom: -95px;" class="thrv_wrapper tve_image_caption tve_set6_bookmark">
         <span class="tve_image_frame">
            <img class="tve_image"
                 src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set6_bookmark.png' ?>"
                 style="width: 81px"/>
        </span>
	</div>
	<div style="width: 470px;margin: -20px -25px 0;"
	     class="thrv_wrapper tve_image_caption aligncenter">
         <span class="tve_image_frame">
            <img class="tve_image tve_img_rounded"
                 src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set6_slidein_img.jpg' ?>"
                 style="width: 470px"/>
        </span>
	</div>
	<h5 style="color: #505050; font-size: 16px;margin-top: 35px;margin-bottom: 30px;">
		Duis aute irure dolor in
	</h5>
	<h2 style="color: #505050;font-size: 30px;margin-top: 0;margin-bottom: 15px;">
		100 BEST RECIPES EVER:</h2>
	<h1 style="color: #de4e40; font-size: 48px; margin-top: 0;margin-bottom: 25px;">
		FOOD & WINE</h1>
	<ul class="thrv_wrapper">
		<li>Duis aute irure dolor in <font color="#de4e40">reprehenderit</font> in voluptate.</li>
		<li>Sed ut <font color="#de4e40">perspiciatis</font> unde omnis iste natus.</li>
		<li>Neque porro quisquam est, <font color="#de4e40">qui dolorem</font> ipsum.</li>
	</ul>
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
	<a href="javascript:void(0)" class="tve-leads-close" title="<?php echo __( 'Close', 'thrive-leads' ) ?>">x</a>
</div>