<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-widget element */
?>
<div class="thrv-leads-widget thrv_wrapper tve_editor_main_content tve_two_set_v1 tve_white tve_brdr_solid" style="background-image: url('<?php echo TVE_LEADS_URL . "editor-templates/_form_css/images/set_five_pattern.jpg" ?>');background-repeat: repeat">
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_cb_top_radius" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin-top: -30px;margin-left: -30px;margin-right: -30px;">
			<div class="tve_cb_cnt">
				<h1 style="color: #3f3f3f; font-size: 30px;margin-top: 0;margin-bottom: 25px;">TOP <font color="#69bd43">10 ITALIAN PIZZA</font> RECIPES</h1>
				<p style="color: #3f3f3f; font-size: 15px;margin-top: 0;margin-bottom: 0;">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia.</p>
			</div>
		</div>
	</div>
	<div style="width: 294px;margin-top: 30px;margin-bottom: 30px;" class="thrv_wrapper tve_image_caption aligncenter img_style_lifted_style1">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set5_lightbox1_book.png' ?>"
                     style="width: 294px"/>
            </span>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode tve_cb_bottom_radius" data-tve-style="5">
		<div class="tve_cb tve_cb5 tve_white" style="margin-left: -30px;margin-right: -30px;margin-bottom: -30px;">
			<div class="tve_cb_cnt">
				<div class="thrv_wrapper thrv_lead_generation tve_clearfix thrv_lead_generation_vertical tve_green tve_2" data-inputs-count="2" data-tve-style="1" style="margin-top: 0; margin-bottom: 0;">
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