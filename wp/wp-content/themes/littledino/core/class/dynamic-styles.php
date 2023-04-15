<?php

defined('ABSPATH') || exit;

/**
 * Dynamic Styles
 *
 *
 * @package littledino\core\class
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 * @version 1.1.8
 */
class LittleDino_dynamic_styles
{
    protected static $instance;

    private $gtdu;
    private $use_minify;

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function register_script()
    {
        $this->gtdu = get_template_directory_uri();
        $this->use_minify = LittleDino_Theme_Helper::get_option('use_minified') ? '.min' : '';
        // Register action
        add_action('wp_enqueue_scripts', [$this, 'css_reg']);
        add_action('wp_enqueue_scripts', [$this, 'js_reg']);
        // Register action for Admin
        add_action('admin_enqueue_scripts', [$this, 'admin_css_reg']);
        add_action('admin_enqueue_scripts', [$this, 'admin_js_reg']);

        add_action('wp_enqueue_scripts', [$this, 'get_elementor_css_cache_footer'] );
    }

    /* Register CSS */
    public function css_reg()
    {
        wp_enqueue_style('littledino-default-style', get_bloginfo('stylesheet_url'));
        // Flaticon register
        wp_enqueue_style('flaticon', $this->gtdu . '/fonts/flaticon/flaticon.css');
        // Font-Awesome
        wp_enqueue_style('font-awesome', $this->gtdu . '/css/font-awesome.min.css');
        wp_enqueue_style('littledino-main', $this->gtdu . '/css/main' . $this->use_minify . '.css');
        // Rtl css
		if (is_rtl()) {
			wp_enqueue_style('littledino-rtl', get_template_directory_uri() . '/css/rtl' . $this->use_minify . '.css');
		}
    }

    /* Register JS */
    public function js_reg()
    {
        wp_enqueue_script('littledino-theme-addons', $this->gtdu . '/js/theme-addons' . $this->use_minify . '.js', array('jquery'), false, true);
        wp_enqueue_script('littledino-theme', $this->gtdu . '/js/theme.js', ['jquery'], false, true);

        wp_localize_script('littledino-theme', 'wgl_core', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'slickSlider' => esc_url(get_template_directory_uri() . '/js/slick.min.js'),
            'JarallaxPlugin' => esc_url(get_template_directory_uri() . '/js/jarallax-video.min.js'),
            'JarallaxPluginVideo' => esc_url(get_template_directory_uri() . '/js/jarallax.min.js'),
            'like' => esc_html__('Like', 'littledino'),
            'unlike' => esc_html__('Unlike', 'littledino')
        ));

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        wp_enqueue_script('perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.min.js');

