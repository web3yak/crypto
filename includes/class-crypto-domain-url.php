<?php
class Crypto_Domain_URL
{
    private $market_page;
    private $search_page;
    private $url_page;
    private $price_ether;
    private $crypto_network;

    public function __construct()
    {
        add_filter('init', array($this, 'rw_init'));
        add_filter('query_vars', array($this, 'rw_query_vars'));
        add_shortcode('crypto-domain-url', array($this, 'start'));
        $this->search_page = crypto_get_option('search_page', 'crypto_marketplace_settings', 0);
        $this->market_page = crypto_get_option('market_page', 'crypto_marketplace_settings', 0);
        $this->url_page = crypto_get_option('url_page', 'crypto_marketplace_settings', 0);
        $this->price_ether = crypto_get_option('price_ether', 'crypto_marketplace_settings', '5');
        $this->crypto_network = crypto_get_option('crypto_network', 'crypto_marketplace_settings', '137');


        add_action('wp_ajax_crypto_ajax_record', array($this, 'crypto_ajax_record'));
        add_action('wp_ajax_nopriv_crypto_ajax_record', array($this, 'crypto_my_must_login'));
    }


    //update primary image from edit screen
    public function crypto_ajax_record()
    {


        if (
            !isset($_POST['crypto-nonce'])
            || !wp_verify_nonce($_POST['crypto-nonce'], 'crypto-nonce')
        ) {
            $response = array(
                'error' => true,
                'msg' => 'Invalid Form submission',
            );
            exit('The form is not valid');
        }

        // A default response holder, which will have data for sending back to our js file
        $response = array(
            'error' => false,
            'msg' => 'No Message',
        );

        $crypto_profile_name = "";
        $crypto_website_url = "";
        $crypto_email = "";
        $crypto_desp = "";

        if (isset($_POST['domain_name'])) {

            if (isset($_POST['crypto_profile_name']) && $_POST['crypto_profile_name'] !== "") {
                $crypto_profile_name = sanitize_text_field($_POST['crypto_profile_name']);
            }

            if (isset($_POST['crypto_email'])) {
                $crypto_email = sanitize_text_field($_POST['crypto_email']);
            }

            if (isset($_POST['crypto_website_url'])) {
                $crypto_website_url = sanitize_text_field($_POST['crypto_website_url']);
            }

            if (isset($_POST['crypto_desp'])) {
                $crypto_desp = sanitize_text_field($_POST['crypto_desp']);
            }
            // crypto_log($_POST['crypto_desp']);

            $gen_json = new Crypto_Generate_Json();
            $msg = $gen_json->create_json(sanitize_text_field($_POST['domain_name']), $edit = true, $crypto_profile_name, $crypto_email, $crypto_website_url, $crypto_desp);

            $response['msg'] = $msg;
            echo wp_json_encode($response);
            die();
        }

        // crypto_log($_POST);
        // Don't forget to exit at the end of processing
        //  crypto_log($response['msg']);
        echo wp_json_encode($response);
        die();
    }

    public function crypto_my_must_login()
    {
        crypto_log("logout....");
        echo __('Login Please !', 'crypto');
        die();
        wp_die();
    }

    public function rw_query_vars($aVars)
    {
        $aVars[] = "web3domain"; // represents the name of the variable as shown in the URL
        return $aVars;
    }

