<?php
/**
 * The template for displaying 404 page
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package    WordPress
 * @subpackage LittleDino
 * @since      1.0
 * @version    1.0
 */
get_header();

$styles = '';
$bg_render = LittleDino_Theme_Helper::bg_render('404_page_main');
$apply_animation = LittleDino_Theme_Helper::get_option('404_parallax_apply_animation');
$animation_speed = LittleDino_Theme_Helper::get_option('404_parallax_speed');
$main_bg_color = LittleDino_Theme_Helper::get_option('404_page_main_bg_image')['background-color'];

$styles .= !empty($main_bg_color) ? 'background-color:'.$main_bg_color.';' : '';
$styles .= !empty($bg_render) ? $bg_render : '';
?>
	<div class="wgl-container full-width">
		<div class="row">
			<div class="wgl_col-12">
				<section  class="page_404_wrapper"<?php echo ( empty($apply_animation) && !empty($styles) ? ' style="'.esc_attr($styles).'"' : ''  );?>>
					<?php
						if(!empty($apply_animation)){
							?>
							<div class="wgl-background-image_parallax">
								<div data-depth="<?php echo (!empty($animation_speed) ? esc_attr($animation_speed) : 0.03 );?>" <?php echo (!empty($styles) ? ' style="'.esc_attr($styles).'"' : '');?>></div>	
							</div>
						<?php
						}
					?>
					<div class="page_404_wrapper-container">
						<div class="row">
							<div class="wgl_col-12 wgl_col-md-12">
								<div class="main_404-wrapper">
									<div class="banner_404">
										<p class="banner_404_desc_number"><?php echo esc_html__('Ooops!', 'littledino');?></p>
										<h1 class="banner_404_number"><?php echo esc_html('404');?></h1>
									</div>
									<h2 class="banner_404_title"><?php echo esc_html__( 'Page Not Found', 'littledino' ); ?></h2>
									<p class="banner_404_text"><?php echo esc_html__( 'The page you are looking for was moved, removed, renamed or never existed.', 'littledino' ); ?></p>
									<div class="littledino_404_search">
										<?php get_search_form(); ?>
									</div>
									<div class="littledino_404_button littledino_module_button wgl_button wgl_button-l">
										<span class="button__wrapper">
											<a class="wgl_button_link" href="<?php echo esc_url(home_url('/')); ?>"><span><?php esc_html_e( 'Take Me Home', 'littledino' ); ?></span></a>	
											<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" class="wgl-dashes"><rect x="5" y="5" rx="25" ry="25" height="50" width="165"/></svg>
										</span>
										
									</div>									
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
<?php get_footer(); ?>