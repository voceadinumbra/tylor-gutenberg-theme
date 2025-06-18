<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
    <?php $categories = get_the_category(); ?>
    <div class="heading">
        <div class="container">
            <h1>
				<?php the_title() ?>
			</h1>
            <div class="nav">
                <?php previous_post_link('%link', '<i class="icon-angle-left"></i>') ?>
                <?php if ($categories && count($categories) > 0) { ?>
                    <a href="<?php echo get_category_link($categories[0]); ?>" title="All"><i class="icon-th-large"></i></a>
                <?php } ?>
                <?php next_post_link('%link', '<i class="icon-angle-right"></i>') ?>
            </div>
        </div>
    </div>
    <div class="container">
			<?php
			$booth_number = get_field( "booth_number");
			$meeting_space = get_field( "meeting_space");
			if( $booth_number && $meeting_space ) {
  			  echo "<h3>Booth $booth_number & $meeting_space</h3>";
			} 
			elseif ($booth_number){
  			  echo "<h3>Booth $booth_number</h3>";
			} 
			elseif ($meeting_space){
  			  echo "<h3>$meeting_space</h3>";
			} 
			?>
        <div class="row">
            <div class="col-xs-12 col-lg-12">
                <?php 
					the_post_thumbnail('medium', array('title' => get_the_title(), 'class' => 'img-sponsor'));
				?>
                <?php 
					$post_meta_data = get_post_custom();
					if ($post_meta_data)
					{
						$location_id = $post_meta_data['sponsor_location'][0];
						if ($location_id)
						{
							$location = get_term_by("id", $location_id, "session-location");
							if ($location)
								echo "<span class='sponsor_location'>" . __("Location", "tyler-child") . "</span>: " . $location->name;
						}
					}
					the_content(); 
				?>
               <!-- <span class="share pull-right" style="margin: -20px 0 20px;">-->
                    <!-- AddThis Button BEGIN -->
                    <!--  <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=xa-529a404744177ed4"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-529a404744177ed4"></script>-->
                    <!-- AddThis Button END -->
                <!--  </span>-->
               <?php //comments_template('', true); ?>
            </div>
        </div>
    </div>
<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>