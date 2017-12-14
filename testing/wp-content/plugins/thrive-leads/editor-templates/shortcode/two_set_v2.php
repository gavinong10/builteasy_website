<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_two_set tve_white">
	<div class="thrv_wrapper thrv_columns tve_clearfix">
		<div class="tve_colm tve_foc tve_df tve_ofo ">
			<div class="thrv_wrapper thrv_icon aligncenter" style="font-size: 40px; border-radius: 15px;">
				<span data-tve-icon="icon-lock" class="tve_sc_icon two-set-lock" style="color: #fff;background-color: rgb(1, 116, 212); padding: 22px; border-radius: 15px; font-size: 87px; width: 87px; height: 87px;"></span>
			</div>
		</div>
		<div class="tve_colm tve_tfo tve_df tve_lst">
			<h2 style="color: #78ac49; font-size: 32px; margin-top: 0px;margin-bottom: 0;" class="">
				<span class="bold_text">PREMIUM CONTENT LOCKED</span></h2>

			<h2 style="color: #333333; font-size: 32px;margin-top: 0;margin-bottom: 20px;" class="" contenteditable="false">Enter Your Details For Instant Access </h2>

			<h2 class="" style="color: #333333; font-size: 32px;margin-top: 0;margin-bottom: 20px;" contenteditable="false"></h2>

			<p style="color: #999999; font-size: 18px; line-height: 25px;margin-top: 0;" class="">
				Duis sed odio sit amet nibh vulputate cursus a sit amet
				mauris aenean sollicitudin, lorem bibendum?
			</p>
		</div>
	</div>
	<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green thrv_lead_generation_horizontal tve_3" data-inputs-count="3" style="margin-top: 20px;margin-bottom: 0;">
		<div style="display: none;" class="thrv_lead_generation_code"></div>
		<div style="display: none;" class="thrv_lead_generation_errors">__CONFIG_lead_generation__<?php echo json_encode( $config ) ?>__CONFIG_lead_generation__</div>
		<div class="thrv_lead_generation_container tve_clearfix">
			<div class="tve_lead_generated_inputs_container tve_clearfix">
				<div class="tve_lead_fields_overlay"></div>
				<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
					<input name="name" value="" data-placeholder="Name" placeholder="Name" type="text">
				</div>
				<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
					<input name="email" value="" data-placeholder="Email" placeholder="Email" type="text">
				</div>
				<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
					<button type="Submit">Unlock</button>
				</div>
			</div>
		</div>
	</div>
</div>