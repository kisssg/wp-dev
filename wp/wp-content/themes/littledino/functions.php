<?php

// Class Theme Helper
require_once ( get_theme_file_path( '/core/class/theme-helper.php' ) );

// Class Theme Cache
require_once ( get_theme_file_path( '/core/class/theme-cache.php' ) );

// Class Walker comments
require_once ( get_theme_file_path( '/core/class/walker-comment.php' ) );

// Class Walker Mega Menu
require_once ( get_theme_file_path( '/core/class/walker-mega-menu.php' ) );

// Class Theme Likes
require_once ( get_theme_file_path( '/core/class/theme-likes.php' ) );

// Class Theme Cats Meta
require_once ( get_theme_file_path( '/core/class/theme-cat-meta.php' ) );

// Class Single Post
require_once ( get_theme_file_path( '/core/class/single-post.php' ) );

// Class Tinymce
require_once ( get_theme_file_path( '/core/class/tinymce-icon.php' ) );

// Class Theme Autoload
require_once ( get_theme_file_path( '/core/class/theme-autoload.php' ) );

// Class Theme Dashboard
require_once ( get_theme_file_path( '/core/class/theme-panel.php' ) );

// Class Theme Verify
require_once ( get_theme_file_path( '/core/class/theme-verify.php' ) );

function littledino_content_width() {
    if ( ! isset( $content_width ) ) {
        $content_width = 940;
    }
}
add_action( 'after_setup_theme', 'littledino_content_width', 0 );

function littledino_theme_slug_setup() {
    add_theme_support('title-tag');
}
add_action( 'after_setup_theme', 'littledino_theme_slug_setup' );

add_action('init', 'littledino_page_init');
if (!function_exists('littledino_page_init')) {
    function littledino_page_init() {
        add_post_type_support('page', 'excerpt');
    }
}

add_action('admin_init', 'littledino_elementor_dom');
if (!function_exists('littledino_elementor_dom')) {
    function littledino_elementor_dom()
    {
        if(!get_option('wgl_elementor_e_dom') && class_exists('\Elementor\Core\Experiments\Manager')){
            $new_option = \Elementor\Core\Experiments\Manager::STATE_INACTIVE;
			update_option('elementor_experiment-e_dom_optimization', $new_option);
            update_option('wgl_elementor_e_dom', 1);
        }
    }
}

if (!function_exists('littledino_main_menu')) {
    function littledino_main_menu($location = '') {
        wp_nav_menu(
            [
                'theme_location'  => 'main_menu',
                'menu'  => $location,
                'container' => '',
                'container_class' => '',
                'after' => '',
                'link_before' => '<span>',
                'link_after' => '</span>',
                'walker' => new LittleDino_Mega_Menu_Waker()
            ]
        );
    }
}

// return all sidebars
if (!function_exists('littledino_get_all_sidebar')) {
    function littledino_get_all_sidebar() {
        global $wp_registered_sidebars;
        $out = [];
        if ( empty( $wp_registered_sidebars ) )
            return;
        foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar) :
            $out[$sidebar_id] = $sidebar['name'];
        endforeach;
        return $out;
    }
}

if (!function_exists('littledino_get_custom_preset')) {
    function littledino_get_custom_preset() {
        $custom_preset = get_option('littledino_set_preset');
        $presets =  littledino_default_preset();

        $out = array();
        $out['default'] = esc_html__( 'Default', 'littledino' );
        $i = 1;
        if(is_array($presets)){
            foreach ($presets as $key => $value) {
                $out[$key] = $key;
                $i++;
            }
        }
        if(is_array($custom_preset)){
            foreach ( $custom_preset as $preset_id => $preset) :
                $out[$preset_id] = $preset_id;
            endforeach;
        }
        return $out;
    }
}

if (!function_exists('littledino_get_custom_menu')) {
    function littledino_get_custom_menu() {
        $taxonomies = array();

        $menus = get_terms('nav_menu');
        foreach ($menus as $key => $value) {
            $taxonomies[$value->name] = $value->name;
        }
        return $taxonomies;
    }
}

function littledino_get_attachment( $attachment_id ) {
    $attachment = get_post( $attachment_id );
    return array(
        'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'href' => get_permalink( $attachment->ID ),
        'src' => $attachment->guid,
        'title' => $attachment->post_title
    );
}

