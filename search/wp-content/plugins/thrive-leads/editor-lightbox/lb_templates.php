<?php
/**
 * show a list of available templates to use for this Form Type Design
 *
 * post_id and variation key will come from $_POST
 *
 */
$post_id       = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
$variation_key = isset( $_POST['_key'] ) ? intval( $_POST['_key'] ) : 0;
if ( empty( $post_id ) || empty( $variation_key ) ) {
	exit( __( 'Invalid data', 'thrive-leads' ) );
}
$variation = tve_leads_get_form_variation( $post_id, $variation_key );
if ( empty( $variation ) ) {
	exit( __( 'Invalid data', 'thrive-leads' ) );
}
$form_type         = tve_leads_get_form_type_from_variation( $variation );
$current_template  = ! empty( $variation[ TVE_LEADS_FIELD_TEMPLATE ] ) ? $variation[ TVE_LEADS_FIELD_TEMPLATE ] : '';
$selected_template = ! empty( $variation[ TVE_LEADS_FIELD_SELECTED_TEMPLATE ] ) ? $variation[ TVE_LEADS_FIELD_SELECTED_TEMPLATE ] : '';
$templates         = Thrive_Leads_Template_Manager::for_form_type( $form_type, false );
$parent_form_type  = tve_leads_get_form_type_from_variation( $variation, true );
$multi_step        = Thrive_Leads_Template_Manager::for_multi_step( $parent_form_type );

$use_only_screenfiller_templates = false;
//for two step lightbox we include the screen filler templates also
$post_type = tve_leads_get_form_type( $post_id, array( 'get_variations' => false ) );
if ( $post_type->post_type == TVE_LEADS_POST_TWO_STEP_LIGHTBOX ) {
	//get screen filler templates
	$screen_filler = Thrive_Leads_Template_Manager::for_form_type( 'screen_filler', false );
	//we will add the screen filler templates only in two cases
	if ( $variation['parent_id'] ) {
		$parent_form = tve_leads_get_form_variation( $post_id, $variation['parent_id'] );
		//when we select a secondary state that has a parent that is still a screen filler
		if ( strpos( $parent_form['tpl'], 'screen_filler' ) !== false ) {
			$templates                       = $screen_filler;
			$use_only_screenfiller_templates = true;
		} elseif ( strpos( $parent_form['tpl'], 'lightbox' ) !== false ) {
			//do nothing, leave only the lightbox templates
			//we don't want to switch states from a lightbox to a screen filler or vice versa
		}
	} else {
		//we add the screen filler templates when we select the default state
		$templates = array_merge( $templates, $screen_filler );
	}
}

