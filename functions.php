<?php
/**
 * Harvest & Hearth Theme Functions
 *
 * @package HarvestHearth
 */

/**
 * Enqueue frontend styles.
 */
function hh_enqueue_assets() {
    $css_path = get_stylesheet_directory() . '/dist/main.css';
    $css_uri  = get_stylesheet_directory_uri() . '/dist/main.css';

    $version = file_exists( $css_path ) ? filemtime( $css_path ) : wp_get_theme()->get( 'Version' );

    wp_enqueue_style( 'hh-main', $css_uri, [], $version );



    wp_enqueue_style(
        'hh-fontawesome',
        get_stylesheet_directory_uri() . '/assets/fontawesome/css/all.min.css',
        [],
        '6.5.1'
    );
    if ( is_page_template('page-planting-calendar.php') ) {
    wp_enqueue_script(
        'html2pdf',
        get_stylesheet_directory_uri() . '/assets/js/vendor/html2pdf.bundle.min.js',
        [],
        '0.10.1',
        true
    );

    wp_enqueue_script(
        'calendar-pdf',
        get_stylesheet_directory_uri() . '/assets/js/calendar-pdf.js',
        ['html2pdf'],
        $version,
        true
    );
}


    wp_enqueue_script(
        'hh-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hh_enqueue_assets' );

function hh_editor_supports() {
    // Load your Tailwind CSS into the editor
    add_theme_support('editor-styles');
    add_editor_style('dist/main.css');

    // Register your brand palette for the block editor
    add_theme_support('editor-color-palette', [
        [ 'name' => 'Sage',       'slug' => 'sage',       'color' => '#9CAF88' ],
        [ 'name' => 'Basil',      'slug' => 'basil',      'color' => '#4E6E4E' ],
        [ 'name' => 'Cream',      'slug' => 'cream',      'color' => '#F5F2E7' ],
        [ 'name' => 'Terracotta', 'slug' => 'terracotta', 'color' => '#C97D60' ],
        [ 'name' => 'Charcoal',   'slug' => 'charcoal',   'color' => '#333333' ],
    ]);
}
add_action('after_setup_theme', 'hh_editor_supports');


/**
 * Theme setup.
 */
function hh_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption' ] );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'appearance-tools' );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'harvesthearth' ),
    ] );

    add_theme_support( 'editor-styles' );
    add_editor_style(
        add_query_arg(
            'ver',
            filemtime( get_stylesheet_directory() . '/dist/main.css' ),
            get_stylesheet_directory_uri() . '/dist/main.css'
        )
    );
}
add_action( 'after_setup_theme', 'hh_theme_setup' );

/**
 * Register custom block pattern category and patterns.
 */
function hh_register_block_patterns() {
    register_block_pattern_category(
        'harvesthearth',
        [ 'label' => __( 'Harvest & Hearth', 'harvesthearth' ) ]
    );

    $patterns = [
        'hero'  => 'Harvest & Hearth Hero',
        'about' => 'Harvest & Hearth About Section',
        'cta'   => 'Harvest & Hearth Call to Action',
    ];

    foreach ( $patterns as $slug => $title ) {
        $file = get_theme_file_path( "patterns/{$slug}.php" );
        if ( file_exists( $file ) ) {
            register_block_pattern(
                "harvesthearth/{$slug}",
                [
                    'title'       => __( $title, 'harvesthearth' ),
                    'description' => '',
                    'categories'  => [ 'harvesthearth' ],
                    'content'     => file_get_contents( $file ),
                ]
            );
        }
    }
}
add_action( 'init', 'hh_register_block_patterns' );

/**
 * Icon shortcode + cheatsheet page
 */
function hh_icon_shortcode( $atts ) {
    $atts = shortcode_atts( [
        'name'  => '',
        'style' => 'solid',
        'class' => '',
    ], $atts );

    if ( ! $atts['name'] ) return '';

    $style_class = 'fa-' . esc_attr( $atts['style'] );
    $icon_class  = 'fa-' . esc_attr( $atts['name'] );
    $extra_class = esc_attr( $atts['class'] );

    return '<i class="fa ' . $style_class . ' ' . $icon_class . ' ' . $extra_class . '"></i>';
}
add_shortcode( 'icon', 'hh_icon_shortcode' );

