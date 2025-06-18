<?php get_header(); ?>
<div class="heading">
    <div class="container">
        <h1><?php _e( '404 Not Found', 'tyler' ); ?></h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-lg-10">
            <?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'tyler' ); ?>
            
            <div>
            	<?php get_search_form(); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>