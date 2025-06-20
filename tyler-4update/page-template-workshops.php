<?php
/*
 * Template Name: Workshops
 */

get_header(); ?>

<div class="heading">
    <div class="container">
        <h1><?php echo esc_html(get_the_title()); ?></h1>
    </div>
</div>

<div class="container">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    wp_reset_postdata();
    ?>

    <div class="loader-img" id="workshops-loader">
        <img alt="<?php esc_attr_e('Loading', 'textdomain'); ?>" 
             src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ajax-loader.gif'); ?>" 
             width="32" height="32" />
    </div>

    <div class="schedule">
        <div class="sessions list" id="workshops-container">
            <?php 
            $workshops = get_workshop_sessions();
            if (!empty($workshops)) :
                render_workshop_sessions($workshops);
            else : ?>
                <p><?php esc_html_e('No workshops found.', 'textdomain'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<?php
/**
 * Get workshop sessions with all required data
 */
function get_workshop_sessions() {
    $args = array(
        'post_type'      => 'session',
        'post_status'    => 'publish',
        'nopaging'       => true,
        'meta_query'     => array(
            array(
                'key'     => 'session_workshop',
                'compare' => 'EXISTS',
            ),
        ),
        'meta_key'       => 'session_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC'
    );

    $sessions_query = new WP_Query($args);
    $workshops = array();

    if ($sessions_query->have_posts()) {
        while ($sessions_query->have_posts()) {
            $sessions_query->the_post();
            $workshops[] = prepare_workshop_data(get_post());
        }
    }
    
    wp_reset_postdata();
    return $workshops;
}

/**
 * Prepare workshop data for a single session
 */
function prepare_workshop_data($post) {
    $session_date = get_post_meta($post->ID, 'session_date', true);
    $session_time = get_post_meta($post->ID, 'session_time', true);
    $session_end_time = get_post_meta($post->ID, 'session_end_time', true);
    $sponsor_logo = get_post_meta($post->ID, 'session_sponsor', true);
    $speakers_list = get_post_meta($post->ID, 'session_speakers_list', true);
    
    // Get session track
    $term_obj_list = get_the_terms($post->ID, 'session-track');
    $track_color = get_track_color($term_obj_list);
    
    // Prepare speakers data
    $speakers = prepare_speakers_data($speakers_list);
    
    return array(
        'ID'           => $post->ID,
        'title'        => get_the_title($post->ID),
        'permalink'    => get_permalink($post->ID),
        'date'         => $session_date,
        'start_time'   => $session_time,
        'end_time'     => $session_end_time,
        'sponsor_logo' => $sponsor_logo,
        'track_color'  => $track_color,
        'speakers'     => $speakers,
        'has_sponsor'  => !empty($sponsor_logo)
    );
}

/**
 * Get track color based on taxonomy
 */
function get_track_color($term_obj_list) {
    if (empty($term_obj_list) || !is_array($term_obj_list)) {
        return '';
    }
    
    $slug = $term_obj_list[0]->slug;
    
    switch ($slug) {
        case 'keynotes':
            return 'color:#f50a0a';
        case 'market-brief':
        case 'tech-brief':
            return 'color:#0042FF';
        default:
            return '';
    }
}

/**
 * Prepare speakers data
 */
function prepare_speakers_data($speakers_list) {
    $speakers = array();
    
    if (!empty($speakers_list) && is_array($speakers_list)) {
        foreach ($speakers_list as $speaker_id) {
            $speakers[] = array(
                'title'      => get_the_title($speaker_id),
                'url'        => get_permalink($speaker_id),
                'image'      => get_the_post_thumbnail($speaker_id, array(54, 54), array('alt' => get_the_title($speaker_id))),
                'company'    => get_post_meta($speaker_id, 'company_name', true),
                'position'   => get_post_meta($speaker_id, 'speaker_title', true),
                'featured'   => get_post_meta($speaker_id, 'speaker_keynote', true),
                'moderator'  => get_post_meta($speaker_id, 'speaker_moderator', true),
            );
        }
    }
    
    return $speakers;
}

/**
 * Render workshop sessions
 */
function render_workshop_sessions($workshops) {
    $previous_date = '';
    
    foreach ($workshops as $workshop) {
        // Render day header if date changed
        if ($workshop['date'] != $previous_date) {
            render_day_header($workshop['date']);
        }
        
        render_single_workshop($workshop);
        $previous_date = $workshop['date'];
    }
}

/**
 * Render day header
 */
function render_day_header($date) {
    ?>
    <div class="followWrap">
        <div class="day-floating">
            <span><?php echo esc_html(date_i18n(get_option('date_format'), $date)); ?></span>
        </div>
    </div>
    <?php
}

/**
 * Render single workshop session
 */
function render_single_workshop($workshop) {
    $start_time = date("g:i A", strtotime($workshop['start_time']));
    $end_time = date("g:i A", strtotime($workshop['end_time']));
    ?>
    <div class="session" data-session-id="<?php echo esc_attr($workshop['ID']); ?>">
        <span class="time"><?php echo esc_html($start_time . ' - ' . $end_time); ?></span>
        <div class="session-inner">
            <?php if ($workshop['has_sponsor']) : ?>
                <?php render_sponsor_logo($workshop['sponsor_logo']); ?>
                <span class="title"><?php echo esc_html($workshop['title']); ?></span>
            <?php else : ?>
                <a href="<?php echo esc_url($workshop['permalink']); ?>" class="more">
                    <?php esc_html_e('More info', 'textdomain'); ?> <i class="icon-angle-right"></i>
                </a>
                <a href="<?php echo esc_url($workshop['permalink']); ?>" 
                   class="title" 
                   style="<?php echo esc_attr($workshop['track_color']); ?>">
                    <span><?php echo esc_html($workshop['title']); ?></span>
                </a>
            <?php endif; ?>
            
            <span class="location"></span>
            
            <?php if (!empty($workshop['speakers'])) : ?>
                <span class="speakers-thumbs">
                    <?php foreach ($workshop['speakers'] as $speaker) : ?>
                        <?php render_speaker_thumb($speaker); ?>
                    <?php endforeach; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Render sponsor logo
 */
function render_sponsor_logo($sponsor_logo) {
    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($sponsor_logo), "medium");
    if ($thumbnail) {
        echo '<img class="session_sponsor" src="' . esc_url($thumbnail[0]) . '" alt="' . esc_attr__('Sponsor', 'textdomain') . '">';
    }
}

/**
 * Render speaker thumbnail
 */
function render_speaker_thumb($speaker) {
    ?>
    <a href="<?php echo esc_url($speaker['url']); ?>" class="speaker">
        <?php echo $speaker['image']; ?>
        <span class="name">
            <span class="text-fit">
                <span class="speaker_name"><?php echo esc_html($speaker['title']); ?></span>
                <?php if ($speaker['position']) : ?>
                    <span class="speaker_title"><br><?php echo esc_html($speaker['position']); ?></span>
                <?php endif; ?>
                <?php if ($speaker['company']) : ?>
                    <span class="speaker_company"><br><?php echo esc_html($speaker['company']); ?></span>
                <?php endif; ?>
            </span>
        </span>
        <span class="hidden speaker_title">
            <span class="speaker_name"><?php echo esc_html($speaker['title']); ?></span>
            <?php if ($speaker['position']) : ?>
                <span class="speaker_title"><br><?php echo esc_html($speaker['position']); ?></span>
            <?php endif; ?>
            <?php if ($speaker['company']) : ?>
                <span class="speaker_company"><br><?php echo esc_html($speaker['company']); ?></span>
            <?php endif; ?>
        </span>
    </a>
    <?php
}