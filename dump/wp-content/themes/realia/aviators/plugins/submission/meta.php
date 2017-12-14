<table class="aviators-options">
	<tr>
		<th>
			<label><?php echo __( 'User ID', 'aviators' ); ?></label>
		</th>

		<td>
			<?php $mb->the_field( 'user_id' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php echo __( 'Status', 'aviators' ); ?></label>
		</th>

		<td>
			<?php $mb->the_field( 'status' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php echo __( 'Cost', 'aviators' ); ?></label>
		</th>

		<td>
			<?php $mb->the_field( 'cost' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php echo __( 'Post ID', 'aviators' ); ?></label>
		</th>

		<td>
			<?php $mb->the_field( 'post_id' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php echo __( 'Payer ID', 'aviators' ); ?></label>
		</th>

		<td>
			<?php $mb->the_field( 'payer_id' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php echo __( 'Token', 'aviators' ); ?></label>
		</th>

		<td>
			<?php $mb->the_field( 'token' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>
</table>