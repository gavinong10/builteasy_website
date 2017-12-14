<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-greedy-ribbon tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_gr_two_set tve_orange">
	<div class="tve-greedy-ribbon-content tve_editor_main_content">
		<h2 class="tve_p_center rft" style="color: #fff; font-size: 70px;margin-top: 0;margin-bottom: 50px;">
			Recover Abandoned Carts & Make 23% More
		</h2>
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_center tve_content_inner" style="width: 800px;min-width:50px; min-height: 2em;">
				<p class="tve_p_center" style="color: #fff; font-size: 24px;margin-top: 0;margin-bottom: 50px;">
					Discover how e-commerce businesses can increase revenue by 23% or more, in just 7 days.
				</p>
				<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_black tve_2"
				     data-inputs-count="2" data-tve-style="1" data-tve-version="1" style="margin-top: 0; margin-bottom: 0;">
					<div class="thrv_lead_generation_code" style="display: none;"></div>
					<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

					<div class="thrv_lead_generation_container tve_clearfix">
						<div class="tve_lead_generated_inputs_container tve_clearfix">
							<div class="tve_lead_fields_overlay"></div>
							<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
								<input type="text" data-placeholder="" value="" name="email" placeholder="Email Address..."/>
							</div>
							<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
								<button type="Submit">FREE REPORT</button>
							</div>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper thrv_button_shortcode tve_centerBtn" data-tve-style="1">
					<div class="tve_btn tve_btn3 tve_nb tve_white tve_normalBtn">
						<a href="javascript:void(0)" class="tve_btnLink tve_evt_manager_listen tve_et_click"
						   data-tcb-events="|close_form|">
                    <span class="tve_left tve_btn_im">
                        <i></i>
                        <span class="tve_btn_divider"></span>
                    </span>
							<span class="tve_btn_txt">No Thanks</span>
						</a>
					</div>
				</div>
			</div>
			<div class="tve_clear"></div>
		</div>
		<div class="thrv_wrapper thrv_icon aligncenter gr-close-button tve_no_drag"
		     style="border: 4px solid #fff;">
            <span data-tve-icon="gr-two-set-close" class="tve_sc_icon gr-two-set-close tve_white tve_evt_manager_listen tve_et_click"
                  style="font-size: 48px;" data-tcb-events="|close_form|"></span>
		</div>
	</div>
</div>


