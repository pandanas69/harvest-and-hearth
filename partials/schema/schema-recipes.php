<?php
/**
 * Schema.org Recipe JSON-LD
 * Loaded only on single recipe pages
 */

if ( ! is_singular('recipe') ) return;

$ingredients = [];
if ( function_exists('get_field') && get_field('ingredients') ) {
    foreach ( get_field('ingredients') as $i ) {
        $ingredients[] = $i['item'];
    }
}

$instructions = [];
if ( function_exists('get_field') && get_field('instructions') ) {
    foreach ( get_field('instructions') as $s ) {
        $instructions[] = [
            '@type' => 'HowToStep',
            'text'  => $s['step'],
        ];
    }
}
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Recipe",
  "name": "<?php echo esc_js( get_the_title() ); ?>",
  "image": "<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>",
  "author": {
    "@type": "Person",
    "name": "<?php echo esc_js( get_the_author() ); ?>"
  },
  "datePublished": "<?php echo get_the_date( 'c' ); ?>",
  "description": "<?php echo esc_js( get_the_excerpt() ); ?>",
  "recipeIngredient": <?php echo wp_json_encode( $ingredients ); ?>,
  "recipeInstructions": <?php echo wp_json_encode( $instructions ); ?>
}
</script>

