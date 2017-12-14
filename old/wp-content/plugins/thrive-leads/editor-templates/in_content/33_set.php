<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_33_set tve_white tve_brdr_solid thrv-leads-in-content">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_purple" style="margin: -20px -20px 0;">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 10px;margin-bottom: 10px;">
					<div class="tve_colm tve_foc tve_df tve_ofo ">
						<div style="width: 154px;" class="thrv_wrapper tve_image_caption aligncenter">
                             <span class="tve_image_frame">
                                <img class="tve_image"
                                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_33_icon.png' ?>"
                                     style="width: 154px;"/>
                            </span>
						</div>
					</div>
					<div class="tve_colm tve_tfo tve_df tve_lst">
						<h2 style="color: #fff; font-size: 32px; margin-top: 20px;margin-bottom: 10px;" class="rft">
							Download Our Freebie
						</h2>

						<p style="color: #fff; font-size: 18px; margin-top: 0;">
							Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem
							nibh id
							elit.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div data-tve-style="1"
	     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_purple thrv_lead_generation_horizontal tve_3"
	     data-inputs-count="3" style="margin-bottom: 10px;margin-top: 30px;">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<input type="hidden" class="tve-lg-err-msg"
		       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
					<input type="text" name="name" value="" data-placeholder="name"
					       placeholder="Name">
				</div>
				<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
					<input type="text" name="email" value="" data-placeholder="email"
					       placeholder="Email">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
					<button type="Submit">SUBSCRIBE</button>
				</div>
			</div>
		</div>
	</div>
</div>