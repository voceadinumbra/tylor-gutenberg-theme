<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
    <?php $categories = get_the_category(); ?>
    <div class="heading">
        <div class="container">
            <h1>
				<?php 
					if (get_post_type() == "sponsor") // show sponsor logo
						the_post_thumbnail('thumbnail', array('title' => get_the_title(), 'class' => 'img-sponsor'));
					the_title();
				?>
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
        <div class="row">
                <?php the_content(); ?>
        </div>
    </div>
<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>