// Dynamically injects the Moveroo footer into an element with id="footer"
class MoverooFooter {
  constructor() {
    this.footerHtml = this.generateFooterHTML();
    this.initialize();
  }

  generateFooterHTML() {
    return `
    <footer class="footer-modern">
      <style>
        .footer-modern {
          background: #0c172e;
          border-top: 1.5px solid #232946;
          color: #e0e0e0;
          font-family: 'Montserrat', Arial, sans-serif;
          min-height: 120px;
          padding-top: 20px;
          padding-bottom: 45px;
          width: 100%;
        }
        .footer-modern .container {
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 15px;
        }
        .footer-modern .footer-main {
          display: flex;
          flex-wrap: wrap;
          gap: 36px;
          justify-content: space-between;
          padding: 44px 0 18px 0;
        }
        .footer-modern .footer-col {
          flex: 1 1 220px;
          min-width: 220px;
          max-width: 340px;
        }
        .footer-modern .footer-about h3 {
          color: #7be495;
          font-size: 1.3em;
          margin-bottom: 8px;
          font-weight: 700;
        }
        .footer-modern .footer-about p {
          color: #e0e0e0;
          font-size: 1.05em;
          margin-bottom: 14px;
          line-height: 1.5;
        }
        .footer-modern .social-links {
          display: flex;
          gap: 12px;
          margin-top: 15px;
        }
        .footer-modern .social-links a {
          color: #b0b8c1;
          font-size: 1.2em;
          transition: all 0.3s ease;
          width: 36px;
          height: 36px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          background: rgba(255, 255, 255, 0.05);
        }
        .footer-modern .social-links a:hover {
          color: #7be495;
          background: rgba(123, 228, 149, 0.1);
          transform: translateY(-3px);
        }
        .footer-modern .footer-links h4,
        .footer-modern .footer-newsletter h4 {
          color: #fff;
          font-size: 1.13em;
          margin-bottom: 15px;
          font-weight: 700;
          position: relative;
          padding-bottom: 8px;
        }
        .footer-modern .footer-links h4:after,
        .footer-modern .footer-newsletter h4:after {
          content: '';
          position: absolute;
          left: 0;
          bottom: 0;
          width: 40px;
          height: 2px;
          background: #7be495;
        }
        .footer-modern .footer-links ul {
          list-style: none;
          padding: 0;
          margin: 0;
        }
        .footer-modern .footer-links li {
          margin-bottom: 10px;
          transition: all 0.3s ease;
        }
        .footer-modern .footer-links li:hover {
          transform: translateX(5px);
        }
        .footer-modern .footer-links a {
          color: #b0b8c1;
          text-decoration: none;
          font-size: 1.04em;
          transition: color 0.3s;
          display: flex;
          align-items: center;
          gap: 8px;
        }
        .footer-modern .footer-links a:before {
          content: '→';
          color: #7be495;
          opacity: 0;
          transition: all 0.3s ease;
          font-size: 0.8em;
        }
        .footer-modern .footer-links a:hover:before {
          opacity: 1;
          transform: translateX(-5px);
        }
        .footer-modern .footer-links a:hover {
          color: #7be495;
        }
        .footer-modern .footer-newsletter p {
          color: #e0e0e0;
          font-size: 1.01em;
          margin-bottom: 15px;
          line-height: 1.6;
        }
        .footer-modern .newsletter-form {
          display: flex;
          gap: 8px;
          flex-direction: column;
        }
        .footer-modern .form-group {
          position: relative;
          margin-bottom: 10px;
        }
        .footer-modern .newsletter-form input[type="email"] {
          padding: 12px 15px;
          border: 1px solid #2d3b5a;
          background: #1a2238;
          color: #fff;
          border-radius: 6px;
          font-size: 0.95em;
          width: 100%;
          transition: all 0.3s ease;
        }
        .footer-modern .newsletter-form input[type="email"]:focus {
          outline: none;
          border-color: #7be495;
          box-shadow: 0 0 0 2px rgba(123, 228, 149, 0.2);
        }
        .footer-modern .newsletter-form button {
          background: #7be495;
          color: #181c24;
          border: none;
          border-radius: 6px;
          padding: 12px 20px;
          font-size: 0.95em;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          width: 100%;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }
        .footer-modern .newsletter-form button:hover {
          background: #6bd085;
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .footer-modern .newsletter-form button:active {
          transform: translateY(0);
        }
        .footer-modern .footer-bottom {
          border-top: 1px solid #2d3b5a;
          padding-top: 20px;
          margin-top: 20px;
          text-align: center;
          color: #b0b8c1;
          font-size: 0.9em;
        }
        .footer-modern .footer-bottom p {
          margin: 0;
        }
        .footer-modern .error-message {
          color: #ff6b6b;
          font-size: 0.85em;
          margin-top: 5px;
          display: none;
        }
        .footer-modern .success-message {
          color: #7be495;
          font-size: 0.9em;
          margin-top: 10px;
          display: none;
        }
        @media (max-width: 768px) {
          .footer-modern .footer-main {
            gap: 30px;
          }
          .footer-modern .footer-col {
            flex: 1 1 100%;
            max-width: 100%;
          }
          .footer-modern .newsletter-form {
            flex-direction: row;
          }
          .footer-modern .newsletter-form input[type="email"] {
            width: auto;
            flex: 1;
          }
          .footer-modern .newsletter-form button {
            width: auto;
          }
        }
        @media (max-width: 480px) {
          .footer-modern .newsletter-form {
            flex-direction: column;
          }
          .footer-modern .newsletter-form button {
            width: 100%;
          }
        }
      </style>
      <div class="container">
        <div class="footer-main">
          <div class="footer-col footer-about">
            <h3>Moveroo</h3>
            <p>Your trusted partner for seamless relocation services. We make moving easy, efficient, and stress-free.</p>
            <div class="social-links">
              <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
              <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
              <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
          </div>
          
          <div class="footer-col footer-links">
            <h4>Quick Links</h4>
            <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="about.html">About Us</a></li>
              <li><a href="services.html">Services</a></li>
              <li><a href="pricing.html">Pricing</a></li>
              <li><a href="blogs.html">Blogs</a></li>
              <li><a href="contact.html">Contact</a></li>
            </ul>
          </div>
          
          <div class="footer-col footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><a href="#">Local Moving</a></li>
              <li><a href="#">Long Distance</a></li>
              <li><a href="#">Packing Services</a></li>
              <li><a href="#">Storage Solutions</a></li>
              <li><a href="#">Commercial Moving</a></li>
            </ul>
          </div>
          
          <div class="footer-col footer-newsletter">
            <h4>Newsletter</h4>
            <p>Subscribe to our newsletter for the latest updates and offers.</p>
            <form id="newsletterForm" class="newsletter-form">
              <div class="form-group">
                <input type="email" placeholder="Your email address" required>
                <div class="error-message">Please enter a valid email address</div>
              </div>
              <button type="submit">Subscribe</button>
              <div class="success-message">Thank you for subscribing!</div>
            </form>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; ${new Date().getFullYear()} Moveroo. All rights reserved. | <a href="privacy.html" style="color: #7be495; text-decoration: none;">Privacy Policy</a> | <a href="terms.html" style="color: #7be495; text-decoration: none;">Terms of Service</a></p>
        </div>
      </div>
    </footer>`;
  }

