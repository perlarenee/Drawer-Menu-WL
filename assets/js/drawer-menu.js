// Drawer Menu WL Plugin JavaScript - ULTRA SIMPLIFIED VERSION
document.addEventListener("DOMContentLoaded", function () {

  const hamburgerInput = document.querySelector("#offcanvas-mobile-nav input.hamburger");

  function isMenuOpen() {
    return hamburgerInput && hamburgerInput.checked;
  }
  
  function updateBodyClass() {
    if (isMenuOpen()) {
      document.body.classList.add('drawer-menu-open');
    } else {
      document.body.classList.remove('drawer-menu-open');
    }
  }

  // Allow any element with class 'drawer-menu-trigger' to open the menu
  document.querySelectorAll('.drawer-menu-trigger').forEach(function(trigger) {
    trigger.addEventListener('click', function(e) {
      e.preventDefault();
      if (hamburgerInput) {
        hamburgerInput.checked = !hamburgerInput.checked;
        updateBodyClass();
      }
    });
  });
  
  // Update body class on checkbox change
  if (hamburgerInput) {
    hamburgerInput.addEventListener('change', updateBodyClass);
  }

  // Close menu when clicking outside
  document.addEventListener('click', function(e) {
    if (isMenuOpen() && 
        !e.target.closest('#offcanvas-mobile-nav') && 
        !e.target.matches('#offcanvas-mobile-nav') &&
        !e.target.closest('.drawer-menu-trigger')) {
      hamburgerInput.checked = false;
      updateBodyClass();
    }
  });

  // Close menu on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && isMenuOpen()) {
      hamburgerInput.checked = false;
      updateBodyClass();
    }
  });

});