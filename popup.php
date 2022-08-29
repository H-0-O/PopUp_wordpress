<?php
/*
 * Plugin Name: پاپ اپ متصل به کریسپ و واتساپ
 * Author: حسین صالحی
 */
require_once __DIR__ . '/Admin_Page.php';
require_once __DIR__.'/client.php';

add_action('admin_menu' , function(){
	$admin_page = new Admin_Page();
});