if (!function_exists('littledino_reorder_comment_fields')) {
    function littledino_reorder_comment_fields($fields ) {
        $new_fields = array();

        $myorder = array('author', 'email', 'url', 'comment');

        foreach( $myorder as $key ){
            $new_fields[ $key ] = isset($fields[ $key ]) ? $fields[ $key ] : '';
            unset( $fields[ $key ] );
        }

        if( $fields ) {
            foreach( $fields as $key => $val ) {
                $new_fields[ $key ] = $val;
            }
        }

        return $new_fields;
    }
}
add_filter('comment_form_fields', 'littledino_reorder_comment_fields');

function littledino_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
add_filter( 'mce_buttons_2', 'littledino_mce_buttons_2' );


function littledino_tiny_mce_before_init( $settings ) {

    $settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4';
    $h_font_color = esc_attr(\LittleDino_Theme_Helper::get_option('header-font')['color']);
    $theme_color = esc_attr(\LittleDino_Theme_Helper::get_option('theme-custom-color'));
    $second_color = esc_attr(\LittleDino_Theme_Helper::get_option('theme-secondary-color'));

    $style_formats = [
        [
            'title' => esc_html__( 'Dropcap', 'littledino' ),
            'items' => [
                [
                    'title' => esc_html__( 'Dropcap', 'littledino' ),
                    'inline' => 'span',
                    'classes' => 'dropcap',
                    'styles' => [ 'color' => $theme_color ],
                ], [
                    'title' => esc_html__( 'Dropcap on background', 'littledino' ),
                    'inline' => 'span',
                    'classes' => 'dropcap-bg',
                    'styles' => [ 'background-color' => $second_color ],
                ],
            ],
        ], [
            'title' => esc_html__( 'Highlighter', 'littledino' ),
            'inline' => 'span',
            'classes' => 'highlighter',
            'styles' => [
                'color' => '#ffffff',
                'background-color' => $theme_color
            ],
        ], [
            'title' => esc_html__( 'Double Heading Font', 'littledino' ),
            'inline' => 'span',
            'classes' => 'dbl_font',
        ], [
            'title' => esc_html__( 'Font Weight', 'littledino' ),
            'items' => [
                [
                    'title' => esc_html__( 'Default', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => 'inherit' ]
                ], [
                    'title' => esc_html__( '100 / Lightest', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '100' ]
                ], [
                    'title' => esc_html__( '200 / Lighter', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '200' ]
                ], [
                    'title' => esc_html__( '300 / Light', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '300' ]
                ], [
                    'title' => esc_html__( '400 / Normal', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '400' ]
                ], [
                    'title' => esc_html__( '500 / Medium', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '500' ]
                ], [
                    'title' => esc_html__( '600 / Semi-Bold', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '600' ]
                ], [
                    'title' => esc_html__( '700 / Bold', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '700' ]
                ], [
                    'title' => esc_html__( '800 / Bolder', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '800' ]
                ], [
                    'title' => esc_html__( '900 / Extra Bold', 'littledino' ),
                    'inline' => 'span',
                    'classes' => '',
                    'styles' => [ 'font-weight' => '900' ]
                ],
            ]
        ], [
            'title' => esc_html__( 'List Style', 'littledino' ),
            'items' => [
                [
                    'title' => esc_html__( 'Check (Theme Color)', 'littledino' ),
                    'selector' => 'ul',
                    'classes' => 'wgl-check'
                ], [
                    'title' => esc_html__( 'Check (Secondary Color)', 'littledino' ),
                    'selector' => 'ul',
                    'classes' => 'wgl-check secondary-color'
                ], [
                    'title' => esc_html__( 'Check (Tertiary Color)', 'littledino' ),
                    'selector' => 'ul',
                    'classes' => 'wgl-check tertiary-color'
                ], [
                    'title' => esc_html__( 'Pencil', 'littledino' ),
                    'selector' => 'ul',
                    'classes' => 'wgl-pencil'
                ], [
                    'title' => esc_html__( 'Plus', 'littledino' ),
                    'selector' => 'ul',
                    'classes' => 'wgl-plus'
                ], [
                    'title' => esc_html__( 'Dash', 'littledino' ),
                    'selector' => 'ul',
                    'classes' => 'wgl-dash'
                ], [
                    'title' => esc_html__( 'Slash', 'littledino' ),
                    'selector' => 'ul',
                    'classes' => 'wgl-slash'
                ], [
                    'title' => esc_html__( 'No List Style', 'littledino' ),
                    'selector' => 'ul',
                    'classes' => 'no-list-style'
                ],
            ]
        ],
    ];

    $settings['style_formats'] = str_replace( '"', "'", json_encode( $style_formats ) );
    $settings['extended_valid_elements'] = 'span[*],a[*],i[*]';
    return $settings;
}
add_filter( 'tiny_mce_before_init', 'littledino_tiny_mce_before_init' );

