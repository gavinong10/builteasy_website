<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-form-box element */
?>

<div class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_three_set_v3"
     style="background-image: url(<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set3_screenfiller_bg.jpg' ?>); background-position: center center; background-size: cover;"
>
	<div class="tve-screen-filler-content tve_editor_main_content">
		<h5 style="color: #333333; font-size: 28px;line-height: 30px;margin-top: 0;margin-bottom: 0;">
			<font color="#ab3334">FREE</font> Video Course
		</h5>

		<div class="thrv_wrapper" style="margin-bottom: 40px;">
			<hr class="tve_sep tve_sep1"/>
		</div>
		<h2 class="rft" style="color: #333; font-size: 54px;line-height: 55px;margin-top:0;margin-bottom: 50px;">
			Market Research in a Week: Teach Yourself
		</h2>
		<div class="thrv_wrapper thrv_columns" style="margin-bottom: 80px;">
			<div class="tve_colm tve_oth">
				<div style="width: 388px;margin-top: 0;margin-bottom: 0;"
				     class="thrv_wrapper tve_image_caption aligncenter img_style_lifted_style2">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_three_big_video.jpg' ?>"
                             style="width: 388px;"/>
                    </span>
				</div>
			</div>
			<div class="tve_colm tve_tth tve_lst">
				<h4 style="color: #333; font-size: 28px;margin-top: 0;margin-bottom: 30px;">FREE 3
					Part Video Course Includes:</h4>
				<div class="thrv_wrapper thrv_bullets_shortcode">
					<ul class="tve_ul tve_ul1 tve_red">
						<li style="color: #333333; font-size: 18px;margin-top: 0;margin-bottom: 20px;">5
							Steps To Create A Powerful,Profitable Presentation
						</li>
						<li style="color: #333333; font-size: 18px;margin-top: 0;margin-bottom: 20px;">
							Social Media Marketing Tips:Essential Advice, Hints etc.
						</li>
						<li style="color: #333333; font-size: 18px;margin-top: 0;margin-bottom: 20px;">
							12 Things Every Business Needs to Know About Digital Marketing
						</li>
					</ul>
				</div>
				<div data-tve-style="1"
				     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_red tve_draggable thrv_lead_generation_horizontal tve_2"
				     draggable="true" style="margin-top: 40px;margin-bottom: 0;">
					<div style="display: none;" class="thrv_lead_generation_code"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class="tve_lg_input_container tve_lg_2 tve_lg_input">
								<div class="thrv_wrapper thrv_icon tve_brdr_solid">
									<span class="tve_sc_icon three-set-mail tve_white" data-tve-icon="two-set-user" style="color: #cccccc;"></span>
								</div>
								<input type="text" name="email" value="" data-placeholder="Enter your email..."
								       placeholder="Enter your email...">
							</div>
							<div class="tve_lg_input_container tve_submit_container tve_lg_2 tve_lg_submit">
								<button type="Submit">SUBSCRIBE</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
