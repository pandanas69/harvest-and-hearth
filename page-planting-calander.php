<?php
/**
 * Template Name: Planting Calendar
 */

defined('ABSPATH') || exit;
get_header();

$json_path = get_theme_file_path('/planting_calendar.json');
$calendar  = file_exists($json_path) ? json_decode(file_get_contents($json_path), true) : [];

$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

// Default region
$region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : 'northern';
$region_colors = [
  'northern' => 'bg-sky-500',
  'middle'   => 'bg-amber-500',
  'southern' => 'bg-rose-500',
];
?>

<main id="site-content" class="max-w-7xl mx-auto px-6 py-12">
  <h1 class="font-heading text-4xl text-basil mb-8 text-center">Planting Calendar</h1>

  <!-- Region selector -->
  <form method="get" class="mb-6 text-center">
    <label for="region" class="mr-2 font-semibold">Region:</label>
    <select id="region" name="region" onchange="this.form.submit()" class="border rounded px-2 py-1">
      <option value="northern" <?php selected($region,'northern'); ?>>Northern</option>
      <option value="middle" <?php selected($region,'middle'); ?>>Middle</option>
      <option value="southern" <?php selected($region,'southern'); ?>>Southern</option>
    </select>
  </form>

  <?php if ($calendar): ?>
<div class="text-center mb-6">
  <button id="download-pdf" 
          class="bg-basil text-white px-4 py-2 rounded hover:bg-basil/90">
    Download as PDF
  </button>
</div>


    <div class="overflow-auto">
      <table class="min-w-full border border-gray-200 text-sm text-left">
        <thead class="bg-basil text-cream">
            <tr>
            <th class="px-4 py-2 font-semibold">Crop</th>
            <th class="px-4 py-2 font-semibold">Harvest</th>
            <?php foreach ($months as $month): ?>
              <th class="px-4 py-2 font-semibold text-center"><?php echo $month; ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($calendar as $crop): ?>
            <tr class="border-t border-gray-200">
              <td class="px-4 py-2 font-medium text-basil">
                <?php echo esc_html($crop['english']); ?>
                <span class="block text-xs text-gray-500"><?php echo esc_html($crop['maori']); ?></span>
              </td>
              <td class="px-4 py-2 text-terracotta font-semibold">
                <?php echo esc_html($crop['harvest_time']); ?>
              </td>
              <?php foreach ($crop['regions'][$region] as $active): ?>
                <td class="px-4 py-2 text-center">
                  <?php if ($active): ?>
                    <span class="inline-flex items-center justify-center <?php echo $region_colors[$region]; ?> text-white w-6 h-6 rounded-full">
                    <i class="fas fa-leaf"></i>
                    </span>
                <?php else: ?>
                    <span class="text-gray-300">–</span>
                <?php endif; ?>

                </td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-center text-gray-600">No planting calendar data found.</p>
  <?php endif; ?>
</main>


<?php get_footer(); ?>

