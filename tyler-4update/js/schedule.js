/**
 * stick date titles to top
 * @param stickies
 */

// FIXED: Dynamic session type detection instead of hardcoded defaults
if (typeof ajaxurl === 'undefined') {
    var ajaxurl = window.location.origin + '/wp-admin/admin-ajax.php';
}

// IMPROVED: Dynamic session type detection function with URL-based fallback
function getCurrentSessionType() {
    // Priority 1: Check for data attribute on schedule wrapper
    var wrapperSessionType = jQuery(".schedule-wrapper").attr("data-session-type");
    if (wrapperSessionType && wrapperSessionType !== '') {
        return wrapperSessionType;
    }
    
    // Priority 2: Check for data attribute on sessions list
    var activeSessionType = jQuery(".sessions.list").attr("data-session-type");
    if (activeSessionType && activeSessionType !== '') {
        return activeSessionType;
    }
    
    // Priority 3: Check nav-tabs data attribute
    var navTabsSessionType = jQuery(".nav-tabs").attr("data-session-type");
    if (navTabsSessionType && navTabsSessionType !== '') {
        return navTabsSessionType;
    }
    
    // Priority 4: Use global window.session_type if set
    if (typeof window.session_type !== 'undefined' && window.session_type !== '') {
        return window.session_type;
    }
    
    // Priority 5: Check for common session post types on page
    var commonTypes = ['session','sessions', 'sessionone', 'sessiontwo', 'sessionthree', 'sessionfour', 'sessionfive', 'workshops', 'keynotes'];
    for (var i = 0; i < commonTypes.length; i++) {
        if (jQuery('[data-session-type="' + commonTypes[i] + '"]').length > 0) {
            return commonTypes[i];
        }
    }
    
    // NEW: Priority 6: Detect session type from URL path
    var pathname = window.location.pathname.toLowerCase();
    if (pathname.includes('sessions-five') || pathname.includes('sessionfive')) {
        return 'sessionfive';
    } else if (pathname.includes('sessions-four') || pathname.includes('sessionfour')) {
        return 'sessionfour';
    } else if (pathname.includes('sessions-three') || pathname.includes('sessionthree')) {
        return 'sessionthree';
    } else if (pathname.includes('sessions-two') || pathname.includes('sessiontwo')) {
        return 'sessiontwo';
    } else if (pathname.includes('sessions-one') || pathname.includes('sessionone')) {
        return 'sessionone';
    } else if (pathname.includes('workshops')) {
        return 'workshops';
    } else if (pathname.includes('keynotes')) {
        return 'keynotes';
    }
    
    // Last resort fallback
    return 'sessiontwo';
}

// REMOVED: Static session_type declaration - now using dynamic detection

function stickyTitles(stickies) {
  var self = this,
    isLoaded = false;

  this.load = function () {
    stickies.each(function () {
      var thisSticky = jQuery(this);
      if (!self.isLoaded) {
        thisSticky.wrap('<div class="followWrap" />');
        thisSticky.parent().height(thisSticky.outerHeight());
      }

      jQuery.data(thisSticky[0], "pos", thisSticky.offset().top);
    });

    self.isLoaded = true;
  };

  this.scroll = function () {
    var offsetTop =
        (parseInt(jQuery(document.body).css("padding-top")) || 0) + 33, // 33px allocated by nav-tabs
      isFloating = false;

    stickies.each(function (i) {
      var thisSticky = jQuery(this),
        pos = thisSticky.hasClass("fixed")
          ? jQuery.data(thisSticky[0], "pos")
          : thisSticky.offset().top;

      jQuery.data(thisSticky[0], "pos", pos);
      pos -= offsetTop;

      if (pos <= jQuery(window).scrollTop()) {
        if (!thisSticky.hasClass("fixed")) {
          thisSticky.prepend(jQuery(".schedule > .nav-tabs").clone(true));
        }
        thisSticky.addClass("fixed container");
      } else {
        if (thisSticky.hasClass("fixed")) {
          thisSticky.find(".nav-tabs").remove();
        }
        thisSticky.removeClass("fixed container");
      }
    });

    if (isFloating) {
      jQuery(".schedule").addClass("floating");
    } else {
      jQuery(".schedule").removeClass("floating");
    }
  };
}

// expand menu on click
jQuery(".schedule > ul li a").click(function (event) {
  event.preventDefault();
  jQuery(this).toggleClass("expand");
});

function getMeta(url, callback) {
  var img = new Image();
  img.src = url;
  img.onload = function () {
    callback(this.width, this.height);
  };
}

var style = "";

