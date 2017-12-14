<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_columns" style="margin-top: 0;margin-bottom: 0;">
	<div class="tve_colm tve_twc">
		<div style="width: 358px; margin: 0;" class="thrv_wrapper tve_image_caption aligncenter">
            <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/uiuxpatterns.png' ?>"
                     style="width: 358px"/>
            </span>
		</div>
	</div>
	<div class="tve_colm tve_twc tve_lst">
		<h2 style="color: #000; font-size: 36px;margin-top: 0;margin-bottom: 15px;">Sign up to download this <font color="#6bbe65">FREE</font> Ebook</h2>
		<p style="color: #898989; font-size: 14px;margin-top: 0;margin-bottom: 25px;">You can download 5 magazines for free.</p>
		<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_vertical tve_3" data-inputs-count="3" draggable="true" style="margin-top: 0;margin-bottom: 0;">
			<div style="display: none;" class="thrv_lead_generation_code"></div>
			<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
			<div class="thrv_lead_generation_container tve_clearfix">
				<div class="tve_lead_generated_inputs_container tve_clearfix">
					<div class="tve_lead_fields_overlay"></div>
					<div class="tve_lg_input_container  tve_lg_3 tve_lg_input">
						<input type="text" name="name" value="" data-placeholder="">
					</div>
					<div class="tve_lg_input_container tve_lg_3 tve_lg_input">
						<input type="text" name="email" value="" data-placeholder="">
					</div>
					<div class="tve_lg_input_container tve_submit_container tve_lg_3 tve_lg_submit">
						<button type="Submit">Download Ebook</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




