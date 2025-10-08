<?php
defined('ABSPATH') || exit;
get_header();
?>

<main id="site-content" class="container mx-auto px-4 py-12">

  <!-- Hero -->
  <section class="text-center mb-12">
    <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-gray-900">
      <?php post_type_archive_title(); ?>
    </h1>
    <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">
      <?php esc_html_e('Discover recipes from the Earth, Air, Land, and Sea.', 'yourtheme'); ?>
    </p>
  </section>

  <!-- Pillar cards -->
  <?php
  $origins = get_terms([
    'taxonomy' => 'recipe_origin',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
    'parent' => 0,
  ]);
  ?>
  <?php if (!is_wp_error($origins) && !empty($origins)) : ?>
    <section class="mb-12">
      <h2 class="sr-only">Explore by origin</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
       <?php foreach ($origins as $term) :
  $link       = get_term_link($term);
  $image_url  = hh_get_recipe_origin_image_url($term->term_id, 'large');
  $maori      = get_term_meta($term->term_id, 'maori_label', true);
  $descriptor = get_term_meta($term->term_id, 'descriptor', true);
?>
  <a href="<?php echo esc_url($link); ?>" class="relative group rounded-lg overflow-hidden shadow-lg block">
    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($term->name); ?>" class="w-full h-40 object-cover group-hover:scale-105 transition-transform">
    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors flex flex-col items-center justify-center text-center px-4">
      <span class="font-heading text-xl text-cream uppercase">
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
    </section>
  <?php endif; ?>

  <!-- Latest recipes grid (archive loop) -->
  <?php if (have_posts()) : ?>
    <section>
      <h2 class="text-2xl font-semibold text-gray-900 mb-6">Latest recipes</h2>
      <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php while (have_posts()) : the_post(); ?>
          <li class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
            <a href="<?php the_permalink(); ?>" class="block">
              <?php if (has_post_thumbnail()) : ?>
                <div class="aspect-w-4 aspect-h-3 bg-gray-100">
                  <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover']); ?>
                </div>
              <?php endif; ?>
              <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900"><?php the_title(); ?></h3>
                <p class="mt-2 text-sm text-gray-600 line-clamp-3"><?php echo get_the_excerpt(); ?></p>
              </div>
            </a>
          </li>
        <?php endwhile; ?>
      </ul>

      <div class="mt-8">
        <?php
        the_posts_pagination([
          'mid_size'  => 2,
          'prev_text' => __('« Prev', 'yourtheme'),
          'next_text' => __('Next »', 'yourtheme'),
        ]);
        ?>
      </div>
    </section>
  <?php else : ?>
    <p class="text-center text-gray-600"><?php esc_html_e('No recipes found yet. Check back soon!', 'yourtheme'); ?></p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