function hh_icon_cheatsheet_menu() {
    add_menu_page(
        'Icon Cheatsheet',
        'Icon Cheatsheet',
        'manage_options',
        'hh-icon-cheatsheet',
        'hh_icon_cheatsheet_page',
        'dashicons-art',
        90
    );
}
add_action('admin_menu', 'hh_icon_cheatsheet_menu');

function hh_icon_cheatsheet_page() {
    ?>
    <div class="wrap">
        <h1>Harvest & Hearth Icon Cheatsheet</h1>
        <p>Copy the HTML or shortcode below to use icons in posts/pages.</p>
        <!-- tables omitted for brevity -->
    </div>
    <?php
}

/**
 * Featured image optimization + WebP support
 */
add_image_size( 'featured-large', 1600, 900, true );
add_image_size( 'featured-medium', 1200, 675, true );
add_image_size( 'featured-small', 600, 338, true );

function hh_featured_image_size( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    if ( $size === 'post-thumbnail' ) {
        $html = get_the_post_thumbnail( $post_id, 'featured-medium', $attr );
    }
    return $html;
}
add_filter( 'post_thumbnail_html', 'hh_featured_image_size', 10, 5 );

add_filter( 'jpeg_quality', fn() => 80 );
add_filter( 'wp_editor_set_quality', fn() => 80 );

function hh_generate_webp_versions( $metadata, $attachment_id ) {
    $upload_dir = wp_upload_dir();
    $file       = $upload_dir['basedir'] . '/' . $metadata['file'];
    $path       = pathinfo( $file );

    if ( ! empty( $metadata['sizes'] ) ) {
        foreach ( $metadata['sizes'] as $sizeinfo ) {
            $image_path = $path['dirname'] . '/' . $sizeinfo['file'];
            $editor = wp_get_image_editor( $image_path );
            if ( ! is_wp_error( $editor ) ) {
                $editor->set_quality( 80 );
                $webp_path = $path['dirname'] . '/' . pathinfo( $sizeinfo['file'], PATHINFO_FILENAME ) . '.webp';
                $editor->save( $webp_path, 'image/webp' );
            }
        }
    }
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'hh_generate_webp_versions', 20, 2 );

function hh_prefer_webp( $image, $attachment_id, $size, $icon ) {
    if ( ! $image ) return $image;
    $upload_dir = wp_get_upload_dir();
    $webp_url   = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $image[0] );
    $webp_file  = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $webp_url );
    if ( file_exists( $webp_file ) ) {
        $image[0] = $webp_url;
    }
    return $image;
}
add_filter( 'wp_get_attachment_image_src', 'hh_prefer_webp', 20, 4 );

function hh_contextual_featured_image( $post_id = null, $attr = [] ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    if ( is_singular( 'post' ) ) {
        echo get_the_post_thumbnail( $post_id, 'featured-large', $attr );
    } elseif ( is_archive() || is_home() ) {
        echo get_the_post_thumbnail( $post_id, 'featured-medium', $attr );
    } else {
        echo get_the_post_thumbnail( $post_id, 'featured-small', $attr );
    }
}

/**
 * Login logo, meta, favicons, social meta
 */
function harvesthearth_login_styles() {
  ?>
  <style>
    /* Background */
    body.login {
      background-color: #fdfbf7; /* your cream brand color */
      font-family: "Playfair Display", serif;
    }

    .login form {
  max-width: 320px;
  margin: 0 auto;
}


    /* Logo */
    .login h1 a {
      background-image: url('<?php echo get_theme_file_uri('/assets/images/logo_new_black.svg'); ?>')!important;
      background-size: contain !important;
      background-repeat: no-repeat !important;
      width: 200px !important;
      height: 80px !important;
    }

    /* Login form card */
    .login form {
      background: #ffffff;
      border: 2px solid #4a7c59; /* basil green */
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 2rem;
    }

    /* Labels and inputs */
    .login label {
      color: #2f2f2f; /* charcoal */
      font-weight: 600;
    }
    .login input[type="text"],
    .login input[type="password"] {
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 0.5rem;
      font-size: 14px;
    }
    .login input:focus {
      border-color: #4a7c59;
      box-shadow: 0 0 0 1px #4a7c59;
    }

    /* Primary button */
    .login .button-primary {
      background-color: #4a7c59;
      border-color: #4a7c59;
      text-shadow: none;
      box-shadow: none;
      font-weight: 600;
    }
    .login .button-primary:hover {
      background-color: #3a6247;
      border-color: #3a6247;
    }

    /* Links */
    .login #nav a,
    .login #backtoblog a {
      color: #4a7c59;
      font-weight: 500;
    }
    .login #nav a:hover,
    .login #backtoblog a:hover {
      color: #2f2f2f;
    }

    /* Optional: hide WP logo link hover outline */
    .login h1 a:focus {
      box-shadow: none;
    }
  </style>
  <?php
}
add_action('login_head', 'harvesthearth_login_styles');

