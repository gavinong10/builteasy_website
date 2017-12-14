<div class="font-import-manager-messages">
	<?php if ( isset( $this->messages['error'] ) && count( $this->messages['error'] ) ) : ?>
		<?php foreach ( $this->messages['error'] as $error ) : ?>
			<div class="error">
				<p><?php echo $error ?></p>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if ( isset( $this->messages['success'] ) && count( $this->messages['success'] ) ) : ?>
		<?php foreach ( $this->messages['success'] as $success ) : ?>
			<div class="updated">
				<p><?php echo $success ?></p>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
