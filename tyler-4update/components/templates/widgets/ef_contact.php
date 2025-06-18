<!-- CONTACT US -->
<?php if (!empty($args['recaptchapublickey']) && !empty($args['recaptchaprivatekey'])) { ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php } ?>
<div id="tile_contact" class="container widget">
	<h2><?php echo stripslashes($args['title']); ?></h2>
	<h3><?php echo stripslashes($args['subtitle']); ?></h3>
	<form method="post" class="contact-us">
		<div class="row">
			<div class="col-sm-4">
				<input type="text" name="contactName" placeholder="<?php _e('Name', 'dxef'); ?>" />
				<input type="text" name="phone" placeholder="<?php _e('Number', 'dxef'); ?>" />
				<input type="text" name="email" placeholder="<?php _e('Email', 'dxef'); ?>" />
			</div>
			<div class="col-sm-8">
				<textarea name="comments" placeholder="<?php _e('Message', 'dxef'); ?>"></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-4">
                <?php if (!empty($args['recaptchapublickey']) && !empty($args['recaptchaprivatekey'])) { ?>
                    <div id="recaptcha_widget"
                         class="g-recaptcha captcha-image pull-left"
                         data-sitekey="<?php print $args['recaptchapublickey']; ?>"></div>
				<?php } ?>
			</div>
			<div class="col-sm-6 col-md-8">
				<button type="submit" class="btn btn-secondary" style="width: 100%"><?php 
					echo stripslashes($args['contactsendtext']);
				?></button>
			</div>
		</div>
		<input type="hidden" name="contact_email" value="<?php echo $args['contactemail'];?>" />
		<input type="hidden" name="action" value="send_contact_email" />
		<input type="hidden" name="submitted" id="submitted" value="true" />
	</form>
</div>
<style type="text/css">
	@media only screen and (max-width: 40.063em) {
		#recaptcha_area, #recaptcha_table {
			width: 290px !important;
		}
		.recaptchatable #recaptcha_image {
			width: 250px !important;
		}
	}
</style>