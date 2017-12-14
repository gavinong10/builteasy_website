<?php
/**
 * notice to be displayed if license not validated - going to load the styles inline because there are so few lines and not worth an extra server hit.
 */
?>
<div id="tve_license_notice">
	<img src="<?php echo tve_editor_css(); ?>/images/Logo-Large.png">

	<p><?php echo sprintf( __( "You need to %s before you can use the editor!", 'thrive-cb' ), '<a class="tve-license-link" href="' . admin_url( "admin.php?page=tve_dash_license_manager_section&return=" . rawurlencode( tcb_get_editor_url() ) ) . '">' . __( "activate your license", "thrive-cb" ) . '</a>' ) ?></p>
	</p></div>
<style type="text/css">
	#tve_license_notice {
		width: 500px;
		margin: 0 auto;
		text-align: center;
		top: 50%;
		left: 50%;
		margin-top: -100px;
		margin-left: -250px;
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
		box-shadow: 2px 5px 3px #efefef;
	}

	#tve_license_notice .tve-license-link, #tve_license_notice .tve-license-link:active, #tve_license_notice .tve-license-link:visited {
		color: #5DA61E;
	}
</style>