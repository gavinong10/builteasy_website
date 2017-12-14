<?php
require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/editor/popups/class-vc-add-element-box-grid-item.php' );
$add_element_box = new Vc_Add_Element_Box_Grid_Item();
$add_element_box->render();
// Edit form for mapped shortcode.
visual_composer()->editForm()->render();
require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/editor/popups/class-vc-templates-editor-grid-item.php' );
$templates_editor = new Vc_Templates_Editor_Grid_Item();
$templates_editor->renderUITemplate();

require_once vc_path_dir( 'EDITORS_DIR', 'popups/class-vc-edit-layout.php' );
$edit_layout = new Vc_Edit_Layout();
$edit_layout->render();
$grid_item = new Vc_Grid_Item();
$shortcodes = $grid_item->shortcodes();

require_once vc_path_dir( 'AUTOLOAD_DIR', 'class-vc-settings-presets.php' );
?>
	<script type="text/javascript">
		var vc_user_mapper = <?php echo json_encode(WpbMap_Grid_Item::getGitemUserShortCodes()) ?>,
			vc_mapper = <?php echo json_encode(WpbMap_Grid_Item::getShortCodes()) ?>,
			vc_settings_presets = <?php echo json_encode(Vc_Settings_Preset::listDefaultSettingsPresets()) ?>,
			vc_frontend_enabled = false,
			vc_mode = '<?php echo vc_mode() ?>',
			vcAdminNonce = '<?php echo vc_generate_nonce( 'vc-admin-nonce' ); ?>';
	</script>

	<script type="text/html" id="vc_settings-image-block">
		<li class="added">
			<div class="inner" style="width: 80px; height: 80px; overflow: hidden;text-align: center;">
				<img rel="<%= id %>" src="<%= url %>"/>
			</div>
			<a href="#" class="icon-remove"></a>
		</li>
	</script>
<?php foreach ( WpbMap_Grid_Item::getGitemUserShortCodes() as $sc_base => $el ): ?>
	<script type="text/html" id="vc_shortcode-template-<?php echo $sc_base ?>">
		<?php
		echo visual_composer()->getShortCode( $sc_base )->template();
		?>
	</script>
<?php endforeach; ?>