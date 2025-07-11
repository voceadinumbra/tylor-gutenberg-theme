<?php
/**
 * Title: Footer with colophon, 3 columns
 * Slug: tyler/footer-colophon-3-col
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: A footer section with a colophon and 3 columns.
 */
?>

<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">
	<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
		<!-- wp:image {"width":"40px","height":"auto","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full is-resized">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icon-message.webp" alt="" style="width:40px;height:auto" />
		</figure>
		<!-- /wp:image -->

		<!-- wp:separator {"className":"is-style-wide"} -->
		<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
		<!-- /wp:separator -->

		<!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|10"}}}} -->
		<div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--10)">
			<!-- wp:column {"width":"57%"} -->
			<div class="wp-block-column" style="flex-basis:57%">
				<!-- wp:heading {"fontSize":"x-large"} -->
				<h2 class="wp-block-heading has-x-large-font-size"><?php esc_html_e( 'Keep up, get in touch.', 'tyler' ); ?></h2>
				<!-- /wp:heading -->
			</div>
			<!-- /wp:column -->
			<!-- wp:column {"width":"30%"} -->
			<div class="wp-block-column" style="flex-basis:30%">
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<div class="wp-block-group">
					<!-- wp:heading {"level":3,"fontSize":"medium","fontFamily":"body"} -->
					<h3 class="wp-block-heading has-body-font-family has-medium-font-size"><?php esc_html_e( 'Contact', 'tyler' ); ?></h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph -->
					<p><a href="#"><?php echo esc_html_x( 'info@example.com', 'Example email in site footer', 'tyler' ); ?></a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->
			<!-- wp:column {"width":"30%"} -->
			<div class="wp-block-column" style="flex-basis:30%">
				<!-- wp:columns {"isStackedOnMobile":false} -->
				<div class="wp-block-columns is-not-stacked-on-mobile">
					<!-- wp:column -->
					<div class="wp-block-column">
						<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical"}} -->
						<div class="wp-block-group">
							<!-- wp:heading {"level":3,"fontSize":"medium","fontFamily":"body"} -->
							<h3 class="wp-block-heading has-body-font-family has-medium-font-size"><?php esc_html_e( 'Follow', 'tyler' ); ?></h3>
							<!-- /wp:heading -->
							<!-- wp:paragraph -->
							<p><a href="#"><?php esc_html_e( 'Instagram', 'tyler' ); ?></a> / <a href="#"><?php esc_html_e( 'Facebook', 'tyler' ); ?></a></p>
							<!-- /wp:paragraph -->
						</div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:column -->
				</div>
				<!-- /wp:columns -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->

		<!-- wp:spacer {"height":"var:preset|spacing|50"} -->
		<div style="height:var(--wp--preset--spacing--50)" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
		<div class="wp-block-group">
			<!-- wp:group {"style":{"spacing":{"blockGap":"6px"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size"><?php esc_html_e( '&copy;', 'tyler' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:site-title {"level":0,"style":{"typography":{"fontStyle":"normal","fontWeight":"400"}},"fontSize":"small"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size">
				<?php
				/* Translators: WordPress link. */
				$wordpress_link = '<a href="' . esc_url( __( 'https://wordpress.org', 'tyler' ) ) . '" rel="nofollow">WordPress</a>';
				echo sprintf(
					/* Translators: Designed with WordPress */
					esc_html__( 'Designed with %1$s', 'tyler' ),
					$wordpress_link
				);
				?>
			</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
