<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-form-box element */
?>

<div
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_seventeen_set tve_green">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_columns" style="margin-top: 110px;">
			<div class="tve_colm tve_tth">
				<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 60px;margin-top: 0;">
					<div class="tve_colm tve_tfo tve_df ">
						<h2 style="color: #333333; font-size: 62px;margin-top: 0;margin-bottom: 0;">
							START YOUR OWN BUSINESS! MAKE MONEY ONLINE THIS MONTH!
						</h2>
					</div>
					<div class="tve_colm  tve_foc tve_ofo tve_df tve_lst"><p>&nbsp;</p></div>
				</div>
				<div class="thrv_wrapper thrv_columns tve_clearfix">
					<div class="tve_colm tve_tfo tve_df ">
						<h4 style="color: #333; font-size: 34px;margin-top: 0;margin-bottom: 0;">
							Create a <span style="background: #cc3333;"><font color="#fff">FREE</font></span>
							Account to get
							Unlimited Access to our Resources!!!...
						</h4>
					</div>
					<div class="tve_colm  tve_foc tve_ofo tve_df tve_lst">
						<div style="width: 95px;margin-top: 0;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter">
                             <span class="tve_image_frame">
                                <img class="tve_image seventeen_set_arrow"
                                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set17_arrow.png' ?>"
                                     style="width: 95px;"/>
                            </span>
						</div>
					</div>
				</div>
			</div>
			<div class="tve_colm tve_oth tve_lst">
				<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
					<div class="tve_cb tve_cb5 tve_white">
						<div class="tve_cb_cnt">
							<h5 style="color: #333; font-size: 32px;margin-top: 50px;margin-bottom: 30px;"
							    class="tve_p_center">JOIN OUR
								TEAM...</h5>

							<p style="color: #666; font-size: 20px;margin-top: 0;margin-bottom: 25px;"
							   class="tve_p_center">Enter your
								name and email address below to get Instant Access to our <a
									href="http://thrivethemes.com" target="_blank">Free Resources.</a></p>

							<div data-tve-style="1"
							     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_red tve_draggable thrv_lead_generation_vertical tve_3"
							     data-inputs-count="3"
							     draggable="true" style="margin-top: 0;margin-bottom: 0;">
								<div style="display: none;" class="thrv_lead_generation_code"></div>
								<input type="hidden" class="tve-lg-err-msg"
								       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

								<div class="thrv_lead_generation_container tve_clearfix">
									<div class="tve_lead_generated_inputs_container tve_clearfix">
										<div class="tve_lead_fields_overlay"></div>
										<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
											<input type="text" name="name" value="" data-placeholder="First Name"
											       placeholder="First Name">
										</div>
										<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
											<input type="text" name="email" value="" data-placeholder="Email"
											       placeholder="Email">
										</div>
										<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
											<button type="Submit">YES, I WANT TO JOIN</button>
										</div>
									</div>
								</div>
								<p style="color: #999999; font-size: 18px;margin-top: 0;margin-bottom: 50px;" class="tve_p_center">Your Privacy is protected.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
