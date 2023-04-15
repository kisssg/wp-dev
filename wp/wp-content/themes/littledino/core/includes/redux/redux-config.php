<?php

if (!class_exists('LittleDino_Core')) {
    return;
}

if (!function_exists('wgl_get_redux_icons')) {
    function wgl_get_redux_icons()
    {
        return WglAdminIcon()->get_icons_name(true);
    }

    add_filter('redux/font-icons', 'wgl_get_redux_icons');
}

if (!function_exists('littledino_get_preset')) {
    function littledino_get_preset()
    {
        $custom_preset = get_option('littledino_set_preset');
        $presets = function_exists('littledino_default_preset') ? littledino_default_preset() : '';

        $out = [];
        $i = 1;
        if (is_array($presets)) {
            foreach ($presets as $key => $value) {
                if ($key != 'img') {
                    $out[$key] = $key;
                    $i++;
                }
            }
        }
        if (is_array($custom_preset)) {
            foreach ($custom_preset as $preset_id => $preset) :
                if ($preset_id != 'default' && $preset_id != 'img') {
                    $out[$preset_id] = $preset_id;
                }
            endforeach;
        }
        return $out;
    }
}

//* This is theme option name where all the Redux data is stored.
$theme_slug = 'littledino_set';

/**
 * Set all the possible arguments for Redux
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */
$theme = wp_get_theme();

