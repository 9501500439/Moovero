// quote-modal.js - Universal handler for all .quote-btn buttons
// Usage: Include this script on any page with a modal (id="quote-modal") and .quote-btn buttons

// Event delegation for all .quote-btn (works for dynamic and static buttons)
document.addEventListener('click', function(e) {
  var target = e.target;
  // Traverse up if the click is on a child (e.g. span inside button)
  while (target && target !== document) {
    if (target.classList && target.classList.contains('quote-btn')) {
      e.preventDefault();
      var modal = document.getElementById('quote-modal');
      if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
      return;
    }
    target = target.parentNode;
  }
});
// Close modal logic
document.addEventListener('DOMContentLoaded', function() {
  var closeBtn = document.getElementById('quote-modal-close');
  if (closeBtn) closeBtn.onclick = closeQuoteModal;
  var modal = document.getElementById('quote-modal');
  if (modal) {
    modal.onclick = function(e) {
      if (e.target === modal) closeQuoteModal();
    };
  }
});
function closeQuoteModal() {
  var modal = document.getElementById('quote-modal');
  if (modal) {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
}
