<?php
$ef_options = EF_Event_Options::get_theme_options();
?>

<footer>
    <div class="container">
        <div class="row row-sm">
            <?php dynamic_sidebar('footer'); ?>
        </div>
    </div>
    <div class="credits">
        <?php 
        if ( isset( $ef_options['ef_footer_content'] ) ) {
        	echo stripslashes( $ef_options['ef_footer_content'] ); 
        }
        ?>
        <div class="footer-tyler-event pbs">
        	Powered by <a href="https://www.showthemes.com/new-event-wordpress-theme-tyler">Powered by Tyler by Showthemes</a>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>

<!-- SCROLL UP BTN -->
<a href="#" id="scroll-up"><?php _e('UP', 'tyler'); ?></a>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<!-- backdrop -->
<div id="backdrop"></div>

</body>
</html>
