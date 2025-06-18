<div id="tile_latest_comments" class="container widget">
<h2 class="widgettitle"><?php echo stripslashes($args['title']); ?></h2><?php 
		
if ($args['comments'] && count($args['comments']) > 0) { ?>
	<ul><?php 
		
		foreach ($args['comments'] as $comment) { ?>
			<li class="comment">
				<a href="<?php echo get_permalink($comment->ID); ?>" class="url">
					<?php echo $comment->comment_content; ?>
					<span class="time"><?php echo sprintf(__('%s ago', 'dxef'), human_time_diff(mysql2date('U', $comment->comment_date), current_time('timestamp'))); ?></span>
				</a>
			</li><?php 
		}?>
	</ul><?php
}
?>
</div>