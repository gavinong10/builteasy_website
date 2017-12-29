<?php

/**
 * Plugin Name:       ReachEdge
 * Plugin URI:        https://github.com/reachlocal/reachedge-wordpress-4x-tracking-plugin
 * Description:       Enables the <a href="http://go.reachlocal.com/contact-us-edge.html">ReachLocal</a> Tracking Code on all your site pages.
 * Version:           1.3.0
 * Author:            ReachLocal, Inc.
 * Author URI:        http://www.reachlocal.com/
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

define('DEFAULT_CODE', '00000000-0000-0000-0000-000000000000');

if (!defined('WPINC')) {
  die;
}

function reachlocal_async_scripts($url){
  if ( strpos( $url, '#reachlocal_async') === false )
      return $url;
  else
    return str_replace( '#reachlocal_async', '', $url )."' async='async";
}

add_filter( 'clean_url', 'reachlocal_async_scripts', 11, 1 );

function reachedge_tracking_plugin() {
  $reachlocal_tracking_id = get_option('reachlocal_tracking_code_id' );

  if (strlen($reachlocal_tracking_id) == strlen(constant('DEFAULT_CODE')) && $reachlocal_tracking_id != DEFAULT_CODE) {
    $output = '<script type="text/javascript">var rl_siteid = "' .  $reachlocal_tracking_id . '";</script> <script type="text/javascript" src="//cdn.rlets.com/capture_static/mms/mms.js" async="async"></script>';
    echo $output;
  }
}

if (is_admin()) {
  require_once(plugin_dir_path(__FILE__) . 'reachedge-tracking-plugin-settings.php');
} else {
  add_action('wp_enqueue_scripts', 'reachedge_tracking_plugin', 5);
}
