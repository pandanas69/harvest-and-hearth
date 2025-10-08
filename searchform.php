<?php
/**
 * Custom search form for Harvest & Hearth
 */
?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="w-48 md:w-64 lg:w-72 flex-shrink-0">
  <div class="relative">
    <input type="search" name="s" placeholder="Search…"
           value="<?php echo get_search_query(); ?>"
           class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2 text-sm
                  focus:outline-none focus:ring-2 focus:ring-basil focus:border-basil" />
    <button type="submit"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-basil"
            aria-label="Search">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24" aria-hidden="true">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
    </button>
  </div>
</form>


