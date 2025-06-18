<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">       
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
            <div class="upnav"><strong>Expo &amp; Sponsorship Sales:</strong>&nbsp;&nbsp;<a href="https://calendly.com/raj-smallsat-europe/expo-sponsorship-meeting" target="_blank">Schedule a Call</a>&nbsp;&nbsp;or&nbsp;&nbsp;+44 7773 770269</div>
        <header class="nav transition">
            <a href="<?php echo esc_url(home_url()); ?>" id="logo">
                <img src="https://2025.smallsateurope.com/wp-content/uploads/sites/24/2024/01/smallsatEU_logo_white-w-yellow.png" alt="Logo <?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>" />
            </a>
            <nav class="navbar" role="navigation">
                <!-- mobile navigation -->
                <div class="navbar-header visible-sm visible-xs">
                    <button type="button" class="btn btn-primary navbar-toggle" data-toggle="collapse" data-target="#tyler-navigation">
                        <span class="sr-only"><?php _e('Toggle navigation', 'tyler'); ?></span>
                        <i class="icon-header"></i>
                    </button>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse text-fit" id="tyler-navigation">
                    <?php
                    $menu = wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'transition',
                        'menu_id' => 'menu-primary',
						'echo' => false
                    ));
					if (empty($menu))
						echo "Empty menu";
					else
						echo $menu;
                    ?>
                </div>
            </nav>
        </header>