function littledino_theme_add_editor_styles() {
    add_editor_style( 'css/font-awesome.min.css' );
}
add_action( 'current_screen', 'littledino_theme_add_editor_styles' );

/**
 * @since 1.0.0
 * @version 1.0.6
 */
function littledino_categories_postcount_filter($variable)
{
    if (strpos($variable, '</a> (')) {
        $variable = str_replace('</a> (', '</a> <span class="post_count">(', $variable);
        $variable = str_replace('</a>&nbsp;(', '</a>&nbsp;<span class="post_count">(', $variable);
        $variable = str_replace(')', ')</span>', $variable);
    } else {
        $variable = str_replace('</a> <span class="count">(', '</a><span class="post_count">(', $variable);
        $variable = str_replace(')', ')</span>', $variable);
    }

    $pattern1 = '/cat-item-\d+/';
    preg_match_all($pattern1, $variable, $matches);
    if (isset($matches[0])) {
        foreach ($matches[0] as $key => $value) {
            $int = (int) str_replace('cat-item-', '', $value);
            $icon_image_id = get_term_meta($int, 'category-icon-image-id', true);
            if (!empty($icon_image_id)) {
                $icon_image_url = wp_get_attachment_image_url($icon_image_id, 'full');
                $icon_image_alt = get_post_meta($icon_image_id, '_wp_attachment_image_alt', true);
                $replacement = '$1<img class="cats_item-image" src="' . esc_url($icon_image_url) . '" alt="' . (!empty($icon_image_alt) ? esc_attr($icon_image_alt) : '') . '"/>';
                $pattern = '/(cat-item-' . $int . '+.*?><a.*?>)/';
                $variable = preg_replace($pattern, $replacement, $variable);
            }
        }
    }

    return $variable;
}
add_filter('wp_list_categories', 'littledino_categories_postcount_filter');

add_filter( 'get_archives_link', 'littledino_render_archive_widgets', 10, 6 );
function littledino_render_archive_widgets ( $link_html, $url, $text, $format, $before, $after ) {

    $text = wptexturize( $text );
    $url  = esc_url( $url );

    if ( 'link' == $format ) {
        $link_html = "\t<link rel='archives' title='" . esc_attr( $text ) . "' href='$url' />\n";
    } elseif ( 'option' == $format ) {
        $link_html = "\t<option value='$url'>$before $text $after</option>\n";
    } elseif ( 'html' == $format ) {
        $after = str_replace('(', '', $after);
        $after = str_replace(' ', '', $after);
        $after = str_replace('&nbsp;', '', $after);
        $after = str_replace(')', '', $after);

        $after = !empty($after) ? " <span class='post_count'>(".esc_html($after).")</span> " : "";

        $link_html = "<li>".esc_html($before)."<a href='".esc_url($url)."'>".esc_html($text)."</a>".$after."</li>";
    } else { // custom
        $link_html = "\t$before<a href='$url'>$text</a>$after\n";
    }

    return $link_html;
}

// Add image size
if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'littledino-840-600',  840, 600, true  );
    add_image_size( 'littledino-440-440',  440, 440, true  );
    add_image_size( 'littledino-180-180',  180, 180, true  );
    add_image_size( 'littledino-120-120',  120, 120, true  );
}

// Include Woocommerce init if plugin is active
if ( class_exists( 'WooCommerce' ) ) {
    require_once( get_theme_file_path ( '/woocommerce/woocommerce-init.php' ) );
}

add_filter('littledino_enqueue_shortcode_css', 'littledino_render_css');
function littledino_render_css($styles){
    global $littledino_dynamic_css;
    if(! isset($littledino_dynamic_css['style'])){
        $littledino_dynamic_css = [];
        $littledino_dynamic_css['style'] = $styles;
    }else{
        $littledino_dynamic_css['style'] .= $styles;
    }
}