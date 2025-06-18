<?php
/*
 * Template Name: Speakers
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <?php while (have_posts()) : the_post(); ?>
        
        <div class="heading">	
            <div class="container">
                <h1>Speakers</h1>
            </div>
        </div>

        <!-- Speakers Content -->
        <section class="speakers-content">
            <div class="container">
                <?php
                // Get current page for pagination
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                
                // Get speakers using WP_Query for better performance and flexibility
                $speakers_query = new WP_Query([
                    'post_type' => 'speaker',
                    'post_status' => 'publish',
                    'posts_per_page' => 36, // Limit to 36 speakers per page
                    'paged' => $paged,
                    'orderby' => 'menu_order title',
                    'order' => 'ASC',
                    'meta_query' => [
                        'relation' => 'OR',
                        [
                            'key' => 'speaker_featured',
                            'value' => '1',
                            'compare' => '='
                        ],
                        [
                            'key' => 'speaker_featured',
                            'compare' => 'NOT EXISTS'
                        ]
                    ]
                ]);

                if ($speakers_query->have_posts()) :
                    // Separate featured and regular speakers
                    $featured_speakers = [];
                    $regular_speakers = [];
                    
                    while ($speakers_query->have_posts()) : $speakers_query->the_post();
                        $speaker_data = get_speaker_data(get_the_ID());
                        
                        if ($speaker_data['is_featured']) {
                            $featured_speakers[] = $speaker_data;
                        } else {
                            $regular_speakers[] = $speaker_data;
                        }
                    endwhile;
                    wp_reset_postdata();
                    
                    // Display Featured Speakers
                    if (!empty($featured_speakers)) : ?>
                        <section class="speakers-section featured-speakers">
                            <header class="section-header">
                                <h2 class="section-title"><?php esc_html_e('Featured Speakers', 'textdomain'); ?></h2>
                            </header>
                            <div class="speakers-grid speakers-grid--featured">
                                <?php foreach ($featured_speakers as $speaker) : ?>
                                    <?php render_speaker_card($speaker, 'featured'); ?>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif;
                    
                    // Display Regular Speakers
                    if (!empty($regular_speakers)) : ?>
                        <section class="speakers-section regular-speakers">
                            <header class="section-header">
                                <h2 class="section-title"><?php esc_html_e('All Speakers', 'textdomain'); ?></h2>
                            </header>
                            <div class="speakers-grid">
                                <?php foreach ($regular_speakers as $speaker) : ?>
                                    <?php render_speaker_card($speaker); ?>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif;
                    
                else : ?>
                    <div class="no-speakers-found">
                        <p><?php esc_html_e('No speakers found.', 'textdomain'); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php
                // Add pagination if there are multiple pages
                if ($speakers_query->max_num_pages > 1) : ?>
                    <nav class="speakers-pagination" role="navigation" aria-label="<?php esc_attr_e('Speakers pagination', 'textdomain'); ?>">
                        <?php
                        echo paginate_links([
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $speakers_query->max_num_pages,
                            'prev_text' => __('&laquo; Previous', 'textdomain'),
                            'next_text' => __('Next &raquo;', 'textdomain'),
                            'mid_size' => 2,
                            'end_size' => 1,
                            'type' => 'plain',
                            'add_args' => false,
                            'add_fragment' => '',
                        ]);
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </section>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>

<?php
/**
 * Helper function to get speaker data
 * 
 * @param int $speaker_id
 * @return array
 */
