<?php
/**
 * Cart Page (Branded Override)
 *
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
wc_print_notices();
?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php do_action( 'woocommerce_before_cart_table' ); ?>

    <!-- Desktop / Tablet Table -->
    <div class="hidden md:block">
        <table class="shop_table w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left text-sm font-semibold">
                    <th class="p-3"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                    <th class="p-3"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                    <th class="p-3"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                    <th class="p-3"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = $cart_item['product_id'];

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <tr <?php echo wc_get_cart_item_class( 'border-b', $cart_item, $cart_item_key ); ?>>
                            <td class="p-3 flex items-center gap-3">
                                <?php
                                // Remove link
                                echo apply_filters( 'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="text-red-600 font-bold">&times;</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) )
                                    ),
                                    $cart_item_key
                                );

                                // Thumbnail
                                $thumbnail = $_product->get_image( 'thumbnail', ['class' => 'w-16 h-16 object-cover rounded'] );
                                if ( ! $product_permalink ) {
                                    echo $thumbnail;
                                } else {
                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                }

                                // Name
                                if ( ! $product_permalink ) {
                                    echo '<span class="ml-2 font-medium">' . wp_kses_post( $_product->get_name() ) . '</span>';
                                } else {
                                    echo '<a href="' . esc_url( $product_permalink ) . '" class="ml-2 font-medium">' . wp_kses_post( $_product->get_name() ) . '</a>';
                                }

                                // Meta data
                                echo wc_get_formatted_cart_item_data( $cart_item );
                                ?>
                            </td>

                            <td class="p-3">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_price',
                                    WC()->cart->get_product_price( $_product ),
                                    $cart_item,
                                    $cart_item_key
                                );
                                ?>
                            </td>

                            <td class="p-3">
                                <?php
                                if ( $_product->is_sold_individually() ) {
                                    $min_quantity = 1;
                                    $max_quantity = 1;
                                } else {
                                    $min_quantity = 0;
                                    $max_quantity = $_product->get_max_purchase_quantity();
                                }

                                woocommerce_quantity_input(
                                    [
                                        'input_name'  => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'min_value'   => $min_quantity,
                                        'max_value'   => $max_quantity,
                                        'classes'     => ['w-16 border rounded text-center'],
                                    ],
                                    $_product,
                                    false
                                );
                                ?>
                            </td>

                            <td class="p-3">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal',
                                    WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ),
                                    $cart_item,
                                    $cart_item_key
                                );
                                ?>
                            </td>
                        </tr>
                    <?php endif;
                endforeach; ?>

                <?php do_action( 'woocommerce_cart_contents' ); ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card Layout -->
    <div class="space-y-4 md:hidden">
        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
            $_product   = $cart_item['data'];
            $product_id = $cart_item['product_id'];

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                ?>
                <div class="border rounded p-4 flex items-center gap-4">
                    <?php
                    // Remove link
                    echo apply_filters( 'woocommerce_cart_item_remove_link',
                        sprintf(
                            '<a href="%s" class="text-red-600 font-bold absolute top-2 right-2">&times;</a>',
                            esc_url( wc_get_cart_remove_url( $cart_item_key ) )
                        ),
                        $cart_item_key
                    );

                    // Thumbnail
                    $thumbnail = $_product->get_image( 'thumbnail', ['class' => 'w-16 h-16 object-cover rounded'] );
                    if ( ! $product_permalink ) {
                        echo $thumbnail;
                    } else {
                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                    }
                    ?>
                    <div class="flex-1">
                        <h3 class="font-semibold">
                            <?php echo wp_kses_post( $_product->get_name() ); ?>
                        </h3>
                        <p class="text-sm text-gray-600">
                            <?php echo WC()->cart->get_product_price( $_product ); ?>
                        </p>
                        <div class="mt-2 flex items-center gap-2">
                            <?php
                            woocommerce_quantity_input(
                                [
                                    'input_name'  => "cart[{$cart_item_key}][qty]",
                                    'input_value' => $cart_item['quantity'],
                                    'min_value'   => 0,
                                    'max_value'   => $_product->get_max_purchase_quantity(),
                                    'classes'     => ['w-16 border rounded text-center'],
                                ],
                                $_product,
                                false
                            );
                            ?>
                            <span class="text-sm font-medium">
                                <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif;
        endforeach; ?>
    </div>

    <!-- Cart Actions -->
    <div class="flex justify-between items-center mt-6 flex-wrap gap-4">
        <?php if ( wc_coupons_enabled() ) : ?>
            <div class="coupon flex gap-2">
                <input type="text" name="coupon_code" class="border rounded px-2 py-1" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
                <button type="submit" class="bg-gray-200 px-3 py-1 rounded" name="apply_coupon"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
            </div>
        <?php endif; ?>

        <button type="submit" class="bg-brand text-white px-4 py-2 rounded" name="update_cart">
            <?php esc_html_e( 'Update Cart', 'woocommerce' ); ?>
        </button>

        <?php do_action( 'woocommerce_cart_actions' ); ?>
    </div>

    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
    <?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<div class="cart-collaterals mt-8">
    <?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

