<div class="vc_ui-font-open-sans vc_ui-panel-window vc_media-xs vc_ui-panel"
     data-vc-panel=".vc_ui-panel-header-header" data-vc-ui-element="panel-edit-element" id="vc_ui-panel-edit-element">
	<div class="vc_ui-panel-window-inner">
		<?php vc_include_template( 'editors/popups/vc_ui-header.tpl.php', array(
			'title' => __( 'Page settings', 'js_composer' ),
			'controls' => array(
				array( 'template' => 'editors/partials/vc_ui-presets-dropdown.tpl.php' ),
				'minimize',
				'close'
			),
			'header_css_class' => 'vc_ui-post-settings-header-container',
			'content_template' => ''
		) ); ?>

		<!-- param window footer-->
		<div class="vc_ui-panel-content-container">
			<div class="vc_ui-panel-content vc_properties-list vc_edit_form_elements">

				<!--/ temp content -->
			</div>
		</div>
		<!-- param window footer-->
		<?php vc_include_template( 'editors/popups/vc_ui-footer.tpl.php', array(
			'controls' => array(
				array(
					'name' => 'close',
					'label' => __( 'Close', 'js_composer' ),
				),
				array(
					'name' => 'save',
					'label' => __( 'Save changes', 'js_composer' ),
					'css_classes' => 'vc_ui-button-fw',
					'style' => 'action'
				)
			),
		) ); ?>
	</div>
</div>