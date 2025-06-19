<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Register the Event Description Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Explore_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Explore_Widget extends WP_Widget {

    /**
     * Contact Widget setup.
     * 
     * @package Event Framework
     * @since 1.0.0
     */
    function Ef_Explore_Widget() {

        $widget_name = EF_Framework_Helper::get_widget_name();

        /* Widget settings. */
        $widget_ops = array('classname' => 'ef_explore', 'description' => __('Shows a section displaying points of interest & maps', 'dxef'));

        /* Create the widget. */
        $this->WP_Widget('ef_explore', $widget_name . __(' Points of Interest', 'dxef'), $widget_ops);
    }

    /**
     * Output of Widget Content
     * 
     * Handle to outputs the
     * content of the widget
     * 
     * @package Event Framework
     * @since 1.0.0
     */
    function widget($args, $instance) {
        $ef_options      = EF_Event_Options::get_theme_options();
        $google_maps_key = '';
        if (!empty($ef_options['ef_googlemaps_key'])) {
            $google_maps_key = $ef_options['ef_googlemaps_key'];
        }
        $exploretitle = isset($instance['exploretitle']) ? $instance['exploretitle'] : '';
        $gmap_zoom    = isset($instance['gmap_zoom']) ? $instance['gmap_zoom'] : '';
        $gmap_zoom    = ( is_numeric($gmap_zoom) ? $gmap_zoom : 13 );

        // Google Map Widget Srcipts
        wp_enqueue_script('ef-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_key, false, false, true);
        wp_enqueue_script('ef-jquery-ui-map', EF_ASSETS_URL . '/js/jquery.ui.map.full.min.js', array('jquery'), false, true);

        echo stripslashes($args['before_widget']);
        echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title'      => $exploretitle,
            'gmap_zoom'  => $gmap_zoom,
        ));
        echo stripslashes($args['after_widget']);
    }

    /**
     * Update Widget Setting
     * 
     * Handle to updates the widget control options
     * for the particular instance of the widget
     * 
     * @package Event Framework
     * @since 1.0.0
     */
    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        /* Set the instance to the new instance. */
        $instance = $new_instance;

        /* Input fields */
        $instance['exploretitle'] = strip_tags($new_instance['exploretitle']);
        $instance['gmap_zoom']    = strip_tags($new_instance['gmap_zoom']);

        return $instance;
    }

    /**
     * Display Widget Form
     * 
     * Displays the widget
     * form in the admin panel
     * 
     * @package Event Framework
     * @since 1.0.0
     */
    function form($instance) {

        $exploretitle = isset($instance['exploretitle']) ? $instance['exploretitle'] : '';
        $gmap_zoom    = isset($instance['gmap_zoom']) ? $instance['gmap_zoom'] : '';
        ?>

        <em><?php _e('Widget Title:', 'dxef'); ?></em><br />
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('exploretitle'); ?>" value="<?php echo $exploretitle; ?>"/>
        <em><?php _e('Google Map Zoom:', 'dxef'); ?></em><br />
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('gmap_zoom'); ?>" value="<?php echo $gmap_zoom; ?>" placeholder="From 1 to 21"/>
        <br /><br />
        <input type="hidden" name="submitted" value="1" /><?php
    }

}



/**
 * Display Widget Form
 * 
 * Displays the widget
 * form in the admin panel
 * 
 * @package Event Framework
 * @since 1.0.0
 */
function tyler_frontend_scripts() {
    ?>

    <script type="text/javascript">

        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';<?php
    // Get Theme Options
    $ef_options = EF_Event_Options::get_theme_options();

    $color_scheme = empty($ef_options['ef_color_palette']) ? 'basic' : $ef_options['ef_color_palette'];

  

   
    ?>

        var contact_missingfield_error = <?php echo json_encode(__('Sorry! You\'ve entered an invalid email.', 'tyler')); ?>;
        var contact_wrongemail_error = <?php echo json_encode(__('This field must be filled out.', 'tyler')); ?>;
    </script><?php
}

//add action for frontend scripts
add_action('wp_head', 'tyler_frontend_scripts');

//Register Widget

function function_Ef_Explore_Widget() {
	return register_widget( 'Ef_Explore_Widget' );
}
add_action( 'widgets_init', 'function_Ef_Explore_Widget');
