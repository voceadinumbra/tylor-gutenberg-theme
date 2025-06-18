<?php get_header() ?>

<?php
// Get Theme Options
$ef_options = EF_Event_Options::get_theme_options();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); 
        $single_session_fields = EF_Query_Manager::get_single_session_fields();
        
        extract( $single_session_fields );
        ?>
        <div class="heading">
            <div class="container">
                <h1>
                    <?php the_title() ?>
                </h1>

                <div class="nav">
                    <?php echo get_previous_post_link('%link', '<i class="icon-angle-left"></i>'); ?>
                    <?php if ($full_schedule_page && count($full_schedule_page) > 0) { ?>
                        <a href="<?php echo get_permalink($full_schedule_page[0]->ID); ?>" title="<?php _e('All', 'dxef'); ?>"><i class="icon-th-large"></i></a>
                    <?php } ?>
                    <?php echo get_next_post_link('%link', '<i class="icon-angle-right"></i>'); ?>
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
                    <!-- AddThis Button BEGIN -->
                    <p class="clearfix">
						<?php if( ! empty( $ef_options['ef_add_this_pubid'] ) ) { ?>
	                        <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=<?php echo $ef_options['ef_add_this_pubid']; ?>">
	                            <img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="<?php _e('Bookmark and Share', 'dxef'); ?>" style="border:0"/>
	                        </a>
						<?php } ?>
                    </p>
                    <!-- AddThis Button END -->
                    <div style="padding:1em 0">
					
					<?php
					$full_schedule_page = get_posts( 
						array(
							'post_type'		=> 'page',
							'meta_key'		=> '_wp_page_template',
							'meta_value'	=> 'schedule.php'
						)
					);
					?>
					
                        <?php foreach ($tracks as $track) { ?>
                            <a href="<?php echo get_permalink($full_schedule_page[0]->ID); ?>" class="btn btn-primary" <?php if (!empty($track->color)) echo "style='background-color: $track->color;'"; ?>><?php echo $track->name; ?></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-4 sessions single">
                    <div class="session">
                        <span class="location"><?php _e('Location:', 'dxef'); ?> <strong><?php echo(!empty($locations) ? $locations[0]->name : ''); ?></strong></span>
                        <span class="date"><?php _e('Date:', 'dxef'); ?> <strong><?php echo(!empty($date) ? date_i18n(get_option('date_format'), $date) : ''); ?></strong></span>
                        <span class="time"><?php _e('Time:', 'dxef'); ?> <strong><?php echo $time; ?> - <?php echo $end_time; ?></strong></span>
                        <span class="speakers-thumbs">
                            <?php
                            if (!empty($speakers_list)) {
                                foreach ($speakers_list as $speaker_id) {
                                    ?>
                                    <a href = "<?php echo get_permalink($speaker_id); ?>" class="speaker<?php if (get_post_meta($speaker_id, 'speaker_keynote', true) == 1) echo ' featured'; ?>">
                                        <?php echo get_the_post_thumbnail($speaker_id, 'post-thumbnail', array('title' => get_the_title($speaker_id))); ?>
                                        <span class="name"><span class="text-fit"><?php echo get_the_title($speaker_id); ?></span></span>
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
                <div style="margin-top: 40px">
                    <h2 class="text-center"><?php _e('Register to the session!', 'dxef'); ?></h2>
                    <div style="padding: 1em">
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

<?php get_footer() ?>