// FIXED: Dynamic session type detection in updateSchedule function with debugging
function updateSchedule(timestamp, location, track) {
  // Get current session type dynamically
  var currentSessionType = getCurrentSessionType();
  

  
  jQuery(".loader-img").show();
  if (track !== null && track !== undefined) {
    tech_track(track);
  }
  
  jQuery.ajax({
    type: "POST",
    dataType: "json",
    url: ajaxurl,
    data: {
      action: "get_schedule",
      session_type: currentSessionType, // FIXED: Use dynamic session type
      "data-timestamp": timestamp,
      "data-location": location,
      "data-track": track,
    },
    success: function (data) {
      
      if (data.sessions && data.sessions.length > 0) {
        var cur_time = 0;
        var cur_date = 0;
        var html = "";

        jQuery.each(data.sessions, function (index, session) {
          if (session.workshop == 1) return true;
          var concurrent = "";
          var speakers = "";
          var color =
            session.color != "" ? ' style="color:' + session.color + '"' : "";

          if (cur_date != session.date) {
            html +=
              '<div class="day-floating"><span>' +
              session.date +
              "</span></div>";
            cur_date = session.date;
          }

          if (cur_time != session.time) {
            cur_time = session.time;
          } else {
            concurrent = " concurrent";
          }

          if (session.speakers)
            jQuery.each(session.speakers, function (index, speaker) {
              var featured = speaker.featured ? " featured" : "";
              var name = "";
              if (
                speaker.speaker_moderator == "1" &&
                session.speakers.length > 1 &&
                index == 0
              )
                name += '<span class="speaker_moderator">Moderator<br></span>';
              name +=
                '<span class="speaker_name">' + speaker.post_title + "</span>";
              if (speaker.speaker_title)
                name +=
                  '<span class="speaker_title"><br>' +
                  speaker.speaker_title +
                  "</span>";
              if (speaker.company)
                name +=
                  '<span class="speaker_company"><br>' +
                  speaker.company +
                  "</span>";
              var desc = speaker.desc;
              speakers +=
                '<a href="' +
                speaker.url +
                '" class="speaker' +
                featured +
                '"> \
                                                        ' +
                speaker.post_image +
                ' \
                                                        <span class="name"><span class="text-fit">' +
                name +
                '</span></span><span class="hidden speaker_title">' +
                name +
                ' \
                                                    </span><span class="hidden desc">' +
                desc +
                "</span></a>";
            });

          html +=
            '<div class="session' +
            concurrent +
            '"> \
                                            <span class="time">' +
            session.time +
            " - " +
            session.end_time +
            '</span> \
                                            <div class="session-inner">';
          if (session.sponsor_logo)
            getMeta(session.sponsor_logo, function (width, height) {
              style = "--width:" + width + " --height:" + height;
            });

          //alert(style);
         
          if (session.no_links != 1){
            html +=
              '<a href="' +
              session.url +
              '" class="title"' +
              color +
              "><span>" +
              session.post_title +
              "</span></a>";
            html +=
              '<img class="session_sponsor" src="' +
              session.sponsor_logo +
              '" style="' +
              style +
              '">';
          }
            
          else
            html +=
              '<a href="' +
              session.url +
              '" class="title"' +
              color +
              "><span>" +
              session.post_title +
              "</span></a>";
          html +=
            '<span class="speakers-thumbs"> \
                                                ' +
            speakers +
            " \
                                                </span>";
          if (session.no_links != 1) {
            html +=
              '<a href="' +
              session.url +
              '" class="more"> \
                                                    ' +
              data.strings["more_info"] +
              ' <i class="icon-angle-right"></i> \
                                                </a>';
          }
          html +=
            "</div> \
                                        </div>";
        });
        //initTextFit();
      } else {
        html = '<div class="no-results">No Session Found</div>';
      }
      jQuery(".schedule .sessions.list").html(html);
      jQuery(".loader-img").hide();

      var newStickies = new stickyTitles(jQuery(".day-floating"));
      newStickies.load();

      jQuery(window).on("resize", newStickies.load);
      jQuery(window).on("scroll", newStickies.scroll);
      
      // Update menu state to reflect current track
      updateMenuState(track);
      
      
      
//here
if (track !== null && track !== undefined && track !== '') {
  if (checkUrlParameter('track')) {
    updateUrlParameter('track', track);
  }
  else{
    let currentURL = window.location.href;
    let updatedURL = appendParameterToURL(currentURL, 'track', track);
    window.history.pushState({}, '', updatedURL); // Updates the URL without reloading the page
  }
}

    },
    error: function(xhr, status, error) {
      console.error('AJAX error:', {xhr: xhr, status: status, error: error});
      jQuery(".schedule .sessions.list").html('<div class="no-results">Error loading sessions</div>');
      jQuery(".loader-img").hide();
    }
  });
}

