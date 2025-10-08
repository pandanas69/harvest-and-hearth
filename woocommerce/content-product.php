<?php
/**
 * The template for displaying product content within loops (Branded Override)
 *
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( 'group border rounded-lg p-4 hover:shadow-md transition relative', $product ); ?>>

    <?php
    // Open product link
    do_action( 'woocommerce_before_shop_loop_item' );

    // Custom sale badge
    if ( $product->is_on_sale() ) : ?>
        <span class="absolute top-2 left-2 bg-basil text-white text-xs font-semibold px-2 py-1 rounded">
            <?php esc_html_e( 'Sale!', 'woocommerce' ); ?>
        </span>
    <?php endif; ?>

    <?php
    // Thumbnail
    woocommerce_template_loop_product_thumbnail();
    ?>

    <h2 class="mt-2 text-lg font-semibold group-hover:text-brand">
        <?php woocommerce_template_loop_product_title(); ?>
    </h2>

    <span class="block mt-1 text-sm text-gray-600">
        <?php woocommerce_template_loop_price(); ?>
    </span>

    <?php
    // Close link + add to cart
    do_action( 'woocommerce_after_shop_loop_item' );
    ?>
</li>

