=== WordPress Reset ===
Contributors: sivel
Donate Link: http://sivel.net/donate
Tags: wordpress-reset, wordpress, reset, admin
Requires at least: 2.8
Tested up to: 3.8
Stable tag: 1.3.3

Resets the WordPress database back to it's defaults. Deletes all customizations and content. Does not modify files only resets the database.

== Description ==

Resets the WordPress database back to it's defaults. Deletes all customizations and content. Does not modify files only resets the database.

This plugin is very helpful for plugin and theme developers.

If the admin user exists and has level_10 permissions it will be recreated with its current password and email address. If the admin user does not exist or is a dummy account without admin permissions the username that is logged in will be recreated with its email address and current password. The blog name is also kept.

The plugin will add an entry to the Admin Bar under the site title and has the ability to reactivate itself and other plugins after the reset.

== Installation ==

1. Upload the `wordpress-reset` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer.
1. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer

== Frequently Asked Questions ==

= How can I get this plugin to automatically reactive after the reset? =

Add `define( 'REACTIVATE_WP_RESET', true );` to `wp-config.php` above `/* That's all, stop editing! Happy blogging. */`

= Can this plugin reactivate other plugins automatically after performing the reset? =

Add an array called `$reactivate_wp_reset_additional` to the global scope by placing it in `wp-config.php` above `/* That's all, stop editing! Happy blogging. */` that contains the plugin basenames of the plugins to activate, such as:

`
$reactivate_wp_reset_additional = array(
	'hello.php',
	'akismet/akismet.php'
);
`

== Upgrade ==

1. Use the plugin updater in WordPress or...
1. Delete the previous `wordpress-reset` folder from the `/wp-content/plugins/` directory
1. Upload the new `wordpress-reset` folder to the `/wp-content/plugins/` directory

== Usage ==

1. Visit the WordPress Reset Tools page by either clicking the link in the Admin Bar or Tools>WordPress Reset
1. Type 'reset' in the text field and click reset.

== Upgrade Notice ==

= 1.3.3 =
Fix an issue where a user does not have a user_level

= 1.3.2 =
Support PHP versions below 5.3.0, by not using lambda function creation in add_action for admin_notices

= 1.3.1 =
Fix sql query, so that _ isn't being used as a single character match

= 1.3 =

Fixes a deprecated notice in WordPress 3.3, removed the $auto_reactivate variable, and look for REACTIVATE_WP_RESET to be defined in wp-config.php, as well as the ability to activate additional plugins using a global $reactivate_wp_reset_additional array defined in wp-config.php

== Changelog ==

= 1.3.3 (2013-12-18): =
* Fix an issue where a user does not have a user_level

= 1.3.2 (2012-02-13): =
* Support PHP versions below 5.3.0, by not using lambda function creation in add_action for admin_notices

= 1.3.1 (2012-01-29): =
* Apparently in a LIKE query, an underscore matches a single character, so we must escape it

= 1.3 (2012-01-29): =
* Fixes a deprecated notice in WordPress 3.3 when using get_userdatabylogin
* Removes the $auto_reactivate variable
* Look for REACTIVATE_WP_RESET constant to be defined in wp-config.php for auto reactivation after reset
* Look for global $reactivate_wp_reset_additional for additional plugins to auto reactivate after reset
* Add a Reset link to the admin bar under the site title

= 1.2 (2010-04-04): =
* Updates to fix deprecated notices for WP 3.0
* Updates for 3.0 to disable password nag
* Modify new blog email to not include the generated password

= 1.1 (2009-10-01): =
* WordPress 2.8 Updates, do not show auto generated password nag after reset

= 1.0 (2009-03-17): =
* Initial Public Release
