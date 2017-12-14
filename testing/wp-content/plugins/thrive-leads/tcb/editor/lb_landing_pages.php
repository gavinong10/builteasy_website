<?php

$landing_pages = tve_get_landing_page_templates();
$tags          = array();
foreach ( $landing_pages as $code => $_item ) {


	if ( isset( $landing_pages[ $code ]['downloaded'] ) && $landing_pages[ $code ]['downloaded'] ) {
		$landing_pages[ $code ]['thumbnail'] = TVE_LANDING_PAGE_TEMPLATE_DOWNLOADED . '/thumbnails/' . $code . '.png';
	} else {
		$landing_pages[ $code ]['thumbnail'] = TVE_LANDING_PAGE_TEMPLATE . '/thumbnails/' . $code . '.png';
	}

	if ( empty( $_item['tags'] ) ) {
		continue;
	}
	foreach ( $_item['tags'] as $index => $tag ) {
		$clean                                            = strtolower( str_replace( ' ', '-', $tag ) );
		$tags[ $clean ]                                   = $tag;
		$landing_pages[ $code ]['tags_classes'][ $index ] = $clean;
	}
}
$tags['tag-downloaded-template'] = __( 'Downloaded templates', 'thrive-cb' );
?>
<div class="tve_large_lightbox">
	<h4 class="tve-with-filter">
		<?php echo __( 'Choose a landing page template', 'thrive-cb' ) ?>
		<span class="tve-quick-filter tve_lb_fields">
			<input class="tve_keyup tve_lightbox_input"
			       data-ctrl="controls.filter_lp"
			       type="text"
			       style="width: 170px"
			       placeholder="<?php echo __( "Quick filter...", "thrive-cb" ) ?>"
			       value=""
			       id="tve_landing_page_filter">
		</span>
	</h4>
	<div class=" thrv_columns tve_clearfix">
		<div class="tve_colm tve_foc tve_df tve_ofo">
			<?php if ( ! empty( $_POST['landing_page'] ) ) : ?>
				<div class="tve_message tve_warning" id="tve_landing_page_msg">
					<h6><?php echo __( "Warning - your changes will be lost", "thrive-cb" ) ?></h6>

					<p>
						<?php echo __( "If you change your landing page template without saving the current revision, you won't be able to revert back to it later.", "thrive-cb" ) ?>
					</p>
					<input id="tve_landing_page_name" type="text" value=""
					       placeholder="<?php echo __( "Template Name", "thrive-cb" ) ?>"
					       class="tve_lightbox_input"><br><br>
					<a id="tve_landing_page_save"
					   class="tve_click tve_editor_button tve_editor_button_success"
					   href="javascript:void(0)"><?php echo __( "Save Landing Page", "thrive-cb" ) ?></a>
				</div>
			<?php endif ?>
			<div class="tve_header_box">
				<div class="tve_header_box_headline">
					<h6><?php echo __( "Filter by Tag", "thrive-cb" ) ?></h6>
				</div>
				<div class="tve_header_box_content tve_landing_page_filters">
					<?php foreach ( $tags as $value => $label ) : ?>
						<div class="tve_lightbox_input_holder">
							<input type="checkbox" class="tve_change tve_landing_page_tag"
							       name="<?php echo $value ?>"
							       id="<?php echo $value ?>"
							       value="<?php echo $value ?>"/><label
								for="<?php echo $value ?>"> <?php echo $label ?></label>
						</div>
					<?php endforeach ?>
					<div class="tve_lightbox_input_holder">
						<input type="checkbox" class="tve_change tve_landing_page_tag"
						       name="imported-template"
						       id="imported-template"
						       value="imported-template"/><label
							for="imported-template"> <?php echo __( 'Imported templates', 'thrive-cb' ); ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="tve_colm tve_tfo tve_df tve_lst">
			<div class="tve_grid tve_landing_pages" id="tve_landing_page_selector">
				<div class="tve_scT tve_green">
					<ul class="tve_clearfix">
						<li id="tve_default_templates" class="tve_tS tve_click"><span
								class="tve_scTC1"><?php echo __( "Default Landing Pages", "thrive-cb" ) ?></span>
						</li>
						<li id="tve_saved_landing_pages" class="tve_click"><span
								class="tve_scTC2"><?php echo __( "Custom Landing Pages", "thrive-cb" ) ?></span>
						</li>
						<li id="tve_cloud_templates" class="tve_click"
						    data-template="<?php echo $_POST['landing_page'] ?>"><span
								class="tve_scTC3"><?php echo __( "Thrive Template Cloud", "thrive-cb" ) ?></span>
						</li>
					</ul>
					<div class="tve_scTC tve_scTC1" style="display: block">

						<div class="tve_clear" style="height: 5px;"></div>
						<div class="tve_overflow_y" id="tve_default_landing_pages">
							<?php foreach ( $landing_pages as $code => $data ) : ?>
								<span
									class="<?php echo empty( $data['tags_classes'] ) ? '' : implode( ' ', $data['tags_classes'] ) ?> tve_grid_cell tve_landing_page_template tve_click<?php echo $_POST['landing_page'] == $code ? ' tve_cell_selected' : '' ?>"
									title="<?php __( "Choose", "thrive" ) ?> <?php echo $data['name'] ?>">
                                    <input type="hidden" class="lp_code"
                                           value="<?php echo $code ?>"/>
                                    <img src="<?php echo $data['thumbnail'] ?>" width="166"
                                         height="140"/>
                                    <span class="tve_cell_caption_holder"><span
		                                    class="tve_cell_caption"><?php echo $data['name'] ?></span></span>
                                    <span class="tve_cell_check tve_icm tve-ic-checkmark"></span>
                                </span>
							<?php endforeach ?>
						</div>
						<div class="tve_clear" style="height: 5px;"></div>
					</div>
					<div class="tve_scTC tve_scTC2" style="display: none;">
						<a href="javascript:void(0)" id="tve_landing_page_delete"
						   class="tve_click tve_editor_button tve_editor_button_cancel tve_right">
							<?php echo __( "Delete Template", "thrive-cb" ) ?>
						</a>
						<h6><?php echo __( "Choose from your saved Landing Pages", "thrive-cb" ) ?></h6>

						<div class="tve_lightbox_input_holder">
							<input type="checkbox" id="tve_landing_page_user_filter"
							       class="tve_change tve_lightbox_input" value="1"/>
							<label
								for="tve_landing_page_user_filter"><?php echo __( "Show only saved versions of the current template", "thrive-cb" ) ?></label>
						</div>
						<div class="tve_clear" style="height: 15px;"></div>
						<div class="tve_overflow_y" id="tve_user_landing_pages">
							<p>
								<?php echo __( "No saved Templates found.", "thrive-cb" ) ?>
							</p>
						</div>
					</div>
					<div class="tve_scTC tve_scTC3" style="display: none;">
						<div class="tve_overflow_y" id="tve_cloud_template_list">
							<p>
								<?php echo __( "Fetching the list of templates ...", "thrive-cb" ) ?>
							</p>
						</div>
					</div>
				</div>
				<div class="tve_clear" style="height: 15px;"></div>
				<div class="tve_landing_pages_actions">
					<div id="tve_landing_page_select"
					     class="tve_click tve_editor_button tve_editor_button_success tve_right">
						<?php echo __( "Load Landing Page", "thrive-cb" ) ?>
					</div>
					<?php if ( ! empty( $_POST['landing_page'] ) ) : ?>
						<div id="tve_landing_page_disable"
						     class="tve_click tve_editor_button tve_editor_button_default tve_right">
							<?php echo __( "Revert to theme template", "thrive-cb" ) ?>
						</div>
					<?php endif ?>
				</div>
				<div class="tve_clear"></div>
			</div>
		</div>
	</div>
</div>
<script data-cfasync="false" type="text/javascript">
	jQuery( function () {
		<?php if (! empty( $_POST['landing_page'] ) && tve_is_cloud_template( $_POST['landing_page'] )) : ?>
		jQuery( 'li#tve_cloud_templates' ).click();
		<?php endif ?>
		setTimeout( function () {
			jQuery( '#tve_landing_page_filter' ).focus();
		}, 200 );
	} );
</script>