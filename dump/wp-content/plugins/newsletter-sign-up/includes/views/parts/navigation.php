
<?php defined( 'ABSPATH' ) or exit; ?>
<h2 class="nav-tab-wrapper">
	<a href="?page=newsletter-sign-up" class="nav-tab <?php echo ($tab == 'mailinglist-settings') ? 'nav-tab-active' : ''; ?>">Mailinglist Settings</a>
	<a href="?page=newsletter-sign-up-checkbox-settings" class="nav-tab <?php echo ($tab === 'checkbox-settings') ? 'nav-tab-active' : ''; ?>">Checkbox Settings</a>
	<a href="?page=newsletter-sign-up-form-settings" class="nav-tab <?php echo ($tab === 'form-settings') ? 'nav-tab-active' : ''; ?>">Form Settings</a>
	<a href="?page=newsletter-sign-up-config-helper" class="nav-tab <?php echo ($tab === 'config-helper') ? 'nav-tab-active' : ''; ?>">Config Helper</a>
</h2>