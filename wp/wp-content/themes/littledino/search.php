<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @subpackage LittleDino
 * @since 1.0.0
 * @version 1.0.5
 */

get_header();

$sb = LittleDino_Theme_Helper::render_sidebars();
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '';
$container_class = $sb['container_class'] ?? '';

?>
    <div class="wgl-container<?php echo apply_filters('littledino_container_class', $container_class); ?>">
        <div class="row<?php echo apply_filters('littledino_row_class', $row_class); ?>">
            <div id='main-content' class="wgl_col-<?php echo apply_filters('littledino_column_class', $column); ?>">
            <?php
                if ( have_posts() ) :
                ?>
                    <header class="searсh-header">
                        <h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'littledino' ), '<span>' .get_search_query(). '</span>' ); ?></h1>
                    </header>
                    <?php
                    global $wgl_blog_atts;
                    global $wp_query;

                    $wgl_blog_atts = array(
                        'query' => $wp_query,
                        'animation_class' => '',
                        // General
                        'blog_layout' => 'grid',
                        // Content
                        'blog_columns' => '12',
                        'hide_media' => false,
                        'hide_content' => false,
                        'hide_blog_title' => false,
                        'hide_postmeta' => false,
                        'meta_author' => false,
                        'meta_date' => false,
                        'meta_comments' => false,
                        'meta_categories' => true,
                        'hide_likes' => true,
                        'hide_share' => true,
                        'read_more_hide' => false,
                        'read_more_text' => esc_html__( 'Learn More', 'littledino' ),
                        'content_letter_count' => '85',
                        'crop_square_img' => 'true',
                        'heading_tag' => 'h3',
                        'items_load'  => 4,
                        'heading_margin_bottom' => '16px',
                        'read_more_icon_type' => 'font',
                        'read_more_icon_pack' => 'flaticon',
                        'read_more_icon_flaticon' => 'flaticon-footprint',
                        'read_more_icon_fontawesome' => '',

                    );
                    get_template_part('templates/post/posts-list');
                    /* Start the Loop */
                    echo LittleDino_Theme_Helper::pagination();

                else :
                    ?>
                    <div class="page_404_wrapper">
                        <header class="searсh-header">
                            <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'littledino' ); ?></h1>
                        </header>

                        <div class="page-content">
                            <?php if ( is_search() ) : ?>
                                <p class="banner_404_text"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'littledino' ); ?></p>
                            <?php else : ?>
                                <p class="banner_404_text"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'littledino' ); ?></p>
                            <?php endif; ?>
                            <div class="search_result_form">
                                <?php get_search_form(); ?>
                            </div>
                            <div class="littledino_404_button littledino_module_button wgl_button wgl_button-l">
                                <span class="button__wrapper">
                                    <a class="wgl_button_link" href="<?php echo esc_url(home_url('/')); ?>"><span><?php esc_html_e( 'Take Me Home', 'littledino' ); ?></span></a>   
                                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" class="wgl-dashes"><rect x="5" y="5" rx="25" ry="25" width="165.859" height="50"/></svg>
                                </span>
                                
                            </div>  
                        </div>
                        
                    </div>
                    <?php
                endif;
                ?>          
            </div>
            <?php
                echo !empty($sb['content']) ? $sb['content'] : '';
            ?>
        </div>
    </div>

<?php

get_footer(); ?>