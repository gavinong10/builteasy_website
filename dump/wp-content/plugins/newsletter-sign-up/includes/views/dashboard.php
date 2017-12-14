<?php defined( 'ABSPATH' ) or exit; ?>
<div class="wrap" id="nsu-admin">

<?php include_once NSU_PLUGIN_DIR . 'includes/views/parts/navigation.php'; ?>

   <h2>Newsletter Sign-Up :: Mailinglist Settings</h2>
   <?php settings_errors(); ?>  
   
<div id="nsu-main">

    <form method="post" action="options.php">
        <?php settings_fields('nsu_mailinglist_group'); ?>

        <p>These settings are the most important, without them Newsletter Sign-Up can't do its job. Having trouble finding the right configuration settings? Have a look at <a href="https://dannyvankooten.com/wordpress-plugins/newsletter-sign-up/">this post on my blog</a> or try the <a href="admin.php?page=newsletter-sign-up/config-helper">configuration extractor</a>.</p>

        <table class="form-table">	
            <tr valign="top">
                    <th scope="row">Select your mailinglist provider: </th>
                    <td>
                        <select class="widefat" name="nsu_mailinglist[provider]" id="ns_mp_provider" onchange="document.location.href = 'admin.php?page=<?php echo $this->hook; ?>&mp=' + this.value">
                            <option value="other"<?php if ($viewed_mp == NULL || $viewed_mp == 'other')
                            echo ' SELECTED'; ?>>-- other / advanced</option>
                            <option value="mailchimp"<?php if ($viewed_mp == 'mailchimp')
                            echo ' SELECTED'; ?> >MailChimp</option>
                            <option value="ymlp"<?php if ($viewed_mp == 'ymlp')
                            echo ' SELECTED'; ?> >YMLP</option>
                            <option value="icontact"<?php if ($viewed_mp == 'icontact')
                            echo ' SELECTED'; ?> >iContact</option>
                            <option value="aweber"<?php if ($viewed_mp == 'aweber')
                            echo ' SELECTED'; ?> >Aweber</option>
                            <option value="phplist"<?php if ($viewed_mp == 'phplist')
                            echo ' SELECTED'; ?> >PHPList</option>
                        </select>
                    </td>
            </tr>

                <?php if(isset($viewed_mp) && file_exists(dirname(__FILE__) . '/parts/rows-' . $viewed_mp . '.php')) require 'parts/rows-' . $viewed_mp . '.php'; ?>

                <tbody class="form_rows"<?php if (isset($viewed_mp) && in_array($viewed_mp, array('mailchimp', 'ymlp')) && isset($opts['use_api']) && $opts['use_api'] == 1)
                echo ' style="display:none" '; ?>>
                <tr valign="top">
                    <th scope="row">Newsletter form action</th>
                    <td><input class="widefat" type="text" id="ns_form_action" name="nsu_mailinglist[form_action]" placeholder="Example: http://newsletter-service.com?action=subscribe&id=123" value="<?php echo esc_attr($opts['form_action']); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        E-mail identifier
                        <small class="help">name attribute of input field for the emailadress</small>
                    </th>
                    <td><input class="widefat" type="text" name="nsu_mailinglist[email_id]" placeholder="Example: EMAIL" value="<?php echo esc_attr($opts['email_id']); ?>"/></td>
                </tr>
            </tbody>
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="subscribe_with_name">Subscribe with name?</label></th>
                    <td><input type="checkbox" id="subscribe_with_name" name="nsu_mailinglist[subscribe_with_name]" value="1" <?php checked($opts['subscribe_with_name'], 1); ?> /></td>
                </tr>
                <tr class="name_dependent" valign="top" <?php if($opts['subscribe_with_name'] != 1) echo 'style="display:none;"'; ?>>
                    <th scope="row">
                        Name identifier
                        <small class="help">name attribute of input field that holds the name</small>
                    </th>
                    <td><input class="widefat" id="ns_name_id" type="text" name="nsu_mailinglist[name_id]" placeholder="Example: NAME" value="<?php echo esc_attr($opts['name_id']); ?>" /></td>
                </tr>
            </tbody>
        </table>
        <p>
            For some newsletter services you need to specify some additional fields, like a list ID or your account name. These fields are usually found as hidden fields in the HTML code of your sign-up forms.
            You can specify these additional fields here using name / value pairs, they will be included in all sign-up requests made by the plugin.
        </p>
        <table class="form-table">
            <tr valign="top" style="font-weight:bold;">
                <th scope="column">Name</th>
                <th scope="column">Value</th>
            </tr>
            <?php
            $last_key = 0;

            if (isset($opts['extra_data']) && is_array($opts['extra_data'])) :
                foreach ($opts['extra_data'] as $key => $value) :
                    ?>
                <tr valign="top">
                    <td><input class="widefat" type="text" name="nsu_mailinglist[extra_data][<?php echo esc_attr( $key ); ?>][name]" value="<?php echo esc_attr( $value['name'] ); ?>" /></td>
                    <td><input class="widefat" type="text" name="nsu_mailinglist[extra_data][<?php echo esc_attr( $key ); ?>][value]" value="<?php echo esc_attr( $value['value'] ); ?>" /></td>
                </tr>					
                <?php
                $last_key = $key + 1;
                endforeach;
                endif;
                ?>
                <tr valign="top">
                    <td><input class="widefat" type="text" name="nsu_mailinglist[extra_data][<?php echo esc_attr( $last_key ); ?>][name]" placeholder="Hidden field name" value="" /></td>
                    <td><input class="widefat" type="text" name="nsu_mailinglist[extra_data][<?php echo esc_attr( $last_key ); ?>][value]" placeholder="Hidden field value" value="" /></td>
                </tr>
            </table>
           
             <p class="help"><strong>Dynamic values:</strong> <code>{name}</code> and <code>{ip}</code> will be replaced by the name or IP address of the subscriber.</p>


            <?php submit_button(); ?>

        </form>
        <p>
            <em>Having trouble finding the right configuration settings? Try the <a href="<?php echo admin_url( 'admin.php?page=newsletter-sign-up-config-helper'); ?>">configuration extractor</a>.</em>
        </p>

    </div>


    <?php include_once NSU_PLUGIN_DIR . 'includes/views/parts/sidebar.php'; ?>

</div>
<br style="clear:both;" />