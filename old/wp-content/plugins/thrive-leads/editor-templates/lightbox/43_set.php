<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_contentbox_shortcode cb-rounded-corners" data-tve-style="5">
	<div class="tve_cb tve_cb5 tve_orange" style="margin: -15px -15px 20px;">
		<div class="tve_cb_cnt">
			<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 0;">
				<div class="tve_colm tve_foc tve_df tve_ofo ">
					<div style="width: 149px;" class="thrv_wrapper tve_image_caption aligncenter">
                             <span class="tve_image_frame">
                                <img class="tve_image"
                                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_43_icon.png' ?>"
                                     style="width: 149px;"/>
                            </span>
					</div>
				</div>
				<div class="tve_colm tve_tfo tve_df tve_lst">
					<h2 style="color: #000; font-size: 34px; margin-top: 25px;margin-bottom: 15px;" class="rft">
						Download The Free Video
					</h2>

					<p style="color: #000; font-size: 20px; margin-top: 0;">
						Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum
						auctor, nisi elit consequat ipsum, nec sem nibh id elit.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div data-tve-style="1"
     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_black thrv_lead_generation_horizontal tve_3"
     data-inputs-count="3" style="margin-top: 0;margin-bottom: 0;">
	<div style="display: none;" class="thrv_lead_generation_code"></div>
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
				<input type="text" name="name" value="" data-placeholder="name" placeholder="Enter Name...">
			</div>
			<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
				<input type="text" name="email" value="" data-placeholder="email" placeholder="Enter Email...">
			</div>
			<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
				<button type="Submit">DOWNLOAD</button>
			</div>
		</div>
	</div>
</div>