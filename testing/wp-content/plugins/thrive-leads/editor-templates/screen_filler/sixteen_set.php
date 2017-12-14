<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-form-box element */
?>

<div class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_sixteen_set tve_white"
     style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set16_bg.jpg" ?>');background-size:cover;background-position: center center;">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_left tve_content_inner" style="width: 790px;min-width:50px; min-height: 2em;">
				<h5 style="color: #fff; font-size: 15px;margin-top: 10px;margin-bottom: 10px;">
                   <span style="background: #000;padding: 10px;">
                       FASHION TRENDS 2015
                   </span>
				</h5>
				<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
					<div class="tve_cb tve_cb5 tve_teal">
						<div class="tve_cb_cnt">
							<h2 style="color: #fff; font-size: 60px;margin-top: 25px;margin-bottom: 25px;" class="rft">
								STEP-BY-STEP GUIDE TO
								<font color="#def58e">INSANELY FLAWLESS MAKE-UP</font>
							</h2>
						</div>
					</div>
				</div>
				<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
					<div class="tve_cb tve_cb5 tve_white">
						<div class="tve_cb_cnt">
							<div data-tve-style="1"
							     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_black tve_draggable thrv_lead_generation_horizontal tve_2" data-inputs-count="2"
							     draggable="true" style="margin-top: 0;margin-bottom: 0;">
								<div style="display: none;" class="thrv_lead_generation_code"></div>
								<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
								<div class="thrv_lead_generation_container tve_clearfix">
									<div class="tve_lead_generated_inputs_container tve_clearfix">
										<div class="tve_lead_fields_overlay"></div>
										<div class="tve_lg_input_container tve_lg_2 tve_lg_input">
											<input type="text" name="email" value="" data-placeholder="Email" placeholder="Email">
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
			</div>
			<div class="tve_clear"></div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
