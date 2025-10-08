<?php get_header(); ?>

<header class="mb-10 text-center">
  <h1 class="font-heading text-4xl text-basil">
    <?php echo get_the_title( get_option('page_for_posts', true) ); ?>
  </h1>
  <p class="font-body text-charcoal mt-2">
    Our latest recipes and stories from the hearth.
  </p>
</header>

<?php if ( have_posts() ) : ?>
  <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class('bg-cream shadow rounded overflow-hidden'); ?>>
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium', ['class' => 'w-full h-auto']); ?>
          </a>
        <?php endif; ?>
        <div class="p-6">
          <h2 class="font-heading text-xl text-basil mb-2">
            <a href="<?php the_permalink(); ?>" class="hover:text-sage"><?php the_title(); ?></a>
          </h2>
          <div class="font-body text-charcoal text-sm">
            <?php the_excerpt(); ?>
          </div>
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
    <p class="font-body text-charcoal">Check back soon for fresh recipes.</p>
  </div>
<?php endif; ?>

<?php get_footer(); ?>
