<?php
/**
 * Taxonomy Template for Recipe Categories
 *
 * Displays recipes filtered by a specific recipe_category term.
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<main id="site-content" class="container mx-auto px-4 py-12">

    <!-- Hero / Intro -->
    <section class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-gray-900">
            <?php single_term_title(); ?>
        </h1>
        <?php if ( term_description() ) : ?>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">
                <?php echo term_description(); ?>
            </p>
        <?php endif; ?>
    </section>

    <!-- Recipes Grid -->
    <?php if ( have_posts() ) : ?>
        <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php while ( have_posts() ) : the_post(); ?>
                <li class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                    <a href="<?php the_permalink(); ?>" class="block">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="aspect-w-4 aspect-h-3 bg-gray-100">
                                <?php the_post_thumbnail( 'medium_large', ['class' => 'w-full h-full object-cover'] ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-900"><?php the_title(); ?></h2>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3"><?php echo get_the_excerpt(); ?></p>
                        </div>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>

        <!-- Pagination -->
        <div class="mt-8">
            <?php
            the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => __('« Prev', 'yourtheme'),
                'next_text' => __('Next »', 'yourtheme'),
            ]);
            ?>
        </div>

    <?php else : ?>
        <p class="text-center text-gray-600">
            <?php esc_html_e( 'No recipes found in this category yet.', 'yourtheme' ); ?>
        </p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>

