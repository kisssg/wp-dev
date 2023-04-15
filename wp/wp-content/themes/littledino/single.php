<?php

defined('ABSPATH') || exit;

use WglAddons\Templates\WglBlog;
use LittleDino_Theme_Helper as LittleDino;

/**
 * The dedault template for single posts rendering
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package littledino
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 * @version 1.1.2
 */

get_header();
the_post();

$sb = LittleDino::render_sidebars('single');
$column = $sb['column'] ?? '';
$row_class = $sb['row_class'] ?? '';
$container_class = $sb['container_class'] ?? '';
$layout = $sb['layout'] ?? '';

$single_type = LittleDino::get_mb_option('single_type_layout', 'mb_post_layout_conditional', 'custom') ?: 2;

$row_class .= ' single_type-' . $single_type;

if ('3' === $single_type) {
    echo '<div class="post_featured_bg">';
        get_template_part('templates/post/single/post', $single_type . '_image');
    echo '</div>';
}

//* Render
echo '<div class="wgl-container', apply_filters('littledino_container_class', $container_class), '">';
echo '<div class="row', apply_filters('littledino_row_class', $row_class), '">';

    echo '<div id="main-content" class="wgl_col-', apply_filters('littledino_column_class', $column), '">';

        get_template_part('templates/post/single/post', $single_type);

        //* ↓ Navigation
        $previousPost = get_adjacent_post(false, '', true);
        $nextPost  = get_adjacent_post(false, '', false);

        if ($nextPost || $previousPost) {
            ?>
            <div class="littledino-post-navigation">
                <?php
                if (is_a($previousPost, 'WP_Post')) {
                    $image_prev_url = wp_get_attachment_image_url(get_post_thumbnail_id($previousPost->ID), 'thumbnail');

                    $class_image_prev = $image_prev_url ? ' image_exist' : ' no_image';
                    $img_prev_html = "<span class='image_prev" . esc_attr($class_image_prev) . "'>";
                    if ($image_prev_url) {
                        $img_prev_html .= "<img src='" . esc_url($image_prev_url) . "' alt='" . esc_attr($previousPost->post_title) . "'/>";
                    } else {
                        $img_prev_html .= "<span class='no_image_post'></span>";
                    }
                    $img_prev_html .= "<svg class='outter-dashes'><rect rx='50%' ry='50%' x='2' y='2' width='76' height='76'></rect></svg></span>";

                    echo '<div class="prev-link_wrapper">';
                    echo '<div class="info_prev-link_wrapper"><a href="' . esc_url(get_permalink($previousPost->ID)) . '" title="' . esc_attr($previousPost->post_title) . '">' . $img_prev_html . '<span class="prev-link-info_wrapper"><span class="prev_title">' . esc_html($previousPost->post_title) . '</span><span class="meta-wrapper"><span class="date_post">' . esc_html(get_the_time(get_option('date_format'), $previousPost->ID)) . '</span></span></span></a></div>';
                    echo '</div>';
                }
                if (is_a($nextPost, 'WP_Post')) {
                    $image_next_url = wp_get_attachment_image_url(get_post_thumbnail_id($nextPost->ID), 'thumbnail');

                    $class_image_next = $image_next_url ? ' image_exist' : ' no_image';
                    $img_next_html = "<span class='image_next" . esc_attr($class_image_next) . "'>";
                    if ($image_next_url) {
                        $img_next_html .= "<img src='" . esc_url($image_next_url) . "' alt='" . esc_attr($nextPost->post_title) . "'/>";
                    } else {
                        $img_next_html .= "<span class='no_image_post'></span>";
                    }
                    $img_next_html .= "<svg class='outter-dashes'><rect rx='50%' ry='50%' x='2' y='2' width='76' height='76'></rect></svg></span>";

                    echo '<div class="next-link_wrapper">';
                    echo '<div class="info_next-link_wrapper"><a href="' . esc_url(get_permalink($nextPost->ID)) . '" title="' . esc_attr($nextPost->post_title) . '"><span class="next-link-info_wrapper"><span class="next_title">' . esc_html($nextPost->post_title) . '</span><span class="meta-wrapper"><span class="date_post">' . esc_html(get_the_time(get_option('date_format'), $nextPost->ID)) . '</span></span></span>' . $img_next_html . '</a></div>';
                    echo '</div>';
                }
            echo '</div>';
        }


        //* ↓ Related Posts
        $related_posts_enabled = LittleDino::get_option('single_related_posts');

            if (
                class_exists('RWMB_Loader')
                && ($mb_blog_show = rwmb_meta('mb_blog_show_r'))
                && 'default' !== rwmb_meta('mb_blog_show_r')
            ) {
                $related_posts_enabled = 'off' === $mb_blog_show ? null : $mb_blog_show;
            }

            if (
                $related_posts_enabled
                && class_exists('LittleDino_Core')
                && class_exists('\Elementor\Plugin')
            ) {
                $related_cats = [];
                $cats = LittleDino::get_option('blog_cat_r');
                if (!empty($cats)) {
                    $related_cats[] = implode(',', $cats);
                }

                if (
                    class_exists('RWMB_Loader')
                    && get_queried_object_id() !== 0
                    && 'custom' === $mb_blog_show
                ) {
                    $related_cats = get_post_meta(get_the_id(), 'mb_blog_cat_r');
                }
                //* Render
                echo '<div class="single related_posts">';

                    //* Get Cats_Slug
                    if ($categories = get_the_category()) {
                        $post_categ = $post_category_compile = '';
                        foreach ($categories as $category) {
                            $post_categ = $post_categ . $category->slug . ',';
                        }
                        $post_category_compile .= '' . trim($post_categ, ',') . '';

                        if (!empty($related_cats[0])) {
                            $categories = get_categories(['include' => $related_cats[0]]);
                            $post_categ = $post_category_compile = '';
                            foreach ($categories as $category) {
                                $post_categ = $post_categ . $category->slug . ',';
                            }
                            $post_category_compile .= trim($post_categ, ',');
                        }

                        $related_cats = $post_category_compile;
                    }

                    $related_module_title = LittleDino::get_mb_option('blog_title_r', 'mb_blog_show_r', 'custom');

                    echo '<div class="littledino_module_title">',
                        '<h3>',
                            esc_html($related_module_title) ?: esc_html__('Recent Posts', 'littledino'),
                        '</h3>',
                    '</div>';

                    $carousel_layout = LittleDino::get_mb_option('blog_carousel_r', 'mb_blog_show_r', 'custom');
                    $columns_amount = LittleDino::get_mb_option('blog_column_r', 'mb_blog_show_r', 'custom');
                    $posts_amount = LittleDino::get_mb_option('blog_number_r', 'mb_blog_show_r', 'custom');

                    $related_posts_atts = [
                        'blog_layout' => $carousel_layout ? 'carousel' : 'grid',
                        'blog_columns' => $columns_amount ?? (('none' == $layout) ? '4' : '6'),
                        'blog_navigation' => 'none',
                        'use_navigation' => null,
                        'hide_content' => true,
                        'hide_share' => true,
                        'hide_likes' => true,
                        'hide_views' => true,
                        'meta_author' => null,
                        'meta_comments' => true,
                        'read_more_hide' => null,
                        'read_more_text' => esc_html__('Read More', 'littledino'),
                        'heading_tag' => 'h4',
                        'content_letter_count' => 130,
                        'crop_square_img' => 1,
                        'name_load_more' => esc_html__('Load More', 'littledino'),
                        'items_load' => 4,
                        'load_more_text' => esc_html__('Load More', 'littledino'),
                        'read_more_icon_type' => 'font',
                        'read_more_icon_pack' => 'flaticon',
                        'read_more_icon_flaticon' => 'flaticon-footprint',
                        'read_more_icon_fontawesome' => null,
                        'read_more_icon_rotate' => '90',
                        //* Carousel
                        'autoplay' => null,
                        'autoplay_speed' => 3000,
                        'slides_to_scroll' => '',
                        'infinite_loop' => false,
                        'use_pagination' => null,
                        'pag_type' => 'circle',
                        'pag_offset' => '',
                        'custom_resp' => true,
                        'resp_medium' => null,
                        'pag_color' => null,
                        'custom_pag_color' => null,
                        'resp_tablets_slides' => null,
                        'resp_tablets' => null,
                        'resp_medium_slides' => null,
                        'resp_mobile' => '601',
                        'resp_mobile_slides' => '1',
                        //* Query
                        'number_of_posts' => (int) $posts_amount,
                        'categories' => $related_cats,
                        'order_by' => 'rand',
                        'exclude_any' => 'yes',
                        'by_posts' => [$post->post_name => $post->post_title] //* exclude current post
                    ];

                    echo (new WglBlog())->render($related_posts_atts);

                echo '</div>';
            }
            //* Comments
            if (comments_open() || get_comments_number()) {
                echo '<div class="row">';
                echo '<div class="wgl_col-12">';
                    comments_template();
                echo '</div>';
                echo '</div>';
            }

        echo '</div>'; //* #main-content

        //* Sidebar
        echo !empty($sb['content']) ? $sb['content'] : '';

echo '</div>';
echo '</div>';

get_footer();
