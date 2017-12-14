<?php
/*
 * Plugin Name:       Under Construction WP
 * Plugin URI:        http://www.seedprod.com
 * Description:       Simple Under Construction Page & Maintenance Mode plugin for WordPress.
 * Version:           1.0.1
 * Author:            John Turner
 * Author URI:        http://www.seedprod.com
 * Text Domain: under-construction-wp
 * Domain Path: /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * Copyright 2015  John Turner (email : john@seedprod.com, twitter : @johnturner)
 */

/**
 * Default Constants
 */
define( 'SEED_UCP_SHORTNAME', 'seed_ucp' ); // Used to reference namespace functions.
define( 'SEED_UCP_SLUG', 'under-construction-wp/under-construction-wp.php' ); // Used for settings link.
define( 'SEED_UCP_PLUGIN_NAME', __( 'Under Construction', 'under-construction-wp' ) ); // Plugin Name shows up on the admin settings screen.
define( 'SEED_UCP_VERSION', '1.0.1'); // Plugin Version Number. Recommend you use Semantic Versioning http://semver.org/
define( 'SEED_UCP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // Example output: /Applications/MAMP/htdocs/wordpress/wp-content/plugins/under-construction/
define( 'SEED_UCP_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // Example output: http://localhost:8888/wordpress/wp-content/plugins/under-construction/
define( 'SEED_UCP_TABLENAME', 'seed_ucp_subscribers' );


/**
 * Load Translation
 */
function seed_ucp_load_textdomain() {
    load_plugin_textdomain( 'under-construction-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'seed_ucp_load_textdomain');


/**
 * Upon activation of the plugin, see if we are running the required version and deploy theme in defined.
 *
 * @since 0.1.0
 */
function seed_ucp_activation(){
	require_once( 'inc/default-settings.php' );
	add_option('seed_ucp_settings_content',unserialize($seed_ucp_settings_deafults['seed_ucp_settings_content']));
}
register_activation_hook( __FILE__, 'seed_ucp_activation' );


/***************************************************************************
 * Load Required Files
 ***************************************************************************/

// Global
global $seed_ucp_settings;

require_once( 'framework/get-settings.php' );
$seed_ucp_settings = seed_ucp_get_settings();

require_once( 'inc/class-seed-ucp.php' );
add_action( 'plugins_loaded', array( 'SEED_UCP', 'get_instance' ) );

if( is_admin() ) {
// Admin Only
	require_once( 'inc/config-settings.php' );
    require_once( 'framework/framework.php' );
    add_action( 'plugins_loaded', array( 'SEED_UCP_ADMIN', 'get_instance' ) );
} else {
// Public only

}
