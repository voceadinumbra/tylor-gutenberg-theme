<!-- REGISTRATION -->
<div id="tile_registration" class="container widget">
	<h2><?php echo stripslashes($args['title']); ?></h2>
	<h3><?php echo stripslashes($args['subtitle']); ?></h3>
	<div style="margin:2em 0">
		<?php echo do_shortcode(stripslashes($args['registrationeventbrite'])); ?>
	</div>
	<p>
		<?php echo do_shortcode(stripslashes($args['registrationtext'])); ?>
	</p>
</div>