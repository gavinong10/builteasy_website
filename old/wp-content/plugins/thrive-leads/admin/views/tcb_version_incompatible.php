<?php
/**
 * notice to be displayed if license is not validated / active
 * going to load the styles inline because there are so few lines and not worth an extra server hit.
 */
?>
<div class="tve-leads-notice-overlay">
	<div id="tve_leads_license_notice">
		<img src="<?php echo TVE_LEADS_URL; ?>/admin/img/logo.png">

		<p style="margin: 20px 0">
			<?php echo __( "It looks like you have Thrive Content Builder installed, but it's not compatible with this version of Thrive Leads. The Thrive Leads plugin uses Thrive Content Builder to edit various pieces of content.
To be able to use this feature, please make sure you have the latest versions for both plugins by clicking on the following link and checking for updates:", 'thrive-leads' ) ?>
		</p>

		<p style="margin: 0;">
			<a class="tve-license-link" href="<?php echo admin_url( 'plugins.php' ); ?>"><?php echo __( 'Manage plugins', 'thrive-leads' ) ?></a>
		</p>
	</div>
</div>
<style type="text/css">
	.tve-leads-notice-overlay {
		z-index: 3000000;
		background: rgba(0, 0, 0, .4);
		position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
	}

	#tve_leads_license_notice {
		width: 500px;
		text-align: center;
		top: 50%;
		left: 50%;
		margin: -150px 0 0 -300px;
		padding: 50px;
		z-index: 3000;
		position: fixed;
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
		background-image: -webkit-linear-gradient(top, #ffffff 20%, #e6e6e6 100%);
		background-image: -moz-linear-gradient(top, #ffffff 20%, #e6e6e6 100%);
		background-image: -o-linear-gradient(top, #ffffff 20%, #e6e6e6 100%);
		background-image: linear-gradient(top, #ffffff 20%, #e6e6e6 100%);
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		border-radius: 10px;
		-webkit-box-shadow: 2px 5px 3px #efefef;
		-moz-box-shadow: 2px 5px 3px #efefef;
		box-shadow: 2px 2px 5px 3px rgba(0, 0, 0, .4);
	}

	#tve_leads_license_notice .tve-license-link, #tve_leads_license_notice .tve-license-link:active, #tve_leads_license_notice .tve-license-link:visited {
		color: #5DA61E;
	}
</style>