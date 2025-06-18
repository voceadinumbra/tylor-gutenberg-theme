<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Footer Text Columns Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Footer_Text_Columns_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Footer_Text_Columns_Widget extends WP_Widget {

	/**
	 * Contact Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Footer_Text_Columns_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_footer_text_columns', 'description' => __( 'Shows text columns organized in columns', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_footer_text_columns', $widget_name . __( ' Footer Text Columns', 'dxef' ), $widget_ops );
		
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
		
	    $textcolumnstitle1 = isset( $instance['textcolumnstitle1'] ) ? $instance['textcolumnstitle1'] : '';
	    $textcolumnscontent1 = isset( $instance['textcolumnscontent1'] ) ? $instance['textcolumnscontent1'] : '';
	    $textcolumnstitle2 = isset( $instance['textcolumnstitle2'] ) ? $instance['textcolumnstitle2'] : '';
	    $textcolumnscontent2 = isset( $instance['textcolumnscontent2'] ) ? $instance['textcolumnscontent2'] : '';
	    $textcolumnstitle3 = isset( $instance['textcolumnstitle3'] ) ? $instance['textcolumnstitle3'] : '';
	    $textcolumnscontent3 = isset( $instance['textcolumnscontent3'] ) ? $instance['textcolumnscontent3'] : '';
	
	    $classname = isset( $this->widget_options['classname'] ) ? $this->widget_options['classname'] : ''; 
	    echo stripslashes($args['before_widget']);
	    echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'textcolumnstitle1' => $textcolumnstitle1,
            'textcolumnscontent1' => $textcolumnscontent1,
            'textcolumnstitle2' => $textcolumnstitle2,
			'textcolumnscontent2' => $textcolumnscontent2,
			'textcolumnstitle3' => $textcolumnstitle3, 
			'textcolumnscontent3' => $textcolumnscontent3,
			'classname' => $classname
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
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		/* Set the instance to the new instance. */
		$instance = $new_instance;
		
		/* Input fields */
		$instance['textcolumnstitle1']	= strip_tags( $new_instance['textcolumnstitle1'] );
		$instance['textcolumnscontent1']	= $new_instance['textcolumnscontent1'];
		$instance['textcolumnstitle2']	= strip_tags( $new_instance['textcolumnstitle2'] );
		$instance['textcolumnscontent2']	= $new_instance['textcolumnscontent2'];
		$instance['textcolumnstitle3']	= strip_tags( $new_instance['textcolumnstitle3'] );
		$instance['textcolumnscontent3']	= $new_instance['textcolumnscontent3'];
		
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
		
		$textcolumnstitle1 = isset( $instance['textcolumnstitle1'] ) ? $instance['textcolumnstitle1'] : '';
	    $textcolumnscontent1 = isset( $instance['textcolumnscontent1'] ) ? $instance['textcolumnscontent1'] : '';
	    $textcolumnstitle2 = isset( $instance['textcolumnstitle2'] ) ? $instance['textcolumnstitle2'] : '';
	    $textcolumnscontent2 = isset( $instance['textcolumnscontent2'] ) ? $instance['textcolumnscontent2'] : '';
	    $textcolumnstitle3 = isset( $instance['textcolumnstitle3'] ) ? $instance['textcolumnstitle3'] : '';
	    $textcolumnscontent3 = isset( $instance['textcolumnscontent3'] ) ? $instance['textcolumnscontent3'] : '';?>
	    
	    <em><?php _e('Title 1:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'textcolumnstitle1' ); ?>" value="<?php echo stripslashes($textcolumnstitle1); ?>" />
	    <br /><br />
	    <em><?php _e('Content 1:', 'dxef'); ?></em><br />
	    <textarea id="textcolumnscontent" name="<?php echo $this->get_field_name( 'textcolumnscontent1' ); ?>" class="widefat" rows="10"><?php echo esc_html($textcolumnscontent1); ?></textarea>
	    <br /><br />
	    <em><?php _e('Title 2:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'textcolumnstitle2' ); ?>" value="<?php echo stripslashes($textcolumnstitle2); ?>" />
	    <br /><br />
	    <em><?php _e('Content 2:', 'dxef'); ?></em><br />
	    <textarea id="textcolumnscontent2" name="<?php echo $this->get_field_name( 'textcolumnscontent2' ); ?>" class="widefat" rows="10"><?php echo esc_html($textcolumnscontent2); ?></textarea>
	    <br /><br />
	    <em><?php _e('Title 3:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'textcolumnstitle3' ); ?>" value="<?php echo stripslashes($textcolumnstitle3); ?>" />
	    <br /><br />
	    <em><?php _e('Content 3:', 'dxef'); ?></em><br />
	    <textarea id="textcolumnscontent3" name="<?php echo $this->get_field_name( 'textcolumnscontent3' ); ?>" class="widefat" rows="10"><?php echo esc_html($textcolumnscontent3); ?></textarea>
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
		
	}
}

// Register Widget
register_widget( 'Ef_Footer_Text_Columns_Widget' );