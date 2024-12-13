document.addEventListener('DOMContentLoaded', function() {
    // Toggle Menu
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const secondaryNav = document.querySelector('.secondary-nav');
    const searchToggle = document.querySelector('.search-toggle');
    const navSearch = document.querySelector('.nav-search');

    // Menu Toggle Handler
    if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Toggle active class pada secondary nav dan nav menu
            if (secondaryNav) {
                secondaryNav.classList.toggle('active');
            }

            // // Log untuk debugging
            // console.log('Menu Toggle Clicked');
            // console.log('Secondary Nav Active:', secondaryNav?.classList.contains('active'));
            // console.log('Nav Menu Active:', navMenu?.classList.contains('active'));
        });
    }

    // Search Toggle Handler
    if (searchToggle && navSearch) {
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            this.classList.toggle('active');
            navSearch.classList.toggle('active');

            // Tutup menu saat search dibuka
            if (secondaryNav) {
                secondaryNav.classList.remove('active');
            }
            if (menuToggle) {
                menuToggle.classList.remove('active');
            }
            if (navMenu) {
                navMenu.classList.remove('active');
            }
        });
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        const isClickInsideNav = e.target.closest('.nav-wrapper') ||
                                e.target.closest('.secondary-nav');

        if (!isClickInsideNav) {
            if (navSearch) navSearch.classList.remove('active');
            if (searchToggle) searchToggle.classList.remove('active');
            if (secondaryNav) secondaryNav.classList.remove('active');
            if (menuToggle) menuToggle.classList.remove('active');
            if (navMenu) navMenu.classList.remove('active');
        }
    });

    // Prevent menu close when clicking inside
    if (secondaryNav) {
        secondaryNav.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});

// Smooth scroll untuk link anchor
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
