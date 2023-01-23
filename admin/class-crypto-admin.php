<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://odude.com/
 * @since      1.0.0
 *
 * @package    Crypto
 * @subpackage Crypto/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Crypto
 * @subpackage Crypto/admin
 * @author     ODude <navneet@odude.com>
 */
class Crypto_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Crypto_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Crypto_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/crypto-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Crypto_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Crypto_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/crypto-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Add plugin's main menu and "Dashboard" menu.
     *
     * @since 1.6.5
     */
    public function admin_menu()
    {

        add_menu_page(
            __('Crypto Settings', 'crypto'),
            __('Crypto', 'crypto'),
            'manage_options',
            'crypto',
            array($this, 'display_dashboard_content'),
            'dashicons-money-alt',
            5
        );

        add_submenu_page(
            'crypto',
            __('Dashboard', 'crypto'),
            __('Dashboard', 'crypto'),
            'manage_options',
            'crypto',
            array($this, 'display_dashboard_content')
        );
    }

    public function display_dashboard_content()
    {
        require CRYPTO_PLUGIN_DIR . 'admin/partials/dashboard.php';
    }
}