<!-- SPEAKERS -->
<div id="tile_speakers" class="container widget">
	<h2><?php echo stripslashes($args['title']); ?></h2>
	<?php if ($args['full_speakers_page'] && count($args['full_speakers_page']) > 0) { ?>
		<a href="<?php echo get_permalink($args['full_speakers_page'][0]->ID); ?>" class="btn btn-primary btn-header pull-right hidden-xs"><?php echo stripslashes($args['speakersviewalltext']); ?></a>
	<?php } ?>
	<h3><?php echo stripslashes($args['subtitle']); ?></h3>
	<div class="speakers carousel slide" data-ride="carousel" data-interval="false" id="speakers-carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<?php
			for ($i = 0; $i < ceil(count($args['speakerslist']) / 3); $i++) {
				?>
				<li data-target="#speakers-carousel" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
				<?php
			}
			?>
		</ol>
		<div class="carousel-inner">
			<?php foreach ($args['speakers_chunks'] as $key => $speakers_chunk) { ?>
				<div class="item<?php if ($key == 0) echo ' active'; ?>">
					<?php foreach ($speakers_chunk as $speaker) { ?>
						<div class="speaker<?php if (get_post_meta($speaker->ID, 'speaker_keynote', true) == 1) echo ' featured'; ?>">
							<a class="speaker-inner" href="<?php echo get_permalink($speaker->ID); ?>">
								<span class="photo">
									<?php echo get_the_post_thumbnail($speaker->ID, 'tyler-speaker'); ?>
								</span>
								<span class="name"><span class="text-fit"><?php echo get_the_title($speaker->ID); ?></span></span>
								<span class="description"><?php echo $speaker->post_excerpt; ?></span>
								<span class="view">
									<?php echo stripslashes($args['speakersviewprofiletext']); ?> <i class="icon-angle-right"></i>
								</span>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php if ($args['full_speakers_page'] && count($args['full_speakers_page']) > 0) { ?>
		<div class="text-center visible-xs">
			<a href="<?php echo get_permalink($args['full_speakers_page'][0]->ID); ?>" class="btn btn-primary btn-header"><?php echo stripslashes($args['speakersviewalltext']); ?></a>
		</div>
	<?php } ?>
</div>