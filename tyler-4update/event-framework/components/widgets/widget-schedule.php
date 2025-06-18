<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Session Schedule Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Schedule_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Schedule_Widget extends WP_Widget {
	
	/**
	 * Schedule Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Schedule_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_schedule', 'description' => __( 'Displays Event Schedule widget.', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_schedule', $widget_name . __( ' Event Schedule', 'dxef' ), $widget_ops );
		
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
		$scheduletitle			= isset( $instance['scheduletitle'] ) ? $instance['scheduletitle'] : '';
		$schedulesubtitle		= isset( $instance['schedulesubtitle'] ) ? $instance['schedulesubtitle'] : '';
		$schedulemoreinfotext	= isset( $instance['schedulemoreinfotext'] ) ? $instance['schedulemoreinfotext'] : '';
		$scheduleviewfulltext	= isset( $instance['scheduleviewfulltext'] ) ? $instance['scheduleviewfulltext'] : '';
		
		$schedule_args	= array(
								'post_type'			=> 'session',
								'posts_per_page'	=> 9,
								'meta_key'			=> 'session_home',
								'meta_value'		=> 1,
								'orderby'			=> 'menu_order',
								'order'				=> 'ASC',
								//'suppress_filters'	=> false
							);
		
		add_filter( 'posts_where', array( $this, 'ef_home_schedule_where' ) );
		
		$schedule_chunks	= array_chunk( get_posts( $schedule_args ), 3 );
		$schedule_chunks	= apply_filters( 'multievent_filter_posts_ef_schedule', $schedule_chunks, $schedule_args, $instance );
		
		remove_filter( 'posts_where', array( $this, 'ef_home_schedule_where' ) );
		
		$full_schedule_page = get_posts( 
									array(
										'post_type'		=> 'page',
										'meta_key'		=> '_wp_page_template',
										'meta_value'	=> 'schedule.php'
									)
								);
		
	    echo stripslashes( $args['before_widget'] );
		echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title' => $scheduletitle,
            'subtitle' => $schedulesubtitle,
            'viewfulltext' => $scheduleviewfulltext,
            'full_schedule_page' => $full_schedule_page,
			'schedule_chunks' =>$schedule_chunks,
			'schedulemoreinfotext'=> $schedulemoreinfotext
        ));
		?>
	    
		<?php
	    
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
        $instance['scheduletitle']			= strip_tags( $new_instance['scheduletitle'] );
		$instance['schedulesubtitle']		= strip_tags( $new_instance['schedulesubtitle'] );
		$instance['schedulemoreinfotext']	= $new_instance['schedulemoreinfotext'];
		$instance['scheduleviewfulltext']	= strip_tags( $new_instance['scheduleviewfulltext'] );
		
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
		
        $scheduletitle			= isset( $instance['scheduletitle'] ) ? $instance['scheduletitle'] : '';
        $schedulesubtitle		= isset( $instance['schedulesubtitle'] ) ? $instance['schedulesubtitle'] : '';
        $schedulemoreinfotext	= isset( $instance['schedulemoreinfotext'] ) ? $instance['schedulemoreinfotext'] : '';
        $scheduleviewfulltext	= isset( $instance['scheduleviewfulltext'] ) ? $instance['scheduleviewfulltext'] : '';?>
        
		<em><?php _e('Title:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'scheduletitle' );?>" value="<?php echo stripslashes($scheduletitle); ?>" />
		<br /><br />
		<em><?php _e('Subtitle:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'schedulesubtitle' );?>" value="<?php echo stripslashes($schedulesubtitle); ?>" />
		<br /><br />
		<br /><br />
		<em><?php _e('"More info" Text:', 'dxef'); ?></em><br/>
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'schedulemoreinfotext' );?>" value="<?php echo esc_html($schedulemoreinfotext); ?>"/>
		<br /><br />
		<em><?php _e('"View full schedule" Button Text:', 'dxef'); ?></em><br/>
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'scheduleviewfulltext' );?>" value="<?php echo stripslashes($scheduleviewfulltext); ?>"/><?php 
		
	}
	
	/**
	 * Order Filter Of Schedule
	 * 
	 * Handle to filter for ordering
	 * sheduling event
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	public function ef_home_schedule_where( $where ) {
		
		return $where . ' AND menu_order > 0';
	}
}

// Register Widget
register_widget( 'Ef_Schedule_Widget' );