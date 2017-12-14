<h2 class="tvd-card-title"><?php echo $this->getTitle() ?></h2>
<div class="tvd-row">
	<div class="tvd-col tvd-s12">
		<p>
			<strong><?php echo __( 'Notification:', TVE_DASH_TRANSLATE_DOMAIN ) ?> </strong><?php echo __( 'Mail Relay limits the number of emails sent per minute to 6. All emails sent after this limit will be discarded', TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</p>
		<p>
			<strong><?php echo __( 'Note:', TVE_DASH_TRANSLATE_DOMAIN ) ?> </strong><?php echo __( 'Connecting to Mail Relay Email Service will also connect to Mail Relay autoresponders.', TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</p>
	</div>
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo $this->getKey() ?>"/>

		<div class="tvd-input-field">
			<input id="tvd-mm-api-key" type="text" name="connection[domain]"
			       value="<?php echo $this->param( 'domain' ) ?>">
			<label for="tvd-mm-api-key"><?php echo __( 'API URL', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-mm-api-key" type="text" name="connection[key]"
			       value="<?php echo $this->param( 'key' ) ?>">
			<label for="tvd-mm-api-key"><?php echo __( 'API key', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
	</form>
</div>
<div class="tvd-card-action">
	<div class="tvd-row tvd-no-margin">
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo __( 'Cancel', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-connect tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"><?php echo __( 'Connect', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
	</div>
</div>

