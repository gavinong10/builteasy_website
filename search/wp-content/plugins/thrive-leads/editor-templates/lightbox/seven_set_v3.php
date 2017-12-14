<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;">
	<div class="tve_colm tve_tth">
		<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_rounded_cb_top" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_white" style="margin: -30px 0 0 -56px;">
				<div class="tve_cb_cnt">
					<h1 style="color: #333333; font-size: 45px;margin-top: 15px;margin-bottom: 30px;">
						FIND <font color="#7c9646">THE BEST</font> PLACES TO TRAVEL, EXPLORE THE PLANET
					</h1>

					<div
						class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_green tve_2"
						data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 30px;">
						<div class="thrv_lead_generation_code" style="display: none;"></div>
						<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
						<div class="thrv_lead_generation_container tve_clearfix">
							<div class="tve_lead_generated_inputs_container tve_clearfix">
								<div class="tve_lead_fields_overlay"></div>
								<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
									<input type="text" data-placeholder="" value="" name="email"
									       placeholder="email*"/>
								</div>
								<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
									<button type="Submit">Get the App</button>
								</div>
							</div>
						</div>
					</div>
					<blockquote style="color: #666666; font-size: 22px;margin-top: 0;margin-bottom: 0;">
						Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem
						nibh id elit ad
						nunc velit auctor.”
					</blockquote>
				</div>
			</div>
		</div>
		<div class="thrv_wrapper thrv_contentbox_shortcode tve_seven_rounded_cb_bottom" data-tve-style="6">
			<div class="tve_cb tve_cb6 tve_white" style="margin: 0 0 -50px -56px;">
				<div class="tve_cb_cnt">
					<h4 style="color: #333333; font-size: 20px;margin-top: 0;margin-bottom: 15px;">Nibh vel velit auctor aliquet quis bibendum auctor elit:</h4>
					<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 0;">
						<div class="tve_colm tve_oth">
							<div class="thrv_wrapper thrv_bullets_shortcode">
								<ul class="tve_ul tve_ul1 tve_green">
									<li>Lorem quis elit bibendum auctor</li>
								</ul>
							</div>
						</div>
						<div class="tve_colm tve_oth">
							<div class="thrv_wrapper thrv_bullets_shortcode">
								<ul class="tve_ul tve_ul1 tve_green">
									<li>Lorem quis elit bibendum auctor</li>
								</ul>
							</div>
						</div>
						<div class="tve_colm tve_thc tve_lst">
							<div class="thrv_wrapper thrv_bullets_shortcode">
								<ul class="tve_ul tve_ul1 tve_green">
									<li>Lorem quis elit bibendum auctor</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tve_colm tve_oth tve_lst">
		<div style="width: 251px;margin-top: 10px;margin-bottom: 0;"
		     class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set7_phone.png' ?>"
                     style="width: 251px"/>
            </span>
		</div>
	</div>
</div>
