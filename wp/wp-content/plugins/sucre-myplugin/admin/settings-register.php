<?php // MyPlugin -  Register Settings

// exit if file is called directly
if( !defined('ABSPATH')){
    exit;
}
// register plugin settings
function myplugin_register_settings(){
    /*
    register_setting(
        string $option_group,
        string $option_name,
        callable $sanitize_callback
    );
    */
    
    register_setting(
        'myplugin_options',
        'myplugin_options',
        'myplugin_callback_validate_options'
    );

    /*
    add_settings_section(
        string $id,
        string $title,
        callable $callback,
        string $page
    );
    */

    add_settings_section(
        'myplugin_section_login',
        'Customize Login Page',
        'myplugin_callback_section_login',
        'myplugin'
    );

    add_settings_section(
        'myplugin_section_admin',
        'Customize Admin Area',
        'myplugin_callback_section_admin',
        'myplugin'
    );

    /*
    add_settings_field(
        string $id,
        string $title,
        string $callback,
        string $page,
        string $section = 'default',
        array $args = array()
        );
        */

    add_settings_field(
        'custom_url',
        'Custom URL',
        'myplugin_callbacke_field_text',
        'myplugin',
        'myplugin_section_login',
        ['id'=>'custom_url','label'=>'Custom URL for the login logo link']
    );
    
    add_settings_field(
        'custom_title',
        'Custom Title',
        'myplugin_callbacke_field_text',
        'myplugin',
        'myplugin_section_login',
        ['id'=>'custom_title','label'=>'Custom Title for the logo link']
        );
    
    add_settings_field(
        'custom_style',
        'Custom Style',
        'myplugin_callbacke_field_radio',
        'myplugin',
        'myplugin_section_login',
        ['id'=>'custom_style','label'=>'Custom CSS Style for the Login screen']
    );

    add_settings_field(
        'custom_message',
        'Custom Message',
        'myplugin_callbacke_field_textarea',
        'myplugin',
        'myplugin_section_login',
        ['id'=>'custom_message','label'=>'Custom text and/or markup']
    );

    add_settings_field(
        'custom_footer',
        'Custom Footer',
        'myplugin_callbacke_field_text',
        'myplugin',
        'myplugin_section_admin',
        ['id'=>'custom_footer','label'=>'Custom Footer text']
    );

    add_settings_field(
        'custom_toolbar',
        'Custom Toolbar',
        'myplugin_callbacke_field_checkbox',
        'myplugin',
        'myplugin_section_admin',
        ['id'=>'custom_toolbar','label'=>'Remove new post and comment links from the Toolbar']
    );
    
    add_settings_field(
        'custom_scheme',
        'Custom Scheme',
        'myplugin_callbacke_field_select',
        'myplugin',
        'myplugin_section_admin',
        ['id'=>'custom_scheme','label'=>'default color scheme for new users']
    );
}

add_action('admin_init', 'myplugin_register_settings');
