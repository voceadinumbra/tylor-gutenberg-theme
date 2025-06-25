jQuery(document).ready(function($) {    
    
    
    function populateSpeakerFields() {
        
        $('.speaker-row-container').each(function(index) {
            var $container = $(this);
            var $postTitle = $container.find('.wp-block-post-title');
            var postTitleText = $postTitle.text().trim();
            
            
           
            
            // Find matching speaker data by title
            var speakerData = null;
            if (speakersData && speakersData.speakers) {
                speakersData.speakers.forEach(function(speaker) {
                    if (speaker.title === postTitleText) {
                        speakerData = speaker;
                    }
                });
            }
            
            if (speakerData) {
                
                // Set the permalink
                $container.attr('href', speakerData.permalink);
                
                // Populate speaker title (position)
                var $positionTitle = $container.find('.position_title');
                if (speakerData.speaker_title && speakerData.speaker_title.trim() !== '') {
                    $positionTitle.text(speakerData.speaker_title).show().css('display', 'block');
                } else {
                    $positionTitle.hide();
                }
                
                // Populate company name
                var $companyName = $container.find('.speaker_company');
                if (speakerData.company_name && speakerData.company_name.trim() !== '') {
                    $companyName.text(speakerData.company_name).show().css('display', 'block');
                } else {
                    $companyName.hide();
                }
                
                // Populate hidden speaker title (combined)
                var combinedTitle = speakerData.title;
                if (speakerData.speaker_title && speakerData.speaker_title.trim() !== '') {
                    combinedTitle += ', ' + speakerData.speaker_title;
                }
                if (speakerData.company_name && speakerData.company_name.trim() !== '') {
                    combinedTitle += ', ' + speakerData.company_name;
                }
                $container.find('.speaker_title').text(combinedTitle);
                
                // Populate hidden description
                if (speakerData.content) {
                    $container.find('.desc').html(speakerData.content);
                }
            } else {         
                if (speakersData && speakersData.speakers) {
                    speakersData.speakers.forEach(function(speaker) {
                    });
                }
            }
        });
    }
    
    // Run multiple times to catch dynamically loaded content
    setTimeout(function() {
        populateSpeakerFields();
    }, 500);
    
    setTimeout(function() {
        populateSpeakerFields();
    }, 1500);
    
    setTimeout(function() {
        populateSpeakerFields();
    }, 3000);
    
    // Watch for changes in the speakers container
    var observer = new MutationObserver(function(mutations) {
        var shouldUpdate = false;
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                for (var i = 0; i < mutation.addedNodes.length; i++) {
                    var node = mutation.addedNodes[i];
                    if (node.nodeType === 1 && (
                        $(node).hasClass('speaker-row-container') || 
                        $(node).find('.speaker-row-container').length > 0
                    )) {
                        shouldUpdate = true;
                        break;
                    }
                }
            }
        });
        
        if (shouldUpdate) {
            setTimeout(populateSpeakerFields, 100);
        }
    });
    
    // Observe the speakers container for changes
    var speakersContainer = document.querySelector('.container-speakers');
    if (speakersContainer) {
        observer.observe(speakersContainer, {
            childList: true,
            subtree: true
        });
    } 
});