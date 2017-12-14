<?php defined( 'ABSPATH' ) or exit; ?>
<tr valign="top">
	<th colspan="2" style="background: lightskyblue; padding: 20px;">
		As you're using MailChimp, I highly recommend switching to my newer plugin:
		<a href="https://wordpress.org/plugins/mailchimp-for-wp/">MailChimp for WordPress</a>.
		You'll be up and running (again) in just a few minutes.
	</th>
</tr>

<tr valign="top">
	<th scope="row">
		<label for="use_api">Use MailChimp API?</label>
		<small class="help">(recommended)</small>
	</th>
	<td>
		<input type="checkbox" id="use_api" name="nsu_mailinglist[use_api]" value="1"<?php if ( isset( $opts['use_api'] ) && $opts['use_api'] == '1' ) {
			echo ' checked="checked"';
		} ?> /></td>
</tr>

<tbody class="api_rows" <?php if ( ! isset( $opts['use_api'] ) || $opts['use_api'] != 1 ) {
	echo ' style="display:none" ';
} ?>>
<tr valign="top">
	<th scope="row">MailChimp API Key <a target="_blank" href="https://admin.mailchimp.com/account/api">(?)</a></th>
	<td>
		<input class="widefat" type="text" id="mc_api_key" name="nsu_mailinglist[mc_api_key]" value="<?php if ( isset( $opts['mc_api_key'] ) ) {
			echo esc_attr( $opts['mc_api_key'] );
		} ?>" /></td>
</tr>
<tr valign="top">
	<th scope="row">MailChimp List ID
		<a href="http://www.mailchimp.com/kb/article/how-can-i-find-my-list-id" target="_blank">(?)</a></th>
	<td>
		<input class="widefat" type="text" name="nsu_mailinglist[mc_list_id]" value="<?php if ( isset( $opts['mc_list_id'] ) ) {
			echo esc_attr( $opts['mc_list_id'] );
		} ?>"; />
	</td>
</tr>
<tr valign="top">
	<th scope="row">
		<label title="Prevents your users from having to confirm their emailaddress. Make sure you comply with the CAN SPAM act." for="mc_prevent_double_optin">Prevent double opt-in?</label>
	</th>
	<td>
		<input type="checkbox" id="mc_prevent_double_optin" name="nsu_mailinglist[mc_no_double_optin]" value="1" <?php if ( isset( $opts['mc_no_double_optin'] ) ) {
			checked( $opts['mc_no_double_optin'], 1 );
		} ?> /></td>
</tr>
<tr valign="top">
	<th scope="row"><label for="mc_use_groupings">Add to group(s)? </label></th>
	<td>
		<input type="checkbox" id="mc_use_groupings" name="nsu_mailinglist[mc_use_groupings]" value="1" <?php if ( isset( $opts['mc_use_groupings'] ) ) {
			checked( $opts['mc_use_groupings'], 1 );
		} ?> /></td>
</tr>
<tbody class="mc_groupings_rows" <?php if ( ! isset( $opts['mc_use_groupings'] ) || $opts['mc_use_groupings'] != 1 ) {
	echo ' style="display:none" ';
} ?>>
<tr valign="top">
	<th scope="row">Grouping name</th>
	<td>
		<input class="widefat" type="text" id="mc_groupings_name" name="nsu_mailinglist[mc_groupings_name]" value="<?php if ( isset( $opts['mc_groupings_name'] ) ) {
			echo esc_attr( $opts['mc_groupings_name'] );
		} ?>" /></td>
</tr>
<tr valign="top">
	<th scope="row">
		Groups
		<small class="help">(comma delimited list of interest groups to add to)</small>
	</th>
	<td>
		<input class="widefat" type="text" name="nsu_mailinglist[mc_groupings_groups]" placeholder="Example: Group 1,Group 2,Group 3" value="<?php if ( isset( $opts['mc_groupings_groups'] ) ) {
			echo esc_attr( $opts['mc_groupings_groups'] );
		} ?>"; />
	</td>
</tr>
</tbody>
</tbody>