$form_type_name = tve_leads_get_form_type_name( $variation['post_parent'] );
/* this is a special case where the user is editing a "lightbox" state for an inline form (shortcode, widget etc) */
if ( ! empty( $variation['parent_id'] ) && $variation['form_state'] == 'lightbox' ) {
	$form_type_name = __( 'Lightbox', 'thrive-leads' );
}
?>
<div class="tve_large_lightbox">
	<h4 class="tve-with-filter">
		<?php echo sprintf( __( 'Choose the %s template you would like to use for this form', 'thrive-leads' ), $form_type_name ) ?>
		<span class="tve-quick-filter tve_lb_fields">
			<input class="tve_keyup tve_lightbox_input" data-ctrl="controls.filter_lp"
			       type="text" style="width: 170px"
			       placeholder="<?php echo __( 'Quick filter...', 'thrive-leads' ) ?>"
			       value=""
			       id="tve_landing_page_filter">
		</span>
	</h4>

	<div class="tve_tl_tpl <?php if ( $current_template )
		echo 'thrv_columns ' ?>tve_clearfix" id="tve-leads-tpl">
		<?php if ( $current_template ) : /* display the "Save" button just if there is some content in the form */ ?>
			<div class="tve_colm tve_foc tve_df tve_ofo">
				<div class="tve_message tve_warning" id="tve_landing_page_msg">
					<h6><?php echo __( 'Warning - your changes will be lost', 'thrive-leads' ) ?></h6>

					<p>
						<?php echo __( "If you change your the template without saving the current revision, you won't be able to revert back to it later.", 'thrive-leads' ) ?>
					</p>

					<input id="tve_landing_page_name" class="tve_lightbox_input" type="text"
					       value=""
					       placeholder="<?php echo __( 'Template Name', 'thrive-leads' ) ?>">
					<br><br>
					<a data-ctrl="function:ext.tve_leads.template.save"
					   class="tve_click tve_editor_button tve_editor_button_success"
					   href="javascript:void(0)"><?php echo __( 'Save As Template', 'thrive-leads' ) ?></a>
				</div>
			</div>
		<?php endif ?>
		<div
			class="<?php if ( $current_template ) : ?>tve_colm tve_tfo tve_df tve_lst<?php endif ?>">
			<div class="tve_grid tve_landing_pages" id="tve_landing_page_selector">
				<div class="tve_clear" style="height: 10px;"></div>
				<div class="tve_scT tve_green">
					<ul class="tve_clearfix">
						<li class="tve_tS tve_click tve_mousedown" data-ctrl-mousedown="function:ext.tve_leads.template.user_tab_clicked">
							<span class="tve_scTC1">
								<?php echo __( 'Opt In Templates', 'thrive-leads' ) ?>
							</span>
						</li>


						<li id="tve_leads_saved_templates"
						    class="tve_click tve_mousedown"
						    data-ctrl-mousedown="function:ext.tve_leads.template.user_tab_clicked">
							<span class="tve_scTC2">
								<?php echo __( 'Saved Templates', 'thrive-leads' ) ?>
							</span>
						</li>

						<?php if ( ! empty( $multi_step ) && $use_only_screenfiller_templates === false ) : ?>
							<li id="tve_multi_step_templates" class="tve_click tve_mousedown"
							    data-ctrl-mousedown="function:ext.tve_leads.template.user_tab_clicked">
								<span class="tve_scTC3">
									<?php echo __( 'Multi-step Templates', 'thrive-leads' ) ?>
								</span>
							</li>
						<?php endif ?>

						<li id="tve_cloud_templates" class="tve_click tve_mousedown"
						    data-variation-child="<?php echo (int) $variation['parent_id'] != 0 ?>"
						    data-template="<?php echo $selected_template ?>"
						    data-form-type="<?php echo $post_type->post_type === TVE_LEADS_POST_TWO_STEP_LIGHTBOX && $form_type === 'lightbox' ? 'two_step_lightbox' : $form_type ?>"
						    data-ctrl-mousedown="function:ext.tve_leads.template.user_tab_clicked">
							<span class="tve_scTC4"><?php echo __( 'Thrive Template Cloud' ) ?></span>
						</li>
					</ul>
					<div class="tve_scTC tve_scTC1" style="display: block">
						<div class="tve_clear" style="height: 5px;"></div>
						<div class="tve_overflow_y " style="">
							<?php foreach ( $templates as $data ) : ?>
								<span
									class="tve-tpl-<?php echo $form_type ?> tve_grid_cell tve_landing_page_template tve_click<?php echo $current_template == $data['key'] ? ' tve_cell_selected' : '' ?>">
                                <input type="hidden" class="lp_code"
                                       value="<?php echo $data['key'] ?>"/>
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
						<a href="javascript:void(0)"
						   data-ctrl="function:ext.tve_leads.template.delete_saved"
						   class="tve_click tve_editor_button tve_editor_button_cancel tve_right"><?php echo __( 'Delete template', 'thrive-leads' ) ?></a>
						<h6><?php echo __( 'Choose from your saved templates', 'thrive-leads' ) ?></h6>

						<?php if ( $current_template ) : ?>
							<div class="tve_lightbox_input_holder">
								<input type="checkbox" id="tl-user-current-templates"
								       data-ctrl="function:ext.tve_leads.template.get_saved"
								       class="tve_change"
								       value="1"/>
								<label for="tl-user-current-templates">
									<?php echo __( 'Show only saved versions of the current template', 'thrive-leads' ) ?>
								</label>
							</div>
						<?php endif ?>

						<div class="tve_clear" style="height: 15px;"></div>
						<div class="tve_overflow_y" style="max-height: 380px" id="tl-saved-templates"></div>
					</div>
					<!-- remove screen filler condition or filter $multi_step when we'll have templates for them -->
					<?php if ( ! empty( $multi_step ) && $use_only_screenfiller_templates === false ) : ?>
						<div class="tve_scTC tve_scTC3" style="display: none">
							<div class="tve_clear" style="height: 5px;"></div>
							<div class="tve_overflow_y " style="">
								<?php foreach ( $multi_step as $data ) : ?>
									<span
										class="tve-tpl-<?php echo $form_type ?> tve_grid_cell tve_landing_page_template tve_click">
                                    <input type="hidden" class="lp_code"
                                           value="<?php echo $data['key'] ?>"/>
                                    <input type="hidden" class="multi_step" value="1"/>
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
					<?php endif ?>
					<div class="tve_scTC tve_scTC4" style="display: none">
						<div class="tve_clear" style="height: 5px;"></div>
						<div id="tve_cloud_template_list" class="tve_overflow_y"></div>
					</div>
				</div>
				<div class="tve_clear" style="height: 15px;"></div>
				<div class="tve_landing_pages_actions">
					<div id="tve-leads-choose-template"
					     class="tve_editor_button tve_editor_button_success tve_right tve_click"
					     data-ctrl="function:ext.tve_leads.template.choose">
						<div
							class="tve_update"><?php echo __( 'Choose template', 'thrive-leads' ) ?></div>
					</div>
					<?php if ( ! empty( $current_template ) ) : ?>
						<div style="margin-right: 20px;" id="tve-leads-reset-template"
						     class="tve_editor_button tve_editor_button_default tve_right tve_click"
						     data-ctrl="function:ext.tve_leads.template.reset">
							<div
								class="tve_preview"><?php echo __( 'Reset contents', 'thrive-leads' ) ?></div>
						</div>
					<?php endif ?>
				</div>
				<div class="tve_clear"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	(function ( $ ) {

		<?php if(tve_leads_is_cloud_template( $form_type, $current_template )) : ?>
		$( '#tve_cloud_templates' ).trigger( 'click' );
		<?php endif; ?>

		setTimeout( function () {
			$( '#tve_landing_page_filter' ).focus();
		}, 200 );

	})( jQuery );
</script>