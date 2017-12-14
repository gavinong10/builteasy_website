<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_35_set tve_brdr_solid" style="margin-top: 50px;">
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_green" style="margin: -10px -10px 20px;">
			<div class="tve_cb_cnt">
				<div style="width: 130px;margin-top: -70px;" class="thrv_wrapper tve_image_caption aligncenter">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_35_icon.png' ?>"
                         style="width: 130px;"/>
                </span>
				</div>
				<h2 style="color: #fff; font-size: 32px; margin-top: 25px;margin-bottom: 15px;"
				    class="rft tve_p_center">
					Download Our <font color="#fad250">Freebie</font>
				</h2>

				<p style="color: #fff; font-size: 18px; margin-top: 0;" class="tve_p_center">
					Aenean sollicitudin, lorem quis bibendum auctor, nisi elit
					ipsum, nec sagittis sem nibh id elit.
				</p>
			</div>
		</div>
	</div>

	<div data-tve-style="1"
	     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_purple thrv_lead_generation_vertical tve_3"
	     data-inputs-count="3" style="margin-top: 0;margin-bottom: 0;">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<input type="hidden" class="tve-lg-err-msg"
		       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
					<input type="text" name="name" value="" data-placeholder="Name" placeholder="Name">
				</div>
				<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
					<input type="text" name="email" value="" data-placeholder="email" placeholder="Email">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
					<button type="Submit">GET ACCESS</button>
				</div>
			</div>
		</div>
	</div>
</div>
