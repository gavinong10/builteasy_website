<?php
/*
Plugin Name: Crazyegg-Heatmap-Tracking
Plugin URI: http://www.crazyegg.com/
Description: Enables Crazyegg.com heatmap tracking on your WordPress site.
Version: 1.1
Author: Crazyegg
Author URI: http://www.crazyegg.com
License: GPL
*/

/* Puts code on Wordpress pages */
add_action('wp_footer', 'crazyegg_tracking_code');

/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'cht_install');

/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'cht_remove' );

if (is_admin()) {
  /* Call the html code */
  add_action('admin_menu', 'cht_admin_menu');

  function cht_admin_menu() {
    add_options_page('Crazyegg Heatmap Tracking', 'Crazyegg Heatmap Tracking', 'administrator', 'crazyegg-heatmap-tracking', 'cht_html_page');
  }
}

function cht_install() {
  /* Creates new database field */
  add_option("cht_account_number", '', '', 'yes');
}

function cht_remove() {
  /* Deletes the database field */
  delete_option('cht_account_number');
}

function cht_html_page() {
?>
<div class="wrap">
  <div id="icon-plugins" class="icon32"></div>
  <h2>Crazyegg Heatmap Tracking</h2>
  <form method="POST" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="cht_account_number">Account Number</label>
        </th>
        <td>
          <input id="cht_account_number" name="cht_account_number" value="<?php echo get_option('cht_account_number'); ?>" class="regular-text" />
          <span class="description">(ex. 00111111)</span>
        </td>
      </tr>
    </table>
    <p style="width: 80%;">This is your numerical CrazyEgg account ID, it is 8 digits long. The easy way to find it is by logging in to your CrazyEgg account and clicking the "What's my code" link located at the top of your Dashboard.</p>
    <p style="width: 80%;">Or it would be shown to you immediately after creating a Snapshot on your Dashboard.</p>
    <p><a href="http://www.crazyegg.com" target="_blank">http://www.crazyegg.com</a></p>
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="cht_account_number" />
    <p class="submit">
      <input class="button-primary" type="submit" name="Save" value="<?php _e('Save'); ?>" />
    </p>
  </form>
</div>
<?php
}

function crazyegg_tracking_code() {
  $account_number = get_option("cht_account_number");
  if (!empty($account_number)) {

    $account_path = str_pad($account_number, 8, "0", STR_PAD_LEFT);
    $account_path = substr($account_path,0,4).'/'.substr($account_path,4,8);
    $account_path = "pages/scripts/".$account_path.".js";

    $script_host = "script.crazyegg.com";

    echo '<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName(\'script\')[0];
a.src=document.location.protocol+"//'.$script_host.'/'.$account_path.'";
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>
';
  }
}
?>
