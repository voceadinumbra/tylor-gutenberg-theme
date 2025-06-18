<?php
/*
 * Template Name: Schedule Session One
 *
 * @package WordPress
 * @subpackage Tyler
 */
?>
<?php get_header() ?>

<?php
$session_dates = sandeep_get_session_dates("session");
$session_tracks = sandeep_get_terms_for_post_type('session-track',"session");
$session_locations = sandeep_get_terms_for_post_type('session-location',"session");

?>

<?php while (have_posts()) : the_post(); ?>
    <div class="heading">
        <div class="container">
            <h1><?php echo esc_html(get_the_title()); ?></h1>
        </div>
    </div>
    <div class="container">
        <?php the_content(); ?>
        
        <div class="loader-img">
            <img alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" width="32" height="32" align="center" />
        </div>
        <div class="schedule">
        <div class="track_name">&nbsp;</div>
            <ul class="nav nav-tabs pull-right">
                <?php if (!empty($session_tracks)) { ?>
                <li>
                    <a href="javascript:void(0)"><?php _e('Filter by track', 'tyler'); ?></a>
                    
                        <ul>
                            <li><a href="#" data-track="0"><?php _e('All', 'tyler'); ?></a></li>
                            <?php
                            foreach ($session_tracks as $session_track) {
                                ?>
                                <li><a href="#" data-track="<?php echo $session_track->term_id; ?>" data-workshop=""><?php echo $session_track->name; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>

                </li>
                <?php } ?>
                <?php if (!empty($session_locations)) { ?>
                <li>
                    <a href="javascript:void(0)"><?php _e('Filter by location', 'tyler'); ?></a>
                    
                        <ul>
                            <li><a href="#" data-location="0"><?php _e('All', 'tyler'); ?></a></li>
                            <?php
                            foreach ($session_locations as $session_location) {
                                ?>
                                <li><a href="#" data-location="<?php echo $session_location->term_id; ?>" ><?php echo $session_location->name; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    
                </li>
                <?php } ?>
                <li class="active">
                    <a href="javascript:void(0)" data-timestamp="0"><?php _e('Filter by days', 'tyler'); ?></a>
                    <?php if (!empty($session_dates)) { ?>
                        <ul>
                            <li><a href="#" data-timestamp="0"><?php _e('All', 'tyler'); ?></a></li>
                            <?php foreach ($session_dates as $session_date) { ?>
                            <?php if ($session_date->meta_value != "1580688000") { ?>
                                <li><a href="#" data-timestamp="<?php echo $session_date->meta_value; ?>"><?php echo date_i18n(get_option('date_format'), $session_date->meta_value); ?></a></li>
                            <?php }} ?>
                        </ul>
                    <?php } ?>
                </li>
            </ul>
            
            <div class="sessions list">
            </div>
        </div>
    </div>
<?php endwhile; // end of the loop. ?>
<script type="text/javascript">
    var session_type="session";
</script>
<?php get_footer() ?>