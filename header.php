<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="bg-white py-4 shadow-sm z-30">
  <div class="max-w-6xl mx-auto px-6 relative flex items-center justify-between">

    <!-- Left: Hamburger (mobile only) -->
    <div class="w-10 md:hidden">
      <button id="nav-toggle"
              class="relative w-6 h-6 focus:outline-none"
              aria-controls="mobile-nav"
              aria-expanded="false"
              aria-label="Toggle navigation">
        <span class="absolute left-0 right-0 top-1 h-[1.5px] bg-black transition-transform duration-300"></span>
        <span class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-[1.5px] bg-black transition-opacity duration-300"></span>
        <span class="absolute left-0 right-0 top-[calc(100%-6px)] h-[1.5px] bg-black transition-transform duration-300"></span>
      </button>
    </div>

    <!-- Center: Logo (perfectly centered on mobile, left-aligned on desktop) -->
    <div class="absolute left-1/2 -translate-x-1/2 md:static md:transform-none md:flex-1 md:flex md:justify-start">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
         class="site-logo flex items-center text-black hover:text-basil mr-6 transition-colors duration-200 py-0">
        <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/logo_new_black.svg' ); ?>
      </a>
    </div>

    <!-- Right: Desktop nav + utilities -->
    <div class="hidden md:flex items-center gap-6">
      <!-- Desktop nav -->
      <nav class="flex">
        <?php
          wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'flex space-x-6 whitespace-nowrap',
            'link_before'    => '<span class="transition-colors duration-200 text-basil hover:text-sage">',
            'link_after'     => '</span>',
            'fallback_cb'    => false,
            'menu_id'        => 'desktop-primary',
          ]);
        ?>
         </nav>

      <div class="max-w-xs">
  <?php get_search_form(); ?>
</div>

      

      <!-- Cart -->
      <?php if ( function_exists('wc_get_cart_url') ) : ?>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="relative" aria-label="Cart">
          <svg class="w-6 h-6 text-gray-700 hover:text-basil" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4" />
            <circle cx="9" cy="21" r="1" />
            <circle cx="20" cy="21" r="1" />
          </svg>
          <?php if ( function_exists('WC') ) : ?>
            <span class="absolute -top-2 -right-2 bg-basil text-white text-xs rounded-full px-1">
              <?php echo intval( WC()->cart->get_cart_contents_count() ); ?>
            </span>
          <?php endif; ?>
        </a>
      <?php endif; ?>

      <!-- Account -->
      <?php
        $my_account_id  = function_exists('wc_get_page_id') ? wc_get_page_id('myaccount') : get_option('woocommerce_myaccount_page_id');
        $my_account_url = $my_account_id ? get_permalink( $my_account_id ) : wp_login_url();
      ?>
      <a href="<?php echo esc_url( $my_account_url ); ?>" class="inline-flex items-center text-gray-700 hover:text-basil" aria-label="Account">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5z" />
          <circle cx="12" cy="8" r="4" />
        </svg>
      </a>
    </div>
  </div>

  <!-- Backdrop -->
  <div id="nav-backdrop" class="fixed inset-0 bg-black/50 hidden md:hidden"></div>

  <!-- Mobile nav drawer -->
  <nav id="mobile-nav"
     class="fixed inset-y-0 right-0 z-40 h-screen w-64 bg-white text-basil
            transform translate-x-full transition-transform duration-300 md:hidden shadow-lg">
  <div class="p-6 flex flex-col gap-4 h-full overflow-y-auto pb-[env(safe-area-inset-bottom)] scroll-smooth">
     <!-- Mobile search (full width, styled) -->
     <?php get_search_form(); ?>

      <!-- Menu -->
      <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'flex flex-col gap-3',
          'link_before'    => '<span class="transition-colors duration-200 text-basil hover:text-sage">',
          'link_after'     => '</span>',
          'fallback_cb'    => false,
          'menu_id'        => 'mobile-primary',
        ]);
      ?>

      <!-- Cart + Account (mobile) -->
      <div class="mt-2 flex items-center gap-4">
        <?php if ( function_exists('wc_get_cart_url') ) : ?>
          <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="relative" aria-label="Cart">
          <svg class="w-6 h-6 text-gray-700 hover:text-basil" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4" />
            <circle cx="9" cy="21" r="1" />
            <circle cx="20" cy="21" r="1" />
          </svg>
          <?php if ( function_exists('WC') ) : ?>
            <span class="absolute -top-2 -right-2 bg-basil text-white text-xs rounded-full px-1">
              <?php echo intval( WC()->cart->get_cart_contents_count() ); ?>
            </span>
          <?php endif; ?>
        </a>
        <?php endif; ?>
         <a href="<?php echo esc_url( $my_account_url ); ?>" class="inline-flex items-center text-gray-700 hover:text-basil" aria-label="Account">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5z" />
          <circle cx="12" cy="8" r="4" />
        </svg>
      </a>
      </div>
    </div>
  </nav>
</header>
