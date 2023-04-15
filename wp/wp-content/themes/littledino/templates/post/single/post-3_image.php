<?php
    $single = LittleDino_Single_Post::getInstance();
    $single->set_data();
    $title = get_the_title();

    $show_likes = LittleDino_Theme_Helper::get_option('single_likes');
	$single_meta = LittleDino_Theme_Helper::get_option('single_meta');
	$single_date =  LittleDino_Theme_Helper::get_option('single_meta_date');
	$show_views = LittleDino_Theme_Helper::get_option('single_views');
	$single->set_post_views(get_the_ID());

	$meta_args = $meta_args_cats = array();

	if ( !(bool)$single_meta ) {
		$meta_args['author'] = !(bool)LittleDino_Theme_Helper::get_option('single_meta_author');
		$meta_args['date'] = !(bool)LittleDino_Theme_Helper::get_option('single_meta_date');
		$meta_args['comments'] = !(bool)LittleDino_Theme_Helper::get_option('single_meta_comments');
		$meta_args_cats['category'] = !(bool)LittleDino_Theme_Helper::get_option('single_meta_categories');
	}

	$page_title_padding = LittleDino_Theme_Helper::get_mb_option('single_padding_layout_3', 'mb_post_layout_conditional', 'custom');
	$page_title_padding_top = !empty($page_title_padding['padding-top']) ? (int)$page_title_padding['padding-top'] : '';
	$page_title_padding_bottom = !empty($page_title_padding['padding-bottom']) ? (int)$page_title_padding['padding-bottom'] : '';
	$page_title_styles = '';
	$page_title_styles .= !empty($page_title_padding_top) ?  'padding-top:'.esc_attr((int) $page_title_padding_top).'px;' : '';
	$page_title_styles .= !empty($page_title_padding_bottom) ?  'padding-bottom:'.esc_attr((int) $page_title_padding_bottom).'px;' : '';
	$page_title_top = !empty($page_title_padding_top) ? $page_title_padding_top : 200;

	$apply_animation = LittleDino_Theme_Helper::get_mb_option('single_apply_animation', 'mb_post_layout_conditional', 'custom');
	$data_attr_image = $data_attr_content = $blog_skrollr_class = '';

	if(!empty($apply_animation)){
		wp_enqueue_script('skrollr', get_template_directory_uri() . '/js/skrollr.min.js', array(), false, false);

		$data_attr_image = ' data-center="background-position: 50% 0px;" data-top-bottom="background-position: 50% -100px;" data-anchor-target=".blog-post-single-item"';

	    $data_attr_content = ' data-center="opacity: 1" data-'.esc_attr($page_title_top).'-top="opacity: 1" data-0-top="opacity: 0" data-anchor-target=".blog-post-single-item .blog-post_content"';
	    $blog_skrollr_class = ' blog_skrollr_init';
	}

?>

<div class="blog-post<?php echo esc_attr($blog_skrollr_class);?> blog-post-single-item format-<?php echo esc_attr($single->get_pf());?>"<?php echo !empty($page_title_styles) ? ' style="'.esc_attr($page_title_styles).'"' : ''?>>
	<div <?php post_class("single_meta"); ?>>
		<div class="item_wrapper">
			<div class="blog-post_content" >
				<?php

					$single->render_bg(false, 'full', false, $data_attr_image);

					echo '<div class="wgl-container">';

					echo '<div class="row">';

					echo '<div class="content-container wgl_col-12"'.$data_attr_content.'>';

    				echo '<div class="content-container_wrapper">';

					//Post Meta render cats
					if ( !(bool)$single_meta && !empty($meta_args_cats) ) {
						$single->render_post_meta($meta_args_cats);
					}
				?>

					<h1 class="blog-post_title"><?php echo get_the_title(); ?></h1>

				<?php
					echo '<div class="blog-post_meta-wrap">';

					//Post Meta render date, author
					if ( !(bool)$single_meta ) {
						$single->render_post_meta($meta_args);
					}

					echo '</div>';
				?>
					<!-- Close content-container -->
					</div>

					<!-- Close content-container -->
					</div>

					<!-- Close Row -->
					</div>

					<!-- Close Container -->
					</div>
			</div>
		</div>
	</div>
</div>