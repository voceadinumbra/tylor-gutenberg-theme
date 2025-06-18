<!-- TIME COUNTER -->
<div id="tile_timer" class="container widget">
    <div class="timecounter">
        <div>
            <span class="title"><?php echo stripslashes($args['title']); ?></span>
            <div class="time">
                <?php if (!empty($args['timerdate'])) { ?>
                    <?php
                    $countdown_expiration = ($args['timerdate'] < time()) ? 0 :$args['timerdate'];    
                    ?>
                    <script type="text/javascript">
                        var countdown_expiration = <?php echo $countdown_expiration; ?>;
                    </script>
                    <input type="hidden" id="countdown_hidden" />
                    <span class="days"><span><?php _e('Days', 'dxef'); ?></span></span>
                    <span class="hours"><span><?php _e('Hours', 'dxef'); ?></span></span>
                    <span class="minutes"><span><?php _e('Minutes', 'dxef'); ?></span></span>
                    <span class="seconds"><span><?php _e('Seconds', 'dxef'); ?></span></span>
                        <?php } ?>
            </div>
        </div>
    </div>
</div>