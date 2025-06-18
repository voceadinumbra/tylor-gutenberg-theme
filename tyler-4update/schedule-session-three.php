<?php
/*
 * Template Name: Schedule Session Three
 *
 * @package WordPress
 * @subpackage Tyler
 */
?>
<?php get_header() ?>

<?php
$session_dates = sandeep_get_session_dates("sessionthree");
$session_tracks = sandeep_get_terms_for_post_type('session-track',"sessionthree");
$session_locations = sandeep_get_terms_for_post_type('session-location',"sessionthree");






?>

 <!-- Sessions List -->
            <div class="sessions list">
                <?php
                // Build WP_Query arguments
                $args = [
                    'post_type' => 'sessionthree',
                    'posts_per_page' => 10,
                    'post_status' => 'publish',
                    'tax_query' => [],
                    'meta_query' => [],
                ];

                /*

                // Add track filter from URL parameter
                if (isset($_GET['track']) && intval($_GET['track']) > 0) {
                    $args['tax_query'][] = [
                        'taxonomy' => 'session-track',
                        'field' => 'term_id',
                        'terms' => intval($_GET['track']),
                    ];
                }

                // Add date filter from URL parameter
                if (isset($_GET['timestamp']) && intval($_GET['timestamp']) > 0) {
                    $args['meta_query'][] = [
                        'key' => 'session_date',
                        'value' => intval($_GET['timestamp']),
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ];
                }

                // Set tax_query relation
                if (count($args['tax_query']) > 1) {
                    $args['tax_query']['relation'] = 'AND';
                }

             

                */

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        ?>
                        <div class="session-item">
                            <h3><?php the_title(); ?></h3>
                            <?php the_content(); ?>
                            <?php
                            $tracks = get_the_terms(get_the_ID(), 'session-track');
                            $locations = get_the_terms(get_the_ID(), 'session-location');
                            $session_date = get_post_meta(get_the_ID(), 'session_date', true);
                            ?>
                            <?php if ($tracks && !is_wp_error($tracks)) : ?>
                                <p><strong>Track:</strong> <?php echo esc_html(implode(', ', wp_list_pluck($tracks, 'name'))); ?></p>
                            <?php endif; ?>
                            <?php if ($locations && !is_wp_error($locations)) : ?>
                                <p><strong>Location:</strong> <?php echo esc_html(implode(', ', wp_list_pluck($locations, 'name'))); ?></p>
                            <?php endif; ?>
                            <?php if ($session_date) : ?>
                                <p><strong>Date:</strong> <?php echo esc_html(date_i18n(get_option('date_format'), $session_date)); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php
                    endwhile;
                else :
                    ?>
                    <p><?php esc_html_e('No sessions found for the selected filters.', 'tyler'); ?></p>
                    <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>






<?php while (have_posts()) : the_post(); ?>

    <div class="heading">
        <div class="container">
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
    <div class="container">
        <?php the_content(); ?>
        <p><br /></p>
        <div class="loader-img">
            <img alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" width="32" height="32" align="center" />
        </div>
        <div class="schedule">
        <div style="font-size:22px" class="track_name">&nbsp;</div>
        <div style="font-size:14px;"><i>Tentative Times (This agenda will change prior to event.)</i></div>
            <ul class="nav nav-tabs pull-right">
                <?php if (!empty($session_tracks)) { ?>
                <li>
                    <a href="javascript:void(0)"><?php _e('Filter by track', 'tyler'); ?></a>
                    
                        <ul style="min-width:350px !important;">
                            <li style="font-size:14px;"><a href="#" data-track="0"><?php _e('All', 'tyler'); ?></a></li>
                            <?php
                            foreach ($session_tracks as $session_track) {
                                ?>
                                <li style="font-size:13px;"><a href="#" data-track="<?php echo $session_track->term_id; ?>" data-workshop=""><?php echo $session_track->name; ?></a></li>
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
            <div class="clearfix"></div>
            <div class="sessions list">
            </div>
        </div>
    </div>
<?php endwhile; // end of the loop. ?>
<script type="text/javascript">
    var session_type="sessionthree";
</script>
<?php get_footer() ?>