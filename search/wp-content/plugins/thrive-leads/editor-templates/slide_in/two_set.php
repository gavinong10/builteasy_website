<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-slide-in tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_two_set tve_brdr_solid">
	<h2 style="color: #333; font-size: 48px;margin-top: 0;margin-bottom: 0;">Guide to Rapid Landing Page Building</h2>
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 30px;">
		<div class="tve_colm tve_oth">
			<div style="width: 162px;margin-top: 20px;" class="thrv_wrapper tve_image_caption aligncenter">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set02_slidein.png' ?>"
                         style="width: 162px"/>
                </span>
			</div>
		</div>
		<div class="tve_colm tve_tth tve_lst">
			<h6 style="color: #333; font-size: 18px;margin-top: 0;margin-bottom: 0;">
				Duis sed odio sit nibh nam vulputate cursus sit amet <span class="underline_text">elitis bibendum</span> lorem ipsum.
			</h6>
			<div class="thrv_wrapper thrv_bullets_shortcode">
				<ul class="tve_ul tve_ul1 tve_green">
					<li>Aenean sollicitudin, lorem quis adec</li>
					<li>Lorem, ipsum consecutur velit</li>
					<li>Bibendum duius ad nunc elitis proin nam</li>
				</ul>
			</div>
		</div>
	</div>
	<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_vertical tve_2" data-inputs-count="2" draggable="true">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container  tve_lg_2 tve_lg_input">
					<input type="text" name="email" value="" data-placeholder="Email:" placeholder="Email:">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_2 tve_lg_submit">
					<button type="Submit">SUBSCRIBE NOW AND GET OUR GUIDE</button>
				</div>
			</div>
		</div>
	</div>

	<a href="javascript:void(0)" class="tve-leads-close" title="<?php echo __( 'Close', 'thrive-leads' ) ?>">x</a>
</div>