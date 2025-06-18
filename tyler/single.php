<?php
/**
 * The template for displaying all single posts
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
            
            <!-- Post Header -->
            <div class="post-header">
                <div class="container">
                    <div class="post-meta">
                        <?php 
                        // Display post type specific information
                        $post_type = get_post_type();
                        $post_type_object = get_post_type_object($post_type);
                        
                        if ($post_type !== 'post') : ?>
                            <span class="post-type-badge">
                                <?php echo esc_html($post_type_object->labels->singular_name); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($post_type === 'post') : ?>
                            <div class="post-categories">
                                <?php
                                $categories = get_the_category();
                                if ($categories) {
                                    foreach ($categories as $category) {
                                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link">' . esc_html($category->name) . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <time class="post-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </div>
                    
                    <h1 class="post-title">
                        <?php 
                        // Special handling for sponsor post type
                        if ($post_type === 'sponsor') {
                            $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail', [
                                'class' => 'sponsor-logo',
                                'alt' => get_the_title(),
                                'loading' => 'eager'
                            ]);
                            if ($thumbnail) {
                                echo '<span class="sponsor-logo-wrapper">' . $thumbnail . '</span>';
                            }
                        }
                        the_title(); 
                        ?>
                    </h1>
                    
                    <?php if ($post_type === 'post') : ?>
                        <div class="post-author-meta">
                            <div class="author-info">
                                <?php echo get_avatar(get_the_author_meta('ID'), 40, '', '', ['class' => 'author-avatar']); ?>
                                <div class="author-details">
                                    <span class="author-name">
                                        <?php esc_html_e('By', 'textdomain'); ?> 
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php echo esc_html(get_the_author()); ?>
                                        </a>
                                    </span>
                                    <span class="reading-time">
                                        <?php echo esc_html(estimate_reading_time(get_the_content())); ?> <?php esc_html_e('min read', 'textdomain'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Post Navigation -->
            <nav class="post-navigation" role="navigation" aria-label="<?php esc_attr_e('Post navigation', 'textdomain'); ?>">
                <div class="container">
                    <div class="nav-links">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        $categories = get_the_category();
                        $archive_link = '';
                        
                        // Determine archive link based on post type
                        if ($post_type === 'post' && $categories) {
                            $archive_link = get_category_link($categories[0]->term_id);
                            $archive_title = sprintf(__('All %s posts', 'textdomain'), $categories[0]->name);
                        } elseif ($post_type !== 'post') {
                            $archive_link = get_post_type_archive_link($post_type);
                            $archive_title = sprintf(__('All %s', 'textdomain'), $post_type_object->labels->name);
                        }
                        ?>
                        
                        <?php if ($prev_post) : ?>
                            <div class="nav-previous">
                                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" 
                                   class="nav-link nav-link--prev"
                                   aria-label="<?php esc_attr_e('Previous post', 'textdomain'); ?>">
                                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="nav-text">
                                        <span class="nav-label"><?php esc_html_e('Previous', 'textdomain'); ?></span>
                                        <span class="nav-title"><?php echo esc_html(get_the_title($prev_post)); ?></span>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($archive_link) : ?>
                            <div class="nav-archive">
                                <a href="<?php echo esc_url($archive_link); ?>" 
                                   class="nav-link nav-link--archive"
                                   aria-label="<?php echo esc_attr($archive_title); ?>">
                                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    <span class="nav-text">
                                        <span class="nav-label"><?php esc_html_e('View All', 'textdomain'); ?></span>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <div class="nav-next">
                                <a href="<?php echo esc_url(get_permalink($next_post)); ?>" 
                                   class="nav-link nav-link--next"
                                   aria-label="<?php esc_attr_e('Next post', 'textdomain'); ?>">
                                    <span class="nav-text">
                                        <span class="nav-label"><?php esc_html_e('Next', 'textdomain'); ?></span>
                                        <span class="nav-title"><?php echo esc_html(get_the_title($next_post)); ?></span>
                                    </span>
                                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
            
            <!-- Featured Image -->
            <?php if (has_post_thumbnail() && $post_type !== 'sponsor') : ?>
                <div class="post-featured-image">
                    <div class="container">
                        <figure class="featured-image-wrapper">
                            <?php 
                            the_post_thumbnail('large', [
                                'class' => 'featured-image',
                                'loading' => 'eager'
                            ]); 
                            ?>
                            <?php 
                            $caption = get_the_post_thumbnail_caption();
                            if ($caption) : ?>
                                <figcaption class="featured-image-caption">
                                    <?php echo esc_html($caption); ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Post Content -->
            <div class="post-content">
                <div class="container">
                    <div class="content-wrapper">
                        <?php
                        the_content();
                        
                        // Handle paginated posts
                        wp_link_pages([
                            'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'textdomain') . '</span>',
                            'after' => '</div>',
                            'link_before' => '<span class="page-number">',
                            'link_after' => '</span>',
                            'pagelink' => '<span class="screen-reader-text">' . __('Page', 'textdomain') . ' </span>%',
                            'separator' => '<span class="screen-reader-text">, </span>',
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Post Footer -->
            <footer class="post-footer">
                <div class="container">
                    <?php if ($post_type === 'post') : ?>
                        <div class="post-tags">
                            <?php
                            $tags = get_the_tags();
                            if ($tags) : ?>
                                <div class="tags-wrapper">
                                    <span class="tags-label"><?php esc_html_e('Tags:', 'textdomain'); ?></span>
                                    <div class="tags-list">
                                        <?php foreach ($tags as $tag) : ?>
                                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                                               class="tag-link"
                                               rel="tag">
                                                <?php echo esc_html($tag->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Social Sharing -->
                        <div class="post-sharing">
                            <span class="sharing-label"><?php esc_html_e('Share:', 'textdomain'); ?></span>
                            <div class="sharing-buttons">
                                <?php
                                $post_url = urlencode(get_permalink());
                                $post_title = urlencode(get_the_title());
                                ?>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" 
                                   class="share-button share-twitter" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   aria-label="<?php esc_attr_e('Share on Twitter', 'textdomain'); ?>">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" 
                                   class="share-button share-facebook" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   aria-label="<?php esc_attr_e('Share on Facebook', 'textdomain'); ?>">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" 
                                   class="share-button share-linkedin" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   aria-label="<?php esc_attr_e('Share on LinkedIn', 'textdomain'); ?>">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </footer>
            
        </article>
        
        <?php
        // Show related posts for blog posts
        if ($post_type === 'post') {
            get_template_part('template-parts/related-posts');
        }
        
        
        ?>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>

<?php
/**
 * Helper function to estimate reading time
 * 
 * @param string $content
 * @return int
 */