function harvesthearth_login_url() {
  return home_url();
}
add_filter('login_headerurl', 'harvesthearth_login_url');



function harvestandhearth_meta_description() {
    $desc = ( is_singular() && has_excerpt() ) ? get_the_excerpt() : get_bloginfo( 'description' );
    echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . '">' . "\n";
}
add_action( 'wp_head', 'harvestandhearth_meta_description' );

function harvestandhearth_favicons() {
    $dir = get_stylesheet_directory_uri() . '/assets/icons';
    ?>
    <link rel="icon" type="image/svg+xml" href="<?php echo $dir; ?>/favicon-16x16.svg" />
    <link rel="icon" href="<?php echo $dir; ?>/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" href="<?php echo $dir; ?>/favicon-16x16.png" sizes="16x16" />
    <link rel="apple-touch-icon" href="<?php echo $dir; ?>/apple-touch-icon.png" />
    <link rel="manifest" href="<?php echo $dir; ?>/site.webmanifest" />
    <meta name="theme-color" content="#fff">
    <?php
}
add_action( 'wp_head', 'harvestandhearth_favicons' );

function harvestandhearth_social_meta() {
    $title = is_singular() ? single_post_title( '', false ) : get_bloginfo( 'name' );
    $desc  = is_singular() && has_excerpt() ? get_the_excerpt() : get_bloginfo( 'description' );
    $url   = is_singular() ? get_permalink() : home_url();
    $image = get_stylesheet_directory_uri() . '/assets/icons/social-share.png';
    ?>
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
    <meta property="og:description" content="<?php echo esc_attr( wp_strip_all_tags( $desc ) ); ?>" />
    <meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
    <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>" />
    <meta property="og:image" content="<?php echo esc_url( $image ); ?>" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr( wp_strip_all_tags( $desc ) ); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url( $image ); ?>" />
    <?php
}
add_action( 'wp_head', 'harvestandhearth_social_meta' );


// ---------------------------------------------
// Navigation menu filters
// ---------------------------------------------

// Ensure every parent <li> can drive group-hover
add_filter('nav_menu_css_class', function($classes, $item, $args, $depth) {
    if (in_array('menu-item-has-children', $classes)) {
        $classes[] = 'group';
        $classes[] = 'has-children';
    }
    return $classes;
}, 10, 4);

// Add hidden + depth markers to each submenu <ul>
add_filter('nav_menu_submenu_css_class', function($classes, $args, $depth) {
    $classes[] = 'hidden';
    $classes[] = 'group-hover:block';
    $classes[] = $depth === 0 ? 'submenu-depth-0' : 'submenu-depth-1';
    return $classes;
}, 10, 3);


// ---------------------------------------------
// Inject latest posts under category items (desktop only)
// ---------------------------------------------

