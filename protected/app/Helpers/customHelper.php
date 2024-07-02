<?php

if (!function_exists('generateReference')) {
    function generateReference($prefix) {
        return $prefix . '-' . date('ymdis');
    }
}