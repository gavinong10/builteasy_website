<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-form-box element */
?>

<div
	class="thrv-leads-screen-filler tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_nineteen_set tve_purple"
	style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set_19_bg.jpg" ?>');background-size:auto 100%;background-position: right center;background-repeat: no-repeat;">
	<div class="tve-screen-filler-content tve_editor_main_content">
		<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_purple" style="margin-left: -50px;margin-right: -50px;">
				<div class="tve_cb_cnt">
					<div class="thrv_wrapper thrv_content_container_shortcode">
						<div class="tve_clear"></div>
						<div class="tve_left tve_content_inner" style="width: 1000px;min-width:50px; min-height: 2em;">
							<h2 style="color: #fff; font-size:  62px;margin-top: 0;margin-bottom: 0;" class="rfs rft">
								<span class="bold_text">Delicious Desserts</span> Recipes
								Ready in just <font color="#e2c179"><span class="bold_text">30 Minutes</span></font>
							</h2>
						</div>
						<div class="tve_clear"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="thrv_wrapper thrv_content_container_shortcode">
			<div class="tve_clear"></div>
			<div class="tve_left tve_content_inner"
			     style="width: 1000px;min-width:50px; min-height: 2em;margin-top: 70px;">
				<div class="thrv_wrapper thrv_columns tve_clearfix">
					<div class="tve_colm tve_oth">
						<div style="margin-top: 0;margin-bottom: 0;width: 323px;"
						     class="thrv_wrapper tve_image_caption aligncenter">
                             <span class="tve_image_frame">
                                <img class="tve_image nineteen_set_cookie"
                                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set_19_product.png' ?>"
                                     style="width: 323px;"/>
                            </span>
						</div>
					</div>
					<div class="tve_colm tve_tth tve_lst">
						<h5 style="color: #fff; font-size: 32px;margin-top: 10px;margin-bottom: 20px;" class="rft">
							Subscribe now and get access to this Book:
						</h5>

						<div data-tve-style="1"
						     class="thrv_wrapper thrv_lead_generation tve_clearfix tve_red tve_draggable thrv_lead_generation_horizontal tve_2"
						     data-inputs-count="2"
						     draggable="true" style="margin-top: 0;margin-bottom: 30px;">
							<div style="display: none;" class="thrv_lead_generation_code"></div>
							<input type="hidden" class="tve-lg-err-msg"
							       value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>

							<div class="thrv_lead_generation_container tve_clearfix">
								<div class="tve_lead_generated_inputs_container tve_clearfix">
									<div class="tve_lead_fields_overlay"></div>
									<div class="tve_lg_input_container tve_lg_2 tve_lg_input">
										<input type="text" name="email" value="" data-placeholder="Email"
										       placeholder="Email">
									</div>
									<div class="tve_lg_input_container tve_submit_container tve_lg_2 tve_lg_submit">
										<button type="Submit">SUBSCRIBE</button>
									</div>
								</div>
							</div>
						</div>
						<h5 style="color: #fff; font-size: 32px;margin-top: 0;margin-bottom: 10px;" class="rft">* <font
								color="#eecd85">Free Download:</font> <a href="http://thrivethemes.com" target="_blank">Cake
								Decorating Tips</a></h5>

						<p style="color: #fff; font-size: 18px;margin-top: 0;margin-bottom: 0;">
							Unlock your free download by entering your email address above.
						</p>
					</div>
				</div>
			</div>
			<div class="tve_clear"></div>
		</div>
	</div>
	<a href="javascript:void(0)" class="tve-screen-filler-close" title="Close">x</a>
</div>
