<div class="tve_cpanel_sec tve_cpanel_sep">
	<span class="tve_cpanel_head tve_expanded"><?php echo __( "Thrive Theme Elements", "thrive-cb" ) ?></span>
</div>
<div class="tve_cpanel_list">
	<?php if ( $is_thrive_theme ) : ?>
		<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Borderless Content", "thrive-cb" ) ?>">
			<div class="tve_icm tve-ic-uniE63C tve_left"></div>
			<span class="tve_expanded tve_left"><?php echo __( "Borderless Content", "thrive-cb" ) ?></span>
                <span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded"
                      id="sub_02"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<li class="cp_draggable sc_borderless_image ui-draggable" title="<?php echo __( "Borderless Image", "thrive-cb" ) ?>" data-elem="sc_borderless_image">
							<div class="tve_icm tve-ic-plus"></div><?php echo __( "Borderless Image", "thrive-cb" ) ?>
						</li>
						<li class="cp_draggable sc_borderless_html ui-draggable" title="<?php echo __( "Borderless Video Embed", "thrive-cb" ) ?>" data-elem="sc_borderless_html">
							<div class="tve_icm tve-ic-plus"></div><?php echo __( "Borderless Video Embed", "thrive-cb" ) ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<?php endif ?>
	<div class="cp_draggable sc_page_section tve_option_separator tve_clearfix" title="<?php echo __( "Page Section", "thrive-cb" ) ?>" data-elem="sc_page_section">
		<div class="tve_icm tve-ic-layout tve_left"></div>
		<span class="tve_expanded tve_left"><?php echo __( "Page Section", "thrive-cb" ) ?></span>

	</div>
	<?php if ( $is_thrive_theme ) : ?>
		<div class="tve_option_separator tve_clearfix" title="<?php echo __( "Thrive Opt-In", "thrive-cb" ) ?>">
			<div class="tve_icm tve-ic-share-square-o tve_left"></div>
			<span class="tve_expanded tve_left"><?php echo __( "Thrive Opt-In", "thrive-cb" ) ?></span>
			<span class="tve_caret tve_icm tve_right tve_sub_btn tve_expanded"></span>

			<div class="tve_clear"></div>
			<div class="tve_sub_btn">
				<div class="tve_sub active_sub_menu">
					<ul>
						<?php if ( ! empty( $thrive_optins ) ) : ?>
							<?php foreach ( $thrive_optins as $_id => $_title ) : ?>
								<li class="cp_draggable sc_thrive_optin ui-draggable" title="<?php echo $_title ?>" data-elem="sc_thrive_optin"
								    data-wpapi="1"
								    data-overlay="1">
									<div class="tve_icm tve-ic-plus"></div><?php echo $_title ?>
									<input type="hidden" name="optin" value="<?php echo $_id ?>"/>
									<input type="hidden" name="text" value="Subscribe Now"/>
									<input type="hidden" name="color" value="blue"/>
								</li>
							<?php endforeach ?>
						<?php else : ?>
							<li class="tve-no-entry"><?php echo __( "No Thrive Opt-In found", "thrive-cb" ) ?></li>
						<?php endif ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="cp_draggable sc_thrive_posts_list tve_option_separator tve_clearfix" title="<?php echo __( "Thrive Post List", "thrive-cb" ) ?>" data-elem="sc_thrive_posts_list" data-wpapi="1" data-overlay="1">
			<div class="tve_icm tve-ic-list tve_left"></div>
			<span class="tve_expanded tve_left"><?php echo __( "Thrive Posts List", "thrive-cb" ) ?></span>
		</div>
		<?php if ( ! in_array( $current_theme_name, $banned_themes_names ) ) : ?>
			<div class="cp_draggable sc_thrive_custom_phone tve_option_separator tve_clearfix" title="<?php echo __( "Thrive Click To Call", "thrive-cb" ) ?>" data-elem="sc_thrive_custom_phone" data-wpapi="1" data-overlay="1">
				<div class="tve_icm tve-ic-phone tve_left"></div>
				<span class="tve_expanded tve_left"><?php echo __( "Thrive Click To Call", "thrive-cb" ) ?></span>
			</div>
		<?php endif; ?>
	<?php endif ?>
</div>