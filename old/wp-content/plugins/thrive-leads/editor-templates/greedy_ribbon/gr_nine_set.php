<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-greedy-ribbon tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_gr_nine_set tve_white" style="background-image: url('<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/gr_nine_set_bg.png' ?>');">
	<div class="tve-greedy-ribbon-content tve_editor_main_content">
		<h2 class="tve_p_center rft" style="color: #606060; font-size: 95px;margin-top: 0;margin-bottom: 50px;">
			<span class="bold_text">Double</span> your investment!
		</h2>
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner" style="width: 850px;min-width:50px; min-height: 2em;">
				<p class="tve_p_center" style="color: #606060; font-size: 31px;margin-top: 0;margin-bottom: 50px;">
					Download the forex cheat-sheet with the longest running track record of positive returns:
				</p>
				<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_blue tve_2"
				     data-inputs-count="2" data-tve-style="1" data-tve-version="1" style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
								<div class="thrv_wrapper thrv_icon tve_brdr_solid">
									<span class="tve_sc_icon gr-nine-set-mail tve_blue" data-tve-icon="gr-nine-set-mail"></span>
								</div>
								<input type="email" data-placeholder="" value="" name="email" placeholder="Enter your email address..."/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
								<button type="Submit">LET'S DO THIS</button>
							</div>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper thrv_content_container_shortcode gr-button-container">
					<div class="tve_clear"></div>
					<div class="tve_right tve_content_inner" style="width: 430px;min-width:50px; min-height: 2em;margin-top: -80px;">
						<p style="color: #9f9f9f; font-size: 35px;" class="tve_p_center rft">
                            <span class="bold_text">
                                <a href="javascript:void(0)" class="tve_evt_manager_listen tve_et_click"
                                   data-tcb-events="|close_form|">NO, I DON'T LIKE MONEY</a>
                            </span>
						</p>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
			<div class="tve_clear"></div>
		</div>

		<div style="width: 76px;margin-top: 0;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter gr-close-button">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/gr_nine_set_close.png' ?>"
                     style="width: 76px"/>
            </span>
		</div>
	</div>
</div>


