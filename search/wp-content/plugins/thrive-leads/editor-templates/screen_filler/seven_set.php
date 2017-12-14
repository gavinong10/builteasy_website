<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-form-box element */
?>

<div class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_seven_set"
     style="background-image: url(<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set7_screenfiller_bg.jpg' ?>); background-position: center center; background-size: cover;"
>
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_columns tve_clearfix">
			<div class="tve_colm tve_oth">
				<div style="margin-top: 30px;margin-bottom: 0;width: 250px;" class="thrv_wrapper tve_image_caption aligncenter">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set7_phone.png' ?>"
                             style="width: 250px;"/>

                    </span>
				</div>
			</div>
			<div class="tve_colm tve_tth tve_lst">
				<h1 style="color: #fff; font-size: 45px;margin-top: 50px;margin-bottom: 0; " class="rft">
					FIND <span class="bold_text">THE BEST </span>PLACES TO TRAVEL, EXPLORE THE PLANET
				</h1>

				<div class="thrv_wrapper" style="margin-top: 25px;margin-bottom: 50px;">
					<hr class="tve_sep tve_sep1"/>
				</div>
				<p style="color: #ccc; font-size: 22px;margin-top: 0;margin-bottom: 35px;">
					By downloading this FREE App you’ll get the following:
				</p>
				<div class="thrv_wrapper thrv_bullets_shortcode">
					<ul class="tve_ul tve_ul3 tve_green">
						<li>Aenean Sollicitudin. Lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit.</li>
						<li>Bibendum Sagittis. Lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet</li>
					</ul>
				</div>
				<div
					class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_green tve_2"
					data-inputs-count="2" data-tve-style="1" style="margin-top: 30px; margin-bottom: 0;">
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
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
