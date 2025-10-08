<?php
/**
 * Single Recipe Template (no ACF)
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<main id="site-content" class="container mx-auto px-4 py-12">

    <?php while ( have_posts() ) : the_post(); ?>

        <!-- Hero / Featured Image -->
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="mb-8">
                <?php the_post_thumbnail( 'full', ['class' => 'w-full h-96 object-cover rounded-lg shadow'] ); ?>
            </div>
        <?php endif; ?>

        <!-- Title & Meta -->
        <header class="mb-6 text-center">
            <h1 class="text-4xl font-bold text-gray-900"><?php the_title(); ?></h1>
            <p class="mt-2 text-gray-600 text-sm">
                <?php echo get_the_date(); ?> · <?php the_author(); ?>
                <?php
                $terms = get_the_terms( get_the_ID(), 'recipe_category' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    echo ' · ';
                    echo esc_html( join( ', ', wp_list_pluck( $terms, 'name' ) ) );
                }
                ?>
            </p>
        </header>

        <!-- Recipe Content -->
        <article class="prose max-w-none mx-auto">
            <?php the_content(); ?>
        </article>

        <!-- Recipe Meta Fields -->
        <?php
        $prep_time  = get_post_meta( get_the_ID(), '_prep_time', true );
        $serves     = get_post_meta( get_the_ID(), '_serves', true );
        $difficulty = get_post_meta( get_the_ID(), '_difficulty', true );
        ?>
        <?php if ( $prep_time || $serves || $difficulty ) : ?>
            <section class="mt-8 flex flex-wrap justify-center gap-6 text-center text-gray-700">
                <?php if ( $prep_time ) : ?>
                    <div><span class="font-semibold">Prep Time:</span> <?php echo esc_html( $prep_time ); ?></div>
                <?php endif; ?>
                <?php if ( $serves ) : ?>
                    <div><span class="font-semibold">Serves:</span> <?php echo esc_html( $serves ); ?></div>
                <?php endif; ?>
                <?php if ( $difficulty ) : ?>
                    <div><span class="font-semibold">Difficulty:</span> <?php echo esc_html( $difficulty ); ?></div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <!-- Ingredients & Instructions -->
        <?php
        $ingredients  = get_post_meta( get_the_ID(), '_ingredients', true );
        $instructions = get_post_meta( get_the_ID(), '_instructions', true );
        ?>
        <?php if ( $ingredients || $instructions ) : ?>
            <section class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php if ( $ingredients ) : ?>
                    <div class="bg-gray-50 p-6 rounded-lg shadow">
                        <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e( 'Ingredients', 'yourtheme' ); ?></h2>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <?php
                            // If stored as newline-separated text
                            $items = is_array($ingredients) ? $ingredients : explode("\n", $ingredients);
                            foreach ( $items as $item ) :
                                if ( trim($item) ) :
                                    echo '<li>' . esc_html( trim($item) ) . '</li>';
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ( $instructions ) : ?>
                    <div class="bg-gray-50 p-6 rounded-lg shadow">
                        <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e( 'Instructions', 'yourtheme' ); ?></h2>
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <?php
                            $steps = is_array($instructions) ? $instructions : explode("\n", $instructions);
                            foreach ( $steps as $step ) :
                                if ( trim($step) ) :
                                    echo '<li>' . esc_html( trim($step) ) . '</li>';
                                endif;
                            endforeach;
                            ?>
                        </ol>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <!-- Shop Ingredients CTA -->
        <div class="mt-12 text-center">
            <a href="<?php echo esc_url( home_url( '/shop' ) ); ?>"
               class="inline-block bg-brand hover:bg-brand-dark text-white font-semibold px-6 py-3 rounded">
                <?php esc_html_e( 'Shop Ingredients', 'yourtheme' ); ?>
            </a>
        </div>

        <!-- Related Recipes -->
        <section class="mt-16">
            <?php
            $cat_ids    = wp_get_post_terms( get_the_ID(), 'recipe_category', ['fields' => 'ids'] );
            $origin_ids = wp_get_post_terms( get_the_ID(), 'recipe_origin',   ['fields' => 'ids'] );

            $heading   = __( 'More Recipes', 'yourtheme' ); // default
            $tax_query = [];

            if ( !empty($cat_ids) ) {
                $tax_query[] = [
                    'taxonomy' => 'recipe_category',
                    'field'    => 'term_id',
                    'terms'    => $cat_ids,
                ];
                $cat_obj = get_term( $cat_ids[0], 'recipe_category' );
                if ( $cat_obj && ! is_wp_error($cat_obj) ) {
                    $heading = sprintf( __( 'More %s Recipes', 'yourtheme' ), $cat_obj->name );
                }
            } elseif ( !empty($origin_ids) ) {
                $tax_query[] = [
                    'taxonomy' => 'recipe_origin',
                    'field'    => 'term_id',
                    'terms'    => $origin_ids,
                ];
                $origin_obj = get_term( $origin_ids[0], 'recipe_origin' );
                if ( $origin_obj && ! is_wp_error($origin_obj) ) {
                    $heading = sprintf( __( 'More from %s', 'yourtheme' ), $origin_obj->name );
                }
            }

            $args = [
                'post_type'      => 'recipe',
                'posts_per_page' => 3,
                'post__not_in'   => [ get_the_ID() ],
            ];
            if ( !empty($tax_query) ) {
                $args['tax_query'] = $tax_query;
            }

            $related = new WP_Query($args);

            if ( !$related->have_posts() ) {
                $related = new WP_Query([
                    'post_type'      => 'recipe',
                    'posts_per_page' => 3,
                    'post__not_in'   => [ get_the_ID() ],
                ]);
                $heading = __( 'Latest Recipes', 'yourtheme' );
            }
            ?>

            <h2 class="text-2xl font-semibold mb-6 text-center">
                <?php echo esc_html( $heading ); ?>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php if ( $related->have_posts() ) :
                    while ( $related->have_posts() ) : $related->the_post(); ?>
                        <article class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium', ['class' => 'w-full h-40 object-cover'] ); ?>
                                <?php endif; ?>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 hover:underline">
                                        <?php the_title(); ?>
                                    </h3>
                                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                        <?php
                                        $cats = get_the_terms( get_the_ID(), 'recipe_category' );
                                        if ( $cats && ! is_wp_error($cats) ) {
                                            foreach ( $cats as $cat ) {
                                                echo '<a href="' . esc_url( get_term_link($cat) ) . '" 
                                                         class="px-3 py-1 rounded-full border border-sage text-sage hover:bg-sage hover:text-white transition">'
                                                     . esc_html( $cat->name ) . '</a>';
                                            }
                                        }
                                        $origins = get_the_terms( get_the_ID(), 'recipe_origin' );
                                        if ( $origins && ! is_wp_error($origins) ) {
                                            foreach ( $origins as $origin ) {
                                                echo '<a href="' . esc_url( get_term_link($origin) ) . '" 
                                                         class="px-3 py-1 rounded-full border border-basil text-basil hover:bg-basil hover:text-white transition">'
                                                     . esc_html( $origin->name ) . '</a>';
                                            }
                                        }
                                        ?>
                                                                    </div>
                            </a>
                        </article>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p class="text-center text-gray-600"><?php esc_html_e( 'No recipes found.', 'yourtheme' ); ?></p>
                <?php endif; ?>
            </div>
        </section>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>

