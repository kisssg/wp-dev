<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


if (!class_exists('LittleDino_header_side_area')) {
	class LittleDino_header_side_area extends LittleDino_get_header{

		public function __construct(){
			$this->header_vars();

	   		$pos = LittleDino_Theme_Helper::get_mb_option('side_panel_position', 'mb_customize_side_panel', 'custom');

	   		$content_type = LittleDino_Theme_Helper::get_mb_option('side_panel_content_type','mb_customize_side_panel','custom');
	   		$class = !empty($pos) ? ' side-panel_position_'.$pos : ' side-panel_position_right';

			echo '<div class="side-panel_overlay"></div>';

    		//Get options
			$side_panel_spacing = LittleDino_Theme_Helper::get_mb_option('side_panel_spacing','mb_customize_side_panel','custom');

	        $style  = '';
	        $style .= !empty($side_panel_spacing['padding-top']) ? ' padding-top:'.(int)$side_panel_spacing['padding-top'].'px;' : '' ;
	        $style .= !empty($side_panel_spacing['padding-bottom']) ? ' padding-bottom:'.(int)$side_panel_spacing['padding-bottom'].'px;' : '' ;
	        $style .= !empty($side_panel_spacing['padding-left']) ? ' padding-left:'.(int)$side_panel_spacing['padding-left'].'px;' : '' ;
	        $style .= !empty($side_panel_spacing['padding-right']) ? ' padding-right:'.(int)$side_panel_spacing['padding-right'].'px;' : '' ;
	        $style  = !empty($style) ? ' style="'.$style.'"' : '';

			echo '<section id="side-panel" class="side-panel_widgets'.esc_attr($class).'"'.$this->side_panel_style().'>';
				echo '<a href="#" class="side-panel_close"><span class="side-panel_close_icon"></span></a>';
				echo '<div class="side-panel_sidebar"'.$style.'>';

					switch ($content_type) {
						case 'widgets':
							dynamic_sidebar( 'side_panel' );
							break;
						case 'pages':
							$this->side_panel_get_pages();
							break;
						default:
							dynamic_sidebar( 'side_panel' );
							break;
					}

				echo '</div>';
			echo '</section>';
		}

		public function side_panel_style(){
			$name_preset = $this->name_preset;
	   		$def_preset = $this->def_preset;

			$bg = LittleDino_Theme_Helper::get_option('side_panel_bg');
			$bg = !empty($bg['rgba']) ? $bg['rgba'] : '';

			$color = LittleDino_Theme_Helper::get_option('side_panel_text_color');
			$color = !empty($color['rgba']) ? $color['rgba'] : '';

			$width = LittleDino_Theme_Helper::get_option('side_panel_width');
			$width = !empty($width['width']) ? $width['width'] : '';

			$align = LittleDino_Theme_Helper::get_mb_option('side_panel_text_alignment', 'mb_customize_side_panel', 'custom');
			$style = '';

			if (class_exists( 'RWMB_Loader' ) && $this->id !== 0) {
				$side_panel_switch = rwmb_meta('mb_customize_side_panel');
				if($side_panel_switch === 'custom'){
					$bg = rwmb_meta("mb_side_panel_bg");
					$color = rwmb_meta("mb_side_panel_text_color");
					$width = rwmb_meta("mb_side_panel_width");
				}
			}

			if (!empty($bg)) {
	            $style .= !empty($bg) ? 'background-color: '.esc_attr($bg).';' : '';
	        }

			if (!empty($color)) {
	            $style .= !empty($color) ? 'color: '.esc_attr($color).';' : '';
	        }

	        if (!empty($width)) {
	            $style .= 'width: '.esc_attr((int) $width ).'px;';
	        }

	        $style .= !empty($align) ? 'text-align: '.esc_attr($align).';' : 'text-align:center;';

			$style = !empty($style) ? ' style="'.$style.'"' : '';
			return $style;
		}

        /**
         * @since 1.0.0
         * @version 1.0.6
         */
        public function side_panel_get_pages()
        {
            $page_select = LittleDino_Theme_Helper::get_mb_option('side_panel_page_select', 'mb_customize_side_panel', 'custom');

            if ($page_select) {
                $page_select = intval($page_select);

				if (class_exists('Polylang') && function_exists('pll_current_language')) {
                    $currentLanguage = pll_current_language();
                    $translations = PLL()->model->post->get_translations($page_select);

                    $polylang_id = $translations[$currentLanguage] ?? '';
                    $page_select = $polylang_id ?: $page_select;
                }

                if (class_exists('SitePress')) {
                    $wpml_id = wpml_object_id_filter($page_select, 'side_panel', false, ICL_LANGUAGE_CODE);
                    if (
                        $wpml_id
                        && 'publish' === get_post_status($wpml_id)
                    ) {
                        $page_select = $wpml_id;
                    }
                }

                if (did_action('elementor/loaded')) {
                    echo \Elementor\Plugin::$instance->frontend->get_builder_content($page_select);
                }
            }
        }
	}

    new LittleDino_header_side_area();
}
