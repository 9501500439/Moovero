// Function to show only the selected process section
function showProcessSection(sectionId) {
    // Define all section and value-added block IDs
    const sections = ['packing-moving', 'pet-relocation', 'vehicle-relocation', 'packing-labour'];
    const valueAddedBlocks = ['value-added-packing', 'value-added-pet', 'value-added-vehicle', 'value-added-labour'];
    
    // First hide all sections
    sections.forEach(id => {
        const section = document.getElementById(id);
        if (section) section.style.display = 'none';
    });
    
    // Hide all value-added sections
    valueAddedBlocks.forEach(id => {
        const block = document.getElementById(id);
        if (block) block.style.display = 'none';
    });
    
    // Show the selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
        
        // Show the corresponding value-added block based on the selected section
        const valueAddedId = getValueAddedBlockId(sectionId);
        if (valueAddedId) {
            const valueAddedBlock = document.getElementById(valueAddedId);
            if (valueAddedBlock) {
                valueAddedBlock.style.display = 'block';
            }
        }
        
        // Smooth scroll to the selected section
        setTimeout(() => {
            selectedSection.scrollIntoView({ behavior: 'smooth' });
        }, 50);
    }
}

// Helper function to get the corresponding value-added block ID for a section
function getValueAddedBlockId(sectionId) {
    const sectionToBlockMap = {
        'packing-moving': 'value-added-packing',
        'pet-relocation': 'value-added-pet',
        'vehicle-relocation': 'value-added-vehicle',
        'packing-labour': 'value-added-labour'
    };
    return sectionToBlockMap[sectionId] || null;
}

// Initialize by showing the first section by default
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu functionality
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileNav = document.getElementById('mobileNav');
    const mobileServicesToggle = document.getElementById('mobileServicesToggle');
    const mobileServicesMenu = document.getElementById('mobileServicesMenu');

    // Toggle mobile menu
    function toggleMobileMenu() {
        mobileNav.classList.toggle('active');
        document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
        
        // Toggle between hamburger and close icon
        const icon = mobileMenuBtn.querySelector('i');
        if (mobileNav.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    }

    // Toggle services dropdown in mobile menu
    if (mobileServicesToggle) {
        mobileServicesToggle.addEventListener('click', function(e) {
            e.preventDefault();
            mobileServicesMenu.style.display = mobileServicesMenu.style.display === 'block' ? 'none' : 'block';
        });
    }

    // Event listeners for mobile menu
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    }
    
    if (closeMobileMenu) {
        closeMobileMenu.addEventListener('click', toggleMobileMenu);
    }

    // Close menu when clicking on nav links
    document.querySelectorAll('.mobile-nav a:not(.dropdown-toggle)').forEach(link => {
        link.addEventListener('click', function() {
            if (mobileNav.classList.contains('active')) {
                toggleMobileMenu();
            }
        });
    });

    // Initialize by showing the first section by default
    const sections = ['packing-moving', 'pet-relocation', 'vehicle-relocation', 'packing-labour'];
    sections.forEach((id, index) => {
        const section = document.getElementById(id);
        if (section) section.style.display = index === 0 ? 'block' : 'none';
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 100, // Adjust for fixed header
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});
