<?php

add_action('admin_menu', 'rl_add_admin_menu');
add_action('admin_init', 'rl_settings_init');

function rl_add_admin_menu() { 
  add_menu_page('ReachLocal Tracking Code', 'ReachLocal Tracking Code', 'administrator', __FILE__, 'rl_options_page');
}

function rl_settings_init() { 
  register_setting('rl_settings', 'reachlocal_tracking_code_id', 'validate_tracking_code_id');

  add_settings_section(
    'rl_tracking_code_section', 
    __('ReachLocal Tracking Code', 'wordpress'), 
    'rl_settings_section_callback', 
    'rl_settings'
  );

  add_settings_field( 
    'rl_tracking_code_id', 
    __('ID', 'wordpress'), 
    'rl_tracking_code_id_render', 
    'rl_settings', 
    'rl_tracking_code_section' 
  );
}


function rl_settings_section_callback() {
?>
  <p>Need help finding your ReachEdge Site ID?</p>
  <ol>
    <li>Sign into <a href="http://edge.reachlocal.com/">ReachEdge</a>.</li>
    <li>Navigate to Settings tab, and click on 'Tracking Code'.</li>
    <li>Copy the Tracking Code ID out of your tracking code snippet. It should look something like: d4098273-6c87-4672-9f5e-94bcabf5597a <strong>Note:</strong> Do not use the example tracking code id as it will not work properly.</li>
  </ol>
  <p>If you have difficulty with this step or cannot find your Tracking ID, please contact your ReachLocal account representative.</p>
<?php
}

function rl_tracking_code_id_render() { 
  echo '<input name="reachlocal_tracking_code_id" id="reachlocal_tracking_code_id" class="regular-text code" type="text" value="' . get_option('reachlocal_tracking_code_id') . '" />';
}

function rl_options_page() {
?>
  <form action='options.php' method='post'>
    
<?php
    settings_fields('rl_settings');
    settings_errors('general');
    do_settings_sections('rl_settings');
    submit_button();
?>
    
  </form>
<?php
}

function validate_tracking_code_id($guid) {
  if (empty($guid) || preg_match('/^[A-Z0-9]{8}(-[A-Z0-9]{4}){3}-[A-Z0-9]{12}$/i', $guid)) {
    return $guid;
  }

  add_settings_error(
    'general',
    'invalid-tracking_code_id',
    'Tracking code ID is invalid.',
    'error'
  );

  return get_option('reachlocal_tracking_code_id') ? get_option('reachlocal_tracking_code_id' ) : '';
}
