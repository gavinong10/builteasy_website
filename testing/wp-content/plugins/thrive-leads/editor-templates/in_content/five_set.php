<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div
	class="thrv-leads-form-box tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_five_set tve_white tve_brdr_solid thrv-leads-in-content">
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
		<div class="tve_colm tve_oth">
			<div style="width: 210px;margin-top: 0;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter img_style_lifted_style1">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set5_book_black.png' ?>"
                         style="width: 210px"/>
                </span>
			</div>
		</div>
		<div class="tve_colm tve_tth tve_lst">
			<h1 style="color: #3f3f3f; font-size: 30px;margin-top: 0;margin-bottom: 25px;">TOP <font color="#69bd43">10 ITALIAN PIZZA</font> RECIPES</h1>
			<p style="color: #3f3f3f; font-size: 15px;margin-top: 0;margin-bottom: 0;">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit.</p>
			<div class="thrv_wrapper thrv_bullets_shortcode">
				<ul class="tve_ul tve_ul1 tve_green">
					<li>Nemo enim ipsam voluptatem voluptas sit aspernatur.</li>
					<li>Nemo enim ipsam voluptatem voluptas sit aspernatur.</li>
				</ul>
			</div>
			<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
				<div class="tve_cb tve_cb6 tve_white">
					<div class="tve_cb_cnt">
						<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_horizontal tve_green tve_2" data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
							<div class="thrv_lead_generation_code" style="display: none;"></div>
							<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
							<div class="thrv_lead_generation_container tve_clearfix">
								<div class="tve_lead_generated_inputs_container tve_clearfix">
									<div class="tve_lead_fields_overlay"></div>
									<div class=" tve_lg_input_container tve_lg_2 tve_lg_input">
										<input type="text" data-placeholder="" value="" name="email" placeholder="Email Address"/>
									</div>
									<div class="tve_lg_input_container tve_submit_container tve tve_lg_2 tve_lg_submit">
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
</div>