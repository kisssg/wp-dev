<?php  if ( ! defined( 'ABSPATH' ) ) { exit; }

if (!class_exists('LittleDino_footer_area')) {
	/**
	 * Footer area
	 *
	 *
	 * @class 		LittleDino_footer_area
	 * @version		1.0
	 * @category	Class
	 * @author 		WebGeniusLab
	 */

    class LittleDino_footer_area {
		/**
		* @since 1.0
		* @access private
		*/

    	private $footer_full_width;
    	private $mb_footer_switch;
    	private $mb_copyright_switch;
    	private $id;

    	function __construct () {
	    	// footer option
	        $footer_switch = LittleDino_Theme_Helper::get_option('footer_switch');
	        $footer_bg_color = LittleDino_Theme_Helper::get_option('footer_bg_color');
	        // copyright option
	        $copyright_switch = LittleDino_Theme_Helper::get_option('copyright_switch');

			//add global variables
	        $this->footer_full_width = LittleDino_Theme_Helper::get_option('footer_full_width');
	        $this->id = get_queried_object_id();

	        if (class_exists( 'RWMB_Loader' ) && $this->id !== 0) {
	            $this->mb_footer_switch = rwmb_meta('mb_footer_switch');
	            if ($this->mb_footer_switch == 'on') {
	                $footer_switch = true;
	                $footer_bg_color = rwmb_meta('mb_footer_bg');
	                $footer_bg_color = !empty($footer_bg_color['color']) ? $footer_bg_color['color'] : "";
	            }elseif (rwmb_meta('mb_footer_switch') == 'off') {
	                $footer_switch = false;
	            }

	            $this->mb_copyright_switch = rwmb_meta('mb_copyright_switch');
	            if ($this->mb_copyright_switch == 'on') {
	                $copyright_switch = true;
	            }elseif ($this->mb_copyright_switch == 'off') {
	                $copyright_switch = false;
	            }
	        }

	        //Footer container style
	        $style = !empty($footer_bg_color) ? ' background-color :'.esc_attr($footer_bg_color).';' : '';
	        $style .= LittleDino_Theme_Helper::bg_render('footer','mb_footer_switch','on');
	        $style = !empty($style) ? ' style="'.esc_attr($style).'"' : '' ;


	        //hide if 404 page
	        $page_not_found = LittleDino_Theme_Helper::get_option('404_show_footer');
			if(is_404() && !(bool) $page_not_found) $footer_switch = $copyright_switch = false;

	        /*
	        *
	        * Footer render
	        */
	        if ($footer_switch || $copyright_switch) {
	            echo "<footer class='footer clearfix'".$style." id='footer'>";
	                if ($footer_switch) {
	                	$footer_content_type = LittleDino_Theme_Helper::get_mb_option('footer_content_type','mb_footer_switch','on');
	                	switch ($footer_content_type) {
	                		case 'widgets':
	                			$this->main_footer_html();
	                			break;
	                		case 'pages':
	                    		$this->main_footer_get_page();
	                			break;
	                		default:
	                			$this->main_footer_html();
	                			break;
	                	}
	                }

	                if ($copyright_switch) {
	                    $this->copyright_html();
	                }

	            echo "</footer>";
	        }
    	}

    	private function get_wave_html(){
    		$wave_switch = LittleDino_Theme_Helper::get_mb_option('footer_add_wave','mb_footer_switch','on');
    		if((bool) $wave_switch){

    			$wave_height = LittleDino_Theme_Helper::get_option('footer_wave_height');
    			$wave_height = isset($wave_height['height']) ? $wave_height['height'] : '';
    			if (class_exists( 'RWMB_Loader' ) && get_queried_object_id() !== 0) {
    				if (rwmb_meta('mb_footer_switch') == 'on') {
    					$wave_height = rwmb_meta('mb_footer_wave_height');
    				}
    			}

    			echo "<div class='littledino_wave_footer' style='height:".(int) $wave_height."px;'>";

					echo '<svg class="wave_bottom" id="OBJECTS" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
						 viewBox="0 0 1920.1 171" preserveAspectRatio="none">';
					echo '<rect x="0" y="171" width="1920.5" height="0"/>';
					echo '<path class="st0" style="fill:#FFFFFF;" d="M0,0v109.7v4V167h0l0.3-39c114.1,20.2,257.5,20.6,391.4,10.3c155.5-12,303.7-37.2,453.9-58.3
						c150.2-21.1,308.7-38.6,462.7-34.1c146.1,4.2,275.9,27.8,405.5,48.9c60.1,9.8,122.3,19.1,186.5,25.8c6.7,1.4,13.5,2.8,20.2,4.3v-2.2
						v-1.8V0H0z"/>';
					echo '<path style="fill:#FFFFFF;" d="M0,0v113.7c191.8,6.7,375-12.2,547.6-34.2c15.9-2,31.8-4.1,47.7-6.1c192.5-25,387.1-51.2,601.9-46.7
						c242.3,5,489.3,48.6,723.3,98.1V0H0z"/>';
					echo '<path class="st1" style="fill:#F3F3F3;" d="M1197.3,26.6c-214.9-4.4-409.4,21.8-601.9,46.7c-15.9,2.1-31.8,4.1-47.7,6.1c-172.6,22-355.8,41-547.6,34.2V171
						h0l0.3-39c114.1,20.2,257.5,20.6,391.4,10.3c155.5-12,303.7-37.2,453.9-58.3c150.2-21.1,308.7-38.6,462.7-34.1
						c146.1,4.2,275.9,27.8,405.5,48.9c66.4,10.8,135.3,21,206.7,27.8v-1.8C1686.6,75.1,1439.5,31.5,1197.3,26.6z"/>';
					echo '</svg>';

    			echo "</div>";
    		}
    	}

    	private function get_footer_vars($optn_1 = null){

			$footer_options = array();

    		//Get options
			$footer_spacing = LittleDino_Theme_Helper::get_mb_option('footer_spacing','mb_footer_switch','on');

	        // Only for widgets in footer
	        if ($optn_1 == 'widgets') {
				$footer_options['widget_columns'] = LittleDino_Theme_Helper::get_option('widget_columns');
		        $footer_options['widget_columns_2'] = LittleDino_Theme_Helper::get_option('widget_columns_2');
		        $footer_options['widget_columns_3'] = LittleDino_Theme_Helper::get_option('widget_columns_3');
		        $footer_align = LittleDino_Theme_Helper::get_option('footer_align');

	    		//footer container class
				$footer_options['footer_class'] = ' align-'.esc_attr($footer_align);
	        }

	        //footer padding style
	        $footer_options['footer_style'] = '';
	        $footer_options['footer_style'] .= !empty($footer_spacing['padding-top']) ? ' padding-top:'.(int)$footer_spacing['padding-top'].'px;' : '' ;
	        $footer_options['footer_style'] .= !empty($footer_spacing['padding-bottom']) ? ' padding-bottom:'.(int)$footer_spacing['padding-bottom'].'px;' : '' ;
	        $footer_options['footer_style'] .= !empty($footer_spacing['padding-left']) ? ' padding-left:'.(int)$footer_spacing['padding-left'].'px;' : '' ;
	        $footer_options['footer_style'] .= !empty($footer_spacing['padding-right']) ? ' padding-right:'.(int)$footer_spacing['padding-right'].'px;' : '' ;
	        $footer_options['footer_style'] = !empty($footer_options['footer_style']) ? ' style="'.$footer_options['footer_style'].'"' : '';

	        // Only for widgets in footer
	        if ($optn_1 == 'widgets') {
		        $footer_options['layout'] = array();
		        switch ((int)$footer_options['widget_columns']) {
		            case 1:
		                $footer_options['layout'] = array('12');
		                break;
		            case 2:
		                $footer_options['layout'] = explode('-', $footer_options['widget_columns_2']);
		                break;
		            case 3:
		                $footer_options['layout'] = explode('-', $footer_options['widget_columns_3']);
		                break;
		            case 4:
		                $footer_options['layout'] = array('3','3','3','3');
		                break;
		            default:
		                $footer_options['layout'] = array('3','3','3','3');
		                break;
		        }
	        }

	        return $footer_options;
    	}

    	private function main_footer_html(){

    		// Get footer vars
	        $footer_vars = $this->get_footer_vars('widgets');
	        extract($footer_vars);

    		echo "<div class='footer_top-area widgets_area column_".(int)$widget_columns.$footer_class."'>";

    			//Render Wave svg
    			$this->get_wave_html();

                if (!$this->footer_full_width) { echo "<div class='wgl-container'>"; }

                $sidebar_exists = false;
                $i = 1;
	            while ($i < (int)$widget_columns + 1) {
					if (is_active_sidebar( 'footer_column_' . $i )) {
						$sidebar_exists = true;
					}
                    $i++;
                }
                if ($sidebar_exists) {
	                echo "<div class='row'".$footer_style.">";
	                	$i = 1;
	                	while ($i < (int)$widget_columns + 1) {
	                		$columns_number = $i - 1;
	                		?>
	                		<div class='wgl_col-<?php echo esc_attr($layout[$columns_number]);?>'>
	                			<?php
	                                if (is_active_sidebar( 'footer_column_' . $i)) dynamic_sidebar( 'footer_column_' . $i);
	                            ?>
	                        </div>
	                        <?php
	                		$i++;
	                	}
	                echo "</div>";
                }

				if (!$this->footer_full_width) { echo "</div>"; }

			echo "</div>";

    	}

        /**
         * @since 1.0.0
         * @version 1.0.6
         */
        private function main_footer_get_page()
        {
            // Get options
            $footer_vars = $this->get_footer_vars('page');
            extract($footer_vars);

            echo '<div class="footer_top-area">';

            // Render Wave svg
            $this->get_wave_html();

            echo '<div class="wgl-container">';
            echo '<div class="row-footer">';
            echo '<div class="row-footer"', $footer_style, '>';

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

                if (did_action('elementor/loaded')) {
                    echo \Elementor\Plugin::$instance->frontend->get_builder_content($footer_page_select_id);
                }
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

    	private function copyright_spacing(){
	        //Get options
    		$copyright_spacing = LittleDino_Theme_Helper::get_mb_option('copyright_spacing','mb_copyright_switch','on');

	        // copyright style
	        $style = '';
	        $style .= !empty($copyright_spacing['padding-top']) ? 'padding-top:'.(int)$copyright_spacing['padding-top'].'px;' : '' ;
	        $style .= !empty($copyright_spacing['padding-bottom']) ? 'padding-bottom:'.(int)$copyright_spacing['padding-bottom'].'px;' : '' ;
	        $style = !empty($style) ? ' style="'.$style.'"' : '';
	        return $style;
    	}

    	private function copyright_style(){
			$bg_color = LittleDino_Theme_Helper::get_mb_option('copyright_bg_color','mb_copyright_switch','on');

			// copyright style
	        $style = '';
	        $style .= !empty($bg_color) ? 'background-color:'.esc_attr($bg_color).';' : '';
	        $style = !empty($style) ? ' style="'.$style.'"' : '';
	        return $style;
    	}

    	private function copyright_html() {
	        $editor = LittleDino_Theme_Helper::get_option('copyright_editor');

	        if ($this->mb_copyright_switch == 'on') {
	        	$editor = rwmb_meta('mb_copyright_editor');
	        }
	        ?>
    		<div class='copyright'<?php echo LittleDino_Theme_Helper::render_html($this->copyright_style()); ?> >
                <?php if (!$this->footer_full_width) echo "<div class='wgl-container'>"; ?>
                	<div class='row' <?php echo LittleDino_Theme_Helper::render_html($this->copyright_spacing());?> >
                       <div class='wgl_col-12'>
                       <?php echo do_shortcode( $editor ); ?>
                       </div>
                	</div>
                <?php if (!$this->footer_full_width) echo "</div>"; ?>
            </div>
            <?php
    	}
    }

    new LittleDino_footer_area();
}