<?php
/** var $this Thrive_Dash_List_Connection_AWeber */
?>
<h2 class="tvd-card-title"><?php echo $this->getTitle() ?></h2>
<?php
try {
	/** @var $this Thrive_Dash_List_Connection_AWeber */
	?>
	<div class="tvd-row">
		<p><?php echo __( "Click the button below to login to your AWeber account and authorize the API Connection.", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	</div>
	<div class="tvd-card-action">
		<div class="tvd-row tvd-no-margin">
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-dark tvd-full-btn tvd-waves-effect"><?php echo __( "Cancel", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
			<div class="tvd-col tvd-s12 tvd-m6">
				<a class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-full-btn"
				   href="<?php echo $this->getAuthorizeUrl() ?>"><?php echo __( "Connect", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
			</div>
		</div>
	</div>

	<?php
} catch ( Thrive_Dash_Api_AWeber_Exception $e ) {
	$url     = false;
	$message = $e->getMessage();
	$api_url = isset( $e->url ) ? $e->url : false;
	?><p
		style="color: red"><?php echo __( "There has been an error while communicating with AWeber API. Below are the error details:", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
	<?php echo $message;
	if ( $api_url ) {
		echo ' (API URL: ' . $api_url . ')';
	}
}
?>
