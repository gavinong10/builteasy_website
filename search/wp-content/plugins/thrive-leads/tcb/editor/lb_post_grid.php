<?php

/**
 * Wrapper for get_post_types. Just to apply some logic if needed
 * @return array
 */
function get_all_post_types() {
	$types = array();

	$banned_types = array(
		'revision',
		'nav_menu_item',
		'project',
		'et_pb_layout',
		'tcb_lightbox',
		'focus_area',
		'thrive_optin',
		'thrive_ad_group',
		'thrive_ad',
		'thrive_slideshow',
		'thrive_slide_item',
		'tve_lead_shortcode',
		'tve_lead_2s_lightbox',
		'tve_form_type',
		'tve_lead_group',
	);
	foreach ( get_post_types() as $type ) {
		if ( ! in_array( $type, $banned_types ) ) {
			$types[] = $type;
		}
	}

	return $types;
}

function display_layouts() {
	$layouts = array(
		'featured_image' => 'Featured image',
		'title'          => 'Title',
		'text'           => 'Text',
	);

	if ( isset( $_POST['layout'] ) && ! empty( $_POST['layout'] ) ) {
		foreach ( $_POST['layout'] as $id ) {
			?>
		<li data-layout="<?php echo $id ?>" class="ui-state-default"><span
				class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $layouts[ $id ] ?></li><?php
		}

		return;
	}

	foreach ( $layouts as $id => $label ) {
		?>
	<li data-layout="<?php echo $id ?>" class="ui-state-default"><span
			class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $label ?></li><?php
	}

}

$_POST = stripslashes_deep( $_POST );

