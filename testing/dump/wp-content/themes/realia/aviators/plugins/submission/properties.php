<?php

/**
 * Processing of submission page
 * Takes care of permission
 * Takes care of performing appropriate action
 * @return string: Redirect identifier
 */
function aviators_submission_process_page() {
  // most basic security check
  if (!is_user_logged_in()) {
    aviators_flash_add_message(AVIATORS_FLASH_ERROR, __('You need to login to access this page.', 'aviators'));
    wp_redirect(home_url());
    return true;
  }

  if ($_GET['id']) {
    // our precious permission check failed
    if (!aviators_property_action_access($_GET['id'], get_current_user_id(), $_GET['action'])) {
      $page = _aviators_properties_get_submission_page();
      wp_redirect(get_permalink($page));
      return true;
    }
  }

  // Edit action
  if (isset($_GET['action'])) {
    $id = null;
    if(isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    switch ($_GET['action']) {
      case 'add':
      case 'edit':
        _aviators_property_form_enqueue_js();
        if (isset($_POST['post_title'])) {
          return aviators_properties_property_edit($id, $_POST);
        }
        break;
      case 'delete':
        return aviators_properties_property_delete($id);
        break;
      case 'delete-confirm':
        return aviators_properties_property_delete_confirm($id);
        break;
      case 'delete-thumbnail':
        return aviators_properties_property_thumbnail_delete($id);
        break;
      case 'unpublish':
        return aviators_properties_property_status($id, 'unpublish');
        break;
      case 'publish':
        return aviators_properties_property_status($id, 'publish');
        break;
      case 'pending':
        return aviators_properties_property_status($id, 'pending');
        break;
      default:
        break;
    }
  }
}