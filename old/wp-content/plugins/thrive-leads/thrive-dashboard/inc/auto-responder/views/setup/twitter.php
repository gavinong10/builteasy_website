<h2 class="tvd-card-title">
	<?php echo $this->getTitle() ?>
	<a href="//fast.wistia.net/embed/iframe/5vukjsb2eh?popover=true" class="wistia-popover[height=450,playerColor=2bb914,width=800]"><span class="tvd-icon-play"></span></a>
</h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo $this->getKey() ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-rc-api-access-token" type="text" name="access_token"
			       value="<?php echo $this->param( 'access_token' ) ?>">
			<label for="tvd-rc-api-access-token"><?php echo __( 'Access Token', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-rc-api-token-secret" type="text" name="token_secret"
			       value="<?php echo $this->param( 'token_secret' ) ?>">
			<label for="tvd-rc-api-token-secret"><?php echo __( 'Access Token Secret', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-rc-api-api-key" type="text" name="api_key"
			       value="<?php echo $this->param( 'api_key' ) ?>">
			<label for="tvd-rc-api-api-key"><?php echo __( 'Api Key', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-ac-api-api-secret" type="text" name="api_secret"
			       value="<?php echo $this->param( 'api_secret' ) ?>">
			<label for="tvd-ac-api-api-secret"><?php echo __( 'Api Secret', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
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

