<?php
/**
 * Template Name: Grow Hub
 */
get_header(); ?>

<main id="site-content" class="max-w-7xl mx-auto px-6 py-12">

  <!-- Hero -->
  <section class="text-center mb-12">
    <h1 class="font-heading text-4xl md:text-5xl text-basil mb-4">Grow with Harvest &amp; Hearth</h1>
    <p class="font-body text-charcoal max-w-2xl mx-auto">
      Practical plant care tips and seasonal growing guides for Aotearoa.
    </p>
  </section>

  <!-- Seasonal Guide CTA -->
  <section class="bg-cream rounded-lg p-8 mb-16 text-center">
    <h2 class="font-heading text-2xl text-basil mb-4">What to Plant This Season</h2>
    <p class="font-body mb-6">Download our NZ seasonal crop calendar and start planning your garden.</p>
    <a href="https://harvesthearth.nz/wp-content/uploads/2025/10/nz-crop-calendar.pdf"
       class="inline-block bg-terracotta text-cream px-6 py-3 rounded font-semibold hover:bg-basil transition-colors">
      Download Crop Calendar (PDF)
    </a>
  </section>

  <!-- Grow Hub Filter Bar -->
  <section id="grow-section" class="mb-12">
    <nav class="text-center">
      <ul class="inline-flex flex-wrap gap-3 justify-center">
        <?php
        $current = isset($_GET['grow_filter']) ? sanitize_text_field($_GET['grow_filter']) : 'all';
        $filters = [
          'all'        => 'All',
          'plant-care' => 'Plant Care',
          'grow-guide' => 'Grow Guides',
        ];
        foreach ($filters as $slug => $label) :
          $active = ($current === $slug);
          $url = add_query_arg('grow_filter', $slug, get_permalink()) . '#grow-section';
        ?>
          <li>
            <a href="<?php echo esc_url($url); ?>"
               class="px-4 py-2 rounded-full border <?php echo $active ? 'bg-basil text-white' : 'border-basil text-basil hover:bg-basil hover:text-white'; ?> transition">
              <?php echo esc_html($label); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </section>

  <?php
  // Determine filter
  $args = [
    'post_type'      => 'post',
    'posts_per_page' => 6,
  ];
  if ($current !== 'all') {
    $args['category_name'] = $current; // 'plant-care' or 'grow-guide'
  }
  $grow_posts = new WP_Query($args);
  ?>

  <?php if ($grow_posts->have_posts()) : ?>
    <div class="grid md:grid-cols-3 gap-8">
      <?php while ($grow_posts->have_posts()) : $grow_posts->the_post(); ?>
        <article class="bg-white rounded-lg shadow hover:shadow-lg overflow-hidden">
          <a href="<?php the_permalink(); ?>">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail('large', ['class'=>'w-full h-48 object-cover']); ?>
            <?php else : ?>
              <img src="<?php echo get_theme_file_uri('/assets/images/placeholder-grow.jpg'); ?>" alt="" class="w-full h-48 object-cover">
            <?php endif; ?>
          </a>
          <div class="p-6">
            <h3 class="font-heading text-xl mb-2">
              <a href="<?php the_permalink(); ?>" class="hover:text-terracotta"><?php the_title(); ?></a>
            </h3>
            <p class="font-body text-sm text-charcoal"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  <?php else : ?>
    <p class="text-center text-gray-600">No posts found in this section yet.</p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

