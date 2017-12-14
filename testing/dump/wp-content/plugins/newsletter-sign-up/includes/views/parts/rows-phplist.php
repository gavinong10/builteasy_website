<?php defined( 'ABSPATH' ) or exit; ?>
<?php if ( ! isset( $opts['phplist_list_id'] ) ) {
	$opts['phplist_list_id'] = 1;
} ?>
<tr valign="top">
	<th scope="row">PHPList list ID</th>
	<td>
		<input type="text" class="widefat" name="nsu_mailinglist[phplist_list_id]" value="<?php echo esc_attr( $opts['phplist_list_id'] ); ?>" />
	</td>
</tr>