<?php get_header() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $full_speakers_url = EF_Speakers_Helper::get_speakers_url();
        $full_schedule_url = EF_Session_Helper::get_schedule_url();
        
        $speaker_id = get_the_ID();

        add_filter( 'posts_fields', array( 'EF_Speakers_Helper', 'ef_speaker_sessions_posts_fields' ) );
        add_filter( 'posts_orderby', array( 'EF_Speakers_Helper', 'ef_speaker_sessions_posts_orderby' ) );

        $sessions_loop = EF_Session_Helper::get_sessions_loop();

        remove_filter( 'posts_fields', array( 'EF_Speakers_Helper', 'ef_speaker_sessions_posts_fields' ) );
        remove_filter( 'posts_orderby', array( 'EF_Speakers_Helper', 'ef_speaker_sessions_posts_orderby' ) );
        ?>
        <div class="heading">
            <div class="container">
                <h1>
                    <?php the_post_thumbnail('tyler_speaker', array('title' => get_the_title(), 'class' => 'img-circle')); ?>
                    <?php the_title(); ?>
                </h1>
                <div class="nav">
                    <?php echo get_previous_post_link('%link', '<i class="icon-angle-left"></i>'); ?>
                    <?php if (!empty($full_speakers_url)) { ?>
                        <a href="<?php echo $full_speakers_url; ?>" title="<?php _e('All', 'tyler'); ?>"><i class="icon-th-large"></i></a>
                    <?php } ?>
                    <?php echo get_next_post_link('%link', '<i class="icon-angle-right"></i>'); ?>
                </div>
            </div>
        </div>
        <div class="container">
            <?php the_content(); ?>
            <hr/>
            <?php if (!empty($full_schedule_url)) { ?>
                <a href="<?php echo $full_schedule_url; ?>" class="btn btn-primary btn-header pull-right hidden-xs"><?php _e('View full schedule', 'tyler'); ?></a>
            <?php } ?>
            <h2><?php _e('Related Sessions', 'tyler'); ?></h2>
            <div class="sessions condensed">
                <?php
                if ($sessions_loop->have_posts()):
                    while ($sessions_loop->have_posts()):
                        $sessions_loop->the_post();
                        $session_speakers = get_post_meta(get_the_ID(), 'session_speakers_list', true);
                        if ($session_speakers && is_array($session_speakers) && in_array($speaker_id, $session_speakers)) {
                            $date = get_post_meta(get_the_ID(), 'session_date', true);
                            $locations = wp_get_post_terms(get_the_ID(), 'session-location', array('fields' => 'all'));
                            $time = get_post_meta(get_the_ID(), 'session_time', true);
                            $end_time = get_post_meta(get_the_ID(), 'session_end_time', true);
                            if (!empty($time)) {
                                $time_parts = explode(':', $time);
                                if (count($time_parts) == 2)
                                    $time = date(get_option("time_format"), mktime($time_parts[0], $time_parts[1], 0));
                            }
                            if (!empty($end_time)) {
                                $time_parts = explode(':', $end_time);
                                if (count($time_parts) == 2)
                                    $end_time = date(get_option("time_format"), mktime($time_parts[0], $time_parts[1], 0));
                            }
                            $tracks = wp_get_post_terms(get_the_ID(), 'session-track', array('fields' => 'ids', 'count' => 1));
                            if ($tracks && count($tracks) > 0)
                                $color = EF_Taxonomy_Helper::ef_get_term_meta('session-track-metas', $tracks[0], 'session_track_color');
                            else
                                $color = '';
                            ?>
                            <div class="session">
                                <a href="<?php the_permalink(); ?>" class="session-inner">
                                    <span class="title" <?php if (!empty($color)) echo("style='color:$color;'"); ?>><span class="text-fit"><?php the_title(); ?></span></span>
                                    <span class="desc"><?php _e('Location:', 'tyler'); ?> <strong><?php echo(!empty($locations) ? $locations[0]->name : ''); ?></strong></span>
                                    <span class="desc"><?php _e('Date:', 'tyler'); ?> <strong><?php echo(!empty($date) ? date_i18n(get_option('date_format'), $date) : ''); ?></strong></span>
                                    <span class="desc"><?php _e('Time:', 'tyler'); ?> <strong><?php echo $time; ?> - <?php echo $end_time; ?></strong></span>
                                    <span class="more">
                                        <?php _e('View session', 'tyler'); ?> <i class="icon-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                            <?php
                        }
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            <?php if (!empty($full_schedule_url)) { ?>
                <p class="visible-xs text-center">
                    <a href="<?php echo $full_schedule_url; ?>" class="btn btn-primary btn-header"><?php _e('View full schedule', 'tyler'); ?></a>
                </p>
            <?php } ?>
        </div>
        <?php
    endwhile;
endif;
?>
<?php get_footer() ?>