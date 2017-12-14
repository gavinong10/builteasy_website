<?php defined( 'ABSPATH' ) or exit; ?>
<tr valign="top">
	<th scope="row"><label for="aweber_list_name" Aweber list name</th>
	<td>
		<input id="aweber_list_name" class="widefat" type="text" name="nsu_mailinglist[aweber_list_name]" value="<?php if ( isset( $opts['aweber_list_name'] ) ) {
			echo esc_attr( $opts['aweber_list_name'] );
		} ?>" /></td>
</tr>