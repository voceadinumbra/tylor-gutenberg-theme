 <!-- 3-COL CONTENT -->
<div id="tile_textcolumns" class="container widget <?php echo $args['classname']; ?>">
	<div class="row row-xs">
		<?php if (!empty($args['textcolumnstitle1']) && !empty($args['textcolumnscontent1'])) { ?>
			<div class="col-md-4">
				<h3><?php echo stripslashes($args['textcolumnstitle1']); ?></h3>
				<p>
					<?php echo stripslashes($args['textcolumnscontent1']); ?>
				</p>
			</div>
		<?php } ?>
		<?php if (!empty($args['textcolumnstitle2']) && !empty($args['textcolumnscontent2'])) { ?>
			<div class="col-md-4">
				<h3><?php echo stripslashes($args['textcolumnstitle2']); ?></h3>
				<p>
					<?php echo stripslashes($args['textcolumnscontent2']); ?>
				</p>
			</div>
		<?php } ?>
		<?php if (!empty($args['textcolumnstitle3']) && !empty($args['textcolumnscontent3'])) { ?>
			<div class="col-md-4">
				<h3><?php echo stripslashes($args['textcolumnstitle3']); ?></h3>
				<p>
					<?php echo stripslashes($args['textcolumnscontent3']); ?>
				</p>
			</div>
		<?php } ?>
	</div>
</div>