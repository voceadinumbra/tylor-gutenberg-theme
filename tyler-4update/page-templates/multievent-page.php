<?php
 /**
 * Template Name: Events Page
 *
 * @package Event Framework
 * @since 1.0.0
 */
 
get_header(); 

// If Multievent page sidebar is active then apply class
$event_body_class = null;
if ( is_active_sidebar('multievent-page-sidebar') ) {
	$event_body_class = 'col-xs-12 col-lg-8';	
}
?>
    <div class="heading">
        <div class="container">
            <h1><?php the_title() ?></h1>

            <div class="nav">
                <?php previous_post_link('%link', '<i class="icon-angle-left"></i>') ?>
                <?php next_post_link('%link', '<i class="icon-angle-right"></i>') ?>
            </div>
        </div><!-- end .container -->
    </div> <!-- end .heading -->
	
    <div class="container">
        <div class="row">
            <div class="<?php echo $event_body_class; ?>">
            	<?php
            	$event_loc_taxonomy  = 'multievent-location';
            	$event_args =  array(
            							'posts_per_page'	=>	5,
            							'orderby'			=> 'post_date',
										'order'            	=> 'DESC',
										'post_type'        	=> 'multievent',
										'post_status'      	=> 'publish'
            						);
            	$event_posts = get_posts( $event_args );
            	
            	if( !empty($event_posts) ) {
            		foreach ( $event_posts as $event ) {
            			
            			// Getting Event link
            			$event_link = get_permalink( $event->ID );
            			
            			// Getting featured image
            			$event_img_url = '';
            			if (has_post_thumbnail( $event->ID ) ) {
            				$event_img_url = wp_get_attachment_url( get_post_thumbnail_id($event->ID) );
            			}
            			
            			// Getting post terms with html
						$event_terms_list = get_the_term_list( $event->ID, $event_loc_taxonomy, '<li>', '</li><li>', '</li>' );
            	?>
            		<div class="mevt-artical-wrp-main">
            			<?php if( !empty($event_terms_list) ) { ?>
        				<ul class="mevt-lc-terms">
        					<?php echo $event_terms_list; ?>
        				</ul>
        				<div class="mevt-clear"></div>
        				<?php } ?>
        				
            			<div class="mevt-artical-wrp">
            				<div class="mevt-artical-head">
            					<h2><a href="<?php echo $event_link; ?>" title="<?php echo ucfirst($event->post_title); ?>"><?php echo ucfirst($event->post_title); ?></a></h2>
            					<p class="mevt-artical-time"><?php echo __('When : ', 'tyler').' '.date_i18n( 'F j, Y', strtotime( $event->post_date ) ); ?></p>
            				</div>
            				
            				<div class="mevt-artical-body">
            					<?php if( !empty($event_img_url) ) { ?>
            					<a href="<?php echo $event_link; ?>">
            						<img src="<?php echo $event_img_url; ?>" alt="<?php _e('Event Image', 'tyler'); ?>" />
            					</a>
            					<?php } ?>
            				</div>
            				
            				<div class="mevt-artical-footer">
        						<a class="mevt-ftr-link mevt-share" href="javascript:void(0);"><?php _e('SHARE', 'tyler'); ?></a>
								<a class="mevt-ftr-link mevt-more" href="<?php echo $event_link; ?>"><?php _e('MORE', 'tyler'); ?></a>
            				</div>
            			</div><!-- end .mevt-artical-wrp -->
            		</div><!-- end .mevt-artical-wrp-main -->
            <?php	}
            	} // End of if
            ?>
            <div class="mevt-clear"></div>
            
                <?php the_post_thumbnail(null, array('class' => 'img-rounded')); ?>
                <?php the_content(); ?>
                <hr style="margin-top: 0.25em" />
                <span class="share pull-right" style="margin: -20px 0 20px;">
                    <!-- AddThis Button BEGIN -->
                    <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=xa-529a404744177ed4"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-529a404744177ed4"></script>
                    <!-- AddThis Button END -->
                </span>
            </div>
            
            <?php if ( is_active_sidebar('multievent-page-sidebar') ) { ?>
            <div class="col-lg-4 sidebar visible-lg">
            	<ul>
				    <?php dynamic_sidebar('multievent-page-sidebar'); ?>
				</ul>
            </div>
            <?php } ?>
            
        </div><!-- end .row -->
    </div><!-- end .container -->
    
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/multievent-page.css">
    
<?php get_footer(); ?>