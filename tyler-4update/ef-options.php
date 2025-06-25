<?php

global $ef_panel_manager;
$theme_options = $ef_panel_manager->get_panel('theme_options');

/*
 * Generate Theme Options Tabs
 */

$tab_general_site_options = new EF_Options_Tab();
$tab_homepage_options     = new EF_Options_Tab();


$tab_social_connecting = new EF_Options_Tab();


/*
 * General Site Options Fields
 */
$color_palette  = new EF_Select_Field(
        'ef_color_palette', 'Color Palette', '', // empty Description
        array('options' => array(
        'basic'        => 'Basic',
        'bangkok'      => 'Bangkok',
        'barcelona'    => 'Barcelona',
        'helsinki'     => 'Helsinki',
        'istanbul'     => 'Istanbul',
        'london'       => 'London',
        'melbourne'    => 'Melbourne',
        'mexico-city'  => 'Mexico City',
        'new-orleans'  => 'New Orleans',
        'oslo'         => 'Oslo',
        'paris'        => 'Paris',
        'san-diego'    => 'San Diego',
        'santa-monica' => 'Santa Monica',
        'shangai'      => 'Shangai',
        'tokyo'        => 'Tokyo'
    ))
);
$logo           = new EF_Image_Field('ef_logo', 'Logo');
$footer_content = new EF_Text_Field('ef_footer_content', 'Footer Copyright Content');
$googlemaps_key = new EF_Text_Field('ef_googlemaps_key', __('Google Maps API Key', 'cpt'));

/*
 * Homepage Options Fields
 */
$event_title         = new EF_Text_Field('ef_herotitle', 'Event Title');
$event_tagline       = new EF_Text_Field('ef_herotagline', 'Event Tagline');
$hero_background     = new EF_Image_Field('ef_hero', 'Hero Background');
$event_title_color   = new EF_Color_Field('ef_title_color', 'Event Title Color');
$event_tagline_color = new EF_Color_Field('ef_subtitle_color', 'Event Tagline Color');
$event_city_country  = new EF_Text_Field('ef_eventcitycountry', 'Event City & Country');
$event_location      = new EF_Text_Field('ef_eventlocation', 'Event Location');
$event_starting_time = new EF_Text_Field('ef_eventstartingtime', 'Event Starting Time');
$event_date          = new EF_Text_Field('ef_eventdate', 'Event Date');

// Social and Connecting
$social_facebook    = new EF_Text_Field('ef_facebook', 'Facebook URL');
$social_rss         = new EF_Checkbox_Field('ef_rss', 'Show RSS?');
$social_email       = new EF_Text_Field('ef_email', 'Email Address');
$social_flickr      = new EF_Text_Field('ef_flickr', 'Flickr URL');
$social_instagram   = new EF_Text_Field('ef_instagram', 'Instagram URL');
$social_pinterest   = new EF_Text_Field('ef_pinterest', 'Pinterest URL');
$social_linkedin    = new EF_Text_Field('ef_linkedin', 'LinkedIn URL');
$social_youtube     = new EF_Text_Field('ef_youtube', 'Youtube URL');

// Add fields to General Site Options
$tab_general_site_options->add_field('ef_color_palette', $color_palette);
$tab_general_site_options->add_field('ef_logo', $logo);
$tab_general_site_options->add_field('ef_footer_content', $footer_content);
$tab_general_site_options->add_field('ef_googlemaps_key', $googlemaps_key);

// Add fields to Homepage Options
$tab_homepage_options->add_field('ef_herotitle', $event_title);
$tab_homepage_options->add_field('ef_herotagline', $event_tagline);
$tab_homepage_options->add_field('ef_hero', $hero_background);
$tab_homepage_options->add_field('ef_title_color', $event_title_color);
$tab_homepage_options->add_field('ef_subtitle_color', $event_tagline_color);
$tab_homepage_options->add_field('ef_eventcitycountry', $event_city_country);
$tab_homepage_options->add_field('ef_eventlocation', $event_location);
$tab_homepage_options->add_field('ef_eventstartingtime', $event_starting_time);
$tab_homepage_options->add_field('ef_eventdate', $event_date);


// Add fields to Social and Connecting tab
$tab_social_connecting->add_field('ef_facebook', $social_facebook);
$tab_social_connecting->add_field('ef_rss', $social_rss);
$tab_social_connecting->add_field('ef_email', $social_email);
$tab_social_connecting->add_field('ef_flickr', $social_flickr);
$tab_social_connecting->add_field('ef_instagram', $social_instagram);
$tab_social_connecting->add_field('ef_youtube', $social_youtube);
$tab_social_connecting->add_field('ef_pinterest', $social_pinterest);
$tab_social_connecting->add_field('ef_linkedin', $social_linkedin);

/*
 * Add All Main Tabs
 */
$theme_options->add_tab('General Site Options', $tab_general_site_options);
$theme_options->add_tab('Homepage Options', $tab_homepage_options);
$theme_options->add_tab('Social Networks', $tab_social_connecting);