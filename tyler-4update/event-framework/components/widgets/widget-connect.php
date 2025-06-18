<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Register the connect Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Connect_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Connect_Widget extends WP_Widget {

    /**
     * Speakers Widget setup.
     * 
     * @package Event Framework
     * @since 1.0.0
     */
    function Ef_Connect_Widget() {

        $widget_name = EF_Framework_Helper::get_widget_name();

        /* Widget settings. */
        $widget_ops = array('classname' => 'ef_connect', 'description' => __('Shows a section displaying icons for the social media links filled out in the Customizer', 'dxef'));

        /* Create the widget. */
        $this->WP_Widget('ef_connect', $widget_name . __(' Social Media Links', 'dxef'), $widget_ops);
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

        $connecttitle = isset($instance['connecttitle']) ? $instance['connecttitle'] : '';

        // Get Theme Options
        $ef_options = EF_Event_Options::get_theme_options();

        $esc_url_protocols = array('http', 'https', 'feed');

        echo stripslashes($args['before_widget']);

        echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title'             => $connecttitle,
            'ef_options'        => $ef_options,
            'esc_url_protocols' => $esc_url_protocols
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
        $instance['connecttitle'] = strip_tags($new_instance['connecttitle']);

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
        $connecttitle = isset($instance['connecttitle']) ? $instance['connecttitle'] : '';
        ?>
        <em><?php _e('Title:', 'dxef'); ?></em><br />
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('connecttitle'); ?>" value="<?php echo stripslashes($connecttitle); ?>" />
        <br /><br />
        <input type="hidden" name="submitted" value="1" /><?php
    }

}

// Register widget
register_widget('Ef_Connect_Widget');
