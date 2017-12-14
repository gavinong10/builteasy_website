<?php $hide_form = isset( $hide_form ) ? $hide_form : false; ?>

<div id="tve-leads-empty-template" <?php if ( ! $hide_form ): ?>style="display: none;"<?php endif; ?>>
	<h4><?php echo __( 'The entire form will be hidden to all visitors that have already subscribed to this offer.', 'thrive-leads' ) ?></h4>
	<h4><?php echo __( 'If you would prefer to add some content for already subscribed visitors then click the eye icon from the already subscribed box.', 'thrive-leads' ) ?></h4>
</div>