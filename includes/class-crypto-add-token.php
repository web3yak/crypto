<?php
class Crypto_Add_Token
{

    public function __construct()
    {

        add_shortcode('crypto-add-token', array($this, 'crypto_add_token'));
        add_shortcode('crypto-add-network', array($this, 'crypto_add_network'));
        add_action('init', array($this, 'create_block_crypto_add_token'));
        add_action('init', array($this, 'create_block_crypto_add_network'));
        // Hook the enqueue functions into the editor
        add_action('enqueue_block_assets', array($this, 'my_block_plugin_editor_scripts'));
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
    public function create_block_crypto_add_network()
    {
        register_block_type(CRYPTO_BASE_DIR . 'block/build/add-network', array(
            'render_callback' => [$this, 'add_network_render'],
            'attributes' => array(
                'title' => array(
                    'default' => 'Add Matic Network',
                    'type'    => 'string'
                ),
                'name' => array(
                    'default' => 'Polygon Mainnet',
                    'type'    => 'string'
                ),
                'chainid' => array(
                    'default' => '137',
                    'type'    => 'string'
                ),
                'currency' => array(
                    'default' => 'MATIC',
                    'type'    => 'string'
                ),
                'symbol' => array(
                    'default' => 'MATIC',
                    'type'    => 'string'
                ),
                'rpcurl' => array(
                    'default' => 'https://polygon.llamarpc.com',
                    'type'    => 'string'
                ),
                'explorer' => array(
                    'default' => 'https://polygonscan.com',
                    'type'    => 'string'
                ),
                'css' => array(
                    'default' => 'fl-button fl-is-small',
                    'type'    => 'string'
                )
            )
        ));
    }

    public function add_token_render($attributes)
    {
        // Coming from RichText, each line is an array's element
        //  $sum = $attributes['number1'][0] + $attributes['number2'][0];

        // $html = "<h1>$sum</h1>";

        // return $html;
        //flexi_log($attributes);
        $short = '[crypto-add-token contract="' . $attributes['contract'] . '" symbol="' . $attributes['symbol'] . '" image="' . $attributes['image'] . '" title="' . $attributes['title'] . '" class="' . $attributes['css'] . '" type="' . $attributes['type'] . '"]';
        return do_shortcode($short);
        //  return $short;
    }



    //add block editor
    public function create_block_crypto_add_token()
    {
        register_block_type(CRYPTO_BASE_DIR . 'block/build/add-token', array(
            'render_callback' => [$this, 'add_token_render'],
            'attributes' => array(
                'title' => array(
                    'default' => 'Add Dogecoin',
                    'type'    => 'string'
                ),
                'contract' => array(
                    'default' => '0xba2ae424d960c26247dd6c32edc70b295c744c43',
                    'type'    => 'string'
                ),
                'image' => array(
                    'default' => 'https://s2.coinmarketcap.com/static/img/coins/64x64/74.png',
                    'type'    => 'string'
                ),
                'symbol' => array(
                    'default' => 'DOGE',
                    'type'    => 'string'
                ),
                'type' => array(
                    'default' => 'ERC20',
                    'type'    => 'string'
                ),
                'css' => array(
                    'default' => 'fl-button fl-is-small',
                    'type'    => 'string'
                )
            )
        ));
    }

    public function add_network_render($attributes)
    {
        // Coming from RichText, each line is an array's element
        //  $sum = $attributes['number1'][0] + $attributes['number2'][0];

        // $html = "<h1>$sum</h1>";

        // return $html;
        //flexi_log($attributes);
        $short = '[crypto-add-network name="' . $attributes['name'] . '" chainid="' . $attributes['chainid'] . '" currency="' . $attributes['currency'] . '" symbol="' . $attributes['symbol'] . '" rpcurl="' . $attributes['rpcurl'] . '" explorer="' . $attributes['explorer'] . '" title="' . $attributes['title'] . '" class="' . $attributes['css'] . '"]';
        return do_shortcode($short);
        //  return $short;
    }

    public function crypto_add_network($atts)
    {


        $put = "";
        ob_start();

        extract(shortcode_atts(array(
            'name' => 'Polygon Mainnet',
            'chainid' => '137',
            'currency' => 'MATIC',
            'symbol' => 'MATIC',
            'rpcurl' => 'https://polygon.llamarpc.com',
            'explorer' => 'https://polygonscan.com',
            'title' => 'Add Network',
            'class' => 'fl-button fl-is-small',
        ), $atts));

?>
<script>
async function crypto_add_network_<?php echo $chainid; ?>() {
    web3 = new Web3(window.ethereum);
    try {
        await window.ethereum.request({
            method: 'wallet_addEthereumChain',
            params: [{
                chainId: web3.utils.toHex('<?php echo $chainid; ?>'),
                chainName: '<?php echo $name; ?>',
                nativeCurrency: {
                    name: '<?php echo $currency; ?>',
                    symbol: '<?php echo $symbol; ?>', // 2-6 characters long
                    decimals: 18
                },
                blockExplorerUrls: ['<?php echo $explorer; ?>'],
                rpcUrls: ['<?php echo $rpcurl; ?>'],
            }, ],
        });
    } catch (addError) {
        console.error(addError);
        jQuery.toast({
            heading: 'Notice',
            text: addError.message,
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
    }



}
</script>

<span class="<?php echo $class; ?>" onclick="crypto_add_network_<?php echo $chainid; ?>()">
    <span class="fl-icon fl-is-small">
        <img src="<?php echo esc_url(CRYPTO_PLUGIN_URL . '/public/img/metamask.svg'); ?>">
    </span>
    <span><?php echo $title; ?></span>
</span>

<?php
        $put = ob_get_clean();

        return $put;
    }


    public function crypto_add_token($atts)
    {


        $put = "";
        ob_start();

        extract(shortcode_atts(array(
            'contract' => '0x7D853F9A29b3c317773A461ed87F54cdDa44B0e0',
            'symbol' => 'W3D',
            'image' => 'https://web3yak.com/favicon.ico',
            'title' => 'Add Web3Domain NFT',
            'class' => 'fl-button fl-is-small',
            'type' => 'ERC20'
        ), $atts));

    ?>
<script>
async function addToken_<?php echo $symbol; ?>() {

    try {

        const wasAdded = await ethereum.request({
            method: 'wallet_watchAsset',
            params: {
                type: '<?php echo $type; ?>',
                options: {
                    address: '<?php echo $contract; ?>',
                    symbol: '<?php echo $symbol; ?>',
                    decimals: '18',
                    image: '<?php echo $image; ?>',
                },
            },
        });

        if (wasAdded) {

            jQuery.toast({
                heading: 'Success',
                text: 'Added to Metamask',
                icon: 'success',
                loader: true,
                loaderBg: '#fff',
                showHideTransition: 'fade',
                hideAfter: 3000,
                allowToastClose: false,
                position: {
                    left: 100,
                    top: 30
                }
            });
        } else {

            jQuery.toast({
                heading: 'Warning',
                text: 'Not added to Metamask',
                icon: 'warning',
                loader: true,
                loaderBg: '#fff',
                showHideTransition: 'fade',
                hideAfter: 3000,
                allowToastClose: false,
                position: {
                    left: 100,
                    top: 30
                }
            });
        }
    } catch (error) {
        //  console.log(error);
        jQuery.toast({
            heading: 'Error',
            text: error.message,
            icon: 'error',
            loader: false,
            loaderBg: '#fff',
            showHideTransition: 'fade',
            hideAfter: false,
            allowToastClose: true,
            position: {
                left: 100,
                top: 30
            }
        });
    }




}
</script>

<span class="<?php echo $class; ?>" onclick="addToken_<?php echo $symbol; ?>()">
    <span class="fl-icon fl-is-small">
        <img src="<?php echo esc_url(CRYPTO_PLUGIN_URL . '/public/img/metamask.svg'); ?>">
    </span>
    <span><?php echo $title; ?></span>
</span>

<?php



        $put = ob_get_clean();

        return $put;
    }
}

$add_token = new Crypto_Add_Token();