        if (is_404()) {
            wp_enqueue_script('parallax', get_template_directory_uri() . '/js/parallax.min.js');
        }
    }

    /* Register css for admin panel */
    public function admin_css_reg()
    {
        // Font-awesome
        wp_enqueue_style('font-awesome', $this->gtdu . '/css/font-awesome.min.css');
        // Main admin styles
        wp_enqueue_style('littledino-admin', $this->gtdu . '/core/admin/css/admin.css');
        // Add standard wp color picker
        wp_enqueue_style('wp-color-picker');
    }

    /* Register css and js for admin panel */
    public function admin_js_reg()
    {
        /* Register JS */
        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');
	    wp_localize_script('wp-color-picker', 'wpColorPickerL10n', array(
		    'clear'            => esc_html__('Clear', 'littledino'),
		    'clearAriaLabel'   => esc_html__('Clear color', 'littledino'),
		    'defaultString'    => esc_html__('Default', 'littledino'),
		    'defaultAriaLabel' => esc_html__('Select default color', 'littledino'),
		    'pick'             => esc_html__('Select', 'littledino'),
		    'defaultLabel'     => esc_html__('Color value', 'littledino'),
	    ));

	    // Admin Js
        wp_enqueue_script('littledino-admin', $this->gtdu . '/core/admin/js/admin.js');
		// If active Metabox IO
		if (class_exists('RWMB_Loader')) {
			wp_enqueue_script('littledino-metaboxes', $this->gtdu . '/core/admin/js/metaboxes.js');
		}

        $currentTheme = wp_get_theme();
        $theme_name = $currentTheme->parent() == false ? wp_get_theme()->get( 'Name' ) : wp_get_theme()->parent()->get( 'Name' );
        $theme_name = trim($theme_name);

        $purchase_code = $email = '';
        if( LittleDino_Theme_Helper::wgl_theme_activated() ){
            $theme_details = get_option('wgl_licence_validated');
            $purchase_code = $theme_details['purchase'];
            $email = $theme_details['email'];
        }

        wp_localize_script('littledino-admin', 'wgl_verify', [
            'ajaxurl' => esc_js(admin_url('admin-ajax.php')),
            'wglUrlActivate' => esc_js(Wgl_Theme_Verify::get_instance()->api. 'verification'),
            'wglUrlDeactivate' => esc_js(Wgl_Theme_Verify::get_instance()->api. 'deactivate'),
            'domainUrl' => esc_js(site_url( '/' )),
            'themeName' => esc_js($theme_name),
            'purchaseCode' => esc_js($purchase_code),
            'email' => esc_js($email),
            'message' => esc_js(esc_html__( 'Thank you, your license has been validated', 'littledino' )),
            'ajax_nonce' => esc_js( wp_create_nonce('_notice_nonce') )
        ]);
    }

    public function get_elementor_css_cache_footer()
    {
        // footer option
        $footer_switch = LittleDino_Theme_Helper::get_option('footer_switch');

        if (class_exists('RWMB_Loader') && get_queried_object_id() !== 0) {
            if (rwmb_meta('mb_footer_switch') == 'on') {
                $footer_switch = true;
            } elseif (rwmb_meta('mb_footer_switch') == 'off') {
                $footer_switch = false;
            }
        }

        //hide if 404 page
        $page_not_found = LittleDino_Theme_Helper::get_option('404_show_footer');
        if (is_404() && !(bool) $page_not_found) $footer_switch = false;

        if ($footer_switch) {
            $footer_content_type = LittleDino_Theme_Helper::get_mb_option('footer_content_type','mb_footer_switch','on');
            if (
				'pages' == $footer_content_type
				&& class_exists('\Elementor\Core\Files\CSS\Post')
            ) {

                $footer_page_select = LittleDino_Theme_Helper::get_mb_option('footer_page_select', 'mb_footer_switch', 'on');

                if ($footer_page_select) {
                    $footer_page_select_id = intval($footer_page_select);

                    if (class_exists('Polylang') && function_exists('pll_current_language')) {
                        $currentLanguage = pll_current_language();
                        $translations = PLL()->model->post->get_translations($footer_page_select_id);

                        $polylang_footer_id = $translations[$currentLanguage] ?? '';
                        $footer_page_select_id = !empty($polylang_footer_id) ? $polylang_footer_id : $footer_page_select_id;
                    }

                    if (class_exists('SitePress')) {
                        $footer_page_select_id = wpml_object_id_filter($footer_page_select_id, 'footer', false, ICL_LANGUAGE_CODE);
                    }

                    $css_file = new \Elementor\Core\Files\CSS\Post($footer_page_select_id);
                    \Elementor\Plugin::$instance->frontend->enqueue_styles();
                    $css_file->enqueue();
                }
            }
        }
    }

    public function init_style()
    {
		add_action('wp_enqueue_scripts', [$this, 'add_style'] );
        add_action('wp_enqueue_scripts', [$this, 'elementor_column_fix'] );
    }

    public function minify_css($css = null)
    {
        if (!$css) return;

        $css = str_replace(',{', '{', $css);
        $css = str_replace(', ', ',', $css);
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        $css = trim($css);

        return $css;
    }

    /**
     * @since 1.0.0
     * @version 1.1.8
     */
    public function add_style()
    {
        $css = '';
        /*-----------------------------------------------------------------------------------*/
        /* Body Style
        /*-----------------------------------------------------------------------------------*/
        $page_colors_switch = LittleDino_Theme_Helper::get_mb_option('page_colors_switch', 'mb_page_colors_switch', 'custom');
        $use_gradient_switch = LittleDino_Theme_Helper::get_mb_option('use-gradient', 'mb_page_colors_switch', 'custom');
        if ($page_colors_switch == 'custom') {
            $theme_color = LittleDino_Theme_Helper::get_mb_option('page_theme_color', 'mb_page_colors_switch', 'custom');
            $theme_secondary_color = LittleDino_Theme_Helper::get_mb_option('page_theme_secondary_color', 'mb_page_colors_switch', 'custom');
            $theme_third_color = LittleDino_Theme_Helper::get_mb_option('page_theme_third_color', 'mb_page_colors_switch', 'custom');

            $bg_body = LittleDino_Theme_Helper::get_mb_option('body_background_color', 'mb_page_colors_switch', 'custom');
            // Go top color
            $scroll_up_bg_color = LittleDino_Theme_Helper::get_mb_option('scroll_up_bg_color', 'mb_page_colors_switch', 'custom');
            $scroll_up_arrow_color = LittleDino_Theme_Helper::get_mb_option('scroll_up_arrow_color', 'mb_page_colors_switch', 'custom');
            // Gradient colors
            $theme_gradient_from = LittleDino_Theme_Helper::get_mb_option('theme-gradient-from', 'mb_page_colors_switch', 'custom');
            $theme_gradient_to = LittleDino_Theme_Helper::get_mb_option('theme-gradient-to', 'mb_page_colors_switch', 'custom');
        } else {
            $theme_color = esc_attr(LittleDino_Theme_Helper::get_option('theme-custom-color'));
            $theme_secondary_color = esc_attr(LittleDino_Theme_Helper::get_option('theme-secondary-color'));
            $theme_third_color = esc_attr(LittleDino_Theme_Helper::get_option('theme-third-color'));

            $bg_body = esc_attr(LittleDino_Theme_Helper::get_option('body-background-color'));
            // Go top color
            $scroll_up_bg_color = LittleDino_Theme_Helper::get_option('scroll_up_bg_color');
            $scroll_up_arrow_color = LittleDino_Theme_Helper::get_option('scroll_up_arrow_color');
            // Gradient colors
            $theme_gradient = LittleDino_Theme_Helper::get_option('theme-gradient');
            $theme_gradient_from = $theme_gradient['from'] ?? '';
            $theme_gradient_to = $theme_gradient['to'] ?? '';
        }

        /*-----------------------------------------------------------------------------------*/
        /* \End Body style
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Body Add Class
        /*-----------------------------------------------------------------------------------*/
        if ((bool) $use_gradient_switch) {
            add_filter('body_class', function ($classes) {
                return array_merge($classes, array('theme-gradient'));
            });
            $gradient_class = '.theme-gradient';
        } else {
            $gradient_class = '';
        }
        if (defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.0', '>=' )) {
			if(
                empty(get_option( 'elementor_element_wrappers_legacy_mode' )) 
                || \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' )
            ){
				add_filter( 'body_class', function( $classes ) {
					return array_merge( $classes, array( 'new-elementor' ) );
				} );
			}
		}
        /*-----------------------------------------------------------------------------------*/
        /* End Body Add Class
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Header Typography
        /*-----------------------------------------------------------------------------------*/
        $header_font = LittleDino_Theme_Helper::get_option('header-font');

        $header_font_family = $header_font_weight = $header_font_color = '';
        if (!empty($header_font)) {
            $header_font_family = esc_attr($header_font['font-family']);
            $header_font_weight = esc_attr($header_font['font-weight']);
            $header_font_color = esc_attr($header_font['color']);
        }

        // Add Heading h1,h2,h3,h4,h5,h6 variables
        for ($i = 1; $i <= 6; $i++) {
            ${'header-h' . $i} = LittleDino_Theme_Helper::get_option('header-h' . $i);
            ${'header-h' . $i . '_family'} = ${'header-h' . $i . '_weight'} = ${'header-h' . $i . '_line_height'} = ${'header-h' . $i . '_size'} = ${'header-h' . $i . '_text_transform'} = '';

            if (!empty(${'header-h' . $i})) {
                ${'header-h' . $i . '_family'} = !empty(${'header-h' . $i}["font-family"]) ? esc_attr(${'header-h' . $i}["font-family"]) : '';
                ${'header-h' . $i . '_weight'} = !empty(${'header-h' . $i}["font-weight"]) ? esc_attr(${'header-h' . $i}["font-weight"]) : '';
                ${'header-h' . $i . '_line_height'} = !empty(${'header-h' . $i}["line-height"]) ? esc_attr(${'header-h' . $i}["line-height"]) : '';
                ${'header-h' . $i . '_size'} = !empty(${'header-h' . $i}["font-size"]) ? esc_attr(${'header-h' . $i}["font-size"]) : '';
                ${'header-h' . $i . '_text_transform'} = !empty(${'header-h' . $i}["text-transform"]) ? esc_attr(${'header-h' . $i}["text-transform"]) : '';
            }
        }

        /*-----------------------------------------------------------------------------------*/
        /* \End Header Typography
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Body Typography
        /*-----------------------------------------------------------------------------------*/
        $main_font = LittleDino_Theme_Helper::get_option('main-font');
        $content_font_family = $content_line_height = $content_font_size = $content_font_weight = $content_color = '';
        if (!empty($main_font)) {
            $content_font_family = esc_attr($main_font['font-family']);
            $content_font_size = esc_attr($main_font['font-size']);
            $content_font_weight = esc_attr($main_font['font-weight']);
            $content_color = esc_attr($main_font['color']);
            $content_line_height = esc_attr($main_font['line-height']);
            $content_line_height = !empty($content_line_height) ? round(((int) $content_line_height / (int) $content_font_size), 3) : '';
        }

        /*-----------------------------------------------------------------------------------*/
        /* \End Body Typography
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Menu, Sub-menu Typography
        /*-----------------------------------------------------------------------------------*/
        $menu_font = LittleDino_Theme_Helper::get_option('menu-font');
        $menu_font_family = $menu_font_weight = $menu_font_line_height = $menu_font_size = '';
        if (!empty($menu_font)) {
            $menu_font_family = !empty($menu_font['font-family']) ? esc_attr($menu_font['font-family']) : '';
            $menu_font_weight = !empty($menu_font['font-weight']) ? esc_attr($menu_font['font-weight']) : '';
            $menu_font_line_height = !empty($menu_font['line-height']) ? esc_attr($menu_font['line-height']) : '';
            $menu_font_size = !empty($menu_font['font-size']) ? esc_attr($menu_font['font-size']) : '';
        }

        $sub_menu_font = LittleDino_Theme_Helper::get_option('sub-menu-font');
        $sub_menu_font_family = $sub_menu_font_weight = $sub_menu_font_line_height = $sub_menu_font_size = '';
        if (!empty($sub_menu_font)) {
            $sub_menu_font_family = !empty($sub_menu_font['font-family']) ? esc_attr($sub_menu_font['font-family']) : '';
            $sub_menu_font_weight = !empty($sub_menu_font['font-weight']) ? esc_attr($sub_menu_font['font-weight']) : '';
            $sub_menu_font_line_height = !empty($sub_menu_font['line-height']) ? esc_attr($sub_menu_font['line-height']) : '';
            $sub_menu_font_size = !empty($sub_menu_font['font-size']) ? esc_attr($sub_menu_font['font-size']) : '';
        }
        /*-----------------------------------------------------------------------------------*/
        /* \End Menu, Sub-menu Typography
        /*-----------------------------------------------------------------------------------*/

        $name_preset = LittleDino_Theme_Helper::header_preset_name();
        $get_def_name = get_option('littledino_set_preset');
        $def_preset = false;
        if (
            isset($get_def_name['default'])
            && $name_preset
            && array_key_exists($name_preset, $get_def_name['default'])
            && !array_key_exists($name_preset, $get_def_name)
        ) {
            $def_preset = true;
        }

        $menu_color_top = LittleDino_Theme_Helper::get_option('header_top_color', $name_preset, $def_preset);
        if (!empty($menu_color_top['rgba'])) {
            $menu_color_top = !empty($menu_color_top['rgba']) ? esc_attr($menu_color_top['rgba']) : '';
        }

        $menu_color_middle = LittleDino_Theme_Helper::get_option('header_middle_color', $name_preset, $def_preset);
        if (!empty($menu_color_middle['rgba'])) {
            $menu_color_middle = !empty($menu_color_middle['rgba']) ? esc_attr($menu_color_middle['rgba']) : '';
        }

        $menu_color_bottom = LittleDino_Theme_Helper::get_option('header_bottom_color', $name_preset, $def_preset);
        if (!empty($menu_color_bottom['rgba'])) {
            $menu_color_bottom = !empty($menu_color_bottom['rgba']) ? esc_attr($menu_color_bottom['rgba']) : '';
        }

        // Set Queries width to apply mobile style
        $sub_menu_color = LittleDino_Theme_Helper::get_option('sub_menu_color', $name_preset, $def_preset);
        $sub_menu_bg = LittleDino_Theme_Helper::get_option('sub_menu_background', $name_preset, $def_preset);
        $sub_menu_bg = $sub_menu_bg['rgba'];

        $sub_menu_border = LittleDino_Theme_Helper::get_option('header_sub_menu_bottom_border', $name_preset, $def_preset);
        $sub_menu_border_height = LittleDino_Theme_Helper::get_option('header_sub_menu_border_height', $name_preset, $def_preset);
        $sub_menu_border_height = $sub_menu_border_height['height'];
        $sub_menu_border_color = LittleDino_Theme_Helper::get_option('header_sub_menu_bottom_border_color', $name_preset, $def_preset);
        if (!empty($sub_menu_border)) {
            $css .= '.primary-nav ul li ul li:not(:last-child) {'
                . (!empty($sub_menu_border_height) ? 'border-bottom-width: ' . (int) (esc_attr($sub_menu_border_height)) . 'px;' : '')
                . (!empty($sub_menu_border_color['rgba']) ? 'border-bottom-color: ' . esc_attr($sub_menu_border_color['rgba']) . ';' : '') . '
                border-bottom-style: solid;
            }';
        }

        $mobile_sub_menu_bg = LittleDino_Theme_Helper::get_option('mobile_sub_menu_background');
        $mobile_sub_menu_bg = $mobile_sub_menu_bg['rgba'];

        $mobile_sub_menu_overlay = LittleDino_Theme_Helper::get_option('mobile_sub_menu_overlay');
        $mobile_sub_menu_overlay = $mobile_sub_menu_overlay['rgba'];

        $mobile_sub_menu_color = LittleDino_Theme_Helper::get_option('mobile_sub_menu_color');

        $hex_header_font_color = LittleDino_Theme_Helper::HexToRGB($header_font_color);
        $hex_theme_color = LittleDino_Theme_Helper::HexToRGB($theme_color);

        $hex_theme_content =  LittleDino_Theme_Helper::HexToRGB($content_color);

        // sticky header logo
        $header_sticky_height = LittleDino_Theme_Helper::get_option('header_sticky_height');
        $header_sticky_height = (int) $header_sticky_height['height'] . 'px';
        // sticky header color
        $header_sticky_color = LittleDino_Theme_Helper::get_option('header_sticky_color');

        $footer_text_color = LittleDino_Theme_Helper::get_option('footer_text_color');
        $footer_heading_color = LittleDino_Theme_Helper::get_option('footer_heading_color');

        $copyright_text_color = LittleDino_Theme_Helper::get_mb_option('copyright_text_color', 'mb_copyright_switch', 'on');

        // Page Title Background Color
        $page_title_bg_color = LittleDino_Theme_Helper::get_option('page_title_bg_color');
        $hex_page_title_bg_color = LittleDino_Theme_Helper::HexToRGB($page_title_bg_color);

        /*-----------------------------------------------------------------------------------*/
        /* Side Panel Css
        /*-----------------------------------------------------------------------------------*/
        $side_panel_title = LittleDino_Theme_Helper::get_option('side_panel_title_color');
        $side_panel_title = !empty($side_panel_title['rgba']) ? $side_panel_title['rgba'] : '';

        if (
            class_exists('RWMB_Loader')
            && get_queried_object_id() !== 0
            && rwmb_meta('mb_customize_side_panel') === 'custom'
        ) {
            $side_panel_title = rwmb_meta('mb_side_panel_title_color');
        }

        /*-----------------------------------------------------------------------------------*/
        /* \End Side Panel Css
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Parse css
        /*-----------------------------------------------------------------------------------*/
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        $filename_class = get_class ($wp_filesystem);
        if($filename_class === 'WP_Filesystem_FTPext'){
            $wp_filesystem = function_exists('wgl_theme_helper') && method_exists(wgl_theme_helper(), 'get_file_system') ? wgl_theme_helper()->get_file_system() : $wp_filesystem;
        }

        $files = array('theme_content', 'theme_color', 'footer');
        if (class_exists('WooCommerce')) {
            array_push($files, 'shop');
        }
        foreach ($files as $key => $file) {
            $file = get_theme_file_path('/core/admin/css/dynamic/' . $file . '.css');
            if ($wp_filesystem->exists($file)) {
                $file = $wp_filesystem->get_contents($file);
                preg_match_all('/\s*\\$([A-Za-z1-9_\-]+)(\s*:\s*(.*?);)?\s*/', $file, $vars);

                $found     = $vars[0];
                $varNames  = $vars[1];
                $count     = count($found);

                for ($i = 0; $i < $count; $i++) {
                    $varName  = trim($varNames[$i]);
                    $file = preg_replace('/\\$' . $varName . '(\W|\z)/', (isset(${$varName}) ? ${$varName} : "") . '\\1', $file);
                }

                $line = str_replace($found, '', $file);

                $css .= $line;
            }
        }
        /*-----------------------------------------------------------------------------------*/
        /* \End Parse css
        /*-----------------------------------------------------------------------------------*/

        $css .= 'body {'
            . (!empty($bg_body) ? 'background:' . $bg_body . ';' : '') . '
        }
        ol.commentlist:after {
            ' . (!empty($bg_body) ? 'background:' . $bg_body . ';' : '') . '
        }';

        /*-----------------------------------------------------------------------------------*/
        /* Typography render
        /*-----------------------------------------------------------------------------------*/
        for ($i = 1; $i <= 6; $i++) {
            $css .= 'h' . $i . ',h' . $i . ' a, h' . $i . ' span {
                ' . (!empty(${'header-h' . $i . '_family'}) ? 'font-family:' . ${'header-h' . $i . '_family'} . ';' : '') . '
                ' . (!empty(${'header-h' . $i . '_weight'}) ? 'font-weight:' . ${'header-h' . $i . '_weight'} . ';' : '') . '
                ' . (!empty(${'header-h' . $i . '_size'}) ? 'font-size:' . ${'header-h' . $i . '_size'} . ';' : '') . '
                ' . (!empty(${'header-h' . $i . '_line_height'}) ? 'line-height:' . ${'header-h' . $i . '_line_height'} . ';' : '') . '
                ' . (!empty(${'header-h' . $i . '_text_transform'}) ? 'text-transform:' . ${'header-h' . $i . '_text_transform'} . ';' : '') . '
            }';
        }
        /*-----------------------------------------------------------------------------------*/
        /* \End Typography render
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Mobile Header render
        /*-----------------------------------------------------------------------------------*/
        $mobile_header = LittleDino_Theme_Helper::get_option('mobile_header');

        // Fetch mobile header height to apply it for mobile styles
        $header_mobile_height = LittleDino_Theme_Helper::get_option('header_mobile_height');
        $header_mobile_min_height = !empty($header_mobile_height['height']) ? 'calc(100vh - ' . esc_attr((int) $header_mobile_height['height']) . 'px - 30px)' : '';
        $header_mobile_height = !empty($header_mobile_height['height']) ? 'calc(100vh - ' . esc_attr((int) $header_mobile_height['height']) . 'px)' : '';

        // Set Queries width to apply mobile style
        $header_queries = LittleDino_Theme_Helper::get_option('header_mobile_queris', $name_preset, $def_preset);
        $mobile_over_content = LittleDino_Theme_Helper::get_option('mobile_over_content');

        if ($mobile_header == '1') {
            $mobile_background = LittleDino_Theme_Helper::get_option('mobile_background');
            $mobile_color = LittleDino_Theme_Helper::get_option('mobile_color');

            $css .= '@media only screen and (max-width: ' . (int) $header_queries . 'px){
                .wgl-theme-header{
                    background-color: ' . esc_attr($mobile_background['rgba']) . ' !important;
                    color: ' . esc_attr($mobile_color) . ' !important;
                }
                .hamburger-inner, .hamburger-inner:before, .hamburger-inner:after{
                    background-color:' . esc_attr($mobile_color) . ';
                }
            }';
        }

        $css .= '@media only screen and (max-width: ' . (int) $header_queries . 'px){
            .wgl-theme-header .wgl-mobile-header{
                display: block;
            }
            .wgl-site-header{
                display:none;
            }
            .wgl-theme-header .mobile-hamburger-toggle{
                display: inline-block;
            }
            .wgl-theme-header .primary-nav{
                display:none;
            }
            header.wgl-theme-header .mobile_nav_wrapper .primary-nav{
                display:block;
            }
            .wgl-theme-header .wgl-sticky-header{
                display: none;
            }
            .wgl-social-share_pages{
                display: none;
            }
        }';

        if ($mobile_over_content == '1') {
            $css .= '@media only screen and (max-width: ' . (int) $header_queries . 'px){
                .wgl-theme-header{
                    position: absolute;
                    z-index: 99;
                    width: 100%;
                    left: 0;
                    top: 0;
                }
            }';
        } else {
            $css .= '@media only screen and (max-width: ' . (int) $header_queries . 'px){
                body .wgl-theme-header.header_overlap{
                    position: relative;
                    z-index: 2;
                }
            }';
        }
        /*-----------------------------------------------------------------------------------*/
        /* \End Mobile Header render
        /*-----------------------------------------------------------------------------------*/

        /**
         * Page Title Responsive
         */
        $css .= $this->get_page_title_responsive_extra_css();
        //* ↑ page title responsive

        /*-----------------------------------------------------------------------------------*/
        /* Footer page css
        /*-----------------------------------------------------------------------------------*/
        $footer_switch = LittleDino_Theme_Helper::get_option('footer_switch');
        if ($footer_switch) {
            $footer_content_type = LittleDino_Theme_Helper::get_option('footer_content_type');
            if (
                class_exists('RWMB_Loader')
                && get_queried_object_id() !== 0
                && rwmb_meta('mb_footer_switch') == 'on'
            ) {
                $footer_content_type = rwmb_meta('mb_footer_content_type');
            }

            if ($footer_content_type == 'pages') {
                $footer_page_id = LittleDino_Theme_Helper::get_mb_option('footer_page_select');
                if ($footer_page_id) {
                    $footer_page_id = intval($footer_page_id);
                    $shortcodes_css = get_post_meta($footer_page_id, '_wpb_shortcodes_custom_css', true);
                    if (!empty($shortcodes_css)) {
                        $shortcodes_css = strip_tags($shortcodes_css);
                        $css .= $shortcodes_css;
                    }
                }
            }
        }
        /*-----------------------------------------------------------------------------------*/
        /* \End Footer page css
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Gradient css
        /*-----------------------------------------------------------------------------------*/

        require_once (get_theme_file_path('/core/admin/css/dynamic/gradient.php'));

        /*-----------------------------------------------------------------------------------*/
        /* \End Gradient css
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Elementor Theme css
        /*-----------------------------------------------------------------------------------*/

		if (did_action('elementor/loaded')) {

            if (defined('ELEMENTOR_VERSION')) {
                if (version_compare(ELEMENTOR_VERSION, '3.0', '<')) {
                    $container_width = get_option('elementor_container_width');
                    $container_width = !empty($container_width) ? $container_width : 1140;
                } else {
                    //* Page settings manager
                    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
                    $kit_id = (new \Elementor\Core\Kits\Manager())->get_active_id();

                    $meta_key = \Elementor\Core\Settings\Page\Manager::META_KEY;
                    $kit_settings = get_post_meta($kit_id, $meta_key, true);

                    if (!$kit_settings) {
                        $container_width = 1140;
                     } else {
                        $container_width = $kit_settings['container_width']['size'] ?? 1140;
                    }
                }
            }

			$css .= 'body.elementor-page main .wgl-container.wgl-content-sidebar,
			body.elementor-editor-active main .wgl-container.wgl-content-sidebar,
			body.elementor-editor-preview main .wgl-container.wgl-content-sidebar {
				max-width: ' . intval($container_width) . 'px;
				margin-left: auto;
				margin-right: auto;
			}';

			$css .= 'body.single main .wgl-container {
				max-width: ' . intval($container_width) . 'px;
				margin-left: auto;
				margin-right: auto;
			}';
		}

        /*-----------------------------------------------------------------------------------*/
        /* \End Elementor Theme css
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /* Add Inline css
        /*-----------------------------------------------------------------------------------*/

        $css = $this->minify_css($css);
        wp_add_inline_style('littledino-main', $css);

        /*-----------------------------------------------------------------------------------*/
        /* \End Add Inline css
        /*-----------------------------------------------------------------------------------*/
    }

    /**
     * @since 1.1.8
     */
    protected function get_page_title_responsive_extra_css()
    {
        $responsive_disabled = !LittleDino_Theme_Helper::get_option('page_title_resp_switch');

        if (
            $this->RWMB_is_active()
            && 'on' === rwmb_meta('mb_page_title_switch')
            && rwmb_meta('mb_page_title_resp_switch')
        ) {
            $responsive_disabled = false;
        }

        if ($responsive_disabled) {
            // Bailout.
            return;
        }

        $pt_padding = LittleDino_Theme_Helper::get_mb_option('page_title_resp_padding', 'mb_page_title_resp_switch', true);
        $pt_height = LittleDino_Theme_Helper::get_mb_option('page_title_resp_height', 'mb_page_title_resp_switch', true);
        $pt_height = $pt_height['height'] ?? $pt_height;

        $extra_css = '.page-header {'
            . (!empty($pt_padding['padding-top']) ? 'padding-top:' . esc_attr((int) $pt_padding['padding-top']) . 'px !important;' : '')
            . (!empty($pt_padding['padding-bottom']) ? 'padding-bottom:' . esc_attr((int) $pt_padding['padding-bottom']) . 'px !important;' : '')
            . (!empty($pt_height) ? 'height:' . esc_attr((int) $pt_height) . 'px !important;' : '')
        . '}';

        $breadcrumbs_switch = LittleDino_Theme_Helper::get_mb_option('page_title_resp_breadcrumbs_switch', 'mb_page_title_resp_switch', true);

        // Title
        $pt_font = LittleDino_Theme_Helper::get_mb_option('page_title_resp_font', 'mb_page_title_resp_switch', true);
        $pt_color = !empty($pt_font['color']) ? 'color:' . esc_attr($pt_font['color']) . ' !important;' : '';
        $pt_f_size = !empty($pt_font['font-size']) ? 'font-size:' . esc_attr((int) $pt_font['font-size']) . 'px !important;' : '';
        $pt_line_height = !empty($pt_font['line-height']) ? 'line-height:' . esc_attr((int) $pt_font['line-height']) . 'px !important;' : '';
        $pt_additional_style = !(bool) $breadcrumbs_switch ? 'margin-bottom: 0 !important;' : '';
        $title_style = $pt_color . $pt_f_size . $pt_line_height . $pt_additional_style;

        $extra_css .= '.page-header_content .page-header_title {' . $title_style . '}';

        // Breadcrumbs
        $page_title_breadcrumbs_font = LittleDino_Theme_Helper::get_mb_option('page_title_resp_breadcrumbs_font', 'mb_page_title_resp_switch', true);
        $breadcrumbs_color = !empty($page_title_breadcrumbs_font['color']) ? 'color:' . $page_title_breadcrumbs_font['color'] . ' !important;' : '';
        $breadcrumbs_f_size = !empty($page_title_breadcrumbs_font['font-size']) ? 'font-size:' . (int) $page_title_breadcrumbs_font['font-size'] . 'px !important;' : '';
        $breadcrumbs_line_height = !empty($page_title_breadcrumbs_font['line-height']) ? 'line-height:' . (int) $page_title_breadcrumbs_font['line-height'] . 'px !important;' : '';
        $breadcrumbs_display = !(bool) $breadcrumbs_switch ? 'display: none !important;' : '';
        $breadcrumbs_style = $breadcrumbs_color . $breadcrumbs_f_size . $breadcrumbs_line_height . $breadcrumbs_display;

        $extra_css .= '.page-header_content .page-header_breadcrumbs {' . $breadcrumbs_style . '}';

        $pt_breakpoint = (int) LittleDino_Theme_Helper::get_mb_option('page_title_resp_resolution', 'mb_page_title_resp_switch', true);

        return '@media (max-width: ' . $pt_breakpoint . 'px) {' . $extra_css . '}';
    }

    public function elementor_column_fix()
	{
        $css = '.elementor-column-gap-default > .elementor-column > .elementor-element-populated{
            padding-left: 15px;
            padding-right: 15px;
        }';

        wp_add_inline_style( 'elementor-frontend', $css );
    }

    /**
     * @since 1.1.8
     */
    public function RWMB_is_active()
    {
        $id = !is_archive() ? get_queried_object_id() : 0;

        return class_exists('RWMB_Loader') && 0 !== $id;
    }
}

if (!function_exists('littledino_dynamic_styles')) {
    function littledino_dynamic_styles()
    {
        return LittleDino_dynamic_styles::instance();
    }
}

littledino_dynamic_styles()->register_script();
littledino_dynamic_styles()->init_style();
