<?php
$config = array(
	'email'    => 'Please enter a valid email address',
	'phone'    => 'Please enter a valid phone number',
	'required' => 'Name and Email fields are required'
)
/**
 * STATE EVENTS examples (important bits: data-tcb-events and the "tve_evt_manager_listen tve_et_click" classes
 *
 * -close lb:
 * <a href="#" data-tcb-events="|close_lightbox|" class="tve_evt_manager_listen tve_et_click">CLOSE THIS LIGHTBOX</a>
 *
 * -state switch example ( open_state_x, where x is the index in the _config / multi_step / states array:
 * <a href="#" data-tcb-events="|open_state_2|" class="tve_evt_manager_listen tve_et_click">open state 2</a>
 */
?>
<div class="thrv_wrapper thrv_columns tve_clearfix" style="margin-top: 0;margin-bottom: 0;">
	<div class="tve_colm tve_foc tve_df tve_ofo ">
		<div style="width: 206px;margin-top: 70px;margin-bottom: 70px;margin-right: -70px;"
		     class="thrv_wrapper tve_image_caption aligncenter">
                                 <span class="tve_image_frame">
                                    <img class="tve_image"
                                         src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/48_set_image.png' ?>"
                                         style="width: 206px;"/>
                                </span>
		</div>
	</div>
	<div class="tve_colm tve_tfo tve_df tve_lst">
		<div class="thrv_wrapper thrv_contentbox_shortcode" data-tve-style="5">
			<div class="tve_cb tve_cb5 tve_white" style="margin: -20px -21px -20px -20px;">
				<div class="tve_cb_cnt">
					<div class="thrv_wrapper thrv_content_container_shortcode set-48-contentcontainer">
						<div class="tve_clear"></div>
						<div class="tve_right tve_content_inner" style="width: 450px;min-width:50px; min-height: 2em;margin-right: 20px;">
							<h2 style="color: #000000; font-size: 32px; margin-top: 30px;margin-bottom: 25px;" class="rft">
								Optimize your content for a
								<span class="bold_text">SALES FOCUSED</span> Website
							</h2>
							<p style="color: #5e5e5e; font-size: 20px;margin-top: 0;margin-bottom: 30px;">
								My 10 step guide for optimizing those
								small details that will end up making
								<span class="bold_text">a big difference in your sales.</span>
							</p>
							<div data-tve-style="1"
							     class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn"
							     draggable="true">
								<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_green">
									<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
									   data-tcb-events="|open_state_2|">
                                        <span class="tve_left tve_btn_im">
                                        <i></i>
                                        <span class="tve_btn_divider"></span>
                                        </span>
										<span class="tve_btn_txt">Send me the FREE Download</span>
									</a>
								</div>
							</div>

						</div>
						<div class="tve_clear"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>