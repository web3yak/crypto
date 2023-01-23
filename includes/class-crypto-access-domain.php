<?php
class Crypto_Access
{
    private $domain_name;
    private $default_access;
    private $crypto_network;

    public function __construct()
    {
        $this->default_access = crypto_get_option('select_access_control', 'crypto_access_settings_start', 'web3domain');
        $this->domain_name = crypto_get_option('domain_name', 'crypto_access_settings', 'web3');
        add_shortcode('crypto-access-domain', array($this, 'crypto_access_box'));
        add_filter('crypto_settings_tabs', array($this, 'add_tabs'));
        add_filter('crypto_settings_sections', array($this, 'add_section_extension'));
        add_filter('crypto_settings_sections', array($this, 'add_section'));
        add_filter('crypto_settings_fields', array($this, 'add_fields'));
        $this->crypto_network = crypto_get_option('crypto_network', 'crypto_marketplace_settings', '137');


        add_filter('crypto_settings_fields', array($this, 'add_extension'));
        //add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('crypto_web3domain', plugin_dir_url(__DIR__) . 'public/js/web3domain.js', array('jquery'), '', false);
    }


    //add_filter flexi_settings_tabs
    public function add_tabs($new)
    {

        $tabs = array(
            'access'   => __('Access Control', 'crypto'),

        );
        $new  = array_merge($new, $tabs);

        return $new;
    }



    public function add_section($new)
    {

        $sections = array(
            array(
                'id' => 'crypto_access_settings',
                'title' => __('Web3Domain Access', 'crypto'),
                'description' => __('Limit access to specific areas of the website based on the availability of a Web3Domain.', 'crypto') . "<br>Get domain from <a href='" . esc_url('https://web3domain.org/') . "' target='_blank'>Web3Domain.org</a><br><br>" . "<b>Shortcode for limiting access to content</b><br>The shortcode should be written as <code>[crypto-block] for private information or content between the shortcode. [/crypto-block]</code><b><br><br>To limit access to the entire page.</b><br><code>Edit the desired page, and use the option from the setting panel to limit access.</code>",
                'tab' => 'access',
            ),
        );
        $new = array_merge($new, $sections);

        return $new;
    }

    public function add_section_extension($new)
    {

        $sections = array(
            array(
                'id' => 'crypto_access_settings_start',
                'title' => __('Access Control Configuration', 'crypto'),
                'description' => __('You can only use one access control setting at a time. Choose your preferred option.', 'crypto'),
                'tab' => 'access',
            ),
        );
        $new = array_merge($new, $sections);

        return $new;
    }

    //Add section fields
    public function add_fields($new)
    {
        $fields = array(
            'crypto_access_settings' => array(

                array(
                    'name' => 'domain_name',
                    'label' => __('TLD of the Web3Domain Name', 'crypto'),
                    'description' => __('Enter the primary Web3Domain name. Access to this page will only be granted to users who have a sub-domain of this name in their wallet.', 'crypto'),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_key',
                ),

                array(
                    'name' => 'restrict_page',
                    'label' => __('Limit access to the page', 'crypto'),
                    'description' => __('To limit access to the entire page, add the shortcode [crypto-connect label="Connect Wallet" class="fl-button fl-is-info fl-is-light"] [crypto-access-domain]', 'crypto'),
                    'type' => 'pages',
                    'sanitize_callback' => 'sanitize_key',
                ),

            ),
        );
        $new = array_merge($new, $fields);

        return $new;
    }

    public function add_extension($new)
    {

        $fields = array('crypto_access_settings_start' => array(
            array(
                'name' => 'select_access_control',
                'label' => __('Choose Access Control', 'flexi'),
                'description' => '',
                'type' => 'radio',
                'options' => array(
                    'web3domain' => __('Web3Domain Access', 'flexi'),
                    'nft' => __('Cryptocurrency & Non-Fungible Token (NFT) Access', 'flexi'),
                ),
                'sanitize_callback' => 'sanitize_key',
            ),
        ));
        $new = array_merge_recursive($new, $fields);

        return $new;
    }

