<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<form name="checkout" method="post"
      class="checkout woocommerce-checkout grid grid-cols-1 lg:grid-cols-2 gap-8"
      action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
      enctype="multipart/form-data"
      aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

    <?php if ( $checkout->get_checkout_fields() ) : ?>

        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

        <!-- Customer Details -->
        <div id="customer_details" class="space-y-6">
            <div class="billing-fields bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4"><?php esc_html_e( 'Billing details', 'woocommerce' ); ?></h3>
                <?php do_action( 'woocommerce_checkout_billing' ); ?>
            </div>

            <div class="shipping-fields bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4"><?php esc_html_e( 'Shipping details', 'woocommerce' ); ?></h3>
                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
            </div>
        </div>

        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

    <?php endif; ?>

    <!-- Order Review (Sticky on Desktop) -->
    <div class="order-review bg-white p-6 rounded shadow space-y-6 lg:sticky lg:top-8 self-start">
        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

        <h3 id="order_review_heading" class="text-xl font-semibold">
            <?php esc_html_e( 'Your order', 'woocommerce' ); ?>
        </h3>

        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

        <div id="order_review" class="woocommerce-checkout-review-order">
            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
        </div>

        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
    </div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

