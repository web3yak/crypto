<?php
class Crypto_Connect_Metamask
{
    private $help = ' <a style="text-decoration: none;" href="#" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>';
    private $walletconnect;
    private $metamask;
    private $disconnect;
    private $connect_class;
    private $disconnect_class;
    private $enable_metamask;
    private $enable_walletconnect;

    public function __construct()
    {
        $this->metamask = crypto_get_option('metamask_label', 'crypto_login_metamask', 'Metamask');
        $this->disconnect = crypto_get_option('disconnect_label', 'crypto_login_metamask', 'Disconnect Wallet');
        $this->connect_class = crypto_get_option('connect_class', 'crypto_login_metamask', 'fl-button fl-is-info');
        $this->disconnect_class = crypto_get_option('disconnect_class', 'crypto_login_metamask', 'fl-button fl-is-danger');

        add_shortcode('crypto-connect', array($this, 'crypto_connect_Metamask'));
        add_action('woocommerce_login_form', array($this, 'crypto_connect_Metamask_small_woocommerce'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        // add_filter('crypto_settings_tabs', array($this, 'add_tabs'));
        add_filter('crypto_settings_sections', array($this, 'add_section'));
        add_filter('crypto_settings_fields', array($this, 'add_fields'));
        add_filter('crypto_settings_fields', array($this, 'add_extension'));

        add_filter('crypto_dashboard_tab', array($this, 'dashboard_add_tabs'));
        add_action('crypto_dashboard_tab_content', array($this, 'dashboard_add_content'));

        add_action('wp_head', array($this, 'crypto_head_script'));
        add_action('enqueue_block_assets', array($this, 'my_block_plugin_editor_scripts'));
        add_action('init', array($this, 'create_block_crypto_connect'));
    }

    /**
     * Enqueue block JavaScript and CSS for the editor
     */
    function my_block_plugin_editor_scripts()
    {
        /*
	
    // Enqueue block editor JS
    wp_enqueue_script(
        'my-block-editor-js',
        plugins_url( '/blocks/custom-block/index.js', __FILE__ ),
        [ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ],
        filemtime( plugin_dir_path( __FILE__ ) . 'blocks/custom-block/index.js' )	
    );
    */

        // Enqueue block editor styles
        wp_enqueue_style(
            'crypto-block-editor-css',
            CRYPTO_PLUGIN_URL . '/public/css/flexi-public-min.css'
        );
    }

    //add block editor
    public function create_block_crypto_connect()
    {
        register_block_type(CRYPTO_BASE_DIR . 'block/build/crypto-connect', array(
            'render_callback' => [$this, 'block_crypto_connect'],
            'attributes' => array(
                'title' => array(
                    'default' => 'Metamask Connect',
                    'type'    => 'string'
                ),
                'color' => array(
                    'default' => '',
                    'type'    => 'string'
                ),
                'size' => array(
                    'default' => '',
                    'type'    => 'string'
                ),
                'theme' => array(
                    'default' => '',
                    'type'    => 'string'
                )
            )
        ));
    }


    public function block_crypto_connect($attributes)
    {
        //flexi_log($attributes['color']);
        $short = '[crypto-connect label="' . $attributes['title'] . '" class="fl-button ' . $attributes['color'] . ' ' . $attributes['size'] . ' ' . $attributes['theme'] . '"]';
        return do_shortcode($short);
        //  return $short;
    }






    //Add Section title
    public function add_section($new)
    {

        $enable_addon = crypto_get_option('enable_crypto_login', 'crypto_general_login', 'metamask');
        //  if ("metamask" == $enable_addon) {
        $sections = array(
            array(
                'id' => 'crypto_login_metamask',
                'title' => __('Metamask Login', 'crypto'),
                'description' => __('Login with Metamask without any 3rd party provider', 'crypto') . "<br>" . "No API required<br>Shortcode eg. <code>[crypto-connect label=\"Connect to Login\" class=\"fl-button fl-is-info fl-is-light\"]</code><br>You must select provider at <a href='" . admin_url('admin.php?page=crypto_settings&tab=login&section=crypto_general_login') . "'>Login Settings</a>. Only one provider works at a time.",
                'tab' => 'login',
            ),
        );
        $new = array_merge($new, $sections);
        //  }
        return $new;
    }

    //Add enable/disable option at extension tab
    public function add_extension($new)
    {

        $fields = array('crypto_general_login' => array(
            array(
                'name' => 'enable_crypto_login',
                'label' => __('Select login provider', 'flexi'),
                'description' => '',
                'type' => 'radio',
                'options' => array(
                    //  'web3modal' => __('Connect using Web3Modal', 'flexi'),
                    //  'moralis' => __('Connect using moralis.io API - Metamask & WalletConnect', 'flexi'),
                    'metamask' => __('Connect using Metamask without any provider', 'flexi'),

                ),
                'sanitize_callback' => 'sanitize_key',
            ),
        ));
        $new = array_merge_recursive($new, $fields);

        return $new;
    }

    //Add section fields
    public function add_fields($new)
    {
        $enable_addon = crypto_get_option('enable_crypto_login', 'crypto_general_login', 'metamask');
        //  if ("metamask" == $enable_addon) {
        $fields = array(
            'crypto_login_metamask' => array(

                array(
                    'name' => 'chainid',
                    'label' => __('Default Network Chain ID', 'crypto'),
                    'description' => __('If specified, network wallet changes notice displayed. Eg. 1 for Ethereum Mainnet & 137 for Matic, 0 for any network', 'crypto'),
                    'type' => 'number',
                    'size' => 'small',
                    'sanitize_callback' => 'intval',
                ),

                array(
                    'name' => 'enable_woocommerce',
                    'label' => __('Enable at WooCommerce', 'crypto'),
                    'description' => __('Display connect button at WooCommmerce Login form', 'crypto') . " <a target='_blank' href='" . esc_url('https://wordpress.org/plugins/woocommerce/') . "'>WooCommerce</a>",
                    'type' => 'checkbox',
                    'sanitize_callback' => 'intval',

                ),
                array(
                    'name' => 'metamask_label',
                    'label' => __('Metamask button label', 'crypto'),
                    'description' => __('Label to display at metamask connect button', 'crypto'),
                    'size' => 20,
                    'type' => 'text',
                ),



                array(
                    'name' => 'connect_class',
                    'label' => __('Connect button class rule', 'crypto'),
                    'description' => __('fl-button fl-is-info fl-is-rounded', 'crypto'),
                    'type' => 'text',
                ),

                array(
                    'name' => 'execute_js',
                    'label' => __('Javascript function', 'crypto'),
                    'description' => __('Execute javascript function as soon as wallet connected. Eg. alert("Hello"); ', 'crypto'),
                    'size' => 20,
                    'type' => 'text',
                ),


            ),
        );
        $new = array_merge($new, $fields);
        // }
        return $new;
    }

    public function enqueue_scripts()
    {
        $enable_addon = crypto_get_option('enable_crypto_login', 'crypto_general_login', 'metamask');
        if ("metamask" == $enable_addon) {
            if ($this->run_script()) {
                wp_register_script('crypto_connect_ajax_process', plugin_dir_url(__DIR__) . 'public/js/crypto_connect_ajax_process.js', array('jquery'), CRYPTO_VERSION);
                wp_enqueue_script('crypto_connect_ajax_process');
                wp_enqueue_script('crypto_login', plugin_dir_url(__DIR__) . 'public/js/metamask/crypto_connect_login_metamask.js', array('jquery'), '', true);
                wp_enqueue_script('crypto_metamask_library', plugin_dir_url(__DIR__) . 'public/js/metamask/library.js', array('jquery'), '', false);

                wp_enqueue_script('crypto_web3', plugin_dir_url(__DIR__) . 'public/js/web3.min.js', array('jquery'), '', false);
                //wp_enqueue_script('crypto_web3-provider', plugin_dir_url(__DIR__) . 'public/js/web3-provider.min.js', array('jquery'), '', false);
            }
        }
    }

    public function crypto_connect_Metamask($atts)
    {

        extract(shortcode_atts(array(
            'class' => $this->connect_class,
            'label' => $this->metamask,
            'disconnect' => $this->disconnect
        ), $atts));


        $enable_addon = crypto_get_option('enable_crypto_login', 'crypto_general_login', 'metamask');
        if ("metamask" == $enable_addon) {

            if ($this->run_script()) {
                $put = "";
                ob_start();
                $nonce = wp_create_nonce("crypto_connect_Metamask_ajax_process");

                if (!is_user_logged_in()) {


?>
<div>
    <a href="#" id="btn-login" class="<?php echo esc_attr($class); ?>"><img width="20"
            src="<?php echo esc_url(CRYPTO_PLUGIN_URL . '/public/img/metamask.svg'); ?>">&nbsp;
        <?php echo esc_attr($label); ?></a>
    <div class="fl-notification fl-is-primary fl-is-light fl-mt-1" id="flexi_notification_box">
        <button class="fl-delete" id="delete_notification"></button>
        <div id="wallet_msg">&nbsp;</div>
    </div>
</div>
<?php
                } else {
                ?>
<script>
isConnected();
crypto_state_check();
</script>
<div>
    <a href="#" id="btn-login" class="<?php echo esc_attr($class); ?>"><img width="20"
            src="<?php echo esc_url(CRYPTO_PLUGIN_URL . '/public/img/metamask.svg'); ?>">&nbsp;
        <?php echo esc_attr($label); ?></a>
    <div class="fl-notification fl-is-primary fl-is-light fl-mt-1" id="flexi_notification_box">
        <button class="fl-delete" id="delete_notification"></button>
        <div id="wallet_msg">&nbsp;</div>
    </div>
</div>

<div id="wallet_addr_box">
    <div class="fl-tags fl-has-addons">
        <span id="wallet_addr" class="fl-tag fl-is-success fl-is-light">Loading...</span>
        <a class="fl-tag fl-is-delete" id="wallet_logout" title="Logout"></a>
    </div>
</div>

<?php
                }
                $put = ob_get_clean();

                return $put;
            }
        }
    }

    public function crypto_connect_Metamask_small_woocommerce()
    {

        //Display at WooCommerce form
        $enable_addon_woo = crypto_get_option('enable_woocommerce', 'crypto_login_metamask', 1);
        if ("1" == $enable_addon_woo) {
            echo wp_kses_post($this->crypto_connect_Metamask());
        }
    }

    public function run_script()
    {
        global $post;
        $enable_addon = crypto_get_option('enable_crypto_login', 'crypto_general_login', 'metamask');
        if ("metamask" == $enable_addon) {

            //add stylesheet for post/page here...
            if (is_single() || is_page()) {
                return true;
            }
        }
        return false;
    }

    public function dashboard_add_tabs($tabs)
    {

        $extra_tabs = array("login" => 'Login & Register');

        // combine the two arrays
        $new = array_merge($tabs, $extra_tabs);
        //crypto_log($new);
        return $new;
    }

    public function dashboard_add_content()
    {
        if (isset($_GET['tab']) && 'login' == sanitize_text_field($_GET['tab'])) {
            echo wp_kses_post($this->crypto_dashboard_content());
        }
    }

    public function crypto_head_script()
    {
        $nonce = wp_create_nonce('crypto_ajax');
        $put = "";
        ob_start();
        ?>

<script>
async function isConnected() {
    const accounts = await ethereum.request({
        method: 'eth_accounts'
    });


    if (accounts.length) {
        console.log(`You're connected to: ${accounts[0]}`);
        jQuery("[id=wallet_addr]").empty();
        jQuery("#wallet_addr_box").fadeIn("slow");
        jQuery("[id=wallet_addr]").append(crypto_wallet_short(accounts[0], 4)).fadeIn("normal");
        jQuery("[id=btn-login]").hide();

        const networkId = await ethereum.request({
            method: 'net_version'
        });

        console.log(networkId);
        crypto_check_network(networkId);

        //console.log(window.ethereum.networkName);
    } else {
        console.log("Metamask is not connected");
        jQuery("[id=wallet_addr_box]").hide();
        jQuery("[id=btn-login]").show();
    }
}
jQuery(document).ready(function() {
    jQuery("[id=wallet_logout]").click(function() {
        //alert("logout");

        jQuery("[id=btn-login]").show();
        jQuery("[id=wallet_addr]").empty();
        jQuery("[id=wallet_addr_box]").hide();

        create_link_crypto_connect_login('<?php echo sanitize_key($nonce); ?>', '', 'logout', '', '',
            '');
        jQuery.toast({
            heading: 'Logout',
            text: "Please Wait...",
            icon: 'success',
            loader: true,
            loaderBg: '#fff',
            showHideTransition: 'fade',
            hideAfter: 10000,
            allowToastClose: false,
            position: {
                left: 100,
                top: 30
            }
        });
        //jQuery("#crypto_connect_ajax_process").click();
        setTimeout(function() {
            jQuery('#crypto_connect_ajax_process').trigger('click');
        }, 1000);

        setTimeout(function() {
            location.reload();
        }, 1500);
    });
});

function crypto_state_check() {

    window.addEventListener("load", function() {
        if (window.ethereum) {

            window.ethereum.enable(); // get permission to access accounts

            // detect Metamask account change
            window.ethereum.on('accountsChanged', function(accounts) {
                console.log('accountsChanges', accounts);
                window.location.reload();

            });

            // detect Network account change
            window.ethereum.on('networkChanged', function(networkId) {
                console.log('networkChanged', networkId);
                window.location.reload();

            });
        } else {
            console.log("No web3 detected");
        }
    });
}

function crypto_check_network(networkId) {
    const chainId_new = crypto_connectChainAjax.chainId;
    console.log(chainId_new);
    console.log(crypto_network_arr[networkId]);
    if ((chainId_new != networkId && chainId_new != 0)) {
        var msg = "Change your network to:" + crypto_network_arr[chainId_new];
        //  jQuery("[id=wallet_msg]").empty();
        //  jQuery("#flexi_notification_box").fadeIn("slow");
        //   jQuery("[id=wallet_msg]").append(msg).fadeIn("normal");
        jQuery.toast({
            heading: 'Notice',
            text: msg,
            icon: 'warning',
            loader: true,
            loaderBg: '#fff',
            showHideTransition: 'fade',
            hideAfter: 10000,
            allowToastClose: false,
            position: {
                left: 100,
                top: 30
            }
        });
        return false;
    }
    return true;

}
</script>
<?php

        $put = ob_get_clean();

        echo $put;
    }


    public function crypto_dashboard_content()
    {
        ob_start();
    ?>
<div class="changelog section-getting-started">
    <div class="feature-section">
        <h2>Login & Register</h2>
        <div class="wrap">
            <b>This plugin connects to your MetaMask or other cryptocurrency wallet. Once connected, the user will be
                automatically logged in without the need for registration.</b>
            <br><br><a class="button button-primary"
                href="<?php echo admin_url('admin.php?page=crypto_settings&tab=login&section=crypto_general_login'); ?>">Login
                Settings</a>
            <br><br>
            <b>Tips</b>
            <ul>

                <li>* If a user has already logged in using their traditional username and password, this plugin will
                    bind their current wallet address. This means that the next time they log in with the same username,
                    they will be automatically logged in as long as they use the same wallet address. </li>
                <li>* "Network Chain ID" refers to the specific blockchain network of a cryptocurrency. For example, the
                    Ethereum mainnet has a Chain ID of 1.</li>
                <li> * Get your own API for faster and more reliable performance.</li>
            </ul>

        </div>
    </div>
</div>
<?php
        $content = ob_get_clean();
        return $content;
    }
}
$connect_page = new Crypto_Connect_Metamask();