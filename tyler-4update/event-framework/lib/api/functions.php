<?php

/**
 * License Key Activation/Deactivation Class
 */
class EF_Activation_License_Manager {

    /**
     * Self Upgrade Values
     */
    // Base URL to the remote upgrade API server

    /**
     * @var string
     */
    public $version = '0.1';

    /**
     * @var string
     */
    public $ef_activation_version_name = 'ef_activation_license_options_version';

    /**
     * @var string
     */
    public $plugin_url;

    /**
     * @var string
     */
    public $text_domain = 'dxef';

    public function __construct() {
        $current_theme = wp_get_theme();


        // Run the activation function
        if (get_option('ef_activation_license_options') === false) {
            $this->activation();
        }

        // FIX: Refresh product id
        $template   = $current_theme->get('Template');
        $theme_name = '';
        if (!empty($template)) {
            $parent_theme = wp_get_theme($template);
            if (!empty($parent_theme)) {
                $theme_name = $parent_theme->get('Name');
            }
        } else {
            $theme_name = $current_theme->get('Name');
        }
        update_option('ef_activation_product_id', $theme_name);
        // ******************
        
        
    }

    public function plugin_url() {
        if (isset($this->plugin_url))
            return $this->plugin_url;
        return $this->plugin_url = get_template_directory_uri() . '/event-framework/lib/api/';
    }

    /**
     * Check for software updates
     */
    public function load_plugin_self_updater() {
        $current_theme     = wp_get_theme();
        $template = $current_theme->get('Template');
        $options           = get_option('ef_activation_license_options');
        $plugin_name       = 'Tyler';//strtolower(empty($template) ? str_replace(' ', '-', $current_theme->get('Name')) : $template);
        $product_id        = get_option('ef_activation_product_id'); // Software Title
        $api_key           = $options['api_key']; // API License Key
        $activation_email  = $options['activation_email']; // License Email
        $instance          = get_option('ef_activation_instance'); // Instance ID (unique to each blog activation)
        $domain            = site_url(); // blog domain name
        $software_version  = get_option($this->ef_activation_version_name); // The software version
        $plugin_or_theme   = 'theme'; // 'theme' or 'plugin'
        // $this->text_domain is used to defined localization for translation

    }

    /**
     * Generate the default data arrays
     */
    public function activation() {
        global $wpdb;
        $current_theme  = wp_get_theme();
        $global_options = array(
            'api_key'          => '',
            'activation_email' => '',
        );

        update_option('ef_activation_license_options', $global_options);

        require_once( get_template_directory() . '/event-framework/lib/api/classes/class-cc-tk-passwords.php' );

        $ef_activation_password_management = new EF_Activation_Password_Management();

        // Generate a unique installation $instance id
        $instance = $ef_activation_password_management->generate_password(12, false);

        $single_options = array(
            'ef_activation_product_id'          => $current_theme->get('Name'),
            'ef_activation_instance'            => $instance,
            'ef_activation_deactivate_checkbox' => 'on',
            'ef_activation_activated'           => 'Deactivated',
        );

        foreach ($single_options as $key => $value) {
            update_option($key, $value);
        }

        $curr_ver = get_option($this->ef_activation_version_name);

        // checks if the current plugin version is lower than the version being installed
        if (version_compare($this->version, $curr_ver, '>')) {
            // update the version
            update_option($this->ef_activation_version_name, $this->version);
        }
    }

}

// End of class

$GLOBALS['ef_activation_license_manager'] = new EF_Activation_License_Manager();
