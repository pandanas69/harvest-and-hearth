<?php get_header(); ?>

<main id="site-content" class="max-w-7xl mx-auto px-6 py-12">

  <!-- Hero -->
  <section class="text-center mb-12">
    <h1 class="font-heading text-4xl md:text-5xl text-basil mb-4">Plant Care &amp; Grow Guides</h1>
    <p class="font-body text-charcoal max-w-2xl mx-auto">
      Explore our collection of practical care tips and seasonal growing advice.
    </p>
  </section>

  <!-- Posts Grid -->
  <?php if ( have_posts() ) : ?>
    <div class="grid md:grid-cols-3 gap-8">
      <?php while ( have_posts() ) : the_post(); ?>
        <article class="bg-white rounded-lg shadow hover:shadow-lg overflow-hidden">
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('large', ['class'=>'w-full h-48 object-cover']); ?>
          </a>
          <div class="p-6">
            <h2 class="font-heading text-xl mb-2">
              <a href="<?php the_permalink(); ?>" class="hover:text-terracotta"><?php the_title(); ?></a>
            </h2>
            <p class="font-body text-sm text-charcoal"><?php echo esc_html( wp_trim_words(get_the_excerpt(), 20) ); ?></p>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <div class="mt-12">
      <?php the_posts_pagination(); ?>
    </div>
  <?php else : ?>
    <p class="text-center text-gray-600">No plant care guides published yet.</p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

