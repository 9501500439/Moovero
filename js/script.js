document.addEventListener('DOMContentLoaded', function() {
    // Collapsible Sections with smooth animations
    const toggleButtons = document.querySelectorAll('.toggle-section-btn');
    
    // Initialize collapsible sections
    function initCollapsibleSections() {
        toggleButtons.forEach(button => {
            const targetId = button.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const icon = button.querySelector('i');
            
            // Set initial state
            content.style.maxHeight = '0';
            content.style.overflow = 'hidden';
            content.style.transition = 'max-height 0.3s ease-out, opacity 0.3s ease';
            content.style.opacity = '0';
            content.style.visibility = 'hidden';
            
            // Add click event
            button.addEventListener('click', function() {
                const isExpanding = content.style.maxHeight === '0px' || !content.style.maxHeight;
                
                // Toggle the active class
                if (isExpanding) {
                    // Close other open sections
                    toggleButtons.forEach(btn => {
                        if (btn !== button) {
                            const otherTargetId = btn.getAttribute('data-target');
                            const otherContent = document.getElementById(otherTargetId);
                            const otherIcon = btn.querySelector('i');
                            
                            otherContent.style.maxHeight = '0';
                            otherContent.style.opacity = '0';
                            otherContent.style.visibility = 'hidden';
                            otherIcon.style.transform = 'rotate(0deg)';
                            btn.style.borderBottomLeftRadius = '10px';
                            btn.style.borderBottomRightRadius = '10px';
                            btn.setAttribute('aria-expanded', 'false');
                        }
                    });
                    
                    // Expand the clicked section
                    content.style.maxHeight = content.scrollHeight + 'px';
                    content.style.opacity = '1';
                    content.style.visibility = 'visible';
                    icon.style.transform = 'rotate(180deg)';
                    button.style.borderBottomLeftRadius = '0';
                    button.style.borderBottomRightRadius = '0';
                    button.setAttribute('aria-expanded', 'true');
                } else {
                    // Collapse the clicked section
                    content.style.maxHeight = '0';
                    content.style.opacity = '0';
                    content.style.visibility = 'hidden';
                    icon.style.transform = 'rotate(0deg)';
                    button.style.borderBottomLeftRadius = '10px';
                    button.style.borderBottomRightRadius = '10px';
                    button.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Handle keyboard navigation
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    button.click();
                }
            });
        });
    }
    
    // Initialize the collapsible sections
    initCollapsibleSections();
    
    // Mobile Navigation Toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    const menuOverlay = document.querySelector('.menu-overlay');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const body = document.body;
    let isMenuOpen = false;

    // Toggle mobile menu
    function toggleMenu() {
        isMenuOpen = !isMenuOpen;
        hamburger.classList.toggle('active');
        
        if (navLinks) {
            if (isMenuOpen) {
                navLinks.style.display = 'flex';
                // Force reflow
                navLinks.offsetHeight;
                navLinks.classList.add('active');
                navLinks.style.maxHeight = navLinks.scrollHeight + 'px';
                navLinks.style.opacity = '1';
            } else {
                navLinks.style.maxHeight = '0';
                navLinks.style.opacity = '0';
                navLinks.style.padding = '0';
                // After transition ends, hide the menu
                setTimeout(() => {
                    if (!isMenuOpen) {
                        navLinks.classList.remove('active');
                        navLinks.style.display = 'none';
                        navLinks.style.padding = '20px 0';
                    }
                }, 300);
            }
        }
        
        if (menuOverlay) {
            menuOverlay.style.display = isMenuOpen ? 'block' : 'none';
            setTimeout(() => {
                menuOverlay.classList.toggle('active', isMenuOpen);
            }, 10);
        }
        
        body.style.overflow = isMenuOpen ? 'hidden' : '';
        body.classList.toggle('menu-open', isMenuOpen);
    }

    // Toggle dropdown menu on mobile
    function toggleDropdown(e) {
        e.preventDefault();
        const parent = this.parentElement;
        const dropdown = parent.querySelector('.dropdown-menu');
        
        // Close other open dropdowns
        document.querySelectorAll('.dropdown').forEach(item => {
            if (item !== parent) {
                item.classList.remove('active');
            }
        });
        
        // Toggle current dropdown
        parent.classList.toggle('active');
    }

    // Add click event for dropdown toggles
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', toggleDropdown);
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.navbar') && !e.target.closest('.nav-links')) {
            if (window.innerWidth <= 992) {
                hamburger.classList.remove('active');
                if (navLinks) navLinks.classList.remove('active');
                if (menuOverlay) menuOverlay.classList.remove('active');
                body.style.overflow = '';
                body.classList.remove('menu-open');
                isMenuOpen = false;
            }
        }
    });

    // Toggle menu when clicking hamburger
    hamburger.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleMenu();
    });

    // Close menu when clicking overlay
    overlay.addEventListener('click', function() {
        if (navLinks.classList.contains('active')) {
            toggleMenu();
        }
    });

    // Close menu when clicking on a nav link
    navLinksItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't close if clicking on a dropdown toggle
            if (this.querySelector('.dropdown-toggle')) {
                e.preventDefault();
                this.classList.toggle('active');
                return;
            }
            
            // Close menu for regular links
            if (navLinks.classList.contains('active')) {
                toggleMenu();
            }
            
            // Smooth scroll to section
            const href = this.querySelector('a').getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
    
    // Close menu when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && navLinks.classList.contains('active')) {
            toggleMenu();
        }
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 992) {
                // Reset mobile menu on desktop
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
                overlay.classList.remove('active');
                body.style.overflow = '';
                body.classList.remove('menu-open');
            }
        }, 250);
    });

    // Header with consistent semi-transparent background
    const header = document.querySelector('.header');
    // Ensure header has the correct class
    header.classList.add('scrolled');

    // Smooth Scrolling for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // City Search Functionality
    const citySearchForm = document.getElementById('city-search-form');
    const citySearchInput = document.getElementById('city-search');
    const cityResultPopup = document.getElementById('city-result-popup');
    const closePopup = document.querySelector('.close-popup');
    const searchedCitySpan = document.getElementById('searched-city');
    const cityTags = document.querySelectorAll('.city-tag');

    // List of available cities (can be expanded)
    const availableCities = [
        'delhi', 'new delhi', 'mumbai', 'bangalore', 'hyderabad', 'chennai',
        'kolkata', 'pune', 'ahmedabad', 'jaipur', 'surat', 'lucknow', 'kanpur',
        'nagpur', 'indore', 'thane', 'bhopal', 'visakhapatnam', 'patna', 'vadodara'
    ];

    // Handle city search form submission
    if (citySearchForm) {
        citySearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const city = citySearchInput.value.trim();
            if (city) {
                checkCityAvailability(city);
            }
        });
    }

    // Handle city tag clicks
    cityTags.forEach(tag => {
        tag.addEventListener('click', function(e) {
            e.preventDefault();
            const city = this.getAttribute('data-city');
            citySearchInput.value = city;
            checkCityAvailability(city);
        });
    });

    // Check if city is available
    function checkCityAvailability(city) {
        const cityLower = city.toLowerCase();
        const isAvailable = availableCities.some(availableCity => 
            availableCity === cityLower
        );
        
        showCityResult(city, isAvailable);
    }

    // Show city search result popup
    function showCityResult(city, isAvailable) {
        const popup = document.getElementById('city-result-popup');
        const popupBody = popup.querySelector('.popup-body');
        
        searchedCitySpan.textContent = city;
        
        if (isAvailable) {
            popupBody.innerHTML = `
                <i class="fas fa-check-circle success-icon"></i>
                <h3>Great News!</h3>
                <p>We're available in <span id="searched-city">${city}</span>!</p>
                <p>Our team is ready to assist you with your moving needs.</p>
                <a href="#contact" class="btn btn-primary">Get Quote</a>
            `;
        } else {
            popupBody.innerHTML = `
                <i class="fas fa-info-circle" style="color: #ff9800; font-size: 60px; margin-bottom: 20px;"></i>
                <h3>Coming Soon!</h3>
                <p>We're not in <span id="searched-city" style="font-weight: 700;">${city}</span> yet, but we're expanding!</p>
                <p>Enter your email below to be notified when we arrive in your city.</p>
                <form class="notify-form" style="margin-top: 20px;">
                    <div style="display: flex; gap: 10px;">
                        <input type="email" placeholder="Your email address" required 
                               style="flex: 1; padding: 12px 15px; border: 1px solid #ddd; border-radius: 50px; outline: none;">
                        <button type="submit" class="btn" style="background: #000; color: white; border: none; border-radius: 50px; padding: 0 25px; cursor: pointer; font-weight: 600;">Notify Me</button>
                    </div>
                </form>
            `;
            
            // Add submit handler for the notify form
            const notifyForm = popupBody.querySelector('.notify-form');
            if (notifyForm) {
                notifyForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const email = this.querySelector('input[type="email"]').value;
                    // Here you would typically send this to your server
                    alert(`Thanks! We'll notify you at ${email} when we're in ${city}.`);
                    closeCityPopup();
                });
            }
        }
        
        // Show the popup
        popup.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close popup when clicking the close button
    if (closePopup) {
        closePopup.addEventListener('click', closeCityPopup);
    }

    // Close popup when clicking outside the content
    if (cityResultPopup) {
        cityResultPopup.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCityPopup();
            }
        });
    }

    // Close popup with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && cityResultPopup.classList.contains('active')) {
            closeCityPopup();
        }
    });

    function closeCityPopup() {
        cityResultPopup.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Newsletter Form Submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            
            if (emailInput.value) {
                // Here you would typically send the email to your server
                console.log('Newsletter subscription:', emailInput.value);
                
                // Show success message
                alert('Thank you for subscribing to our newsletter!');
                emailInput.value = '';
            }
        });
    }

    // Add active class to current section in navigation
    const sections = document.querySelectorAll('section');
    
    function highlightNav() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (window.scrollY >= (sectionTop - 100)) {
                current = section.getAttribute('id');
            }
        });
        
        navLinksItems.forEach(li => {
            li.querySelector('a').classList.remove('active');
            if (li.querySelector(`a[href*="${current}"]`)) {
                li.querySelector('a').classList.add('active');
            }
        });
    }
    
    window.addEventListener('scroll', highlightNav);
    
    // Initialize AOS (Animate On Scroll) if you want to add animations
    // AOS.init({
    //   duration: 1000,
    //   once: true
    // });
    
    // Add loading animation for better user experience
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');
    });
});

// Add loading animation styles
const loadingStyles = document.createElement('style');
loadingStyles.textContent = `
    /* Loading Animation */
    .loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--white);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s, visibility 0.5s;
    }
    
    .loading.fade-out {
        opacity: 0;
        visibility: hidden;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;

document.head.appendChild(loadingStyles);

// Add loading element to the page
const loadingElement = document.createElement('div');
loadingElement.className = 'loading';
loadingElement.innerHTML = '<div class="spinner"></div>';
document.body.prepend(loadingElement);

// Remove loading element when page is fully loaded
window.addEventListener('load', function() {
    loadingElement.classList.add('fade-out');
    setTimeout(() => {
        loadingElement.remove();
    }, 500);
});
