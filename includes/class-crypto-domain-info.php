<?php
class Crypto_Domain_INFO
{
    private $market_page;
    private $search_page;
    private $url_page;
    private $price_ether;
    private $crypto_network;

    public function __construct()
    {

        add_shortcode('crypto-domain-info', array($this, 'start'));
        $this->search_page = crypto_get_option('search_page', 'crypto_marketplace_settings', 0);
        $this->market_page = crypto_get_option('market_page', 'crypto_marketplace_settings', 0);
        $this->url_page = crypto_get_option('url_page', 'crypto_marketplace_settings', 0);
        $this->price_ether = crypto_get_option('price_ether', 'crypto_marketplace_settings', '5');
        $this->crypto_network = crypto_get_option('crypto_network', 'crypto_marketplace_settings', '137');
    }

    public function start()
    {
        ob_start();
        global $wp_query;
        if (0 != $this->search_page) {
            $this->search_page = esc_url(get_page_link($this->search_page));
        } else {
            $this->search_page = "#";
        }
        if (0 != $this->market_page) {
            $this->market_page = esc_url(get_page_link($this->market_page));
        } else {
            $this->market_page = "#";
        }


        if (isset($_GET['domain'])) {

?>

<script>
jQuery(document).ready(function() {
    jQuery("#crypto_available").hide();
    jQuery("#crypto_manage_domain").hide();
    jQuery("#crypto_ipfs_domain").hide();
    jQuery("#crypto_blockchain_url").hide();
    jQuery("#crypto_register_domain").hide();

    var final_domain = "<?php echo sanitize_text_field($_GET['domain']); ?>";

    jQuery("[id=crypto_domain_name]").html(final_domain);

    jQuery("#crypto_manage_domain").attr("href",
        "<?php echo get_site_url(); ?>/web3/" + final_domain +
        "/?domain=manage");
    jQuery("#crypto_ipfs_domain").attr("href",
        "<?php echo get_site_url(); ?>/web3/" + final_domain +
        "/");

    crypto_start('');

    function crypto_start(method) {
        crypto_is_metamask_Connected().then(acc => {
            if (acc.addr == '') {
                //console.log("Metamask is not connected. Please connect to it first.");
                jQuery('#json_container').html(
                    '<div class="crypto_alert-box crypto_error">Metamask is not connected. Please connect to it first.</div>'
                );
                jQuery("#crypto_loading").hide();

            } else {
                jQuery("#crypto_loading").show();
                console.log("Connected to:" + acc.addr + "\n Network:" + acc.network);

                if ((acc.network != '<?php echo $this->crypto_network; ?>')) {
                    var msg =
                        "Please change your network to Polygon (MATIC). Your currently connected network is " +
                        acc.network;
                    jQuery('#json_container').html(
                        '<div class="crypto_alert-box crypto_error">' + msg + '</div>'
                    );
                    jQuery("#crypto_loading").hide();
                    // jQuery("[id=crypto_msg_ul]").empty();
                    //  jQuery("[id=crypto_msg_ul]").append(msg).fadeIn("normal");
                } else {
                    //  crypto_init();
                    web3 = new Web3(window.ethereum);

                    const connectWallet = async () => {
                        const accounts = await ethereum.request({
                            method: "eth_requestAccounts"
                        });
                        var persons = [];
                        account = accounts[0];
                        // console.log(`Connectedxxxxxxx account...........: ${account}`);

                        jQuery("[id=crypto_wallet_address]").html(crypto_network_arr[acc
                                .network])
                            .fadeIn(
                                "normal");

                        // getBalance(account);
                        await crypto_sleep(1000);
                        var domain_id = await getId(
                            '<?php echo sanitize_text_field($_GET['domain']); ?>');
                        jQuery('#json_container').html('Checking ownership...');
                        if (typeof domain_id !== 'undefined') {
                            if (acc.network == '137') {
                                jQuery("#crypto_blockchain_url").attr("href",
                                    "<?php echo CRYPTO_POLYGON_URL; ?>" + domain_id);
                            } else {
                                jQuery("#crypto_blockchain_url").attr("href",
                                    "<?php echo CRYPTO_FILECOIN_URL; ?>" + domain_id);
                            }
                            //console.log(domain_id);

                            jQuery("#crypto_manage_domain").show();
                            jQuery("#crypto_ipfs_domain").show();
                            jQuery("#crypto_blockchain_url").show();

                            var domain_owner = await getOwner(domain_id);
                            console.log('Domain owner ' + domain_owner);
                            jQuery("#crypto_available").show();
                            jQuery('#crypto_available_text').html(domain_owner);

                            if (domain_owner.toLowerCase() === account.toLowerCase()) {
                                console.log("Authorized");
                                jQuery('#json_container').html('');
                                jQuery("#transfer_box").show();
                                jQuery("#crypto_claim_box").hide();
                                if (method == 'crypto_transfer') {

                                    console.log('Ready to transfer');
                                    var transfer_to = jQuery('#to_add').val();

                                    if (!transfer_to) {
                                        alert("Enter polygon wallet address");
                                        // coin_toggle_loading("end");
                                        // jQuery('#json_container').html('Transfer cancel');
                                        jQuery('#json_container').html(
                                            '<div class="crypto_alert-box crypto_warning">Transfer cancelled</div>'
                                        );
                                    } else {
                                        // alert(curr_user + " - " + transfer_to + " - " + claim_id);
                                        var domain_transfer = await transferFrom(transfer_to,
                                            domain_id);
                                        console.log(domain_transfer);
                                        if (domain_transfer == true) {
                                            jQuery('#json_container').html(
                                                '<div class="crypto_alert-box crypto_success">Successfully transfer to  <strong>' +
                                                transfer_to +
                                                '</strong></div>');
                                            jQuery("#transfer_box").hide();
                                            jQuery("#crypto_claim_box").hide();
                                        } else {
                                            jQuery('#json_container').html(
                                                '<div class="crypto_alert-box crypto_notice">' +
                                                domain_transfer +
                                                '</div>');
                                        }
                                    }

                                }



                            } else {
                                //  console.log("Not authorized");
                                jQuery('#json_container').html(
                                    '<div class="crypto_alert-box crypto_warning"> Your are not owner of this domain name. Check your connected wallet address </div>'
                                );
                                jQuery("#crypto_manage_domain").hide();

                            }
                            jQuery("#crypto_loading").hide();
                        } else {
                            //  console.log("Domain not minted yet");
                            jQuery('#json_container').html(
                                '<div class="crypto_alert-box crypto_notice"> This domain has not been minted yet. </div>'
                            );
                            jQuery("#crypto_loading").hide();
                            jQuery("#crypto_register_domain").attr("href",
                                "<?php echo get_site_url(); ?>/web3/" + final_domain +
                                "/?domain=manage");
                            jQuery("#crypto_register_domain").show();
                        }

                        // console.log(contract);

                    };

                    connectWallet();
                    connectContract(contractAbi, contractAddress);




                }
            }
        });
    }


});
</script>
<div class="fl-columns">
    <div class="fl-column fl-is-three-quarters">

        <div class="fl-buttons fl-has-addons">
            <a href="<?php echo $this->search_page; ?>" class="fl-button ">Search</a>
            <a href="<?php echo $this->market_page; ?>" class="fl-button">My Domains</a>
            <a href="#" class="fl-button fl-is-success fl-is-selected">Domain Information</a>
        </div>
    </div>
    <div class="fl-column">
        <div id="crypto_wallet_address" class="fl-tag fl-is-warning"><img
                src="<?php echo esc_url(CRYPTO_PLUGIN_URL . '/public/img/loading.gif'); ?>" width="15"></div>
    </div>

</div>
<div class="fl-card" id="crypto_panel">
    <header class="fl-card-header">
        <p class="fl-card-header-title" id="crypto_domain_name">
            Web3 Domain Name
        </p>
    </header>
    <div class="fl-card-content">
        <div class="fl-content" id="crypto_domain_result_box">
            <div id="crypto_loading" style="text-align:center;"> <img
                    src="<?php echo esc_url(CRYPTO_PLUGIN_URL . '/public/img/loading.gif'); ?>" width="100">
            </div>

            <article class="fl-message fl-is-primary" id="crypto_available">
                <div class="fl-message-body">
                    <div class="fl-tags fl-has-addons">
                        <span class="fl-tag fl-is-large" id="crypto_domain_name">Domain Name</span>
                        <span class="fl-tag fl-is-primary fl-is-large" id="crypto_available_text">Available</span>

                    </div>
                </div>
            </article>


            <div id="json_container"></div>

        </div>

    </div>
    <footer class="fl-card-footer">
        <a href="#" class="fl-card-footer-item" id="crypto_blockchain_url" target="_blank">Blockchain Record</a>
        <a href="#" class="fl-card-footer-item" id="crypto_manage_domain">Manage Domain</a>
        <a href="<?php echo $this->url_page; ?>" target="_blank" class="fl-card-footer-item"
            id="crypto_ipfs_domain">Visit Site</a>
        <a href="#" class="fl-card-footer-item" id="crypto_register_domain">Register
            Domain</a>

    </footer>
</div>
<?php
        } else {
            echo "No domain";
        }


        $content = ob_get_clean();
        return $content;
    }
}
new Crypto_Domain_INFO();