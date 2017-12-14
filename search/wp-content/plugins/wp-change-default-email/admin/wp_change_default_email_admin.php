<?php
/*
Filename: wp_change_default_email_admin.php
Description: Adds the admin pages for WP Change Default Email Plugin
License: GPLv2
Author: Vijay Sharma
Author URI: http://www.techeach.com/
*/

function wp_change_default_email_admin() {
	add_options_page('WP Change Default Email Options', 'WP Change Default Email','manage_options', 
			  __FILE__, 'wp_change_default_email_page');
}

function wp_change_default_email_page() {
	//global $wcdeOptions;
	if ( isset($_POST['wp_change_default_email_update'])) {
	    $wcdeOptions = array();
	    $wcdeOptions["from"] = trim($_POST["wp_change_default_email_from"]);
	    $wcdeOptions["fromname"] = trim($_POST['wp_change_default_email_fromname']);
        $wcdeOptions['deactivate'] = isset($_POST['wp_change_default_email_deactivate']) ? 1 : 0;

	    update_option("wp_change_default_email_options",$wcdeOptions);
	    if(!is_email($wcdeOptions["from"])){
			echo '<div id="message" class="updated error fade"><p><strong>' . __("The field \"From\" must be a valid email address!") . '</strong></p></div>';
	    } else{
			echo '<div id="message" class="updated fade"><p><strong>' . __("Options saved.", 'wp-change-default-email') . '</strong></p></div>';
   	    }
	}
?>
<div class="wrap">
	
<h2><?php _e("WP Change Default Email", 'wp-change-default-email'); ?></h2>

    <?php
        //Load the Options before loading the form
        $wcdeOptions = get_option('wp_change_default_email_options');
    ?>
<form action="" method="post" enctype="multipart/form-data" name="wp_change_default_email_form">
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<?php _e('From','wp-change-default-email'); ?>
		</th>
		<td>
			<label>
				<input type="text" name="wp_change_default_email_from" value="<?php echo $wcdeOptions["from"]; ?>" size="43" style="width:272px;height:24px;" />
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('From Name', 'wp-change-default-email'); ?>
		</th>
		<td>
			<label>
				<input type="text" name="wp_change_default_email_fromname" value="<?php echo $wcdeOptions["fromname"]; ?>" size="43" style="width:272px;height:24px;" />
			</label>
		</td>
	</tr>
    <tr valign="top">
        <th scope="row">
            <?php _e('Delete Options at Uninstall', 'wp-change-default-email'); ?>
        </th>
        <td>
            <label>
                <input type="checkbox" name="wp_change_default_email_deactivate" <?php if($wcdeOptions["deactivate"]) echo 'checked'; ?> />
            </label>
        </td>
    </tr>
</table>

<p class="submit">
<input type="hidden" name="wp_change_default_email_update" value="update" />
<input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes', 'wp-change-default-email'); ?>" />
</p>

</form>

<br />
<h2><?php __('Donate','wp-change-default-email'); ?></h2>
<p>
<?php __('If you find my work useful and you want to encourage the development of more free resources, you can do it by donating.','wp-change-default-email'); ?>
</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="JKWDG57E8SCMA">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<br />

</div>
<?php 
}
add_action('admin_menu', 'wp_change_default_email_admin');