function get_speaker_data($speaker_id) {
    $speaker_meta = get_post_meta($speaker_id);
    
    return [
        'id' => $speaker_id,
        'title' => get_the_title($speaker_id),
        'permalink' => get_permalink($speaker_id),
        'thumbnail' => get_the_post_thumbnail($speaker_id, 'medium', [
            'class' => 'speaker-photo',
            'loading' => 'lazy',
            'alt' => get_the_title($speaker_id)
        ]),
        'job_title' => isset($speaker_meta['speaker_title'][0]) ? sanitize_text_field($speaker_meta['speaker_title'][0]) : '',
        'company' => isset($speaker_meta['company_name'][0]) ? sanitize_text_field($speaker_meta['company_name'][0]) : '',
        'bio' => isset($speaker_meta['speaker_bio'][0]) ? wp_trim_words($speaker_meta['speaker_bio'][0], 20) : '',
        'is_featured' => isset($speaker_meta['speaker_featured'][0]) && $speaker_meta['speaker_featured'][0] === '1',
        'is_keynote' => isset($speaker_meta['speaker_keynote'][0]) && $speaker_meta['speaker_keynote'][0] === '1',
        'social_links' => [
            'twitter' => isset($speaker_meta['speaker_twitter'][0]) ? esc_url($speaker_meta['speaker_twitter'][0]) : '',
            'linkedin' => isset($speaker_meta['speaker_linkedin'][0]) ? esc_url($speaker_meta['speaker_linkedin'][0]) : '',
            'website' => isset($speaker_meta['speaker_website'][0]) ? esc_url($speaker_meta['speaker_website'][0]) : ''
        ]
    ];
}

/**
 * Render speaker card
 * 
 * @param array $speaker
 * @param string $type
 */
function render_speaker_card($speaker, $type = 'regular') {
    $card_classes = ['speaker-card'];
    
    if ($speaker['is_featured']) {
        $card_classes[] = 'speaker-card--featured';
    }
    
    if ($speaker['is_keynote']) {
        $card_classes[] = 'speaker-card--keynote';
    }
    
    $description_parts = array_filter([
        $speaker['job_title'],
        $speaker['company']
    ]);
    
    $description = implode(', ', $description_parts);
    ?>
    
    <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?>" 
             itemscope itemtype="https://schema.org/Person">
        
        <a href="<?php echo esc_url($speaker['permalink']); ?>" 
           class="speaker-card__link" 
           aria-label="<?php echo esc_attr(sprintf(__('View %s profile', 'textdomain'), $speaker['title'])); ?>">
            
            <?php if ($speaker['thumbnail']) : ?>
                <div class="speaker-card__photo">
                    <?php echo $speaker['thumbnail']; ?>
                    <?php if ($speaker['is_keynote']) : ?>
                        <span class="speaker-badge speaker-badge--keynote" aria-label="<?php esc_attr_e('Keynote Speaker', 'textdomain'); ?>">
                            <?php esc_html_e('Keynote', 'textdomain'); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="speaker-card__content">
                <h3 class="speaker-card__name" itemprop="name">
                    <?php echo esc_html($speaker['title']); ?>
                </h3>
                
                <?php if ($description) : ?>
                    <p class="speaker-card__description" itemprop="jobTitle">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($speaker['bio']) : ?>
                    <p class="speaker-card__bio" itemprop="description">
                        <?php echo esc_html($speaker['bio']); ?>
                    </p>
                <?php endif; ?>
                
                <span class="speaker-card__cta">
                    <?php esc_html_e('View Profile', 'textdomain'); ?>
                    <svg class="speaker-card__arrow" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8.293 12.707a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0 0-1.414l-4-4a1 1 0 0 0-1.414 1.414L10.586 7H3a1 1 0 0 0 0 2h7.586l-2.293 2.293a1 1 0 0 0 0 1.414z"/>
                    </svg>
                </span>
            </div>
        </a>
        
        <?php if (array_filter($speaker['social_links'])) : ?>
            <div class="speaker-card__social">
                <?php foreach ($speaker['social_links'] as $platform => $url) : ?>
                    <?php if ($url) : ?>
                        <a href="<?php echo esc_url($url); ?>" 
                           class="speaker-social-link speaker-social-link--<?php echo esc_attr($platform); ?>"
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="<?php echo esc_attr(sprintf(__('%s on %s', 'textdomain'), $speaker['title'], ucfirst($platform))); ?>">
                            <span class="screen-reader-text"><?php echo esc_html(ucfirst($platform)); ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </article>
    
    <?php
}
?>
<style>
    

