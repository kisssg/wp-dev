<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
* LittleDino Woocommerce
*
*
* @class        LittleDino_Woocoommerce
* @version      1.0
* @category     Class
* @author       WebGeniusLab
*/

if (!class_exists('LittleDino_Woocoommerce')) {
	class LittleDino_Woocoommerce{
	    /**
		* Generate lauout template
		*
		*
		* @since 1.0
		* @access private
		*/
		private $row_class;
		private $container_class;
		private $column;
		private $content;


		public function __construct ( ){
			add_action( 'after_setup_theme', array( $this, 'setup' ) );
			add_action( 'woocommerce_init', array( $this, 'init' ) );
			add_filter( 'woocommerce_show_page_title', '__return_false' );

			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'woocommerce_template_loop_product_thumbnail' ), 10);
		}

		public function setup() {
			// Declare WooCommerce support.
			add_theme_support( 'woocommerce', apply_filters( 'littledino_woocommerce_args', array(
				'single_image_width'    => 1080,
				'thumbnail_image_width' => 540,
				'gallery_thumbnail_image_width' => 240,
				'product_grid'          => array(
					'default_columns' => (int) LittleDino_Theme_Helper::get_option('shop_column'),
					'default_rows'    => 4,
					'min_columns'     => 1,
					'max_columns'     => 6,
					'min_rows'        => 1,),
			) ) );

			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
			// Declare support for title theme feature.
			add_theme_support( 'title-tag' );

			// Declare support for selective refreshing of widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );
		}

		public function init (){

			remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper',       10 );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end',   10 );
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10, 0 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5, 0 );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10, 0 );
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10, 0 );
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

			// ↓ reassign call stack ↓
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 29 );
			// ↑ reassign call stack ↑

			add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 10 );
			add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 20 );

			// ↓ WGL Page Template ↓
			add_action( 'woocommerce_before_main_content', array( $this, 'wgl_page_template_open' ), 10 );
			add_action( 'woocommerce_after_main_content',  array( $this, 'wgl_page_template_close' ), 10 );
			// ↑ WGL Page Template ↑

			// ↓ WGL Wrapper Sorting ↓
			add_action( 'woocommerce_before_shop_loop', array( $this, 'wgl_sorting_wrapper_open' ), 9 );
			add_action( 'woocommerce_before_shop_loop', array( $this, 'wgl_sorting_wrapper_close' ), 31 );
			// ↑ WGL Wrapper Sorting ↑

			// ↓ loop ↓
			add_action( 'woocommerce_shop_loop_item_title', array( $this, 'template_loop_product_open' ), 5 );
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'template_loop_product_close' ), 15 );

			add_action( 'woocommerce_shop_loop_item_title', array( $this, 'template_loop_product_title' ), 10 );
			add_filter( 'loop_shop_per_page', array( $this, 'loop_products_per_page' ), 20 );

			add_filter( 'woocommerce_before_shop_loop_item', array( $this, 'loop_products_item_wrapper' ), 20 );
			add_filter( 'woocommerce_after_shop_loop_item', array( $this, 'loop_products_item_wrapper_close' ), 20 );
			// ↑ loop ↑

			// ↓ widgets ↓
			add_action( 'woocommerce_before_mini_cart', array( $this, 'minicart_wrapper_open' ) );
			add_action( 'woocommerce_after_mini_cart', array( $this, 'minicart_wrapper_close' ) );
			add_action( 'wp_ajax_woocommerce_remove_from_cart', array( $this, 'ajax_remove_from_cart' ), 1000 );
			add_action( 'wp_ajax_nopriv_woocommerce_remove_from_cart', array( $this, 'ajax_remove_from_cart' ), 1000 );

			if (defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0', '<' )) {
				add_filter( 'add_to_cart_fragments', array( $this, 'header_add_to_cart_fragment' ) );
			} else {
				add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'header_add_to_cart_fragment' ) );
			}
			// ↑ widgets ↑

			add_filter( 'woocommerce_product_thumbnails_columns',   array( $this, 'thumbnail_columns' ) );
			add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
			// Legacy WooCommerce columns filter.
			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3', '<' ) ) {
				add_filter( 'loop_shop_columns',  array( $this, 'loop_columns' ));
			}

			// tabs remove heading filter
			add_filter( 'woocommerce_product_description_heading', '__return_false' );

			add_action( 'woocommerce_before_shop_loop', array( $this, 'wgl_product_columns_wrapper_open' ), 40 );
			add_action( 'woocommerce_after_shop_loop', array( $this, 'wgl_product_columns_wrapper_close' ), 40 );

			add_filter( 'comment_form_fields',  array( $this, 'wgl_comments_fiels' ) );
			add_filter( 'woocommerce_product_review_comment_form_args',array( $this, 'wgl_filter_comments' ), 10, 1 );
			add_filter( 'woocommerce_product_review_list_args',array( $this, 'wgl_filter_reviews' ), 10, 1 );
			add_filter( 'woocommerce_review_gravatar_size',array( $this, 'wgl_review_gravatar_size' ), 10, 1 );

			add_filter( 'woocommerce_cart_item_thumbnail',array( $this, 'wgl_image_thumbnails' ), 10, 3);

			// Filter pagination
			add_filter('woocommerce_pagination_args', array( $this, 'wgl_filter_pagination' ) );

			add_action('wp_enqueue_scripts', array($this,'js_reg') );
		}

		/* Register JS */
		public function js_reg()
		{

			//Notify JS
			wp_enqueue_script('notify', get_template_directory_uri() . '/js/notify.min.js', array(), false, false);
		}

		/**/
		/* WGL Reviews filter */
		/**/
		function wgl_filter_reviews($array){
			return array( 'callback' => array( $this, 'wgl_templates_reviews' ) );
		}

		public function wgl_templates_reviews($comment, $args, $depth){
			$GLOBALS['comment'] = $comment;
			?>
			<li <?php comment_class('comment'); ?> id="li-comment-<?php comment_ID() ?>">

				<div id="comment-<?php comment_ID(); ?>" class="stand_comment">
					<div class="thiscommentbody">
	                    <div class="commentava">
							<?php
							/**
							 * The woocommerce_review_before hook
							 *
							 * @hooked woocommerce_review_display_gravatar - 10
							 */
							do_action( 'woocommerce_review_before', $comment );
							?>
						</div>
						<div class="comment_info">
							<div class="comment_author_says">
							<?php
								/**
								 * The woocommerce_review_meta hook.
								 *
								 * @hooked woocommerce_review_display_meta - 20
								 * @hooked WC_Structured_Data::generate_review_data() - 20
								 */
								$this->review_comments_meta_info($comment);

							?>
							</div>
						</div>
						<div class="comment_content">
							<?php

							do_action( 'woocommerce_review_before_comment_text', $comment );

							/**
							 * The woocommerce_review_comment_text hook
							 *
							 * @hooked woocommerce_review_display_comment_text - 10
							 */
							do_action( 'woocommerce_review_comment_text', $comment );

							do_action( 'woocommerce_review_after_comment_text', $comment ); ?>

						</div>
					</div>
				</div>
			<?php
		}

		public function wgl_review_gravatar_size(){
			return 120;
		}

		function review_comments_meta_info($comment){
			global $comment;
			$verified = function_exists('wc_review_is_from_verified_owner') ? wc_review_is_from_verified_owner( $comment->comment_ID ) : '';

			if ( '0' === $comment->comment_approved ) { ?>
				<em class="woocommerce-review__awaiting-approval">
					<?php esc_html_e( 'Your review is awaiting approval', 'littledino' ); ?>
				</em>

			<?php } else { ?>
				<span class="comments_author">
					<?php comment_author(); ?>

					<span class="raiting-meta-wrapper">
						<?php
						/**
						* The woocommerce_review_before_comment_meta hook.
						*
						* @hooked woocommerce_review_display_rating - 10
						*/
						do_action( 'woocommerce_review_before_comment_meta', $comment );
						?>
					</span>
				</span>

				<?php
				if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
					echo '<em class="woocommerce-review__verified verified">(' . esc_attr__( 'verified owner', 'littledino' ) . ')</em> ';
				}
				?>
				<div class="meta-wrapper">
					<time class="woocommerce-review__published-date" datetime="<?php echo esc_attr( get_comment_date( 'c' ) ); ?>"><?php echo esc_html( get_comment_date( wc_date_format() ) ); ?></time>
				</div>

			<?php
			}
		}

		/**/
		/* WGL Comments Form Filter */
		/**/
		function wgl_filter_comments($comment_form){
			$commenter = wp_get_current_commenter();

			$comment_form = array(
				'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'littledino' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'littledino' ), get_the_title() ),
				'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'littledino' ),
				'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
				'title_reply_after'    => '</span>',
				'comment_notes_after'  => '',
				'fields'               => array(
					'author' => '<p class="comment-form-author">' . '<label for="author"></label> ' .
					'<input id="author" name="author" placeholder="'.esc_attr__( 'Name', 'littledino' ).'" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required /></p>',
					'email'  => '<p class="comment-form-email"><label for="email"></label> ' .
					'<input id="email" name="email" placeholder="'. esc_attr__( 'Email', 'littledino' ).'" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required /></p>',
				),
				'label_submit'  => esc_html__( 'Submit', 'littledino' ),
				'logged_in_as'  => '',
				'comment_field' => '',
			);

			if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
				$allowed_html = array(
                    'a' => array(
                    	'href' => true,
                    ),
                );
				$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( wp_kses( __( 'You must be <a href="%s">logged in</a> to post a review.', 'littledino' ), $allowed_html), esc_url( $account_page_url ) ) . '</p>';
			}

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
				$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'littledino' ) . '</label><select name="rating" id="rating" aria-required="true" required>
				<option value="">' . esc_html__( 'Rate&hellip;', 'littledino' ) . '</option>
				<option value="5">' . esc_html__( 'Perfect', 'littledino' ) . '</option>
				<option value="4">' . esc_html__( 'Good', 'littledino' ) . '</option>
				<option value="3">' . esc_html__( 'Average', 'littledino' ) . '</option>
				<option value="2">' . esc_html__( 'Not that bad', 'littledino' ) . '</option>
				<option value="1">' . esc_html__( 'Very poor', 'littledino' ) . '</option>
				</select></div>';
			}

			$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment"></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="'.esc_attr__( 'Your review', 'littledino' ).'" required></textarea></p>';
			return $comment_form;
		}

		/**/
		/* Comments Field Reorder */
		/**/
		function wgl_comments_fiels( $fields ){
			if( is_product() ) {
				$comment_field = $fields['comment'];
				unset( $fields['comment'] );
				$fields['comment'] = $comment_field;
			}
			return $fields;
		}

		/* ↓ Loop ↓ */
		public function loop_products_per_page() {
			return (int) LittleDino_Theme_Helper::get_option('shop_products_per_page');
		}
		/* ↑ Loop ↑ */

		/**/
		/* WIDGETS */
		/**/
		public function ajax_remove_from_cart() {
			global $woocommerce;
			$woocommerce->cart->set_quantity( $_POST['remove_item'], 0 );

			$ver = explode( '.', WC_VERSION );

			if ( $ver[1] == 1 && $ver[2] >= 2 ) :
				$wc_ajax = new WC_AJAX();
				$wc_ajax->get_refreshed_fragments();
			else :
				woocommerce_get_refreshed_fragments();
			endif;

			die();
		}

		public function header_add_to_cart_fragment( $fragments ) {
			global $woocommerce;
			ob_start();
				?>
					<span class='woo_mini-count flaticon-shopcart-icon'><?php echo ((WC()->cart->cart_contents_count > 0) ?  '<span>' . esc_html( WC()->cart->cart_contents_count ) .'</span>' : '') ?></span>
				<?php
				$fragments['.woo_mini-count'] = ob_get_clean();

				ob_start();
				woocommerce_mini_cart();
				$fragments['div.woo_mini_cart'] = ob_get_clean();

				return $fragments;
		}
		public function minicart_wrapper_open (){
			echo "<div class='woo_mini_cart'>";
		}
		public function minicart_wrapper_close (){
			echo "</div>";
		}
		/**/
		/* \WIDGETS */
		/**/

		public function woocommerce_template_loop_product_thumbnail (){
			$permalink = esc_url( get_the_permalink() );

			// Sale Product
			ob_start();
			woocommerce_show_product_loop_sale_flash();
			$sale = ob_get_clean();

			global $product;
			$secondary_image = '';

			if(method_exists($product, 'get_gallery_image_ids')){
				$attachment_ids = $product->get_gallery_image_ids();

				if ($attachment_ids) {
					if(isset($attachment_ids['0'])){
						$secondary_image_id = $attachment_ids['0'];
						$secondary_image = wp_get_attachment_image($secondary_image_id, apply_filters('shop_catalog', 'shop_catalog'));
					}
				}
			}
			$sale_banner = !empty( $sale ) ? "<div class='woo_banner_wrapper'><div class='woo_banner sale_bunner'><div class='woo_banner_text'>$sale</div></div></div>" : "";
			echo "<div class='woo_product_image shop_media'>";
				echo "<div class='picture".(empty($secondary_image) ? ' no_effects' : '')."'>";
					echo !empty( $sale_banner ) ? $sale_banner : "";
					if(function_exists('woocommerce_get_product_thumbnail')){
						echo "<a class='woo_post-link' href='$permalink'>";
							echo woocommerce_get_product_thumbnail();

							if (!empty($secondary_image)) {
						        echo wp_kses_post($secondary_image);
						    }

						echo "</a>";
					}
				echo "</div>";
			echo '</div>';
		}
		public function loop_products_item_wrapper(){
			echo '<div class="products-post">';
				echo '<div class="products-post_wrapper">';
		}

		public function loop_products_item_wrapper_close(){

			// Add To cart product
			ob_start();
			echo '<div class="group_button-woo">';

			echo '<div class="add_to_cart-btn">';
			woocommerce_template_loop_add_to_cart();
			echo '</div>';

			echo '</div>';

			$add_to_cart = ob_get_clean();

				echo !empty($add_to_cart) ? $add_to_cart : "";
				echo '</div>';
			echo '</div>';
		}

		/**
		 * Product gallery thumbnail columns
		 *
		 * @return integer number of columns
		 * @since  1.0.0
		 */
		public function thumbnail_columns() {
			$columns = 4;
			return intval( $columns );
		}

		/**
		 * Related Products Args
		 *
		 * @param  array $args related products args.
		 * @since 1.0.0
		 * @return  array $args related products args
		 */
		public function related_products_args( $args ) {
			$args = array(
				'posts_per_page' => (int) LittleDino_Theme_Helper::get_option('shop_r_products_per_page'),
				'columns'        => (int) LittleDino_Theme_Helper::get_option('shop_related_columns'),
			);

			return $args;
		}

		/**
		 * Columns Products
		 *
		 * @param  array $args columns products args.
		 * @since 1.0.0
		 * @return  array $args columns products args
		 */
		public function loop_columns( $args ) {
			$columns = (int) LittleDino_Theme_Helper::get_option('shop_column'); // 3 products per row
			return $columns;
		}

		public function wgl_product_columns_wrapper() {
			$columns = (int) LittleDino_Theme_Helper::get_option('shop_column');
			echo '<div class="wgl-products-catalog wgl-products-wrapper columns-' . absint( $columns ) . '">';
		}

		public function template_loop_product_title(){
			global $product;
			$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
			echo '<h2 class="woocommerce-loop-product__title"><a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">' . get_the_title() . '</a></h2>';
		}

		public function wgl_sorting_wrapper_open(){
			echo '<div class="wgl-woocommerce-sorting">';
		}

		public function wgl_sorting_wrapper_close(){
			echo '</div>';
		}

		public function wgl_product_columns_wrapper_open() {
			$columns = (int) LittleDino_Theme_Helper::get_option('shop_column');
			echo '<div class="wgl-products-catalog wgl-products-wrapper columns-' . absint( $columns ) . '">';
		}

		public function wgl_product_columns_wrapper_close() {
			echo '</div>';
		}

		public function template_loop_product_open(){
			echo "<div class='woo_product_content'>";
		}

		public function template_loop_product_close(){
			echo "</div>";
		}

        /**
         * @since 1.0.0
         * @version 1.0.6
         */
        public function init_template()
        {
            $shop_template = is_single() ? 'single' : 'catalog';
            $sb = LittleDino_Theme_Helper::render_sidebars('shop_' . $shop_template);
            $this->row_class = $sb['row_class'] ?? '';
            $this->column = $sb['column'] ?? '';
            $this->container_class = $sb['container_class'] ?? '';
            $this->content = !empty($sb['content']) ? $sb['content'] : '';
        }

		public function wgl_page_template_open()
		{
			$this->init_template();
			?>
			<div class="wgl-container single_product<?php echo esc_attr($this->container_class); ?>">
    			<div class="row<?php echo esc_attr($this->row_class); ?>">
					<div id='main-content' class="wgl_col-<?php echo (int)esc_attr($this->column); ?>">
		    <?php
		}

		public function wgl_page_template_close()
		{
			$this->init_template();
			echo '</div>';
				echo !empty($this->content) ? $this->content : '';
				echo "</div>";
			echo "</div>";
        }

        /**
         * @since 1.0.0
         * @version 1.0.6
         */
        public function wgl_filter_pagination()
        {
            $total = $total ?? wc_get_loop_prop('total_pages');
            $current = $current ?? wc_get_loop_prop('current_page');
            $base = $base ?? esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
            $format = $format ?? '';

            if ($total <= 1) {
                return;
            }

            return [ // WPCS: XSS ok.
                'base' => $base,
                'format' => $format,
                'add_args' => false,
                'current' => max(1, $current),
                'total' => $total,
                'prev_text' => '<i class="fa fa-angle-left"></i>',
                'next_text' => '<i class="fa fa-angle-right"></i>',
                'type' => 'list',
                'end_size' => 3,
                'mid_size' => 3,
            ];
        }

        /**
         * @since 1.0.0
         * @version 1.0.6
         */
        public function wgl_image_thumbnails($image, $cart_item, $cart_item_key)
        {
            $class = 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail wgl-woocommerce_thumbnail'; // Default cart thumbnail class.
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $image_url = wp_get_attachment_image_src( $_product->get_image_id(), 'full', false );
            if(function_exists('aq_resize') && !!$image_url){
                $image_data = wp_get_attachment_metadata($_product->get_image_id());
                $image_meta_title = $image_data['image_meta']['title'] ?? '';
                $image_url[0] = aq_resize($image_url[0], '80', '80', true, true, true);

                $image = '<img'
                    . ' class="'. esc_attr($class) .'"'
                    . ' src="' . esc_url($image_url[0]) . '"'
                    . ' alt="' . esc_attr($image_meta_title) . '"'
                    . '>';
            }

            return $image;
        }
	}
}

