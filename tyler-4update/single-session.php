<?php get_header() ?>

<?php
// Get Theme Options
$ef_options = EF_Event_Options::get_theme_options();


?>

<?php
if (have_posts()) : while (have_posts()) : the_post();
        $single_session_fields = EF_Query_Manager::get_single_session_fields();

        extract($single_session_fields);
        ?>
        <div class="heading">
            <div class="container">
                <h1>
                    <?php the_title() ?>
                </h1>

                <?php $prev_post = get_adjacent_post(true, '', true, 'category_name'); ?>
                <?php if (!empty($prev_post)): ?>
                    <a href="<?php echo $prev_post->guid; ?>"><?php echo $prev_post->post_title; ?></a>
                <?php endif; ?>

                <?php $next_post = get_adjacent_post(true, '', false, 'category_name'); ?>
                <?php if (!empty($next_post)): ?>
                    <a href="<?php echo $next_post->guid; ?>"><?php echo $next_post->post_title; ?></a>
                <?php endif; ?>

                <div class="nav">
                    <?php
                    //echo get_previous_post_link('%link', '<i class="icon-angle-left"></i>');
                    tyler_previous_post_link_plus(array('order_by' => 'custom', 'meta_key' => 'session_date', 'format' => '%link', 'link' => '<i class="icon-angle-left"></i>'));
                    ?>

<?php if(is_singular( 'session' )){if ($full_schedule_page && count($full_schedule_page) > 0) { ?>
<a href="<?php echo get_permalink($full_schedule_page[0]->ID); ?>" title="<?php _e('All', 'tyler'); ?>"><i class="icon-th-large"></i></a>
<?php } 
}
?>


<?php if(is_singular( 'sessiontwo' )){?>
<a href="<?php echo get_permalink($full_schedule_page[0]->ID); ?>" title="<?php _e('All', 'tyler'); ?>"><i class="icon-th-large"></i></a>
<?php } ?>
            
<?php if(is_singular( 'sessionthree' )){?>
<a href="<?php echo get_permalink($full_schedule_page[0]->ID); ?>" title="<?php _e('All', 'tyler'); ?>"><i class="icon-th-large"></i></a>
<?php } ?>

<?php if(is_singular( 'sessionfour' )){?>
<a href="<?php echo get_permalink($full_schedule_page[0]->ID); ?>" title="<?php _e('All', 'tyler'); ?>"><i class="icon-th-large"></i></a>
<?php } ?>

<?php if(is_singular( 'sessionfive' )){?>
<a href="<?php echo get_permalink($full_schedule_page[0]->ID); ?>" title="<?php _e('All', 'tyler'); ?>"><i class="icon-th-large"></i></a>
<?php } ?>
                            
                 
                    <?php
                    //echo get_next_post_link('%link', '<i class="icon-angle-right"></i>');
                    tyler_next_post_link_plus(array('order_by' => 'custom', 'meta_key' => 'session_date', 'format' => '%link', 'link' => '<i class="icon-angle-right"></i>'));
                    ?>
                </div>

            </div>
        </div>
        <div class="container">
            <p>
                <?php the_post_thumbnail('tyler-content'); ?>
            </p>
            <div class="row">
                <div class="col-md-8">
                    <?php the_content(); ?>
                    
                    <div>

                        <?php
                        $full_schedule_page = get_posts(
                                array(
                                    'post_type'  => 'page',
                                    'meta_key'   => '_wp_page_template',
                                    'meta_value' => 'schedule.php'
                                )
                        );                       
                   
                    if (isset($trackis) && is_array($trackis)) 
                    {
                        extract($trackis);                       
                    }
                    else                
                    {
                        $trackis = "http://simon-p.local/agenda-main/";
                    }

                        ?>

                <?php foreach ($tracks as $track) { ?>                
                    <?php echo "<a href=\"" . $trackis . "?track=" . $track->term_id . "\">"; ?><span class="single-session-link btn btn-primary" <?php if (!empty($track->color)) echo "style='background-color: $track->color;'"; ?>><?php echo $track->name; ?></span></a>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-md-4 sessions single">
                    <div class="session">
                        <!--<span class="location"><?php _e('Location:', 'tyler'); ?> <strong><?php echo(!empty($locations) ? $locations[0]->name : ''); ?></strong></span>-->
                        <span class="date"><?php _e('Date:', 'tyler'); ?> <strong><?php echo(!empty($date) ? date_i18n(get_option('date_format'), $date) : ''); ?></strong></span>
                        <span class="time"><?php _e('Time:', 'tyler'); ?> <strong><?php echo $time; ?> - <?php echo $end_time; ?></strong></span>
                        <span class="speakers-thumbs">
                            <?php
                            if (!empty($speakers_list)) 
                            {
                                foreach ($speakers_list as $speaker_id) 
                                {
									$post_meta_data = get_post_custom($speaker_id);
									$speaker_name = get_the_title($speaker_id);
									$speaker_name_only = get_the_title($speaker_id);
									if (!empty($post_meta_data['speaker_title'][0]))
										$speaker_title = $post_meta_data['speaker_title'][0];
										$speaker_name .= ", " . $post_meta_data['speaker_title'][0];
									if (!empty($post_meta_data['company_name'][0]))
										$speaker_company = $post_meta_data['company_name'][0];
										$speaker_name .= ", " . $post_meta_data['company_name'][0];
                                    ?>
                                    <a href = "<?php echo get_permalink($speaker_id); ?>" class="speaker<?php echo (!empty($post_meta_data['speaker_keynote'][0]) && $post_meta_data['speaker_keynote'][0] == 1) ? ' featured' : ''; ?>">
                                        <?php echo get_the_post_thumbnail($speaker_id, 'post-thumbnail', array('title' => get_the_title($speaker_id))); ?>
                                        <span class="name"><!--<span class="text-speaker"><?php echo $speaker_name; ?></span>-->
                                       <span><span class="speaker_name"><?php echo $speaker_name_only; ?></span>
                                        <span class="speaker_title"><br><?php echo $speaker_title; ?></span>
                                        <span class="speaker_company"><br><?php echo $speaker_company; ?></span></span>
                                        </span>
										<span class="hidden speaker_title"><?php echo $speaker_name; ?></span>
										<span class="hidden desc"><?php echo apply_filters('the_content', get_post_field('post_content', $speaker_id)); ?></span>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php if (!empty($registration_code)) { ?>
                <div>
                    <h2 class="text-center"><?php echo $registration_title; ?></h2>
                    <div>
                        <?php echo $registration_code; ?>
                    </div>
                    <p>
                        <?php echo get_post_meta(get_the_ID(), 'session_registration_text', true); ?>
                    </p>
                </div>
            <?php } ?>
        </div>
        <?php
    endwhile;
endif;
?>

<?php
get_footer();