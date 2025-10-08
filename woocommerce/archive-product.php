<?php
/**
 * The Template for displaying product archives, including the main shop page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<main id="site-content" class="container mx-auto px-4 py-8">

    <?php
    /**
     * Hook: woocommerce_before_main_content.
     *
     * Keep this if you want breadcrumbs or structured data.
     */
    do_action( 'woocommerce_before_main_content' );
    ?>

    <!-- Custom Shop Intro -->
    <header class="mb-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900">
            <?php woocommerce_page_title(); ?>
        </h1>
        <?php if ( apply_filters( 'woocommerce_show_page_description', true ) ) : ?>
            <p class="mt-2 text-gray-600 max-w-2xl mx-auto">
                <?php woocommerce_taxonomy_archive_description(); ?>
                <?php woocommerce_product_archive_description(); ?>
            </p>
        <?php endif; ?>
    </header>

    <?php if ( woocommerce_product_loop() ) : ?>

        <?php
        /**
         * Hook: woocommerce_before_shop_loop.
         *
         * @hooked woocommerce_output_all_notices - 10
         * @hooked woocommerce_result_count - 20
         * @hooked woocommerce_catalog_ordering - 30
         */
        do_action( 'woocommerce_before_shop_loop' );
        ?>

        <ul class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php
                do_action( 'woocommerce_shop_loop' );
                wc_get_template_part( 'content', 'product' );
                ?>
            <?php endwhile; ?>
        </ul>

        <?php
        /**
         * Hook: woocommerce_after_shop_loop.
         *
         * @hooked woocommerce_pagination - 10
         */
        do_action( 'woocommerce_after_shop_loop' );
        ?>

    <?php else : ?>

        <?php
        /**
         * Hook: woocommerce_no_products_found.
         *
         * @hooked wc_no_products_found - 10
         */
        do_action( 'woocommerce_no_products_found' );
        ?>

    <?php endif; ?>

    <?php
    /**
     * Hook: woocommerce_after_main_content.
     */
    do_action( 'woocommerce_after_main_content' );
    ?>

</main>

<?php get_footer( 'shop' ); ?>