function harvesthearth_inject_posts_into_menu( $items, $args ) {
    if ( empty( $args->menu_id ) || $args->menu_id !== 'desktop-primary' ) {
        return $items;
    }

    $new_items  = $items;
    $parent_map = [];
    $depth_map  = [];

    // Build parent map
    foreach ( $items as $it ) {
        $parent_map[ $it->ID ] = (int) $it->menu_item_parent;
    }

    // Build depth map
    foreach ( $items as $it ) {
        $depth = 0;
        $p     = $it->menu_item_parent;
        while ( $p ) {
            $depth++;
            $p = $parent_map[ $p ] ?? 0;
        }
        $depth_map[ $it->ID ] = $depth;
    }

    // Inject posts under category items at depth 1
    foreach ( $items as $item ) {
        if ( ( $depth_map[ $item->ID ] ?? 0 ) === 1 && $item->object === 'category' ) {
            // Skip if category already has children
            $has_children = false;
            foreach ( $items as $child ) {
                if ( (int) $child->menu_item_parent === (int) $item->ID ) {
                    $has_children = true;
                    break;
                }
            }
            if ( $has_children ) {
                continue;
            }

            $posts = get_posts( [
                'cat'            => (int) $item->object_id,
                'posts_per_page' => 3,
                'no_found_rows'  => true,
            ] );

            foreach ( $posts as $post ) {
                $m                     = new \stdClass();
                $m->ID                 = -absint( $post->ID ) - rand( 1, 999 );
                $m->db_id              = $m->ID;
                $m->menu_item_parent   = $item->ID;
                $m->object_id          = $post->ID;
                $m->object             = 'post';
                $m->type               = 'post_type';
                $m->title              = get_the_title( $post );
                $m->url                = get_permalink( $post );
                $m->classes            = [ 'menu-item', 'submenu-post' ];
                $m->post_type          = 'nav_menu_item';
                $m->post_status        = 'publish';

                $new_items[] = $m;
            }
        }
    }

    return $new_items;
}
add_filter( 'wp_nav_menu_objects', 'harvesthearth_inject_posts_into_menu', 10, 2 );



// ---------------------------------------------
// Add caret toggle buttons (mobile only)
// ---------------------------------------------
add_filter('walker_nav_menu_start_el', function($item_output, $item, $depth, $args) {
    if (isset($args->menu_id) && $args->menu_id === 'mobile-primary' && in_array('menu-item-has-children', $item->classes)) {
        $item_output .= '<button class="submenu-toggle ml-2 md:hidden" aria-expanded="false" aria-label="Toggle submenu">'
                      . '<svg class="w-4 h-4 transition-transform duration-300 rotate-0" viewBox="0 0 20 20" fill="currentColor">'
                      . '<path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>'
                      . '</svg></button>';
    }
    return $item_output;
}, 10, 4);

// Remove default WooCommerce wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Remove sidebar if not used
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Register Recipe Custom Post Type
 */
function hh_register_recipe_cpt() {
    $labels = [
        'name'               => __( 'Recipes', 'yourtheme' ),
        'singular_name'      => __( 'Recipe', 'yourtheme' ),
        'menu_name'          => __( 'Recipes', 'yourtheme' ),
        'name_admin_bar'     => __( 'Recipe', 'yourtheme' ),
        'add_new'            => __( 'Add New', 'yourtheme' ),
        'add_new_item'       => __( 'Add New Recipe', 'yourtheme' ),
        'new_item'           => __( 'New Recipe', 'yourtheme' ),
        'edit_item'          => __( 'Edit Recipe', 'yourtheme' ),
        'view_item'          => __( 'View Recipe', 'yourtheme' ),
        'all_items'          => __( 'All Recipes', 'yourtheme' ),
        'search_items'       => __( 'Search Recipes', 'yourtheme' ),
        'not_found'          => __( 'No recipes found.', 'yourtheme' ),
        'not_found_in_trash' => __( 'No recipes found in Trash.', 'yourtheme' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-carrot', // fun icon
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author' ],
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'recipes' ],
        'show_in_rest'       => true, // Gutenberg + API support
    ];

    register_post_type( 'recipe', $args );
}
add_action( 'init', 'hh_register_recipe_cpt' );

// Register Recipe Categories taxonomy
function hh_register_recipe_taxonomy() {
    register_taxonomy('recipe_category', 'recipe', [
        'label'        => 'Recipe Categories',
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'recipe-category' ],
        'show_admin_column' => true,
        'show_in_rest' => true,
    ]);
}
add_action( 'init', 'hh_register_recipe_taxonomy' );

add_action('wp_head', function() {
    if ( is_singular('recipe') ) {
        get_template_part('partials/schema/schema', 'recipe');
    }
});
// Register the brand pillar taxonomy: Earth / Air / Land / Sea
function hh_register_recipe_origin_taxonomy() {
    register_taxonomy('recipe_origin', 'recipe', [
        'label'             => 'Recipe Origin',
        'hierarchical'      => true,
        'rewrite'           => [ 'slug' => 'origin' ],
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ]);
}
add_action('init', 'hh_register_recipe_origin_taxonomy');


