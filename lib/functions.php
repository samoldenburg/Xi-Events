<?php

function xi_date_cmp_sort($a, $b) {
    if ($a->start_timestamp == $b->start_timestamp) {
        return 0;
    }
    return ($a->start_timestamp < $b->start_timestamp) ? -1 : 1;
}
