<script type="text/javascript">
	jQuery(function() {
		
		// Google Maps Zoom
		var gmap_zoom = <?php echo $args['gmap_zoom'];?>;

		// Widget Explore Scripts
		jQuery('#tile_explore .map').gmap({
			scrollwheel: false,
			zoomControl: true
		}).bind('init', function(ev, map) {
			if(pois && pois.length > 0){
				for(i=0; i<pois.length; i++) {
					var cur_poi = pois[i];
					jQuery('#tile_explore .map').gmap('addMarker', {
						'position': cur_poi.poi_latitude + ',' + cur_poi.poi_longitude,
						'bounds': true,
						'icon' : new google.maps.MarkerImage(poi_marker),
						'text': cur_poi.poi_address
					}).click(function() {
						jQuery('#tile_explore .map').gmap('openInfoWindow', {
							'content': this.text
						}, this);
					});
					jQuery('#tile_explore .map').gmap('option', 'zoom', gmap_zoom);
				}
			}
		});
		
		jQuery('#tile_explore .carousel-inner a[data-lat]').click(function(e){
			e.preventDefault();
			jQuery('#tile_explore .map').gmap('get','map').setOptions({
				'center': new google.maps.LatLng(jQuery(this).attr('data-lat'),jQuery(this).attr('data-lng'))
			});
		});
		
		jQuery('#location-carousel').on('slid.bs.carousel', function () {
			jQuery('#location-carousel .item.active .scrollable').jScrollPane();
		});
	});
</script>

<!-- LOCATION -->
<div id="tile_explore" class="container widget">
	<div class="row location">
		<!-- Explore list -->
		<div class="col-md-4">
			<div class="explore carousel slide" data-ride="carousel" data-interval="false" id="location-carousel">
				<!-- heading -->
				<div class="heading">
					<a href="#location-carousel" data-slide="prev" class="pull-left"><i class="icon-angle-left"></i></a>
					<a href="#location-carousel" data-slide="next" class="pull-right"><i class="icon-angle-right"></i></a>
					<?php echo stripslashes($args['title']); ?>
				</div>
				<!-- Wrapper for slides -->
				<div class="carousel-inner"><?php
					
					$i = 0;
					foreach ( $args['poi_groups'] as $po_group ) {?>
						
						<div class="item<?php if ( $i == 0 ) echo ' active'; ?>">
							<div class="scrollable">
								<div><?php
									$pois	= get_posts(
												array(
														'post_type'		=> 'poi',
														'posts_per_page'=> -1,
														'poi-group'		=> $po_group->slug,
														'orderby'		=> 'menu_order',
														'order'			=> 'ASC'
													)
												);
									echo $po_group->name;?>
									<ul><?php 
										foreach( $pois as $poi ) {?>
											<li>
												<a href="#" data-lat="<?php echo get_post_meta($poi->ID, 'poi_latitude', true); ?>" data-lng="<?php echo get_post_meta($poi->ID, 'poi_longitude', true); ?>">
													<?php echo get_the_title($poi->ID); ?>
												</a>
											</li><?php
										}?>
									</ul>
								</div>
							</div>
						</div><?php
						
						$i++;
					}?>
				</div>
			</div>
		</div>
		<!-- MAP -->
		<div class="col-md-8">
			<div class="map">
				<iframe style="width:100%;height: 100%; margin:0; padding:0; border: none" src="https://maps.google.sk/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Unicorn+Theatre,+Tooley+Street,+London,+United+Kingdom&amp;aq=0&amp;oq=unicorn+the&amp;sll=48.135866,17.115917&amp;sspn=0.334518,0.693512&amp;ie=UTF8&amp;hq=Unicorn+Theatre,&amp;hnear=Tooley+St,+London,+United+Kingdom&amp;t=m&amp;z=16&amp;iwloc=A&amp;output=embed"></iframe>
			</div>
		</div>
	</div>
</div>