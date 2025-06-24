jQuery(document).ready(function($) {
    console.log('=== SPEAKER ARCHIVE DEBUG ===');
    console.log('Full speakers data:', speakersData);
    
    if (speakersData && speakersData.speakers) {
        console.log('Number of speakers found:', speakersData.speakers.length);
        
        // Log each speaker's data for debugging
        speakersData.speakers.forEach(function(speaker, index) {
            console.log('Speaker ' + index + ':', {
                title: speaker.title,
                speaker_title: speaker.speaker_title,
                company_name: speaker.company_name,
                all_meta_keys: speaker.all_meta_keys,
                post_meta_data_keys: speaker.post_meta_data_keys
            });
        });
    }
    
    function populateSpeakerFields() {
        console.log('=== POPULATING SPEAKER FIELDS ===');
        
        $('.speaker-row-container').each(function(index) {
            var $container = $(this);
            var $postTitle = $container.find('.wp-block-post-title');
            var postTitleText = $postTitle.text().trim();
            
            console.log('Processing container ' + index + ', title: "' + postTitleText + '"');
            
            if (!postTitleText) {
                console.log('No title found, skipping...');
                return;
            }
            
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
                console.log('Found matching speaker data:', speakerData);
                
                // Set the permalink
                $container.attr('href', speakerData.permalink);
                console.log('Set permalink to:', speakerData.permalink);
                
                // Populate speaker title (position)
                var $positionTitle = $container.find('.position_title');
                if (speakerData.speaker_title && speakerData.speaker_title.trim() !== '') {
                    $positionTitle.text(speakerData.speaker_title).show().css('display', 'block');
                    console.log('Set speaker title to:', speakerData.speaker_title);
                } else {
                    $positionTitle.hide();
                    console.log('No speaker title found');
                }
                
                // Populate company name
                var $companyName = $container.find('.speaker_company');
                if (speakerData.company_name && speakerData.company_name.trim() !== '') {
                    $companyName.text(speakerData.company_name).show().css('display', 'block');
                    console.log('Set company name to:', speakerData.company_name);
                } else {
                    $companyName.hide();
                    console.log('No company name found');
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
                console.log('Set combined title to:', combinedTitle);
                
                // Populate hidden description
                if (speakerData.content) {
                    $container.find('.desc').html(speakerData.content);
                    console.log('Set content');
                }
            } else {
                console.log('No matching speaker data found for: "' + postTitleText + '"');
                console.log('Available speaker titles:');
                if (speakersData && speakersData.speakers) {
                    speakersData.speakers.forEach(function(speaker) {
                        console.log(' - "' + speaker.title + '"');
                    });
                }
            }
        });
    }
    
    // Run multiple times to catch dynamically loaded content
    setTimeout(function() {
        console.log('Running speaker population (500ms delay)');
        populateSpeakerFields();
    }, 500);
    
    setTimeout(function() {
        console.log('Running speaker population (1500ms delay)');
        populateSpeakerFields();
    }, 1500);
    
    setTimeout(function() {
        console.log('Running speaker population (3000ms delay)');
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
            console.log('DOM change detected, updating speaker fields...');
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
        console.log('MutationObserver attached to speakers container');
    } else {
        console.log('Speakers container not found');
    }
});