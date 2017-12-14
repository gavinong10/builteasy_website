<table class="aviators-options">
	<tr>
		<th>
			<label><?php print __( 'URL', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'url' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>">
		</td>
	</tr>
</table>