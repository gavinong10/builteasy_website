<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-slide-in tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_eight_set tve_brdr_solid">
	<h6 style="color: #505050; font-size: 15px;margin-top: 20px;margin-bottom: 30px;">SED UT PERSPICIATIS UNDE OMNIS ISTE</h6>
	<h3 class="tve_p_center" style="color: #252525; font-size: 30px;margin-top: 0;margin-bottom: 10px;">LEARN HOW TO
		MAKE A <font color="#1c81a5">WEBSITE WITH WORDPRESS</font></h3>

	<div class="thrv_wrapper thrv_columns" style="margin-top: 0;margin-bottom: 0;">
		<div class="tve_colm tve_twc">
			<div style="width: 165px; margin-top: 20px;margin-bottom: 20px;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/eight_set_lightbox.png' ?>"
                         style="width: 165px;"/>
                </span>
			</div>
		</div>
		<div class="tve_colm tve_twc tve_lst">
			<div class="thrv_wrapper thrv_bullets_shortcode">
				<ul class="tve_ul tve_ul6 tve_blue">
					<li>Ut enim ad minim veniam quis nostrud exercitation.</li>
					<li>Ut enim ad minim veniam quis nostrud exercitation.</li>
					<li>Ut enim ad minim veniam quis nostrud exercitation.</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_eight_set_rnd_btm" data-tve-style="6">
		<div class="tve_cb tve_cb6 tve_white" style="margin-left: -20px;margin-right: -20px;margin-bottom: -20px;">
			<div class="tve_cb_cnt">
				<div
					class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_blue tve_2"
					data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
								<div class="thrv_wrapper thrv_icon tve_brdr_solid">
                                    <span class="tve_sc_icon eight-set-icon-email tve_blue" data-tve-icon="icon-support"
                                          style="color: #1c81a5;"></span>
								</div>
								<input type="text" data-placeholder="" value="" name="email"
								       placeholder="Email Address" style="padding-right: 50px!important;"/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
								<button type="Submit">Sign up and download!</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-leads-close" title="<?php echo __( 'Close', 'thrive-leads' ) ?>">x</a>
</div>