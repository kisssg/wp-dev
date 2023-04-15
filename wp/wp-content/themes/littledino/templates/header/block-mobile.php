<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


if (!class_exists('LittleDino_header_mobile')) {
    class LittleDino_header_mobile extends LittleDino_get_header
    {
        public function __construct()
        {
			$this->header_vars();
	   		$this->html_render = 'mobile';
	   		$name_preset = $this->name_preset;
	   		$def_preset = $this->def_preset;

	   		$header_mobile_background = LittleDino_Theme_Helper::get_option('mobile_background');
	   		$header_mobile_color = LittleDino_Theme_Helper::get_option('mobile_color');
	   		$mobile_header_custom =  LittleDino_Theme_Helper::get_option('mobile_header');

	   		$mobile_styles = '';
	   		$mobile_styles .= !empty($header_mobile_background['rgba']) ? 'background-color: '.(esc_attr($header_mobile_background['rgba'])).';' : '';
	   		$mobile_styles .= !empty($header_mobile_color) ? 'color: '.(esc_attr($header_mobile_color)).';' : '';
	   		$mobile_styles = !empty($mobile_styles) ? ' style="'.$mobile_styles.'"' : '';

	   		echo "<div class='wgl-mobile-header'".(!empty($mobile_styles) ? $mobile_styles : '').">";
	   		echo "<div class='container-wrapper'>";
	   		if (!empty($mobile_header_custom)) {
	   			$this->build_header_layout('mobile');
	   		} else {
	   			$this->default_header_mobile();
	   		}

	   		$this->build_header_mobile_menu($name_preset, $def_preset);
	   		echo "</div>";

	   		echo "</div>";
		}

        public function default_header_mobile()
        {
			$mobile_height = LittleDino_Theme_Helper::get_option('header_mobile_height');

			if (isset($mobile_height['height'])) {
                $mobile_height_style = 'height:'.(esc_attr((int)$mobile_height['height'])).'px;';
            }
            $mobile_height_style = !empty($mobile_height_style) ? ' style="' . $mobile_height_style . '"' : '';

			echo "<div class='wgl-header-row'>";
				echo "<div class='fullwidth-wrapper'>";
					echo "<div class='wgl-header-row_wrapper'" . $mobile_height_style . ">";

						echo "<div class='header_side display_grow v_align_middle h_align_left'>";
							echo "<div class='header_area_container'>";
							if (has_nav_menu('main_menu')) {
								echo "<nav class='primary-nav'>";
								if (function_exists('littledino_main_menu')) {
									$menu = '';
						   			if (class_exists('RWMB_Loader') && $this->id !== 0) {
						   				if (rwmb_meta('mb_customize_header_layout') == 'custom') {
						   					$menu = rwmb_meta('mb_menu_header');
						   				}
						   			}
						   			littledino_main_menu($menu);
								}
								echo "</nav>";
								echo '<div class="mobile-hamburger-toggle"><div class="hamburger-box"><div class="hamburger-inner"></div></div></div>';
							}
							echo "</div>";
						echo "</div>";

						echo "<div class='header_side display_grow v_align_middle h_align_center'>";
							echo "<div class='header_area_container'>";
								parent::get_logo('mobile');
							echo "</div>";
						echo "</div>";

						echo "<div class='header_side display_grow v_align_middle h_align_right'>";
							echo "<div class='header_area_container'>";
								echo LittleDino_Theme_Helper::render_html($this->search('mobile', ''));
							echo '</div>';
						echo '</div>';

					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	}

    new LittleDino_header_mobile();
}