Redux::setArgs($theme_slug, [
    'opt_name' => $theme_slug, //* This is where your data is stored in the database and also becomes your global variable name.
    'display_name' => $theme->get('Name'), //* Name that appears at the top of your panel
    'display_version' => $theme->get('Version'), //* Version that appears at the top of your panel
    'menu_type' => 'menu', //* Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu' => true, //* Show the sections below the admin menu item or not
    'menu_title' => esc_html__('Theme Options', 'littledino'),
    'page_title' => esc_html__('Theme Options', 'littledino'),
    'google_api_key' => '', //* You will need to generate a Google API key to use this feature. Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_update_weekly' => false, //* Set it you want google fonts to update weekly. A google_api_key value is required.
    'async_typography' => true, //* Must be defined to add google fonts to the typography module
    'admin_bar' => true, //* Show the panel pages on the admin bar
    'admin_bar_icon' => 'dashicons-admin-generic', //* Choose an icon for the admin bar menu
    'admin_bar_priority' => 50, //* Choose an priority for the admin bar menu
    'global_variable' => '', //* Set a different name for your global variable other than the opt_name
    'dev_mode' => false,
    'update_notice' => true, //* If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer' => true,
    'page_priority' => 3, //* Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent' => 'wgl-dashboard-panel', //* For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions' => 'manage_options', //* Permissions needed to access the options panel.
    'menu_icon' => 'dashicons-admin-generic', //* Specify a custom URL to an icon
    'last_tab' => '', //* Force your panel to always open to a specific tab (by id)
    'page_icon' => 'icon-themes', //* Icon displayed in the admin panel next to your menu_title
    'page_slug' => 'wgl-theme-options-panel', //* Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults' => true, //* On load save the defaults to DB before user clicks save or not
    'default_show' => false, //* If true, shows the default value next to each field that is not the default value.
    'default_mark' => '', //* What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export' => true, //* Shows the Import/Export panel when not used as a field.
    'transient_time' => 60 * MINUTE_IN_SECONDS, //* Show the time the page took to load, etc
    'output' => true, //* Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag' => true, //* FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database' => '', //* possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'use_cdn' => true,
]);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'general',
        'title' => esc_html__('General', 'littledino'),
        'icon' => 'el el-screen',
        'fields' => [
            [
                'id' => 'use_minified',
                'title' => esc_html__('Use minified css/js files', 'littledino'),
                'type' => 'switch',
                'desc' => esc_html__('Speed up your site load.', 'littledino'),
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
            ],
            [
                'id' => 'preloder_settings',
                'title' => esc_html__('Preloader', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'preloader',
                'title' => esc_html__('Preloader', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'preloader_background',
                'title' => esc_html__('Preloader Background', 'littledino'),
                'type' => 'color',
                'required' => ['preloader', '=', '1'],
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'preloader_settings-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'search_settings',
                'type' => 'section',
                'title' => esc_html__('Search', 'littledino'),
                'indent' => true,
            ],
            [
                'id' => 'search_style',
                'title' => esc_html__('Choose search style', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'standard' => esc_html__('Standard', 'littledino'),
                    'alt' => esc_html__('Full Page Width', 'littledino'),
                ],
                'default' => 'standard',
            ],
            [
                'id' => 'search_post_type',
                'title' => esc_html__('Search Post Types', 'littledino'),
                'type' => 'multi_text',
                'validate' => 'no_html',
                'add_text' => esc_html__('Add Post Type', 'littledino'),
                'default' => [],
            ],
            [
                'id' => 'search_settings-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'scroll_up_settings',
                'title' => esc_html__('Scroll Up Button', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'scroll_up',
                'title' => esc_html__('Button', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Disable', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'scroll_up_arrow_color',
                'title' => esc_html__('Arrow Color', 'littledino'),
                'type' => 'color',
                'required' => ['scroll_up', '=', true],
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'scroll_up_bg_color',
                'title' => esc_html__('Background Color', 'littledino'),
                'type' => 'color',
                'required' => ['scroll_up', '=', true],
                'transparent' => false,
                'default' => '#ffc85b',
            ],
            [
                'id' => 'scroll_up_settings-end',
                'type' => 'section',
                'indent' => false,
            ],
        ],
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'editors-option',
        'title' => esc_html__('Custom JS', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'custom_js',
                'title' => esc_html__('Custom JS', 'littledino'),
                'type' => 'ace_editor',
                'subtitle' => esc_html__('Paste your JS code here.', 'littledino'),
                'mode' => 'javascript',
                'theme' => 'chrome',
                'default' => ''
            ],
            [
                'id' => 'header_custom_js',
                'title' => esc_html__('Custom JS', 'littledino'),
                'type' => 'ace_editor',
                'subtitle' => esc_html__('Code to be added inside HEAD tag', 'littledino'),
                'mode' => 'html',
                'theme' => 'chrome',
                'default' => ''
            ],
        ],
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'header_section',
        'title' => esc_html__('Header', 'littledino'),
        'icon' => 'fa fa-window-maximize',
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'logo',
        'title' => esc_html__('Logo', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'header_logo',
                'title' => esc_html__('Header Logo', 'littledino'),
                'type' => 'media',
            ],
            [
                'id' => 'logo_height_custom',
                'title' => esc_html__('Enable Logo Height', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'logo_height',
                'title' => esc_html__('Set Logo Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['logo_height_custom', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'logo_sticky',
                'title' => esc_html__('Sticky Logo', 'littledino'),
                'type' => 'media',
            ],
            [
                'id' => 'sticky_logo_height_custom',
                'title' => esc_html__('Enable Sticky Logo Height', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'sticky_logo_height',
                'title' => esc_html__('Set Sticky Logo Height', 'littledino'),
                'type' => 'dimensions',
                'height' => true,
                'width' => false,
                'default' => ['height' => ''],
                'required' => [
                    ['sticky_logo_height_custom', '=', '1'],
                ],
            ],
            [
                'id' => 'logo_mobile',
                'title' => esc_html__('Mobile Logo', 'littledino'),
                'type' => 'media',
            ],
            [
                'id' => 'mobile_logo_height_custom',
                'title' => esc_html__('Enable Mobile Logo Height', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'mobile_logo_height',
                'title' => esc_html__('Set Mobile Logo Height', 'littledino'),
                'type' => 'dimensions',
                'height' => true,
                'width' => false,
                'default' => ['height' => ''],
                'required' => [
                    ['mobile_logo_height_custom', '=', '1'],
                ],
            ],
            [
                'id' => 'logo_mobile_menu',
                'title' => esc_html__('Mobile Menu Logo', 'littledino'),
                'type' => 'media',
            ],
            [
                'id' => 'mobile_logo_menu_height_custom',
                'title' => esc_html__('Enable Mobile Menu Logo Height', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'mobile_logo_menu_height',
                'title' => esc_html__('Set Mobile Menu Logo Height', 'littledino'),
                'type' => 'dimensions',
                'height' => true,
                'width' => false,
                'default' => ['height' => ''],
                'required' => [
                    ['mobile_logo_menu_height_custom', '=', '1'],
                ],
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'title' => esc_html__('Header Builder', 'littledino'),
        'id' => 'header-customize',
        'subsection' => true,
        'fields' => [
            [
                'id' => 'header_def_js_preset',
                'title' => esc_html__('Header default preset', 'littledino'),
                'type' => 'select',
                'desc' => esc_html__('Please choose template to use it for all Pages by default. You can change it for a specific page - the corresponding setting is available in every Page\'s metabox options.', 'littledino'),
                'select2' => ['allowClear' => false],
                'options' => littledino_get_preset(),
                'default' => '',
            ],
            [
                'id' => 'opt-js-preset',
                'title' => esc_html__('Custom Preset', 'littledino'),
                'type' => 'custom_preset',
            ],
            [
                'id' => 'bottom_header_layout',
                'type' => 'custom_header_builder',
                'title' => esc_html__('Header Builder', 'littledino'),
                'compiler' => 'true',
                'full_width' => true,
                'options' => [
                    'items' => [
                        'html1' => ['title' => esc_html__('HTML 1', 'littledino'), 'settings' => true],
                        'html2' => ['title' => esc_html__('HTML 2', 'littledino'), 'settings' => true],
                        'html3' => ['title' => esc_html__('HTML 3', 'littledino'), 'settings' => true],
                        'html4' => ['title' => esc_html__('HTML 4', 'littledino'), 'settings' => true],
                        'html5' => ['title' => esc_html__('HTML 5', 'littledino'), 'settings' => true],
                        'html6' => ['title' => esc_html__('HTML 6', 'littledino'), 'settings' => true],
                        'html7' => ['title' => esc_html__('HTML 7', 'littledino'), 'settings' => true],
                        'html8' => ['title' => esc_html__('HTML 8', 'littledino'), 'settings' => true],
                        'wpml' => ['title' => esc_html__('WPML', 'littledino'), 'settings' => false],
                        'delimiter1' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter2' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter3' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter4' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter5' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter6' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'spacer3' => ['title' => esc_html__('Spacer 3', 'littledino'), 'settings' => true],
                        'spacer4' => ['title' => esc_html__('Spacer 4', 'littledino'), 'settings' => true],
                        'spacer5' => ['title' => esc_html__('Spacer 5', 'littledino'), 'settings' => true],
                        'spacer6' => ['title' => esc_html__('Spacer 6', 'littledino'), 'settings' => true],
                        'spacer7' => ['title' => esc_html__('Spacer 7', 'littledino'), 'settings' => true],
                        'spacer8' => ['title' => esc_html__('Spacer 8', 'littledino'), 'settings' => true],
                        'button1' => ['title' => esc_html__('Button', 'littledino'), 'settings' => true],
                        'button2' => ['title' => esc_html__('Button', 'littledino'), 'settings' => true],
                        'cart' => ['title' => esc_html__('Cart', 'littledino'), 'settings' => false],
                        'login' => ['title' => esc_html__('Login', 'littledino'), 'settings' => false],
                        'wishlist' => ['title' => esc_html__('Wishlist', 'littledino'), 'settings' => false],
                        'side_panel' => ['title' => esc_html__('Side Panel', 'littledino'), 'settings' => true],
                    ],
                    'Top Left area' => [],
                    'Top Center area' => [],
                    'Top Right area' => [],
                    'Middle Left area' => [
                        'spacer2' => ['title' => esc_html__('Spacer 2', 'littledino'), 'settings' => true],
                        'logo' => ['title' => esc_html__('Logo', 'littledino'), 'settings' => false],
                    ],
                    'Middle Center area' => [
                        'menu' => ['title' => esc_html__('Menu', 'littledino'), 'settings' => false],
                    ],
                    'Middle Right area' => [
                        'item_search' => ['title' => esc_html__('Search', 'littledino'), 'settings' => false],
                        'spacer1' => ['title' => esc_html__('Spacer 1', 'littledino'), 'settings' => true],
                    ],
                    'Bottom Left  area' => [],
                    'Bottom Center area' => [],
                    'Bottom Right area' => [],
                ],
                'default' => [
                    'items' => [
                        'html1' => ['title' => esc_html__('HTML 1', 'littledino'), 'settings' => true],
                        'html2' => ['title' => esc_html__('HTML 2', 'littledino'), 'settings' => true],
                        'html3' => ['title' => esc_html__('HTML 3', 'littledino'), 'settings' => true],
                        'html4' => ['title' => esc_html__('HTML 4', 'littledino'), 'settings' => true],
                        'html5' => ['title' => esc_html__('HTML 5', 'littledino'), 'settings' => true],
                        'html6' => ['title' => esc_html__('HTML 6', 'littledino'), 'settings' => true],
                        'html7' => ['title' => esc_html__('HTML 7', 'littledino'), 'settings' => true],
                        'html8' => ['title' => esc_html__('HTML 8', 'littledino'), 'settings' => true],
                        'wpml' => ['title' => esc_html__('WPML', 'littledino'), 'settings' => false],
                        'delimiter1' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter2' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter3' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter4' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter5' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'delimiter6' => ['title' => esc_html__('|', 'littledino'), 'settings' => true],
                        'spacer3' => ['title' => esc_html__('Spacer 3', 'littledino'), 'settings' => true],
                        'spacer4' => ['title' => esc_html__('Spacer 4', 'littledino'), 'settings' => true],
                        'spacer5' => ['title' => esc_html__('Spacer 5', 'littledino'), 'settings' => true],
                        'spacer6' => ['title' => esc_html__('Spacer 6', 'littledino'), 'settings' => true],
                        'spacer7' => ['title' => esc_html__('Spacer 7', 'littledino'), 'settings' => true],
                        'spacer8' => ['title' => esc_html__('Spacer 8', 'littledino'), 'settings' => true],
                        'button1' => ['title' => esc_html__('Button', 'littledino'), 'settings' => true],
                        'button2' => ['title' => esc_html__('Button', 'littledino'), 'settings' => true],
                        'cart' => ['title' => esc_html__('Cart', 'littledino'), 'settings' => false],
                        'login' => ['title' => esc_html__('Login', 'littledino'), 'settings' => false],
                        'wishlist' => ['title' => esc_html__('Wishlist', 'littledino'), 'settings' => false],
                        'side_panel' => ['title' => esc_html__('Side Panel', 'littledino'), 'settings' => true],
                    ],
                    'Top Left area' => [],
                    'Top Center area' => [],
                    'Top Right  area' => [],
                    'Middle Left  area' => [
                        'spacer2' => ['title' => esc_html__('Spacer 2', 'littledino'), 'settings' => true],
                        'logo' => ['title' => esc_html__('Logo', 'littledino'), 'settings' => false],
                    ],
                    'Middle Center  area' => [
                        'menu' => ['title' => esc_html__('Menu', 'littledino'), 'settings' => false],
                    ],
                    'Middle Right  area' => [
                        'item_search' => ['title' => esc_html__('Search', 'littledino'), 'settings' => false],
                        'spacer1' => ['title' => esc_html__('Spacer 1', 'littledino'), 'settings' => true],
                    ],
                    'Bottom Left area' => [],
                    'Bottom Center area' => [],
                    'Bottom Right area' => [],
                ],
            ],
            [
                'id' => 'bottom_header_spacer1',
                'title' => esc_html__('Header Spacer 1 Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 30],
            ],
            [
                'id' => 'bottom_header_spacer2',
                'title' => esc_html__('Header Spacer 2 Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 30],
            ],
            [
                'id' => 'bottom_header_spacer3',
                'title' => esc_html__('Header Spacer 3 Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer4',
                'title' => esc_html__('Header Spacer 4 Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer5',
                'title' => esc_html__('Header Spacer 5 Width', 'littledino'),
                'type' => 'dimensions',
                'height' => false,
                'width' => true,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer6',
                'title' => esc_html__('Header Spacer 6 Width', 'littledino'),
                'type' => 'dimensions',
                'height' => false,
                'width' => true,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer7',
                'title' => esc_html__('Header Spacer 7 Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer8',
                'title' => esc_html__('Header Spacer 8 Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_delimiter1_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter1_width',
                'title' => esc_html__('Delimiter Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter1_bg',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter1_margin',
                'title' => esc_html__('Delimiter Spacing', 'littledino'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ]
            ],
            [
                'id' => 'bottom_header_delimiter1_sticky_custom',
                'title' => esc_html__('Customize Sticky Delimiter', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_delimiter1_sticky_color',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_delimiter1_sticky_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter1_sticky_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['bottom_header_delimiter1_sticky_custom', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter2_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter2_width',
                'title' => esc_html__('Delimiter Width', 'littledino'),
                'type' => 'dimensions',
                'height' => false,
                'width' => true,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter2_bg',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter2_margin',
                'title' => esc_html__('Delimiter Spacing', 'littledino'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter2_sticky_custom',
                'title' => esc_html__('Customize Sticky Delimiter', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_delimiter2_sticky_color',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_delimiter2_sticky_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter2_sticky_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['bottom_header_delimiter2_sticky_custom', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],

            [
                'id' => 'bottom_header_delimiter3_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter3_width',
                'title' => esc_html__('Delimiter Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter3_bg',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter3_margin',
                'title' => esc_html__('Delimiter Spacing', 'littledino'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter3_sticky_custom',
                'title' => esc_html__('Customize Sticky Delimiter', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_delimiter3_sticky_color',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_delimiter3_sticky_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter3_sticky_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['bottom_header_delimiter3_sticky_custom', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter4_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter4_width',
                'title' => esc_html__('Delimiter Width', 'littledino'),
                'type' => 'dimensions',
                'height' => false,
                'width' => true,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter4_bg',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter4_margin',
                'title' => esc_html__('Delimiter Spacing', 'littledino'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter4_sticky_custom',
                'title' => esc_html__('Customize Sticky Delimiter', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_delimiter4_sticky_color',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
                'mode' => 'background',
                'required' => ['bottom_header_delimiter4_sticky_custom', '=', '1'],
            ],
            [
                'id' => 'bottom_header_delimiter4_sticky_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['bottom_header_delimiter4_sticky_custom', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter5_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter5_width',
                'title' => esc_html__('Delimiter Width', 'littledino'),
                'type' => 'dimensions',
                'height' => false,
                'width' => true,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter5_bg',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
                'mode' => 'background',
            ],
            [
                'id' => 'bottom_header_delimiter5_margin',
                'title' => esc_html__('Delimiter Spacing', 'littledino'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter5_sticky_custom',
                'title' => esc_html__('Customize Sticky Delimiter', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_delimiter5_sticky_color',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_delimiter5_sticky_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter5_sticky_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['bottom_header_delimiter5_sticky_custom', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter6_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'height' => true,
                'width' => false,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter6_width',
                'title' => esc_html__('Delimiter Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter6_bg',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter6_margin',
                'title' => esc_html__('Delimiter Spacing', 'littledino'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter6_sticky_custom',
                'title' => esc_html__('Customize Sticky Delimiter', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_delimiter6_sticky_color',
                'title' => esc_html__('Delimiter Background', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_delimiter6_sticky_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter6_sticky_height',
                'title' => esc_html__('Delimiter Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['bottom_header_delimiter6_sticky_custom', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_button1_title',
                'title' => esc_html__('Button Text', 'littledino'),
                'type' => 'text',
                'default' => esc_html__('Get Ticket', 'littledino'),
            ],
            [
                'id' => 'bottom_header_button1_link',
                'title' => esc_html__('Link', 'littledino'),
                'type' => 'text',
            ],
            [
                'id' => 'bottom_header_button1_target',
                'title' => esc_html__('Open link in a new tab', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'bottom_header_button1_size',
                'title' => esc_html__('Button Size', 'littledino'),
                'type' => 'select',
                'options' => [
                    's' => esc_html__('Small', 'littledino'),
                    'm' => esc_html__('Medium', 'littledino'),
                    'l' => esc_html__('Large', 'littledino'),
                    'xl' => esc_html__('Extra Large', 'littledino'),
                ],
                'default' => 's',
            ],
            [
                'id' => 'bottom_header_button1_radius',
                'title' => esc_html__('Button Border Radius', 'littledino'),
                'type' => 'text',
                'desc' => esc_html__('Value in pixels.', 'littledino'),
                'default' => '20',
            ],
            [
                'id' => 'bottom_header_button1_custom',
                'title' => esc_html__('Customize Button', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_button1_color_txt',
                'title' => esc_html__('Text Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_color_txt',
                'title' => esc_html__('Hover Text Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_bg',
                'title' => esc_html__('Background Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_bg',
                'title' => esc_html__('Hover Background Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_border',
                'title' => esc_html__('Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_border',
                'title' => esc_html__('Hover Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_custom_sticky',
                'title' => esc_html__('Customize Sticky Button', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_button1_color_txt_sticky',
                'title' => esc_html__('Sticky Text Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_color_txt_sticky',
                'title' => esc_html__('Sticky Hover Text Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_bg_sticky',
                'title' => esc_html__('Sticky Background Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_bg_sticky',
                'title' => esc_html__('Sticky Hover Background Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_border_sticky',
                'title' => esc_html__('Sticky Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_border_sticky',
                'title' => esc_html__('Sticky Hover Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_title',
                'title' => esc_html__('Button Text', 'littledino'),
                'type' => 'text',
                'default' => esc_html__('Get Ticket', 'littledino'),
            ],
            [
                'id' => 'bottom_header_button2_link',
                'title' => esc_html__('Link', 'littledino'),
                'type' => 'text',
            ],
            [
                'id' => 'bottom_header_button2_target',
                'title' => esc_html__('Open link in a new tab', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'bottom_header_button2_size',
                'title' => esc_html__('Button Size', 'littledino'),
                'type' => 'select',
                'options' => [
                    's' => esc_html__('Small', 'littledino'),
                    'm' => esc_html__('Medium', 'littledino'),
                    'l' => esc_html__('Large', 'littledino'),
                    'xl' => esc_html__('Extra Large', 'littledino'),
                ],
                'default' => 'm',
            ],
            [
                'id' => 'bottom_header_button2_radius',
                'title' => esc_html__('Button Border Radius', 'littledino'),
                'type' => 'text',
                'desc' => esc_html__('Value in pixels.', 'littledino'),
                'default' => '20',
            ],
            [
                'id' => 'bottom_header_button2_custom',
                'title' => esc_html__('Customize Button', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_button2_color_txt',
                'title' => esc_html__('Text Color Idle', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_color_txt',
                'title' => esc_html__('Text Color Hover', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_bg',
                'title' => esc_html__('Background Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_bg',
                'title' => esc_html__('Hover Background Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_border',
                'title' => esc_html__('Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_border',
                'title' => esc_html__('Hover Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_custom_sticky',
                'title' => esc_html__('Customize Sticky Button', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_button2_color_txt_sticky',
                'title' => esc_html__('Sticky Text Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_color_txt_sticky',
                'title' => esc_html__('Sticky Hover Text Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_bg_sticky',
                'title' => esc_html__('Sticky Background Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_bg_sticky',
                'title' => esc_html__('Sticky Hover Background Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_border_sticky',
                'title' => esc_html__('Sticky Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_border_sticky',
                'title' => esc_html__('Sticky Hover Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom_sticky', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_bar_html1_editor',
                'title' => esc_html__('HTML Element 1 Editor', 'littledino'),
                'type' => 'ace_editor',
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'bottom_header_bar_html2_editor',
                'title' => esc_html__('HTML Element 2 Editor', 'littledino'),
                'type' => 'ace_editor',
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'bottom_header_bar_html3_editor',
                'title' => esc_html__('HTML Element 3 Editor', 'littledino'),
                'type' => 'ace_editor',
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'bottom_header_bar_html4_editor',
                'title' => esc_html__('HTML Element 4 Editor', 'littledino'),
                'type' => 'ace_editor',
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'bottom_header_bar_html5_editor',
                'title' => esc_html__('HTML Element 5 Editor', 'littledino'),
                'type' => 'ace_editor',
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'bottom_header_bar_html6_editor',
                'title' => esc_html__('HTML Element 6 Editor', 'littledino'),
                'type' => 'ace_editor',
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'bottom_header_bar_html7_editor',
                'title' => esc_html__('HTML Element 7 Editor', 'littledino'),
                'type' => 'ace_editor',
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'bottom_header_bar_html8_editor',
                'title' => esc_html__('HTML Element 8 Editor', 'littledino'),
                'type' => 'ace_editor',
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'bottom_header_side_panel_color',
                'title' => esc_html__('Icon Color', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'bottom_header_side_panel_background',
                'title' => esc_html__('Background Icon', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'bottom_header_side_panel_sticky_custom',
                'title' => esc_html__('Customize Sticky Icon', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'bottom_header_side_panel_sticky_color',
                'title' => esc_html__('Icon Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_side_panel_sticky_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_side_panel_sticky_background',
                'title' => esc_html__('Background Icon', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_side_panel_sticky_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#313131',
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)'
                ],
            ],
            [
                'id' => 'header_top-start',
                'title' => esc_html__('Header Top Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_top_full_width',
                'title' => esc_html__('Full Width Top Header', 'littledino'),
                'type' => 'switch',
                'subtitle' => esc_html__('Set header content in full width top layout', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'header_top_height',
                'title' => esc_html__('Header Top Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 40],
            ],
            [
                'id' => 'header_top_background_image',
                'title' => esc_html__('Header Top Background Image', 'littledino'),
                'type' => 'media',
            ],
            [
                'id' => 'header_top_background',
                'title' => esc_html__('Header Top Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'header_top_color',
                'title' => esc_html__('Header Top Text Color', 'littledino'),
                'type' => 'color_rgba',
                'subtitle' => esc_html__('Set Top header text color', 'littledino'),
                'mode' => 'background',
                'default' => [
                    'color' => '#fefefe',
                    'alpha' => '.5',
                    'rgba' => 'rgba(254,254,254,0.5)'
                ],
            ],
            [
                'id' => 'header_top_bottom_border',
                'type' => 'switch',
                'title' => esc_html__('Set Header Top Bottom Border', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'header_top_border_height',
                'title' => esc_html__('Header Top Border Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['header_top_bottom_border', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_top_bottom_border_color',
                'title' => esc_html__('Header Top Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['header_top_bottom_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,0.2)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'header_top-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_middle-start',
                'title' => esc_html__('Header Middle Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_middle_full_width',
                'type' => 'switch',
                'title' => esc_html__('Full Width Middle Header', 'littledino'),
                'subtitle' => esc_html__('Set header content in full width middle layout', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'header_middle_height',
                'title' => esc_html__('Header Middle Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 110],
            ],
            [
                'id' => 'header_middle_background_image',
                'title' => esc_html__('Header Middle Background Image', 'littledino'),
                'type' => 'media',
            ],
            [
                'id' => 'header_middle_background',
                'title' => esc_html__('Header Middle Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'header_middle_color',
                'title' => esc_html__('Header Middle Text Color', 'littledino'),
                'type' => 'color_rgba',
                'subtitle' => esc_html__('Set Middle header text color', 'littledino'),
                'mode' => 'background',
                'default' => [
                    'color' => '#12265a',
                    'alpha' => '1',
                    'rgba' => 'rgba(18, 38, 90, 1)'
                ],
            ],
            [
                'id' => 'header_middle_bottom_border',
                'title' => esc_html__('Set Header Middle Bottom Border', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_middle_border_height',
                'title' => esc_html__('Header Middle Border Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['header_middle_bottom_border', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_middle_bottom_border_color',
                'title' => esc_html__('Header Middle Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['header_middle_bottom_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,0.2)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'header_middle-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_bottom-start',
                'title' => esc_html__('Header Bottom Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_bottom_full_width',
                'title' => esc_html__('Full Width Bottom Header', 'littledino'),
                'type' => 'switch',
                'subtitle' => esc_html__('Set header content in full width bottom layout', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'header_bottom_height',
                'title' => esc_html__('Header Bottom Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'header_bottom_background_image',
                'title' => esc_html__('Header Bottom Background Image', 'littledino'),
                'type' => 'media',
            ],
            [
                'id' => 'header_bottom_background',
                'title' => esc_html__('Header Bottom Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'header_bottom_color',
                'title' => esc_html__('Header Bottom Text Color', 'littledino'),
                'type' => 'color_rgba',
                'subtitle' => esc_html__('Set Bottom header text color', 'littledino'),
                'mode' => 'background',
                'default' => [
                    'color' => '#fefefe',
                    'alpha' => '.5',
                    'rgba' => 'rgba(254,254,254,0.5)'
                ],
            ],
            [
                'id' => 'header_bottom_bottom_border',
                'title' => esc_html__('Set Header Bottom Border', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'header_bottom_border_height',
                'title' => esc_html__('Header Bottom Border Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['header_bottom_bottom_border', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_bottom_bottom_border_color',
                'title' => esc_html__('Header Bottom Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['header_bottom_bottom_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,0.2)'
                ],
            ],
            [
                'id' => 'header_bottom-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-top-left-start',
                'title' => esc_html__('Top Left Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_top_left_horz',
                'type' => 'button_set',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_top_left_vert',
                'type' => 'button_set',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_top_left_display',
                'type' => 'button_set',
                'title' => esc_html__('Display', 'littledino'),
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-top-left-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-top-center-start',
                'title' => esc_html__('Top Center Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_top_center_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_top_center_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_top_center_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-top-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-top-center-start',
                'title' => esc_html__('Top Center Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_top_center_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_top_center_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_top_center_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-top-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-top-right-start',
                'title' => esc_html__('Top Right Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_top_right_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'right'
            ],
            [
                'id' => 'header_column_top_right_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_top_right_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-top-right-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-middle-left-start',
                'title' => esc_html__('Middle Left Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_middle_left_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_middle_left_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_middle_left_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-middle-left-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-middle-center-start',
                'title' => esc_html__('Middle Center Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_middle_center_horz',
                'type' => 'button_set',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left',
            ],
            [
                'id' => 'header_column_middle_center_vert',
                'type' => 'button_set',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_middle_center_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-middle-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-middle-center-start',
                'title' => esc_html__('Middle Center Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_middle_center_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_middle_center_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_middle_center_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-middle-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-middle-right-start',
                'title' => esc_html__('Middle Right Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_middle_right_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'right',
            ],
            [
                'id' => 'header_column_middle_right_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle',
            ],
            [
                'id' => 'header_column_middle_right_display',
                'type' => 'button_set',
                'title' => esc_html__('Display', 'littledino'),
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal',
            ],
            [
                'id' => 'header_column-middle-right-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-bottom-left-start',
                'title' => esc_html__('Bottom Left Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_bottom_left_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_bottom_left_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_bottom_left_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-bottom-left-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-bottom-center-start',
                'title' => esc_html__('Middle Center Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_bottom_center_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_bottom_center_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_bottom_center_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-bottom-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-bottom-center-start',
                'title' => esc_html__('Bottom Center Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_bottom_center_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_bottom_center_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_bottom_center_display',
                'type' => 'button_set',
                'title' => esc_html__('Display', 'littledino'),
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-bottom-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-bottom-right-start',
                'title' => esc_html__('Bottom Right Column Options', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_column_bottom_right_horz',
                'title' => esc_html__('Horizontal Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'right'
            ],
            [
                'id' => 'header_column_bottom_right_vert',
                'title' => esc_html__('Vertical Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'littledino'),
                    'middle' => esc_html__('Middle', 'littledino'),
                    'bottom' => esc_html__('Bottom', 'littledino'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_bottom_right_display',
                'title' => esc_html__('Display', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'littledino'),
                    'grow' => esc_html__('Grow', 'littledino'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-bottom-right-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_row_settings-start',
                'title' => esc_html__('Settings for selected Template', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'header_shadow',
                'title' => esc_html__('Header Bottom Shadow', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_on_bg',
                'title' => esc_html__('Over content', 'littledino'),
                'type' => 'switch',
                'subtitle' => esc_html__('Display header preset over the content.', 'littledino'),
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'lavalamp_active',
                'type' => 'switch',
                'title' => esc_html__('Lavalamp Marker', 'littledino'),
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'sub_menu_background',
                'type' => 'color_rgba',
                'title' => esc_html__('Sub Menu Background', 'littledino'),
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'sub_menu_color',
                'title' => esc_html__('Sub Menu Text Color', 'littledino'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#12265a',
            ],
            [
                'id' => 'header_sub_menu_bottom_border',
                'title' => esc_html__('Sub Menu Bottom Border', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_sub_menu_border_height',
                'title' => esc_html__('Sub Menu Border Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['header_sub_menu_bottom_border', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_sub_menu_bottom_border_color',
                'title' => esc_html__('Sub Menu Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['header_sub_menu_bottom_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(0, 0, 0, 0.08)'
                ],
            ],
            [
                'id' => 'header_mobile_queris',
                'title' => esc_html__('Mobile Header Switch Breakpoint', 'littledino'),
                'type' => 'slider',
                'display_value' => 'text',
                'min' => 1,
                'max' => 1700,
                'default' => 1200,
            ],
            [
                'id' => 'header_search_post_type',
                'title' => esc_html__('Search Post Types', 'littledino'),
                'type' => 'multi_text',
                'validate' => 'no_html',
                'add_text' => esc_html__('Add Post Type', 'littledino'),
                'default' => [],
            ],
            [
                'id' => 'header_row_settings-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'title' => esc_html__('Header Sticky', 'littledino'),
        'id' => 'header_builder_sticky',
        'subsection' => true,
        'fields' => [
            [
                'id' => 'header_sticky',
                'title' => esc_html__('Header Sticky', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'header_sticky-start',
                'title' => esc_html__('Sticky Settings', 'littledino'),
                'type' => 'section',
                'required' => ['header_sticky', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'header_sticky_color',
                'title' => esc_html__('Sticky Header Text Color', 'littledino'),
                'type' => 'color',
                'subtitle' => esc_html__('Set sticky header text color', 'littledino'),
                'transparent' => false,
                'default' => '#313131',
            ],
            [
                'id' => 'header_sticky_background',
                'title' => esc_html__('Sticky Header Background', 'littledino'),
                'type' => 'color_rgba',
                'subtitle' => esc_html__('Set sticky header background color', 'littledino'),
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1.0',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'header_sticky_height',
                'title' => esc_html__('Sticky Header Height', 'littledino'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'header_sticky_style',
                'type' => 'select',
                'title' => esc_html__('Appearance', 'littledino'),
                'options' => [
                    'standard' => esc_html__('Always Visible', 'littledino'),
                    'scroll_up' => esc_html__('Visible while scrolling upwards', 'littledino'),
                ],
                'default' => 'standard',
            ],
            [
                'id' => 'header_sticky_border',
                'title' => esc_html__('Bottom Border On/Off', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_sticky_border_height',
                'title' => esc_html__('Bottom Border Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['header_sticky_border', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_sticky_border_color',
                'title' => esc_html__('Bottom Border Color', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['header_sticky_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(82, 82, 82, 1)',
                    'color' => '#525252',
                ],
            ],
            [
                'id' => 'header_sticky_shadow',
                'title' => esc_html__('Bottom Shadow On/Off', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'sticky_header',
                'title' => esc_html__('Custom Sticky Header', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'sticky_header_layout',
                'title' => esc_html__('Sticky Header Order', 'littledino'),
                'type' => 'sorter',
                'required' => ['sticky_header', '=', '1'],
                'desc' => esc_html__('Organize the layout of the sticky header', 'littledino'),
                'compiler' => 'true',
                'full_width' => true,
                'options' => [
                    'items' => [
                        'html1' => esc_html__('HTML 1', 'littledino'),
                        'html2' => esc_html__('HTML 2', 'littledino'),
                        'html3' => esc_html__('HTML 3', 'littledino'),
                        'html4' => esc_html__('HTML 4', 'littledino'),
                        'html5' => esc_html__('HTML 5', 'littledino'),
                        'html6' => esc_html__('HTML 6', 'littledino'),
                        'item_search' => esc_html__('Search', 'littledino'),
                        'wpml' => esc_html__('WPML', 'littledino'),
                        'delimiter1' => esc_html__('|', 'littledino'),
                        'delimiter2' => esc_html__('|', 'littledino'),
                        'delimiter3' => esc_html__('|', 'littledino'),
                        'delimiter4' => esc_html__('|', 'littledino'),
                        'delimiter5' => esc_html__('|', 'littledino'),
                        'delimiter6' => esc_html__('|', 'littledino'),
                        'side_panel' => esc_html__('Side Panel', 'littledino'),
                        'cart' => esc_html__('Cart', 'littledino'),
                        'login' => esc_html__('Login', 'littledino'),
                        'wishlist' => esc_html__('Wishlist', 'littledino'),
                        'spacer1' => esc_html__('Spacer 1', 'littledino'),
                        'spacer2' => esc_html__('Spacer 2', 'littledino'),
                        'spacer3' => esc_html__('Spacer 3', 'littledino'),
                        'spacer4' => esc_html__('Spacer 4', 'littledino'),
                        'spacer5' => esc_html__('Spacer 5', 'littledino'),
                        'spacer6' => esc_html__('Spacer 6', 'littledino'),
                    ],
                    'Left align side' => [
                        'logo' => esc_html__('Logo', 'littledino'),
                    ],
                    'Center align side' => [],
                    'Right align side' => [
                        'menu' => esc_html__('Menu', 'littledino'),
                    ],
                ],
                'default' => [
                    'items' => [
                        'html1' => esc_html__('HTML 1', 'littledino'),
                        'html2' => esc_html__('HTML 2', 'littledino'),
                        'html3' => esc_html__('HTML 3', 'littledino'),
                        'html4' => esc_html__('HTML 4', 'littledino'),
                        'html5' => esc_html__('HTML 5', 'littledino'),
                        'html6' => esc_html__('HTML 6', 'littledino'),
                        'item_search' => esc_html__('Search', 'littledino'),
                        'wpml' => esc_html__('WPML', 'littledino'),
                        'delimiter1' => esc_html__('|', 'littledino'),
                        'delimiter2' => esc_html__('|', 'littledino'),
                        'delimiter3' => esc_html__('|', 'littledino'),
                        'delimiter4' => esc_html__('|', 'littledino'),
                        'delimiter5' => esc_html__('|', 'littledino'),
                        'delimiter6' => esc_html__('|', 'littledino'),
                        'spacer1' => esc_html__('Spacer 1', 'littledino'),
                        'spacer2' => esc_html__('Spacer 2', 'littledino'),
                        'spacer3' => esc_html__('Spacer 3', 'littledino'),
                        'spacer4' => esc_html__('Spacer 4', 'littledino'),
                        'spacer5' => esc_html__('Spacer 5', 'littledino'),
                        'spacer6' => esc_html__('Spacer 6', 'littledino'),
                        'side_panel' => esc_html__('Side Panel', 'littledino'),
                        'cart' => esc_html__('Cart', 'littledino'),
                        'login' => esc_html__('Login', 'littledino'),
                        'wishlist' => esc_html__('Wishlist', 'littledino'),
                    ],
                    'Left align side' => [
                        'logo' => esc_html__('Logo', 'littledino'),
                    ],
                    'Center align side' => [],
                    'Right align side' => [
                        'menu' => esc_html__('Menu', 'littledino'),
                    ],
                ],
            ],
            [
                'id' => 'header_custom_sticky_full_width',
                'title' => esc_html__('Full Width Sticky Header', 'littledino'),
                'type' => 'switch',
                'required' => ['sticky_header', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'sticky_header_bar_html1_editor',
                'title' => esc_html__('HTML Element 1 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['sticky_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'sticky_header_bar_html2_editor',
                'title' => esc_html__('HTML Element 2 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['sticky_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'sticky_header_bar_html3_editor',
                'title' => esc_html__('HTML Element 3 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['sticky_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'sticky_header_bar_html4_editor',
                'title' => esc_html__('HTML Element 4 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['sticky_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'sticky_header_bar_html5_editor',
                'title' => esc_html__('HTML Element 5 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['sticky_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'sticky_header_bar_html6_editor',
                'title' => esc_html__('HTML Element 6 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['sticky_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'sticky_header_spacer1',
                'title' => esc_html__('Spacer 1 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['sticky_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'sticky_header_spacer2',
                'title' => esc_html__('Spacer 2 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['sticky_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'sticky_header_spacer3',
                'title' => esc_html__('Spacer 3 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['sticky_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'sticky_header_spacer4',
                'title' => esc_html__('Spacer 4 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['sticky_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'sticky_header_spacer5',
                'title' => esc_html__('Spacer 5 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['sticky_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'sticky_header_spacer6',
                'title' => esc_html__('Spacer 6 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['sticky_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'header_sticky-end',
                'type' => 'section',
                'required' => ['header_sticky', '=', '1'],
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'header_builder_mobile',
        'title' => esc_html__('Header Mobile', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'mobile_header',
                'title' => esc_html__('Custom Mobile Header', 'littledino'),
                'type' => 'switch',
                'default'  => true,
            ],
            [
                'id' => 'mobile_background',
                'title' => esc_html__('Mobile Header Background', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['mobile_header', '=', '1'],
                'subtitle' => esc_html__('Set mobile header background color', 'littledino'),
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(49,49,49, 1)',
                    'color' => '#313131',
                ],
            ],
            [
                'id' => 'mobile_color',
                'title' => esc_html__('Mobile Header Text Color', 'littledino'),
                'type' => 'color',
                'required' => ['mobile_header', '=', '1'],
                'subtitle' => esc_html__('Set mobile header text color', 'littledino'),
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'mobile_sub_menu_background',
                'title' => esc_html__('Mobile Sub Menu Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'required' => ['mobile_header', '=', '1'],
                'subtitle' => esc_html__('Set sub menu background color', 'littledino'),
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(45,45,45,1)',
                    'color' => '#2d2d2d',
                ],
            ],
            [
                'id' => 'mobile_sub_menu_overlay',
                'title' => esc_html__('Mobile Sub Menu Overlay', 'littledino'),
                'type' => 'color_rgba',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(49, 49, 49, 0.8)',
                    'color' => '#313131',
                ],
            ],
            [
                'id' => 'mobile_sub_menu_color',
                'title' => esc_html__('Mobile Sub Menu Text Color', 'littledino'),
                'type' => 'color',
                'required' => ['mobile_header', '=', '1'],
                'subtitle' => esc_html__('Set sub menu header text color', 'littledino'),
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'header_mobile_height',
                'title' => esc_html__('Mobile Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => '100'],
            ],
            [
                'id' => 'mobile_over_content',
                'title' => esc_html__('Mobile Over Content', 'littledino'),
                'type' => 'switch',
                'default'  => false,
            ],
            [
                'id' => 'mobile_position',
                'title' => esc_html__('Mobile Sub Menu Position', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left',
            ],
            [
                'id' => 'mobile_header_layout',
                'title' => esc_html__('Mobile Header Order', 'littledino'),
                'type' => 'sorter',
                'required' => ['mobile_header', '=', '1'],
                'desc' => esc_html__('Organize the layout of the mobile header', 'littledino'),
                'compiler' => 'true',
                'full_width' => true,
                'options' => [
                    'items' => [
                        'html1' => esc_html__('HTML 1', 'littledino'),
                        'html2' => esc_html__('HTML 2', 'littledino'),
                        'html3' => esc_html__('HTML 3', 'littledino'),
                        'html4' => esc_html__('HTML 4', 'littledino'),
                        'html5' => esc_html__('HTML 5', 'littledino'),
                        'html6' => esc_html__('HTML 6', 'littledino'),
                        'wpml' => esc_html__('WPML', 'littledino'),
                        'spacer1' => esc_html__('Spacer 1', 'littledino'),
                        'spacer2' => esc_html__('Spacer 2', 'littledino'),
                        'spacer3' => esc_html__('Spacer 3', 'littledino'),
                        'spacer4' => esc_html__('Spacer 4', 'littledino'),
                        'spacer5' => esc_html__('Spacer 5', 'littledino'),
                        'spacer6' => esc_html__('Spacer 6', 'littledino'),
                        'side_panel' => esc_html__('Side Panel', 'littledino'),
                        'cart' => esc_html__('Cart', 'littledino'),
                        'login' => esc_html__('Login', 'littledino'),
                        'wishlist' => esc_html__('Wishlist', 'littledino'),
                    ],
                    'Left align side' => [
                        'menu' => esc_html__('Menu', 'littledino'),
                    ],
                    'Center align side' => [
                        'logo' => esc_html__('Logo', 'littledino'),
                    ],
                    'Right align side' => [
                        'item_search' => esc_html__('Search', 'littledino'),
                    ],
                ],
                'default' => [
                    'items' => [
                        'html1' => esc_html__('HTML 1', 'littledino'),
                        'html2' => esc_html__('HTML 2', 'littledino'),
                        'html3' => esc_html__('HTML 3', 'littledino'),
                        'html4' => esc_html__('HTML 4', 'littledino'),
                        'html5' => esc_html__('HTML 5', 'littledino'),
                        'html6' => esc_html__('HTML 6', 'littledino'),
                        'wpml' => esc_html__('WPML', 'littledino'),
                        'spacer1' => esc_html__('Spacer 1', 'littledino'),
                        'spacer2' => esc_html__('Spacer 2', 'littledino'),
                        'spacer3' => esc_html__('Spacer 3', 'littledino'),
                        'spacer4' => esc_html__('Spacer 4', 'littledino'),
                        'spacer5' => esc_html__('Spacer 5', 'littledino'),
                        'spacer6' => esc_html__('Spacer 6', 'littledino'),
                        'side_panel' => esc_html__('Side Panel', 'littledino'),
                        'cart' => esc_html__('Cart', 'littledino'),
                        'login' => esc_html__('Login', 'littledino'),
                        'wishlist' => esc_html__('Wishlist', 'littledino'),
                    ],
                    'Left align side' => [
                        'menu' => esc_html__('Menu', 'littledino'),
                    ],
                    'Center align side' => [
                        'logo' => esc_html__('Logo', 'littledino'),
                    ],
                    'Right align side' => [
                        'item_search' => esc_html__('Search', 'littledino'),
                    ],
                ],
            ],
            [
                'id' => 'mobile_header_bar_html1_editor',
                'title' => esc_html__('HTML Element 1 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html2_editor',
                'title' => esc_html__('HTML Element 2 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html3_editor',
                'title' => esc_html__('HTML Element 3 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html4_editor',
                'title' => esc_html__('HTML Element 4 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html5_editor',
                'title' => esc_html__('HTML Element 5 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html6_editor',
                'title' => esc_html__('HTML Element 6 Editor', 'littledino'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_spacer1',
                'title' => esc_html__('Spacer 1 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer2',
                'title' => esc_html__('Spacer 2 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer3',
                'title' => esc_html__('Spacer 3 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer4',
                'title' => esc_html__('Spacer 4 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer5',
                'title' => esc_html__('Spacer 5 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer6',
                'title' => esc_html__('Spacer 6 Width', 'littledino'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'page_title',
        'title' => esc_html__('Page Title', 'littledino'),
        'icon' => 'el el-home-alt',
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'page_title_settings',
        'title' => esc_html__('General', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'page_title_switch',
                'title' => esc_html__('Use Page Titles?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'page_title-start',
                'title' => esc_html__('Page Title Settings', 'littledino'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'page_title_bg_switch',
                'title' => esc_html__('Use Background?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'page_title_bg_image',
                'title' => esc_html__('Background', 'littledino'),
                'type' => 'background',
                'required' => ['page_title_bg_switch', '=', true],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-image' => esc_url(get_template_directory_uri() . "/img/page_title_bg.png"),
                    'background-repeat' => 'no-repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center bottom',
                    'background-color' => 'transparent',
                ],
            ],
            [
                'id' => 'page_title_height',
                'title' => esc_html__('Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['page_title_bg_switch', '=', true],
                'width' => false,
                'height' => true,
                'default' => ['height' => 348],
            ],
            [
                'id' => 'page_title_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'littledino'),
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
                'default' => [
                    'padding-top' => '12',
                    'padding-bottom' => '88',
                ],
            ],
            [
                'id' => 'page_title_margin',
                'title' => esc_html__('Margin Bottom', 'littledino'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => true,
                'top' => false,
                'left' => false,
                'right' => false,
                'default' => ['margin-bottom' => '40'],
            ],
            [
                'id' => 'page_title_align',
                'title' => esc_html__('Title Alignment', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'page_title_breadcrumbs_switch',
                'title' => esc_html__('Breadcrumbs', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'page_title_breadcrumbs_block_switch',
                'title' => esc_html__('Breadcrumbs Full Width', 'littledino'),
                'type' => 'switch',
                'required' => ['page_title_breadcrumbs_switch', '=', true],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'page_title_breadcrumbs_align',
                'title' => esc_html__('Breadcrumbs Alignment', 'littledino'),
                'type' => 'button_set',
                'required' => ['page_title_breadcrumbs_block_switch', '=', true],
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'page_title_parallax',
                'title' => esc_html__('Parallax Effect', 'littledino'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'page_title_parallax_speed',
                'title' => esc_html__('Parallax Speed', 'littledino'),
                'type' => 'spinner',
                'required' => ['page_title_parallax', '=', '1'],
                'min' => '-5',
                'max' => '5',
                'step' => '0.1',
                'default' => '0.3',
            ],
            [
                'id' => 'page_title_parallax_mouse',
                'title' => esc_html__('Parallax Mouse', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'page_title_mouse_bg_image',
                'title' => esc_html__('Background', 'littledino'),
                'type' => 'background',
                'required' => ['page_title_parallax_mouse', '=', '1'],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-image' => esc_url(get_template_directory_uri() . "/img/page_title_bg.png"),
                    'background-repeat' => 'no-repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center bottom',
                    'background-color' => 'transparent',
                ],
            ],
            [
                'id' => 'page_title_parallax_speed_mouse',
                'title' => esc_html__('Parallax Speed', 'littledino'),
                'type' => 'spinner',
                'required' => ['page_title_parallax_mouse', '=', '1'],
                'min' => '-5',
                'max' => '5',
                'step' => '0.01',
                'default' => '0.03',
            ],
            [
                'id' => 'page_title-end',
                'type' => 'section',
                'required' => ['page_title_switch', '=', '1'],
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'page_title_typography',
        'title' => esc_html__('Typography', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'page_title_font',
                'title' => esc_html__('Page Title Font', 'littledino'),
                'type' => 'custom_typography',
                'font-size' => true,
                'google' => false,
                'font-weight' => false,
                'font-family' => false,
                'font-style' => false,
                'color' => true,
                'line-height' => true,
                'font-backup' => false,
                'text-align' => false,
                'all_styles' => false,
                'default' => [
                    'font-size' => '52px',
                    'line-height' => '60px',
                    'color' => '#12265a',
                ],
            ],
            [
                'id' => 'page_title_breadcrumbs_font',
                'title' => esc_html__('Page Title Breadcrumbs Font', 'littledino'),
                'type' => 'custom_typography',
                'font-size' => true,
                'google' => false,
                'font-weight' => false,
                'font-family' => false,
                'font-style' => false,
                'color' => true,
                'line-height' => true,
                'font-backup' => false,
                'text-align' => false,
                'all_styles' => false,
                'default' => [
                    'font-size' => '16px',
                    'color' => '#12265a',
                    'line-height' => '24px',
                ],
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'title' => esc_html__('Responsive', 'littledino'),
        'id' => 'page_title_responsive',
        'subsection' => true,
        'fields' => [
            [
                'id' => 'page_title_resp_switch',
                'title' => esc_html__('Responsive Settings', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'page_title_resp_resolution',
                'title' => esc_html__('Screen breakpoint', 'littledino'),
                'type' => 'slider',
                'required' => ['page_title_resp_switch', '=', '1'],
                'desc' => esc_html__('Use responsive settings on screens smaller then choosed breakpoint.', 'littledino'),
                'display_value' => 'text',
                'min' => 1,
                'max' => 1700,
                'step' => 1,
                'default' => 768,
            ],
            [
                'id' => 'page_title_resp_height',
                'title' => esc_html__('Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['page_title_resp_switch', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 230],
            ],
            [
                'id' => 'page_title_resp_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'littledino'),
                'type' => 'spacing',
                'required' => ['page_title_resp_switch', '=', '1'],
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
                'default' => [
                    'padding-top' => '15',
                    'padding-bottom' => '40',
                ],
            ],
            [
                'id' => 'page_title_resp_font',
                'title' => esc_html__('Page Title Font', 'littledino'),
                'type' => 'custom_typography',
                'required' => ['page_title_resp_switch', '=', '1'],
                'google' => false,
                'all_styles' => false,
                'font-family' => false,
                'font-style' => false,
                'font-size' => true,
                'font-weight' => false,
                'font-backup' => false,
                'line-height' => true,
                'text-align' => false,
                'color' => true,
                'default' => [
                    'font-size' => '52px',
                    'line-height' => '60px',
                    'color' => '#12265a',
                ],
            ],
            [
                'id' => 'page_title_resp_breadcrumbs_switch',
                'title' => esc_html__('Breadcrumbs', 'littledino'),
                'type' => 'switch',
                'required' => ['page_title_resp_switch', '=', '1'],
                'default' => true,
            ],
            [
                'id' => 'page_title_resp_breadcrumbs_font',
                'title' => esc_html__('Page Title Breadcrumbs Font', 'littledino'),
                'type' => 'custom_typography',
                'required' => ['page_title_resp_breadcrumbs_switch', '=', '1'],
                'google' => false,
                'all_styles' => false,
                'font-family' => false,
                'font-style' => false,
                'font-size' => true,
                'font-weight' => false,
                'font-backup' => false,
                'line-height' => true,
                'text-align' => false,
                'color' => true,
                'default' => [
                    'font-size' => '16px',
                    'color' => '#12265a',
                    'line-height' => '24px',
                ],
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'footer',
        'title' => esc_html__('Footer', 'littledino'),
        'icon' => 'fa fa-window-maximize el-rotate-180',
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'footer_settings',
        'title' => esc_html__('General', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'footer_switch',
                'title' => esc_html__('Footer', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Disable', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'footer-start',
                'title' => esc_html__('Footer Settings', 'littledino'),
                'type' => 'section',
                'required' => ['footer_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'footer_add_wave',
                'title' => esc_html__('Add Wave', 'littledino'),
                'type' => 'switch',
                'required' => ['footer_switch', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'footer_wave_height',
                'title' => esc_html__('Set Wave Height', 'littledino'),
                'type' => 'dimensions',
                'required' => ['footer_add_wave', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 158],
            ],
            [
                'id' => 'footer_content_type',
                'title' => esc_html__('Content Type', 'littledino'),
                'type' => 'select',
                'options' => [
                    'widgets' => esc_html__('Get Widgets', 'littledino'),
                    'pages' => esc_html__('Get Pages', 'littledino'),
                ],
                'default' => 'widgets',
            ],
            [
                'id' => 'footer_page_select',
                'title' => esc_html__('Page Select', 'littledino'),
                'type' => 'select',
                'required' => ['footer_content_type', '=', 'pages'],
                'data' => 'posts',
                'args' => [
                    'post_type' => 'footer',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ],
            ],
            [
                'id' => 'widget_columns',
                'title' => esc_html__('Columns', 'littledino'),
                'type' => 'button_set',
                'required' => ['footer_content_type', '=', 'widgets'],
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '4',
            ],
            [
                'id' => 'widget_columns_2',
                'title' => esc_html__('Columns Layout', 'littledino'),
                'type' => 'image_select',
                'required' => ['widget_columns', '=', '2'],
                'options' => [
                    '6-6' => [
                        'alt' => '50-50',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/50-50.png'
                    ],
                    '3-9' => [
                        'alt' => '25-75',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/25-75.png'
                    ],
                    '9-3' => [
                        'alt' => '75-25',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/75-25.png'
                    ],
                    '4-8' => [
                        'alt' => '33-66',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/33-66.png'
                    ],
                    '8-4' => [
                        'alt' => '66-33',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/66-33.png'
                    ]
                ],
                'default' => '6-6',
            ],
            [
                'id' => 'widget_columns_3',
                'title' => esc_html__('Columns Layout', 'littledino'),
                'type' => 'image_select',
                'required' => ['widget_columns', '=', '3'],
                'options' => [
                    '4-4-4' => [
                        'alt' => '33-33-33',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/33-33-33.png'
                    ],
                    '3-3-6' => [
                        'alt' => '25-25-50',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/25-25-50.png'
                    ],
                    '3-6-3' => [
                        'alt' => '25-50-25',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/25-50-25.png'
                    ],
                    '6-3-3' => [
                        'alt' => '50-25-25',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/50-25-25.png'
                    ],
                ],
                'default' => '4-4-4',
            ],
            [
                'id' => 'footer_spacing',
                'title' => esc_html__('Paddings', 'littledino'),
                'type' => 'spacing',
                'output' => ['.wgl-footer'],
                'all' => false,
                'mode' => 'padding',
                'units' => 'px',
                'default' => [
                    'padding-top' => '0px',
                    'padding-right' => '0px',
                    'padding-bottom' => '0px',
                    'padding-left' => '0px'
                ],
            ],
            [
                'id' => 'footer_full_width',
                'title' => esc_html__('Full Width On/Off', 'littledino'),
                'type' => 'switch',
                'required' => ['footer_content_type', '=', 'widgets'],
                'default' => false,
            ],
            [
                'id' => 'footer-end',
                'type' => 'section',
                'required' => ['footer_switch', '=', '1'],
                'indent' => false,
            ],
            [
                'id' => 'footer-start-styles',
                'title' => esc_html__('Footer Styling', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'footer_bg_image',
                'title' => esc_html__('Background Image', 'littledino'),
                'type' => 'background',
                'preview' => false,
                'preview_media' => true,
                'background-color' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                ],
            ],
            [
                'id' => 'footer_align',
                'title' => esc_html__('Content Align', 'littledino'),
                'type' => 'button_set',
                'required' => ['footer_content_type', '=', 'widgets'],
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'footer_bg_color',
                'title' => esc_html__('Background Color', 'littledino'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#2d4073',
            ],
            [
                'id' => 'footer_heading_color',
                'title' => esc_html__('Headings color', 'littledino'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'footer_text_color',
                'title' => esc_html__('Content color', 'littledino'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'footer-end-styles',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'copyright',
        'title' => esc_html__('Copyright', 'littledino'),
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'title' => esc_html__('Settings', 'littledino'),
        'id' => 'copyright-settings',
        'subsection' => true,
        'fields' => [
            [
                'id' => 'copyright_switch',
                'type' => 'switch',
                'title' => esc_html__('Copyright', 'littledino'),
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Disable', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'copyright-start',
                'type' => 'section',
                'title' => esc_html__('Copyright Settings', 'littledino'),
                'indent' => true,
            ],
            [
                'id' => 'copyright_editor',
                'title' => esc_html__('Editor', 'littledino'),
                'type' => 'editor',
                'required' => ['copyright_switch', '=', '1'],
                'args' => [
                    'wpautop' => false,
                    'media_buttons' => false,
                    'textarea_rows' => 2,
                    'teeny' => false,
                    'quicktags' => true,
                ],
                'default' => '<p>Copyright © 2020 LittleDino by <a href="https://themeforest.net/user/webgeniuslab" rel="noopener noreferrer" target="_blank">WebGeniusLab</a>. All Rights Reserved</p>',
            ],
            [
                'id' => 'copyright_text_color',
                'title' => esc_html__('Text Color', 'littledino'),
                'type' => 'color',
                'required' => ['copyright_switch', '=', '1'],
                'transparent' => false,
                'default' => '#b1bee0',
            ],
            [
                'id' => 'copyright_bg_color',
                'title' => esc_html__('Background Color', 'littledino'),
                'type' => 'color',
                'required' => ['copyright_switch', '=', '1'],
                'transparent' => false,
                'default' => '#233668',
            ],
            [
                'id' => 'copyright_spacing',
                'type' => 'spacing',
                'title' => esc_html__('Paddings', 'littledino'),
                'required' => ['copyright_switch', '=', '1'],
                'mode' => 'padding',
                'left' => false,
                'right' => false,
                'all' => false,
                'default' => [
                    'padding-top' => '28',
                    'padding-bottom' => '28',
                ],
            ],
            [
                'id' => 'copyright-end',
                'type' => 'section',
                'required' => ['footer_switch', '=', '1'],
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'blog-option',
        'title' => esc_html__('Blog', 'littledino'),
        'icon' => 'el el-bullhorn',
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'blog-list-option',
        'title' => esc_html__('Archive', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'post_archive_page_title_bg_image',
                'title' => esc_html__('Background Image', 'littledino'),
                'type' => 'background',
                'background-color' => false,
                'preview_media' => true,
                'preview' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '#1e73be',
                ],
            ],
            [
                'id' => 'blog_list_sidebar_layout',
                'title' => esc_html__('Sidebar Layout', 'littledino'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'none'
            ],
            [
                'id' => 'blog_list_sidebar_def',
                'title' => esc_html__('Sidebar Template', 'littledino'),
                'type' => 'select',
                'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
            ],
            [
                'id' => 'blog_list_sidebar_def_width',
                'title' => esc_html__('Sidebar Width', 'littledino'),
                'type' => 'button_set',
                'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => '25%',
                    '8' => '33%',
                ],
                'default' => '9',
            ],
            [
                'id' => 'blog_list_sidebar_sticky',
                'title' => esc_html__('Sticky Sidebar', 'littledino'),
                'type' => 'switch',
                'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                'default' => false,
            ],
            [
                'id' => 'blog_list_sidebar_gap',
                'title' => esc_html__('Sidebar Side Gap', 'littledino'),
                'type' => 'select',
                'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                'options' => [
                    'def' => esc_html__('Default', 'littledino'),
                    '0' => '0',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '30' => '30',
                    '35' => '35',
                    '40' => '40',
                    '45' => '45',
                    '50' => '50',
                ],
                'default' => '30',
            ],
            [
                'id' => 'blog_list_columns',
                'title' => esc_html__('Columns in Archive', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    '12' => esc_html__('One', 'littledino'),
                    '6' => esc_html__('Two', 'littledino'),
                    '4' => esc_html__('Three', 'littledino'),
                    '3' => esc_html__('Four', 'littledino'),
                ],
                'default' => '12',
            ],
            [
                'id' => 'blog_list_likes',
                'title' => esc_html__('Likes', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_share',
                'title' => esc_html__('Shares', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_hide_media',
                'title' => esc_html__('Hide Media?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_hide_title',
                'title' => esc_html__('Hide Title?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_hide_content',
                'title' => esc_html__('Hide Content?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_post_listing_content',
                'title' => esc_html__('Limit the characters amount in Content?', 'littledino'),
                'type' => 'switch',
                'required' => ['blog_list_hide_content', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_letter_count',
                'title' => esc_html__('Characters amount to be displayed in Content', 'littledino'),
                'type' => 'text',
                'required' => ['blog_post_listing_content', '=', true],
                'default' => '85',
            ],
            [
                'id' => 'blog_list_read_more',
                'title' => esc_html__('Hide Read More Button?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta',
                'title' => esc_html__('Hide all post-meta?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta_author',
                'title' => esc_html__('Hide post-meta author?', 'littledino'),
                'type' => 'switch',
                'required' => ['blog_list_meta', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta_comments',
                'title' => esc_html__('Hide post-meta comments?', 'littledino'),
                'type' => 'switch',
                'required' => ['blog_list_meta', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta_categories',
                'title' => esc_html__('Hide post-meta categories?', 'littledino'),
                'type' => 'switch',
                'required' => ['blog_list_meta', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta_date',
                'title' => esc_html__('Hide post-meta date?', 'littledino'),
                'type' => 'switch',
                'required' => ['blog_list_meta', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'blog-single-option',
        'title' => esc_html__('Single', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'single_type_layout',
                'title' => esc_html__('Default Post Layout', 'littledino'),
                'type' => 'button_set',
                'desc' => esc_html__('Note: each Post can be separately customized within its Metaboxes section.', 'littledino'),
                'options' => [
                    '1' => esc_html__('Title First', 'littledino'),
                    '2' => esc_html__('Image First', 'littledino'),
                    '3' => esc_html__('Overlay Image', 'littledino')
                ],
                'default' => '3',
            ],
            [
                'id' => 'blog_single_page_title-start',
                'title' => esc_html__('Page Title', 'littledino'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'blog_title_conditional',
                'title' => esc_html__('Blog Post Title On/Off', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'post_single_page_title_text',
                'title' => esc_html__('Single Page Title Text', 'littledino'),
                'type' => 'text',
                'required' => ['blog_title_conditional', '=', true],
                'default' => esc_html__('Blog', 'littledino'),
            ],
            [
                'id' => 'post_single_page_title_bg_image',
                'title' => esc_html__('Background Image', 'littledino'),
                'type' => 'background',
                'preview' => false,
                'preview_media' => true,
                'background-color' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '#fcf9f4',
                ],
            ],
            [
                'id' => 'single_padding_layout_3',
                'type' => 'spacing',
                'title' => esc_html__('Page Title Padding Top/Bottom', 'littledino'),
                'required' => ['single_type_layout', '=', '3'],
                'mode' => 'padding',
                'all' => false,
                'top' => true,
                'right' => false,
                'bottom' => true,
                'left' => false,
                'default' => [
                    'padding-top' => '110px',
                    'padding-bottom' => '110px',
                ],
            ],
            [
                'id' => 'blog_single_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'blog_single_sidebar-start',
                'type' => 'section',
                'title' => esc_html__('Sidebar', 'littledino'),
                'indent' => true,
            ],
            [
                'id' => 'single_sidebar_layout',
                'title' => esc_html__('Sidebar Layout', 'littledino'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'right'
            ],
            [
                'id' => 'single_sidebar_def',
                'title' => esc_html__('Sidebar Template', 'littledino'),
                'type' => 'select',
                'required' => ['single_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
                'default' => 'sidebar_main-sidebar',
            ],
            [
                'id' => 'single_sidebar_def_width',
                'title' => esc_html__('Sidebar Width', 'littledino'),
                'type' => 'button_set',
                'required' => ['single_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => '25%',
                    '8' => '33%',
                ],
                'default' => '9',
            ],
            [
                'id' => 'single_sidebar_sticky',
                'title' => esc_html__('Sticky Sidebar', 'littledino'),
                'type' => 'switch',
                'required' => ['single_sidebar_layout', '!=', 'none'],
                'default' => true,
            ],
            [
                'id' => 'single_sidebar_gap',
                'title' => esc_html__('Sidebar Side Gap', 'littledino'),
                'type' => 'select',
                'required' => ['single_sidebar_layout', '!=', 'none'],
                'options' => [
                    'def' => esc_html__('Default', 'littledino'),
                    '0' => '0',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '30' => '30',
                    '35' => '35',
                    '40' => '40',
                    '45' => '45',
                    '50' => '50',
                ],
                'default' => 'def',
            ],
            [
                'id' => 'blog_single_sidebar-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'blog_single_appearance-start',
                'title' => esc_html__('Appearance', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'featured_image_type',
                'title' => esc_html__('Featured Image', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'default' => esc_html__('Default', 'littledino'),
                    'off' => esc_html__('Off', 'littledino'),
                    'replace' => esc_html__('Replace', 'littledino')
                ],
                'default' => 'default',
            ],
            [
                'id' => 'featured_image_replace',
                'title' => esc_html__('Image To Replace On', 'littledino'),
                'type' => 'media',
                'required' => ['featured_image_type', '=', 'replace'],
            ],
            [
                'id' => 'single_apply_animation',
                'title' => esc_html__('Apply Animation?', 'littledino'),
                'type' => 'switch',
                'required' => ['single_type_layout', '=', '3'],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'single_likes',
                'title' => esc_html__('Likes', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'single_views',
                'title' => esc_html__('Views', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'single_share',
                'title' => esc_html__('Shares', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'single_meta_tags',
                'title' => esc_html__('Tags', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'single_author_info',
                'title' => esc_html__('Author Info', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'single_meta',
                'title' => esc_html__('Hide all post-meta?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'single_meta_author',
                'title' => esc_html__('Hide post-meta author?', 'littledino'),
                'type' => 'switch',
                'required' => ['single_meta', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'single_meta_comments',
                'title' => esc_html__('Hide post-meta comments?', 'littledino'),
                'type' => 'switch',
                'required' => ['single_meta', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'single_meta_categories',
                'title' => esc_html__('Hide post-meta categories?', 'littledino'),
                'type' => 'switch',
                'required' => ['single_meta', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'single_meta_date',
                'title' => esc_html__('Hide post-meta date?', 'littledino'),
                'type' => 'switch',
                'required' => ['single_meta', '=', false],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'blog_single_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'blog-single-related-option',
        'title' => esc_html__('Related', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'single_related_posts',
                'title' => esc_html__('Related Posts', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'blog_title_r',
                'title' => esc_html__('Related Section Title', 'littledino'),
                'type' => 'text',
                'required' => ['single_related_posts', '=', '1'],
                'default' => esc_html__('Related Posts', 'littledino'),
            ],
            [
                'id' => 'blog_cat_r',
                'title' => esc_html__('Select Categories', 'littledino'),
                'type' => 'select',
                'required' => ['single_related_posts', '=', '1'],
                'multi' => true,
                'data' => 'categories',
                'width' => '20%',
            ],
            [
                'id' => 'blog_column_r',
                'title' => esc_html__('Columns', 'littledino'),
                'type' => 'button_set',
                'required' => ['single_related_posts', '=', '1'],
                'options' => [
                    '12' => '1',
                    '6' => '2',
                    '4' => '3',
                    '3' => '4'
                ],
                'default' => '6',
            ],
            [
                'id' => 'blog_number_r',
                'title' => esc_html__('Number of Related Items', 'littledino'),
                'type' => 'text',
                'required' => ['single_related_posts', '=', '1'],
                'default' => '2',
            ],
            [
                'id' => 'blog_carousel_r',
                'title' => esc_html__('Display items in the carousel', 'littledino'),
                'type' => 'switch',
                'required' => ['single_related_posts', '=', '1'],
                'default' => true,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'portfolio-option',
        'title' => esc_html__('Portfolio', 'littledino'),
        'icon' => 'el el-picture',
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'portfolio-list-option',
        'title' => esc_html__('Archive', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'portfolio_slug',
                'title' => esc_html__('Portfolio Slug', 'littledino'),
                'type' => 'text',
                'default' => 'portfolio',
            ],
            [
                'id' => 'portfolio_archive_page_title-start',
                'title' => esc_html__('Page Title', 'littledino'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'portfolio_archive_page_title_bg_image',
                'title' => esc_html__('Page Title Background Image', 'littledino'),
                'type' => 'background',
                'preview' => false,
                'preview_media' => true,
                'background-color' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '#fcf9f4',
                ],
            ],
            [
                'id' => 'portfolio_archive_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_archive_sidebar-start',
                'title' => esc_html__('Sidebar', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_list_sidebar_layout',
                'title' => esc_html__('Sidebar Layout', 'littledino'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'none'
            ],
            [
                'id' => 'portfolio_list_sidebar_def',
                'title' => esc_html__('Sidebar Template', 'littledino'),
                'type' => 'select',
                'required' => ['portfolio_list_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
            ],
            [
                'id' => 'portfolio_list_sidebar_def_width',
                'title' => esc_html__('Sidebar Width', 'littledino'),
                'type' => 'button_set',
                'required' => ['portfolio_list_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => esc_html__('25%', 'littledino'),
                    '8' => esc_html__('33%', 'littledino'),
                ],
                'default' => '9',
            ],
            [
                'id' => 'portfolio_archive_sidebar-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_list_appearance-start',
                'title' => esc_html__('Appearance', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_list_columns',
                'title' => esc_html__('Columns in Archive', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    '1' => esc_html__('One', 'littledino'),
                    '2' => esc_html__('Two', 'littledino'),
                    '3' => esc_html__('Three', 'littledino'),
                    '4' => esc_html__('Four', 'littledino'),
                ],
                'default' => '3',
            ],
            [
                'id' => 'portfolio_list_show_title',
                'title' => esc_html__('Title', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_list_show_content',
                'title' => esc_html__('Content', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_list_show_cat',
                'title' => esc_html__('Categories', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_list_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'portfolio-single-option',
        'title' => esc_html__('Single', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'portfolio_single_layout-start',
                'title' => esc_html__('Layout', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_single_type_layout',
                'title' => esc_html__('Portfolio Single Layout', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    '1' => esc_html__('Title First', 'littledino'),
                    '2' => esc_html__('Image First', 'littledino'),
                    '3' => esc_html__('Overlay Image', 'littledino'),
                    '4' => esc_html__('Overlay Image with Info', 'littledino'),
                ],
                'default' => '2',
            ],
            [
                'id' => 'portfolio_single_layout-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_single_page_title-start',
                'title' => esc_html__('Page Title', 'littledino'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', true],
                'indent' => true,
            ],
            [
                'id' => 'portfolio_title_conditional',
                'title' => esc_html__('Use Custom Post Title?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Post Type Name', 'littledino'),
                'off' => esc_html__('Post Title', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_page_title_text',
                'title' => esc_html__('Custom Post Title', 'littledino'),
                'type' => 'text',
                'required' => ['portfolio_title_conditional', '=', true],
                'default' => '',
            ],
            [
                'id' => 'portfolio_single_title_align',
                'title' => esc_html__('Title Alignment', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'portfolio_single_breadcrumbs_align',
                'title' => esc_html__('Breadcrumbs Alignment', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'portfolio_single_breadcrumbs_block_switch',
                'title' => esc_html__('Breadcrumbs Full Width', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'portfolio_single_title_bg_switch',
                'title' => esc_html__('Use Background?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_single_page_title_bg_image',
                'title' => esc_html__('Background', 'littledino'),
                'type' => 'background',
                'required' => ['portfolio_single_title_bg_switch', '=', true],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '',
                ],
            ],
            [
                'id' => 'portfolio_single_page_title_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'littledino'),
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
                'default' => [
                    'padding-top' => '12',
                    'padding-bottom' => '88',
                ],
            ],
            [
                'id' => 'portfolio_single_page_title_margin',
                'title' => esc_html__('Margin Bottom', 'littledino'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => true,
                'top' => false,
                'left' => false,
                'right' => false,
                'default' => ['margin-bottom' => '40'],
            ],
            [
                'id' => 'portfolio_single_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_single_align',
                'title' => esc_html__('Content Alignment', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left',
            ],
            [
                'id' => 'portfolio_single_padding',
                'title' => esc_html__('Portfolio Single Padding', 'littledino'),
                'type' => 'spacing',
                'required' => [
                    ['portfolio_single_type_layout', '!=', '1'],
                    ['portfolio_single_type_layout', '!=', '2'],
                ],
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
                'default' => [
                    'padding-top' => '165px',
                    'padding-bottom' => '165px',
                ],
            ],
            [
                'id' => 'portfolio_parallax',
                'title' => esc_html__('Add Portfolio Parallax', 'littledino'),
                'type' => 'switch',
                'required' => [
                    ['portfolio_single_type_layout', '!=', '1'],
                    ['portfolio_single_type_layout', '!=', '2'],
                ],
                'default' => false,
            ],
            [
                'id' => 'portfolio_parallax_speed',
                'title' => esc_html__('Parallax Speed', 'littledino'),
                'type' => 'spinner',
                'required' => ['portfolio_parallax', '=', '1'],
                'min' => '-5',
                'max' => '5',
                'step' => '0.1',
                'default' => '0.3',
            ],
            [
                'id' => 'portfolio_single_sidebar-start',
                'title' => esc_html__('Sidebar', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_single_sidebar_layout',
                'title' => esc_html__('Sidebar Layout', 'littledino'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'none'
            ],
            [
                'id' => 'portfolio_single_sidebar_def',
                'title' => esc_html__('Sidebar Template', 'littledino'),
                'type' => 'select',
                'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
            ],
            [
                'id' => 'portfolio_single_sidebar_def_width',
                'title' => esc_html__('Sidebar Width', 'littledino'),
                'type' => 'button_set',
                'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => '25%',
                    '8' => '33%',
                ],
                'default' => '8',
            ],
            [
                'id' => 'portfolio_single_sidebar_sticky',
                'title' => esc_html__('Sticky Sidebar', 'littledino'),
                'type' => 'switch',
                'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_sidebar_gap',
                'title' => esc_html__('Sidebar Side Gap', 'littledino'),
                'type' => 'select',
                'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                'options' => [
                    'def' => esc_html__('Default', 'littledino'),
                    '0' => '0',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '30' => '30',
                    '35' => '35',
                    '40' => '40',
                    '45' => '45',
                    '50' => '50',
                ],
                'default' => 'def',
            ],
            [
                'id' => 'portfolio_single_sidebar-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_single_appearance-start',
                'title' => esc_html__('Appearance', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_above_content_cats',
                'title' => esc_html__('Tags', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_above_content_share',
                'title' => esc_html__('Shares', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_single_meta_likes',
                'title' => esc_html__('Likes', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_single_meta',
                'title' => esc_html__('Hide all post-meta?', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_meta_author',
                'title' => esc_html__('Post-meta author', 'littledino'),
                'type' => 'switch',
                'required' => ['portfolio_single_meta', '=', false],
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_meta_comments',
                'title' => esc_html__('Post-meta comments', 'littledino'),
                'type' => 'switch',
                'required' => ['portfolio_single_meta', '=', false],
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_meta_categories',
                'title' => esc_html__('Post-meta categories', 'littledino'),
                'type' => 'switch',
                'required' => ['portfolio_single_meta', '=', false],
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_single_meta_date',
                'title' => esc_html__('Post-meta date', 'littledino'),
                'type' => 'switch',
                'required' => ['portfolio_single_meta', '=', false],
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_single_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'portfolio-related-option',
        'title' => esc_html__('Related Posts', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'portfolio_related_switch',
                'title' => esc_html__('Related Posts', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'pf_title_r',
                'title' => esc_html__('Title', 'littledino'),
                'type' => 'text',
                'required' => ['portfolio_related_switch', '=', '1'],
                'default' => esc_html__('Related Portfolio', 'littledino'),
            ],
            [
                'id' => 'pf_carousel_r',
                'title' => esc_html__('Display items carousel for this portfolio post', 'littledino'),
                'type' => 'switch',
                'required' => ['portfolio_related_switch', '=', '1'],
                'default' => true,
            ],
            [
                'id' => 'pf_column_r',
                'title' => esc_html__('Related Columns', 'littledino'),
                'type' => 'button_set',
                'required' => ['portfolio_related_switch', '=', '1'],
                'options' => [
                    '2' => esc_html__('Two', 'littledino'),
                    '3' => esc_html__('Three', 'littledino'),
                    '4' => esc_html__('Four', 'littledino'),
                ],
                'default' => '3',
            ],
            [
                'id' => 'pf_number_r',
                'title' => esc_html__('Number of Related Items', 'littledino'),
                'type' => 'text',
                'required' => ['portfolio_related_switch', '=', '1'],
                'default' => '3',
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'team-option',
        'title' => esc_html__('Team', 'littledino'),
        'icon' => 'el el-user',
        'fields' => [
            [
                'id' => 'team_slug',
                'title' => esc_html__('Team Slug', 'littledino'),
                'type' => 'text',
                'default' => 'team',
            ],
            [
                'id' => 'team_single_page_title_bg_image',
                'title' => esc_html__('Single Page Title Background Image', 'littledino'),
                'type' => 'background',
                'preview' => false,
                'preview_media' => true,
                'background-color' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '#fcf9f4',
                ]
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'team-single-option',
        'title' => esc_html__('Single', 'littledino'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'team_title_conditional',
                'title' => esc_html__('Team Post Title On/Off', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'team_single_page_title_text',
                'title' => esc_html__('Single Page Title Text', 'littledino'),
                'type' => 'text',
                'required' => ['team_title_conditional', '=', true],
                'default' => esc_html__('Team', 'littledino'),
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'title' => esc_html__('Page 404', 'littledino'),
        'id' => '404-option',
        'icon' => 'el el-error',
        'fields' => [
            [
                'id' => '404_page_title-start',
                'title' => esc_html__('404 Settings', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => '404_page_main_bg_image',
                'title' => esc_html__('Main Background', 'littledino'),
                'type' => 'background',
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-repeat' => 'no-repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'right top',
                    'background-color' => '',
                    'background-image' => esc_url(get_template_directory_uri() . "/img/bg_404_page.png"),
                ],
            ],
            [
                'id' => '404_parallax_apply_animation',
                'title' => esc_html__('Apply Background Animation?', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => '404_parallax_speed',
                'title' => esc_html__('Parallax Speed', 'littledino'),
                'type' => 'spinner',
                'required' => ['404_parallax_apply_animation', '=', '1'],
                'min' => '-5',
                'step' => '0.01',
                'max' => '5',
                'default' => '0.03',
            ],
            [
                'id' => '404_show_header',
                'title' => esc_html__('Show Header?', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => '404_show_footer',
                'title' => esc_html__('Show Footer?', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => '404_page_title_switcher',
                'title' => esc_html__('Show Page Title?', 'littledino'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => '404_custom_title_switch',
                'title' => esc_html__('Use Custom Page Title?', 'littledino'),
                'type' => 'switch',
                'required' => ['404_page_title_switcher', '=', true],
                'default' => false,
            ],
            [
                'id' => '404_page_title_text',
                'title' => esc_html__('Custom Page Title', 'littledino'),
                'type' => 'text',
                'required' => ['404_custom_title_switch', '=', true],
                'default' => '',
            ],
            [
                'id' => '404_title_bg_switch',
                'title' => esc_html__('Use Background?', 'littledino'),
                'type' => 'switch',
                'required' => ['404_page_title_switcher', '=', true],
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => true,
            ],
            [
                'id' => '404_page_title_bg_image',
                'title' => esc_html__('Background', 'littledino'),
                'type' => 'background',
                'required' => ['404_title_bg_switch', '=', true],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '',
                ],
            ],
            [
                'id' => '404_page_title_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'littledino'),
                'type' => 'spacing',
                'required' => ['404_page_title_switcher', '=', true],
                'mode' => 'padding',
                'all' => false,
                'top' => true,
                'bottom' => true,
                'left' => false,
                'right' => false,
                'default' => [
                    'padding-top' => '12',
                    'padding-bottom' => '88',
                ],
            ],
            [
                'id' => '404_page_title_margin',
                'title' => esc_html__('Margin Bottom', 'littledino'),
                'type' => 'spacing',
                'required' => ['404_page_title_switcher', '=', true],
                'mode' => 'margin',
                'all' => false,
                'top' => false,
                'bottom' => true,
                'left' => false,
                'right' => false,
                'default' => ['margin-bottom' => '40'],
            ],
            [
                'id' => '404_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'side_panel',
        'title' => esc_html__('Side Panel', 'littledino'),
        'icon' => 'el el-indent-left',
        'fields' => [
            [
                'id' => 'side_panel_content_type',
                'title' => esc_html__('Content Type', 'littledino'),
                'type' => 'select',
                'options' => [
                    'widgets' => esc_html__('Get Widgets', 'littledino'),
                    'pages' => esc_html__('Get Pages', 'littledino'),
                ],
                'default' => 'pages',
            ],
            [
                'id' => 'side_panel_page_select',
                'title' => esc_html__('Page Select', 'littledino'),
                'type' => 'select',
                'required' => ['side_panel_content_type', '=', 'pages'],
                'data' => 'posts',
                'args' => [
                    'post_type' => 'side_panel',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ],
            ],
            [
                'id' => 'side_panel_spacing',
                'title' => esc_html__('Paddings', 'littledino'),
                'type' => 'spacing',
                'output' => ['#side-panel .side-panel_sidebar'],
                'mode' => 'padding',
                'units' => 'px',
                'all' => false,
                'default' => [
                    'padding-top' => '105px',
                    'padding-right' => '90px',
                    'padding-bottom' => '105px',
                    'padding-left' => '90px',
                ],
            ],
            [
                'id' => 'side_panel_title_color',
                'title' => esc_html__('Title Color', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'side_panel_text_color',
                'title' => esc_html__('Text Color', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(204,204,204,1)',
                    'color' => '#cccccc',
                ],
            ],
            [
                'id' => 'side_panel_bg',
                'title' => esc_html__('Background', 'littledino'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'side_panel_text_alignment',
                'title' => esc_html__('Text Align', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'center' => esc_html__('Center', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'left',
            ],
            [
                'id' => 'side_panel_width',
                'title' => esc_html__('Width', 'littledino'),
                'type' => 'dimensions',
                'width' => true,
                'height' => false,
                'default' => ['width' => 475],
            ],
            [
                'id' => 'side_panel_position',
                'title' => esc_html__('Position', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'littledino'),
                    'right' => esc_html__('Right', 'littledino'),
                ],
                'default' => 'right'
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'layout_options',
        'title' => esc_html__('Sidebars', 'littledino'),
        'icon' => 'el el-braille',
        'fields' => [
            [
                'id' => 'sidebars',
                'title' => esc_html__('Register Sidebars', 'littledino'),
                'type' => 'multi_text',
                'validate' => 'no_html',
                'add_text' => esc_html__('Add Sidebar', 'littledino'),
                'default' => ['Main Sidebar'],
            ],
            [
                'id' => 'sidebars-start',
                'title' => esc_html__('Sidebar Settings', 'littledino'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'page_sidebar_layout',
                'title' => esc_html__('Page Sidebar Layout', 'littledino'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'littledino'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'none'
            ],
            [
                'id' => 'page_sidebar_def',
                'title' => esc_html__('Page Sidebar', 'littledino'),
                'type' => 'select',
                'required' => ['page_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
            ],
            [
                'id' => 'page_sidebar_def_width',
                'title' => esc_html__('Page Sidebar Width', 'littledino'),
                'type' => 'button_set',
                'required' => ['page_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => '25%',
                    '8' => '33%',
                ],
                'default' => '9',
            ],
            [
                'id' => 'page_sidebar_sticky',
                'title' => esc_html__('Sticky Sidebar', 'littledino'),
                'type' => 'switch',
                'required' => ['page_sidebar_layout', '!=', 'none'],
                'default' => false,
            ],
            [
                'id' => 'page_sidebar_gap',
                'title' => esc_html__('Sidebar Side Gap', 'littledino'),
                'type' => 'select',
                'required' => ['page_sidebar_layout', '!=', 'none'],
                'options' => [
                    'def' => esc_html__('Default', 'littledino'),
                    '0' => '0',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '30' => '30',
                    '35' => '35',
                    '40' => '40',
                    '45' => '45',
                    '50' => '50',
                ],
                'default' => '30',
            ],
            [
                'id' => 'sidebars-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'soc_shares',
        'title' => esc_html__('Social Shares', 'littledino'),
        'icon' => 'el el-share-alt',
        'fields' => [
            [
                'id' => 'show_soc_icon_page',
                'title' => esc_html__('Page Social Shares', 'littledino'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'littledino'),
                'off' => esc_html__('Hide', 'littledino'),
                'default' => false,
            ],
            [
                'id' => 'soc_icon_style',
                'title' => esc_html__('Socials visibility', 'littledino'),
                'type' => 'button_set',
                'options' => [
                    'standard' => esc_html__('Standard', 'littledino'),
                    'hovered' => esc_html__('On Hover', 'littledino'),
                ],
                'default' => 'standard',
                'required' => ['show_soc_icon_page', '=', '1'],
            ],
            [
                'id' => 'soc_icon_position',
                'title' => esc_html__('Fixed Position On/Off', 'littledino'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_offset',
                'title' => esc_html__('Offset Top', 'littledino'),
                'type' => 'spacing',
                'required' => ['show_soc_icon_page', '=', '1'],
                'desc' => esc_html__('Measurement units defined as "percents" while position fixed is enabled, and as "pixels" while position is off.', 'littledino'),
                'mode' => 'margin',
                'all' => false,
                'top' => false,
                'bottom' => true,
                'left' => false,
                'right' => false,
                'default' => ['margin-bottom' => '40%'],
            ],
            [
                'id' => 'soc_icon_facebook',
                'title' => esc_html__('Facebook Button', 'littledino'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_twitter',
                'title' => esc_html__('Twitter Button', 'littledino'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_linkedin',
                'title' => esc_html__('Linkedin Button', 'littledino'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_pinterest',
                'title' => esc_html__('Pinterest Button', 'littledino'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_tumblr',
                'title' => esc_html__('Tumblr Button', 'littledino'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'add_custom_share',
                'title' => esc_html__('Need Additional Socials?', 'littledino'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'on' => esc_html__('Yes', 'littledino'),
                'off' => esc_html__('No', 'littledino'),
                'default' => true,
            ],
            [
                'id' => 'select_custom_share_icons-1',
                'title' => esc_html__('Custom Share Icon 1', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-1',
                'title' => esc_html__('Custom Share Link 1', 'littledino'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-2',
                'title' => esc_html__('Custom Share Icon 2', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-2',
                'title' => esc_html__('Custom Share Link 2', 'littledino'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-3',
                'title' => esc_html__('Custom Share Icon 3', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-3',
                'title' => esc_html__('Custom Share Link 3', 'littledino'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-4',
                'title' => esc_html__('Custom Share Icon 4', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-4',
                'title' => esc_html__('Custom Share Link 4', 'littledino'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-5',
                'title' => esc_html__('Custom Share Icon 5', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-5',
                'type' => 'text',
                'title' => esc_html__('Custom Share Link 5', 'littledino'),
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-6',
                'title' => esc_html__('Custom Share Icon 6', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-6',
                'title' => esc_html__('Custom Share Link 6', 'littledino'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-7',
                'title' => esc_html__('Custom Share Icon 7', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-7',
                'title' => esc_html__('Custom Share Link 7', 'littledino'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-8',
                'title' => esc_html__('Custom Share Icon 8', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-8',
                'title' => esc_html__('Custom Share Link 8', 'littledino'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-9',
                'title' => esc_html__('Custom Share Icon 9', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-9',
                'type' => 'text',
                'title' => esc_html__('Custom Share Link 9', 'littledino'),
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'select_custom_share_icons-10',
                'title' => esc_html__('Custom Share Icon 10', 'littledino'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'select_custom_share_text-10',
                'title' => esc_html__('Custom Share Link 10', 'littledino'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
        ]
    ]
);

Redux::setSection(
    $theme_slug,
    [
        'id' => 'color_options_color',
        'title' => esc_html__('Color Settings', 'littledino'),
        'icon' => 'el-icon-tint',
        'fields' => [
            [
                'id' => 'theme-custom-color',
                'title' => esc_html__('Primary Theme Color', 'littledino'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#fa9db7',
            ],
            [
                'id' => 'theme-secondary-color',
                'title' => esc_html__('Secondary Theme Color', 'littledino'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#ffc85b',
            ],
            [
                'id' => 'theme-third-color',
                'title' => esc_html__('Tertiary Theme Color', 'littledino'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#45b3df',
            ],
            [
                'id' => 'body-background-color',
                'title' => esc_html__('Body Background Color', 'littledino'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
        ]
    ]
);

//* ↓ Typography Config
Redux::setSection(
    $theme_slug,
    [
        'id' => 'Typography',
        'title' => esc_html__('Typography', 'littledino'),
        'icon' => 'el-icon-font',
    ]
);

$typography = [];
$main_typography = [
    [
        'id' => 'main-font',
        'title' => esc_html__('Content Font', 'littledino'),
        'color' => true,
        'line-height' => true,
        'font-size' => true,
        'subsets' => false,
        'all_styles' => true,
        'font-weight-multi' => true,
        'defs' => [
            'font-size' => '16px',
            'line-height' => '30px',
            'color' => '#70747f',
            'font-family' => 'Muli',
            'font-weight' => '400',
            'font-weight-multi' => '600,700,800',
        ],
    ],
    [
        'id' => 'header-font',
        'title' => esc_html__('Headings Font', 'littledino'),
        'font-size' => false,
        'line-height' => false,
        'color' => true,
        'subsets' => false,
        'all_styles' => true,
        'font-weight-multi' => true,
        'defs' => [
            'google' => true,
            'color' => '#12265a',
            'font-family' => 'Nunito',
            'font-weight' => '900',
            'font-weight-multi' => '',
        ],
    ],
];
foreach ($main_typography as $key => $value) {
    array_push($typography, [
        'id' => $value['id'],
        'type' => 'custom_typography',
        'title' => $value['title'],
        'color' => $value['color'] ?? '',
        'line-height' => $value['line-height'],
        'font-size' => $value['font-size'],
        'subsets' => $value['subsets'],
        'all_styles' => $value['all_styles'],
        'font-weight-multi' => $value['font-weight-multi'] ?? '',
        'subtitle' => $value['subtitle'] ?? '',
        'google' => true,
        'font-style' => true,
        'font-backup' => false,
        'text-align' => false,
        'default' => $value['defs'],
    ]);
}

Redux::setSection(
    $theme_slug,
    [
        'id' => 'main_typography',
        'title' => esc_html__('Main Content', 'littledino'),
        'subsection' => true,
        'fields' => $typography,
    ]
);

//* ↓ Menu Typography
$menu_typography = [
    [
        'id' => 'menu-font',
        'title' => esc_html__('Menu Font', 'littledino'),
        'color' => false,
        'line-height' => true,
        'font-size' => true,
        'subsets' => true,
        'defs' => [
            'google' => true,
            'font-family' => 'Nunito',
            'font-size' => '18px',
            'font-weight' => '900',
            'line-height' => '30px'
        ],
    ],
    [
        'id' => 'sub-menu-font',
        'title' => esc_html__('Submenu Font', 'littledino'),
        'color' => false,
        'line-height' => true,
        'font-size' => true,
        'subsets' => true,
        'defs' => [
            'google' => true,
            'font-family' => 'Nunito',
            'font-size' => '14px',
            'font-weight' => '900',
            'line-height' => '30px'
        ],
    ],
];
$menu_typography_array = [];
foreach ($menu_typography as $key => $value) {
    array_push($menu_typography_array, [
        'id' => $value['id'],
        'type' => 'custom_typography',
        'title' => $value['title'],
        'color' => $value['color'],
        'line-height' => $value['line-height'],
        'font-size' => $value['font-size'],
        'subsets' => $value['subsets'],
        'google' => true,
        'font-style' => true,
        'font-backup' => false,
        'text-align' => false,
        'all_styles' => false,
        'default' => $value['defs'],
    ]);
}
Redux::setSection(
    $theme_slug,
    [
        'id' => 'main_menu_typography',
        'title' => esc_html__('Menu', 'littledino'),
        'subsection' => true,
        'fields' => $menu_typography_array
    ]
);
//* ↑ menu typography

//* ↓ Headings Typography
$headings = [
    [
        'id' => 'header-h1',
        'title' => esc_html__('‹h1›', 'littledino'),
        'defs' => [
            'font-family' => 'Nunito',
            'font-size' => '52px',
            'line-height' => '60px',
            'font-weight' => '900',
        ],
    ],
    [
        'id' => 'header-h2',
        'title' => esc_html__('‹h2›', 'littledino'),
        'defs' => [
            'font-family' => 'Nunito',
            'font-size' => '48px',
            'line-height' => '56px',
            'font-weight' => '900',
        ],
    ],
    [
        'id' => 'header-h3',
        'title' => esc_html__('‹h3›', 'littledino'),
        'defs' => [
            'font-family' => 'Nunito',
            'font-weight' => '900',
            'font-size' => '42px',
            'line-height' => '48px',
        ],
    ],
    [
        'id' => 'header-h4',
        'title' => esc_html__('H4', 'littledino'),
        'defs' => [
            'font-family' => 'Nunito',
            'font-size' => '36px',
            'line-height' => '42px',
            'font-weight' => '900',
        ],
    ],
    [
        'id' => 'header-h5',
        'title' => esc_html__('H5', 'littledino'),
        'defs' => [
            'font-family' => 'Nunito',
            'font-size' => '30px',
            'line-height' => '38px',
            'font-weight' => '900'
        ],
    ],
    [
        'id' => 'header-h6',
        'title' => esc_html__('H6', 'littledino'),
        'defs' => [
            'font-family' => 'Nunito',
            'font-size' => '24px',
            'line-height' => '32px',
            'font-weight' => '900',
        ],
    ],
];
$headings_array = [];
foreach ($headings as $key => $heading) {
    array_push($headings_array, [
        'id' => $heading['id'],
        'type' => 'custom_typography',
        'title' => $heading['title'],
        'google' => true,
        'font-backup' => false,
        'font-size' => true,
        'line-height' => true,
        'color' => false,
        'word-spacing' => false,
        'letter-spacing' => true,
        'text-align' => false,
        'text-transform' => true,
        'default' => $heading['defs'],
    ]);
}

Redux::setSection(
    $theme_slug,
    [
        'id' => 'main_headings_typography',
        'title' => esc_html__('Headings', 'littledino'),
        'subsection' => true,
        'fields' => $headings_array
    ]
);

if (class_exists('WooCommerce')) {
    Redux::setSection(
        $theme_slug,
        [
            'id' => 'shop-option',
            'title' => esc_html__('Shop', 'littledino'),
            'icon' => 'el-icon-shopping-cart',
            'fields' => []
        ]
    );

    Redux::setSection(
        $theme_slug,
        [
            'id' => 'shop-catalog-option',
            'title' => esc_html__('Catalog', 'littledino'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_catalog_page_title_bg_image',
                    'title' => esc_html__('Page Title Background Image', 'littledino'),
                    'type' => 'background',
                    'required' => ['page_title_switch', '=', true],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '#1e73be',
                    ]
                ],
                [
                    'id' => 'shop_catalog_sidebar-start',
                    'title' => esc_html__('Sidebar Settings', 'littledino'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'shop_catalog_sidebar_layout',
                    'title' => esc_html__('Sidebar Layout', 'littledino'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'littledino'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'littledino'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'littledino'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ],
                    ],
                    'default' => 'left',
                ],
                [
                    'id' => 'shop_catalog_sidebar_def',
                    'title' => esc_html__('Shop Catalog Sidebar', 'littledino'),
                    'type' => 'select',
                    'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                ],
                [
                    'id' => 'shop_catalog_sidebar_def_width',
                    'title' => esc_html__('Shop Sidebar Width', 'littledino'),
                    'type' => 'button_set',
                    'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => '25%',
                        '8' => '33%',
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'shop_catalog_sidebar_sticky',
                    'title' => esc_html__('Sticky Sidebar', 'littledino'),
                    'type' => 'switch',
                    'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                    'default' => false,
                ],
                [
                    'id' => 'shop_catalog_sidebar_gap',
                    'title' => esc_html__('Sidebar Side Gap', 'littledino'),
                    'type' => 'select',
                    'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                    'options' => [
                        'def' => esc_html__('Default', 'littledino'),
                        '0' => '0',
                        '15' => '15',
                        '20' => '20',
                        '25' => '25',
                        '30' => '30',
                        '35' => '35',
                        '40' => '40',
                        '45' => '45',
                        '50' => '50',
                    ],
                    'default' => 'def',
                ],
                [
                    'id' => 'shop_catalog_sidebar-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'shop_column',
                    'title' => esc_html__('Shop Column', 'littledino'),
                    'type' => 'button_set',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4'
                    ],
                    'default' => '3',
                ],
                [
                    'id' => 'shop_products_per_page',
                    'title' => esc_html__('Products per page', 'littledino'),
                    'type' => 'spinner',
                    'min' => '1',
                    'max' => '100',
                    'default' => '12',
                ],
                [
                    'id' => 'use_animation_shop',
                    'title' => esc_html__('Use Animation Shop?', 'littledino'),
                    'type' => 'switch',
                    'default' => true,
                ],
                [
                    'id' => 'shop_catalog_animation_style',
                    'title' => esc_html__('Animation Style', 'littledino'),
                    'type' => 'select',
                    'required' => ['use_animation_shop', '=', true],
                    'select2' => ['allowClear' => false],
                    'options' => [
                        'fade-in' => esc_html__('Fade In', 'littledino'),
                        'slide-top' => esc_html__('Slide Top', 'littledino'),
                        'slide-bottom' => esc_html__('Slide Bottom', 'littledino'),
                        'slide-left' => esc_html__('Slide Left', 'littledino'),
                        'slide-right' => esc_html__('Slide Right', 'littledino'),
                        'zoom' => esc_html__('Zoom', 'littledino'),
                    ],
                    'default' => 'slide-left',
                ],
            ]
        ]
    );

    Redux::setSection(
        $theme_slug,
        [
            'id' => 'shop-single-option',
            'title' => esc_html__('Single', 'littledino'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_single_page_title-start',
                    'title' => esc_html__('Page Title Settings', 'littledino'),
                    'type' => 'section',
                    'required' => ['page_title_switch', '=', true],
                    'indent' => true,
                ],
                [
                    'id' => 'shop_title_conditional',
                    'title' => esc_html__('Use Custom Post Title?', 'littledino'),
                    'type' => 'switch',
                    'default' => true,
                ],
                [
                    'id' => 'shop_single_page_title_text',
                    'title' => esc_html__('Custom Post Title', 'littledino'),
                    'type' => 'text',
                    'required' => ['shop_title_conditional', '=', true],
                    'default' => esc_html__('Shop Single', 'littledino'),
                ],
                [
                    'id' => 'shop_single_title_align',
                    'title' => esc_html__('Title Alignment', 'littledino'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'littledino'),
                        'center' => esc_html__('Center', 'littledino'),
                        'right' => esc_html__('Right', 'littledino'),
                    ],
                    'default' => 'center',
                ],
                [
                    'id' => 'shop_single_breadcrumbs_block_switch',
                    'title' => esc_html__('Breadcrumbs Display', 'littledino'),
                    'type' => 'switch',
                    'required' => ['page_title_breadcrumbs_switch', '=', true],
                    'on' => esc_html__('Block', 'littledino'),
                    'off' => esc_html__('Inline', 'littledino'),
                    'default' => true,
                ],
                [
                    'id' => 'shop_single_breadcrumbs_align',
                    'title' => esc_html__('Title Breadcrumbs Alignment', 'littledino'),
                    'type' => 'button_set',
                    'required' => [
                        ['page_title_breadcrumbs_switch', '=', true],
                        ['shop_single_breadcrumbs_block_switch', '=', true]
                    ],
                    'options' => [
                        'left' => esc_html__('Left', 'littledino'),
                        'center' => esc_html__('Center', 'littledino'),
                        'right' => esc_html__('Right', 'littledino'),
                    ],
                    'default' => 'center',
                ],
                [
                    'id' => 'shop_single_title_bg_switch',
                    'title' => esc_html__('Use Background?', 'littledino'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'littledino'),
                    'off' => esc_html__('Hide', 'littledino'),
                    'default' => true,
                ],
                [
                    'id' => 'shop_single_page_title_bg_image',
                    'title' => esc_html__('Background', 'littledino'),
                    'type' => 'background',
                    'required' => ['shop_single_title_bg_switch', '=', true],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '#fcf9f4',
                    ],
                ],
                [
                    'id' => 'shop_single_page_title_padding',
                    'title' => esc_html__('Paddings Top/Bottom', 'littledino'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'bottom' => true,
                    'top' => true,
                    'left' => false,
                    'right' => false,
                    'default' => [
                        'padding-top' => '33',
                        'padding-bottom' => '0',
                    ],
                ],
                [
                    'id' => 'shop_single_page_title_margin',
                    'title' => esc_html__('Margin Bottom', 'littledino'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => true,
                    'top' => false,
                    'left' => false,
                    'right' => false,
                    'default' => ['margin-bottom' => '-7'],
                ],
                [
                    'id' => 'shop_single_page_title_border_switch',
                    'title' => esc_html__('Enable Border Top?', 'littledino'),
                    'type' => 'switch',
                    'default' => false,
                ],
                [
                    'id' => 'shop_single_page_title_border_color',
                    'title' => esc_html__('Border Top Color', 'littledino'),
                    'type' => 'color_rgba',
                    'required' => ['shop_single_page_title_border_switch', '=', true],
                    'default' => [
                        'color' => '#e5e5e5',
                        'alpha' => '1',
                        'rgba' => 'rgba(229,229,229,1)'
                    ],
                ],
                [
                    'id' => 'shop_single_page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'shop_single_sidebar-start',
                    'title' => esc_html__('Sidebar Settings', 'littledino'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'shop_single_sidebar_layout',
                    'title' => esc_html__('Sidebar Layout', 'littledino'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'littledino'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'littledino'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'littledino'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ],
                    ],
                    'default' => 'none',
                ],
                [
                    'id' => 'shop_single_sidebar_def',
                    'title' => esc_html__('Sidebar Template', 'littledino'),
                    'type' => 'select',
                    'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                ],
                [
                    'id' => 'shop_single_sidebar_def_width',
                    'title' => esc_html__('Sidebar Width', 'littledino'),
                    'type' => 'button_set',
                    'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => '25%',
                        '8' => '33%',
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'shop_single_sidebar_sticky',
                    'title' => esc_html__('Sticky Sidebar', 'littledino'),
                    'type' => 'switch',
                    'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                    'default' => false,
                ],
                [
                    'id' => 'shop_single_sidebar_gap',
                    'title' => esc_html__('Sidebar Side Gap', 'littledino'),
                    'type' => 'select',
                    'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                    'options' => [
                        'def' => esc_html__('Default', 'littledino'),
                        '0' => '0',
                        '15' => '15',
                        '20' => '20',
                        '25' => '25',
                        '30' => '30',
                        '35' => '35',
                        '40' => '40',
                        '45' => '45',
                        '50' => '50',
                    ],
                    'default' => 'def',
                ],
                [
                    'id' => 'shop_single_sidebar-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'shop_single_share',
                    'title' => esc_html__('Share On/Off', 'littledino'),
                    'type' => 'switch',
                    'default' => false,
                ],
            ]
        ]
    );

    Redux::setSection(
        $theme_slug,
        [
            'title' => esc_html__('Related', 'littledino'),
            'id' => 'shop-related-option',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_related_columns',
                    'title' => esc_html__('Related products column', 'littledino'),
                    'type' => 'button_set',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                    ],
                    'default' => '4',
                ],
                [
                    'id' => 'shop_r_products_per_page',
                    'title' => esc_html__('Related products per page', 'littledino'),
                    'type' => 'spinner',
                    'min' => '1',
                    'max' => '100',
                    'default' => '4',
                ],
            ]
        ]
    );

    Redux::setSection(
        $theme_slug,
        [
            'title' => esc_html__('Cart', 'littledino'),
            'id' => 'shop-cart-option',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_cart_page_title_bg_image',
                    'title' => esc_html__('Page Title Background Image', 'littledino'),
                    'type' => 'background',
                    'required' => ['page_title_switch', '=', true],
                    'background-color' => false,
                    'preview_media' => true,
                    'preview' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '#fcf9f4',
                    ],
                ],
            ]
        ]
    );
    Redux::setSection(
        $theme_slug,
        [
            'title' => esc_html__('Checkout', 'littledino'),
            'id' => 'shop-checkout-option',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_checkout_page_title_bg_image',
                    'title' => esc_html__('Page Title Background Image', 'littledino'),
                    'type' => 'background',
                    'background-color' => false,
                    'preview_media' => true,
                    'preview' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '#fcf9f4',
                    ],
                ],
            ]

        ]
    );
}
