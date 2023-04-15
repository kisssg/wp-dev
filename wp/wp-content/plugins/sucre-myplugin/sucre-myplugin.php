<?php
/**
 * @package Sucre_myplugin
 * @version 1.0.0
 */
/*
Plugin Name: Sucre my plugin
Plugin URI: http://wordpress.org/plugins/sucre-myplugin/
Description: This is just another plugin.
Author: Sucre Xu
Version: 1.0.0
Author URI: http://google.com
*/

// exit if file is called directly
if( !defined('ABSPATH')){
    exit;
}

//if admin area

if( is_admin()){
    //include dependencies
    require_once plugin_dir_path(__FILE__) . 'admin/admin-menu.php';
    require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
    require_once plugin_dir_path(__FILE__) . 'admin/settings-register.php';
    require_once plugin_dir_path(__FILE__) . 'admin/settings-callbacks.php';
}

// default plugin options
function myplugin_options_default(){
    return array(
        'custom_url' => 'https://google.com',
        'custom_title' => 'Powered by WordPress',
        'custom_style' => 'disable',
        'custom_message' => '<p class="custom-message">My custom message</p>',
        'custom_footer' => 'Special message for users',
        'custom_toolbar' => false,
        'custom_scheme' => 'default',
    );
}
