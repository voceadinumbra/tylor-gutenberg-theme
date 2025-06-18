<!-- SPONSORS -->
<div id="tile_sponsors" class="container widget">
	
	<h2><?php echo stripslashes($args['title']); ?></h2>
	<a href="<?php echo stripslashes( $args['sponsorsbuttonlink'] ); ?>" class="btn btn-primary btn-header pull-right hidden-xs">
		<?php echo stripslashes($args['sponsorsbuttontext']); ?>
	</a>
	<h3><?php echo stripslashes($args['subtitle']); ?></h3>
	<br/><?php
	
	$categories	= get_terms( 'sponsor-tier' );
	
	// tiers sorting
	$new_tiers = array();
	foreach ($categories as $tier) {
		$tier->order = EF_Taxonomy_Helper::ef_get_term_meta('sponsor-tier-metas', $tier->term_id, 'sponsor_tier_order');
		$new_tiers[$tier->order] = $tier;
	}
	ksort($new_tiers, SORT_NUMERIC);
	$categories = $new_tiers;
	// -------------

	foreach ( $categories as $category ) { ?>
		
		<h3 class="sponsor"><span><?php echo $category->name; ?></span></h3>
		<div class="sponsors sponsors-lg"><?php
			
			$sponsors_args	= array(
								'posts_per_page'	=> -1,
								'post_type'			=> 'sponsor',
								'tax_query'			=> array(
															array(
																'taxonomy'	=> 'sponsor-tier',
																'field'		=> 'slug',
																'terms'		=> array( $category->slug )
															),
														)
							);
			
			$sponsors_chunks	= array_chunk( get_posts( $sponsors_args ), 3 );
			$sponsors_chunks	= apply_filters( 'multievent_filter_posts_ef_sponsors', $sponsors_chunks, $sponsors_args, $instance );
			
			if( !empty( $sponsors_chunks ) ) {
				
				foreach ( $sponsors_chunks as $chunk_key => $sponsors_chunk ) {?>

					<div class="item<?php if ($chunk_key == 0) echo ' active'; ?>"><?php
						if( !empty( $sponsors_chunk ) ) {

							foreach ( $sponsors_chunk as $sponsors ) {
							
								$link = get_post_meta( $sponsors->ID, 'sponsor_link', true );

								echo('<div class="sponsor">');

								if( $link ) {
								
									echo ("<a href='$link' title='" . $sponsors->post_title . "' target='_blank'>");
								}

								echo get_the_post_thumbnail( $sponsors->ID, 'full' );

								if( $link ) {
								
									echo ("</a>");
								}

								echo('</div>');
							}
						}?>
					</div><!-- .item --><?php 
				}
			}?>
		</div><!-- .sponsors .sponsors-lg --><?php
	}//end categories foreach loop?>
	
	<div class="text-center visible-xs">
		<a href="<?php echo stripslashes($sponsorsbuttonlink); ?>" class="btn btn-primary btn-header">
			<?php echo stripslashes($sponsorsbuttontext); ?>
		</a>
	</div>
</div>