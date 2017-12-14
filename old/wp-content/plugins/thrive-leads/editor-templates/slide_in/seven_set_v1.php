<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-slide-in tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_seven_set_v1 tve_brdr_solid"
	style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set_seven_widget_bg.png" ?>');background-size:cover;background-position: center center;">
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_bubble" data-tve-style="6">
		<div class="tve_cb tve_cb6 tve_blue">
			<div class="tve_cb_cnt">
				<p style="color: #fff;font-size: 20px; margin-top: 0;margin-bottom: 0;padding-bottom: 0;">FREE APP</p>
			</div>
		</div>
	</div>
	<h2 style="color: #fff; font-size: 46px;margin-top: 50px;margin-bottom: 20px;padding-right: 11px;">
		<font color="#90b348">BEST</font> PLACES TO TRAVEL
	</h2>

	<div class="thrv_wrapper">
		<hr class="tve_sep tve_sep3"/>
	</div>
	<div class="thrv_wrapper thrv_columns" style="margin-top: 40px;margin-bottom: 40px;">
		<div class="tve_colm tve_twc">
			<div style="width: 138px;margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_seven_widget3_phone.png' ?>"
                             style="width: 138px"/>
                    </span>
			</div>
		</div>
		<div class="tve_colm tve_twc tve_lst">
			<ul class="thrv_wrapper">
				<li>Aenean sollicitudin, lorem quis bibendum</li>
				<li>Bibendum nisi elitis adec consequat</li>
				<li>Ipsum, nec sagittis sem nibh id elit.</li>
			</ul>
		</div>
	</div>
	<div class="thrv_wrapper" style="margin: 0 -20px;">
		<hr class="tve_sep tve_sep2"/>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_rounded_cb" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin-left: -20px;margin-right: -20px;margin-bottom: -20px;">
			<div class="tve_cb_cnt">
				<div
					class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_green tve_2"
					data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
								<input type="text" data-placeholder="" value="" name="email"
								       placeholder="email*"/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
								<button type="Submit">SIGN UP TODAY</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-leads-close" title="<?php echo __( 'Close', 'thrive-leads' ) ?>">x</a>
</div>