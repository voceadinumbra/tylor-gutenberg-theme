<div id="tile_calltoaction" class="jumbotron widget" style="background-image: url('<?php bloginfo('template_url'); ?>/images/parallax.jpg')">
	<div class="container">
		<h1><?php echo stripslashes( $args['title'] ); ?></h1>
		<p class="lead"><?php echo stripslashes( $args['subtitle'] ); ?></p>
		<br/>
		<a href="<?php echo stripslashes( $args['buttonlink'] ); ?>" class="btn btn-lg btn-secondary"><?php echo stripslashes($args['buttontext']); ?></a>
	</div>
	<br/>
</div>