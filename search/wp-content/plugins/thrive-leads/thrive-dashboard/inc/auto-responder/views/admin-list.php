<?php
$json_connected_apis = Thrive_Dash_List_Manager::toJSON( $connected_apis );
$json_available_apis = Thrive_Dash_List_Manager::toJSON( $available_apis );
?>
<script type="text/javascript">
	TVE_Dash.API.ConnectedAPIs = new TVE_Dash.API.collections.Connections(<?php echo $json_connected_apis ?>);
	TVE_Dash.API.AvailableAPIs = new TVE_Dash.API.collections.Connections(<?php echo $json_available_apis ?>);
	TVE_Dash.API.ToBeConnected = new TVE_Dash.API.models.ToBeConnected();
	TVE_Dash.API.APITypes = new TVE_Dash.API.collections.APITypes(<?php echo json_encode( $types )?>);
</script>

<?php include dirname( __FILE__ ) . '/admin-messages.php' ?>
<?php include TVE_DASH_PATH . '/templates/header.phtml'; ?>
<div class="tvd-v-spacer"></div>
<div class="tvd-container tvd-hide tvd-show-onload">
	<h3 class="tvd-section-title"><?php echo __( "Active Connections", TVE_DASH_TRANSLATE_DOMAIN ) ?></h3>
	<div class="tvd-row tvd-api-list"></div>

	<div class="tvd-row">
		<div class="tvd-col tvd-s12 tvd-m6">
			<a href="<?php echo admin_url( 'admin.php?page=tve_dash_section' ); ?>"
			   class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-gray">
				<?php echo __( "Back To Dashboard", TVE_DASH_TRANSLATE_DOMAIN ); ?>
			</a>
		</div>
		<div class="tvd-col tvd-s12 tvd-m6">
			<a href="<?php echo admin_url( 'admin.php?page=tve_dash_api_error_log' ); ?>"
			   class="tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-dark tvd-waves-effect tvd-right tvd-btn-small">
				<?php echo __( "View Error Logs", TVE_DASH_TRANSLATE_DOMAIN ); ?>
			</a>
		</div>
	</div>
</div>

<script type="text/template" id="tvd-api-connected">
	<div class="tvd-card tvd-white tvd-small">
		<div class="tvd-card-image" style="background-image: url('<#= item.get('logoUrl') #>');">
			<img src="<#= item.get('logoUrl') #>" alt="<#= item.get('title')#>">
		</div>
		<div class="tvd-card-action">
			<div class="tvd-row">
				<div class="tvd-col tvd-s12 tvd-m6">
					<h4>
						<#= item.get('title') #>
					</h4>
				</div>
				<div class="tvd-col tvd-s12 tvd-m6">
					<div class="tvd-right">
						<a class="tvd-api-test tvd-btn tvd-btn-green tvd-btn-toggle">
							<i class="tvd-icon-exchange tvd-left"></i>
							<span class="tvd-btn-text"><?php echo __( "Test", TVE_DASH_TRANSLATE_DOMAIN ) ?></span>
						</a>
						<a class="tvd-api-edit tvd-btn tvd-btn-blue tvd-btn-toggle">
							<i class="tvd-icon-pencil tvd-left"></i>
							<span class="tvd-btn-text"><?php echo __( "Edit", TVE_DASH_TRANSLATE_DOMAIN ) ?></span>
						</a>
						<a class="tvd-api-delete tvd-btn tvd-btn-red tvd-btn-toggle">
							<i class="tvd-icon-trash-o tvd-left"></i>
							<span class="tvd-btn-text"><?php echo __( "Delete", TVE_DASH_TRANSLATE_DOMAIN ) ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tvd-api-state-new">
	<div class="tvd-card tvd-small tvd-card-new tvd-valign-wrapper" id="tvd-add-new-api">
		<div class="tvd-card-content tvd-valign tvd-center-align tvd-pointer">
			<i class="tvd-icon-plus tvd-icon-rounded tvd-icon-medium"></i>
			<h4><?php echo __( "Add new Connection", TVE_DASH_TRANSLATE_DOMAIN ) ?></h4>
		</div>
	</div>
</script>

<script type="text/template" id="tvd-api-state-select">
	<div class="tvd-card tvd-small tvd-card-new tvd-valign-wrapper">
		<div class="tvd-card-content tvd-valign tvd-center-align">
			<div class="tvd-row">
				<div class="tvd-col tvd-s10 tvd-offset-s1">
					<div class="input-field2">
						<select id="selected-api">
							<optgroup label="" class="tvd-hide">
								<option
									value="none"><?php echo __( "- Select an app -", TVE_DASH_TRANSLATE_DOMAIN ) ?></option>
							</optgroup>
						</select>
					</div>
				</div>
			</div>
			<h4 class="tvd-dark-text"><?php echo __( "Add new Connection", TVE_DASH_TRANSLATE_DOMAIN ) ?></h4>
		</div>
	</div>
</script>

<script type="text/template" id="tvd-api-state-form">
	<div class="tvd-card tvd-white">
		<div class="tvd-card-content tvd-card-content-wfooter"></div>
	</div>
</script>

