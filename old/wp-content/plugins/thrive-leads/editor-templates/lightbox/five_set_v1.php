<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
	<div class="tve_colm tve_oth">
		<div style="width: 294px;margin-top: 25px;" class="thrv_wrapper tve_image_caption aligncenter img_style_lifted_style1">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set5_lightbox1_book.png' ?>"
                     style="width: 294px"/>
            </span>
		</div>
	</div>
	<div class="tve_colm tve_tth tve_lst">
		<div class="thrv_wrapper thrv_contentbox_shortcode tve_cb5_round" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_white" style="margin-top: -15px;margin-bottom: -15px;">
				<div class="tve_cb_cnt">
					<h1 style="color: #3f3f3f; font-size: 30px;margin-top: 0;margin-bottom: 25px;">TOP <font color="#69bd43">10 ITALIAN PIZZA</font> RECIPES</h1>
					<p style="color: #3f3f3f; font-size: 15px;margin-top: 0;margin-bottom: 0;">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia.</p>
					<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-bottom: 0;">
						<div class="tve_colm tve_oth">
							<ol class="thrv_wrapper">
								<li>Duis aute irure dolor in rehenderit in voluptate.</li>
								<li>Duis aute irure dolor in rehenderit in voluptate.</li>
								<li>Duis aute irure dolor in rehenderit in voluptate.</li>
							</ol>
						</div>
						<div class="tve_colm tve_tth tve_lst">
							<div class="thrv_wrapper thrv_testimonial_shortcode" data-tve-style="1">
								<div class="tve_ts tve_ts1 tve_red" style="margin-bottom: 0;">
									<div class="tve_ts_t">
										<span class="tve_ts_ql"></span>
										<p style="color: #000; font-size: 15px;margin-top: 0;margin-bottom: 0;">
											Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
											incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.
										</p>
									</div>
									<div class="tve_ts_o">
										<img src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/shanemelaugh2.jpg' ?>" alt=""/>
                            <span>
                                <b>Shane Melaugh</b>
                                <br/>
                                UI designer at thrivethemes
                            </span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_green tve_3" data-inputs-count="3" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
						<div class="thrv_lead_generation_code" style="display: none;"></div>
						<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
						<div class="thrv_lead_generation_container tve_clearfix">
							<div class="tve_lead_generated_inputs_container tve_clearfix">
								<div class="tve_lead_fields_overlay"></div>
								<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
									<input type="text" data-placeholder="" value="" name="name" placeholder="Full Name"/>
								</div>
								<div class=" tve_lg_input_container tve_lg_3 tve_lg_input">
									<input type="text" data-placeholder="" value="" name="email" placeholder="Email Address"/>
								</div>
								<div class="tve_lg_input_container tve_submit_container tve tve_lg_3 tve_lg_submit">
									<button type="Submit">GET INSTANT ACCESS</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




