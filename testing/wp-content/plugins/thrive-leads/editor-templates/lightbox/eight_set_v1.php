<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<h6 style="color: #505050; font-size: 15px;margin-top: 15px;margin-bottom: 10px;">SED UT PERSPICIATIS UNDE OMNIS ISTE NATUS ERROR SIT VOLUPTATEM ACCUSANTIUM DOLOREMQUE LAUDA</h6>
<h2 style="color: #252525; font-size: 36px;margin-top: 30px;margin-bottom: 30px;">LEARN HOW TO MAKE A <font color="#1c81a5">WEBSITE WITH WORDPRESS</font></h2>
<div class="thrv_wrapper thrv_columns tve_clearfix">
	<div class="tve_colm tve_tth">
		<div class="thrv_wrapper thrv_bullets_shortcode">
			<ul class="tve_ul tve_ul6 tve_blue">
				<li>Lorem ipsum dolor sit amet, <font color="#fe2872">consectetur adipisicing</font> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
				<li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris <font color="#fe2872">nisi ut aliquip ex</font> ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate.</li>
				<li>Excepteur sint <font color="#fe2872">occaecat cupidatat</font> non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
			</ul>
		</div>
	</div>
	<div class="tve_colm tve_oth tve_lst">
		<div style="width: 165px; margin-top: 30px;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/eight_set_lightbox.png' ?>"
                         style="width: 165px;"/>
                </span>
		</div>
	</div>
</div>
<div
	class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_blue tve_3"
	data-inputs-count="3" data-tve-style="1" style="margin-top: 20px; margin-bottom: 0;">
	<div class="thrv_lead_generation_code" style="display: none;"></div>
	<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
	<div class="thrv_lead_generation_container tve_clearfix">
		<div class="tve_lead_generated_inputs_container tve_clearfix">
			<div class="tve_lead_fields_overlay"></div>
			<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
				<div class="thrv_wrapper thrv_icon tve_brdr_solid">
					<span class="tve_sc_icon eight-set-icon-user tve_blue" data-tve-icon="icon-support" style="color: #1c81a5;"></span>
				</div>
				<input type="text" data-placeholder="" value="" name="name"
				       placeholder="Full Name" style="padding-right: 50px!important;"/>
			</div>
			<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
				<div class="thrv_wrapper thrv_icon tve_brdr_solid">
					<span class="tve_sc_icon eight-set-icon-email tve_blue" data-tve-icon="icon-support" style="color: #1c81a5;"></span>
				</div>
				<input type="text" data-placeholder="" value="" name="email"
				       placeholder="Email Address" style="padding-right: 50px!important;"/>
			</div>
			<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
				<button type="Submit">Sign up and download!</button>
			</div>
		</div>
	</div>
</div>


