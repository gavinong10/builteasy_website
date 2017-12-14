<?php
/**
 * Template Name: Submission Index
 */

if (is_user_logged_in()) {
  $redirect = aviators_submission_process_page();
  if($redirect) {
    return;
  }
  $content = aviators_submission_render_page();
    echo View::render( 'page-submission-index.twig', array('content' => $content) );
} else {
    aviators_flash_add_message( AVIATORS_FLASH_ERROR, __( 'You must be logged in to access this page.', 'aviators' ) );

    $pages = get_posts(array(
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-login.php',
    ));

    if (is_array($pages) && count($pages) > 0) {
        $login_page = $pages[0];
        $login_page_permalink = get_post_permalink( $login_page->ID );
        return wp_redirect( $login_page_permalink );
    }

    return wp_redirect( home_url() );
}