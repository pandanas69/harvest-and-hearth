<?php get_header(); ?>

<?php if ( have_posts() ) : ?>
  <div class="grid gap-10">
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class('bg-cream shadow rounded p-6'); ?>>
        <h2 class="font-heading text-2xl text-basil mb-2">
          <a href="<?php the_permalink(); ?>" class="hover:text-sage"><?php the_title(); ?></a>
        </h2>
        <div class="font-body text-charcoal prose prose-basil max-w-none">
          <?php the_excerpt(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
  <div class="mt-10">
    <?php the_posts_pagination(); ?>
  </div>
<?php else : ?>
  <div class="text-center py-20">
    <h2 class="font-heading text-3xl mb-3">No posts yet</h2>
    <p class="font-body text-charcoal">Start your garden by adding your first post.</p>
  </div>
<?php endif; ?>

<?php get_footer(); ?>
