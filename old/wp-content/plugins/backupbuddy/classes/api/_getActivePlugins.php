<?php
$plugins = get_option( 'active_plugins' );

if ( !function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$pluginData = array();
foreach( (array)$plugins as $plugin ) {
	$info = array_change_key_case( get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin, false, false ), CASE_LOWER );
	//print_r( $info );
	$pluginData[$plugin] = array( 'name' => $info['name'], 'version' => $info['version'] );
}

return $pluginData;
/*
$myPluginFiles = array_values($pup_plugin_files);
if (is_array($myPluginFiles[0])) {
	// new style - used the keys, not the values
	$myPluginFiles = array_keys($pup_plugin_files);
};
sort($myPluginFiles); // Alphabetize by filename. Better way?
$myPluginFiles=array_unique($myPluginFiles);

function pups_getPluginData($plugin_file) {
	if (trim($plugin_file) == "") return '';
	if (!file_exists(ABSPATH . '/wp-content/plugins/' .
$plugin_file)) return '';
	if (!is_readable(ABSPATH . '/wp-content/plugins/' .
$plugin_file)) return '';
	$plugin_data = implode('', file(ABSPATH .
'/wp-content/plugins/' . $plugin_file));
	preg_match("|Plugin Name:(.*)|i", $plugin_data,
$plugin_name);
	if ('' == $plugin_name[1]) return '';
	preg_match("|Plugin URI:(.*)|i", $plugin_data, $plugin_uri);
	preg_match("|Description:(.*)|i", $plugin_data,
$description);
	preg_match("|Author:(.*)|i", $plugin_data, $author_name);
	preg_match("|Author URI:(.*)|i", $plugin_data, $author_uri);
	if ( preg_match("|Version:(.*)|i", $plugin_data, $version) )
		$version = $version[1];
	else
		$version ='';

	$description = wptexturize($description[1]);
	$description = wp_kses($description, array('a' => array('href' => array(),'title' => array()),'abbr' => array('title' => array()),'acronym' => array('title' => array()),'code' => array(),'em' => array(),'strong' => array()) );

	if ('' == $plugin_uri) {
		$plugin = $plugin_name[1];
	} else {
		$plugin = __("<a href='".trim($plugin_uri[1])."' title='Visit plugin homepage'>{$plugin_name[1]}</a>");
	}

	if ('' == $author_uri) {
		$author = $author_name[1];
	} else {
		$author = __("<a href='".trim($author_uri[1])."' title='Visit author homepage'>{$author_name[1]}</a>");
	}
*/
	//return array('plugin_name' => trim($plugin_name[1]), 'plugin_uri' => $plugin_uri[1], 'description' => $description, 'author_name' => $author_name[1], 'author_uri' => $author_uri[1], 'version' => $version, 'plugin' => $plugin, 'author' => $author );