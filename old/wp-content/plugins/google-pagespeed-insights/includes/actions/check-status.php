<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

$options = get_option('gpagespeedi_options');

$current_status = $options['progress'];

if($current_status) {

    $split_status = explode(':', $current_status);

    $percent_complete = $split_status[0] / $split_status[1];
    $percent_complete = round($percent_complete * 100);

    echo $percent_complete;

}
