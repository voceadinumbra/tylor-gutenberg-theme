<?php
include_once( EF_HELPERS_DIR . 'framework-helper.php' );

include_once( EF_COMPONENTS_DIR . 'widgets/widget-registration.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-connect.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-schedule.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-facebook-rsvp.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-speakers.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-sponsors.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-media.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-twitter.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-timer.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-news.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-contact.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-explore.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-event-description.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-call-to-action.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-text-columns.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-footer-text-columns.php' );
include_once( EF_COMPONENTS_DIR . 'widgets/widget-comments.php' );

// Multievent widgets file
if ( current_theme_supports( 'multievent' ) ) {
	include_once( EF_PARENT_DIR . 'multievent/includes/class-multievent-widgets.php' );
}