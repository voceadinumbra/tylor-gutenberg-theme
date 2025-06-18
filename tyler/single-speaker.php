<?php
/**
 * Single Speaker Template
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $speaker_id = get_the_ID();
        $post_meta_data = get_post_custom();
        $speaker_title = $post_meta_data['speaker_title'][0] ?? '';
        $company_name = $post_meta_data['company_name'][0] ?? '';
        $full_schedule_url = class_exists('EF_Session_Helper') ? EF_Session_Helper::get_schedule_url() : '';

        // Set up filters for sessions query
        if (class_exists('EF_Speakers_Helper')) {
            add_filter('posts_fields', ['EF_Speakers_Helper', 'ef_speaker_sessions_posts_fields']);
            add_filter('posts_orderby', ['EF_Speakers_Helper', 'ef_speaker_sessions_posts_orderby']);
            $sessions_loop = class_exists('EF_Session_Helper') ? EF_Session_Helper::get_sessions_loop() : null;
            remove_filter('posts_fields', ['EF_Speakers_Helper', 'ef_speaker_sessions_posts_fields']);
            remove_filter('posts_orderby', ['EF_Speakers_Helper', 'ef_speaker_sessions_posts_orderby']);
        } else {
            $sessions_loop = null;
        }
?>

<div class="heading">
    <div class="container">
        <h1>
            <?php
            the_post_thumbnail('tyler_speaker', [
                'title' => esc_attr(get_the_title()),
                'class' => 'img-speaker',
                'alt' => esc_attr(get_the_title())
            ]);
            ?>
            <div class="speaker_details">
                <div class="speaker_name">
                    <?php
                    the_title();
                    if (!empty($speaker_title)) {
                        echo ', ' . esc_html($speaker_title);
                    }
                    ?>
                </div>
                <?php if (!empty($company_name)) : ?>
                    <div class="speaker_company"><?php echo esc_html($company_name); ?></div>
                <?php endif; ?>
            </div>
        </h1>
        <nav class="nav">
            <?php previous_post_link('%link', '<i class="icon-angle-left"></i>'); ?>
            <a href="<?php echo esc_url(home_url('/speakers')); ?>" title="<?php esc_attr_e('All', 'tyler'); ?>">
                <i class="icon-th-large"></i>
            </a>
            <?php next_post_link('%link', '<i class="icon-angle-right"></i>'); ?>
        </nav>
    </div>
</div>

<div class="container">
    <?php the_content(); ?>
    <hr>
    <?php if (!empty($full_schedule_url)) : ?>
        <a href="<?php echo esc_url($full_schedule_url); ?>" class="btn btn-primary btn-header pull-right hidden-xs">
            <?php esc_html_e('View full schedule', 'tyler'); ?>
        </a>
    <?php endif; ?>

    <h2><?php esc_html_e('Related Sessions', 'tyler'); ?></h2>
    <div class="sessions condensed">
        <?php if ($sessions_loop && $sessions_loop->have_posts()) : ?>
            <?php while ($sessions_loop->have_posts()) : $sessions_loop->the_post(); ?>
                <?php
                $session_speakers = get_post_meta(get_the_ID(), 'session_speakers_list', true);
                if (is_array($session_speakers) && in_array($speaker_id, $session_speakers)) :
                    $date = get_post_meta(get_the_ID(), 'session_date', true);
                    $locations = wp_get_post_terms(get_the_ID(), 'session-location', ['fields' => 'all']);
                    $time = get_post_meta(get_the_ID(), 'session_time', true);
                    $end_time = get_post_meta(get_the_ID(), 'session_end_time', true);

                    // Format time
                    if (!empty($time)) {
                        $time_parts = explode(':', $time);
                        if (count($time_parts) === 2) {
                            $time = date(get_option('time_format'), mktime((int)$time_parts[0], (int)$time_parts[1], 0));
                        }
                    }
                    if (!empty($end_time)) {
                        $time_parts = explode(':', $end_time);
                        if (count($time_parts) === 2) {
                            $end_time = date(get_option('time_format'), mktime((int)$time_parts[0], (int)$time_parts[1], 0));
                        }
                    }

                    // Get track color
                    $tracks = wp_get_post_terms(get_the_ID(), 'session-track', ['fields' => 'ids', 'count' => 1]);
                    $color = $tracks && class_exists('EF_Taxonomy_Helper')
                        ? EF_Taxonomy_Helper::ef_get_term_meta('session-track-metas', $tracks[0], 'session_track_color')
                        : '';
                ?>
                    <div class="session">
                        <a href="<?php the_permalink(); ?>" class="session-inner">
                            <span class="title" <?php if (!empty($color)) echo 'style="color:' . esc_attr($color) . ';"'; ?>>
                                <span class="text-fit"><?php echo esc_html(get_the_title()); ?></span>
                            </span>
                            <span class="desc">
                                <?php esc_html_e('Location:', 'tyler'); ?>
                                <strong><?php echo !empty($locations) ? esc_html($locations[0]->name) : ''; ?></strong>
                            </span>
                            <span class="desc">
                                <?php esc_html_e('Date:', 'tyler'); ?>
                                <strong><?php echo !empty($date) ? esc_html(date_i18n(get_option('date_format'), $date)) : ''; ?></strong>
                            </span>
                            <span class="desc">
                                <?php esc_html_e('Time:', 'tyler'); ?>
                                <strong><?php echo esc_html($time) . ' - ' . esc_html($end_time); ?></strong>
                            </span>
                            <span class="more">
                                <?php esc_html_e('View session', 'tyler'); ?> <i class="icon-angle-right"></i>
                            </span>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php esc_html_e('No related sessions found.', 'tyler'); ?></p>
        <?php endif; ?>
    </div>

    <?php if (!empty($full_schedule_url)) : ?>
        <p class="visible-xs text-center">
            <a href="<?php echo esc_url($full_schedule_url); ?>" class="btn btn-primary btn-header">
                <?php esc_html_e('View full schedule', 'tyler'); ?>
            </a>
        </p>
    <?php endif; ?>
</div>

<?php
    endwhile;
else :
    echo '<p>' . esc_html__('No content found.', 'tyler') . '</p>';
endif;

get_footer();
?>