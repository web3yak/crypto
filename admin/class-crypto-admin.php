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

        //The icon in Base64 format
        $icon_base64 = 'PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMzAwLjAwMDAwMHB0IiBoZWlnaHQ9IjMwMC4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDMwMC4wMDAwMDAgMzAwLjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgo8bWV0YWRhdGE+CkNyZWF0ZWQgYnkgcG90cmFjZSAxLjE2LCB3cml0dGVuIGJ5IFBldGVyIFNlbGluZ2VyIDIwMDEtMjAxOQo8L21ldGFkYXRhPgo8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwzMDAuMDAwMDAwKSBzY2FsZSgwLjEwMDAwMCwtMC4xMDAwMDApIgpmaWxsPSIjMDAwMDAwIiBzdHJva2U9Im5vbmUiPgo8cGF0aCBkPSJNMjY5MyAyNDA2IGMtODUgLTU5IC0xMzAgLTE2MyAtMjAzIC00NzEgLTUyIC0yMTggLTg2IC0zMTcgLTEyNgotMzYzIGwtMzIgLTM3IC04MyAwIGMtNjkgMCAtMTA4IDcgLTIxNCAzOSAtMTI5IDM5IC0xMzEgNDAgLTE4NiA5OSAtMzEgMzIKLTU5IDU1IC02MiA1MSAtMyAtNSAtMTcgLTU0IC0zMSAtMTA5IC0xNSAtNTUgLTM4IC0xMTggLTUxIC0xNDEgLTUzIC05MCAtMTMyCi0xMzEgLTIzMyAtMTIxIC0xMDQgMTEgLTE2MyA3OCAtMjA4IDIzNyAtMjkgMTAzIC00MCAxMzAgLTUzIDEzMCAtNSAwIC0yNgotMjMgLTQ3IC01MCAtMzcgLTQ5IC00MiAtNTIgLTE2NCAtOTIgLTEwNiAtMzUgLTEzOCAtNDEgLTIxMiAtNDIgLTEzMiAtMQotMTM2IDUgLTIzOCAzNDMgLTkyIDMwNSAtMTY2IDQ2MSAtMjQzIDUxMCAtMTcgMTEgLTMyIDE5IC0zNCAxOCAtMSAtMSAyOSAtODkKNjcgLTE5NCA3OCAtMjE2IDg1IC0yNTAgMTAwIC00ODggMTAgLTE1NyAxNCAtMTc1IDQyIC0yMzcgMzYgLTgwIDExMyAtMTU4CjE4NCAtMTg4IDkyIC00MCAyMTUgLTM2IDM1MyAxMiAzMCAxMCA1NSAxNyA1NSAxNiA5IC0yMCA1NyAtMjMzIDU4IC0yNjAgMQotMjEgNiAtMzggMTAgLTM4IDEzIDAgNjggOTEgNjggMTEyIDAgMTAgNCAxOCAxMCAxOCAxMSAwIDYgLTk2IC0xNiAtMjc0IC04Ci02NSAtMTIgLTEyMSAtOSAtMTI0IDMgLTMgMjUgMTQgNDkgMzcgMjQgMjQgNDYgMzkgNDkgMzQgMyAtNCAxMyAtMzcgMjIgLTczCjI5IC0xMTEgNTQgLTE0MyAxNDEgLTE3NiAzOCAtMTQgNTEgLTE1IDg0IC01IDc4IDIzIDE0NyAxMDcgMTcxIDIwNiA2IDI4IDEzCjUxIDE0IDUzIDIgMiAyNCAtMTggNTAgLTQ1IGw0NyAtNDggLTYgMzUgYy0xNCA4OCAtMjYgMjIyIC0yNiAyOTQgbDAgODAgMjUKLTUxIGMzNSAtNzAgNTIgLTgzIDYwIC00NiAzIDE1IDE3IDc1IDMwIDEzMyAxMyA1OCAyNiAxMTcgMjkgMTMyIGw2IDI3IDkzCi0zMyBjMTIwIC00NCAxNjMgLTUwIDIzNCAtMzYgMTYxIDMzIDI0NiAxMTkgMzA0IDMwNiAyNyA4OCA0MiAxNzEgNzggNDI5IDE3CjEyMSA0MiAyNjIgNTYgMzE0IDE0IDUyIDI0IDk1IDIzIDk2IC0yIDIgLTE3IC03IC0zNSAtMTl6Ii8+CjwvZz4KPC9zdmc+Cg==';

        //The icon in the data URI scheme
        $icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;

        add_menu_page(
            __('Crypto Settings', 'crypto'),
            __('Crypto', 'crypto'),
            'manage_options',
            'crypto',
            array($this, 'display_dashboard_content'),
            $icon_data_uri,
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
