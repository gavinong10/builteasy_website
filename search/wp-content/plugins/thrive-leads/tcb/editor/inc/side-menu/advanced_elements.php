<div class="tve_cpanel_sec tve_cpanel_sep">
	<span class="tve_cpanel_head tve_expanded"><?php echo __( "Advanced Elements", "thrive-cb" ) ?></span>
</div>
<div class="tve_cpanel_list">
	<div class="sc_table tve_option_separator tve_clearfix" title="<?php echo __( "Widgets", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-gears tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Widgets", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="<?php echo __( "Table", "thrive-cb" ) ?>">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable" title="<?php echo __( "Custom Menu", "thrive-cb" ) ?>" data-elem="sc_widget_menu" data-overlay="1" data-wpapi="1">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Custom Menu", "thrive-cb" ) ?>
						<?php foreach ( $menus as $item ) : /* by default, use the first available menu. If nothing is found, show the user a message that no custom menu is defined */ ?>
							<input type="hidden" name="menu_id" value="<?php echo $item['id'] ?>">
							<?php break; endforeach; ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_grid tve_option_separator tve_clearfix" title="<?php echo __( "Pricing Table", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-dollar tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Pricing Table", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_pricing_table_1col" title="<?php echo sprintf( __( "%s Column", "thrive-cb" ), "1" ) ?>" data-elem="sc_pricing_table_1col">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Column", "thrive-cb" ), "1" ) ?>
					</li>
					<li class="cp_draggable sc_pricing_table_2col" title="<?php echo sprintf( __( "%s Columns", "thrive-cb" ), "2" ) ?>" data-elem="sc_pricing_table_2col">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns", "thrive-cb" ), "2" ) ?>
					</li>
					<li class="cp_draggable sc_pricing_table_3col" title="<?php echo sprintf( __( "%s Columns", "thrive-cb" ), "3" ) ?>" data-elem="sc_pricing_table_3col">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns", "thrive-cb" ), "3" ) ?>
					</li>
					<li class="cp_draggable sc_pricing_table_4col" title="<?php echo sprintf( __( "%s Columns", "thrive-cb" ), "4" ) ?>" data-elem="sc_pricing_table_4col">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns", "thrive-cb" ), "4" ) ?>
					</li>
					<li class="cp_draggable sc_pricing_table_5col" title="<?php echo sprintf( __( "%s Columns", "thrive-cb" ), "5" ) ?>" data-elem="sc_pricing_table_5col">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns", "thrive-cb" ), "5" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="sc_table tve_option_separator tve_clearfix" title="<?php echo __( "Tabs", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-folder tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Tabbed Content", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="<?php echo __( "Table", "thrive-cb" ) ?>">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_tabs" title="<?php echo __( "Horizontal Tabs", "thrive-cb" ) ?>" data-elem="sc_tabs">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Horizontal Tabs", "thrive-cb" ) ?>
					</li>
					<li class="cp_draggable sc_tabs sc_vtabs" title="<?php echo __( "Vertical Tabs", "thrive-cb" ) ?>" data-elem="sc_vTabs">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Vertical Tabs", "thrive-cb" ) ?>
						<input type="hidden" name="vtabs" value="1"/>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_grid tve_option_separator tve_clearfix" title="<?php echo __( "Feature Grids", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-th tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Feature Grid", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded" id="sub_02"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="tve_sub_title"><strong><?php echo __( "Feature grid with Images", "thrive-cb" ) ?></strong></li>
					<li class="cp_draggable sc_feature_grid_2_column"
					    title="<?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "2" ) ?>" data-elem="sc_feature_grid_2_column">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "2" ) ?>
					</li>
					<li class="cp_draggable sc_feature_grid_3_column"
					    title="<?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "3" ) ?>" data-elem="sc_feature_grid_3_column">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "3" ) ?>
					</li>
					<li class="cp_draggable sc_feature_grid_4_column"
					    title="<?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "4" ) ?>" data-elem="sc_feature_grid_4_column">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "4" ) ?>
					</li>
					<li class="tve_sub_title"><strong><?php echo __( "Feature grid with Icons", "thrive-cb" ) ?></strong></li>
					<li class="cp_draggable sc_feature_grid_2_column"
					    title="<?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "2" ) ?>" data-elem="sc_feature_grid_2_column_icons">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "2" ) ?>
						<input type="hidden" name="use_icons" value="1"/>
					</li>
					<li class="cp_draggable sc_feature_grid_3_column"
					    title="<?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "3" ) ?>" data-elem="sc_feature_grid_3_column_icons">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "3" ) ?>
						<input type="hidden" name="use_icons" value="1"/>
					</li>
					<li class="cp_draggable sc_feature_grid_4_column"
					    title="<?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "4" ) ?>" data-elem="sc_feature_grid_4_column_icons">
						<div class="tve_icm tve-ic-plus"></div><?php echo sprintf( __( "%s Columns Feature Grid", "thrive-cb" ), "4" ) ?>
						<input type="hidden" name="use_icons" value="1"/>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="cp_draggable sc_toggle tve_option_separator tve_clearfix" title="<?php echo __( "Toggle", "thrive-cb" ) ?>" data-elem="sc_toggle">
		<div class="tve_icm tve-ic-eye-blocked tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Content Toggle", "thrive-cb" ) ?></span>
	</div>
	<div class="sc_table tve_option_separator tve_clearfix" title="<?php echo __( "Table", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-table tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Table", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_table_plain" title="<?php echo __( "Plain", "thrive-cb" ) ?>" data-elem="sc_table_plain">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Plain", "thrive-cb" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="tve_option_separator tve_clearfix" title="Data Elements">
		<div class="tve_icm tve-ic-data-elements tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Data Elements", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="Data Elements">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_progress_bar" title="Progress Bar" data-elem="sc_progress_bar">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Progress Bar", "thrive-cb" ) ?>
					</li>
					<li class="cp_draggable sc_fill_counter" title="Fill Counter" data-elem="sc_fill_counter">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Fill Counter", "thrive-cb" ) ?>
					</li>
					<li class="cp_draggable sc_number_counter" title="Number Counter" data-elem="sc_number_counter">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Number Counter", "thrive-cb" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="cp_draggable sc_gmap tve_option_separator tve_clearfix" title="<?php echo __( "Google Map", "thrive-cb" ) ?>" data-elem="sc_gmap">
		<div class="tve_icm tve-ic-location tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Google Map Embed", "thrive-cb" ) ?></span>
	</div>
	<div class="sc_countdown_timer tve_option_separator tve_clearfix" title="<?php echo __( "Countdown Timer", "thrive-cb" ) ?>">
		<div class="tve_icm tve-ic-clock tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Countdown Timer", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_countdown_timer sc_countdown_timer_plain" title="<?php echo __( "Countdown", "thrive-cb" ) ?>" data-elem="sc_countdown_timer_plain">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Countdown", "thrive-cb" ) ?>
						<input type="hidden" name="wp_timezone" value="<?php echo $tzd ?>"/>
						<input type="hidden" name="wp_timezone_offset" value="<?php echo $timezone_offset ?>"/>
					</li>
					<li class="cp_draggable sc_countdown_timer sc_countdown_timer_evergreen" title="<?php echo __( "Countdown Evergreen", "thrive-cb" ) ?>"
					    data-elem="sc_countdown_timer_evergreen">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Countdown Evergreen", "thrive-cb" ) ?>
						<input type="hidden" name="wp_timezone" value="<?php echo $tzd ?>"/>
						<input type="hidden" name="wp_timezone_offset" value="<?php echo $timezone_offset ?>"/>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="cp_draggable sc_responsive_video tve_option_separator tve_clearfix" title="<?php echo __( "Responsive Video", "thrive-cb" ) ?>" data-elem="sc_responsive_video">
		<div class="tve_icm tve-ic-play tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Responsive Video", "thrive-cb" ) ?></span>
	</div>
	<div class="cp_draggable sc_contents_table tve_option_separator tve_clearfix" title="<?php echo __( "Table Of Contents", "thrive-cb" ) ?>" data-elem="sc_contents_table">
		<div class="tve_icm tve-ic-list-alt tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Table of Contents", "thrive-cb" ) ?></span>
	</div>
	<div class="cp_draggable sc_lead_generation tve_option_separator tve_clearfix" title="<?php echo __( "Lead Generation", "thrive-cb" ) ?>" data-elem="sc_lead_generation">
		<div class="tve_icm tve-ic-envelope tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Lead Generation", "thrive-cb" ) ?></span>
	</div>
	<div class="cp_draggable tve_option_separator tve_clearfix" title="<?php echo __( "Post Grid", "thrive-cb" ) ?>" data-elem="sc_post_grid" data-overlay="1">
		<div class="tve_icm tve-ic-th-large tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Post Grid", "thrive-cb" ) ?></span>
		<input type="hidden" name="placeholder" value="1"/>
	</div>

	<div class="tve_option_separator tve_clearfix" title="Comments">
		<div class="tve_icm tve-ic-comment-o tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Comments", "thrive-cb" ) ?></span>
		<span class="tve_caret tve_icm tve_sub_btn tve_right tve_expanded"></span>

		<div class="tve_clear"></div>
		<div class="tve_sub_btn" title="Comments">
			<div class="tve_sub">
				<ul>
					<li class="cp_draggable sc_facebook_comments" title="Facebook" data-elem="sc_facebook_comments">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Facebook", "thrive-cb" ) ?>
					</li>
					<li class="cp_draggable sc_disqus_comments" title="Disqus" data-elem="sc_disqus_comments">
						<div class="tve_icm tve-ic-plus"></div><?php echo __( "Disqus", "thrive-cb" ) ?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<?php
	/**
	 * Add tcb elements on the sidebar
	 * TODO: move the Leads shortcode and TU shortcode into the corresponding places in TL and TU based on the following filter
	 *
	 * @since: 1.200.5
	 */
	do_action( 'tcb_advanced_elements_html', $cpanel_config );
	?>
</div>