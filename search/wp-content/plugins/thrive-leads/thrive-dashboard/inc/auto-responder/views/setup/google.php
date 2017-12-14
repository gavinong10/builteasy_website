<h2 class="tvd-card-title">
	<?php echo $this->getTitle() ?>
	<a href="//fast.wistia.net/embed/iframe/mf6yx17kj5?popover=true" class="wistia-popover[height=450,playerColor=2bb914,width=800]"><span class="tvd-icon-play"></span></a>
</h2>
<div class="tvd-row">
	<form class="tvd-col tvd-s12">
		<input type="hidden" name="api" value="<?php echo $this->getKey() ?>"/>
		<div class="tvd-input-field">
			<input id="tvd-rc-api-client-id" type="text" name="client_id"
			       value="<?php echo $this->param( 'client_id' ) ?>">
			<label for="tvd-rc-api-client-id"><?php echo __( 'Client ID', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
		</div>
		<div class="tvd-input-field">
			<input id="tvd-ac-api-client-secret" type="text" name="client_secret"
			       value="<?php echo $this->param( 'client_secret' ) ?>">
			<label for="tvd-ac-api-client-secret"><?php echo __( 'Client Secret', TVE_DASH_TRANSLATE_DOMAIN ) ?></label>
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