// Seed the top-level terms once, on theme switch.
// Comment out or remove after first run to avoid duplicates.
function hh_seed_recipe_origin_terms() {
    $taxonomy = 'recipe_origin';
    $terms = ['Earth', 'Air', 'Land', 'Sea'];

    foreach ($terms as $name) {
        if (!term_exists($name, $taxonomy)) {
            wp_insert_term($name, $taxonomy, ['slug' => strtolower($name)]);
        }
    }
}
add_action('after_switch_theme', 'hh_seed_recipe_origin_terms');


// Add image field to recipe_origin taxonomy add/edit screens
function hh_recipe_origin_add_media_field($taxonomy) {
    if ($taxonomy !== 'recipe_origin') return;
    ?>
    <div class="form-field term-image-wrap">
        <label for="term_image_id">Category Image</label>
        <input type="hidden" name="term_image_id" id="term_image_id" value="">
        <div id="term-image-preview" style="margin-top:8px;"></div>
        <button type="button" class="button term-image-upload">Select image</button>
        <button type="button" class="button term-image-remove" style="display:none;">Remove image</button>
        <p class="description">Used for the category card (Earth/Air/Land/Sea).</p>
    </div>
    <?php
}
add_action('recipe_origin_add_form_fields', 'hh_recipe_origin_add_media_field');

function hh_recipe_origin_edit_media_field($term, $taxonomy) {
    if ($taxonomy !== 'recipe_origin') return;
    $image_id  = get_term_meta($term->term_id, 'term_image_id', true);
    $image_src = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
    ?>
    <tr class="form-field term-image-wrap">
        <th scope="row"><label for="term_image_id">Category Image</label></th>
        <td>
            <input type="hidden" name="term_image_id" id="term_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div id="term-image-preview" style="margin-top:8px;">
                <?php if ($image_src) : ?>
                    <img src="<?php echo esc_url($image_src); ?>" style="max-width:160px;height:auto;">
                <?php endif; ?>
            </div>
            <button type="button" class="button term-image-upload">Select image</button>
            <button type="button" class="button term-image-remove" <?php echo $image_id ? '' : 'style="display:none;"'; ?>>Remove image</button>
            <p class="description">Used for the category card (Earth/Air/Land/Sea).</p>
        </td>
    </tr>
    <?php
}
add_action('recipe_origin_edit_form_fields', 'hh_recipe_origin_edit_media_field', 10, 2);


// Save the term image
function hh_recipe_origin_save_term_meta($term_id) {
    if (isset($_POST['term_image_id'])) {
        $id = intval($_POST['term_image_id']);
        if ($id) {
            update_term_meta($term_id, 'term_image_id', $id);
        } else {
            delete_term_meta($term_id, 'term_image_id');
        }
    }
}
add_action('created_recipe_origin', 'hh_recipe_origin_save_term_meta');
add_action('edited_recipe_origin',  'hh_recipe_origin_save_term_meta');


// Enqueue media uploader + JS for the buttons
function hh_recipe_origin_admin_assets($hook) {
    if (!isset($_GET['taxonomy']) || $_GET['taxonomy'] !== 'recipe_origin') return;

    wp_enqueue_media();
    wp_add_inline_script('jquery-core', "
        jQuery(document).ready(function($){
            var frame;
            function selectImage(){
                frame = wp.media({
                    title: 'Select category image',
                    button: { text: 'Use this image' },
                    library: { type: 'image' },
                    multiple: false
                });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#term_image_id').val(attachment.id);
                    $('#term-image-preview').html('<img src=\"'+attachment.sizes.medium.url+'\" style=\"max-width:160px;height:auto;\"/>');
                    $('.term-image-remove').show();
                });
                frame.open();
            }

            $(document).on('click', '.term-image-upload', function(e){
                e.preventDefault();
                selectImage();
            });

            $(document).on('click', '.term-image-remove', function(e){
                e.preventDefault();
                $('#term_image_id').val('');
                $('#term-image-preview').empty();
                $(this).hide();
            });
        });
    ");
}
add_action('admin_enqueue_scripts', 'hh_recipe_origin_admin_assets');


// Helper to fetch a term image URL with fallback
function hh_get_recipe_origin_image_url($term_id, $size = 'large') {
    $id = get_term_meta($term_id, 'term_image_id', true);
    if ($id) {
        $url = wp_get_attachment_image_url($id, $size);
        if ($url) return $url;
    }
    return get_theme_file_uri('/assets/images/fallback.jpg');
}

