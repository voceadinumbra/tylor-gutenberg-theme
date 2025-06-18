<?php
require_once 'class-tgm-plugin-activation.php';
require_once 'class-imagely-editor-pure-helpers.php';

if (!class_exists('Imagely_Editor')) {

    class Imagely_Editor
    {
        /**
         * @var Imagely_Editor_Pure_Helpers $pure
         */
        public $pure;
    
        function __construct()
        {
            $this->pure = new Imagely_Editor_Pure_Helpers;    
            $this->add_hooks();
        }
    
        function add_hooks()
        {
            if ($this->is_classic_plugin_required()) {
                add_action( 'tgmpa_register', array($this, 'register_required_plugins') );
                add_action( 'admin_bar_menu', array($this, 'admin_bar'), PHP_INT_MAX-1 );
                add_action( 'admin_init', array($this, 'fix_classic_editor_option') );
            }
    
            if ($this->has_gutenberg_plugin() || $this->has_block_editor()) {
                add_filter( 'get_edit_post_link', array($this, 'get_edit_post_link'), PHP_INT_MAX-1, 2 );
                add_action( 'admin_enqueue_scripts', array($this, 'hide_page_builder'));
            }
        }

        function fix_classic_editor_option()
        {
            // We used to previously set the "classic-editor-replace" option
            // to "no-replace".
            //
            // That option has changed it's implementation since, and its now best
            // to not set any option at all as having it set to "no-replace" causes
            // problems.
            //
            if (class_exists('Classic_Editor')) {
                $klass = new ReflectionClass('Classic_Editor');
                $version = $klass->getConstant('plugin_version');
                if (version_compare($version, "0.5") > 0 && get_option('classic-editor-replace') == 'no-replace') {
                    update_option('classic-editor-replace', 'block');
                }
            }
        }
    
        /**
         * Determines if the classic editor plugin is required for this WP instance
         * @return bool
         */
        function is_classic_plugin_required()
        {
            return $this->has_block_editor();
        }
    
        /**
         * Determines if the WP instance has gutenberg plugin installed
         * @return bool
         */
        function has_gutenberg_plugin()
        {
            $this->is_plugin_active('gutenberg') || $this->is_plugin_active('gutenberg/gutenberg.php');
        }
    
        /**
         * Determines if a plugin is active
         * @param string $name  the name of the plugin
         * @return bool
         */
        function is_plugin_active($name)
        {
            return in_array($name, get_option('active_plugins', array()));
        }
    
        /**
         * Determines if the WP instance has the block editor
         * @return bool
         */
        function has_block_editor()
        {
            global $wp_version;
            return ( version_compare( $wp_version, '4.9.9', '>=' ) );
        }

        function is_vc_problematic()
        {
            return WPB_VC_VERSION && version_compare(WPB_VC_VERSION, '5.1.1.2', '<=');
        }


        function does_need_classic_editor($post)
        {
            return $this->pure->has_tesla_page_builder_content($post) || ($this->pure->has_vc_content($post) && $this->is_vc_problematic());
        }
    
        /**
         * Using CSS, hides the page builder when using the Gutenberg editor
         * @see admin_init 
         */
        function hide_page_builder()
        {
            if (!$this->pure->is_classic_editor_requested($_REQUEST)) {
                wp_add_inline_style('wp-edit-post', "#ef-cb-main {display: none}");        
            }
        }
    
        /**
         * Returns a link to classic editor if the post contains page builder content; otherwise
         * returns the default edit link
         * 
         * @see get_edit_post_link filter
         * @param string $prev_link
         * @param int $post_id
         * @return string
         */
        function get_edit_post_link($prev_link, $post_id)
        {
            if (($post = get_post($post_id))) {
                return $this->does_need_classic_editor($post) // ?
                    ? $this->pure->to_classic_edit_url($prev_link)
                    : $prev_link;	
            }
    
            return $prev_link;
        }
    
        /**
         * Returns an admin bar with the edit post link used to open the classic editor if the
         * post contains page builder content
         * 
         * @see wp_admin_bar action
         * @param WP_Admin_Bar $admin_bar
         * @return WP_Admin_Bar
         */
        function admin_bar($admin_bar)
        {
            global $post;
    
            if ($post && $this->does_need_classic_editor($post)) {// ?
                $node_id 	= 'classic-editor';
                $node 		= $admin_bar->get_node($node_id);
                if ($node) {
                    $node->href = $this->pure->to_classic_edit_url($node->href);
                    $node->title = __('Edit');
                    $admin_bar->remove_node($node_id);
                    $admin_bar->add_node($node);
                }
            }
    
            return $admin_bar;
        }
    
        /**
         * Intializes TGM
         * @see tgmpa_register action
         * @return array
         */
        function register_required_plugins() {
            $plugins = array(
                array(
                    'name'              => 'Classic Editor',
                    'slug'              => 'classic-editor',
                    'required'          => TRUE,
                    'force_activation'  => TRUE,
                )
            ); 
    
            $config = array(
                'id'           => 'cpt',                 // Unique ID for hashing notices for multiple instances of TGMPA.
                'default_path' => '',                      // Default absolute path to bundled plugins.
                'menu'         => 'tgmpa-install-plugins', // Menu slug.
                'parent_slug'  => 'themes.php',            // Parent menu slug.
                'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
                'has_notices'  => TRUE,                    // Show admin notices or not.
                'dismissable'  => TRUE,                    // If false, a user cannot dismiss the nag message.
                'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
                'is_automatic' => TRUE,                   // Automatically activate plugins after installation or not.
                'message'      => '',                      // Message to output right before the plugins table.
    
                /*
                'strings'      => array(
                    'page_title'                      => __( 'Install Required Plugins', 'cpt' ),
                    'menu_title'                      => __( 'Install Plugins', 'cpt' ),
                    /* translators: %s: plugin name. * /
                    'installing'                      => __( 'Installing Plugin: %s', 'cpt' ),
                    /* translators: %s: plugin name. * /
                    'updating'                        => __( 'Updating Plugin: %s', 'cpt' ),
                    'oops'                            => __( 'Something went wrong with the plugin API.', 'cpt' ),
                    'notice_can_install_required'     => _n_noop(
                        /* translators: 1: plugin name(s). * /
                        'This theme requires the following plugin: %1$s.',
                        'This theme requires the following plugins: %1$s.',
                        'cpt'
                    ),
                    'notice_can_install_recommended'  => _n_noop(
                        /* translators: 1: plugin name(s). * /
                        'This theme recommends the following plugin: %1$s.',
                        'This theme recommends the following plugins: %1$s.',
                        'cpt'
                    ),
                    'notice_ask_to_update'            => _n_noop(
                        /* translators: 1: plugin name(s). * /
                        'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                        'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                        'cpt'
                    ),
                    'notice_ask_to_update_maybe'      => _n_noop(
                        /* translators: 1: plugin name(s). * /
                        'There is an update available for: %1$s.',
                        'There are updates available for the following plugins: %1$s.',
                        'cpt'
                    ),
                    'notice_can_activate_required'    => _n_noop(
                        /* translators: 1: plugin name(s). * /
                        'The following required plugin is currently inactive: %1$s.',
                        'The following required plugins are currently inactive: %1$s.',
                        'cpt'
                    ),
                    'notice_can_activate_recommended' => _n_noop(
                        /* translators: 1: plugin name(s). * /
                        'The following recommended plugin is currently inactive: %1$s.',
                        'The following recommended plugins are currently inactive: %1$s.',
                        'cpt'
                    ),
                    'install_link'                    => _n_noop(
                        'Begin installing plugin',
                        'Begin installing plugins',
                        'cpt'
                    ),
                    'update_link' 					  => _n_noop(
                        'Begin updating plugin',
                        'Begin updating plugins',
                        'cpt'
                    ),
                    'activate_link'                   => _n_noop(
                        'Begin activating plugin',
                        'Begin activating plugins',
                        'cpt'
                    ),
                    'return'                          => __( 'Return to Required Plugins Installer', 'cpt' ),
                    'plugin_activated'                => __( 'Plugin activated successfully.', 'cpt' ),
                    'activated_successfully'          => __( 'The following plugin was activated successfully:', 'cpt' ),
                    /* translators: 1: plugin name. * /
                    'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'cpt' ),
                    /* translators: 1: plugin name. * /
                    'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'cpt' ),
                    /* translators: 1: dashboard link. * /
                    'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'cpt' ),
                    'dismiss'                         => __( 'Dismiss this notice', 'cpt' ),
                    'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'cpt' ),
                    'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'cpt' ),
    
                    'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
                ),
                */
            );
    
            tgmpa( $plugins, $config );
        }
    }
    
}

new Imagely_Editor;