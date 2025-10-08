<?php get_header(); ?>

<header class="mb-10">
  <h1 class="font-heading text-3xl text-basil">
    <?php
      if ( is_category() ) {
        single_cat_title();
      } elseif ( is_tag() ) {
        single_tag_title();
      } elseif ( is_author() ) {
        the_post();
        echo 'Author: ' . get_the_author();
        rewind_posts();
      } elseif ( is_day() ) {
        echo 'Day: ' . get_the_date();
      } elseif ( is_month() ) {
        echo 'Month: ' . get_the_date('F Y');
      } elseif ( is_year() ) {
        echo 'Year: ' . get_the_date('Y');
      } else {
        echo 'Archives';
      }
    ?>
  </h1>
  <?php if ( term_description() ) : ?>
    <div class="font-body text-charcoal mt-2">
      <?php echo term_description(); ?>
    </div>
  <?php endif; ?>
</header>

<?php if ( have_posts() ) : ?>
  <div class="grid gap-10">
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class('bg-cream shadow rounded p-6'); ?>>
        <h2 class="font-heading text-2xl text-basil mb-2">
          <a href="<?php the_permalink(); ?>" class="hover:text-sage"><?php the_title(); ?></a>
        </h2>
        <div class="font-body text-charcoal prose max-w-none">
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
    <h2 class="font-heading text-3xl mb-3">No posts found</h2>
    <p class="font-body text-charcoal">There’s nothing here yet — check back soon.</p>
  </div>
<?php endif; ?>

<?php get_footer(); ?>