?>
<div class="tve_post_grid_tabs_container">
	<input type="hidden" name="tve_lb_type" value="tve_post_grid">

	<div class="tve_scT tve_green">
		<ul class="tve_clearfix">
			<li class="tve_tS tve_click"><span class="tve_scTC1"><?php echo __( "Layout", "thrive-cb" ) ?></span></li>
			<li class="tve_click"><span class="tve_scTC2"><?php echo __( "Edit Query", "thrive-cb" ) ?></span></li>
			<li class="tve_click"><span class="tve_scTC3"><?php echo __( "Filter Settings", "thrive-cb" ) ?></span></li>
			<li class="tve_click"><span class="tve_scTC4"><?php echo __( "Display Settings", "thrive-cb" ) ?></span></li>
		</ul>

		<div class="tve_scTC tve_scTC1 tve_clearfix" style="display: block">
			<div class="tve_options_wrapper tve_clearfix">
				<div class="tve_option_container tve_clearfix">
					<label class="lblOption"><?php echo __( "Teaser Layout", "thrive-cb" ) ?>:</label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_input_holder">
							<input id="tve_pg_featured_image"
							       type="checkbox" <?php echo isset( $_POST['teaser_layout'] ) ? isset( $_POST['teaser_layout']['featured_image'] ) && $_POST['teaser_layout']['featured_image'] === 'true' ? 'checked="checked"' : '' : 'checked="checked"' ?>
							       name="teaser_layout[featured_image]"/>
							<label for="tve_pg_featured_image"><?php echo __( "Featured image", "thrive-cb" ) ?></label>
						</div>

						<div class="tve_lightbox_input_holder">
							<input id="tve_pg_title"
							       type="checkbox" <?php echo isset( $_POST['teaser_layout'] ) ? isset( $_POST['teaser_layout']['title'] ) && $_POST['teaser_layout']['title'] === 'true' ? 'checked="checked"' : '' : 'checked="checked"' ?>
							       name="teaser_layout[title]"/>
							<label for="tve_pg_title"><?php echo __( "Title", "thrive-cb" ) ?></label>
						</div>

						<div class="tve_lightbox_input_holder">
							<input id="tve_pg_text"
							       type="checkbox" <?php echo isset( $_POST['teaser_layout'] ) ? isset( $_POST['teaser_layout']['text'] ) && $_POST['teaser_layout']['text'] === 'true' ? 'checked="checked"' : '' : 'checked="checked"' ?>
							       name="teaser_layout[text]"/>
							<label for="tve_pg_text"><?php echo __( "Text", "thrive-cb" ) ?></label>
						</div>
						<div class="tve_lightbox_input_holder">
							<input id="tve_pg_read_more"
							       type="checkbox" <?php echo isset( $_POST['teaser_layout'] ) ? isset( $_POST['teaser_layout']['read_more'] ) && $_POST['teaser_layout']['read_more'] === 'true' ? 'checked="checked"' : '' : 'checked="checked"' ?>
							       name="teaser_layout[read_more]"/>
							<label for="tve_pg_read_more"><?php echo __( "Read more link", "thrive-cb" ) ?></label>
						</div>
					</div>
				</div>

				<div class="tve_option_container tve_clearfix">
					<label class="lblOption"><?php echo __( "Text type", "thrive-cb" ) ?>:</label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_select_holder">
							<select name="text_type">
								<option
									value="summary" <?php echo isset( $_POST['text_type'] ) && $_POST['text_type'] === 'summary' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Summary", "thrive-cb" ) ?>
								</option>
								<option
									value="excerpt" <?php echo isset( $_POST['text_type'] ) && $_POST['text_type'] === 'excerpt' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Excerpt", "thrive-cb" ) ?>
								</option>
								<option
									value="fulltext" <?php echo isset( $_POST['text_type'] ) && $_POST['text_type'] === 'fulltext' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Full text", "thrive-cb" ) ?>
								</option>
							</select>
						</div>
					</div>
				</div>

				<div class="tve_option_container tve_clearfix">
					<label class="lblOption"><?php echo __( "Layout", "thrive-cb" ) ?>:</label>

					<div class="tve_fields_container">
						<p><?php echo __( "Drag the items into the correct order for display:", 'thrive-cb' ) ?></p>

						<div class="tve-sp"></div>
						<ul class="tve_sortable_layout">
							<?php display_layouts() ?>
						</ul>
					</div>
				</div>

				<div class="tve_option_container tve_clearfix">
					<label class="lblOption"><?php echo __( "Grid layout:", "thrive-cb" ) ?></label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_select_holder">
							<select name="grid_layout">
								<option
									value="horizontal" <?php echo empty( $_POST['grid_layout'] ) || $_POST['grid_layout'] === 'horizontal' ? 'selected="selected"' : '' ?>><?php echo __( 'Horizontal', "thrive-cb" ) ?></option>
								<option
									value="vertical" <?php echo ! empty( $_POST['grid_layout'] ) && $_POST['grid_layout'] === 'vertical' ? 'selected="selected"' : '' ?>><?php echo __( 'Vertical', 'thrive-cb' ) ?></option>
							</select>
						</div>
						<p><?php echo __( "For vertical grids the images will always be displayed on the left part of posts.", 'thrive-cb' ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="tve_scTC tve_scTC2 tve_clearfix">

			<div class="tve_options_wrapper tve_clearfix">

				<div class="tve_option_container tve_clearfix">
					<label class="lblOption"><?php echo __( "Content to include", "thrive-cb" ) ?></label>

					<div class="tve_fields_container">
						<?php foreach ( get_all_post_types() as $type ) : ?>
							<div class="tve_lightbox_input_holder">
								<input id="post_types[<?php echo $type ?>]"
								       type="checkbox" <?php echo isset( $_POST['post_types'][ $type ] ) ? $_POST['post_types'][ $type ] === 'true' ? 'checked="checked"' : '' : $type === 'post' ? 'checked="checked"' : '' ?>
								       name="post_types[<?php echo $type ?>]"/>
								<label for="post_types[<?php echo $type ?>]"><?php echo ucfirst( $type ) ?></label>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="tve_option_container tve_clearfix">
					<label class="lblOption"><?php echo __( "Number of posts", "thrive-cb" ) ?></label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_select_holder">
							<select name="posts_per_page">
								<option value="0"><?php echo __( "All", "thrive-cb" ) ?></option>
								<?php for ( $i = 1; $i <= 20; $i ++ ) : ?>
									<option <?php echo isset( $_POST['posts_per_page'] ) ? $_POST['posts_per_page'] == $i ? 'selected="selected"' : '' :
										$i === 6 ? 'selected="selected"' : '' ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
						</div>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( "Start", "thrive-cb" ) ?></label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_select_holder">
							<select name="posts_start">
								<?php for ( $i = 0; $i <= 20; $i ++ ) : ?>
									<option <?php echo isset( $_POST['posts_start'] ) ? $_POST['posts_start'] == $i ? 'selected="selected"' : '' :
										$i === 0 ? 'selected="selected"' : '' ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
						</div>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( "Order by", "thrive-cb" ) ?></label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_select_holder">
							<select name="orderby">
								<option
									value="date" <?php echo isset( $_POST['orderby'] ) && $_POST['orderby'] === 'date' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Date", "thrive-cb" ) ?>
								</option>
								<option
									value="title" <?php echo isset( $_POST['orderby'] ) && $_POST['orderby'] === 'title' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Title", "thrive-cb" ) ?>
								</option>
								<option
									value="author" <?php echo isset( $_POST['orderby'] ) && $_POST['orderby'] === 'author' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Author", "thrive-cb" ) ?>
								</option>
								<option
									value="comment_count" <?php echo isset( $_POST['orderby'] ) && $_POST['orderby'] === 'comment_count' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Number of Comments", "thrive-cb" ) ?>
								</option>
								<option
									value="rand" <?php echo isset( $_POST['orderby'] ) && $_POST['orderby'] === 'rand' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Random", "thrive-cb" ) ?>
								</option>
							</select>
						</div>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( "Order", "thrive-cb" ) ?></label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_select_holder">
							<select name="order">
								<option
									value="DESC" <?php echo isset( $_POST['order'] ) && $_POST['order'] === 'DESC' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Descending", "thrive-cb" ) ?>
								</option>
								<option
									value="ASC" <?php echo isset( $_POST['order'] ) && $_POST['order'] === 'ASC' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Ascending", "thrive-cb" ) ?>
								</option>
							</select>
						</div>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( 'Show Items More Recent Than', 'thrive-cb' ) ?></label>

					<div class="tve_fields_container">
						<input maxlength="3"
						       value="<?php echo ! empty( $_POST['recent_days'] ) ? intval( $_POST['recent_days'] ) : 0; ?>"
						       name="recent_days" type="text"
						       class="tve_lightbox_input tve_lightbox_input_inline"/> <?php echo __( 'Days', "thrive-cb" ) ?>
					</div>
				</div>
			</div>
		</div>

		<div class="tve_scTC tve_scTC3 tve_clearfix">
			<div class="tve_options_wrapper tve_clearfix">
				<div class="tve_option_container tve_clearfix">
					<h4><?php echo __( "Choose which content to include", "thrive-cb" ) ?></h4>
					<label class="lblOption"><?php echo __( "Categories", "thrive-cb" ) ?></label>

					<div class="tve_fields_container ui-front">
						<input name="filters[category]" value="<?php echo @$_POST['filters']['category'] ?>" type="text"
						       class="tve_post_grid_autocomplete tve_lightbox_input" data-action="tve_categories_list"/>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( "Tags", "thrive-cb" ) ?></label>

					<div class="tve_fields_container ui-front">
						<input name="filters[tag]" value="<?php echo @$_POST['filters']['tag'] ?>" type="text"
						       class="tve_post_grid_autocomplete tve_lightbox_input" data-action="tve_tags_list"/>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( "Custom Taxonomies", "thrive-cb" ) ?></label>

					<div class="tve_fields_container ui-front">
						<input name="filters[tax]" value="<?php echo @$_POST['filters']['tax'] ?>" type="text"
						       class="tve_post_grid_autocomplete tve_lightbox_input"
						       data-action="tve_custom_taxonomies_list"/>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( "Author", "thrive-cb" ) ?></label>

					<div class="tve_fields_container ui-front">
						<input name="filters[author]" value="<?php echo @$_POST['filters']['author'] ?>" type="text"
						       class="tve_post_grid_autocomplete tve_lightbox_input" data-action="tve_authors_list"/>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( "Individual Posts/Pages", "thrive-cb" ) ?></label>

					<div class="tve_fields_container ui-front">
						<input name="filters[posts]" value="<?php echo @$_POST['filters']['posts'] ?>" type="text"
						       class="tve_post_grid_autocomplete tve_lightbox_input" data-action="tve_posts_list"/>
					</div>
				</div>
			</div>
		</div>

		<div class="tve_scTC tve_scTC4 tve_clearfix">
			<div class="tve_options_wrapper tve_clearfix">
				<div class="tve_option_container tve_clearfix">
					<label class="lblOption"><?php echo __( "Number of Columns", "thrive-cb" ) ?></label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_select_holder">
							<select name="columns">
								<?php for ( $i = 1; $i <= 6; $i ++ ) : ?>
									<option <?php echo isset( $_POST['columns'] ) ? $_POST['columns'] == $i ? 'selected="selected"' : '' :
										$i === 3 ? 'selected="selected"' : '' ?> ><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
						</div>
					</div>
					<div class="tve-sp"></div>
					<label class="lblOption"><?php echo __( "Order", "thrive-cb" ) ?></label>

					<div class="tve_fields_container">
						<div class="tve_lightbox_select_holder">
							<select name="display">
								<option
									value="grid" <?php echo isset( $_POST['display'] ) && $_POST['display'] === 'grid' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Grid", "thrive-cb" ) ?>
								</option>
								<option
									value="masonry" <?php echo isset( $_POST['display'] ) && $_POST['display'] === 'masonry' ? 'selected="selected"' : '' ?>>
									<?php echo __( "Masonry", "thrive-cb" ) ?>
								</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="tve-sp"></div>