<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<style type="text/css">
	#wpadminbar {
		z-index: 999992 !important;
	}
</style>
<div class="thrv-ribbon tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_two_set tve_blue">
	<div class="tve-ribbon-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_columns" style="margin-top: 0; margin-bottom: 0;">
			<div class="tve_colm tve_twc">
				<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
					<div class="tve_cb tve_cb6 tve_blue" style="margin-top: -21px;margin-bottom: -21px;">
						<div class="tve_cb_cnt">
							<h3 class="tve_p_right" style="margin-top: 0;margin-bottom: 0;"><span class="bold_text">Aenean sollicitudin,</span> lorem quis bibendum auctor, nisi elitis.</h3>
						</div>
					</div>
				</div>
			</div>
			<div class="tve_colm tve_twc tve_lst">
				<div class="thrv_wrapper thrv_content_container_shortcode">
					<div class="tve_clear"></div>
					<div class="tve_left tve_content_inner" style="width: 600px;min-width:50px; min-height: 2em;">
						<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_black tve_3" data-inputs-count="3" data-tve-style="1" style="margin-top: 5px; margin-bottom: 0;">
							<div class="thrv_lead_generation_code" style="display: none;"></div>
							<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
							<div class="thrv_lead_generation_container tve_clearfix">
								<div class="tve_lead_generated_inputs_container tve_clearfix">
									<div class="tve_lead_fields_overlay"></div>
									<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
										<input type="text" data-placeholder="" value="" name="name" placeholder="Name"/>
									</div>
									<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
										<input type="text" data-placeholder="" value="" name="email" placeholder="Email"/>
									</div>
									<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
										<button type="Submit">SUBSCRIBE</button>
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

	<a href="javascript:void(0)" class="tve-ribbon-close" title="Close">x</a>
</div>
