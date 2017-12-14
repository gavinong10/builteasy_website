<?php
require_once vc_path_dir( 'EDITORS_DIR', 'popups/class-vc-add-element-box.php' );
$add_element_box = new Vc_Add_Element_Box();
$add_element_box->render();

// Edit form for mapped shortcode.
visual_composer()->editForm()->render();

// Rendering Templates Modal Editor
// visual_composer()->templatesPanelEditor()->render();
visual_composer()->templatesPanelEditor()->renderUITemplate();

// Post settings
require_once vc_path_dir( 'EDITORS_DIR', 'popups/class-vc-post-settings.php' );
$post_settings = new Vc_Post_Settings( $editor );
// $post_settings->render();
$post_settings->renderUITemplate();
require_once vc_path_dir( 'EDITORS_DIR', 'popups/class-vc-edit-layout.php' );
$edit_layout = new Vc_Edit_Layout();
$edit_layout->renderUITemplate();
global $current_user;
get_currentuserinfo();

require_once vc_path_dir( 'AUTOLOAD_DIR', 'class-vc-settings-presets.php' );
?>
<script type="text/javascript">
	var vc_user_mapper = <?php echo json_encode(WPBMap::getUserShortCodes()) ?>,
		vc_mapper = <?php echo json_encode(WPBMap::getShortCodes()) ?>,
		vc_settings_presets = <?php echo json_encode(Vc_Settings_Preset::listDefaultSettingsPresets()) ?>,
		vc_roles = <?php echo json_encode( array_merge( array( 'current_user' => $current_user->roles ), (array) vc_settings()->get( 'groups_access_rules' ) ) ); ?>,
		vc_frontend_enabled = <?php echo vc_enabled_frontend() ? 'true' : 'false' ?>,
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
<?php foreach ( WPBMap::getShortCodes() as $sc_base => $el ): ?>
	<script type="text/html" id="vc_shortcode-template-<?php echo $sc_base ?>">
		<?php
		echo visual_composer()->getShortCode( $sc_base )->template();
		?>
	</script>
<?php endforeach; ?>
<script type="text/html" id="vc_row-inner-element-template">
	<?php
	echo visual_composer()->getShortCode( 'vc_row_inner' )->template();
	?>
</script>
<script type="text/html" id="vc_settings-page-param-block">
	<div class="row-fluid wpb_el_type_<%= type %>">
		<div class="wpb_element_label"><%= heading %></div>
		<div class="edit_form_line">
			<%= form_element %>
		</div>
	</div>
</script>