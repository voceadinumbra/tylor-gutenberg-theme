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
    </div>
</footer>


<!-- SCROLL UP BTN -->
<a href="#" id="scroll-up"><?php _e('UP', 'tyler'); ?></a>

<!-- backdrop -->
<div id="backdrop"></div>


<?php wp_footer(); ?>
</body>
</html>