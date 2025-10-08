/*!
    * Start Bootstrap - Dispatch v2.0.5 (https://shop.startbootstrap.com/product/sb-admin-pro)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under SEE_LICENSE (https://github.com/StartBootstrap/sb-admin-pro/blob/master/LICENSE)
    */
    function initSidebarToggle() {
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        console.log('Sidebar toggle handler attached');
        sidebarToggle.onclick = function(event) {
            event.preventDefault();
            document.body.classList.toggle('sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sidenav-toggled'));
            console.log('Sidebar toggled. sidenav-toggled:', document.body.classList.contains('sidenav-toggled'));
        };
    } else {
        console.log('Sidebar toggle button NOT found');
    }
}

window.addEventListener('DOMContentLoaded', event => {
    // Activate feather
    feather.replace();

    // Enable tooltips globally
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Enable popovers globally
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Dynamically load sidebar and topnav HTML
    fetch('sidebar.html')
        .then(response => response.text())
        .then(html => {
            const sidebarNav = document.getElementById('layoutSidenav_nav');
            if (sidebarNav) sidebarNav.innerHTML = html;
            initSidebarToggle(); // Re-initialize toggle after loading
        });
    fetch('topnav.html')
        .then(response => response.text())
        .then(html => {
            const topnav = document.getElementById('topnav');
            if (topnav) topnav.innerHTML = html;
        });

    // Close side navigation when width < LG
    const sidenavContent = document.body.querySelector('#layoutSidenav_content');
    if (sidenavContent) {
        sidenavContent.addEventListener('click', event => {
            const BOOTSTRAP_LG_WIDTH = 992;
            if (window.innerWidth >= 992) {
                return;
            }
            if (document.body.classList.contains("sidenav-toggled")) {
                document.body.classList.toggle("sidenav-toggled");
            }
        });
    }

    // Add active state to sidbar nav links
    let activatedPath = window.location.pathname.match(/([\w-]+\.html)/, '$1');

    if (activatedPath) {
        activatedPath = activatedPath[0];
    } else {
        activatedPath = 'index.php';
    }

    const targetAnchors = document.body.querySelectorAll('[href="' + activatedPath + '"].nav-link');

    targetAnchors.forEach(targetAnchor => {
        let parentNode = targetAnchor.parentNode;
        while (parentNode !== null && parentNode !== document.documentElement) {
            if (parentNode.classList.contains('collapse')) {
                parentNode.classList.add('show');
                const parentNavLink = document.body.querySelector(
                    '[data-bs-target="#' + parentNode.id + '"]'
                );
                parentNavLink.classList.remove('collapsed');
                parentNavLink.classList.add('active');
            }
            parentNode = parentNode.parentNode;
        }
        targetAnchor.classList.add('active');
    });
});

// Optional: Log out on tab/browser close for stricter session security
window.addEventListener('unload', function() {
    if (navigator.sendBeacon) {
        navigator.sendBeacon('logout.php');
    } else {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'logout.php', false);
        xhr.send();
    }
});

// Common email pattern (RFC 5322 simplified)
const EMAIL_PATTERN = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

// Validate all email fields with class 'email-pattern' in a form
function validateEmailFields(form) {
    let valid = true;
    const emailFields = form.querySelectorAll('.email-pattern');
    emailFields.forEach(function(field) {
        field.classList.remove('field-error');
        if (field.value && !EMAIL_PATTERN.test(field.value)) {
            field.classList.add('field-error');
            valid = false;
        }
    });
    return valid;
}
