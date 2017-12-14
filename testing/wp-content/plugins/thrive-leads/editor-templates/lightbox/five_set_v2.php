<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
?>
<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
	<div class="tve_colm tve_tth">
		<h1 style="color: #3f3f3f; font-size: 32px;margin-top: 0;margin-bottom: 35px;">TOP <font color="#69bd43">10 ITALIAN PIZZA</font> RECIPES</h1>
		<h6 style="color: #3f3f3f; font-size: 18px;margin-top: 0;margin-bottom: 45px;">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia.</h6>
		<ul class="thrv_wrapper">
			<li>Nemo enim ipsam voluptatem voluptas sit aspernatur.</li>
			<li>Nemo enim ipsam voluptatem voluptas sit aspernatur.</li>
			<li>Nemo enim ipsam voluptatem voluptas sit aspernatur.</li>
		</ul>
		<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_white">
				<div class="tve_cb_cnt">
					<div data-tve-style="1" class="thrv_wrapper thrv_lead_generation tve_clearfix tve_green tve_draggable thrv_lead_generation_horizontal tve_2" data-inputs-count="2" draggable="true" style="margin-top: 0;margin-bottom: 0;">
						<div style="display: none;" class="thrv_lead_generation_code"></div>
						<input type="hidden" class="tve-lg-err-msg" value="<?php echo htmlspecialchars( json_encode( $config ) ) ?>"/>
						<div class="thrv_lead_generation_container tve_clearfix">
							<div class="tve_lead_generated_inputs_container tve_clearfix">
								<div class="tve_lead_fields_overlay"></div>
								<div class="tve_lg_input_container  tve_lg_2 tve_lg_input">
									<input type="text" name="email" value="" data-placeholder="Email Address" placeholder="Email Address">
								</div>
								<div class="tve_lg_input_container tve_submit_container tve_lg_2 tve_lg_submit">
									<button type="Submit">GET INSTANT ACCESS</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tve_colm tve_oth tve_lst">
		<div style="width: 294px;margin-top: 25px;" class="thrv_wrapper tve_image_caption aligncenter img_style_lifted_style1">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set5_book_black.png' ?>"
                     style="width: 294px"/>
            </span>
		</div>
	</div>
</div>