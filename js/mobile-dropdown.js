// Mobile Services Dropdown Toggle
(function() {
    var servicesToggle = document.getElementById('mobileServicesToggle');
    var servicesMenu = document.getElementById('mobileServicesMenu');
    if (servicesToggle && servicesMenu) {
        servicesToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (servicesMenu.style.display === 'block') {
                servicesMenu.style.display = 'none';
            } else {
                servicesMenu.style.display = 'block';
            }
        });
    }
})();
