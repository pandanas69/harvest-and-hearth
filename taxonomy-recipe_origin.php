<?php
/**
 * Taxonomy Template for Recipe Origins
 */

defined( 'ABSPATH' ) || exit;

get_header();

$term = get_queried_object();
$image_url = function_exists('hh_get_recipe_origin_image_url')
    ? hh_get_recipe_origin_image_url($term->term_id, 'large')
    : '';

// Handle combined filter: if ?recipe_category= is set, adjust the query
if ( isset($_GET['recipe_category']) && $_GET['recipe_category'] ) {
    add_action('pre_get_posts', function($query) use ($term) {
        if ( $query->is_main_query() && !is_admin() && $query->is_tax('recipe_origin', $term->slug) ) {
            $query->set('tax_query', [
                'relation' => 'AND',
                [
                    'taxonomy' => 'recipe_origin',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ],
                [
                    'taxonomy' => 'recipe_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET['recipe_category']),
                ],
            ]);
        }
    });
}
?>

<main id="site-content" class="container mx-auto px-4 py-12">

   <?php
$maori = get_term_meta($term->term_id, 'maori_label', true);
$descriptor = get_term_meta($term->term_id, 'descriptor', true);
?>
<section class="relative text-center mb-12">
  <?php if ( $image_url ) : ?>
    <div class="h-64 bg-cover bg-center rounded-lg shadow mb-8"
         style="background-image:url('<?php echo esc_url($image_url); ?>')">
      <div class="h-full w-full bg-black/40 flex flex-col items-center justify-center">
        <h1 class="text-5xl font-heading text-cream">
          <?php echo esc_html($maori); ?>
          <span class="block text-lg text-cream/80"><?php echo esc_html($term->name); ?></span>
        </h1>
        <?php if ($descriptor): ?>
          <p class="mt-2 text-cream text-sm"><?php echo esc_html($descriptor); ?></p>
        <?php endif; ?>
      </div>
    </div>
  <?php else : ?>
    <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-gray-900">
      <?php echo esc_html($maori ?: $term->name); ?>
    </h1>
  <?php endif; ?>
</section>


    <!-- Category Filter Bar -->
    <?php
// Fetch ALL recipe IDs for this Origin (not just current page)
$origin_post_ids = get_posts([
    'post_type'      => 'recipe',
    'fields'         => 'ids',
    'nopaging'       => true,         // include all posts in this origin
    'no_found_rows'  => true,
    'tax_query'      => [[
        'taxonomy' => 'recipe_origin',
        'field'    => 'term_id',
        'terms'    => $term->term_id,
    ]],
]);

// Only show categories that are actually used by these origin posts
$categories = $origin_post_ids ? get_terms([
    'taxonomy'   => 'recipe_category',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
    'object_ids' => $origin_post_ids, // scope to posts in this origin
]) : [];

if ( $categories && ! is_wp_error($categories) ) : ?>
    <nav class="mb-12 text-center">
        <ul class="inline-flex flex-wrap gap-3 justify-center">
            <?php foreach ( $categories as $cat ) :
                $active = ( isset($_GET['recipe_category']) && $_GET['recipe_category'] === $cat->slug );
                ?>
                <li>
                    <a href="<?php echo esc_url( add_query_arg('recipe_category', $cat->slug, get_term_link($term)) ); ?>"
                       class="px-4 py-2 rounded-full border <?php echo $active ? 'bg-sage text-white' : 'border-sage text-sage hover:bg-sage hover:text-white'; ?> transition">
                        <?php echo esc_html( $cat->name ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <?php if ( isset($_GET['recipe_category']) ) : ?>
                <li>
                    <a href="<?php echo esc_url( get_term_link($term) ); ?>"
                       class="px-4 py-2 rounded-full border border-gray-400 text-gray-600 hover:bg-gray-200 transition">
                        Clear
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>


    <!-- Recipes Grid -->
    <?php if ( have_posts() ) : ?>
        <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php while ( have_posts() ) : the_post(); ?>
                <li class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                    <a href="<?php the_permalink(); ?>" class="block">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="aspect-w-4 aspect-h-3 bg-gray-100">
                                <?php the_post_thumbnail( 'medium_large', ['class' => 'w-full h-full object-cover'] ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <!-- Categories -->
                            <div class="text-xs uppercase tracking-wide text-sage font-semibold mb-2">
                                <?php
                                $cats = get_the_terms( get_the_ID(), 'recipe_category' );
                                if ( $cats && ! is_wp_error($cats) ) {
                                    $links = [];
                                    foreach ( $cats as $cat ) {
                                        $links[] = '<a href="' . esc_url( add_query_arg('recipe_category', $cat->slug, get_term_link($term)) ) . '" class="hover:underline">'
                                                 . esc_html( $cat->name ) . '</a>';
                                    }
                                    echo implode( ', ', $links );
                                }
                                ?>
                            <h2 class="text-lg font-semibold text-gray-900">
                                <a href="<?php the_permalink(); ?>" class="hover:underline">
                            <?php the_title(); ?>
                            </a>
                            </h2>

                    </a>
                </li>
            <?php endwhile; ?>
        </ul>

        <div class="mt-8">
            <?php the_posts_pagination(); ?>
        </div>

    <?php else : ?>
        <p class="text-center text-gray-600">No recipes found in this origin yet.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>