    public function rw_init()
    {
        add_rewrite_rule(
            '^web3/([^/]*)$',
            'index.php?web3domain=$matches[1]&page_id=' . $this->url_page,
            'top'
        );
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

        $uploaddir = wp_upload_dir();
?>

<div class="fl-columns">
    <div class="fl-column fl-is-three-quarters">

        <div class="fl-buttons fl-has-addons">
            <a href="<?php echo $this->search_page; ?>" class="fl-button ">Search</a>
            <a href="<?php echo $this->market_page; ?>" class="fl-button">My Domains</a>
            <a href="#" class="fl-button fl-is-success fl-is-selected">Manage Domain</a>
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

            <div id="crypto_loading_url" style="text-align:center;"> Please wait, redirection in progress...
                <br>
                <a href="#" id="crypto_loading_url_link">Direct External Link</a>
            </div>
            <article class="fl-message fl-is-danger" id="crypto_unavailable">
                <div class="fl-message-body">
                    <div class="fl-tags fl-has-addons">
                        <span class="fl-tag fl-is-large" id="crypto_domain_name">Domain Name</span>
                        <span class="fl-tag fl-is-danger fl-is-large" id="crypto_domain_name_error">Website not
                            available</span>
                    </div>
                </div>
            </article>

            <div id="json_container"></div>

        </div>




        <?php
                if (isset($wp_query->query_vars['web3domain'])) {
                    $subdomain = $wp_query->query_vars['web3domain'];
                    $subdomain = strtolower($subdomain);
                    if (isset($_GET['domain'])) {
                        $nonce = wp_create_nonce('crypto_ajax');
                        $curr_url = $_SERVER['REQUEST_URI'];

                ?>

        <script>
        jQuery(document).ready(function() {
            jQuery("#crypto_unavailable").hide();
            jQuery("#crypto_loading_url").hide();
            jQuery("[id=crypto_domain_name]").html('<?php echo $subdomain; ?>');
            jQuery("#transfer_box").hide();
            jQuery("#crypto_claim_box").hide();
            jQuery("#record_box").hide();
            jQuery("#crypto_manage_tab").hide();
            jQuery("#crypto_publish_box").hide();
            crypto_start('');

            jQuery("#transfer").click(function() {
                //alert("Transfer");
                //coin_toggle_loading("start");
                crypto_start('crypto_transfer');
            });

            jQuery("#crypto_claim").click(function() {
                //alert("claim");
                //coin_toggle_loading("start");
                crypto_claim();
            });

            jQuery("#crypto_publish_record").click(function() {
                // alert("save record");
                //coin_toggle_loading("start");

                jQuery('#crypto_publish_record').addClass('fl-is-loading');
                crypto_start("crypto_record");
            });

        });

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
                            "Please change your network to " + crypto_network_arr[
                                '<?php echo $this->crypto_network; ?>'] +
                            ". Your currently connected network is " +
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
                            var domain_id = await getId('<?php echo $subdomain; ?>');
                            jQuery('#json_container').html('Checking ownership...');
                            if (typeof domain_id !== 'undefined') {
                                console.log(domain_id);
                                var domain_owner = await getOwner(domain_id);
                                console.log('Domain owner ' + domain_owner);
                                await crypto_sleep(3000);
                                if (typeof domain_owner !== 'undefined') {
                                    //all is fine
                                } else {
                                    location.reload();
                                }
                                if (domain_owner.toLowerCase() === account.toLowerCase()) {
                                    console.log("Authorized");
                                    jQuery("#crypto_manage_tab").show();
                                    jQuery('#json_container').html('');
                                    var method_url =
                                        '<?php echo sanitize_text_field($_GET['domain']); ?>';

                                    console.log("Manage: " + method_url);
                                    if (method_url == 'record') {
                                        jQuery("#record_box").show();
                                        jQuery("#transfer_box").hide();
                                        jQuery("#crypto_claim_box").hide();
                                        jQuery("#crypto_tab_transfer").attr("class", "");
                                        jQuery("#crypto_tab_record").attr("class", "fl-is-active");

                                    } else {
                                        jQuery("#transfer_box").show();
                                        jQuery("#crypto_claim_box").hide();

                                    }
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
                                                jQuery("#record_box").hide();
                                            } else {
                                                jQuery('#json_container').html(
                                                    '<div class="crypto_alert-box crypto_notice">' +
                                                    domain_transfer +
                                                    '</div>');
                                            }
                                        }

                                    }

                                    if (method == 'crypto_record') {

                                        console.log('Ready to publish');
                                        var claim_url =
                                            '<?php echo $uploaddir['baseurl'] . '/yak/' . $subdomain . '_edit.json'; ?>';
                                        console.log(claim_url);
                                        var TokenURI = await setTokenURI(domain_id, claim_url);
                                    }



                                } else {
                                    //  console.log("Not authorized");
                                    jQuery('#json_container').html(
                                        '<div class="crypto_alert-box crypto_warning"> You are not the owner of this domain name. Please check the connected wallet address. </div>'
                                    );
                                    jQuery("#transfer_box").hide();
                                    jQuery("#crypto_claim_box").hide();
                                    jQuery("#record_box").hide();
                                }
                                jQuery("#crypto_loading").hide();
                            } else {
                                //  console.log("Domain not minted yet");
                                jQuery('#json_container').html(
                                    '<div class="crypto_alert-box crypto_notice"> This domain has not been minted yet. </div>'
                                );
                                jQuery("#crypto_loading").hide();
                                jQuery("#crypto_claim_box").show();
                                jQuery("#record_box").hide();
                            }

