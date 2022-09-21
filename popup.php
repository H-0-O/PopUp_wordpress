<?php
/*
 * Plugin Name: پاپ اپ متصل به کریسپ و واتساپ
 * Author: حسین صالحی
 */
require_once __DIR__ . '/Admin_Page.php';
require_once __DIR__ . '/Client_Page.php';

add_action('init' , function(){

    if(is_admin())
    {
	    $admin_page = new Admin_Page();
    }else {
	    $client_page = new Client_Page();
    }
});

