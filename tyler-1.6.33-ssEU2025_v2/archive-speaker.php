<?php 
	get_header();
?>
	<div class="heading">	
		<div class="container">
			<h1><?php _e("Speakers", "tyler-child"); ?></h1>
		</div>
	</div>
	<div class="container container-speakers">
<?php
	while (have_posts()) : the_post(); 
?>
		<a href="<?php the_permalink(); ?>" class="speaker-row-container">
		<div class="speaker-row">
			<div class="speaker-img-container">
			<?php
				the_post_thumbnail('thumbnail', array('title' => get_the_title(), 'class' => 'img-speaker'));
			?>
			</div>
			<div class="speaker-details">
				<h2><?php the_title(); ?></h2>
				<?php
					$post_meta_data = get_post_custom($post->ID);
				?>
				<p class="position_title">
				<?php
					echo $post_meta_data['speaker_title'][0];
				?>
				</p>
				<p class="speaker_company">
				<?php
					echo $post_meta_data['company_name'][0];
				?>
				</p>
				<span class="hidden speaker_title">
					<?php 
						the_title();
						if (!(empty($post_meta_data['speaker_title'][0])))
							echo ", " . $post_meta_data['speaker_title'][0];
						if (!(empty($post_meta_data['company_name'][0])))
							echo ", " . $post_meta_data['company_name'][0];
					?>
				</span>
				<span class="hidden desc">
				<?php 
					the_content();
				?>
				</span>
			</div>
		</div><!--.speaker-row-->
		</a>
<?php endwhile; // end of the loop. ?>
	</div><!--.container-->

<?php get_footer() ?>