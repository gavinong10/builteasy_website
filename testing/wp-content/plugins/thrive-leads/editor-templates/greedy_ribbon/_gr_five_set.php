<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-greedy-ribbon tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_gr_five_set tve_white" style="background-image: url('<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/gr_five_set_bg.jpg' ?>');background-size: cover; background-position: center center;">
	<div class="tve-greedy-ribbon-content tve_editor_main_content">
		<h2 class="rft" style="color: #000; font-size: 51px;margin-top: 0;margin-bottom: 10px;">
			SAVE 15% ON YOUR NEXT <br>
			BOOKING WITH US!
		</h2>
		<p style="color: #000; font-size: 22px;margin-top: 0;margin-bottom: 20px;">
			SUBSCRIBE TO GET THE COUPON SENT TO YOUR INBOX;
		</p>
		<div class="thrv_wrapper thrv_columns tve_clearfix">
			<div class="tve_colm tve_tth">
				<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_teal tve_2"
				     data-inputs-count="2" data-tve-style="1" data-tve-version="1" style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
								<input type="text" data-placeholder="" value="" name="email" placeholder="Email"/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
								<button type="Submit">SUBSCRIBE</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tve_colm tve_oth tve_lst">
				<div class="thrv_wrapper thrv_button_shortcode gr-five-button" data-tve-style="1">
					<div class="tve_btn tve_btn3 tve_nb tve_white tve_normalBtn">
						<a href="javascript:void(0)" class="tve_btnLink tve_evt_manager_listen tve_et_click"
						   data-tcb-events="|close_form|">
                                    <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                    </span>
							<span class="tve_btn_txt">NO THANKS</span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div style="width: 35px;margin-top: 0;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter gr-close-button tve_no_drag">
             <span class="tve_image_frame">
                <img class="tve_image tve_evt_manager_listen tve_et_click" data-tcb-events="|close_form|"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/gr_five_set_arrow.png' ?>"
                     style="width: 35px"/>
            </span>
		</div>
	</div>
</div>


