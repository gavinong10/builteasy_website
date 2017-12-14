<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db

$option_values = array(
	'01'=>__('01-Jan', 'seed_ucp'),
	'02'=>__('02-Feb', 'seed_ucp'),
	'03'=>__('03-Mar', 'seed_ucp'),
	'04'=>__('04-Apr', 'seed_ucp'),
	'05'=>__('05-May', 'seed_ucp'),
	'06'=>__('06-Jun', 'seed_ucp'),
	'07'=>__('07-Jul', 'seed_ucp'),
	'08'=>__('08-Aug', 'seed_ucp'),
	'09'=>__('09-Sep', 'seed_ucp'),
	'10'=>__('10-Oct', 'seed_ucp'),
	'11'=>__('11-Nov', 'seed_ucp'),
	'12'=>__('12-Dec', 'seed_ucp'),
	);

_e('Start Date', 'seed_ucp');
echo "<select id='mm' name='{$setting_id}[$id][start_month]'>";
foreach ( $option_values as $k => $v ) {
    echo "<option value='$k' " . selected( $options[ $id ]['start_month'], $k, false ) . ">$v</option>";
}
echo "</select>";

echo "<input id='jj' class='small-text' placeholder='".__('day', 'seed_ucp')."' name='{$setting_id}[$id][start_day]' type='text' value='" . esc_attr( $options[ $id ]['start_day'] ) . "' />";

echo ',';
echo "<input id='aa' class='small-text' placeholder='".__('year', 'seed_ucp')."' name='{$setting_id}[$id][start_year]' type='text' value='" . esc_attr( $options[ $id ]['start_year'] ) . "' />";

echo '&nbsp;&nbsp;&nbsp;&nbsp;';
_e('End Date', 'seed_ucp');
echo "<select id='mm' name='{$setting_id}[$id][end_month]'>";
foreach ( $option_values as $k => $v ) {
    echo "<option value='$k' " . selected( $options[ $id ]['end_month'], $k, false ) . ">$v</option>";
}
echo "</select>";

echo "<input id='jj' class='small-text' placeholder='".__('day', 'seed_ucp')."' name='{$setting_id}[$id][end_day]' type='text' value='" . esc_attr( $options[ $id ]['end_day'] ) . "' />";

echo ',';
echo "<input id='aa' class='small-text' placeholder='".__('year', 'seed_ucp')."' name='{$setting_id}[$id][end_year]' type='text' value='" . esc_attr( $options[ $id ]['end_year'] ) . "' /><br>";

