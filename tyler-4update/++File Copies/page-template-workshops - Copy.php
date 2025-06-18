<?php
/*
 * Template Name: Workshops
 *
 */

get_header() ?>

<?php
    $session_dates = EF_Session_Helper::ef_get_session_dates();
    $session_tracks = get_terms('session-track');
    $session_locations = get_terms('session-location');
?>

<div class="heading">
    <div class="container">
        <h1><?php echo esc_html(get_the_title()); ?></h1>
    </div>
</div>
<div class="container">
    <?php
    while ( have_posts() ) :
        the_post();

        the_content();

    endwhile; // End of the loop.
    wp_reset_postdata();
    ?>

<?php 
    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'session' ),
        'post_status'            => array( 'publish' ),
        'nopaging'               => true,
        'meta_query'     => array(
                array(
                    'key'     => 'session_workshop',
                    'compare' => 'EXISTS',
                ),
            ),
        'meta_key'       => 'session_date', // The meta key used for ordering
        'orderby'        => 'meta_value',
        'order'          => 'ASC' // Change to 'DESC' if you want descending order
        
    );
    $services = new WP_Query( $args );
    $session_dates_previous = '';
?>        
            
            
            
    <div class="loader-img">
        <img alt="<?php esc_attr_e('Loading', 'textdomain'); ?>" 
            src="<?php echo esc_url(get_template_directory_uri() . '/images/ajax-loader.gif'); ?>" 
            width="32" height="32" />
    </div>

            <div class="schedule">
                
                
                <div class="sessions list">
                <?php 
                $isfirst = true;
                if ( $services->have_posts() ) {
                while ( $services->have_posts() ) {
                $services->the_post(); 
                
                
                $session_workshop = get_post_meta($post->ID, 'session_workshop', true);
                $session_date = get_post_meta($post->ID, 'session_date', true);
                
                $session_time = get_post_meta($post->ID, 'session_time', true);
                $session_end_time = get_post_meta($post->ID, 'session_end_time', true);
                $s_start_time = strtotime($session_time);
                $s_end_time = strtotime($session_end_time);

                $sponsor_logo = get_post_meta($post->ID, 'session_sponsor', true);
				
				//$meta = get_post_meta( $post->ID );


                $term_obj_list = get_the_terms( $post->ID, 'session-track' );

                $speakers_list = get_post_meta($post->ID, 'session_speakers_list', true);
                $speakers      = array();
                
                if ($speakers_list && count($speakers_list) > 0) {
                    foreach ($speakers_list as $speaker_id)
                        $speakers[] = array(
                            'post_title' => get_the_title($speaker_id),
                            'featured'   => get_post_meta($speaker_id, 'speaker_keynote', true),
                            'url'        => get_permalink($speaker_id),
                            'post_image' => get_the_post_thumbnail($speaker_id, array(54, 54), array('alt' => get_the_title($speaker_id))),
                            'company'	 => get_post_meta($speaker_id, 'company_name', true),
                            'speaker_title'	 => get_post_meta($speaker_id, 'speaker_title', true),
                            'speaker_moderator' => get_post_meta($speaker_id, 'speaker_moderator', true),
                            'desc'	 => apply_filters('the_content', get_post_field('post_content', $speaker_id))
                        );
                }
                
                $termcolor = '';
                if($term_obj_list[0]->slug == 'keynotes') {
                    $termcolor = 'color:#f50a0a';
                } else if($term_obj_list[0]->slug == 'market-brief' OR $term_obj_list[0]->slug == 'tech-brief') {
                    $termcolor = 'color:#0042FF';
                } else {
                    $termcolor = '';
                }
                ?>
                <?php if($session_date != $session_dates_previous) { ?>
                    <div class="followWrap" style="height: 60px;">
                        <div class="day-floating">
                            <span><?php echo date_i18n(get_option('date_format'), $session_date); ?></span>
                        </div>
                    </div>
                <?php } ?>
                <div class="session">
                    <span class="time"><?php echo date("g:i A", $s_start_time); ?> - <?php echo date("g:i A", $s_end_time); ?></span>
                    <div class="session-inner">
                                            <?php
                        
                        if($sponsor_logo != ''){
                        //echo "<img src=\"$sponsor_logo\" />";
                        //$logo = get_the_post_thumbnail($sponsor_logo);
                        $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $sponsor_logo ), "size" );
                        echo "<img class=\"session_sponsor\" src=\"$thumbnail[0]\">";
                        }
                        else{
                        echo "<a href=\""; the_permalink(); echo"\" class=\"more\">More info <i class=\"icon-angle-right\"></i></a>";
						} 
                        ?>
                    
                    <?php
                    if($sponsor_logo == '') {
                    echo "<a href=\""; the_permalink(); echo"\" class=\"title\" style=\"$termcolor\"> ";
                    echo "<span>"; the_title(); echo "</span>";
                    echo "</a>";
                    }
                    else{
                    echo "<span class=\"title\">"; the_title(); echo"</span>";
                    }
                ?>
                        <span class="location">
                        </span>
                        <span class="speakers-thumbs">

                        <?php for($i=0; $i < count($speakers); $i++ ) {  
                            
                            ?>
                            
                            <a href="<?php echo $speakers[$i]["url"]; ?>" class="speaker">
                            <?php echo $speakers[$i]['post_image']; ?>
                                <span class="name">
                                    <span class="text-fit">
                                        <span class="speaker_name"><?php echo $speakers[$i]["post_title"]; ?></span>
                                        <span class="speaker_title"><br><?php echo $speakers[$i]["speaker_title"]; ?></span>
                                        <span class="speaker_company"><br><?php echo $speakers[$i]["company"]; ?></span>
                                    </span>
                                </span>
                                <span class="hidden speaker_title">
                                    <span class="speaker_name"><?php echo $speakers[$i]["post_title"]; ?></span>
                                    <span class="speaker_title"><br><?php echo $speakers[$i]["speaker_title"]; ?></span>
                                    <span class="speaker_company"><br><?php echo $speakers[$i]["company"]; ?></span>
                                </span>
                                <span class="hidden desc"><p><?php echo $speakers[$i]["bio"]; ?></p>
                                <span class="name">
                                    <span class="text-fit">
                                        <span class="speaker_name"><?php echo $speakers[$i]["post_title"]; ?></span>
                                        <span class="speaker_title"><br><?php echo $speakers[$i]["speaker_title"]; ?></span>
                                        <span class="speaker_company"><br><?php echo $speakers[$i]["company"];?></span>
                                    </span>
                                </span>
                            </a>
                        <?php } ?>
                        

                        </span>

                    </div>
                </div>
                <?php 
                $isfirst = false;
                $session_dates_previous = $session_date; 
                }
                    } else {
                        // no posts found
                    } wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    

<?php get_footer() ?>