.page-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 700;
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.page-description {
    font-size: 1.125rem;
    max-width: 600px;
    margin: 0 auto;
    opacity: 0.9;
    line-height: 1.6;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Speakers Content */
.speakers-content {
    padding: 2rem 0 4rem;
}

.speakers-section {
    margin-bottom: 4rem;
}

.speakers-section:last-child {
    margin-bottom: 0;
}

.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -0.5rem;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
}

/* Speakers Grid */
.speakers-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.speakers-grid--featured {
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
}

/* Speaker Card */
.speaker-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.speaker-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.speaker-card--featured {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border: 2px solid #e2e8f0;
}

.speaker-card--keynote::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f6ad55, #ed8936);
}

.speaker-card__link {
    display: block;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.speaker-card__photo {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
}

.speaker-card__photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.speaker-card:hover .speaker-card__photo img {
    transform: scale(1.05);
}

.speaker-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(237, 137, 54, 0.9);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.speaker-card__content {
    padding: 1.5rem;
}

.speaker-card__name {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: #2d3748;
    line-height: 1.3;
}

.speaker-card__description {
    color: #4a5568;
    font-size: 0.875rem;
    margin: 0 0 1rem 0;
    font-weight: 500;
}

.speaker-card__bio {
    color: #718096;
    font-size: 0.875rem;
    margin: 0 0 1.5rem 0;
    line-height: 1.5;
}

.speaker-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #667eea;
    font-weight: 500;
    font-size: 0.875rem;
    transition: color 0.2s ease;
}

.speaker-card:hover .speaker-card__cta {
    color: #5a67d8;
}

.speaker-card__arrow {
    transition: transform 0.2s ease;
}

.speaker-card:hover .speaker-card__arrow {
    transform: translateX(4px);
}

/* Social Links */
.speaker-card__social {
    padding: 0 1.5rem 1.5rem;
    display: flex;
    gap: 0.75rem;
}

.speaker-social-link {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
}

.speaker-social-link:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.speaker-social-link--twitter:hover {
    background: #1da1f2;
}

.speaker-social-link--linkedin:hover {
    background: #0077b5;
}

.speaker-social-link--website:hover {
    background: #48bb78;
}

/* Screen reader text */
.screen-reader-text {
    position: absolute !important;
    height: 1px;
    width: 1px;
    overflow: hidden;
    clip: rect(1px, 1px, 1px, 1px);
    white-space: nowrap;
}

/* No speakers found */
.no-speakers-found {
    text-align: center;
    padding: 4rem 0;
    color: #718096;
}

/* Pagination */
.speakers-pagination {
    margin-top: 3rem;
    text-align: center;
}

.speakers-pagination .page-numbers {
    display: inline-block;
    padding: 0.75rem 1rem;
    margin: 0 0.25rem;
    color: #4a5568;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.speakers-pagination .page-numbers:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.speakers-pagination .page-numbers.current {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.speakers-pagination .page-numbers.prev,
.speakers-pagination .page-numbers.next {
    font-weight: 600;
}

.speakers-pagination .page-numbers.dots {
    border: none;
    background: none;
    color: #a0aec0;
}

.speakers-pagination .page-numbers.dots:hover {
    background: none;
    color: #a0aec0;
    border: none;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .speakers-grid,
    .speakers-grid--featured {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
   
    
    .speakers-grid,
    .speakers-grid--featured {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .speakers-section {
        margin-bottom: 3rem;
    }
    
    .section-header {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 0.75rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .page-description {
        font-size: 1rem;
    }
    
    .speakers-grid,
    .speakers-grid--featured {
        grid-template-columns: 1fr;
    }
    
    .speaker-card__content {
        padding: 1rem;
    }
    
    .speaker-card__social {
        padding: 0 1rem 1rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .speaker-card {
        background: #2d3748;
        color: #e2e8f0;
    }
    
    .speaker-card--featured {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        border-color: #4a5568;
    }
    
    .speaker-card__name {
        color: #f7fafc;
    }
    
    .speaker-card__description {
        color: #cbd5e0;
    }
    
    .speaker-card__bio {
        color: #a0aec0;
    }
    
    .section-title {
        color: #f7fafc;
    }
    
    .no-speakers-found {
        color: #a0aec0;
    }
}
</style>