                            // console.log(contract);

                        };

                        connectWallet();
                        connectContract(contractAbi, contractAddress);




                    }
                }
            });
        }




        function crypto_claim() {
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
                            console.log(`Connect account...........: ${account}`);
                            // getBalance(account);
                            await crypto_sleep(1000);

                            var claim_id = crypto_uniqueId();
                            var claim_name = '<?php echo $subdomain; ?>';
                            var claim_url =
                                '<?php echo $uploaddir['baseurl'] . '/yak/' . $subdomain . '.json'; ?>';
                            var claim_transfer_to = account;
                            var amount = "<?php echo  $this->price_ether; ?>";
                            var domain_claim = await claim(claim_id, claim_name, claim_url,
                                claim_transfer_to, amount);
                            jQuery('#json_container').html('Claim Started...');
                            if (domain_claim == true) {
                                jQuery('#json_container').html(
                                    '<div class="crypto_alert-box crypto_success">Successfully minted and domain transferred to <strong>' +
                                    claim_transfer_to +
                                    '</strong></div>');

                                jQuery("#crypto_claim_box").hide();
                                jQuery("#crypto_loading").hide();
                            } else {
                                jQuery('#json_container').html(
                                    '<div class="crypto_alert-box crypto_notice">' +
                                    domain_claim +
                                    '</div>');
                                jQuery("#crypto_loading").hide();
                            }

                            // console.log(contract);

                        };

                        connectWallet();
                        connectContract(contractAbi, contractAddress);




                    }
                }
            });
        }
        </script>
        <div class="fl-tabs fl-is-boxed" id="crypto_manage_tab">
            <ul>
                <li id="crypto_tab_transfer" class="fl-is-active">
                    <a href="<?php echo get_site_url() . '/web3/' . $subdomain . '/?domain=transfer'; ?>">
                        <span class="fl-icon fl-is-small"><i class="fas fa-image" aria-hidden="true"></i></span>
                        <span>Transfer</span>
                    </a>
                </li>
                <li id="crypto_tab_record" class="">
                    <a href="<?php echo get_site_url() . '/web3/' . $subdomain . '/?domain=record'; ?>">
                        <span class="fl-icon fl-is-small"><i class="fas fa-music" aria-hidden="true"></i></span>
                        <span>Record</span>
                    </a>
                </li>
                <li id="crypto_tab_claim" class="">
                    <a>
                        <span class="fl-icon fl-is-small"><i class="fas fa-film" aria-hidden="true"></i></span>
                        <span>Videos</span>
                    </a>
                </li>
                <li>
                    <a>
                        <span class="fl-icon fl-is-small"><i class="far fa-file-alt" aria-hidden="true"></i></span>
                        <span>Documents</span>
                    </a>
                </li>
            </ul>
        </div>

        <div id="transfer_box">
            <div class="fl-column fl-is-full">
                <div class="fl-box">
                    <div class="fl-field">
                        <label class="fl-label">Transfer the Web3Domain "<?php echo $subdomain; ?>" to another
                            wallet</label>
                        <div class="fl-control">
                            <input class="fl-input" id="to_add"
                                placeholder="e.g. 0xf11a4fac7b7839771da0a526145198e99d0575be">
                        </div>
                    </div>
                    <p class="fl-help fl-is-success">
                        This will transfer ownership of the current NFT domain to a new owner.<br>
                        Please ensure to enter the correct wallet address for the selected network.<br>
                        This transaction cannot be undone.
                        <br>
                    </p>

                    <div class="fl-control">
                        <button class="fl-button fl-is-primary" id="transfer">Transfer</button>
                    </div>



                </div>
            </div>
        </div>

        <div id="crypto_claim_box">
            <div class="fl-column fl-is-full">
                <div class="fl-box">


                    <div class="fl-field">
                        <label class="fl-label">Create a Web3Domain Name : <?php echo $subdomain; ?></label>

                    </div>
                    <p class="fl-help fl-is-success">
                        This will register a Web3Domain Name and store it as an NFT in your wallet.<br>
                        Please ensure that your Metamask is connected to the specified network.<br>
                        Afterwards, you can import your domain to other sites for additional functionality.<br><br></p>

                    <div class="fl-control">
                        <button class="fl-button fl-is-primary" id="crypto_claim">Claim Now</button>
                    </div>

                    <?php do_action("crypto_ipfs_upload", $subdomain);
                                    ?>

                </div>
            </div>
        </div>

        <?php

                        $gen_json = new Crypto_Generate_Json();

                        ?>

        <form id="crypto-record-form" class="crypto_ajax_record" method="post"
            action="<?php echo admin_url("/admin-ajax.php"); ?>">
            <input type="hidden" name="action" value="crypto_ajax_record">
            <input type="hidden" name="domain_name" value="<?php echo $subdomain; ?>">
            <?php wp_nonce_field('crypto-nonce', 'crypto-nonce', false); ?>
            <div id="record_box">
                <div class="fl-column fl-is-full">
                    <div class="fl-box">
                        <div class="fl-field">
                            <label class="fl-label">Full Name</label>
                            <div class="fl-control fl-has-icons-left fl-has-icons-right">
                                <input class="fl-input" type="text" placeholder="Public display name"
                                    name="crypto_profile_name"
                                    value="<?php echo $gen_json->fetch($subdomain, 'name'); ?>">
                                <span class="fl-icon fl-is-small is-left">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>

                        <div class="fl-field">
                            <label class="fl-label">Web Site URL</label>
                            <div class="fl-control fl-has-icons-left fl-has-icons-right">
                                <input class="fl-input fl-is-success" type="text" placeholder="http://"
                                    name="crypto_website_url"
                                    value="<?php echo $gen_json->fetch($subdomain, 'web_url'); ?>">
                                <span class="fl-icon fl-is-small is-left">
                                    <i class="fas fa-link"></i>
                                </span>

                            </div>
                            <p class="fl-help fl-is-success">Enter full website URL.</p>
                        </div>

                        <div class="fl-field">
                            <label class="fl-label">Email Address</label>
                            <div class="fl-control fl-has-icons-left fl-has-icons-right">
                                <input class="fl-input" type="email"
                                    value="<?php echo $gen_json->fetch($subdomain, 'email'); ?>" name="crypto_email">
                                <span class="fl-icon fl-is-small is-left">
                                    <i class="fas fa-envelope"></i>
                                </span>

                            </div>
                        </div>



                        <div class="fl-field">
                            <label class="fl-label">Description</label>
                            <div class="fl-control">
                                <textarea class="fl-textarea"
                                    placeholder="About yourself , Company, Bank details / Communication Address / Notice"
                                    name="crypto_desp"> <?php echo $gen_json->fetch($subdomain, 'notes'); ?></textarea>
                            </div>
                        </div>




                        <div class="fl-field fl-is-grouped">
                            <div class="fl-control">
                                <button type="submit" name="submit" id="crypto_save_record"
                                    class="fl-button fl-is-link">Save Draft</button>

                            </div>
                            <?php

                                            $uploaddir = wp_upload_dir();
                                            $base_path =  $uploaddir['basedir'] . "/yak/" . $subdomain . '_edit.json'; //upload dir.
                                            if (file_exists($base_path)) {
                                            ?>
                            <script>
                            jQuery(document).ready(function() {
                                jQuery("#crypto_publish_box").show();
                            });
                            </script>
                            <?php
                                            }
                                            ?>
                            <div class="fl-control" id="crypto_publish_box">
                                <button type="button" class="fl-button fl-is-success" id="crypto_publish_record">Publish
                                    to
                                    Blockchain</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <?php
                    } else {
                    ?>



        <script>
        jQuery(document).ready(function() {
            jQuery("#crypto_unavailable").hide();
            crypto_check_w3d_name_json('<?php echo $subdomain; ?>');

            function crypto_check_w3d_name_json(domain_name) {
                jQuery("[id=crypto_domain_name]").html(domain_name + "");
                fetch('https://w3d.name/api/v1/index.php?domain=' + domain_name)
                    .then(res => res.json())
                    .then((out) => {
                        console.log('Output: ', out);
                        jQuery("[id=crypto_wallet_address]").html(domain_name)
                            .fadeIn(
                                "normal");
                        if (typeof out.error !== 'undefined') {
                            //      console.log("This domain name is available to mint.");
                            //  jQuery("[id=crypto_domain_name]").html(domain_name + "");
                            jQuery("#crypto_loading").hide();
                            jQuery("#crypto_loading_url").hide();
                            jQuery("#crypto_unavailable").show();
                        } else {
                            console.log("Already registered");
                            //console.log(out);
                            // jQuery("#crypto_loading").hide();
                            jQuery("#crypto_unavailable").hide();

                            if (crypto_ValidURL(out.records["50"].value)) {
                                console.log("Valid URL " + out.records["50"].value);
                                var web_url = out.records["50"].value;
                            } else {
                                console.log("Invalid URL " + out.records["50"].value);
                                var web_url = "https://ipfs.io/ipfs/" + out.records["50"].value;
                            }

                            var web3_url = '';
                            if (out.records.hasOwnProperty('51')) {
                                var web3_url = out.records["51"].value;
                            }
                            jQuery("#crypto_loading_url").show();
                            jQuery("#crypto_loading_url_link").attr("href", web_url);
                            if (web3_url != '') {
                                console.log(web3_url);
                                window.location.href = web3_url;
                            } else {
                                console.log(web_url);

                                window.location.href = web_url;

                            }


                        }
                    }).catch(err => console.error(err));
            }
        });
        </script>


        <?php

                    }
                    ?>
    </div>
</div>
<?php
                }
                $content = ob_get_clean();
                return $content;
            }

            public function fetch_url($domain_name, $update)
            {
                $uri = "https://w3d.name/api/v1/index.php?domain=" . $domain_name . "&" . rand();

                if ($update == 'true') {
                    $uri = "https://w3d.name/api/v1/index.php?domain=" . $domain_name . "&update=true&" . rand();
                }

                // Open file
                $handle = @fopen($uri, 'r');

                // Check if file exists
                if ($handle) {

                    $json = crypto_file_get_contents_ssl($uri);
                    //var_dump($json);
                    $json_data = json_decode($json, true);
                    //return $json_data;
                    if (isset($json_data['records']['51']['value']) && $json_data['records']['51']['value'] != '') {

                        return $json_data['records']['51']['value'];
                    } else {
                        if (isset($json_data['records']['50']['value'])) {
                            return 'https://ipfs.io/ipfs/' . $json_data['records']['50']['value'];
                        } else {
                            return "";
                        }
                    }
                }
            }
        }
        new Crypto_Domain_URL();