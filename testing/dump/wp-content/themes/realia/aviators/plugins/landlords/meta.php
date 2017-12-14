<table class="aviators-options">
	<tr>
		<th>
			<label><?php print __( 'First Name', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'first_name' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php print __( 'Last Name', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'last_name' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php print __( 'Address', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'address' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php print __( 'City', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'city' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php print __( 'ZIP', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'zip' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

	<tr>
		<th>
			<label><?php print __( 'Country', 'aviators' ); ?></label>
		</th>
		<td>
			<?php $mb->the_field( 'country' ); ?>
			<input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" />
		</td>
	</tr>

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

	<tr>
		<th>
			<label><?php print __( 'Owned Properties', 'aviators' ); ?></label>
		</th>
		<td>
			<?php global $post; ?>
			<?php $properties = aviators_landlords_get_property_list_by_id( $post->ID ); ?>
			<?php if ( is_array( $properties ) && count( $properties ) > 0 ): ?>
				<ul>
					<?php foreach ( $properties as $property ): ?>
						<?php if ( ! empty( $property->post_title ) ): ?>
							<li>
								<a href="<?php print get_edit_post_link( $property->ID ); ?>"><?php print $property->post_title; ?></a> |
								<a href="<?php print get_permalink( $property->ID ); ?>"><?php print __( 'View on front end', 'aviators' ); ?></a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<?php print __( 'Nothing', 'aviators' ); ?>
			<?php endif; ?>
		</td>
	</tr>
</table>