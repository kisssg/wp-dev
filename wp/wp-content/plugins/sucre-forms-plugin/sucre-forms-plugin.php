<?php
/**
 * @package Sucre_forms
 * @version 1.7.2
 */
/*
Plugin Name: Sucre forms
Plugin URI: http://wordpress.org/plugins/sucre-forms/
Description: This is just another plugin for forms creating
Author: Sucre Xu
Version: 1.0.0
Author URI: http://google.com
*/

/*
 * Activation
 */
function myplugin_on_activation(){
    if(! current_user_can('activate_plugins')) return ;
    add_option('myplugin_posts_per_page',10);
    add_option('myplugin_show_welcome_page',true);
}

register_activation_hook(__FILE__,'myplugin_on_activation');

/*
 * Deactivation
 */
function myplugin_on_deactivation(){
        if(! current_user_can('activate_plugins')) return ;     
        flush_rewrite_rules();   
        // delete_option('myplugin_posts_per_page');        
        // delete_option('myplugin_show_welcome_page');
    }
register_deactivation_hook(__FILE__,'myplugin_on_deactivation');

/*
 * Unistall
 */
function  myplugin_on_uninstall(){
    if(! current_user_can('activate_plugins')) return ;
    delete_option('myplugin_posts_per_page');
    delete_option('myplugin_show_welcome_page');
}

register_uninstall_hook(__FILE__,'myplugin_on_uninstall');