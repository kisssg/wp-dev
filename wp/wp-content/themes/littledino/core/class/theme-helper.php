<?php

defined('ABSPATH') || exit;


if (!class_exists('LittleDino_Theme_Helper')) {
    /**
     * LittleDino Theme Helper
     *
     *
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.1.4
     */
    class LittleDino_Theme_Helper
    {
        private static $instance;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Retrieves Redux option.
         *
         * @since 1.0.0
         * @version 1.1.2
         *
         * @param string $name  Desired option name.
         * @return string|null  Option value or `null` if value wasn't set.
         */
        public static function get_option($name, $preset = null, $def_preset = null)
        {
            if (class_exists('Redux')) {
                $preset = 'default' == $preset ? null : $preset;

                if (!$preset) {

                    // Customizer
                    if (!empty($GLOBALS['littledino_set'])) {
                        $theme_options = $GLOBALS['littledino_set'];
                    } else {
                        $theme_options = get_option('littledino_set');
                    }

                } else {
                    $theme_options = get_option('littledino_set_preset');
                }

                if (empty($theme_options)) {
                    $theme_options = get_option('littledino_default_options');
                }

                if (!$preset) {
                    return $theme_options[$name] ?? null;
                }

                if (!empty($def_preset)) {
                    return $theme_options['default'][$preset][$name] ?? null;
                } else {
                    return $theme_options[$preset][$name] ?? null;
                }


            } else {
                $default_option = get_option('littledino_default_options');

                return $default_option[$name] ?? null;
            }
        }

        /**
         * Retrieves Metabox option.
         *
         * Assumes that all RWMB options share same name
         * as their redux analogues, prefixed with `mb_` string.
         *
         * @since 1.1.2
         * @version 1.1.4
         *
         * @param string $name              Desired option name.
         * @param string $dependency_key    Optional. Key of related metabox option,
         *                                  on which desired option depends.
         * @param string $dependency_value  Optional. Value of related metabox option,
         *                                  on which desired option depends.
         *
         * @return string rwmb value.
         * @return string redux value, if condition isn't met or rwmb value wasn't set.
         */
        public static function get_mb_option(
            String $name,
            $dependency_key = null,
	        $dependency_value = null
        ) {
	        $mb_option = '';

            $id = !is_archive() ? get_queried_object_id() : 0;

            if (
                class_exists('RWMB_Loader')
                && 0 !== $id
            ) {
                if (
                    $dependency_key
                    && $dependency_value
                ) {
                    if ($dependency_value == rwmb_meta($dependency_key)) {
                        $mb_option = rwmb_meta('mb_' . $name);
                    }
                } else {
                    $mb_option = rwmb_meta('mb_' . $name);
                }
            }

            return '' !== $mb_option
                ? $mb_option
                : self::get_option($name);
        }

        /**
         * @since 1.0.0
         * @deprecated 1.1.2
         */
        public static function options_compare($name, $dependency_key = null, $dependency_value = null)
        {
            return self::get_mb_option($name, $dependency_key, $dependency_value);
        }

        /**
         * @since 1.0.0
         * @version 1.1.8
         */
        public static function bg_render(
            String $name,
            $dependency_key = false,
            $dependency_value = false
        ) {
            $id = !is_archive() ? get_queried_object_id() : 0;

            if (
                class_exists('RWMB_Loader')
                && 0 !== $id
            ) {
                if (
                    $dependency_key
                    && $dependency_value === rwmb_meta($dependency_key)
                ) {
                    $mb_image = rwmb_meta('mb_' . $name . '_bg');
                } elseif ('on' === rwmb_meta('mb_page_title_switch')) {
                    $mb_image = rwmb_meta('mb_page_title_bg');
                }
            }

            $redux_image = self::get_option($name . '_bg_image');

            $src = !empty($mb_image['image'])
                ? $mb_image['image']
                : ($redux_image['background-image'] ?? '');

            $repeat = !empty($mb_image['repeat'])
                ? $mb_image['repeat']
                : ($redux_image['background-repeat'] ?? '');

            $size = !empty($mb_image['size'])
                ? $mb_image['size']
                : ($redux_image['background-size'] ?? '');

            $attachment = !empty($mb_image['attachment'])
                ? $mb_image['attachment']
                : ($redux_image['background-attachment'] ?? '');

            $position = !empty($mb_image['position'])
                ? $mb_image['position']
                : ($redux_image['background-position'] ?? '');

            // Collect attributes
            if ($src) {
                $style = 'background-image: url(' . esc_url($src) . ');';
                $style .= $size ? ' background-size:' . esc_attr($size) . ';' : '';
                $style .= $repeat ? ' background-repeat:' . esc_attr($repeat) . ';' : '';
                $style .= $attachment ? ' background-attachment:' . esc_attr($attachment) . ';' : '';
                $style .= $position ? ' background-position:' . esc_attr($position) . ';' : '';
            }

            return $style ?? '';
        }

        /**
         * @since 1.0.0
         * @version 1.1.8
         */
        public static function preloader()
        {
            if (!self::get_option('preloader')) {
                return;
            }

            $wrapper_bg = self::get_option('preloader_background');

            $wrapper_style = $wrapper_bg ? ' style=background-color:' . esc_attr($wrapper_bg) . ';' : '';

            echo '<div id="preloader-wrapper" ', $wrapper_style, '>',
                '<div class="preloader-container">',
                    '<div class="cssload-loader"></div>',
                '</div>',
            '</div>';
        }

        /**
         * @since 1.0.0
         * @version 1.1.2
         */
        public static function pagination($range = 5, $query = false, $alignment = 'left')
        {
            if ($query != false) {
                $wp_query = $query;
            } else {
                global $paged, $wp_query;
            }
            if (empty($paged)) {
                $query_vars = $wp_query->query_vars;
                $paged = $query_vars['paged'] ?? 1;
            }

            $max_page = $wp_query->max_num_pages;

            if ($max_page < 2) {
                // Abort, if no need for pagination
                return;
            }

            switch ($alignment) {
                case 'right':
                    $class_alignment = ' aright';
                    break;
                case 'center':
                    $class_alignment = ' acenter';
                    break;
                default:
                case 'left':
                    $class_alignment = '';
                    break;
            }

            $big = 999999999;

            $test_pag = paginate_links([
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'type' => 'array',
                'current' => max(1, $paged),
                'total' => $max_page,
                'prev_text' => '<i class="fa fa-angle-left"></i>',
                'next_text' => '<i class="fa fa-angle-right"></i>',
            ]);
            $test_comp = '';
            foreach ($test_pag as $value) {
                $test_comp .= '<li class="page">' . $value . '</li>';
            }

            return '<ul class="wgl-pagination' . esc_attr($class_alignment) . '">' . $test_comp . '</ul>';
        }

        public static function hexToRGB($hex = "#ffffff")
        {
            $color = array();
            if (strlen($hex) < 1) {
                $hex = "#ffffff";
            }

            $color['r'] = hexdec(substr($hex, 1, 2));
            $color['g'] = hexdec(substr($hex, 3, 2));
            $color['b'] = hexdec(substr($hex, 5, 2));

            return $color['r'] . "," . $color['g'] . "," . $color['b'];
        }

        /**
         * @link https://github.com/opensolutions/smarty/blob/master/plugins/modifier.truncate.php
         */
        public static function modifier_character(
            $string,
            $length = 80,
            $etc = '... ',
            $break_words = false
        ) {
            if (0 == $length) {
                return '';
            }

            if (mb_strlen($string, 'utf8') > $length) {
                $length -= mb_strlen($etc, 'utf8');
                if (!$break_words) {
                    $string = preg_replace('/\s+\S+\s*$/su', '', mb_substr($string, 0, $length + 1, 'utf8'));
                }

                return mb_substr($string, 0, $length, 'utf8') . $etc;
            } else {
                return $string;
            }
        }

		public static function load_more(
            $query = false,
            $name_load_more = '',
            $class = ''
        ) {
			$name_load_more = !empty($name_load_more) ? $name_load_more : esc_html__('Load More', 'littledino');

			$uniq = uniqid();
			$ajax_data_str = htmlspecialchars(json_encode($query), ENT_QUOTES, 'UTF-8');

			$out = '<div class="clear"></div>';
			$out .= '<div class="load_more_wrapper'.(!empty($class) ? ' '.esc_attr($class) : '' ).'">';

            $out .= '<div class="button_wrapper">';
                $out .= '<span class="button__wrapper">';
				    $out .= '<a href="#" class="load_more_item"><span>'.esc_html($name_load_more).'</span></a>';
                    $out .= '<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" class="wgl-dashes"><rect x="5" y="5" rx="25" ry="25" width="179.344" height="53"/></svg>';
                $out .= '</span>';
			$out .= '</div>';
			$out .= '<form class="posts_grid_ajax">';
				$out .= "<input type='hidden' class='ajax_data' name='".esc_attr($uniq)."_ajax_data' value='$ajax_data_str' />";
			$out .= '</form>';
			$out .= '</div>';

			return $out;
		}

        public static function header_preset_name()
        {
            $id = !is_archive() ? get_queried_object_id() : 0;
            $name_preset = '';

            // Redux options header
            $name_preset = self::get_option('header_def_js_preset');
            $get_def_name = get_option( 'littledino_set_preset' );
            if ( !self::in_array_r($name_preset, get_option( 'littledino_set_preset' ))) {
                $name_preset = 'default';
            }

            // Metaboxes options header
            if (class_exists( 'RWMB_Loader' ) && $id !== 0) {
                $customize_header = rwmb_meta('mb_customize_header');
                if (!empty($customize_header) && rwmb_meta('mb_customize_header') != 'default') {
                    $name_preset = rwmb_meta('mb_customize_header');
                    if ( !self::in_array_r($name_preset, get_option( 'littledino_set_preset' ))) {
                        $name_preset = 'default';
                    }
                }
            }
            return $name_preset;
        }

        public static function render_html($args)
        {
            return $args ?? '';
        }

        public static function in_array_r($needle, $haystack, $strict = false)
        {
            if (is_array($haystack)) {
                foreach ($haystack as $item) {
                    if (
                        ($strict ? $item === $needle : $item == $needle)
                        || (is_array($item) && self::in_array_r($needle, $item, $strict))
                    ) {
                        return true;
                    }
                }
            }

            return false;
        }

        public static function render_sidebars($args = 'page')
        {
            $output = array();
            $sidebar_style = '';

            $layout = self::get_option( $args . '_sidebar_layout');
            $sidebar = self::get_option( $args . '_sidebar_def');
            $sidebar_width = self::get_option($args . '_sidebar_def_width');
            $sticky_sidebar = self::get_option($args . '_sidebar_sticky');
            $sidebar_gap = self::get_option($args . '_sidebar_gap');
            $sidebar_class = $sidebar_style = '';

            $littledino_core = class_exists('LittleDino_Core');

            if (is_archive() || is_search() || is_home() || is_page()) {
                if (!$littledino_core) {
                    if (is_active_sidebar( 'sidebar_main-sidebar' )) {
                        $layout = 'right';
                        $sidebar = 'sidebar_main-sidebar';
                        $sidebar_width = 9;
                    }
                }
            }

            if (function_exists('is_shop') &&  is_shop()) {
                if (!$littledino_core) {
                    if (is_active_sidebar( 'shop_products' )) {
                        $layout = 'right';
                        $sidebar = 'shop_products';
                        $sidebar_width = 9;
                    } else {
                        $column = 12;
                        $sidebar = '';
                        $layout = 'none';
                    }
                }
            }

            if (is_single()) {
                if (!$littledino_core) {
                    if (function_exists('is_product') && is_product()) {
                        if (is_active_sidebar( 'shop_single' )) {
                            $layout = 'right';
                            $sidebar = 'shop_single';
                            $sidebar_width = 9;
                        }
                    } elseif(is_active_sidebar( 'sidebar_main-sidebar' )) {
                        $layout = 'right';
                        $sidebar = 'sidebar_main-sidebar';
                        $sidebar_width = 9;
                    }
                }
            }

            $id = !is_archive() ? get_queried_object_id() : 0;

            if (
                class_exists('RWMB_Loader')
                && 0 !== $id
            ) {
                $mb_layout = rwmb_meta('mb_page_sidebar_layout');
                if ($mb_layout && $mb_layout != 'default') {
                    $layout = $mb_layout;
                    $sidebar = rwmb_meta('mb_page_sidebar_def');
                    $sidebar_width = rwmb_meta('mb_page_sidebar_def_width');
                    $sticky_sidebar = rwmb_meta('mb_sticky_sidebar');
                    $sidebar_gap = rwmb_meta('mb_sidebar_gap');
                }
            }

            if ($sticky_sidebar) {
                wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js');
                $sidebar_class .= 'sticky-sidebar';
            }

            if (
                isset($sidebar_gap)
                && 'def' != $sidebar_gap
                && 'default' != $layout
            ) {
                $layout_pos = $layout == 'left' ? 'right' : 'left';
                $sidebar_style = 'style="padding-' . $layout_pos . ': ' . $sidebar_gap . 'px;"';
            }

            $column = 12;
            if ($layout == 'left' || $layout == 'right') {
                $column = (int) $sidebar_width;
            } else {
                $sidebar = '';
            }

            //* GET Params sidebar
            if (!empty($_GET['shop_sidebar'])) {
                $layout = $_GET['shop_sidebar'];
                $sidebar = 'shop_products';
                $column = 9;
            }

            if (!is_active_sidebar($sidebar)) {
                $column = 12;
                $sidebar = '';
                $layout = 'none';
            }

            $output['column'] = $column;
            $output['row_class'] = $layout != 'none' ? ' sidebar_'.esc_attr($layout) : '';
            $output['container_class'] = $layout != 'none' ? ' wgl-content-sidebar' : '';
            $output['layout'] = $layout;
            $output['content'] = '';

            if ($layout == 'left' || $layout == 'right') {
                    $output['content'] .= '<div class="sidebar-container '.$sidebar_class.' wgl_col-'.(12 - (int)$column).'" '.$sidebar_style.'>';
                        if (is_active_sidebar( $sidebar )) {
                            $output['content'] .= "<aside class='sidebar'>";
                                ob_start();
                                    dynamic_sidebar( $sidebar );
                                $output['content'] .= ob_get_clean();
                            $output['content'] .= "</aside>";
                        }
                    $output['content'] .= "</div>";
            }

            return $output;
        }

        public static function posted_meta_on()
        {
            global $post;

            printf(
                '<span><time class="entry-date published" datetime="%1$s">%2$s</time></span><span>' . esc_html__('Published in', 'littledino') . ' <a href="%3$s" rel="gallery">%4$s</a></span>',
                esc_attr(get_the_date('c')),
                esc_html(get_the_date()),
                esc_url(get_permalink($post->post_parent)),
                esc_html(get_the_title($post->post_parent))
            );

            printf(
                '<span class="author vcard">%1$s</span>',
                sprintf(
                    '<a class="url fn n" href="%1$s">%2$s</a>',
                    esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                    esc_html(get_the_author())
                )
            );

            $metadata = wp_get_attachment_metadata();

            if ($metadata) {
                printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s" title="%2$s">%1$s %3$s &times; %4$s</a></span>',
                    esc_html_x( 'Full size', 'Used before full size attachment link.', 'littledino' ),
                    esc_url( wp_get_attachment_url() ),
                    esc_attr( absint( $metadata['width'] ) ),
                    esc_attr( absint( $metadata['height'] ) )
                );
            }

            $kses_allowed_html = [
                'span' => ['id' => true, 'class' => true, 'style' => true],
                'br' => ['id' => true, 'class' => true, 'style' => true],
                'em' => ['id' => true, 'class' => true, 'style' => true],
                'b' => ['id' => true, 'class' => true, 'style' => true],
                'strong' => ['id' => true, 'class' => true, 'style' => true],
            ];

            edit_post_link(
                /* translators: %s: Name of current post */
                sprintf(
                    wp_kses(__('Edit<span class="screen-reader-text"> "%s"</span>', 'littledino'), $kses_allowed_html),
                        get_the_title()
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
        }

        public static function hexagon_html($fill = '#fff' , $shadow = false) {

            $rgb = self::hexToRGB($fill);
            $svg_shadow = (bool)$shadow ? 'filter: drop-shadow(4px 5px 4px rgba('.$rgb.',0.3));' : '';

            $output = '<div class="littledino_hexagon"><svg style="'.esc_attr($svg_shadow).' fill: '.esc_attr($fill).';" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 177.4 197.4"><path d="M0,58.4v79.9c0,6.5,3.5,12.6,9.2,15.8l70.5,40.2c5.6,3.2,12.4,3.2,18,0l70.5-40.2c5.7-3.2,9.2-9.3,9.2-15.8V58.4 c0-6.5-3.5-12.6-9.2-15.8L97.7,2.4c-5.6-3.2-12.4-3.2-18,0L9.2,42.5C3.5,45.8,0,51.8,0,58.4z"/></svg></div>';

            return $output;
        }

        public static function render_html_attributes( array $attributes ) {
            $rendered_attributes = [];

            foreach ( $attributes as $attribute_key => $attribute_values ) {
                if ( is_array( $attribute_values ) ) {
                    $attribute_values = implode( ' ', $attribute_values );
                }

                $rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
            }

            return implode( ' ', $rendered_attributes );
        }

        /**
         * Check licence activation
         */
        public static function wgl_theme_activated()
        {
            $licence_key = get_option('wgl_licence_validated');
            $licence_key = empty($licence_key) ? get_option(Wgl_Theme_Verify::get_instance()->item_id) : $licence_key;

            if (!empty($licence_key)) {
                return $licence_key;
            }

            return false;
        }
    }

    new LittleDino_Theme_Helper();
}
