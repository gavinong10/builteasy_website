<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-greedy-ribbon tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_gr_ten_set tve_blue">
	<div class="tve-greedy-ribbon-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_columns">
			<div class="tve_colm tve_twc">
				<div style="width: 736px;margin-top: 0;margin-bottom: 0;"
				     class="thrv_wrapper tve_image_caption aligncenter gr_ten_set_image">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/gr_ten_set_img.png' ?>"
                             style="width: 736px"/>
                    </span>
				</div>
			</div>
			<div class="tve_colm tve_twc tve_lst">
				<h2 class="rft" style="color: #fbfbfb; font-size: 60px;margin-top: 0;margin-bottom: 5px;">
					<span class="bold_text">Reach your limits!</span><br/>
					Be the best version of yourself.
				</h2>
				<h5 style="color: #fbfbfb; font-size: 30px;margin-top: 0;margin-bottom: 30px;">
					Take this 6-day mini course and reach new heights:
				</h5>
				<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_blue tve_2"
				     data-inputs-count="2" data-tve-style="1" data-tve-version="1"
				     style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<input type="hidden" class="tve-lg-err-msg"
					       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
								<input type="email" data-placeholder="" value="" name="email"
								       placeholder="Your email"/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
								<button type="Submit">Download now</button>
							</div>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper thrv_content_container_shortcode gr-button-container">
					<div class="tve_clear"></div>
					<div class="tve_right tve_content_inner"
					     style="width: 430px;min-width:50px; min-height: 2em;margin-top: -70px;">
						<p style="color: #cbcdd0; font-size: 31px;" class="tve_p_center">
							<a href="javascript:void(0)" class="tve_evt_manager_listen tve_et_click"
							   data-tcb-events="|close_form|">No, I don’t like free stuff</a>
						</p>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
		</div>
		<div class="thrv_wrapper thrv_icon aligncenter gr-close-button tve_no_drag" style="">
            <span data-tve-icon="gr-ten-set-close" class="tve_sc_icon gr-ten-set-close tve_blue tve_evt_manager_listen tve_et_click"
                  style="background: #fff; font-size: 40px;" data-tcb-events="|close_form|"></span>
		</div>
	</div>
</div>


