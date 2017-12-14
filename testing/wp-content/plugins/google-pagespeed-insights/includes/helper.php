<?php

// Some sorting functions to organize the data
function cmpfloat($a, $b) {
    if (abs($a-$b) < 0.00000001) {
        return 0; // almost equal
    } else if (($a-$b) < 0) {   
        return -1;
    } else {
        return 1;
    }
}
function cmpfloat_scores($a, $b) {
    if (abs($a['score']-$b['score']) < 0.00000001) {
        return 0; // almost equal
    } else if (($a['score']-$b['score']) < 0) {
        return -1;
    } else {
        return 1;
    }
}
function cmpfloat_rules($a, $b) {
    if (abs($b['total_impact']-$a['total_impact']) < 0.00000001) {
        return 0; // almost equal
    } else if (($b['total_impact']-$a['total_impact']) < 0) {
        return -1;
    } else {
        return 1;
    }
}
function cmpfloat_impact($a, $b) {
    if (abs($a['rule_impact']-$b['rule_impact']) < 0.00000001) {
        return 0; // almost equal
    } else if (($a['rule_impact']-$b['rule_impact']) < 0) {
        return -1;
    } else {
        return 1;
    }
}