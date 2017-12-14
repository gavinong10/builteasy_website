<?php
/**
 * Gets decoded settings from theme
 *
 * @return string|boolean
 */
function aviators_settings_get_config( $filename ) {

	if ( file_exists( $filename ) ) {
		$content = file_get_contents( $filename );
		return json_decode( $content );
	}
	return FALSE;
}


/**
 * Gets settings full path
 *
 * @return string
 */
function aviators_settings_get_settings_path() {
	return get_template_directory() . '/' . AVIATORS_SETTINGS_FILENAME;
}


/**
 * Gets registered pages
 */
function aviators_settings_get_registered_pages( $page = '' ) {
	$tabs    = aviators_settings_get_tabs( $page );
	$display = array();

	foreach ( $tabs as $tab ) {
		if ( $tab->settings->subpage_slug == $page ) {
			$display[] = $tab->settings->slug;
		}
	}

	return $display;
}


/**
 * Registers all pages
 *
 * @return array
 */
function aviators_settings_register_pages() {
	$plugins = aviator_core_get_all_plugins_list();
	$display = array();

	foreach ( $plugins as $key => $plugin ) {
		$filename = $plugin['path'] . '/settings.json';

		if ( is_file( $filename ) ) {
			$tabs = aviators_settings_get_tabs( $key );

			foreach ( $tabs as $tab ) {
				$page_id = $tab->settings->subpage_slug . '_' . $tab->settings->slug;

				foreach ( $tab->sections as $section ) {
					$id = $tab->settings->subpage_slug . '_' . $section->settings->id;

					if ( ! empty( $section->settings->title ) ) {
						$title = $section->settings->title;
					}
					else {
						$title = '';
					}
//                    print $page_id . '<br>';
					add_settings_section( $id, $title, '', $page_id );
					aviators_settings_register_fields( $section, $id, $page_id );
					$display[] = $page_id;
				}
			}
		}
	}

	return $display;
}


/**
 * Registers sections for tab
 *
 * @param $section    string
 * @param $section_id string
 * @param $page_id    string
 */
function aviators_settings_register_fields( $section, $section_id, $page_id ) {
	foreach ( $section->options as $option ) {
		$id = $section_id . '_' . $option->id;
		add_settings_field( $id, $option->label, AVIATORS_SETTINGS_FIELD_CALLBACK, $page_id, $section_id, array(
			'id'     => $id,
			'option' => $option
		) );
		register_setting( $page_id, $id );
	}
}


/**
 * Gets list of available tabs
 *
 * @return array
 */
function aviators_settings_get_tabs( $page ) {
	$tabs    = array();
	$plugins = aviator_core_get_all_plugins_list();
	$config  = aviators_settings_get_config( $plugins[$page]['path'] . '/settings.json' );

	if ( is_array( $config->tabs ) ) {
		foreach ( $config->tabs as $tab ) {
			if ( $tab->settings->subpage_slug == $page || $page == '' ) {
				$tabs[] = $tab;
			}
		}
	}

	return $tabs;
}

/**
 * Fetch value for certain option
 *
 * @param $tab_slug   string
 * @param $section_id string
 * @param $option_id  string
 *
 * @return mixed|void
 */
function aviators_settings_get_value( $plugin_slug, $section_id, $option_id ) {
	$plugins = aviator_core_get_all_plugins_list();

	if ( ! isset( $plugins[$plugin_slug] ) ) {
		return;
	}

	$config = aviators_settings_get_config( $plugins[$plugin_slug]['path'] . '/settings.json' );

	foreach ( $config->tabs as $tab ) {
		foreach ( $tab->sections as $section ) {
			if ( $section->settings->id == $section_id ) {
				foreach ( $section->options as $option ) {
					if ( $option->id == $option_id ) {
						$result = $option;
						break;
					}
				}
			}
		}
	}

	$key = $plugin_slug . '_' . $section_id . '_' . $option_id;

	return get_option( $key, ! empty( $result->default ) ? $result->default : NULL );
}