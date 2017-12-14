<?php

require_once 'properties.php';

function aviators_submission_render_page() {

  if($_GET['action'] == 'add') {
    return aviators_submission_render_add();
  }

  if($_GET['action'] == 'edit') {
    return aviators_submission_render_edit($_GET['id']);
  }

  if($_GET['action'] == 'delete-confirm') {
    return false;
  }

  return aviators_submission_render_index();
}

/**
 * List of all user posts
 *
 * @return string
 */
function aviators_submission_render_index() {
	global $wp_query;

	$wp_query = aviators_submission_get_user_submissions( NULL, TRUE );
	$type     = aviators_settings_get_value( 'submission', 'common', 'post_type' );

	if ( empty( $type ) ) {
		aviators_flash_add_message( AVIATORS_FLASH_ERROR, __( 'There are no defined custom post types for submission system.', 'aviators' ) );
		return wp_redirect( home_url() );
	}
	return View::render( 'submission/index.twig', array(
      'posts' => aviators_submission_get_user_submissions(),
	));
}

/**
 * Edit post
 *
 * @param int
 *
 * @return string
 */
function aviators_submission_render_edit( $id ) {
	global $current_user;

	$post = get_post( $id );

	if ( ! is_user_logged_in() ) {
		aviators_flash_add_message( AVIATORS_FLASH_ERROR, __( 'You must be logged in to access this page.', 'aviators' ) );
		return wp_redirect( home_url() );
	}

	if ( $current_user->ID != $post->post_author ) {
		aviators_flash_add_message( AVIATORS_FLASH_ERROR, __( 'You are not post owner.', 'aviators' ) );
		return wp_redirect( home_url() );
	}

	$type = aviators_settings_get_value( 'submission', 'common', 'post_type' );

	if ( empty( $type ) ) {
		aviators_flash_add_message( AVIATORS_FLASH_ERROR, __( 'There are no defined custom post types for submission system.', 'aviators' ) );
		return wp_redirect( home_url() );
	}

    global $post;
    $post = get_post( $id );

    $form = call_user_func( 'aviators_' . $type . '_form' );

	return View::render( 'submission/edit.twig', array(
		'post' => $post,
		'form' => $form,
	) );
}

/**
 * Add new post
 *
 * @return string
 */
function aviators_submission_render_add() {
    if ( ! is_user_logged_in() ) {
        aviators_flash_add_message( AVIATORS_FLASH_ERROR, __( 'You must be logged in to access this page.', 'aviators' ) );
        return wp_redirect( home_url() );
    }

    $type = aviators_settings_get_value( 'submission', 'common', 'post_type' );

    if ( empty( $type ) ) {
        aviators_flash_add_message( AVIATORS_FLASH_ERROR, __( 'There are no defined custom post types for submission system.', 'aviators' ) );
        return wp_redirect( home_url() );
    }

    $form = call_user_func( 'aviators_' . $type . '_form' );

    $tos = aviators_settings_get_value( 'submission', 'tos', 'enable_tos' );
    if($tos) {
        $tos_content = __('No Legal Agreement selected!', 'aviators');
        if($tos_id = aviators_settings_get_value( 'submission', 'tos', 'tos_page' )) {
            $tos_page = get_post($tos_id);
            $tos_content = do_shortcode($tos_page->post_content);
        }
    }

    return View::render( 'submission/add.twig', array(
        'form' => $form,
        'tos' => $tos,
        'tos_page' => $tos_page,
        'tos_content' => $tos_content,
    ));
}