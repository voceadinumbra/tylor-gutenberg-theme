<!-- FACEBOOK -->
<?php $invited = $args['invited']; ?>
<div id="tile_facebook" class="container widget">
    <div class="row facebook">
        <div class="col-md-8">
            <div class="fb-event bg-fb-light">
                <div class="col">
                    <i class="icon-thumbs-up"></i>
                    <span class="num"><?php echo $args['rsvpattending']['summary']['count']; ?></span>
                    <?php _e('YES', 'dxef'); ?>
                </div>
                <div class="col">
                    <i class="icon-pause"></i>
                    <span class="num"><?php echo $args['rsvpmaybe']['summary']['count']; ?></span>
                    <?php _e('MAYBE', 'dxef'); ?>
                </div>
                <div class="col">
                    <i class="icon-thumbs-down"></i>
                    <span class="num"><?php echo $args['rsvpdeclined']['summary']['count']; ?></span>
                    <?php _e('NO', 'dxef'); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="<?php echo stripslashes($args['eventlink']); ?>" target="_blank" class="bg-fb-dark fb-view">
                <i class="icon-facebook-alt"></i>
                <?php echo stripslashes($args['eventlinktitle']); ?> <i class="icon-angle-right"></i>
            </a>
        </div>
    </div>
</div>