<?php get_header(); ?>

<main id="site-content" class="max-w-5xl mx-auto px-4 py-12">

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article <?php post_class('bg-cream shadow rounded p-6'); ?>>

      <!-- Title -->
      <h1 class="font-heading text-3xl text-basil mb-6"><?php the_title(); ?></h1>

      <!-- Featured Image -->
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="mb-6">
          <?php the_post_thumbnail( 'large', ['class' => 'rounded-lg w-full h-auto'] ); ?>
        </div>
      <?php endif; ?>

      <!-- Content -->
      <div class="font-body text-charcoal prose max-w-none mb-8">
        <?php the_content(); ?>
      </div>

      <!-- Footer Meta + Back Link -->
      <div class="flex justify-between items-center border-t border-sage pt-6">
        <?php
        if ( in_category('plant-care') ) {
          // Back to Plant Care archive
          $plant_care_link = get_category_link( get_cat_ID('plant-care') );
          echo '<a href="' . esc_url($plant_care_link) . '" 
                  class="text-basil font-semibold hover:text-sage transition-colors">
                  ← Back to Plant Care
                </a>';
        } elseif ( in_category('grow-guide') ) {
          // Back to Grow Guides archive
          $grow_link = get_category_link( get_cat_ID('grow-guide') );
          echo '<a href="' . esc_url($grow_link) . '" 
                  class="text-basil font-semibold hover:text-sage transition-colors">
                  ← Back to Grow Guides
                </a>';
        } else {
          // Default back to blog home
          $blog_page = get_option('page_for_posts');
          if ( $blog_page ) {
            echo '<a href="' . esc_url( get_permalink($blog_page) ) . '" 
                    class="text-basil font-semibold hover:text-sage transition-colors">
                    ← Back to Blog
                  </a>';
          }
        }
        ?>
        <div class="text-sm text-charcoal">
          Posted on <?php echo get_the_date(); ?>
        </div>
      </div>

    </article>
  <?php endwhile; else : ?>
    <div class="text-center py-20">
      <h2 class="font-heading text-3xl mb-3">Post not found</h2>
      <p class="font-body text-charcoal">Sorry, the post you’re looking for doesn’t exist.</p>
    </div>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

