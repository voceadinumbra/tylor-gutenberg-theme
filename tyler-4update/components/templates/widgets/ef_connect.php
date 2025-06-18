<!-- CONNECT -->
<div id="tile_connect" class="container widget">
	<div class="connect">
		<div class="connect-inner">
			<span><?php echo stripslashes( $args['title'] ); ?></span>
			<div class="links"><?php
				
				if ( ! empty( $args['ef_options']['ef_email'] ) && is_email( $args['ef_options']['ef_email'] ) ) { ?>
					<a href="mailto:<?php echo $args['ef_options']['ef_email']; ?>" title="Email"><i class="icon-envelope"></i></a><?php
				}
				if ( isset( $args['ef_options']['ef_rss'] ) && $args['ef_options']['ef_rss'] == true) { ?>
					<a href="<?php echo get_bloginfo('rss_url'); ?>" target="_blank" title="Rss"><i class="icon-rss"></i></a><?php
				}
				if ( ! empty( $args['ef_options']['ef_facebook'] ) ) { ?>
					<a href="<?php echo esc_url( $args['ef_options']['ef_facebook'], $args['esc_url_protocols'] ); ?>" target="_blank" title="Facebook"><i class="icon-facebook"></i></a><?php
				}
				if ( ! empty( $args['ef_options']['ef_twitter'] ) ) { ?>
					<a href="<?php echo esc_url( $args['ef_options']['ef_twitter'], $args['esc_url_protocols'] ); ?>" target="_blank" title="Twitter"><i class="icon-twitter"></i></a><?php 
				}
				if ( ! empty( $args['ef_options']['ef_google_plus'] ) ) { ?>
					<a href="<?php echo esc_url( $args['ef_options']['ef_google_plus'], $args['esc_url_protocols'] ); ?>" target="_blank" title="Google+"><i class="icon-googleplus"></i></a><?php
				}
				if ( ! empty( $args['ef_options']['ef_instagram'] ) ) { ?>
					<a href="<?php echo esc_url( $args['ef_options']['ef_instagram'], $args['esc_url_protocols'] ); ?>" target="_blank" title="Instagram"><i class="icon-instagram"></i></a><?php
				}
                                if ( ! empty( $args['ef_options']['ef_youtube'] ) ) { ?>
					<a href="<?php echo esc_url( $args['ef_options']['ef_youtube'], $args['esc_url_protocols'] ); ?>" target="_blank" title="Youtube"><i class="icon-youtube"></i></a><?php
				}
				if ( ! empty( $args['ef_options']['ef_flickr'] ) ) { ?>
					<a href="<?php echo esc_url( $args['ef_options']['ef_flickr'], $args['esc_url_protocols'] ); ?>" target="_blank" title="Flickr"><i class="icon-flickr"></i></a><?php
				}
				if ( ! empty( $args['ef_options']['ef_linkedin'] ) ) {?>
					<a href="<?php echo esc_url( $args['ef_options']['ef_linkedin'], $args['esc_url_protocols'] ); ?>" target="_blank" title="LinkedIn"><i class="icon-linkedin"></i></a><?php
				}
				if ( ! empty( $args['ef_options']['ef_pinterest'] ) ) { ?>
					<a href="<?php echo esc_url( $args['ef_options']['ef_pinterest'], $args['esc_url_protocols'] ); ?>" target="_blank" title="Pinterest"><i class="icon-pinterest"></i></a><?php
				}?>
			</div>
		</div>
	</div>
</div>