    public function crypto_access_box()
    {
        $put = "";
        ob_start();
        $nonce = wp_create_nonce('crypto_ajax');
        if (is_user_logged_in()) {
            $default_access = crypto_get_option('select_access_control', 'crypto_access_settings_start', 'web3domain');

            if ($this->default_access == 'web3domain') {
                $saved_array = get_user_meta(get_current_user_id(),  'domain_names');
                // flexi_log($saved_array);
                $check = new crypto_connect_ajax_process();
                $check->checknft(get_current_user_id(),  $saved_array);
?>

                <script>
                    crypto_is_metamask_Connected().then(acc => {
                        if (acc.addr == '') {
                            console.log("Metamask is not connected. Please connect to it first.");
                        } else {
                            console.log("Connected to:" + acc.addr + "\n Network:" + acc.network);

                            if ((acc.network != '<?php echo $this->crypto_network; ?>')) {
                                var msg =
                                    "Please change your network to Polygon (MATIC). Your currently connected network is " +
                                    acc.network;
                                jQuery("[id=crypto_msg_ul]").empty();
                                jQuery("[id=crypto_msg_ul]").append(msg).fadeIn("normal");
                            } else {
                                //  crypto_init();
                                web3 = new Web3(window.ethereum);

                                const connectWallet = async () => {
                                    const accounts = await ethereum.request({
                                        method: "eth_requestAccounts"
                                    });
                                    var persons = [];
                                    account = accounts[0];
                                    //console.log(`Connectedxxxxxxx account...........: ${account}`);
                                    // getBalance(account);
                                    await crypto_sleep(1000);
                                    var domain_count = await balanceOf(account);
                                    console.log(domain_count);
                                    crypto_process_domain_count(domain_count, account);

                                    console.log(contract);
                                    persons.length = 0;
                                    for (let i = 0; i < domain_count; i++) {
                                        try {
                                            const nft = await contract.methods.tokenOfOwnerByIndex(account, i).call();
                                            //console.log(nft);
                                            var domain_name = await titleOf(nft);
                                            console.log(nft + ' = ' + domain_name);
                                            jQuery("[id=crypto_msg_ul]").append("<li>" + domain_name + "</li>").fadeIn(
                                                "normal");
                                            persons.push(domain_name);
                                            // console.log(i + " *** " + domain_count);
                                            if (i + 1 == domain_count) {
                                                console.log(persons);
                                                // console.log("sssss");
                                                process_login_savenft(account, persons, domain_count);
                                            }
                                        } catch (error) {
                                            console.log(error.message);
                                        }
                                    }
                                };

                                connectWallet();
                                connectContract(contractAbi, contractAddress);

                                function process_login_savenft(curr_user, persons, count) {


                                    create_link_crypto_connect_login('<?php echo sanitize_key($nonce); ?>', '', 'savenft',
                                        curr_user,
                                        persons, count);
                                    //  console.log(persons);
                                    setTimeout(function() {
                                        //alert("hi");
                                        jQuery('#crypto_connect_ajax_process').trigger('click');
                                    }, 1000);

                                }

                                function crypto_process_domain_count(count, account) {
                                    if (count == 0) {
                                        console.log("zero domain");
                                        jQuery("[id=crypto_msg_ul]").append(
                                                "<li>Your wallet do not have <?php echo "." . $this->domain_name; ?> Domain. <strong>Account restricted.</strong> </li>"
                                            )
                                            .fadeIn("normal");
                                        create_link_crypto_connect_login('<?php echo sanitize_key($nonce); ?>', '', 'savenft',
                                            account, '', count);

                                        setTimeout(function() {
                                            jQuery('#crypto_connect_ajax_process').trigger('click');
                                        }, 1000);
                                    }

                                }


                            }
                        }
                    });
                </script>
                <?php
                $check_access = new Crypto_Block();
                $current_user = wp_get_current_user();
                if ($check_access->crypto_can_user_view()) {

                ?>

                    <div class="fl-tags fl-has-addons">
                        <span class="fl-tag">Account Status (<?php echo $current_user->user_login; ?>)</span>
                        <span class="fl-tag fl-is-primary"><?php echo "." . $this->domain_name; ?> sub-domain holder</span>
                    </div>
                <?php
                } else {
                ?>

                    <div class="fl-tags fl-has-addons">
                        <span class="fl-tag">Account Status (<?php echo $current_user->user_login; ?>)</span>
                        <span class="fl-tag fl-is-danger"><?php echo "." . $this->domain_name; ?> sub-domain required</span>
                    </div>
                <?php
                }
                ?>


                <div class="fl-message fl-is-dark">
                    <div class="fl-message-body">
                        Some content or pages on the site is accessible only to the selected member who owns
                        <strong><?php echo "." . $this->domain_name; ?></strong>'s
                        sub-domain from <a href="https://www.web3domain.org/" target="_blank">web3domain.org</a>
                    </div>
                </div>

                <div class="fl-message" id="crypto_msg">
                    <div class="fl-message-header">
                        <p>Available domains into polygon address</p>
                    </div>
                    <div class="fl-message-body" id="crypto_msg_body">
                        <ul id="crypto_msg_ul">

                        </ul>
                    </div>
                </div>

                <div>
                    <a href="#" id="check_domain" onclick="location.reload();" class="fl-button fl-is-link fl-is-light">Check again for
                        :
                        <?php echo "." . $this->domain_name; ?> domain</a>
                </div>
            <?php
            } else {
                echo '<div class="fl-message-body">Web3Domain access is disabled. Enable it from settings</div>';
            }
        } else {
            ?>

            <div class="fl-message">
                <div class="fl-message-header">
                    <p>Please login</p>

                </div>
                <div class="fl-message-body">
                    After login you can check your wallet for eligibility.
                </div>
            </div>
<?php
        }
        $put = ob_get_clean();

        return $put;
    }
}
$price_page = new Crypto_Access();
