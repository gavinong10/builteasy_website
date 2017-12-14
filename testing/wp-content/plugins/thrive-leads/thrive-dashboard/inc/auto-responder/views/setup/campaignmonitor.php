<?php $admin_email = get_option( 'admin_email' ); ?>
<h2 class="tvd-card-title"><?php echo $this->getTitle() ?></h2>
<div class="tvd-row">

	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo $this->getKey() ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-mm-api-key" type="text" name="connection[key]"
			       value="<?php echo $this->param( 'key' ) ?>">
			<label for="tvd-mm-api-key"><?php echo __( 'API key', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<p><?php echo __( 'Would you also like to connect to the Transactional Email Service ?', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
		<br/>
		<div class="tvd-col tvd-s12 tvd-m4 tvd-no-padding">
			<p>
				<input class="tvd-new-connection-yes" name="connection[new_connection]" type="radio" value="1"
				       id="tvd-new-connection-yes" <?php echo $this->param( 'new_connection' ) == 1 ? 'checked="checked"' : ''; ?> />
				<label for="tvd-new-connection-yes"><?php echo __( 'Yes', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
			</p>
		</div>
		<div class="tvd-col tvd-s12 tvd-m4 tvd-no-padding">
			<p>
				<?php $connection = $this->param( 'new_connection' ); ?>
				<input class="tvd-new-connection-no" name="connection[new_connection]" type="radio" value="0"
				       id="tvd-new-connection-no" <?php echo empty( $connection ) || $connection == 0 ? 'checked="checked"' : ''; ?> />
				<label for="tvd-new-connection-no"><?php echo __( 'No', TVE_DASH_TRANSLATE_DOMAIN ); ?></label>
			</p>
		</div>
		<br/>
		<br/>
		<div class="tvd-input-field">
			<input id="tvd-aw-api-email" type="text" name="connection[email]"
			       value="<?php echo $this->param( 'email', $admin_email ) ?>">
			<label for="tvd-aw-api-email" class="tvd-active"><?php echo __( 'Campaign Monitor-approved email address', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>

		<div class="tvd-col tvd-s12 tvd-m12 tvd-no-padding">
			<p>
				<strong><?php echo __( 'Note:', TVE_DASH_TRANSLATE_DOMAIN ) ?> </strong><?php echo __( 'Sending email through Campaign Monitor will only work if your email address has been verified and you have a paying account.', TVE_DASH_TRANSLATE_DOMAIN ) ?>
				<strong><a target="_blank"
				           href="https://help.campaignmonitor.com/topic.aspx?t=88#manage-your-own"><?php echo __( 'Learn More', TVE_DASH_TRANSLATE_DOMAIN ) ?></a></strong>.</p>
			<div class="tvd-col tvd-s12 tvd-m12">
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

