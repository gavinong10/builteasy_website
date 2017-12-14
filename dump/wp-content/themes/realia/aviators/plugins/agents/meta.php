<table class="aviators-options">
	<?php if ( function_exists( 'aviators_agencies_create_post_type' ) ): ?>
		<tr>
			<th>
				<label><?php print __( 'Agency', 'aviators' ); ?></label>
			</th>
			<td>
				<?php $mb->the_field( 'agency' ); ?>

				<select name="<?php $mb->the_name(); ?>">
					<option value="">---</option>
					<?php foreach ( aviators_agencies_get() as $agency ): ?>
						<option value="<?php echo $agency->ID; ?>" <?php $mb->the_select_state( $agency->ID ); ?>><?php echo $agency->post_title ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	<?php endif; ?>

	<tr>
		<th>
			<label><?php print __( 'Mobile', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'mobile' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
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
</table>