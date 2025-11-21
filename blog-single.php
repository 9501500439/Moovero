<?php
// Database connection
require_once 'admin/config/db_connect.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: blogs_new.php');
    exit;
}

$post_id = (int)$_GET['id'];

// Get the blog post from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ? AND status = 'published'");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post) {
        // If post not found or not published, redirect to blog listing
        header('Location: blogs_new.php');
        exit;
    }
    
    // Get recent posts for the sidebar
    $recent_posts_stmt = $pdo->query("SELECT id, title, featured_image, created_at FROM blog_posts WHERE status = 'published' AND id != $post_id ORDER BY created_at DESC LIMIT 3");
    $recent_posts = $recent_posts_stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Error loading blog post: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> | Moveroo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --primary-color: #FF6B00;
            --primary-light: #ff8c3f;
            --primary-dark: #e05a00;
            --secondary-color: #2D3748;
            --text-color: #4A5568;
            --light-gray: #EDF2F7;
            --medium-gray: #CBD5E0;
            --dark-gray: #718096;
            --white: #fff;
            --black: #1A202C;
            --font-main: 'Poppins', sans-serif;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.08);
            --shadow: 0 4px 6px rgba(0,0,0,0.1), 0 2px 4px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            --transition: all 0.3s ease;
            --border-radius: 8px;
        }
        
        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }
        
        body { 
            font-family: var(--font-main); 
            color: var(--text-color); 
            background-color: #F7FAFC; 
            line-height: 1.8;
            font-size: 1rem;
            font-weight: 400;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header & Navigation */
        .site-header {
            background-color: var(--white);
            box-shadow: var(--shadow);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .back-button:hover {
            background-color: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
        }
        
        .back-button i {
            transition: var(--transition);
        }
        
        .back-button:hover i {
            transform: translateX(-3px);
        }
        
        /* Main Content */
        .main-content {
            padding: 120px 0 80px;
        }
        
        .blog-container {
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        
        .blog-hero {
            position: relative;
            height: 400px;
            overflow: hidden;
        }
        
        .blog-hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .blog-hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0));
            padding: 40px;
            color: var(--white);
        }
        
        .blog-hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .blog-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .blog-meta span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .blog-meta i {
            color: var(--primary-light);
        }
        
        .blog-content-wrapper {
            display: flex;
            gap: 40px;
            padding: 40px;
        }
        
        .blog-main {
            flex: 1;
            min-width: 0;
        }
        
        .blog-content {
            font-size: 1.05rem;
            line-height: 1.5;
        }
        
        .blog-content p {
            margin-bottom: 0.8em;
        }
        
        .blog-content h2, 
        .blog-content h3, 
        .blog-content h4 {
            color: var(--secondary-color);
            margin: 1.2em 0 0.6em;
            line-height: 1.3;
            font-weight: 600;
        }
        
        .blog-content h2 {
            font-size: 1.8rem;
        }
        
        .blog-content h3 {
            font-size: 1.5rem;
        }
        
        .blog-content h4 {
            font-size: 1.25rem;
        }
        
        .blog-content a {
            color: var(--primary-color);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: var(--transition);
        }
        
        .blog-content a:hover {
            border-bottom-color: var(--primary-color);
        }
        
        .blog-content ul, 
        .blog-content ol {
            margin-bottom: 0.8em;
            padding-left: 1.5em;
        }
        
        .blog-content li {
            margin-bottom: 0.2em;
        }
        
        .blog-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1.5em;
            margin: 1.8em 0;
            font-style: italic;
            color: var(--dark-gray);
        }
        
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: var(--border-radius);
            margin: 1.8em 0;
        }
        
        .blog-sidebar {
            width: 300px;
            flex-shrink: 0;
        }
        
        .sidebar-widget {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-sm);
        }
        
        .widget-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-gray);
        }
        
        .recent-posts-list {
            list-style: none;
        }
        
        .recent-post-item {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .recent-post-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .recent-post-image {
            width: 70px;
            height: 70px;
            border-radius: var(--border-radius);
            object-fit: cover;
        }
        
        .recent-post-info {
            flex: 1;
            min-width: 0;
        }
        
        .recent-post-title {
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 5px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .recent-post-title a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .recent-post-title a:hover {
            color: var(--primary-color);
        }
        
        .recent-post-date {
            font-size: 0.8rem;
            color: var(--dark-gray);
        }
        
        .share-buttons {
            display: flex;
            gap: 10px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }
        
        .share-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-gray);
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .share-button:hover {
            background-color: var(--primary-color);
            color: var(--white);
            transform: translateY(-3px);
        }
        
        /* Footer */
        .blog-footer {
            background-color: var(--white);
            padding: 30px 0;
            text-align: center;
            border-top: 1px solid var(--light-gray);
            margin-top: 60px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .blog-content-wrapper {
                flex-direction: column;
            }
            
            .blog-sidebar {
                width: 100%;
            }
            
            .blog-hero {
                height: 350px;
            }
            
            .blog-hero-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 100px 0 60px;
            }
            
            .blog-hero {
                height: 300px;
            }
            
            .blog-hero-overlay {
                padding: 30px;
            }
            
            .blog-hero-title {
                font-size: 1.8rem;
            }
            
            .blog-content-wrapper {
                padding: 30px;
            }
        }
        
        @media (max-width: 576px) {
            .blog-hero {
                height: 250px;
            }
            
            .blog-hero-overlay {
                padding: 20px;
            }
            
            .blog-hero-title {
                font-size: 1.5rem;
            }
            
            .blog-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .blog-content-wrapper {
                padding: 20px;
            }
            
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-container">
                <a href="index.html" class="logo">Moveroo</a>
                <a href="blogs_new.php" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to All Posts
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="blog-container">
                <?php if (!empty($post['featured_image'])): ?>
                <div class="blog-hero">
                    <img class="blog-hero-image" src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                    <div class="blog-hero-overlay">
                        <h1 class="blog-hero-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                        <div class="blog-meta">
                            <span><i class="far fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                            <?php if (!empty($post['author'])): ?>
                                <span><i class="far fa-user"></i> <?php echo htmlspecialchars($post['author']); ?></span>
                            <?php endif; ?>
                            <span><i class="far fa-clock"></i> <?php echo ceil(str_word_count(strip_tags($post['content'])) / 200); ?> min read</span>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="blog-header" style="padding: 40px 40px 20px; text-align: left; border-bottom: 1px solid var(--light-gray);">
                    <h1 class="blog-title" style="font-size: 2.5rem; margin-bottom: 20px; color: var(--secondary-color);"><?php echo htmlspecialchars($post['title']); ?></h1>
                    <div class="blog-meta" style="justify-content: flex-start;">
                        <span><i class="far fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                        <?php if (!empty($post['author'])): ?>
                            <span><i class="far fa-user"></i> <?php echo htmlspecialchars($post['author']); ?></span>
                        <?php endif; ?>
                        <span><i class="far fa-clock"></i> <?php echo ceil(str_word_count(strip_tags($post['content'])) / 200); ?> min read</span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="blog-content-wrapper">
                    <div class="blog-main">
                        <div class="blog-content">
                            <?php 
                            // Convert new lines to paragraphs and preserve HTML formatting
                            $content = $post['content'];
                            // Convert line breaks to <br> tags
                            $content = nl2br($content);
                            // Convert double line breaks to paragraphs
                            $content = '<p>' . preg_replace('/\n\s*\n/', '</p><p>', $content) . '</p>';
                            // Remove empty paragraphs
                            $content = str_replace('<p><br></p>', '', $content);
                            echo $content;
                            ?>
                            
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-button">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="share-button">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&title=<?php echo urlencode($post['title']); ?>" target="_blank" class="share-button">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="mailto:?subject=<?php echo urlencode($post['title']); ?>&body=<?php echo urlencode('Check out this article: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="share-button">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <aside class="blog-sidebar">
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Recent Posts</h3>
                            <?php if (!empty($recent_posts)): ?>
                            <ul class="recent-posts-list">
                                <?php foreach ($recent_posts as $recent_post): ?>
                                <li class="recent-post-item">
                                    <?php if (!empty($recent_post['featured_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($recent_post['featured_image']); ?>" alt="<?php echo htmlspecialchars($recent_post['title']); ?>" class="recent-post-image">
                                    <?php endif; ?>
                                    <div class="recent-post-info">
                                        <h4 class="recent-post-title">
                                            <a href="blog-single.php?id=<?php echo $recent_post['id']; ?>"><?php echo htmlspecialchars($recent_post['title']); ?></a>
                                        </h4>
                                        <div class="recent-post-date">
                                            <?php echo date('M j, Y', strtotime($recent_post['created_at'])); ?>
                                        </div>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else: ?>
                            <p>No recent posts found.</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Categories</h3>
                            <ul class="categories-list" style="list-style: none;">
                                <li><a href="#" style="display: block; padding: 8px 0; color: var(--text-color); text-decoration: none; border-bottom: 1px solid var(--light-gray); transition: var(--transition);">Moving Tips</a></li>
                                <li><a href="#" style="display: block; padding: 8px 0; color: var(--text-color); text-decoration: none; border-bottom: 1px solid var(--light-gray); transition: var(--transition);">Packing Guide</a></li>
                                <li><a href="#" style="display: block; padding: 8px 0; color: var(--text-color); text-decoration: none; border-bottom: 1px solid var(--light-gray); transition: var(--transition);">International Shipping</a></li>
                                <li><a href="#" style="display: block; padding: 8px 0; color: var(--text-color); text-decoration: none; border-bottom: 1px solid var(--light-gray); transition: var(--transition);">Vehicle Transport</a></li>
                                <li><a href="#" style="display: block; padding: 8px 0; color: var(--text-color); text-decoration: none; transition: var(--transition);">Pet Relocation</a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="blog-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Moveroo. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/footer.js"></script>
</body>
</html>
