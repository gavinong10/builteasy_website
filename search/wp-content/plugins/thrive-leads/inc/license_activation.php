<?php

/**
 * make the call to validate the license
 *
 * @param $licensed_email
 * @param string $license_key
 *
 * @return array|mixed
 */
function tve_leads_license_check( $licensed_email, $license_key = '' ) {
	$api_url = "https://thrivethemes.com/wp-content/plugins/license_check/api/request.php";
	$api_url .= "?license=" . $license_key;
	$api_url .= "&email=" . $licensed_email;
	$api_url .= "&product_id=4,5,6,7,37,38,39,40,41,42,43,30,31,36,47,48";
	$licenseValid = wp_remote_get( $api_url, array( 'sslverify' => false ) );

	if ( is_wp_error( $licenseValid ) ) {

		/** @var WP_Error $licenseValid */
		/** Couldn't connect to the API URL - possible because wp_remote_get failed for whatever reason.  Maybe CURL not activated on server, for instance */
		return array(
			'success' => 0,
			'reason'  => sprintf( __( "An error occurred while connecting to the license server. Error: %s. Please login to thrivethemes.com, report this error message on the forums and we'll get this sorted for you", 'thrive-leads' ), $licenseValid->get_error_message() )
		);
	}

	/** Successfully connected to the API */
	$response = @json_decode( $licenseValid['body'], true );
	if ( empty( $response ) ) {

		return array(
			'success' => 0,
			'reason'  => sprintf( __( "An error occurred while receiving the license status. The response was: %s. Please login to thrivethemes.com, report this error message on the forums and we'll get this sorted for you.", 'thrive-leads' ), $licenseValid['body'] )
		);
	}

	return $response;

}

/** retrospectively modify license status to remove license details from db once activated **/
if ( tve_leads_license_activated() ) {
	update_option( 'tve_leads_license_email', 'License Activated' );
	update_option( 'tve_leads_license_key', 'License Activated' );
}

if ( isset( $_POST['tve_leads_license_email'] ) && isset( $_POST['tve_leads_license_key'] ) ) {
	update_option( 'tve_leads_license_email', trim( $_POST['tve_leads_license_email'] ) );
	update_option( 'tve_leads_license_key', trim( $_POST['tve_leads_license_key'] ) );

	$validate = tve_leads_license_check( get_option( 'tve_leads_license_email' ), get_option( 'tve_leads_license_key' ) );

	// license validated
	if ( isset( $validate['success'] ) && $validate['success'] == 1 ) {
		update_option( 'tve_leads_license_status', "ACTIVE" );
		update_option( 'tve_leads_license_email', "License Activated" );
		update_option( 'tve_leads_license_key', "License Activated" );

		?>
		<div id="message" class="updated" style="margin: 10px 0 0; font-weight: bold;">
			<p><?php echo __( 'Thank you - You have successfully validated your license!', 'thrive-leads' ) ?></p>
		</div>

		<?php
		// some kind of error
	} elseif ( $validate['success'] == 0 ) {
		?>
		<div id="message" class="error" style="margin: 10px 0 0; font-weight: bold;">
			<p><?php echo $validate['reason']; ?></p>
		</div>
		<?php
	} else {
		?>
		<div id="message" class="error" style="margin: 10px 0 0; font-weight: bold;">
			<p><?php echo __( "License activation error - please contact support copying this message and we'll get this sorted for you.", 'thrive-leads' ) ?></p>
		</div>
		<?php
	}
}
$license_email = get_option( 'tve_leads_license_email', '' );
$license_key   = get_option( 'tve_leads_license_key', '' );
?>

<div class="wpbootstrap">
	<div>
		<center>
			<img src="<?php echo TVE_LEADS_ADMIN_URL; ?>/img/logo.png" style="margin-bottom:30px;">
		</center>
		<form method="post" class="form-horizontal">
			<div id="facebook" class="panel like-panel">
				<h2 style="margin-bottom: 10px;"><?php echo __( 'Validate your License:', 'thrive-leads' ) ?></h2>

				<fieldset style="padding-top: 10px;">
					<div class="control-group">
						<label class="tve-control-label" for="tve_leads_license_email"><?php echo __( 'Email Address:', 'thrive-leads' ) ?></label>

						<div class="controls">
							<input type="text" class="short" name="tve_leads_license_email" id="tve_leads_license_email"
							       value="<?php echo $license_email ?>" style="width: 270px;"/>
							<br/><br/>
						</div>
					</div>
					<div class="control-group">
						<label class="tve-control-label" for="tve_leads_license_key"><?php echo __( 'License Key', 'thrive-leads' ) ?></label>

						<div class="controls">
							<input type="text" class="short" name="tve_leads_license_key" id="tve_leads_license_key"
							       value="<?php echo $license_key ?>" style="width: 270px;"/>
						</div>
					</div>
				</fieldset>

				<div class="form-actions">
					<br/><br/>
					<input name="save-action" class="button-primary" type="submit" value="<?php echo __( 'Activate License', 'thrive-leads' ) ?>"/>
				</div>
			</div>
			<div style="clear: both;"></div>
	</div>
	</form>
</div>

<style type="text/css">.wpbootstrap {
		text-align: center;
		margin: 0 auto;
		width: 400px;
		padding: 40px;
		margin-top: 50px;
		-moz-border-radius-bottomleft: 10px;
		-webkit-border-bottom-left-radius: 10px;
		border-bottom-left-radius: 10px;
		-moz-border-radius-bottomright: 10px;
		-webkit-border-bottom-right-radius: 10px;
		border-bottom-right-radius: 10px;
		border-bottom: 1px solid #bdbdbd;
		background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyYWQpIiAvPjwvc3ZnPiA=');
		background-size: 100%;
		background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(20%, #ffffff), color-stop(100%, #e6e6e6));
		background-image: -webkit-linear-gradient(top, #fdfdfd 20%, #e6e6e6 100%);
		background-image: -moz-linear-gradient(top, #fdfdfd 20%, #e6e6e6 100%);
		background-image: -o-linear-gradient(top, #fdfdfd 20%, #e6e6e6 100%);
		background-image: linear-gradient(top, #fdfdfd 20%, #e6e6e6 100%);
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		border-radius: 10px;
		-webkit-box-shadow: 2px 5px 3px #efefef;
		-moz-box-shadow: 2px 5px 3px #efefef;
		box-shadow: 2px 5px 3px #efefef;
	}
</style>