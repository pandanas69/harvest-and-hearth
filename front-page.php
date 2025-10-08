<?php get_header(); ?>

<!-- Hero -->
<section class="relative bg-cream bg-cover bg-center"
         style="background-image: url('<?php echo get_theme_file_uri('/assets/images/hearth2.jpg'); ?>');
         background-color: rgba(255,255,255,0.6); 
         background-blend-mode: multiply;">
  <div class="absolute inset-0 bg-cream/10"></div>
  <div class="relative z-10 max-w-4xl mx-auto px-6 py-24 flex flex-col items-center text-500 font-semibold text-center">
    <h1 class="font-heading text-5xl md:text-6xl text-basil mb-6">
      Harvest &amp; Hearth
    </h1>
    <p class="font-body text-lg text-white mb-8 max-w-2xl">
      Seasonal recipes, rustic living, and the joy of gathering around the table.
    </p>
    <div class="flex gap-4">
      <a href="#latest"
         class="inline-block bg-terracotta text-cream px-6 py-3 rounded font-semibold hover:bg-basil transition-colors">
        Explore Recipes
      </a>
      <a href="<?php echo site_url('/shop'); ?>"
         class="inline-block bg-basil text-cream px-6 py-3 rounded font-semibold hover:bg-terracotta transition-colors">
        Visit Shop
      </a>
    </div>
  </div>
</section>

<!-- Latest Recipes -->
<section id="latest" class="max-w-7xl mx-auto px-6 py-20">
  <h2 class="font-heading text-3xl text-basil mb-12 text-center">Latest Recipes</h2>
  <?php
    $latest_recipes = new WP_Query([
      'post_type'           => 'recipe',
      'posts_per_page'      => 3,
      'orderby'             => 'date',
      'order'               => 'DESC',
      'ignore_sticky_posts' => true,
      'no_found_rows'       => true,
    ]);

    if ( $latest_recipes->have_posts() ) :
  ?>
    <div class="grid md:grid-cols-3 gap-10">
      <?php while ( $latest_recipes->have_posts() ) : $latest_recipes->the_post(); ?>
        <article <?php post_class('group bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow'); ?>>
          <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
              <?php the_post_thumbnail('large', ['class' => 'w-full h-56 object-cover group-hover:scale-105 transition-transform']); ?>
            </a>
          <?php endif; ?>
          <div class="p-6">
            <span class="text-xs uppercase tracking-wide text-sage font-semibold">
              <?php
                $terms = get_the_terms( get_the_ID(), 'recipe_origin' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                  $links = [];
                  foreach ( $terms as $term ) {
                    $links[] = '<a href="' . esc_url( get_term_link( $term ) ) . '" class="hover:underline">'
                             . esc_html( $term->name ) . '</a>';
                  }
                  echo implode( ', ', $links );
                }
              ?>
            </span>
            <h3 class="font-heading text-xl text-basil mt-2 mb-3">
              <a href="<?php the_permalink(); ?>" class="hover:text-terracotta"><?php the_title(); ?></a>
            </h3>
            <p class="font-body text-charcoal text-sm leading-relaxed">
              <?php
                $excerpt = get_the_excerpt();
                echo esc_html( wp_trim_words( $excerpt ? $excerpt : wp_strip_all_tags( get_the_content() ), 18 ) );
              ?>
            </p>
          </div>
        </article>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>
    <div class="text-center mt-12">
      <a href="<?php echo get_post_type_archive_link('recipe'); ?>"
         class="inline-block bg-basil text-cream px-6 py-3 rounded font-semibold hover:bg-terracotta transition-colors">
        View All Recipes
      </a>
    </div>
  <?php else : ?>
    <p class="text-center text-gray-600">No recipes published yet.</p>
  <?php endif; ?>
</section>

<!-- Recipe Origin Hub -->
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
              <?php echo esc_html($maori); ?> <span class="block text-sm text-cream/80"><?php echo esc_html($term->name); ?></span>
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

<!-- Shop Teaser -->
<section class="bg-white py-20">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h2 class="font-heading text-3xl text-basil mb-6">Shop Harvest &amp; Hearth</h2>
    <p class="font-body text-charcoal mb-8">Heritage seeds, rustic kitchenware, seasonal boxes — coming soon.</p>
    <a href="<?php echo site_url('/shop'); ?>"
       class="inline-block bg-terracotta text-cream px-6 py-3 rounded font-semibold hover:bg-basil transition-colors">
      Browse the Shop
    </a>
  </div>
</section>

<!-- Grow Hub Strip -->
<section id="grow-section" class="bg-sage py-20 text-cream">
  <div class="max-w-7xl mx-auto px-6">

    <h2 class="font-heading text-3xl mb-12 text-center">Grow with Harvest &amp; Hearth</h2>

    <!-- Filter Bar -->
    <nav class="mb-12 text-center">
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
      // 👇 build the URL and append the anchor
      $url = add_query_arg('grow_filter', $slug, home_url('/')) . '#grow-section';
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


    <!-- Posts Grid -->
    <?php
    $args = [
      'post_type'      => 'post',
      'posts_per_page' => 4,
    ];
    if ($current !== 'all') {
      $args['category_name'] = $current;
    }
    $grow_posts = new WP_Query($args);
    ?>

    <?php if ($grow_posts->have_posts()) : ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php while ($grow_posts->have_posts()) : $grow_posts->the_post(); ?>
          <article class="bg-white/10 rounded-lg overflow-hidden hover:bg-white/20 transition-colors">
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
      <p class="text-center text-gray-600">No Grow posts found yet.</p>
    <?php endif; ?>

    <!-- View All Button -->
    <div class="text-center mt-12">
      <a href="<?php echo site_url('/grow'); ?>"
         class="inline-block bg-cream text-basil px-6 py-3 rounded font-semibold hover:bg-terracotta hover:text-cream transition-colors">
        View Grow Hub
      </a>
    </div>

  </div>
</section>

<!-- CTA -->
<section class="relative py-24 bg-sage text-cream">
  <div class="absolute inset-0 bg-[url('<?php echo get_theme_file_uri('/assets/images/linen-texture.jpg'); ?>')] opacity-20"></div>
  <div class="relative max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h2 class="font-heading text-4xl mb-4">Join Our Table</h2>
      <p class="font-body mb-6">Get seasonal recipes and rustic living tips delivered to your inbox.</p>
      <form class="flex flex-col sm:flex-row gap-3 w-full">
        <input type="email" placeholder="Your email"
               class="flex-1 px-4 py-3 rounded text-charcoal min-w-0">
        <button class="bg-terracotta px-6 py-3 rounded font-semibold hover:bg-basil transition-colors w-full sm:w-auto">
          Subscribe
        </button>
      </form>
    </div>
    <div class="hidden md:block">
      <img src="<?php echo get_theme_file_uri('/assets/images/bread-basket.jpg'); ?>" alt="" class="w-full max-w-sm mx-auto">
    </div>
  </div>
</section>

<?php get_footer(); ?>


