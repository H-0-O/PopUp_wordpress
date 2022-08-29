<?php
class Client_Page
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts' , [$this , 'add_files']);
    }

    public function add_files()
    {
        wp_enqueue_style( 'bootstrap' , 'https://lib.arvancloud.com/bootstrap/5.1.3/css/bootstrap.min.css' , false , false ,'all');
        wp_enqueue_script('client_side' , plugin_dir_url(__FILE__).'/assets/js/client-side.js');
    }
    private function generate_html()
    {

    }
}