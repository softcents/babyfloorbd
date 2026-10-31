(function () {
    'use strict';

    document.querySelectorAll('.elementor-menu-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            var dropdown = toggle.parentElement.querySelector('.elementor-nav-menu--dropdown');
            if (dropdown) {
                dropdown.setAttribute('aria-hidden', expanded ? 'true' : 'false');
                dropdown.classList.toggle('elementor-nav-menu--dropdown-open', !expanded);
            }
        });
    });
})();
