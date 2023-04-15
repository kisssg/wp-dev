<?php
if ( !defined( 'ABSPATH' ) ) { exit; }

$css .= '
.theme-gradient input[type="submit"],
.rev_slider .rev-btn.gradient-button,
body .widget .widget-title .widget-title_wrapper:before,
.inside_image.sub_layer_animation .wgl-portfolio-item_description,
.wpb-js-composer .wgl-container .vc_row .vc_general.vc_tta.vc_tta-tabs .vc_tta-tabs-container .vc_tta-tabs-list .vc_tta-tab:before,
.wpb-js-composer .wgl-container .vc_row .vc_general.vc_tta.vc_tta-tabs .vc_tta-panels-container .vc_tta-panels .vc_tta-panel .vc_tta-panel-heading .vc_tta-panel-title:before,
.littledino_module_progress_bar .progress_bar,
.littledino_module_testimonials.type_inline_top .testimonials_meta_wrap:after{';
if ( (bool)$use_gradient_switch ) {
	$css .= '
		background: -webkit-linear-gradient(left, '.$theme_gradient_from.' 0%, '.$theme_gradient_to.' 50%, '.$theme_gradient_from.' 100%);
		background-size: 300%, 1px;
		background-position: 0%;
	}';
} else {
	$css .= 'background-color:'.$theme_color.';}';
}

?>