<script type="text/template" id="tvd-api-state-error">
	<div class="tvd-card tvd-orange">
		<div class="tvd-card-content tvd-card-content-wfooter">
			<div class="tvd-center-align">
				<i class="tvd-icon-close tvd-icon-big tvd-icon-border tvd-icon-rounded"></i>
			</div>
			<h3 class="tvd-card-title"><?php echo __( "The connection didn't work.", TVE_DASH_TRANSLATE_DOMAIN ) ?></h3>
			<p class="tvd-center tvd-card-spacer">
				<?php echo __( "Error message:", TVE_DASH_TRANSLATE_DOMAIN ) ?>
				<#= item.get('response').message#>
			</p>
			<br><br>
			<div class="tvd-card-action">
				<div class="tvd-row tvd-no-margin">
					<div class="tvd-col tvd-s12 tvd-m6">
						<a class="tvd-api-cancel tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-light tvd-waves-effect"><?php echo __( "Cancel", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
					</div>
					<div class="tvd-col tvd-s12 tvd-m6">
						<a class="tvd-api-retry tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-light tvd-waves-effect"><?php echo __( "Retry", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tvd-api-state-success">
	<div class="tvd-card tvd-green tvd-medium">
		<div class="tvd-card-content tvd-center-align">
			<i class="tvd-icon-check tvd-icon-big tvd-icon-border tvd-icon-rounded"></i>
			<h3 class="tvd-modal-title">
				<#= item.get('title') #><br/><?php echo __( "Connection Ready!", TVE_DASH_TRANSLATE_DOMAIN ) ?></h3>
			<p>
				<# if(typeof item.get('success_message') !== 'undefined' && item.get('success_message') != '') { #>
					<#= item.get('success_message') #>
						<# } else { #>
							<?php echo __( "You can now connect your opt-in forms to ", TVE_DASH_TRANSLATE_DOMAIN ) ?>
							<#= item.get('title') #>.
								<a class="wistia-popover[height=450,playerColor=2bb914,width=800]" href="//fast.wistia.net/embed/iframe/7sv6uvfshp?popover=true"><?php echo __( "See how it's done.", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
								<# } #>
			</p>
			<div class="tvd-row">
				<div class="tvd-col tvd-s12 tvd-m6 tvd-offset-m3">
					<a href="javascript:void(0)"
					   class="tvd-api-done tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-light tvd-full-btn tvd-btn-margin-top tvd-waves-effect"><?php echo __( "Done", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
				</div>
			</div>
		</div>
	</div>
</script>

<?php foreach ( $connected_apis as $key => $api ) : ?>
	<?php /** @var $api Thrive_Dash_List_Connection_Abstract */ ?>
	<script type="text/template" id="tvd-api-form-<?php echo $key ?>">
		<?php echo $api->outputSetupForm() ?>
	</script>
<?php endforeach; ?>

<?php foreach ( $available_apis as $key => $api ) : ?>
	<?php /** @var $api Thrive_Dash_List_Connection_Abstract */ ?>
	<script type="text/template" id="tvd-api-form-<?php echo $key ?>">
		<?php echo $api->outputSetupForm() ?>
	</script>
<?php endforeach; ?>

<script type="text/template" id="tvd-api-form-preloader">
	<div class="tvd-card-preloader">
		<div class="tvd-preloader-wrapper tvd-big tvd-active">
			<div class="tvd-spinner-layer tvd-spinner-blue-only">
				<div class="tvd-circle-clipper tvd-left">
					<div class="tvd-circle"></div>
				</div>
				<div class="tvd-gap-patch">
					<div class="tvd-circle"></div>
				</div>
				<div class="tvd-circle-clipper tvd-right">
					<div class="tvd-circle"></div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tvd-api-connection-error">
	<div class="tvd-card tvd-orange tvd-valign-wrapper">
		<div class="tvd-card-content tvd-valign tvd-center-align">
			<a href="javascript:void(0)" class="tvd-close-card">
				<i class="tvd-icon-close2"></i>
			</a>
			<i class="tvd-icon-exclamation tvd-icon-rounded tvd-icon-medium"></i>
			<h4><?php echo __( "The connection failed.", TVE_DASH_TRANSLATE_DOMAIN ) ?></h4>
			<p>
				<#= item.get('response').message #>
			</p>
			<a class="tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-light tvd-api-edit tvd-waves-effect">
				<?php echo __( "Edit settings", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</a>
		</div>
	</div>
</script>

<script type="text/template" id="tvd-api-confirm-delete">
	<div class="tvd-card tvd-small tvd-red">
		<div class="tvd-card-content tvd-center-align">
			<h4 class="tvd-margin-top">
				<?php echo __( "Are you sure you want to delete this connection?", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</h4>
		</div>
		<div class="tvd-card-action">
			<div class="tvd-row">
				<div class="tvd-col tvd-s12 tvd-m6">
					<a class="tvd-api-delete-yes tvd-btn-flat tvd-btn-flat-secondary tvd-btn-flat-light tvd-left tvd-waves-effect"
					   href="javascript:void(0)">
						<?php echo __( "Yes, delete", TVE_DASH_TRANSLATE_DOMAIN ) ?>
					</a>
				</div>
				<div class="tvd-col tvd-s12 tvd-m6">
					<a class="tvd-api-delete-no tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-light tvd-right tvd-waves-effect">
						<?php echo __( "No, keep it", TVE_DASH_TRANSLATE_DOMAIN ) ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tvd-api-connection-hover">
	<div class="tvd-card tvd-small <#= color #> tvd-valign-wrapper">
		<div class="tvd-card-content tvd-valign tvd-center-align">
			<a href="javascript:void(0)" class="tvd-close-card">
				<i class="tvd-icon-close2"></i>
			</a>
			<i class="<#= icon #> tvd-icon-medium"></i>
			<h4 class="tvd-dark-text">
				<#= text ? text : 'Testing...' #>
			</h4>
		</div>
	</div>
</script>