function estimate_reading_time($content) {
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
    return max($reading_time, 1); // Minimum 1 minute
}
?>

<style>
    /* Modern Single Post Template Styles */

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Main Content */
.site-main {
    padding: 2rem 0;
}

.single-post {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

/* Post Header */
.post-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 0;
    text-align: center;
    position: relative;
}

.post-header::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
}

.post-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.post-type-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.post-categories {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.category-link {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background 0.2s ease;
}

.category-link:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
}

.post-date {
    font-size: 0.875rem;
    opacity: 0.9;
    font-weight: 500;
}

.post-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    margin: 0 0 1.5rem 0;
    line-height: 1.2;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.sponsor-logo-wrapper {
    display: inline-flex;
    align-items: center;
}

.sponsor-logo {
    max-width: 80px;
    max-height: 80px;
    width: auto;
    height: auto;
    background: white;
    padding: 0.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.post-author-meta {
    margin-top: 1rem;
}

.author-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.author-avatar {
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.author-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.author-name a {
    color: white;
    text-decoration: none;
    font-weight: 600;
}

.author-name a:hover {
    text-decoration: underline;
}

.reading-time {
    font-size: 0.875rem;
    opacity: 0.8;
}

/* Post Navigation */
.post-navigation {
    background: #f7fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(10px);
}

.nav-links {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.nav-link:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.nav-text {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.nav-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    opacity: 0.7;
}

.nav-title {
    font-size: 0.875rem;
    font-weight: 500;
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.nav-icon {
    flex-shrink: 0;
    opacity: 0.7;
}

.nav-link--archive .nav-text {
    flex-direction: row;
    align-items: center;
}

/* Featured Image */
.post-featured-image {
    margin: 2rem 0;
}

.featured-image-wrapper {
    margin: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.featured-image {
    width: 100%;
    height: auto;
    display: block;
}

.featured-image-caption {
    padding: 1rem;
    background: #f7fafc;
    color: #4a5568;
    font-size: 0.875rem;
    font-style: italic;
    text-align: center;
    border-top: 1px solid #e2e8f0;
}

/* Post Content */
.post-content {
    padding: 3rem 0;
}

.content-wrapper {
    max-width: 800px;
    margin: 0 auto;
    font-size: 1.125rem;
    line-height: 1.7;
    color: #2d3748;
}

.content-wrapper h1,
.content-wrapper h2,
.content-wrapper h3,
.content-wrapper h4,
.content-wrapper h5,
.content-wrapper h6 {
    color: #1a202c;
    font-weight: 600;
    margin: 2rem 0 1rem 0;
    line-height: 1.3;
}

.content-wrapper h2 {
    font-size: 2rem;
}

.content-wrapper h3 {
    font-size: 1.5rem;
}

.content-wrapper h4 {
    font-size: 1.25rem;
}

.content-wrapper p {
    margin-bottom: 1.5rem;
}

.content-wrapper a {
    color: #667eea;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s ease;
}

.content-wrapper a:hover {
    border-color: #667eea;
}

.content-wrapper img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 1.5rem 0;
}

.content-wrapper blockquote {
    border-left: 4px solid #667eea;
    padding: 1rem 1.5rem;
    margin: 2rem 0;
    background: #f7fafc;
    border-radius: 0 8px 8px 0;
    font-style: italic;
    color: #4a5568;
}

.content-wrapper ul,
.content-wrapper ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.content-wrapper li {
    margin-bottom: 0.5rem;
}

.content-wrapper code {
    background: #f1f5f9;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875em;
    color: #e53e3e;
}

.content-wrapper pre {
    background: #1a202c;
    color: #f7fafc;
    padding: 1.5rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.content-wrapper pre code {
    background: none;
    color: inherit;
    padding: 0;
}

/* Page Links (for paginated posts) */
.page-links {
    margin: 2rem 0;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 8px;
    text-align: center;
}

.page-links-title {
    font-weight: 600;
    margin-right: 1rem;
    color: #4a5568;
}

.page-number {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.page-number:hover,
.page-links .current .page-number {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

/* Post Footer */
.post-footer {
    background: #f7fafc;
    padding: 2rem 0;
    border-top: 1px solid #e2e8f0;
}

.post-tags {
    margin-bottom: 2rem;
}

.tags-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.tags-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.875rem;
}

.tags-list {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.tag-link {
    background: white;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
}
</style>