/**/
/* Config and enable extension */
new LittleDino_Woocoommerce ( );

// LittleDino Woocoommerce Helpers

if ( ! function_exists( 'littledino_woocommerce_breadcrumb' ) ) {
	/**
	 * Output the WooCommerce Breadcrumb.
	 *
	 * @param array $args Arguments.
	 */
	function littledino_woocommerce_breadcrumb( $args = array() ) {
		$args = wp_parse_args( $args, apply_filters( 'woocommerce_breadcrumb_defaults', array(
			'delimiter'   => '&nbsp;&#47;&nbsp;',
			'wrap_before' => '',
			'wrap_after'  => '',
			'before'      => '',
			'after'       => '',
			'home'        => esc_html_x( 'Home', 'breadcrumb', 'littledino' ),
		) ) );

		$breadcrumbs = new WC_Breadcrumb();

		$args['breadcrumb'] = $breadcrumbs->generate();

		/**
		 * WooCommerce Breadcrumb hook
		 *
		 * @hooked WC_Structured_Data::generate_breadcrumblist_data() - 10
		 */
		do_action( 'woocommerce_breadcrumb', $breadcrumbs, $args );

		extract($args);

		$out = '';
		if ( ! empty( $breadcrumb ) ) {

			$out .= LittleDino_Theme_Helper::render_html($wrap_before);

			foreach ( $breadcrumb as $key => $crumb ) {

				$out .=  LittleDino_Theme_Helper::render_html($before);

				if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
					$out .=  '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
				} else {
					$out .=  '<span class="current">' .( $crumb[0] ). '</span>';
				}

				$out .=  LittleDino_Theme_Helper::render_html($after);

				if ( sizeof( $breadcrumb ) !== $key + 1 ) {
					$out .=  LittleDino_Theme_Helper::render_html($delimiter);
				}
			}
			$out .=  LittleDino_Theme_Helper::render_html($wrap_after);
		}

		return $out;

	}
}
if ( ! function_exists( 'wgl_wc_print_notices' ) ) {
	/**
	 * Output messages and errors which are stored in the session, then clears them.
	 *
	 * @param array $args Arguments.
	 */
	function wgl_wc_print_notices( $return = false ) {
		if ( ! did_action( 'woocommerce_init' ) ) {
			return;
		}

		$all_notices  = WC()->session->get( 'wc_notices', array() );
		$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'notice' ) );

		// Buffer output.
		ob_start();

		foreach ( $notice_types as $notice_type ) {
			if ( wc_notice_count( $notice_type ) > 0 ) {
				$messages = array();

				foreach ( $all_notices[ $notice_type ] as $notice ) {
					$messages[] = isset( $notice['notice'] ) ? $notice['notice'] : $notice;
				}

				wc_get_template(
					"notices/{$notice_type}.php",
					array(
						'messages' => array_filter( $messages ), // @deprecated 3.9.0
						'notices'  => array_filter( $all_notices[ $notice_type ] ),
					)
				);
			}
		}

		//wc_clear_notices();

		$notices = wc_kses_notice( ob_get_clean() );

		if ( $return ) {
			return $notices;
		}

		echo LittleDino_Theme_Helper::render_html($notices); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}


?>