var textFit = function (el, rel) {
  rel = Math.min(1, rel || 1);
  el = jQuery(el);

  var elInner =
    el.find(".text-fit-inner")[0] ||
    el
      .wrapInner("<span class='text-fit-inner' style='display:block'></span>")
      .find(".text-fit-inner")[0];
  elInner = jQuery(elInner);

  var maxW = Math.min(el.innerWidth(), parseInt(el.css("max-width"))),
    maxH = Math.min(el.innerHeight(), parseInt(el.css("max-height")));

  if (elInner.outerWidth() > maxW || elInner.outerHeight() > maxH) {
    rel *= 0.95;
    elInner.css("font-size", rel + "em");
    textFit(el, rel);
  }
};

var initTextFit = function () {
  jQuery(".text-fit").each(function (i, el) {
    textFit(el);
  });
};

var getUrlParameter = function getUrlParameter(sParam) {
  var sPageURL = window.location.search.substring(1),
    sURLVariables = sPageURL.split("&"),
    sParameterName,
    i;

  for (i = 0; i < sURLVariables.length; i++) {
    sParameterName = sURLVariables[i].split("=");

    if (sParameterName[0] === sParam) {
      return sParameterName[1] === undefined
        ? true
        : decodeURIComponent(sParameterName[1]);
    }
  }
};

// NEW: Function to update menu state based on current track
function updateMenuState(trackId) {
  // Remove active state from all track menu items
  jQuery('.schedule a[data-track], .tracker a[data-track]').removeClass('active selected current');
  jQuery('.schedule li, .tracker li').removeClass('active selected current');
  
  // Also remove from any other potential menu containers
  jQuery('nav a[data-track], .nav a[data-track], .menu a[data-track]').removeClass('active selected current');
  jQuery('nav li, .nav li, .menu li').removeClass('active selected current');
  
  if (trackId && trackId !== 'null' && trackId !== '') {
    // Add active state to the selected track - try multiple selectors
    var targetLinks = jQuery([
      '.schedule a[data-track="' + trackId + '"]',
      '.tracker a[data-track="' + trackId + '"]', 
      'nav a[data-track="' + trackId + '"]',
      '.nav a[data-track="' + trackId + '"]',
      '.menu a[data-track="' + trackId + '"]'
    ].join(', '));
    
    var targetParents = targetLinks.closest('li');
    
    targetLinks.addClass('active selected current');
    targetParents.addClass('active selected current');
    
    // Also check for any dropdown or submenu that should be expanded
    targetParents.closest('ul').addClass('show');
    targetParents.closest('li').addClass('expand');
    
    // Handle nav-tabs specifically if they exist
    jQuery('.nav-tabs a[data-track="' + trackId + '"]').addClass('active');
    jQuery('.nav-tabs li').has('a[data-track="' + trackId + '"]').addClass('active');
    
  } else {
    // If no track selected, highlight "All" or default option
    var allSelectors = [
      '.schedule a:not([data-track])',
      '.tracker a:not([data-track])',
      'nav a:not([data-track])',
      '.nav a:not([data-track])',
      '.menu a:not([data-track])'
    ];
    
    var allLinks = jQuery(allSelectors.join(', ')).filter(function() {
      var text = jQuery(this).text().toLowerCase();
      return text.includes('all') || text.includes('show all') ||
             jQuery(this).attr('data-track') === '' ||
             jQuery(this).attr('data-track') === undefined;
    });
    
    // If no "all" links found, try to find the first menu item
    if (allLinks.length === 0) {
      allLinks = jQuery('.schedule a, .tracker a, nav a, .nav a, .menu a').first();
    }
    
    allLinks.addClass('active selected current');
    allLinks.closest('li').addClass('active selected current');
    
  }
}

// NEW: Helper function to initialize schedule with better timing
function initializeScheduleWithTrack() {
  // Clean up any existing track=null parameters
  cleanupTrackParameter();

  var ptrack = getUrlParameter("track");

  // Update menu state first
  updateMenuState(ptrack);

  if (ptrack != undefined && ptrack !== 'null' && ptrack !== '') {
    updateSchedule(null, null, ptrack);
  } else {
    updateSchedule(null, null, null);
  }
}

