document.addEventListener('DOMContentLoaded', () => {
  const toggle   = document.querySelector('#nav-toggle');
  const drawer   = document.querySelector('#mobile-nav');
  const backdrop = document.querySelector('#nav-backdrop');
  if (!toggle || !drawer || !backdrop) return;

  // Drawer open/close
  const openMenu = () => {
    drawer.classList.remove('translate-x-full');
    backdrop.classList.remove('hidden');
    toggle.setAttribute('aria-expanded', 'true');
    toggle.classList.add('open');
  };
  const closeMenu = () => {
    drawer.classList.add('translate-x-full');
    backdrop.classList.add('hidden');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.classList.remove('open');
  };
  toggle.addEventListener('click', () => {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    isOpen ? closeMenu() : openMenu();
  });
  backdrop.addEventListener('click', closeMenu);

  // Submenu accordion (CSS-driven)
  const isMobile = () => window.innerWidth < 768;

  function initMobileDrawer() {
    // Remove any inline styles so CSS can take over
    drawer.querySelectorAll('.menu-item-has-children > .sub-menu').forEach(ul => {
      ul.style.removeProperty('overflow');
      ul.style.removeProperty('max-height');
      ul.style.removeProperty('transition');
    });

    // Reset caret handlers
    drawer.querySelectorAll('.submenu-toggle').forEach(btn => {
      const clone = btn.cloneNode(true);
      btn.parentNode.replaceChild(clone, btn);
    });

    if (!isMobile()) return;

    // Bind caret buttons
    drawer.querySelectorAll('.submenu-toggle').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        const li = btn.closest('.menu-item-has-children');
        if (!li) return;

        const isOpen = li.classList.contains('open');

        // Close siblings
        const siblings = li.parentElement?.querySelectorAll('.menu-item-has-children.open') || [];
        siblings.forEach(sib => {
          if (sib !== li) {
            sib.classList.remove('open');
            const sibBtn = sib.querySelector('.submenu-toggle');
            if (sibBtn) sibBtn.setAttribute('aria-expanded', 'false');
            const icon = sibBtn?.querySelector('svg');
            if (icon) icon.style.transform = 'rotate(0deg)';
          }
        });

        // Toggle this one (CSS handles height/overflow)
        if (!isOpen) {
          li.classList.add('open');
          btn.setAttribute('aria-expanded', 'true');
          const icon = btn.querySelector('svg');
          if (icon) icon.style.transform = 'rotate(90deg)';
        } else {
          li.classList.remove('open');
          btn.setAttribute('aria-expanded', 'false');
          const icon = btn.querySelector('svg');
          if (icon) icon.style.transform = 'rotate(0deg)';
        }
      });
    });
  }

  initMobileDrawer();
  window.addEventListener('resize', initMobileDrawer);
});

