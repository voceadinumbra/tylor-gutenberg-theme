<?php 
	get_header();
	$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); 
?>
	<div class="heading">	
		<div class="container">
			<h1><?php 
				if ($term)
					echo $term->name; 
				else
					echo __("Sponsors", "tyler-child");
			?></h1>
		</div>
	</div>
	<?php
	// show a location selection if on exhibitors page
	$locations = get_terms(array(
		'taxonomy' => 'session-location',
		'hide_empty' => true,
	));
	if ($locations)
	{
		$locations_arr = array();
		foreach($locations as $location)
			$locations_arr[$location->term_taxonomy_id] = $location->name;
	}
	if (get_query_var('term') == "exhibitors" or get_query_var('term') == "meeting-space") :
	?>
<!--	<div class="container">	
		<ul class="nav nav-tabs pull-right">
			<li class="active">
				<a href="javascript:void(0)"><?php _e("Filter by location", "tyler-child"); ?></a>
				<ul class="">
					<li><a href="#" class="sponsor-location" data-location="0"><?php _e("All", "tyler-child"); ?></a></li>
				<?php
					if ($locations)
					{
						foreach($locations as $location)
							echo "<li><a href='#' class='sponsor-location' data-location='" . $location->term_taxonomy_id . "'>" . $location->name . "</a></li>";
					}		
				?>
				</ul>
			</li>
		</ul>
		<div class="after-nav-tabs"></div>
	</div> -->
<?php	
	endif;
	 			if( get_query_var('term') == "meeting-space" ) {
   			  echo '<div class="container"><div style="background-color: #efefef; padding: 15px; margin-bottom:13px;"><p style="font-style: italic; text-align: center;">A limited number of private meeting spaces are available for reservation. <br />Please <a style="text-decoration: underline;" href="https://2024.smallsatshow.com/exhibition-and-sponsorship/">contact our exhibitor &amp; sponsor sales team</a> for availability and pricing</p></div></div>';
 			} 
	
	while (have_posts()) : the_post(); 
		$exhibitor_location = null;
		//if (get_query_var('term') == "exhibitors") 
			$exhibitor_location = get_post_meta(get_the_ID(), "sponsor_location");
?>
	<div class="sponsor-row" data-location="<?php if ($exhibitor_location) echo $exhibitor_location[0]; ?>">
		<div class="container">
			<h2><?php the_title(); ?></h2><br clear="all" />
			<?php
			if (get_query_var('term') == "exhibitors"){
			$booth_number = get_field( "booth_number");
			if( $booth_number ) {
  			  echo "<h3>Booth $booth_number</h3>";
			} 
			}
 				if (get_query_var('term') == "meeting-space"){
 			$meeting_space = get_field( "meeting_space");
 			if( $meeting_space ) {
   			  echo "<h3>$meeting_space</h3>";
 			} 
 			}
			?>
		</div>
		<div class="container clearfix">
			<?php
			
				if (!empty($exhibitor_location[0]))
					echo "<span class='sponsor_location'>" . __("Location", "tyler-child") . "</span>: " . $locations_arr[$exhibitor_location[0]];
				the_post_thumbnail('medium', array('title' => get_the_title(), 'class' => 'img-sponsor'));
			?>
			<p>
				<?php the_excerpt(); ?>
			</p>
			<p>
			<a href="<?php the_permalink(); ?>">Read More</a>
			</p>
			
		</div><!--.container-->
	</div><!--.sponsor-row-->
<?php endwhile; // end of the loop. ?>

<?php get_footer() ?>