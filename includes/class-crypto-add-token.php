<?php
class Crypto_Add_Token
{

    public function __construct()
    {

        add_shortcode('crypto-add-token', array($this, 'crypto_add_token'));
        add_shortcode('crypto-add-network', array($this, 'crypto_add_network'));
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
            const tokenAddress = '<?php echo $contract; ?>';
            const tokenSymbol = '<?php echo $symbol; ?>';
            const tokenDecimals = 18;
            const tokenImage = '<?php echo $image; ?>';
            const tokenType = '<?php echo $image; ?>';

            async function addToken_<?php echo $symbol; ?>() {

                try {

                    const wasAdded = await ethereum.request({
                        method: 'wallet_watchAsset',
                        params: {
                            type: '<?php echo $type; ?>',
                            options: {
                                address: tokenAddress,
                                symbol: tokenSymbol,
                                decimals: tokenDecimals,
                                image: tokenImage,
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
