<?php if ( $this->messages ) : ?>
	<?php $this->render( 'messages' ); ?>
<?php endif ?>
<?php if ( empty( $this->messages['redirect'] ) ) : ?>
	<?php include TVE_DASH_PATH .'/templates/header.phtml';?>
	<div class="tvd-v-spacer vs-2"></div>
	<div class="dash-icon-manager-settings">
		<h3><?php echo __( "Thrive Icon Manager", TVE_DASH_TRANSLATE_DOMAIN ); ?></h3>
		<p><?php echo __( "Thrive Themes / Content Builder integrate with IcoMoon. Here's how it works:", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
		<ol>
			<li><?php echo sprintf( __( "%s to go to the IcoMoon web app and select the icons you want to use in your site", TVE_DASH_TRANSLATE_DOMAIN ), '<a target="_blank" href="//icomoon.io/app/#/select">' . __( "Click here", TVE_DASH_TRANSLATE_DOMAIN ) . '</a>' ) ?></li>
			<li><?php echo __( "Download the font file from IcoMoon to your computer", TVE_DASH_TRANSLATE_DOMAIN ) ?></li>
			<li><?php echo __( "Upload the font file through the upload button below", TVE_DASH_TRANSLATE_DOMAIN ) ?></li>
			<li><?php echo __( "Your icons will be available for use!", TVE_DASH_TRANSLATE_DOMAIN ) ?></li>
		</ol>
		<div class="clear"></div>
		<p>&nbsp;</p>
		<h3><?php echo __( "Import Icons", TVE_DASH_TRANSLATE_DOMAIN ) ?></h3>

		<?php if ( ! $this->icons ) : ?>
			<p><?php echo __( "You don't have any icons yet, use the Upload button to import a custom icon pack.", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
		<?php else: ?>
			<p><?php echo __( "Your custom icon pack has been loaded. To modify your icon pack, simply upload a new file.", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
		<?php endif ?>

		<?php $this->render( 'form' ) ?>

		<div class="clear"></div>
		<p>&nbsp;</p>

		<?php if ( $this->icons ) : ?>
			<?php $this->render( 'icons' ) ?>
		<?php endif ?>

		<div class="tvd-row" style="margin-top: 10px;">
			<a href="<?php echo admin_url( 'admin.php?page=tve_dash_section' ); ?>" class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-gray">
				<?php echo __( "Back To Dashboard", TVE_DASH_TRANSLATE_DOMAIN ); ?>
			</a>
		</div>
	</div>
<?php endif ?>