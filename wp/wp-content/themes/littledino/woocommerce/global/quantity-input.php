<?php
/**
 * Product quantity inputs
 *
 * This template is overridden by WebGeniusLab team.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
	?>
	<div class="quantity hidden number-input">
		<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
		<span class="minus"></span>
		<span class="plus"></span>
	</div>
	<?php
} else {
	/* translators: %s: Quantity. */
	$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'littledino' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'littledino' );
	?>
	<div class="quantity number-input">
		<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
		<label class="label-qty" for="<?php echo esc_attr( $input_id ); ?>"><?php esc_html_e( 'Quantity', 'littledino' ); ?></label>
		<div class="quantity-wrapper">
			<input
				type="number"
				id="<?php echo esc_attr( $input_id ); ?>"
				class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
				step="<?php echo esc_attr( $step ); ?>"
				min="<?php echo esc_attr( $min_value ); ?>"
				max="<?php echo esc_attr( 0 < $max_value ? $max_value : '999' ); ?>"
				name="<?php echo esc_attr( $input_name ); ?>"
				value="<?php echo esc_attr( $input_value ); ?>"
				title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'littledino' ); ?>"
				size="4"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				inputmode="<?php echo esc_attr( $inputmode ); ?>"
				aria-labelledby="<?php echo esc_attr( $label ); ?>" />
			<span class="minus"></span>
			<span class="plus"></span>
		</div>
		<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
	</div>
	<?php
}
