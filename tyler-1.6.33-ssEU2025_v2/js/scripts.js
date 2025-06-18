jQuery(document).ready(function($){
	$('body.tax-sponsor-type ul.nav-tabs li').hover(
    	function() {
    		$(this).children('ul').addClass('hover');
    	}, function() {
    		$(this).children('ul').removeClass('hover');
    	}
    );
	
	$('body.tax-sponsor-type ul.nav-tabs li a.sponsor-location').on('click', function(e) {
		var id = $(this).attr('data-location');
		$("div.sponsor-row#empty").remove();
		if (id == 0)
			$("div.sponsor-row").fadeIn();
		else
		{
			$("div.sponsor-row[data-location=" + id + "]").fadeIn();
			$("div.sponsor-row[data-location!=" + id + "]").fadeOut('400', function(){
				if ($("div.sponsor-row:visible").length == 0)
				{ // let the user know there are no results to his selection
					$("div.sponsor-row:first").before("<div class='sponsor-row' id='empty'><div class='container'>There are no results for this location. Please select a different location.</div></div>");
				}
			});
		}
		e.preventDefault();
	});

	jQuery(document).on("click", "body.page-template-schedule .schedule a.speaker, body.post-type-archive-speaker a.speaker-row-container, body.single-session a.speaker", function(e) {
		if (speakerBioInLightBox != 1)
			return;
		jQuery("div#speaker-bio").remove();
		var html = "<div id='speaker-bio' class='dialog'><img src='" + jQuery(this).find("img").attr("src") + "'><p class='speaker_title'>" + 
			jQuery(this).find(".speaker_title").html() + "</p><div class='desc'>" + jQuery(this).find("span.desc").html() + "</div></div>";
		jQuery(html).appendTo("body");
		dialogWidth = "500px";
		if (jQuery(window).width() < 500)
			dialogWidth = "90%";
		jQuery("div#speaker-bio").dialog({
			modal: true,
			width: dialogWidth,
			show: {effect: "slide", duration: 300},
			hide: {effect: "slide", duration: 300},
			open: function(){
				jQuery('.ui-widget-overlay').bind('click',function(){
					jQuery('div#speaker-bio').dialog('close');
				})
			}
		});
		e.preventDefault();
	});

});