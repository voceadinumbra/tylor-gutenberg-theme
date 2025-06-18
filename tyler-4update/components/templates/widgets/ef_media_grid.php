<!--MEDIABOX-->
<div id="tile_media" class="mediabox-wrapper widget">
	<div class="container">
		<h2><?php echo stripslashes($args['title']); ?></h2>
		<div class="btn-group-header pull-right">
			<?php
			$media_types = get_terms('media-type');
			if (count($media_types) > 0) {
				?>
				<a class="btn btn-check btn-primary active" data-id="0"><?php _e('ALL', 'dxef'); ?></a>
				<?php
				foreach ($media_types as $media_type) {
					?>
					<a class="btn btn-check btn-primary" data-id="<?php echo $media_type->term_id; ?>"><?php echo $media_type->name; ?></a>
					<?php
				}
			}
			?>
		</div>
		<h3><?php echo stripslashes($args['subtitle']); ?></h3>
	</div>
	<div class="mediabox carousel slide" data-ride="carousel" data-interval="false" id="mediabox-carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
		</ol>
		<!-- Wrapper for slides -->
		<div class="carousel-inner">
		</div>
	</div>
</div>