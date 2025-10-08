<?php get_header(); ?>

<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
    <article <?php post_class('bg-cream shadow rounded p-6'); ?>>
      <h1 class="font-heading text-3xl text-basil mb-6"><?php the_title(); ?></h1>
      <div class="font-body text-charcoal prose max-w-none">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
<?php else : ?>
  <div class="text-center py-20">
    <h2 class="font-heading text-3xl mb-3">Page not found</h2>
    <p class="font-body text-charcoal">Sorry, the page you’re looking for doesn’t exist.</p>
  </div>
<?php endif; ?>

<?php get_footer(); ?>
