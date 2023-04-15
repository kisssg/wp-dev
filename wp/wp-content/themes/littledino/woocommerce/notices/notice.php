<?php
/**
 * Show messages
 *
 * This template is overridden by WebGeniusLab team.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly.
}

if ( ! $messages ) {
	return;
}

?>
 
<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-info littledino_module_message_box type_info closable wpb_animate_when_almost_visible wpb_left-to-right left-to-right wpb_start_animation animated"<?php echo wc_get_notice_data_attr( $message ); ?>>
	<div class="message_icon_wrap"><i class="message_icon "></i></div>
		<div class="message_content">
			<div class="message_text">
				<?php echo wc_kses_notice( $message ); ?>
			</div>
		</div>
		<span class="message_close_button"></span>
	</div>
<?php endforeach; ?>
