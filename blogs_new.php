<?php
// This is a PHP version of the blogs page that fetches from the database
require_once 'admin/config/db_connect.php';

// Check if a category filter is applied
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Get all published blog posts from the database
try {
    if (!empty($category_filter)) {
        // Filter by category if one is selected
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE status = 'published' AND category = ? ORDER BY created_at DESC");
        $stmt->execute([$category_filter]);
    } else {
        // Get all posts if no category filter
        $stmt = $pdo->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC");
    }
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get all categories from the blog_categories table
    $stmt_categories = $pdo->query("SELECT name FROM blog_categories ORDER BY name ASC");
    $categories = $stmt_categories->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $posts = [];
    $categories = [];
    $error = "Error loading blog posts: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | Moveroo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Base */
        :root {
            --brand-dark: #0c172e;
            --brand-primary: #1a4b8c;
            --brand-accent: #C71585;
            --brand-green: #28a745;
            --text-dark: #232946;
            --text-mid: #444;
            --text-light: #666;
            --border: #e9edf3;
            --chip-bg: #f4f6fb;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Montserrat', sans-serif; color: var(--text-dark); background: #fff; }
        
        /* Logo Styling */
        .logo img {
            max-height: 70px;
            min-height: 50px;
            width: auto;
            border-radius: 0;
            transition: all 0.3s ease;
            padding: 6px 0;
        }
        .logo {
            min-height: 40px;
            padding: 2px 0;
            display: flex;
            align-items: center;
            padding: 0px 0;
        }
        /* Header and Navbar Styles */
        .header, .navbar {
            min-height: 10px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }
        .header {
            min-height: 80px !important;
            padding: 15px 0 !important;
            position: fixed;
            background: #000 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            width: 100%;
        }
        /* Mobile menu toggle button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 28px;
            color: #ffffff;
            cursor: pointer;
            padding: 8px;
            margin-left: auto;
            z-index: 1002;
            transition: color 0.3s ease;
        }
        .mobile-menu-btn:hover {
            color: #cccccc;
        }
        /* Mobile menu styles */
        .mobile-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 320px;
            height: 100vh;
            background: rgb(211, 208, 208);
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            z-index: 1001;
            padding: 70px 20px 30px;
            transition: right 0.3s ease;
            overflow-y: auto;
        }
        .mobile-nav.active {
            right: 0;
        }
        .mobile-nav .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            color: #333;
        }
        .mobile-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .mobile-nav ul li {
            margin: 10px 0;
        }
        .mobile-nav ul li a {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .mobile-nav ul li a:hover {
            background: #f5f5f5;
        }
        /* Hide desktop nav on mobile */
        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }
            .mobile-menu-btn {
                display: block;
            }
            .logo {
                margin-right: auto !important;
            }
        }
        /* Desktop styles */
        @media (min-width: 993px) {
            .mobile-menu-btn,
            .mobile-nav {
                display: none;
            }
            .nav-links {
                display: flex;
            }
        }
        
        @media (max-width: 600px) {
          .navbar {
            justify-content: flex-end !important;
          }
          .logo {
            margin-right: auto !important;
          }
        }
        
        /* Hero Section */
        .blog-hero { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 180px 0 60px; text-align: center; margin-top: 30px; }
        .blog-hero h1 { font-size: 2.5rem; margin: 0 0 15px; color: var(--brand-primary); }
        .blog-hero p { font-size: 1.1rem; color: var(--text-mid); margin: 0 0 10px; }
        
        /* Blog Controls */
        .blog-controls { padding: 30px 0; background: white; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .controls-wrap { display: flex; justify-content: space-between; align-items: center; }
        .filters-row { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .filter-title { font-weight: 600; margin-right: 10px; color: var(--text-dark); }
        .search-wrap { display: flex; align-items: center; }
        .search-wrap input { 
            padding: 8px 12px; 
            border-radius: 20px 0 0 20px; 
            border: 1px solid var(--border);
            width: 200px;
        }
        .search-wrap button {
            background: var(--brand-primary);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
        }
        .chip, .pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 20px;
            background: var(--chip-bg);
            border: 1px solid var(--border);
            color: var(--text-dark);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .chip:hover, .pill:hover {
            background: #e6f0ff;
            border-color: var(--brand-primary);
        }
        .chip.active, .pill.active {
            background: var(--brand-primary);
            color: white;
            border-color: var(--brand-primary);
        }
        
        /* Posts Grid */
        .posts-wrap { padding: 40px 0; }
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .post-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            width: 100%;
        }
        
        .post-image {
            width: 100%;
            height: 250px;
            overflow: hidden;
        }
        
        .post-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .post-card:hover .post-image img {
            transform: scale(1.05);
        }
        
        .post-content {
            padding: 20px;
        }
        
        .post-title {
            margin: 0 0 10px;
            font-size: 1.25rem;
            line-height: 1.4;
        }
        
        .post-title a {
            color: var(--text-dark);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .post-title a:hover {
            color: var(--brand-primary);
        }
        
        .post-excerpt {
            color: var(--text-mid);
            margin: 0 0 15px;
            line-height: 1.6;
        }
        
        .post-meta {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
            display: flex;
            gap: 15px;
        }
        
        .post-author {
            color: var(--brand-primary);
            font-weight: 500;
        }
        
        .post-category {
            display: inline-flex;
            align-items: center;
            background-color: var(--chip-bg);
            color: var(--brand-primary);
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85em;
        }
        
        .post-category i {
            margin-right: 4px;
            font-size: 0.9em;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }
        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 15px;
        }
        .empty-state p {
            font-size: 1.1rem;
            color: var(--text-mid);
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .posts-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .mobile-menu-btn { display: block; }
            .nav-links { display: none; }
            .blog-hero { padding: 140px 0 40px; margin-top: 40px; }
            .blog-hero h1 { font-size: 2rem; }
            .controls-wrap { flex-direction: column; align-items: flex-start; gap: 20px; }
            .filters-row { 
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                width: 100%; 
                gap: 8px; 
            }
            .filter-title { 
                width: 100%;
                margin-bottom: 10px; 
                text-align: center;
                font-size: 1.1rem;
            }
            .pill {
                flex: 0 0 auto;
                padding: 8px 16px;
                margin: 0 4px 8px;
            }
            .search-wrap {
                width: 100%;
                margin-top: 15px;
                display: flex;
                justify-content: center;
            }
            .search-wrap input {
                width: 70%;
                max-width: 300px;
            }
        }
        @media (max-width: 576px) {
            .posts-grid { grid-template-columns: 1fr; }
            .blog-hero { padding: 120px 0 35px; margin-top: 50px; }
            .blog-hero h1 { font-size: 1.75rem; }
            .pill {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
            .search-wrap input {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <!-- Logo -->
                <div class="logo">
                    <a href="index.html">
                        <img src="assets/1.png" alt="Moveroo Logo" class="logo-img">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <ul class="nav-links">
                    <li><a href="index.html#home">Home</a></li>
                    <!-- Services Dropdown -->
                    <li class="dropdown">
                        <a href="#services" class="dropdown-toggle" onclick="event.preventDefault(); document.querySelector('#services').scrollIntoView({ behavior: 'smooth' });">
                            Services <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="packing-moving.html">Packing & Moving</a></li>
                            <li><a href="vehicle.html">Vehicle Relocation</a></li>
                            <li><a href="pet.html">Pet Relocation</a></li>
                            <li><a href="packing-labour.html">Packing Labour</a></li>
                        </ul>
                    </li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="process.html">Process</a></li>
                    <li><a href="blogs_new.php" class="active">Blogs</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <!-- Get Quote Button -->
                    <li>
                        <a href="#quote" class="quote-btn">
                            Get a Quote
                        </a>
                    </li>
                </ul>

                <!-- Mobile Menu Toggle Button -->
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>
    
    <!-- Mobile Navigation Menu -->
    <nav class="mobile-nav" id="mobileNav">
        <button class="close-btn" id="closeMobileMenu">
            <i class="fas fa-times"></i>
        </button>
        <ul>
            <li><a href="index.html#home">Home</a></li>
            <li class="dropdown mobile-dropdown">
                <a href="#services" class="dropdown-toggle" id="mobileServicesToggle">
                    Services <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="dropdown-menu" id="mobileServicesMenu" style="display: none;">
                    <li><a href="packing-moving.html">Packing & Moving</a></li>
                    <li><a href="vehicle.html">Vehicle Relocation</a></li>
                    <li><a href="pet.html">Pet Relocation</a></li>
                    <li><a href="packing-labour.html">Packing Labour</a></li>
                </ul>
            </li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="process.html">Process</a></li>
            <li><a href="blogs_new.php" class="active">Blogs</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="blog-hero">
        <div class="container">
            <h1>Moveroo Blog</h1>
            <p>Insights, tips, and updates about moving and relocation</p>
        </div>
    </section>

    <!-- Blog Controls -->
    <section class="blog-controls">
        <div class="container">
            <div class="controls-wrap">
                <div class="filters-row">
                    <span class="filter-title">Categories:</span>
                    <a href="blogs_new.php" class="pill <?php echo empty($category_filter) ? 'active' : ''; ?>">All Posts</a>
                    <?php foreach ($categories as $category): ?>
                    <a href="blogs_new.php?category=<?php echo urlencode($category); ?>" class="pill <?php echo $category_filter == $category ? 'active' : ''; ?>"><?php echo htmlspecialchars($category); ?></a>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </section>

    <!-- Posts Grid -->
    <section class="posts-wrap">
        <div class="container">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="posts-grid">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card">
                            <?php if (!empty($post['featured_image'])): ?>
                                <div class="post-image">
                                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="post-content">
                                <h2><a href="blog-single.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                                <div class="post-meta">
                                    <span class="post-date"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                                    <?php if (!empty($post['author'])): ?>
                                        <span class="post-author">By <?php echo htmlspecialchars($post['author']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($post['category'])): ?>
                                        <span class="post-category"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($post['category']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php 
                                    $raw = strip_tags($post['content']);
                                    $preview = mb_substr($raw, 0, 500);
                                ?>
                                <p class="post-excerpt" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;">
                                    <?php echo htmlspecialchars($preview); ?>
                                </p>
                                <a href="blog-single.php?id=<?php echo $post['id']; ?>" class="read-more">Read More</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="far fa-newspaper"></i>
                        <p>No blog posts found. Check back later for updates!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/918872998866" target="_blank" id="whatsapp-float" title="Chat with us on WhatsApp">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" style="width:56px;height:56px;">
    </a>
    <style>
    #whatsapp-float {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 99999;
        background: #25d366;
        border-radius: 50%;
        box-shadow: 0 4px 16px rgba(0,0,0,0.18);
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    #whatsapp-float:hover {
        box-shadow: 0 8px 32px rgba(37,211,102,0.25);
        transform: scale(1.08);
        background: #1ebe57;
    }
    #whatsapp-float img {
        display: block;
    }
    </style>

    <!-- Quote Modal -->
    <div id="quote-modal" class="quote-modal-overlay" style="display:none;">
        <div class="quote-modal-content">
            <button class="quote-modal-close" id="quote-modal-close" aria-label="Close">&times;</button>
            <div class="quote-header">
                <h2>Get a free Quote today</h2>
                <p>Fill the form and our team will contact you soon.</p>
            </div>
            <form id="quoteForm" class="quote-form" action="https://formsubmit.co/jhravindra@gmail.com" method="POST">
                <div class="form-group">
                    <input type="text" id="name" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <input type="tel" id="mobile" name="mobile" placeholder="Mobile No" required pattern="[0-9]{10,15}">
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <select id="service" name="service" required>
                        <option value="" disabled selected>Select Service</option>
                        <option value="packing-moving">Packing & Moving</option>
                        <option value="vehicle-relocation">Vehicle Relocation</option>
                        <option value="pet-relocation">Pet Relocation</option>
                        <option value="packing-labour">Packing Labour</option>
                    </select>
                </div>
                <button type="submit" class="btn-red btn-block">Submit</button>
            </form>
            <div id="success-popup" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:99999;justify-content:center;align-items:center;">
                <div style="background:#fff;padding:32px 28px;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.18);text-align:center;max-width:350px;">
                    <h2 style="color:#888;margin-bottom:12px;">Thank you!</h2>
                    <p style="color:#333;">Thank you! Your request has been accepted. Our expert team will get in touch with you shortly.</p>
                    <button onclick="document.getElementById('success-popup').style.display='none'" style="margin-top:18px;padding:10px 28px;background:#888;color:#fff;border:none;border-radius:6px;font-size:1rem;cursor:pointer;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div id="footer"></div>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/quote-modal.js"></script>
    <script src="js/quote-popup.js"></script>
    <script src="js/footer.js"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileNav = document.getElementById('mobileNav');
            const closeBtn = document.getElementById('closeBtn');
            
            if (mobileMenuBtn && mobileNav && closeBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileNav.classList.add('active');
                });
                
                closeBtn.addEventListener('click', function() {
                    mobileNav.classList.remove('active');
                });
            }
            
            // Mobile Services Dropdown Toggle
            const servicesToggle = document.getElementById('mobileServicesToggle');
            const servicesMenu = document.getElementById('mobileServicesMenu');
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
            
            // Filter functionality
            const filterButtons = document.querySelectorAll('.pill');
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    button.classList.add('active');
                    
                    const filter = button.getAttribute('data-filter');
                    const posts = document.querySelectorAll('.post-card');
                    
                    posts.forEach(post => {
                        if (filter === 'all' || post.getAttribute('data-category') === filter) {
                            post.style.display = 'block';
                        } else {
                            post.style.display = 'none';
                        }
                    });
                });
            });


        });
    </script>
</body>
</html>
