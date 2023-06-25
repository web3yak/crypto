<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://web3domain.org/
 * @since             2.9
 * @package           Crypto
 *
 * @wordpress-plugin
 * Plugin Name:       Crypto
 * Plugin URI:        http://web3domain.org/
 * Description:       Crypto - everyday use tools. 
 * Version:           2.9
 * Author:            ODude
 * Author URI:        https://web3domain.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       crypto
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('CRYPTO_VERSION', '2.9');
define('CRYPTO_FOLDER', dirname(plugin_basename(__FILE__)));
define('CRYPTO_PLUGIN_URL', content_url('/plugins/' . CRYPTO_FOLDER));
define('CRYPTO_BASE_DIR', WP_CONTENT_DIR . '/plugins/' . CRYPTO_FOLDER . '/');
define('CRYPTO_ROOT_URL', plugin_dir_url(__FILE__));
define('CRYPTO_POLYGON_URL', 'https://polygonscan.com/token/0x7D853F9A29b3c317773A461ed87F54cdDa44B0e0?a=');
define('CRYPTO_FILECOIN_URL', 'https://explorer.glif.io/address/0x57E34eaDd86A52bA2A13c2f530dBA37bC919F010/?');

// Path to the plugin directory
if (!defined('CRYPTO_PLUGIN_DIR')) {
    define('CRYPTO_PLUGIN_DIR', plugin_dir_path(dirname(__FILE__)) . '' . CRYPTO_FOLDER . '/');
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-crypto-activator.php
 */
function activate_crypto()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-crypto-activator.php';
    Crypto_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-crypto-deactivator.php
 */
function deactivate_crypto()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-crypto-deactivator.php';
    Crypto_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_crypto');
register_deactivation_hook(__FILE__, 'deactivate_crypto');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-crypto.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_crypto()
{

    $plugin = new Crypto();
    $plugin->run();
}
run_crypto();
