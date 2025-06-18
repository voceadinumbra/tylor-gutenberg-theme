<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the sponsors Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Sponsors_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Sponsors_Widget extends WP_Widget {
	
	/**
	 * Sponsors Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Sponsors_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_sponsors', 'description' => __( 'Shows a section displaying sponsors by tier type created in the Sponsors custom post type.', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_sponsors', $widget_name . __( ' Sponsor List', 'dxef' ), $widget_ops );
		
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
	function widget( $args, $instance ) {
		
		$sponsorstitle		= isset( $instance['sponsorstitle'] ) ? $instance['sponsorstitle'] : '';
		$sponsorssubtitle	= isset( $instance['sponsorssubtitle'] ) ? $instance['sponsorssubtitle'] : '';
		$sponsorsbuttontext	= isset( $instance['sponsorsbuttontext'] ) ? $instance['sponsorsbuttontext'] : '';
		$sponsorsbuttonlink	= isset( $instance['sponsorsbuttonlink'] ) ? $instance['sponsorsbuttonlink'] : '';
		
	    echo stripslashes( $args['before_widget'] );
		echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title' => $sponsorstitle,
            'subtitle' => $sponsorssubtitle,
			'sponsorsbuttontext' => $sponsorsbuttontext,
			'sponsorsbuttonlink' => $sponsorsbuttonlink
        ));	    
		echo stripslashes( $args['after_widget'] );
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
	function update( $new_instance, $old_instance ) {
	
        $instance = $old_instance;
		
		/* Set the instance to the new instance. */
		$instance = $new_instance;
		
		/* Input fields */
        $instance['sponsorstitle']		= strip_tags( $new_instance['sponsorstitle'] );
		$instance['sponsorssubtitle']	= strip_tags( $new_instance['sponsorssubtitle'] );
		$instance['sponsorsbuttontext']	= strip_tags( $new_instance['sponsorsbuttontext'] );
		$instance['sponsorsbuttonlink']	= strip_tags( $new_instance['sponsorsbuttonlink'] );
		
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
	function form( $instance ) {
		
        $sponsorstitle		= isset( $instance['sponsorstitle'] ) ? $instance['sponsorstitle'] : '';
        $sponsorssubtitle	= isset( $instance['sponsorssubtitle'] ) ? $instance['sponsorssubtitle'] : '';
        $sponsorsbuttontext	= isset( $instance['sponsorsbuttontext'] ) ? $instance['sponsorsbuttontext'] : '';
        $sponsorsbuttonlink	= isset( $instance['sponsorsbuttonlink'] ) ? $instance['sponsorsbuttonlink'] : '';?>
        
		<em><?php _e('Title:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'sponsorstitle' );?>" value="<?php echo stripslashes($sponsorstitle); ?>"/>
		<br /><br />
		<em><?php _e('Subtitle:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'sponsorssubtitle' );?>" value="<?php echo stripslashes($sponsorssubtitle); ?>"/>
		<br /><br />
		<em><?php _e('Button text:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'sponsorsbuttontext' );?>" value="<?php echo stripslashes($sponsorsbuttontext); ?>"/>
		<br /><br />
		<em><?php _e('Button url:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'sponsorsbuttonlink' );?>" value="<?php echo stripslashes($sponsorsbuttonlink); ?>"/><?php 
		
	}
}

// Register Widget
register_widget( 'Ef_Sponsors_Widget' );