  initialize() {
    // Add Font Awesome if not already loaded
    if (!document.querySelector('link[href*="font-awesome"]')) {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
      document.head.appendChild(link);
    }

    // Add Montserrat font if not already loaded
    if (!document.querySelector('link[href*="montserrat"]')) {
      const fontLink = document.createElement('link');
      fontLink.href = 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap';
      fontLink.rel = 'stylesheet';
      document.head.appendChild(fontLink);
    }

    // Insert footer into the DOM
    const footerElement = document.getElementById('footer');
    if (footerElement) {
      footerElement.innerHTML = this.footerHtml;
      this.attachEventListeners();
    } else {
      console.warn('Element with id "footer" not found. Footer not rendered.');
    }
  }

  attachEventListeners() {
    // Newsletter form submission
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
      newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleNewsletterSubmit(e.target);
      });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        if (targetId !== '#') {
          e.preventDefault();
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 100,
              behavior: 'smooth'
            });
          }
        }
      });
    });
  }

  handleNewsletterSubmit(form) {
    const emailInput = form.querySelector('input[type="email"]');
    const errorMessage = form.querySelector('.error-message');
    const successMessage = form.querySelector('.success-message');
    
    // Hide previous messages
    errorMessage.style.display = 'none';
    successMessage.style.display = 'none';
    
    // Validate email
    const email = emailInput.value.trim();
    if (!this.isValidEmail(email)) {
      errorMessage.style.display = 'block';
      emailInput.focus();
      return false;
    }
    
    // Here you would typically send the email to your server
    // For demo purposes, we'll just show a success message
    console.log('Subscribing email:', email);
    
    // Show success message and reset form
    successMessage.style.display = 'block';
    form.reset();
    
    // Hide success message after 5 seconds
    setTimeout(() => {
      successMessage.style.display = 'none';
    }, 5000);
    
    return true;
  }
  
  isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
  }
}

// Initialize the footer when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
  new MoverooFooter();
});
