<table class="aviators-options">
	<tr>
		<th>
			<label><?php print __( 'Address', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'address' ); ?>
			<textarea type="text" name="<?php $mb->the_name(); ?>" cols="80" rows="8"><?php $mb->the_value(); ?></textarea>
		</td>
	</tr>

	<tr>
		<th>
			<label><?php print __( 'Phone', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'phone' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php print __( 'E-mail', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'email' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php print __( 'URL', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'url' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>
</table>