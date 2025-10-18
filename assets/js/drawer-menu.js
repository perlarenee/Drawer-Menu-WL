/**
 * Drawer Menu WL Plugin JavaScript
 * Enhanced version with accessibility support and auto-close
 *
 * @package DrawerMenuWL
 * @since 1.0.0
 */
document.addEventListener("DOMContentLoaded", function () {

  const hamburgerInput = document.querySelector("#offcanvas-mobile-nav input.hamburger");
  const hamburgerLabel = document.querySelector("#offcanvas-mobile-nav label.hamburger");
  const drawerNav = document.querySelector("#offcanvas-mobile-nav");
  const drawerContent = document.querySelector("#offcanvas-mobile-nav .drawer-list");
  const drawerMenuNav = document.querySelector("#offcanvas-mobile-nav .drawer-menu-navigation");

  let focusableElements = [];
  let previousFocus = null;

  /**
   * Check if menu is open
   * @returns {boolean}
   */
  function isMenuOpen() {
    return hamburgerInput && hamburgerInput.checked;
  }

  /**
   * Get all focusable elements within the drawer
   * @returns {Array}
   */
  function getFocusableElements() {
    if (!drawerContent) return [];

    const selectors = [
      'a[href]:not([disabled])',
      'button:not([disabled])',
      'textarea:not([disabled])',
      'input[type="text"]:not([disabled])',
      'input[type="radio"]:not([disabled])',
      'input[type="checkbox"]:not([disabled])',
      'select:not([disabled])',
      '[tabindex]:not([tabindex="-1"]):not([disabled])'
    ];

    return Array.from(drawerContent.querySelectorAll(selectors.join(', ')));
  }

  /**
   * Update body class and ARIA attributes
   */
  function updateBodyClass() {
    const isOpen = isMenuOpen();

    if (isOpen) {
      document.body.classList.add('drawer-menu-open');

      // Store currently focused element
      previousFocus = document.activeElement;

      // Update ARIA attributes
      if (hamburgerLabel) {
        hamburgerLabel.setAttribute('aria-expanded', 'true');
      }

      // Get focusable elements and focus first one
      focusableElements = getFocusableElements();
      if (focusableElements.length > 0) {
        focusableElements[0].focus();
      }

      // Trap focus within drawer
      setupFocusTrap();

    } else {
      document.body.classList.remove('drawer-menu-open');

      // Update ARIA attributes
      if (hamburgerLabel) {
        hamburgerLabel.setAttribute('aria-expanded', 'false');
      }

      // Restore focus to previously focused element
      if (previousFocus && previousFocus.focus) {
        previousFocus.focus();
      }

      // Remove focus trap
      removeFocusTrap();
    }
  }

  /**
   * Set up focus trap for accessibility
   */
  function setupFocusTrap() {
    document.addEventListener('keydown', handleFocusTrap);
  }

  /**
   * Remove focus trap
   */
  function removeFocusTrap() {
    document.removeEventListener('keydown', handleFocusTrap);
  }

  /**
   * Handle focus trap - keep focus within drawer when open
   * @param {Event} e
   */
  function handleFocusTrap(e) {
    if (!isMenuOpen() || e.key !== 'Tab') return;

    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    if (e.shiftKey) {
      // Shift + Tab
      if (document.activeElement === firstFocusable) {
        lastFocusable.focus();
        e.preventDefault();
      }
    } else {
      // Tab
      if (document.activeElement === lastFocusable) {
        firstFocusable.focus();
        e.preventDefault();
      }
    }
  }

  /**
   * Toggle menu state
   */
  function toggleMenu() {
    if (hamburgerInput) {
      hamburgerInput.checked = !hamburgerInput.checked;
      updateBodyClass();
    }
  }

  /**
   * Close menu
   */
  function closeMenu() {
    if (hamburgerInput) {
      hamburgerInput.checked = false;
      updateBodyClass();
    }
  }

  // Allow any element with class 'drawer-menu-trigger' to open the menu
  document.querySelectorAll('.drawer-menu-trigger').forEach(function(trigger) {
    trigger.addEventListener('click', function(e) {
      e.preventDefault();
      toggleMenu();
    });

    // Add keyboard support for custom triggers
    trigger.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        toggleMenu();
      }
    });
  });

  // Handle hamburger label keyboard interaction
  if (hamburgerLabel) {
    hamburgerLabel.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        toggleMenu();
      }
    });
  }

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
      closeMenu();
    }
  });

  // Close menu on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && isMenuOpen()) {
      closeMenu();
    }
  });

  // Close menu when clicking on links inside the drawer menu navigation
  document.addEventListener('click', function(e) {
    // Check if menu is open first
    if (!isMenuOpen()) return;
    
    // Check if the clicked element is a link or inside a link
    const link = e.target.closest('a');
    
    // Only proceed if it's a link and it's inside the drawer navigation
    if (link && drawerNav && drawerNav.contains(link)) {
      const href = link.getAttribute('href');
      
      console.log('Drawer link clicked:', href); // Debug log
      
      // Close menu for all links except hash-only links
      if (href && href !== '#' && !href.startsWith('javascript:')) {
        // Small delay to ensure click registers
        setTimeout(function() {
          console.log('Closing menu...'); // Debug log
          closeMenu();
        }, 300);
      }
    }
  }, true); // Use capture phase

});