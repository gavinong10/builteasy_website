<?php

/**
 * Renders settings page
 */
function aviators_settings_render_settings_page() {
	$plugins = aviator_core_get_all_plugins_list();

	$page            = $_GET['page'];
	$config          = aviators_settings_get_config( $plugins[$page]['path'] . '/settings.json' );
	$tabs            = aviators_settings_get_tabs( $page );
	$registered_tabs = aviators_settings_get_registered_pages( $_GET['page'] );

	$submit     = get_submit_button();
	$active_tab = ! empty( $_GET['tab'] ) ? $_GET['tab'] : $tabs[0]->settings->slug;
	$forms      = array();

	foreach ( $registered_tabs as $registered_tab ) {
		if ( $registered_tab == $active_tab ) {
			$forms[] = $_GET['page'] . '_' . $registered_tab;
		}
	}

	foreach ( $tabs as $tab ) {
		if ( $tab->settings->slug == $active_tab ) {
			$active_tab_title = $tab->settings->title;
		}
	}

	echo View::render( 'settings/wrapper.twig', array(
		'active_tab_title' => $active_tab_title,
		'title'            => __( 'Settings', 'settings' ),
		'page_title'       => $config->title,
		'tabs'             => aviators_settings_render_tabs(),
		'forms'            => $forms,
		'active_tab'       => $active_tab,
		'submit'           => $submit
	) );
}

/**
 * Renders all tabs
 *
 * @return string
 */
function aviators_settings_render_tabs() {
	$tabs = aviators_settings_get_tabs( $_GET['page'] );

	return View::render( 'settings/tabs.twig', array(
		'tabs'       => $tabs,
		'active_tab' => ! empty( $_GET['tab'] ) ? $_GET['tab'] : $tabs[0]->settings->slug,
	) );
}

function aviators_settings_render_field( $args ) {
	$option = $args['option'];
	if ( isset( $option->default ) ) {
		$value = get_option( $args['id'], $option->default );
	}
	else {
		$value = get_option( $args['id'] );
	}

	$args = array(
		'args'   => $args,
		'value'  => $value,
		'option' => $option
	);

	if ( $option->type == 'select_user_role' ) {
		$args['user_roles'] = get_editable_roles();
	}

	if ( $option->type == 'select_post_type' ) {
		$args['post_types'] = get_post_types( array(
			'public'   => true,
			'_builtin' => false
		), 'objects' );
	}

	if ( $option->type == 'select_post_type_for_submission' ) {
		$types      = array();
		$post_types = get_post_types( array(
			'public'   => true,
			'_builtin' => false
		), 'objects' );

		if ( is_array( $post_types ) ) {
			foreach ( $post_types as $key => $post_type ) {
				if ( function_exists( 'aviators_' . $key . '_form' ) ) {
					$types[] = $post_type;
				}
			}
		}

		$args['post_types'] = $types;
	}

	echo View::render( 'settings/fields/' . $option->type . '.twig', $args );
}