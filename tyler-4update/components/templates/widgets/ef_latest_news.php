 <!-- LATEST NEWS -->
<div id="tile_news" class="container widget">
	<h2><?php echo stripslashes($args['title']); ?></h2>
	<?php
	$blog_category_id = get_cat_ID( 'Blog' );
	$categories = get_categories( array( 'type' => 'post' ) );
	
	if ( empty( $blog_category_id ) ) {
		$categories = get_categories( array( 'type' => 'post' ) );
		$blog_category_id = $categories[0];
	}

	// Get the URL of this category
	$blog_category_link = get_category_link( $blog_category_id );
	
	if ( ! empty( $blog_category_link ) ) { 
	?>
		<a href="<?php echo $blog_category_link; ?>" class="btn btn-primary btn-header pull-right hidden-xs"><?php echo stripslashes($args['viewaltext']); ?></a>
	<?php
	}
	?>
		
	<h3><?php echo stripslashes($args['subtitle']); ?></h3>
	<div class="articles carousel slide" data-ride="carousel" data-interval="false" id="articles-carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<?php
			for ($i = 0; $i < count($args['news_chunks']); $i++) {
				?>
				<li data-target="#articles-carousel" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
				<?php
			}
			?>
		</ol>
		<!-- Wrapper for slides -->
		<div class="carousel-inner">
			<?php foreach ($args['news_chunks'] as $key => $news_chunk) { ?>
				<div class="item<?php if ($key == 0) echo ' active'; ?>">
					<?php
					foreach ($news_chunk as $news) {
						$news_date = strtotime($news->post_date);
						?>
						<article>
							<div class="image">
								<a href="<?php echo get_permalink($news->ID); ?>" class="text-fit">
									<span class="date">
										<span class="month"><?php echo date_i18n('M', $news_date); ?></span>
										<span class="day"><?php echo date('d', $news_date); ?></span>
										<span class="year"><?php echo date('Y', $news_date); ?></span>
									</span>
									<?php echo get_the_post_thumbnail( $news->ID, 'tyler-blog-home' ); ?>
								</a>
							</div>
							<div class="post-content">
								<a href="<?php echo get_permalink($news->ID); ?>" class="text-fit">
									<strong class="heading"><?php echo get_the_title($news->ID); ?></strong>
									<span class="perex">
										<?php 
										$news_content = $news->post_excerpt;
										if ( empty( $news_content ) ) {
											$news_content = $news->post_content;
											$news_content = wp_trim_words( $news_content, 55 );
										}

										echo $news_content;
										?>
									</span>
								</a>
							</div>
						</article>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php if (!empty($newscategory)) { ?>
		<div class="text-center visible-xs">
			<a href="<?php echo get_category_link($newscategory); ?>" class="btn btn-primary btn-header"><?php echo stripslashes($args['viewaltext']); ?></a>
		</div>
	<?php } ?>
</div>