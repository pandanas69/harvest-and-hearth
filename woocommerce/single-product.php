<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 1.6.4
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<main id="site-content" class="container mx-auto px-4 py-8">

    <?php
    /**
     * Hook: woocommerce_before_main_content.
     *
     * Keep this if you still want breadcrumbs or other hooked content.
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action( 'woocommerce_before_main_content' );
    ?>

    <?php while ( have_posts() ) : ?>
        <?php the_post(); ?>
        <?php wc_get_template_part( 'content', 'single-product' ); ?>
    <?php endwhile; ?>

    <?php
    /**
     * Hook: woocommerce_after_main_content.
     *
     * Useful if plugins or custom code hook into this.
     */
    do_action( 'woocommerce_after_main_content' );
    ?>

   

</main>

<?php get_footer( 'shop' ); ?>