jQuery(document).ready(function ($) {
  var newStickies = new stickyTitles(jQuery(".day-floating"));
  newStickies.load();

  jQuery(window).on("resize", newStickies.load);
  jQuery(window).on("scroll", newStickies.scroll);

  jQuery(document).on(
    "click",
    ".schedule a[data-timestamp], .schedule a[data-location], .schedule a[data-track], .tracker a[data-track]",
    function (e) {
      e.preventDefault();
      var clickedTrack = jQuery(this).attr("data-track");
      
      // Update menu state before updating schedule
      updateMenuState(clickedTrack);
      
      updateSchedule(
        jQuery(this).attr("data-timestamp"),
        jQuery(this).attr("data-location"),
        clickedTrack,
      );
      if ($(".schedule li").children("ul").hasClass("hover")) {
        $(".schedule li").children("ul").removeClass("hover");
      }
    },
  );

  $(".schedule li").hover(
    function () {
      $(this).children("ul").addClass("hover");
    },
    function () {
      $(this).children("ul").removeClass("hover");
    },
  );
 
// IMPROVED: Better timing for initial track parameter handling
// Use setTimeout to ensure DOM is fully ready
setTimeout(function() {
  initializeScheduleWithTrack();
}, 100);

// BACKUP: Also try after window load event if elements still not ready
$(window).on('load', function() {
  // Only initialize if not already done successfully
  if (!window.scheduleInitialized && jQuery(".sessions.list").length > 0) {
    initializeScheduleWithTrack();
    window.scheduleInitialized = true;
  }
});

// ADDITIONAL: Ensure menu updates even if schedule was already initialized
setTimeout(function() {
  if (!window.scheduleInitialized) {
    var ptrack = getUrlParameter("track");
    updateMenuState(ptrack);
  }
}, 300);
  
});

function checkUrlParameter(parameterName) {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  return urlParams.has(parameterName);
}

function appendParameterToURL(url, paramName, paramValue) {
  let newURL = new URL(url);
  newURL.searchParams.append(paramName, paramValue);
  return newURL.toString();
}

function updateUrlParameter(paramName, paramValue) {
  const url = new URL(window.location.href);
  const params = new URLSearchParams(url.search);

  if (params.has(paramName)) {
    params.set(paramName, paramValue);
  } else {
    params.append(paramName, paramValue);
  }
  url.search = params.toString();
  
  // Use pushState instead of replaceState to add a new entry in browser history
  window.history.pushState({ path: url.toString() }, '', url.toString());
}

function tech_track(track_id) {
  var tracks_arr = {
    7: "Business", 
    11: "Keynotes", 
    16: "Market Briefs", 
    22: "Missions: Experimental/Scientific", 
    23: "Missions: Commercial", 
    24: "Missions: Other", 
    25: "Research: Space Science", 
    26: "Research: Earth Science", 
    27: "Research: Scientific Payloads", 
    28: "Research: Other", 
    29: "RF Engineering", 
    30: "Optical Engineering", 
    31: "Systems Engineering &amp; Integration", 
    32: "Engineering: Statistical Analysis and Application", 
    33: "Engineering: Satellite Manufacturing", 
    34: "Engineering: Other", 
    35: "Earth Observation", 
    36: "TT&amp;C", 
    37: "Propulsion", 
    38: "Launchers", 
    39: "Ground Systems", 
    46: "Ground: Antenna Design", 
    47: "Ground: Signal Processing and Distribution", 
    48: "Ground: Other", 
    40: "Constellation Design", 
    41: "Situational Awareness", 
    42: "Simulation, Modeling and Automation", 
    43: "Systems Analysis", 
    44: "AI/ML in Satellite Data Missions", 
    45: "Other"
  };
  
  const targetElement = document.querySelector('.track_name');
  if (targetElement) {
    if (tracks_arr[track_id]) {
      targetElement.innerHTML = "<b>TRACK:</b> " + tracks_arr[track_id];
    } else {
      targetElement.innerHTML = "&nbsp;";
    }
  } 
}

window.addEventListener("popstate", function (event) {
  const track = getUrlParameter("track");
  // Only pass track if it's not null or 'null'
  const validTrack = (track && track !== 'null') ? track : null;
  
  // Update menu state when navigating with browser buttons
  updateMenuState(validTrack);
  
  updateSchedule(null, null, validTrack); // Reload schedule based on updated track parameter
});

function cleanupTrackParameter() {
  const trackParam = getUrlParameter('track');
  if (trackParam === 'null' || trackParam === null || trackParam === '') {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    params.delete('track');
    url.search = params.toString();
    window.history.replaceState({}, '', url.toString());
  }
}