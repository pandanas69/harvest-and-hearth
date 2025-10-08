<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<!-- Desktop / Tablet Table -->
<div class="hidden md:block">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left text-sm font-semibold">
                <th class="p-3"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                <th class="p-3"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            do_action( 'woocommerce_review_order_before_cart_contents' );

            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    ?>
                    <tr class="border-b <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                        <td class="p-3">
                            <?php
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
                            echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="ml-1">× ' . $cart_item['quantity'] . '</strong>', $cart_item, $cart_item_key );
                            echo wc_get_formatted_cart_item_data( $cart_item );
                            ?>
                        </td>
                        <td class="p-3">
                            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                        </td>
                    </tr>
                    <?php
                }
            }

            do_action( 'woocommerce_review_order_after_cart_contents' );
            ?>
        </tbody>
        <tfoot>
            <tr class="cart-subtotal">
                <th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                <td><?php wc_cart_totals_subtotal_html(); ?></td>
            </tr>

            <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                    <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                    <td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
                <?php wc_cart_totals_shipping_html(); ?>
                <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
            <?php endif; ?>

            <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                <tr class="fee">
                    <th><?php echo esc_html( $fee->name ); ?></th>
                    <td><?php wc_cart_totals_fee_html( $fee ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                    <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                        <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                            <th><?php echo esc_html( $tax->label ); ?></th>
                            <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="tax-total">
                        <th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
                        <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>

            <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

            <tr class="order-total">
                <th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                <td><?php wc_cart_totals_order_total_html(); ?></td>
            </tr>

            <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
        </tfoot>
    </table>
</div>

<!-- Mobile Card Layout -->
<div class="space-y-4 md:hidden">
    <?php
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
            ?>
            <div class="border rounded p-4 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold"><?php echo wp_kses_post( $_product->get_name() ); ?></h3>
                    <p class="text-sm text-gray-600">Qty: <?php echo esc_html( $cart_item['quantity'] ); ?></p>
                    <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                </div>
                <span class="font-medium">
                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                </span>
            </div>
            <?php
        }
    }
    ?>

    <!-- Totals -->
    <div class="border rounded p-4 space-y-2">
        <div class="flex justify-between">
            <span class="font-medium"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
            <span><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="flex justify-between text-green-600">
                <span class="font-medium"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <div class="flex justify-between">
                <span class="font-medium"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
                <span><?php wc_cart_totals_shipping_html(); ?></span>
            </div>
        <?php endif; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="flex justify-between text-gray-700">
                <span class="font-medium"><?php echo esc_html( $fee->name ); ?></span>
                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                    <div class="flex justify-between text-gray-700">
                        <span class="font-medium"><?php echo esc_html( $tax->label ); ?></span>
                        <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="flex justify-between text-gray-700">
                    <span class="font-medium"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                    <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="flex justify-between text-lg font-semibold border-t pt-2">
            <span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
            <span><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
    </div>
</div>

