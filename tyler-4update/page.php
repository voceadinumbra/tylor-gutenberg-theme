<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
    <?php $categories = get_the_category(); ?>
    <div class="heading">
        <div class="container">
            <h1><?php the_title() ?></h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div>
                <?php the_post_thumbnail(null, array('class' => 'img-rounded')); ?>
                <?php the_content(); ?>
                <?php comments_template('', true); ?>
            </div>
        </div>
    </div>
<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>