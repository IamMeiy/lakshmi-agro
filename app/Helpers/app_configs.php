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

if (!defined('SHOP_NAME')) {
    define('SHOP_NAME', 'LAKSHMI AGRO');
}

if (!defined('SHOP_ADDRESS')) {
    define('SHOP_ADDRESS', 'Kottayur, Mottangaadu, Sankagiri');
}

if (!defined('SHOP_PINCODE')) {
    define('SHOP_PINCODE', '637 104');
}

if (!defined('SHOP_PHONE')) {
    define('SHOP_PHONE', '90258 04086');
}

if (!defined('SHOP_EMAIL')) {
    define('SHOP_EMAIL', 'vikramperumal0747@gmail.com');
}

if (!defined('GSTIN')) {
    define('GSTIN', '33AENPE1845G1ZQ');
}
