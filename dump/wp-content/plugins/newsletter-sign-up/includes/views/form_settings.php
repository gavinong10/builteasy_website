<?php defined( 'ABSPATH' ) or exit; ?>
<div class="wrap" id="nsu-admin">

    <?php include_once 'parts/navigation.php'; ?>

    <div id="nsu-main">

        <h2>Newsletter Sign-Up :: Form Settings</h2>
        <?php settings_errors(); ?>  

        <p>Customize your newsletter sign-up form by customizing the labels, input fields, buttons and validation texts using the settings below. Use <code>[nsu_form]</code> to render a sign-up form in your posts or pages or use the <em>Newsletter Sign-Up</em> widget to display a sign-up form in your widget areas.</p>

        <form method="post" action="options.php">
            <?php settings_fields('nsu_form_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">E-mail label</th>
                    <td colspan="2"><input class="widefat" type="text" name="nsu_form[email_label]" value="<?php echo esc_attr($opts['email_label']); ?>" placeholder="Eg: Your email:" required /></td>
                </tr>
                <tr valign="top">
                   <th scope="row">E-mail default value</th>
                   <td colspan="2"><input class="widefat" type="text" name="nsu_form[email_default_value]" value="<?php echo esc_attr($opts['email_default_value']); ?>" /></td>
               </tr>
               <tr valign="top" class="name_dependent" <?php if($opts['mailinglist']['subscribe_with_name'] != 1) echo 'style="display:none;"'; ?>>
                <th scope="row">Name label</th>
                <td colspan="2">
                    <input class="widefat" type="text" name="nsu_form[name_label]" value="<?php echo esc_attr($opts['name_label']); ?>" /><br />
                    <p><input type="checkbox" id="name_required" name="nsu_form[name_required]" value="1" <?php checked($opts['name_required'], 1); ?> />
                    <label for="name_required">Name is a required field?</label></p>
                </td>
            </tr>
            <tr valign="top" class="name_dependent" <?php if($opts['mailinglist']['subscribe_with_name'] != 1) echo 'style="display:none;"'; ?>>
                <th scope="row">Name default value</th>
                <td colspan="2"><input class="widefat" type="text" name="nsu_form[name_default_value]" value="<?php echo esc_attr($opts['name_default_value']); ?>" /></td>

            </tr>
            <tr valign="top">
                <th scope="row">Submit button text</th>
                <td colspan="2"><input class="widefat" type="text" name="nsu_form[submit_button]" value="<?php echo esc_attr($opts['submit_button']); ?>" placeholder="Eg: Subscribe" required /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Text to replace the form with after a successful sign-up</th>
                <td colspan="2">
                    <textarea class="widefat" rows="5" cols="50" name="nsu_form[text_after_signup]"><?php echo esc_textarea($opts['text_after_signup']); ?></textarea>
                    <p><label><input id="nsu_form_wpautop" name="nsu_form[wpautop]" type="checkbox" value="1" <?php checked($opts['wpautop'], 1) ?> /> <?php _e('Automatically add paragraphs'); ?></label></p>
                </td>
            </tr>

            <?php if($opts['mailinglist']['use_api'] == 1) { ?>
            <tr valign="top">
                <th scope="row">Redirect to this url after signing up <small>(leave empty for no redirect)</small></th>
                <td colspan="2"><input class="widefat" type="text" name="nsu_form[redirect_to]" value="<?php echo $opts['redirect_to']; ?>" /></td>
            </tr>
            <?php } ?>

            <tr valign="top">
                <th scope="row"><label for="ns_load_form_styles">Load some default CSS</label></th>
                <td><input type="checkbox" id="ns_load_form_styles" name="nsu_form[load_form_css]" value="1" <?php if($opts['load_form_css'] == 1) echo 'checked'; ?> /></td>
                <td><small>Check this to load some default form CSS styles.</small></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="nsu_use_html_5">Use HTML 5?</label></th>
                <td><input type="checkbox" id="nsu_use_html_5" name="nsu_form[use_html5]" value="1" <?php checked($opts['use_html5'], 1); ?> /></td>
                <td><small>Use HTML5 fields and attributes? (recommended)</small></td>
            </tr>
        </table>
        
        <?php submit_button(); ?>

        <?php if($this->options['mailinglist']['use_api'] == 1) { ?>
            <h3>Form text messages</h3>
             <table class="form-table">
                <?php if($opts['mailinglist']['subscribe_with_name']) { ?>
                <tr valign="top">
                    <th scope="row">Empty name field message</th>
                    <td colspan="2"><input class="widefat" type="text" name="nsu_form[text_empty_name]" value="<?php echo esc_attr($opts['text_empty_name']); ?>" /></td>
                </tr>
                <?php } ?>
                <tr valign="top">
                    <th scope="row">Empty email address field message</th>
                    <td colspan="2"><input class="widefat" type="text" name="nsu_form[text_empty_email]" value="<?php echo esc_attr($opts['text_empty_email']); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Invalid email address message</th>
                    <td colspan="2"><input class="widefat" type="text" name="nsu_form[text_invalid_email]" value="<?php echo esc_attr($opts['text_invalid_email']); ?>" /></td>
                </tr>
            </table>
        <?php } ?>
    </form>
</div>

<?php require 'parts/sidebar.php'; ?>

</div>