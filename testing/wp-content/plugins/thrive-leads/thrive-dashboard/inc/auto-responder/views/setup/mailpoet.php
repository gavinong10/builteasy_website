<h2 class="tvd-card-title"><?php echo $this->getTitle() ?></h2>
<?php
/** @var $this Thrive_Dash_List_Connection_MailPoet */
?>
<?php if ( $this->pluginInstalled() ) : ?>
	<div class="tvd-row">
		<p><?php echo __( "Click the button below to enable MailPoet integration.", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	</div>
	<div class="tvd-card-action">
		<div class="tvd-row tvd-no-margin">
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo __( "Cancel", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
			<form class="tvd-hide">
				<input type="hidden" name="api" value="<?php echo $this->getKey() ?>">
			</form>
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn tvd-api-connect"
				   href="javascript:void(0)"><?php echo __( "Connect", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
		</div>
	</div>
<?php else : ?>
	<p><?php echo __( "You currently do not have the MailPoet WP plugin installed or activated.", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	<br>
	<div class="tvd-card-action">
		<div class="tvd-row tvd-no-margin">
			<div class="tvd-col tvd-s12">
				<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo __( "Cancel", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
		</div>
	</div>
<?php endif ?>
