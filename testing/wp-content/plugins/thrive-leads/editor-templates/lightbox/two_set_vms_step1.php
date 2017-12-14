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
<h2 style="color: #333; font-size: 60px;margin-top: 0;margin-bottom: 0;" class="rft settwo_ms_heading tve_p_center">Your Website Analytics Are <span class="bold_text">Useless.</span></h2>
<div class="thrv_wrapper thrv_columns tve_clearfix">
	<div class="tve_colm tve_oth">
		<div style="width: 322px; margin-top: 0;margin-bottom: 0;"
		     class="thrv_wrapper tve_image_caption aligncenter">
             <span class="tve_image_frame">
                <img class="tve_image"
                     src="<?php echo TVE_LEADS_URL . 'editor-templates/_form_css/images/set2_multistep_book.png' ?>"
                     style="width: 322px;"/>
            </span>
		</div>
	</div>
	<div class="tve_colm tve_tth tve_lst">
		<p class="settwo_ms_paragraph" style="color: #999; font-size: 26px;margin-top: 0;margin-bottom: 50px;">90% of online businesses use web analytics as a fancy distraction, collect the wrong data or fail to take intelligent action based on their anayltics. Or all of the above.</p>
		<p class="settwo_ms_paragraph" style="color: #999; font-size: 26px;margin-top: 0;margin-bottom: 50px;">Get our free whitepaper to fix the problem and join the top 10% of businesses.</p>
	</div>
</div>
<div data-tve-style="1" class="thrv_wrapper thrv_button_shortcode tve_draggable tve_ea_tl_state_lightbox tve_fullwidthBtn" draggable="true">
	<div class="tve_btn tve_nb tve_btn1 tve_normalBtn tve_green">
		<a class="tve_btnLink tve_evt_manager_listen tve_et_click" href=""
		   data-tcb-events="|open_state_2|">
                    <span class="tve_left tve_btn_im">
                    <i></i>
                    <span class="tve_btn_divider"></span>
                    </span>
			<span class="tve_btn_txt">Yes, Send Me the Whitepaper</span>
		</a>
	</div>
</div>
<p style="color: #999; font-size: 26px;margin-top: 40px;margin-bottom: 0;" class="settwo_ms_paragraph tve_p_center underline_text">
	<a href="#" data-tcb-events="|close_lightbox|" class="tve_evt_manager_listen tve_et_click">No thanks, I’d like to stay in the dark.</a>
</p>

