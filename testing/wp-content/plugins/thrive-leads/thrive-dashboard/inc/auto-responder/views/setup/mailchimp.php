<?php $admin_email = get_option( 'admin_email' ); ?>
<h2 class="tvd-card-title"><?php echo $this->getTitle() ?></h2>
<div class="tvd-row">
	<p><strong><?php echo __( 'Notification:', TVE_DASH_TRANSLATE_DOMAIN ) ?> </strong><?php echo __( 'If you would like to use Transactional emails. You should fill in the Mandrill API Key and Mandrill-approved email address optional fields.', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo $this->getKey() ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-mm-api-key" type="text" name="connection[key]"
			       value="<?php echo $this->param( 'key' ) ?>">
			<label for="tvd-mm-api-key"><?php echo __( "API key", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<h4><strong><?php echo __( 'Mandrill:', TVE_DASH_TRANSLATE_DOMAIN ) ?> </strong></h4>
		<div class="tvd-input-field">
			<input id="tvd-pm-api-email" type="text" name="connection[email]"
			       value="<?php echo $this->param( 'email', $admin_email ) ?>">
			<label for="tvd-pm-api-email">
				<?php echo __( "Mandrill-approved email address", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-mandrill-api-key" type="text" name="connection[mandrill-key]"
			       value="<?php echo $this->param( 'mandrill-key' ) ?>">
			<label for="tvd-mandrill-api-key"><?php echo __( "Mandrill API key", TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
	</form>
</div>
<div class="tvd-row">
	<div class="tvd-col tvd-s12">
		<p class="tvd-form-description tvd-note-text">
			<?php echo __( 'Note: sending from Mandrill only works if the email you enter has been verified in Mandrill.', TVE_DASH_TRANSLATE_DOMAIN ) ?>
			<a href="https://mandrillapp.com/settings/sending-domains" target="_blank"><?php echo __( 'Learn more', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>.
		</p>
	</div>
</div>
<div class="tvd-card-action">
	<div class="tvd-row tvd-no-margin">
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo __( "Cancel", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-connect tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"><?php echo __( "Connect", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
	</div>
</div>

