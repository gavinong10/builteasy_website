<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-greedy-ribbon tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_gr_eight_set tve_white"
     style="background-image: url('<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/gr_eight_set_bg.jpg' ?>');background-size: cover; background-position: center center;">
	<div class="tve-greedy-ribbon-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner" style="width: 1080px;min-width:50px; min-height: 2em;">
				<h2 class="tve_p_center rft" style="color: #fff; font-size: 80px;margin-top: 40px;margin-bottom: 0;">
					Sign up below to get access
					to the FREE Articles
				</h2>
			</div>
			<div class="tve_clear"></div>
		</div>
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner"
			     style="width: 1050px;min-width:50px; min-height: 2em;margin-top: 40px;">
				<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
					<div class="tve_cb tve_cb6 tve_white">
						<div class="tve_cb_cnt">
							<div class="thrv_wrapper thrv_content_container_shortcode">
								<div class="tve_clear"></div>
								<div class="tve_center tve_content_inner" style="width: 530px;min-width:50px; min-height: 2em;margin-top: 60px;margin-bottom: -85px;">
									<div
										class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_blue tve_3"
										data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 20px;">
										<div class="thrv_lead_generation_code" style="display: none;"></div>
										<input type="hidden" class="tve-lg-err-msg"
										       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

										<div class="thrv_lead_generation_container tve_clearfix">
											<div class="tve_lead_generated_inputs_container tve_clearfix">
												<div class="tve_lead_fields_overlay"></div>
												<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
													<input type="text" data-placeholder="" value="" name="name"
													       placeholder="Your name"/>
												</div>
												<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
													<input type="text" data-placeholder="" value="" name="email"
													       placeholder="Your Email Address"/>
												</div>
												<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
													<button type="Submit">YES, SEND ME THE FREE ARTICLES</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tve_clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tve_clear"></div>
		</div>
		<div class="thrv_wrapper thrv_icon aligncenter gr-close-button tve_no_drag">
            <span data-tve-icon="gr-eight-set-close"
                  class="tve_sc_icon gr-eight-set-close tve_white tve_evt_manager_listen tve_et_click"
                  style="font-size: 90px;" data-tcb-events="|close_form|"></span>
		</div>
	</div>
</div>






