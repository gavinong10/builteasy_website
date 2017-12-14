<?php if ( ! empty( $template_downloaded ) ) : ?>

	<input type="hidden" class="lp_code" value="<?php echo (bool) $tpl['multi_step'] ? 'multi_step|' : '' ?><?php echo $form_type . '|' . $template_downloaded ?>"/>
	<input type="hidden" class="multi_step" value="<?php echo (int) $tpl['multi_step'] ?>">

	<div class="tve_cell_actions" style="text-align:center;">
		<a href="javascript:void(0);"
		   class="tve_click tve_editor_button tve_editor_button_success tve_editor_button_small"
		   data-ctrl="function:ext.tve_leads.template.open"
		   title="<?php echo __( 'Open', 'thrive-leads' ) ?>">
			<?php echo __( 'Open', 'thrive-leads' ) ?>
		</a>
		<?php
		$href = '';
		if ( ! empty( $tpl['multi_step'] ) ) {
			$href .= 'multi_step|';
		}
		if ( ! empty( $tpl['form_type'] ) ) {
			$href .= $tpl['form_type'] . '|';
		}
		$href .= $template;
		?>
		<a href="http://landingpages.thrivethemes.com/thrive-leads/?tl_preview=<?php echo $href; ?>&form_type=<?php echo $tpl['form_type']; ?>"
		   class="tve_editor_button tve_editor_button_default tve_editor_button_small"
		   target="_blank">
			<?php echo __( 'Preview', 'thrive-leads' ) ?>
		</a>
	</div>

	<?php return; ?>

<?php endif; ?>


<?php if ( count( $templates ) ) : ?>
	<p class="tve_leads_one_line">
		<input type="checkbox" id="tve_filter_multi"/>
		<label
			for="tve_filter_multi"><?php echo __( 'View only multi step templates', 'thrive-leads' ) ?></label>
	</p>
<?php else: ?>
	<p><?php echo __( 'No template available', 'thrive-leads' ) ?></p>
<?php endif; ?>

<?php foreach ( $templates as $data ) : ?>
	<?php $multi_class = (int) $data['multi_step'] ? 'tve_cloud_multi' : '' ?>
	<div class="tve_tpl_item tve_tpl_cloud_item <?php echo $multi_class ?>">
		<span
			class="tve-tpl-<?php echo $data['form_type'] ?> tve_grid_cell tve_leads_template tve-templates-cloud tve_click<?php echo $selected === $data['key'] ? ' tve_cell_template_cloud_selected' : '' ?>"
			data-ctrl="function:ext.tve_leads.template.select">
			<img src="<?php echo $data['thumbnail'] ?>" width="166" height="140"/>
			<div>
				<?php if ( ! empty( $data['update_available'] ) ) : ?>
					<div class="tve_tpl_update">
						<a href="javascript:void(0)" class="tve_click tve-tpl-update"
						   data-ctrl="function:tve_cloud_template_download"
						   data-template="<?php echo $data['key'] ?>"
						   data-multi-step="<?php echo (int) $data['multi_step'] ?>"
						   data-form-type="<?php echo $data['form_type']; ?>"
						   title="<?php echo __( 'Update', 'thrive-leads' ) ?>"><?php echo __( 'Update available. Click to update!', 'thrive-leads' ) ?>
						</a>
					</div>
					<input type="hidden" class="lp_code"
					       value="<?php echo (bool) $data['multi_step'] ? 'multi_step|' : '' ?><?php echo $data['form_type'] . '|' . $data['key'] ?>"/>
					<input type="hidden" class="multi_step"
					       value="<?php echo (int) $data['multi_step'] ?>">
				<?php elseif ( ! empty( $data['downloaded'] ) ) : ?>
					<input type="hidden" class="lp_code"
					       value="<?php echo (bool) $data['multi_step'] ? 'multi_step|' : '' ?><?php echo $data['form_type'] . '|' . $data['key'] ?>"/>
					<input type="hidden" class="multi_step"
					       value="<?php echo (int) $data['multi_step'] ?>">

					<div class="tve_cell_actions" style="text-align:center;">
						<a href="javascript:void(0);"
						   class="tve_click tve_editor_button tve_editor_button_success tve_editor_button_small"
						   data-ctrl="function:ext.tve_leads.template.open"
						   title="<?php echo __( 'Open', 'thrive-leads' ) ?>">
							<?php echo __( 'Open', 'thrive-leads' ) ?>
						</a>
						<a href="<?php echo $data['preview'] ?>"
						   class="tve_editor_button tve_editor_button_default tve_editor_button_small"
						   target="_blank">
							<?php echo __( 'Preview', 'thrive-leads' ) ?>
						</a>
					</div>
				<?php else : ?>
					<div class="tve_cell_actions" style="text-align:center;">
						<a href="javascript:void(0)"
						   class="tve_click tve_editor_button tve_editor_button_success tve_editor_button_small"
						   data-ctrl="function:tve_cloud_template_download"
						   data-template="<?php echo $data['key'] ?>"
						   data-multi-step="<?php echo (int) $data['multi_step'] ?>"
						   data-form-type="<?php echo $data['form_type']; ?>"
						   title="<?php echo __( 'Download', 'thrive-leads' ) ?>"><?php echo __( 'Download', 'thrive-leads' ) ?></a>
						<span class="tve-grid-btn-sep">&nbsp;</span>
						<a href="<?php echo $data['preview'] ?>"
						   class="tve_editor_button tve_editor_button_default tve_editor_button_small"
						   target="_blank">
							<?php echo __( 'Preview', 'thrive-leads' ) ?>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<span class="tve_cell_caption_holder">
		        <span class="tve_cell_caption"><?php echo $data['name'] ?></span>
	        </span>

			<!--			<span class="tve_cell_check tve_icm tve-ic-checkmark"></span>-->
	    </span>
	</div>
<?php endforeach ?>

<script type="text/javascript">
	(function ( $ ) {
		$( '#tve_filter_multi' ).click( function () {
			var $this = $( this );

			$items = $( '.tve_tpl_cloud_item' );

			if ( $this.is( ':checked' ) ) {
				$filtered = $items.filter( function ( index, element ) {
					return ! $( this ).is( '.tve_cloud_multi' );
				} );
				$filtered.hide();
			} else {
				$items.show();
			}
		} );
	})( jQuery )
</script>