<div class="tve_cpanel_sec tve_cpanel_sep">
	<span class="tve_cpanel_head tve_expanded"><?php echo __( "Multi-Style Elements", "thrive-cb" ) ?></span>
</div>
<div class="tve_cpanel_list">
	<?php include dirname( __FILE__ ) . '/user_templates.php' ?>
	<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Column Layout", 'thrive-cb' ) ?>">
		<div class="tve_icm tve-ic-columns tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Column Layout", 'thrive-cb' ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="<?php echo __( "Column Layout", "thrive-cb" ) ?>">
			<div class="tve_sub">
				<ul class="tve_columns_menu">
					<li class="cp_draggable standard_halfs tve_nohighlight" data-elem="standard_halfs">
						<div class="tve_colm tve_twc"><p>1/2</p></div>
						<div class="tve_colm tve_twc tve_lst"><p>1/2</p></div>
					</li>
					<li class="cp_draggable standard_thirds" data-elem="standard_thirds">
						<div class="tve_colm tve_oth"><p>1/3</p></div>
						<div class="tve_colm tve_oth"><p>1/3</p></div>
						<div class="tve_colm tve_thc tve_lst"><p>1/3</p></div>
					</li>
					<li class="cp_draggable standard_fourths" data-elem="standard_fourths">
						<div class="tve_colm tve_foc"><p>1/4</p></div>
						<div class="tve_colm tve_foc"><p>1/4</p></div>
						<div class="tve_colm tve_foc"><p>1/4</p></div>
						<div class="tve_colm tve_foc tve_lst"><p>1/4</p></div>
					</li>
					<li class="cp_draggable standard_fifths" data-elem="standard_fifths">
						<div class="tve_colm tve_fic"><p>1/5</p></div>
						<div class="tve_colm tve_fic"><p>1/5</p></div>
						<div class="tve_colm tve_fic"><p>1/5</p></div>
						<div class="tve_colm tve_fic"><p>1/5</p></div>
						<div class="tve_colm tve_fic tve_lst"><p>1/5</p></div>
					</li>
					<li class="cp_draggable standard_thirds_one_two" data-elem="standard_thirds_one_two">
						<div class="tve_colm tve_oth"><p>1/3</p></div>
						<div class="tve_colm tve_tth tve_lst"><p>2/3</p></div>
					</li>
					<li class="cp_draggable standard_thirds_two_one" data-elem="standard_thirds_two_one">
						<div class="tve_colm tve_tth"><p>2/3</p></div>
						<div class="tve_colm tve_oth tve_lst"><p>1/3</p></div>
					</li>
					<li class="cp_draggable standard_fourths_one_three" data-elem="standard_fourths_one_three">
						<div class="tve_colm tve_foc"><p>1/4</p></div>
						<div class="tve_colm tve_tfo tve_lst"><p>3/4</p></div>
					</li>
					<li class="cp_draggable standard_fourths_three_one" data-elem="standard_fourths_three_one">
						<div class="tve_colm tve_tfo"><p>3/4</p></div>
						<div class="tve_colm  tve_foc tve_lst"><p>1/4</p></div>
					</li>
					<li class="cp_draggable standard_two_fourths_half" data-elem="standard_two_fourths_half">
						<div class="tve_colm tve_foc"><p>1/4</p></div>
						<div class="tve_colm tve_foc"><p>1/4</p></div>
						<div class="tve_colm tve_twc tve_lst"><p>1/2</p></div>
					</li>
					<li class="cp_draggable standard_fourth_half_fourth" data-elem="standard_fourth_half_fourth">
						<div class="tve_colm tve_foc"><p>1/4</p></div>
						<div class="tve_colm tve_twc"><p>1/2</p></div>
						<div class="tve_colm tve_foc tve_lst"><p>1/4</p></div>
					</li>
					<li class="cp_draggable standard_half_fourth_fourth" data-elem="standard_half_fourth_fourth">
						<div class="tve_colm tve_twc"><p>1/2</p></div>
						<div class="tve_colm tve_foc"><p>1/4</p></div>
						<div class="tve_colm tve_foc tve_lst"><p>1/4</p></div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Content Box", 'thrive-cb' ) ?>">
		<div class="tve_icm tve-ic-file tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Content Box", 'thrive-cb' ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="<?php echo __( "Content Box", "thrive-cb" ) ?>">
			<div class="tve_sub">
				<ul>
					<li class="tve_sub_heading"><?php echo __( "With Headline", 'thrive-cb' ) ?></li>
					<li class="cp_draggable sc_contentbox1" data-elem="sc_contentbox1">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?></li>
					<li class="cp_draggable sc_contentbox2" data-elem="sc_contentbox2">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?></li>
					<li class="cp_draggable sc_contentbox3" data-elem="sc_contentbox3">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?></li>
					<li class="tve_sub_heading"><?php echo __( "Without Headline", 'thrive-cb' ) ?></li>
					<li class="cp_draggable sc_contentbox4" data-elem="sc_contentbox4">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "4" ) ?></li>
					<li class="cp_draggable sc_contentbox5" data-elem="sc_contentbox5">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "5" ) ?></li>
					<li class="cp_draggable sc_contentbox6" data-elem="sc_contentbox6">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "6" ) ?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Symbol Box", 'thrive-cb' ) ?>">
		<div class="tve_icm tve-ic-plus-square-o tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Symbol Box", 'thrive-cb' ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable" data-elem="sc_contentbox_icon">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Icon Box", 'thrive-cb' ) ?></li>
					<li class="cp_draggable" data-elem="sc_contentbox_text">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Number Box", "thrive-cb" ) ?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix">
		<div class="tve_icm tve-ic-share-alt-square tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Social Share Buttons", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="<?php echo __( "Twitter Share 1", "thrive-cb" ) ?>">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_social_custom tve_clearfix" data-elem="sc_social_custom">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Custom design", "thrive-cb" ) ?>
					</li>
					<li class="cp_draggable sc_social_default tve_clearfix" data-elem="sc_social_default">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Default design", "thrive-cb" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix">
		<div class="tve_icm tve-ic-comment-o tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Quote Share", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="<?php echo __( "Twitter Share 1", "thrive-cb" ) ?>">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_tw_quote_share1 tve_clearfix" data-elem="sc_tw_quote_share1">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Twitter Share 1", "thrive-cb" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Styled List", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-list2 tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Styled List", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_bullets1 tve_clearfix" data-elem="sc_bullets1">
						<div class="tve_icm tve-ic-plus tve_left"></div>
						<span class="tve_left"><?php echo sprintf( __( "Style %s", 'thrive-cb' ), "1" ) ?> &nbsp; </span>
						<div class="tve_icm tve-ic-checkmark tve_left tve_ic_hover"></div>
					</li>
					<li class="cp_draggable sc_bullets2 tve_clearfix" data-elem="sc_bullets2">
						<div class="tve_icm tve-ic-plus tve_left"></div>
						<span class="tve_left"><?php echo sprintf( __( "Style %s", 'thrive-cb' ), "2" ) ?> &nbsp; </span>
						<div class="tve_icm tve-ic-arrow-right2 tve_left tve_ic_hover"></div>
					</li>
					<li class="cp_draggable sc_bullets3 tve_clearfix" data-elem="sc_bullets3">
						<div class="tve_icm tve-ic-plus tve_left"></div>
						<span class="tve_left"><?php echo sprintf( __( "Style %s", 'thrive-cb' ), "3" ) ?> &nbsp; </span>
						<div class="tve_icm tve-ic-plus tve_left tve_ic_hover"></div>
					</li>
					<li class="cp_draggable sc_bullets4 tve_clearfix" data-elem="sc_bullets4">
						<div class="tve_icm tve-ic-plus tve_left"></div>
						<span class="tve_left"><?php echo sprintf( __( "Style %s", 'thrive-cb' ), "4" ) ?> &nbsp; </span>
						<div class="tve_icm tve-ic-warning tve_left tve_ic_hover"></div>
					</li>
					<li class="cp_draggable sc_bullets5 tve_clearfix" data-elem="sc_bullets5">
						<div class="tve_icm tve-ic-plus tve_left"></div>
						<span class="tve_left"><?php echo sprintf( __( "Style %s", 'thrive-cb' ), "5" ) ?> &nbsp; </span>
						<div class="tve_icm tve-ic-lightbulb-o tve_left tve_ic_hover"></div>
					</li>
					<li class="cp_draggable sc_bullets6 tve_clearfix" data-elem="sc_bullets6">
						<div class="tve_icm tve-ic-plus tve_left"></div>
						<span class="tve_left"><?php echo sprintf( __( "Style %s", 'thrive-cb' ), "6" ) ?> &nbsp; </span>
						<div class="tve_icm tve-ic-square-o tve_left tve_ic_hover"></div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Divider", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-minus tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Divider", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_divider1" data-elem="sc_divider1">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?>
					</li>
					<li class="cp_draggable sc_divider2" data-elem="sc_divider2">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?>
					</li>
					<li class="cp_draggable sc_divider3" data-elem="sc_divider3">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?>
					</li>
					<li class="cp_draggable sc_divider4" data-elem="sc_divider4">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "4" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Testimonials", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-quotes-left tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Testimonial", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="tve_sub_heading"><?php echo __( "Templates With Picture", "thrive-cb" ) ?></li>
					<li class="cp_draggable sc_testimonial1" data-elem="sc_testimonial1">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?>
					</li>
					<li class="cp_draggable sc_testimonial2" data-elem="sc_testimonial2">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?>
					</li>
					<li class="cp_draggable sc_testimonial3" data-elem="sc_testimonial3">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?>
					</li>
					<li class="cp_draggable sc_testimonial4" data-elem="sc_testimonial4">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "4" ) ?>
					</li>
					<li class="cp_draggable sc_testimonial9" data-elem="sc_testimonial9">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "5" ) ?>
					</li>
					<li class="tve_sub_heading"><?php echo __( "Templates Without Picture", "thrive-cb" ) ?></li>
					<li class="cp_draggable sc_testimonial5" data-elem="sc_testimonial5">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "6" ) ?>
					</li>
					<li class="cp_draggable sc_testimonial6" data-elem="sc_testimonial6">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "7" ) ?>
					</li>
					<li class="cp_draggable sc_testimonial7" data-elem="sc_testimonial7">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "8" ) ?>
					</li>
					<li class="cp_draggable sc_testimonial8" data-elem="sc_testimonial8">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "9" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Call to Action", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-lightbulb-o tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Call to Action", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_calltoaction1" data-elem="sc_calltoaction1">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?>
					</li>
					<li class="cp_draggable sc_calltoaction2" data-elem="sc_calltoaction2">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?>
					</li>
					<li class="cp_draggable sc_calltoaction3" data-elem="sc_calltoaction3">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?>
					</li>
					<li class="cp_draggable sc_calltoaction4" data-elem="sc_calltoaction4">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "4" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Guarantee", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-bookmark tve_left"></div>
		<span class="tve_expanded tve_left">Guarantee Box</span>
		<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="Guarantee">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_guarantee1" data-elem="sc_guarantee1">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "1" ) ?>
					</li>
					<li class="cp_draggable sc_guarantee2" data-elem="sc_guarantee2">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "2" ) ?>
					</li>
					<li class="cp_draggable sc_guarantee3" data-elem="sc_guarantee3">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "3" ) ?>
					</li>
					<li class="cp_draggable sc_guarantee4" data-elem="sc_guarantee4">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "Style %s", "thrive-cb" ), "4" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>