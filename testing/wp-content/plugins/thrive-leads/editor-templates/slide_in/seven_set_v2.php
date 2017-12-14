<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-slide-in tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_seven_set_v2 tve_brdr_solid"
	style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set_seven_slidein_bg.png" ?>');background-size:cover;background-position: center center;">
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
		<div class="tve_colm tve_tfo tve_df">
			<h2 style="color: #fff; font-size: 45px;margin-top: 0;margin-bottom: 20px;padding-right: 11px;">
				<font color="#90b348">BEST</font> PLACES TO TRAVEL
			</h2>
			<div class="thrv_wrapper" style="margin-bottom: 35px;">
				<hr class="tve_sep tve_sep3"/>
			</div>
			<h5 style="color: #fff; font-size: 20px;margin-top: 0;margin-bottom: 10px;">Nibh vel velit auctor aliquet quis bibendum auctor elit:</h5>
			<ul class="thrv_wrapper" style="margin-top: 30px;margin-bottom: 30px;">
				<li>Duis sed odio sit amet nibh vulputate</li>
				<li>Morbi accumsan ipsum velit.</li>
			</ul>
		</div>
		<div class="tve_colm  tve_foc tve_ofo tve_df tve_lst">
			<div style="width: 138px;margin-top: 0;margin-bottom: 0;"
			     class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_seven_widget3_phone.png' ?>"
                             style="width: 138px"/>
                    </span>
			</div>
		</div>
	</div>
	<div class="thrv_wrapper" style="margin: 0 -40px;">
		<hr class="tve_sep tve_sep2"/>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_rounded_cb" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin-left: -40px;margin-right: -40px;margin-bottom: -20px;">
			<div class="tve_cb_cnt">
				<div
					class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_green tve_2"
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
								<button type="Submit">Download</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-leads-close" title="<?php echo __( 'Close', 'thrive-leads' ) ?>">x</a>
</div>