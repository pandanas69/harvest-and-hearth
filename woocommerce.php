<?php
/**
 * The template for displaying WooCommerce pages
 * 
 * @package HarvestAndHearth
 */

get_header(); ?>

<main id="site-content" class="container mx-auto px-4 py-8">
    <?php woocommerce_content(); ?>
</main>

<?php get_footer(); ?>

