<?php
if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';
$importerError 	= false;

$default_path = get_template_directory() . "/admin/demo/default/default";
$source_path = get_template_directory() . "/admin/demo/default";

if (isset($_POST['path'])) {
	$default_path = trailingslashit(get_template_directory()) . $_POST['path'];
}

if (isset($_POST['source'])) {
	$source_path = trailingslashit(get_template_directory()) . $_POST['source'];
}

if ( !class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) ) {
		require_once($class_wp_importer);
	} else {
		$importerError = true;
	}
}

if ( !class_exists( 'WP_Import' ) ) {
	$class_wp_import = MAD_FRAMEWORK::$path['frameworkPHP'] . 'config-import-export/wordpress-importer.php';
	if ( file_exists( $class_wp_import ) ) {
		require_once($class_wp_import);
	} else {
		$importerError = true;
	}
}

if ($importerError !== false) {
	echo "Please use the wordpress importer and import the XML file that is located in your themes folder manually.";
} else {

	if (class_exists('WP_Import')) {
		include_once('import-class.php');
	}

	if (!is_file($default_path.'.xml')) {
		echo "The XML file containing the dummy content is not available or could not be read in <pre>".get_template_directory() ."</pre><br/> You might want to try to set the file permission to chmod 777.";
	} else {

		do_action('pre_import_hook');

		$wp_import = new mad_wp_import();
		$wp_import->fetch_attachments = true;
		$wp_import->import($default_path . '.xml');
		$wp_import->importSliders($source_path);
		$wp_import->save_settings($default_path . '.php');
		$wp_import->menu_install();

		do_action('after_import_hook');

	}
}