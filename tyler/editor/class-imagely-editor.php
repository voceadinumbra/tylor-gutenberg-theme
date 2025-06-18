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
    
        
            
    }
    
}

new Imagely_Editor;