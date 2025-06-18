<!-- SESSIONS -->
<div id="tile_schedule" class="container widget">
	<h2><?php echo stripslashes($args['title']); ?></h2><?php 
	
	if ($args['full_schedule_page'] && count($args['full_schedule_page']) > 0) { ?>
		
		<a href="<?php echo get_permalink($args['full_schedule_page'][0]->ID);?>" class="btn btn-primary btn-header pull-right hidden-xs">
			<?php echo stripslashes($args['viewfulltext']); ?>
		</a><?php 
		
	}?>
	
	<h3><?php echo stripslashes($args['subtitle']); ?></h3>
	<div class="sessions carousel slide" data-ride="carousel" data-interval="false" id="sessions-carousel">
		<ol class="carousel-indicators"><?php 
			
			for ($i = 0; $i < count($args['schedule_chunks']); $i++) { ?>
				<li data-target="#sessions-carousel" data-slide-to="<?php echo $i; ?>" <?php if($i == 0) echo 'class="active"'; ?>></li><?php 
			}?>
		</ol>
		<div class="carousel-inner"><?php 
			
			foreach ($args['schedule_chunks'] as $key => $schedule_chunk) { ?>
				
				<div class="item<?php if ($key == 0) echo ' active'; ?>"><?php
					
					foreach ($schedule_chunk as $session) {
						
						$locations = wp_get_post_terms( $session->ID, 'session-location' );
						if ($locations && count($locations) > 0) {
							
							$location = $locations[0];
						} else {
							
							$location = '';
						}
						
						$tracks = wp_get_post_terms( $session->ID, 'session-track', array( 'fields' => 'ids', 'count' => 1 ) );
						if ( $tracks && count( $tracks ) > 0 ) {
							
							$track = $tracks[0];
							$color = EF_Taxonomy_Helper::ef_get_term_meta( 'session-track-metas', $track, 'session_track_color' );
						} else {
							
							$track = '';
							$color = '';
						}
						
						$session_date = get_post_meta( $session->ID, 'session_date', true );
						$date = '';
						if ( ! empty( $session_date ) ) {
							
							$date = date_i18n( get_option( 'date_format' ), $session_date );
						}
						
						$time = get_post_meta( $session->ID, 'session_time', true );
						$end_time = get_post_meta( $session->ID, 'session_end_time', true );
						if ( ! empty( $time ) ) {
							
							$time_parts = explode( ':', $time );
							
							if ( count( $time_parts ) == 2 ) {
								$time = date( get_option( 'time_format' ), mktime( $time_parts[0], $time_parts[1], 0 ) );
							}
						}

						if ( ! empty( $end_time ) ) {
							
							$time_parts = explode( ':', $end_time );
							if ( count( $time_parts ) == 2 ) {
								
								$end_time = date( get_option( 'time_format' ), mktime( $time_parts[0], $time_parts[1], 0 ) );
							}
						}
						
						$speakers_list = get_post_meta( $session->ID, 'session_speakers_list', true );
						if( $speakers_list && count( $speakers_list ) > 0 ) {
							
							$speakers_list = array_slice( $speakers_list, 0, 8 );
						}?>
						
						<div class="session">
							<a href="<?php echo get_permalink($session->ID); ?>" class="session-inner">
								<span class="title" <?php if (isset($color)) echo " style='color:$color;'"; ?>>
									<span class="text-fit"><?php echo get_the_title($session->ID); ?></span>
								</span>
								<span class="desc">
									<?php _e('Location:', 'dxef'); ?> <strong><?php if (isset($location->name)) echo $location->name; else echo $location; ?></strong>
								</span><?php 
								
								if ( ! empty( $date ) ) { ?>
									
									<span class="desc">
										<?php _e('Date:', 'dxef'); ?> <strong><?php echo $date ?></strong>
									</span><?php 
								}
								
								if ( ! empty( $date ) ) { ?>
									<span class="desc">
										<?php _e('Time:', 'dxef'); ?> <strong><?php echo $time; ?> - <?php echo $end_time; ?></strong>
									</span><?php 
								}?>
								
								<span class="speakers-thumbs"><?php
									
									if( $speakers_list && count( $speakers_list ) > 0 ) {
										foreach ($speakers_list as $speaker_id) { ?>
											<span class="speaker <?php if (get_post_meta($speaker_id, 'speaker_keynote', true) == 1) echo ' featured'; ?>"><?php 
												echo get_the_post_thumbnail($speaker_id, 'post-thumbnail', array('title' => get_the_title($speaker_id))); ?>
											</span><?php 
										}
									}?>
								</span><!-- .speakers-thumbs -->
								<span class="more">
									<?php echo stripslashes($args['schedulemoreinfotext']); ?> <i class="icon-angle-right"></i>
								</span>
							</a><!-- a.session-inner -->
						</div><!-- .session --><?php 
					}?>
				</div><!-- .item --><?php 
			}?>
		</div><!-- .carousel-inner -->
	</div><!-- .sessions .carousel .slide --><?php 
	
	if ( $args['full_schedule_page'] && count($args['full_schedule_page']) > 0) { ?>
		
		<div class="text-center visible-xs">
			<a href="<?php echo get_permalink($args['full_schedule_page'][0]->ID); ?>" class="btn btn-primary btn-header">
				<?php echo stripslashes($args['viewfulltext']); ?>
			</a>
		</div><?php
	}?>
</div><!-- .container .widget -->