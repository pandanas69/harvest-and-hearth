<?php
/**
 * Template Name: Recipes Page
 *
 * A branded hub page for showcasing all recipes in a grid layout,
 * with an Explore by Origin section including Māori labels.
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<main id="site-content" class="max-w-7xl mx-auto px-6 py-12">

  <!-- Hero Section -->
  <section class="text-center mb-12">
    <h1 class="font-heading text-4xl md:text-5xl text-basil mb-4">
      <?php the_title(); ?>
    </h1>
    <?php if ( get_the_content() ) : ?>
      <p class="font-body text-charcoal max-w-2xl mx-auto">
        <?php echo wp_kses_post( get_the_content() ); ?>
      </p>
    <?php endif; ?>
  </section>

  <!-- Recipes Grid -->
  <section class="mb-20">
    <?php
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $recipes = new WP_Query([
      'post_type'      => 'recipe',
      'posts_per_page' => 12,
      'paged'          => $paged,
      'orderby'        => 'date',
      'order'          => 'DESC',
    ]);

    if ( $recipes->have_posts() ) : ?>
      <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php while ( $recipes->have_posts() ) : $recipes->the_post(); ?>
          <article <?php post_class('bg-white rounded-lg shadow hover:shadow-lg overflow-hidden transition'); ?>>
            <a href="<?php the_permalink(); ?>" class="block">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail('large', ['class' => 'w-full h-48 object-cover']); ?>
              <?php else : ?>
                <img src="<?php echo get_theme_file_uri('/assets/images/placeholder-recipe.jpg'); ?>" alt="" class="w-full h-48 object-cover">
              <?php endif; ?>
            </a>
            <div class="p-4">
              <h2 class="font-heading text-lg text-basil mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-terracotta"><?php the_title(); ?></a>
              </h2>
              <p class="font-body text-sm text-charcoal mb-2">
                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
              </p>
              <!-- Māori origin labels -->
              <div class="text-xs uppercase tracking-wide text-sage font-semibold">
                <?php
                $terms = get_the_terms( get_the_ID(), 'recipe_origin' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                  $labels = [];
                  foreach ( $terms as $term ) {
                    $maori = get_term_meta($term->term_id, 'maori_label', true);
                    $labels[] = esc_html( $maori ? $maori : $term->name );
                  }
                  echo implode( ', ', $labels );
                }
                ?>
              </div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <!-- Pagination -->
      <div class="mt-12">
        <?php
        the_posts_pagination([
          'mid_size'  => 2,
          'prev_text' => __('« Prev', 'harvesthearth'),
          'next_text' => __('Next »', 'harvesthearth'),
        ]);
        ?>
      </div>

    <?php else : ?>
      <p class="text-center text-gray-600">No recipes found yet. Check back soon!</p>
    <?php endif; wp_reset_postdata(); ?>
  </section>

  <!-- Explore by Origin -->
  <section class="bg-cream py-20">
    <div class="max-w-7xl mx-auto px-6 category-hub not-prose">
      <h2 class="font-heading text-3xl text-basil mb-12 text-center">Explore Recipes by Origin</h2>
      <div class="grid md:grid-cols-4 gap-8">
        <?php
        $origins = get_terms([
          'taxonomy'   => 'recipe_origin',
          'hide_empty' => false,
          'orderby'    => 'name',
          'order'      => 'ASC',
          'parent'     => 0
        ]);

        foreach ($origins as $term) :
          $link = get_term_link($term);
          $image_url = hh_get_recipe_origin_image_url($term->term_id, 'large');
          $maori = get_term_meta($term->term_id, 'maori_label', true);
          $descriptor = get_term_meta($term->term_id, 'descriptor', true);
        ?>
          <a href="<?php echo esc_url($link); ?>" class="relative group rounded-lg overflow-hidden shadow-lg block">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($term->name); ?>" class="w-full h-56 object-cover group-hover:scale-105 transition-transform">
            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors flex flex-col items-center justify-center text-center px-4">
              <span class="font-heading text-2xl text-cream uppercase">
                <?php echo esc_html($maori); ?>
                <span class="block text-sm text-cream/80"><?php echo esc_html($term->name); ?></span>
              </span>
              <?php if ($descriptor): ?>
                <p class="text-cream text-xs mt-2"><?php echo esc_html($descriptor); ?></p>
              <?php endif; ?>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>

