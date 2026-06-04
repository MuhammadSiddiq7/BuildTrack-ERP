<?php

if (!function_exists('formatDateToCustom')) {
    function formatDateToCustom($date) {
        return $date ? date('d-m-Y', strtotime($date)) : '';
    }
}
