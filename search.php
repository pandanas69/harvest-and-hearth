<?php get_header(); ?>

<main class="container mx-auto py-8">
  <?php if ( have_posts() ) : ?>
    <h1 class="text-2xl font-heading mb-6">
      Search results for: <?php echo get_search_query(); ?>
    </h1>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('border p-4 rounded'); ?>>
          <h2 class="text-xl font-heading mb-2">
            <a href="<?php the_permalink(); ?>" class="text-basil hover:text-sage">
              <?php the_title(); ?>
            </a>
          </h2>
          <div class="text-sm text-charcoal">
            <?php the_excerpt(); ?>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <?php the_posts_navigation(); ?>

  <?php else : ?>
    <h1 class="text-2xl font-heading mb-6">No results found</h1>
    <?php get_search_form(); ?>
  <?php endif; ?>
</main>

<?php get_footer(); ?>

