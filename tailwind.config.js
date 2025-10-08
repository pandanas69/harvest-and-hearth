// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./**/*.php",
    "./patterns/**/*.php",
    "./assets/js/**/*.js"
  ],
  safelist: [
    'text-sage',
    'text-terracotta',
    'text-cream',
    'text-charcoal',
    'text-basil',
    'menu-item-has-children',
    'sub-menu',
    'menu-item-265',
    'menu-item-recipes'
  ],
  theme: {
    extend: {
      colors: {
        sage: '#9CAF88',
        basil: '#4E6E4E',
        cream: '#F5F2E7',
        terracotta: '#C97D60',
        charcoal: '#333333',
        // social brand colors if you want to keep them
        facebook: '#1877F2',
        instagram: '#E4405F',
        x: '#000000',
        linkedin: '#0A66C2',
        pinterest: '#BD081C',
        youtube: '#FF0000',
        whatsapp: '#25D366',
        tiktok: '#000000',
        github: '#181717',
        wordpress: '#21759B',
        google: '#4285F4',
        dribbble: '#EA4C89',
        behance: '#1769FF',
        spotify: '#1DB954',
        slack: '#4A154B',
      },
      fontFamily: {
        heading: ['PlayfairDisplayVariable', 'serif'],
      body: ['InterVariable', 'sans-serif'],
      },
    }
  },
  plugins: [
    require('@tailwindcss/typography'),
    function ({ addBase }) {
      addBase({
        '.fa, [class^="fa-"], [class*=" fa-"]': {
          backgroundColor: 'transparent',
          lineHeight: '1',
          verticalAlign: 'middle',
          color: 'inherit',
        },
      });
    }
  ],
};

