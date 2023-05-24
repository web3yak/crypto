<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://odude.com/
 * @since      1.0.0
 *
 * @package    Crypto
 * @subpackage Crypto/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Crypto
 * @subpackage Crypto/public
 * @author     ODude <navneet@odude.com>
 */
class Crypto_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/crypto-public.css', array(), $this->version, 'all');
        wp_enqueue_style('flexi_min', plugin_dir_url(__FILE__) . 'css/flexi-public-min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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



        $chainId = crypto_get_option('chainid', 'crypto_login_metamask', '1');
        $execute_js = crypto_get_option('execute_js', 'crypto_login_metamask', '');
        $crypto_network = crypto_get_option('crypto_network', 'crypto_marketplace_settings', '137');

        if ($crypto_network == '137') {
            $contract_addr = '0x7D853F9A29b3c317773A461ed87F54cdDa44B0e0';
        } else if ($crypto_network == '80001') {
            $contract_addr = '0xf89F5492094b5169C82dfE1cD9C7Ce1C070ca902'; //mumbai test
        } else {
            $contract_addr = '0x57E34eaDd86A52bA2A13c2f530dBA37bC919F010'; //FIL
        }

        $translation_array = array(
            'delete_string' => __('Are you sure you want to delete?', 'crypto'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'chainId' => $chainId,
            'executeJS' => $execute_js,
            'crypto_plugin_url' => CRYPTO_PLUGIN_URL,
            'crypto_network' => $crypto_network,
            'crypto_contract' => $contract_addr
        );

        wp_localize_script('crypto_connect_ajax_process', 'crypto_connectChainAjax', $translation_array);
        wp_enqueue_script('crypto_connect_ajax_process');

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/crypto-public.js', array('jquery'), $this->version, false);

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/crypto_connect_login-public.js', array('jquery'), $this->version, false);


        //Ajax record update
        wp_register_script('crypto_ajax_record', plugin_dir_url(__FILE__) . 'js/crypto_ajax_record.js', array('jquery'), $this->version);
        wp_enqueue_script('crypto_ajax_record');
    }
}
