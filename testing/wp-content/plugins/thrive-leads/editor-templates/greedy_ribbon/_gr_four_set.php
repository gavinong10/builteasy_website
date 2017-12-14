<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-greedy-ribbon tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_gr_four_set tve_black" style="background-image: url('<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/gr_four_set_bg.jpg' ?>');background-size: auto 100%; background-position: left center;background-repeat: no-repeat">
	<div class="tve-greedy-ribbon-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner" style="width: 770px;min-width:50px; min-height: 2em;margin-top: 0;">
				<div class="thrv_wrapper thrv_columns tve_clearfix">
					<div class="tve_colm tve_tfo tve_df ">
						<h2 class="rft" style="color: #fff; font-size: 44px;margin-top: 10px;margin-bottom: 10px;">
							Make Your Website Load
							Ridiculously <font color="#00b0d2">Fast!</font>
						</h2>
					</div>
					<div class="tve_colm  tve_foc tve_ofo tve_df tve_lst">
						<div style="width: 108px;margin-top: 0;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter gr-four-set-image">
                     <span class="tve_image_frame">
                        <img class="tve_image"
                             src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/gr_four_set_image.png' ?>"
                             style="width: 108px"/>
                    </span>
						</div>
					</div>
				</div>
				<p style="color: #979696; font-size: 22px;margin-top: 0;margin-bottom: 30px;">
					Faster websites get more visitors, make more sales and are just all around
					cooler. Get our <span class="bold_text">free report</span> to find out how to make your site blazingly fast:
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
									<span class="tve_sc_icon gr-four-set-mail tve_white" data-tve-icon="gr-four-set-mail"></span>
								</div>
								<input type="text" data-placeholder="" value="" name="email" placeholder="Enter your email address"/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
								<button type="Submit">GIMME</button>
							</div>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper thrv_content_container_shortcode gr-button-container">
					<div class="tve_clear"></div>
					<div class="tve_right tve_content_inner" style="width: 377px;min-width:50px; min-height: 2em;margin-top: -113px;">
						<div class="thrv_wrapper thrv_button_shortcode tve_fullwidthBtn" data-tve-style="1">
							<div class="tve_btn tve_btn3 tve_nb tve_white tve_normalBtn">
								<a href="javascript:void(0)" class="tve_btnLink tve_evt_manager_listen tve_et_click"
								   data-tcb-events="|close_form|">
                                            <span class="tve_left tve_btn_im">
                                                <i></i>
                                                <span class="tve_btn_divider"></span>
                                            </span>
									<span class="tve_btn_txt">NO, I WANT A SLOW SITE</span>
								</a>
							</div>
						</div>
					</div>
					<div class="tve_clear"></div>
				</div>
			</div>
			<div class="tve_clear"></div>
		</div>
		<div class="thrv_wrapper thrv_icon aligncenter gr-close-button tve_no_drag" style="border: 3px solid rgba(255,255,255, .3)">
            <span data-tve-icon="gr-four-set-close" class="tve_sc_icon gr-four-set-close tve_white tve_evt_manager_listen tve_et_click"
                  style="font-size: 40px;" data-tcb-events="|close_form|"></span>
		</div>
	</div>
</div>