// Recipe meta box
add_action('add_meta_boxes', function() {
  add_meta_box('recipe_details', 'Recipe Details', function($post) {
    $prep = get_post_meta($post->ID, '_prep_time', true);
    $serves = get_post_meta($post->ID, '_serves', true);
    $difficulty = get_post_meta($post->ID, '_difficulty', true);
    ?>
    <p><label>Prep Time: <input type="text" name="prep_time" value="<?= esc_attr($prep); ?>"></label></p>
    <p><label>Serves: <input type="number" name="serves" value="<?= esc_attr($serves); ?>"></label></p>
    <p><label>Difficulty:
      <select name="difficulty">
        <?php foreach (['Easy','Medium','Hard'] as $opt): ?>
          <option value="<?= $opt; ?>" <?php selected($difficulty, $opt); ?>><?= $opt; ?></option>
        <?php endforeach; ?>
      </select>
    </label></p>
    <?php
  }, 'recipe');
});

// Save recipe fields
add_action('save_post_recipe', function($post_id) {
  if (isset($_POST['prep_time'])) update_post_meta($post_id, '_prep_time', sanitize_text_field($_POST['prep_time']));
  if (isset($_POST['serves'])) update_post_meta($post_id, '_serves', intval($_POST['serves']));
  if (isset($_POST['difficulty'])) update_post_meta($post_id, '_difficulty', sanitize_text_field($_POST['difficulty']));
});


// Add custom fields to recipe_origin taxonomy
function hh_add_origin_meta_fields( $term ) {
    $maori_label = get_term_meta( $term->term_id, 'maori_label', true );
    $descriptor  = get_term_meta( $term->term_id, 'descriptor', true );
    ?>
    <tr class="form-field">
        <th scope="row"><label for="maori_label">Māori Label</label></th>
        <td>
            <input type="text" name="maori_label" id="maori_label" value="<?php echo esc_attr( $maori_label ); ?>">
            <p class="description">Enter the Māori name for this origin (e.g. Moana, Whenua, Papatūānuku, Hau).</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="descriptor">Descriptor</label></th>
        <td>
            <input type="text" name="descriptor" id="descriptor" value="<?php echo esc_attr( $descriptor ); ?>">
            <p class="description">Short tagline or description for this origin tile.</p>
        </td>
    </tr>
    <?php
}
add_action( 'recipe_origin_edit_form_fields', 'hh_add_origin_meta_fields' );

// Save the fields
function hh_save_origin_meta_fields( $term_id ) {
    if ( isset( $_POST['maori_label'] ) ) {
        update_term_meta( $term_id, 'maori_label', sanitize_text_field( $_POST['maori_label'] ) );
    }
    if ( isset( $_POST['descriptor'] ) ) {
        update_term_meta( $term_id, 'descriptor', sanitize_text_field( $_POST['descriptor'] ) );
    }
}
add_action( 'edited_recipe_origin', 'hh_save_origin_meta_fields' );

// functions.php
function hh_conversion_widget() {
    ob_start(); ?>
    <div id="conversion-widget" class="conversion-widget">
  <div class="conv-row">
    <label for="conv-value">Value</label>
    <input type="number" id="conv-value" placeholder="e.g. 2">
  </div>

  <div class="conv-row">
    <label for="conv-type">Convert</label>
    <select id="conv-type">
      <option value="cups">Cups → mL</option>
      <option value="tbsp">Tablespoons → mL</option>
      <option value="tsp">Teaspoons → mL</option>
      <option value="oz">Ounces → Grams</option>
      <option value="lb">Pounds → Grams</option>
      <option value="gToLb">Grams → Pounds</option>
      <option value="lbToKg">Pounds → Kilograms</option>
      <option value="qtToL">Quarts → Litres</option>
      <option value="galToL">Gallons → Litres</option>
      <option value="f">°F → °C</option>
      <option value="c">°C → °F</option>
    </select>
  </div>

  <button id="conv-btn">Convert</button>

  <div id="conv-result"></div>
</div>

    <?php
    return ob_get_clean();
}
add_shortcode('conversion_widget', 'hh_conversion_widget');

function hh_enqueue_conversion_script() {
    // Replace with your actual page ID or slug check
    if ( is_page('kitchen-conversions') ) {
        wp_enqueue_script(
            'hh-conversion',
            get_template_directory_uri() . '/assets/js/conversion.js',
            array(),
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'hh_enqueue_conversion_script');


