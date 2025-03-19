<?php

if (!defined('APP_URL')) {
    define('APP_URL', 'http://localhost/lakshmi-agro');
}

if (!defined('ASSET_PATH')) {
    define('ASSET_PATH', APP_URL . '/public');
}

if (!defined('PAYMENT_TYPE')) {
    define('PAYMENT_TYPE', [
        1 => 'UPI',
        2 => 'CASH',
        3 => 'NOT PAID'
    ]);
}
