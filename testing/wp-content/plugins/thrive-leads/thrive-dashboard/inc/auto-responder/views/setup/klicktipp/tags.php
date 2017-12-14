<div class="tve-sp"></div>
<h6><?php echo __( 'Choose tag:', TVE_DASH_TRANSLATE_DOMAIN ) ?></h6>
<div class="tvd-row tvd-collapse">
	<div class="tvd-col tvd-s4">
		<div class="tve_lightbox_select_holder tve_lightbox_input_inline tve_lightbox_select_inline tvd-input-field">
			<select class="tve-api-extra tl-api-connection-list" name="klicktipp_tag">
				<option
					value="0"<?php echo isset( $data['tag'] ) && $data['tag'] === '0' ? ' selected="selected"' : '' ?>>
					<?php echo __( 'No tag', TVE_DASH_TRANSLATE_DOMAIN ) ?>
				</option>
				<?php foreach ( $data['tags'] as $id => $tag ) : ?>
					<option <?php echo isset( $data['tag'] ) && $data['tag'] == $id ? ' selected="selected"' : '' ?>
						value="<?php echo $id ?>"><?php echo $tag ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>
