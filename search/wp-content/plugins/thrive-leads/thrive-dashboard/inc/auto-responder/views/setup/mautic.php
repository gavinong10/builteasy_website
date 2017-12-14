<h2 class="tvd-card-title"><?php echo $this->getTitle() ?></h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo $this->getKey() ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-ac-api-url" type="text" name="connection[baseUrl]"
			       value="<?php echo $this->param( 'baseUrl' ) ?>">
			<label for="tvd-ac-api-url"><?php echo __( 'API URL', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-ac-api-key" type="text" name="connection[clientKey]"
			       value="<?php echo $this->param( 'clientKey' ) ?>">
			<label for="tvd-ac-api-key"><?php echo __( 'Public key', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-ac-api-secret-key" type="text" name="connection[clientSecret]"
			       value="<?php echo $this->param( 'clientSecret' ) ?>">
			<label for="tvd-ac-api-secret-key"><?php echo __( 'Secret key', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<select id="tvd-aw-api-country" type="text" name="connection[version]">
				<option value="OAuth2" <?php selected( $this->param( 'version' ), 'OAuth2' ); ?> >OAuth2
				</option>
				<option value="OAuth1a" <?php selected( $this->param( 'version' ), 'OAuth1a' ); ?> >OAuth
				</option>

			</select>
			<label for="tvd-aw-api-country"><?php echo __( 'Authentication Type', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<p><?php echo __( 'When using OAuth2 as the Authentication Type you need to reauthorize the connection after a certain amount of time (14 days by default) for more information please read the following', TVE_DASH_TRANSLATE_DOMAIN ) ?> <a href="https://thrivethemes.com/tkb_item/how-to-correctly-use-the-oauth2-authentication-type-in-our-mautic-integration" target="_blank"><strong><?php echo __( 'Knowledge Base Article', TVE_DASH_TRANSLATE_DOMAIN ) ?></strong></a></p>
	</form>
</div>
<div class="tvd-card-action">
	<div class="tvd-row tvd-no-margin">
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo __( 'Cancel', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6">
			<a class="tvd-api-redirect tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"><?php echo __( 'Connect', TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
		</div>
	</div>
</div>
