

jQuery(function(){

    jQuery('#tile_media .btn-group-header .btn').click(function(){
        jQuery('#tile_media .btn-group-header .btn').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('#tile_media .carousel-indicators').empty();
        jQuery('#tile_media .carousel-inner').empty();
    });

   
    
    jQuery('#tile_media .btn-group-header .btn:first').trigger('click');
    
    

    jQuery('#tile_contact form').submit(function() {
        var hasError = false;
        jQuery('.error', this).remove();
        jQuery('.requiredField', this).each(function() {
            if(jQuery.trim(jQuery(this).val()) == '') {
                jQuery(this).parent().append('<span class="error">' + contact_missingfield_error + '</span>');
                jQuery(this).addClass('inputError');
                hasError = true;
            } else if(jQuery(this).hasClass('email')) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?jQuery/;
                if(!emailReg.test(jQuery.trim(jQuery(this).val()))) {
                    jQuery(this).parent().append('<span class="error">' + contact_wrongemail_error+ '</span>');
                    jQuery(this).addClass('inputError');
                    hasError = true;
                }
            }
        });
        if(!hasError) {
            jQuery('#tile_contact .alert, #tile_contact .info').remove();
            jQuery.ajax({
                url: ajaxurl,
                data: jQuery(this).serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if(data.sent == true)
                        jQuery('#tile_contact form').slideUp("fast", function() {
                            jQuery('#tile_contact form').before('<p class="info">' + data.message + '</p>');
                        });
                    else
                        jQuery('#tile_contact form').before('<p class="alert">' + data.message + '</p>');
                },
                error: function(data) {
                    jQuery('#tile_contact form').before('<p class="alert">' + data.message + '</p>');
                }
            });
        }
        return false;	
    });

});