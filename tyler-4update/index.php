<?php
get_header('home');

// Get Theme Options
//$ef_options = get_option( 'eventframework' );
$ef_options = EF_Event_Options::get_theme_options();

// Loop to allow EE to print necessary markup, specifically used by ESPRESSO_TICKET_SELECTOR shortcode to show "Select Ticket Quantity" alert
if(function_exists('espresso_version')){
    if(have_posts()){
        while(have_posts()){
            the_post();
        }
    }
}

?>
<!-- LANDING - BIG PICTURE -->
<div id="hero_video">
<div class="container widget">
    <div class="landing">
        <div class="bg"<?php if (isset($ef_options['ef_hero'])) { ?> style="background-image: url('<?php echo $ef_options['ef_hero']; ?>')"<?php } ?>></div>
        <h1><span class="text-fit" <?php if (isset($ef_options['ef_title_color'])) echo 'style="color:' . $ef_options['ef_title_color'] . '"'; ?>><span style="text-shadow: 2px 2px 5px black;"><?php if (isset($ef_options['ef_herotitle'])) {
    echo stripslashes($ef_options['ef_herotitle']);
} ?></span></span></h1>
        <p class="lead text-fit" <?php if (!empty($ef_options['ef_subtitle_color'])) echo 'style="color:' . $ef_options['ef_subtitle_color'] . '"'; ?>><span style="text-shadow: 2px 2px 5px black;">
        <?php if (isset($ef_options['ef_herotagline'])) {
            echo esc_attr(stripslashes($ef_options['ef_herotagline']));
        } ?>
        </span></p>
        <?php
        $widget_ef_registration = get_option('widget_ef_registration');

        if (is_active_widget(false, false, 'ef_registration') && is_array($widget_ef_registration)) {
            foreach ($widget_ef_registration as $key => $reg_widget) {
                if (empty($reg_widget)) {
                    unset($widget_ef_registration[$key]);
                    update_option('widget_ef_registration', $widget_ef_registration);
                }
                if (isset($reg_widget['registrationshowcalltoaction']) && $reg_widget['registrationshowcalltoaction'] == 1) {
                    ?>
                    <!--<a href="https://satnews.regfox.com/smallsat-symposium-2020" class="btn btn-lg btn-secondary"><?php _e('REGISTER NOW', 'tyler') ?></a>-->
            <?php
            break;
        }
    }
}
?>
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <i class="icon-calendar"></i>
                    <div class="box-inner">
                        <div>
                            <span class="sub"><?php _e('WHEN', 'tyler') ?></span>
                            <span class="title"><strong style="font-weight: 400;"><?php if (isset($ef_options['ef_eventdate'])) {
    echo stripslashes($ef_options['ef_eventdate']);
} ?></span>
</strong>
                            <span class="desc"> <?php if (isset($ef_options['ef_eventstartingtime'])) {
    echo stripslashes($ef_options['ef_eventstartingtime']);
} ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <i class="icon-map-marker"></i>
                    <div class="box-inner">
                        <div>
                            <span class="sub"><?php _e('WHERE', 'tyler'); ?></span>
                            <span class="title"><?php if (isset($ef_options['ef_eventcitycountry'])) {
    echo stripslashes($ef_options['ef_eventcitycountry']);
} ?></span>
                            <span class="desc"><?php if (isset($ef_options['ef_eventlocation'])) {
    echo stripslashes($ef_options['ef_eventlocation']);
} ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<?php
if (is_active_sidebar('homepage'))
    dynamic_sidebar('homepage');
?>

<?php get_footer(); ?>
