"use strict";

is_visible_init();
littledino_slick_navigation_init();

jQuery(document).ready(function($) {
	littledino_split_slider();
	littledino_sticky_init();
	littledino_search_init();
	littledino_side_panel_init();
	littledino_mobile_header();
	littledino_woocommerce_helper();
	littledino_woocommerce_tools();
	littledino_woocommerce_filters();
	littledino_woocommerce_tabs();
	littledino_woocommerce_login_in();
	littledino_init_timeline_appear();
	littledino_accordion_init();
	littledino_striped_services_init();
	littledino_progress_bars_init();
	littledino_carousel_slick();
	littledino_image_comparison();
	littledino_counter_init();
	littledino_countdown_init ();
	littledino_circuit_services();
	littledino_circuit_services_resize();
	littledino_img_layers();
	littledino_page_title_parallax();
	littledino_extended_parallax();
	littledino_portfolio_parallax();
	littledino_message_anim_init();
	littledino_scroll_up();
	littledino_link_scroll();
	littledino_skrollr_init();
	littledino_sticky_sidebar ();
	littledino_videobox_init ();
	littledino_parallax_video();
	littledino_tabs_init();
	littledino_select_wrap();
	littledino_button_wrap();
	jQuery( '.wgl_module_title .carousel_arrows' ).littledino_slick_navigation();
	jQuery( '.wgl-products > .carousel_arrows' ).littledino_slick_navigation();
	jQuery( '.littledino_module_custom_image_cats > .carousel_arrows' ).littledino_slick_navigation();
	littledino_scroll_animation();
	littledino_woocommerce_mini_cart();
	littledino_woocommerce_notifications();
	littledino_text_background();
	littledino_image_parallax();
	littledino_dynamic_styles();
	littledino_multi_headings();
});

jQuery(window).load(function() {
	littledino_isotope();
	littledino_blog_masonry_init();
	setTimeout(function(){
		jQuery('#preloader-wrapper').fadeOut();
	},1100);
	particles_custom();

	littledino_menu_lavalamp();
	jQuery(".wgl-currency-stripe_scrolling").each(function(){
    	jQuery(this).simplemarquee({
	        speed: 40,
	        space: 0,
	        handleHover: true,
	        handleResize: true
	    });
    })
    littledino_wgl_dashes();
});

jQuery( window ).resize(
	function() {
		littledino_wgl_dashes();
	}
);