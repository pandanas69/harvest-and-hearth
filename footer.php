</main>

<footer class="bg-basil text-cream">
  <div class="max-w-6xl mx-auto px-6 py-12 grid gap-10 md:grid-cols-4 text-center md:text-left">

    <!-- Brand / Logo -->
    <div class="md:col-span-1">
      <h2 class="font-display text-2xl tracking-wide">Harvest &amp; Hearth</h2>
      <p class="mt-3 text-sm font-body opacity-80">
        Nourishing stories, recipes, and inspiration for a grounded life.
      </p>
    </div>

    <!-- Navigation -->
    <div>
      <h3 class="font-body uppercase text-sm text-white tracking-wider mb-4">Explore</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="/about" class="text-white hover:text-sage transition">About Us</a></li>
        <li><a href="/recipes" class="text-white hover:text-sage transition">Recipes</a></li>
        <li><a href="/grow" class="text-white hover:text-sage transition">Grow</a></li>
        <li><a href="/contact" class="text-white hover:text-sage transition">Contact</a></li>
      </ul>
    </div>

    <!-- Shop (WooCommerce) -->
    <div>
      <h3 class="font-body uppercase text-sm text-white tracking-wider mb-4">Shop</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="/shop" class="text-white hover:text-sage transition">All Products</a></li>
        <li><a href="/cart" class="text-white hover:text-sage transition">Cart</a></li>
        <li><a href="/my-account" class="text-white hover:text-sage transition">My Account</a></li>
        <li><a href="/checkout" class="text-white hover:text-sage transition">Checkout</a></li>
      </ul>
    </div>

    <!-- Connect / Social + Legal -->
    <div>
      <h3 class="font-body uppercase text-sm tracking-wider mb-4">Connect</h3>
      <div class="flex justify-center md:justify-start space-x-6 text-xl mb-6">
        <a href="https://facebook.com" target="_blank" rel="noopener" class="text-white hover:text-sage transition">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" rel="noopener" class="text-white hover:text-sage transition">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="mailto:hello@harvesthearth.com" class="text-white hover:text-sage transition">
         <i class="fas fa-envelope"></i>
        </a>

      </div>
      <ul class="space-y-2 text-xs font-body opacity-80">
        <li><a href="/privacy-policy" class="text-white hover:text-sage transition">Privacy Policy</a></li>
        <li><a href="/terms" class="text-white hover:text-sage transition">Terms of Service</a></li>
        <li><a href="/sitemap" class="text-white hover:text-sage transition">Sitemap</a></li>
      </ul>
    </div>

  </div>

  <!-- Bottom Bar -->
  <div class="border-t border-cream/20 mt-10 py-6 text-center text-xs font-body opacity-70">
    &copy; <?php echo date('Y'); ?> Harvest &amp; Hearth. All rights reserved.
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

