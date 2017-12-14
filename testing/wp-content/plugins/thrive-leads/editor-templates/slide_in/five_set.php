<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/* always include all elements inside a thrv-leads-slide-in element */
?>
<div class="thrv-leads-slide-in tve_no_drag tve_no_icons tve_element_hover thrv_wrapper tve_editor_main_content tve_five_set tve_brdr_solid">
	<h1 style="color: #3f3f3f; font-size: 30px;margin-top: 0;margin-bottom: 25px;">TOP <font color="#69bd43">10 ITALIAN PIZZA</font></h1>
	<p style="color: #3f3f3f; font-size: 15px;margin-top: 0;margin-bottom: 0;">
		Lorem ipsum dolor sit amet, consectetur adipisicing elit,
		sed do eiusmod tempor incididunt ut labore et dolore magna.
	</p>
	<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 30px;">
		<div class="tve_colm tve_oth">
			<div style="width: 132px;margin-top: 10px;margin-bottom: 0;" class="thrv_wrapper tve_image_caption aligncenter img_style_lifted_style1">
                 <span class="tve_image_frame">
                    <img class="tve_image"
                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set5_small_book.png' ?>"
                         style="width: 132px"/>
                </span>
			</div>
		</div>
		<div class="tve_colm tve_tth tve_lst">
			<ul class="thrv_wrapper">
				<li style="margin-bottom: 20px;">Nemo enim ipsam voluptatem voluptas sit aspernatur.</li>
				<li style="margin-bottom: 20px;">Nemo enim ipsam voluptatem voluptas sit aspernatur.</li>
				<li style="margin-bottom: 20px;">Nemo enim ipsam voluptatem voluptas sit aspernatur.</li>
			</ul>
		</div>
	</div>
	<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="6">
		<div class="tve_cb tve_cb6 tve_red" style="margin-left: -25px;margin-right: -25px;margin-bottom: 25px;">
			<div class="tve_cb_cnt">
				<h6 style="color: #fff;font-size: 18px;margin-top: 0;margin-bottom: 0;">
                    <span class="bold_text">
                        Sign up to download this <span class="underline_text">FREE</span> Ebook
                    </span>
				</h6>
			</div>
		</div>
	</div>
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
	<a href="javascript:void(0)" class="tve-leads-close" title="<?php echo __( 'Close', 'thrive-leads' ) ?>">x</a>
</div>