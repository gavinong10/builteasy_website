<div class="iconmanager-messages">
	<?php if ( ! empty( $this->messages['error'] ) ) : ?>
		<div class="error">
			<p><?php echo $this->messages['error'] ?></p>
		</div>
	<?php endif ?>
	<?php if ( ! empty( $this->messages['success'] ) ) : ?>
		<div class="updated">
			<p><?php echo $this->messages['success'] ?></p>
			<?php if ( ! empty( $this->messages['redirect'] ) ) : ?>
				<input type="hidden" id="tve-redirect-to" value="<?php echo $this->messages['redirect'] ?>">
			<?php endif ?>
		</div>
	<?php endif ?>
</div>