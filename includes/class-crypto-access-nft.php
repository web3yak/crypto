<?php
class Crypto_Access_NFT
{
	private $chainid;
	private $contract;
	private $nft_name;
	private $nft_count;
	private $nft_type;
	private $default_access;
	private $crypto_network;

	public function __construct()
	{
		$this->default_access = crypto_get_option('select_access_control', 'crypto_access_settings_start', 'web3domain');
		$this->chainid = crypto_get_option('chainid', 'crypto_access_other', '1');
		$this->contract = crypto_get_option('chain_contract', 'crypto_access_other', '0x.......');
		$this->nft_name = crypto_get_option('nft_name', 'crypto_access_other', 'NFT of Something');
		$this->nft_count = crypto_get_option('nft_count', 'crypto_access_other', '1');
		$this->nft_type = crypto_get_option('nft_type', 'crypto_access_other', 'coin');
		add_filter('crypto_settings_sections', array($this, 'add_section'));
		add_filter('crypto_settings_fields', array($this, 'add_fields'));
		add_shortcode('crypto-access-nft', array($this, 'crypto_access_box'));
		$this->crypto_network = crypto_get_option('crypto_network', 'crypto_marketplace_settings', '137');
	}

	//Add Section title
	public function add_section($new)
	{

		$sections = array(
			array(
				'id' => 'crypto_access_other',
				'title' => __('Cryptocurrency & Non-Fungible Token (NFT) Access', 'crypto'),
				'description' => __('Limit access to specific sections of the website based on the availability of NFT tokens.', 'crypto') . "<br><br><b>Shortcode for limiting access to content</b><br>The shortcode should be written as <code>[crypto-block] for private information or content between the shortcode. [/crypto-block]</code><b><br><br>To limit access to the entire page.</b><br><code>Edit the desired page, and use the option from the setting panel to limit access.</code>",
				'tab' => 'access',
			),
		);
		$new = array_merge($new, $sections);
		//  }
		return $new;
	}

	//Add section fields
	public function add_fields($new)
	{

		$fields = array(
			'crypto_access_other' => array(
				array(
					'name' => 'chainid',
					'label' => __('Blockchain Network', 'crypto'),
					'description' => __('Choose the blockchain on which the contract address is used.', 'crypto'),
					'type' => 'select',
					'options' => array(

						'1' => __('Ethereum Mainnet', 'crypto'),
						'137' => __('Matic - Polygon Mainnet', 'crypto'),
						'56' => __('BNB Smart Chain', 'crypto'),
						'80001' => __('Mumbai Testnet', 'crypto'),
					)
				),
				array(
					'name' => 'chain_contract',
					'label' => __('Contract address', 'crypto'),
					'description' => __('Enter the Contract address of the NFT or token, it should start with "0x..."', 'crypto'),
					'size' => 'large',
					'type' => 'text',
				),
				array(
					'name' => 'nft_name',
					'label' => __('Token Name', 'crypto'),
					'description' => __('Enter the name of the NFT or token.', 'crypto'),
					'size' => 'large',
					'type' => 'text',
				),
				array(
					'name' => 'nft_count',
					'label' => __('NFT or Crypto count', 'crypto'),
					'description' => __('Enter the number of NFTs/tokens that must be available for access to be granted.', 'crypto'),
					'type' => 'number',
					'size' => 'medium',
					'min' => '0.0',
					'max' => '9999999999999999999999999',
					'step' => 'any'
				),

				array(
					'name'              => 'nft_type',
					'label'             => __('Crypto Type', 'flexi'),
					'description'       => '',
					'type'              => 'radio',
					'options'           => array(
						'coin'   => __('Coin (Eg. ERC-20)', 'flexi'),
						'nft' => __('NFT (Eg. ERC-721)', 'flexi'),
					),
					'sanitize_callback' => 'sanitize_key',
				),


				array(
					'name' => 'restrict_page',
					'label' => __('Limit access to the page', 'crypto'),
					'description' => __('To limit access to the entire page, add the shortcode [crypto-connect label="Connect Wallet" class="fl-button fl-is-info fl-is-light"] [crypto-access-nft].', 'crypto'),
					'type' => 'pages',
					'sanitize_callback' => 'sanitize_key',
				),
			)
		);
		$new = array_merge($new, $fields);
		return $new;
	}


	public function crypto_access_box()
	{
		$arr = array('1' => 'Ethereum Mainnet', '137' => 'Matic - Polygon Mainnet', '56' => 'BNB Smart Chain', '80001' => 'Mumbai Testnet');
		$put = "";
		ob_start();
		$nonce = wp_create_nonce('crypto_ajax');
		if (is_user_logged_in()) {
			if ($this->default_access == 'nft') {
				$saved_array = get_user_meta(get_current_user_id(),  'domain_names');
?>


				<script>
					crypto_is_metamask_Connected().then(acc => {
						if (acc.addr == '') {
							console.log("Metamask is not connected. Please connect to it first.");
						} else {
							console.log("Connected to:" + acc.addr + "\n Network:" + acc.network);

							if ((acc.network != '<?php echo $this->chainid; ?>')) {
								var msg =
									"Change your network to <?php echo $arr[$this->chainid]; ?>. Your connected network is " +
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
									// console.log(`Connected..... account...........: ${account}`);
									// getBalance(account);
									await crypto_sleep(1000);
									var nft_count = await balanceOf(account);
									console.log(nft_count);

									<?php
									if ($this->nft_type == 'coin') {
									?>
										const formattedResult = web3.utils.fromWei(nft_count, "ether");
										//      console.log(count + " Balance is " + formattedResult + " -- " + count / 100000000);
										jQuery("[id=crypto_msg_ul]").empty();
										jQuery("[id=crypto_msg_ul]").append("<li>Crypto Found: <strong>" +
											formattedResult +
											"</strong></li>").fadeIn("normal");
										console.log(formattedResult);

										if (formattedResult < <?php echo $this->nft_count; ?>) {
											// console.log("zero domain");
											jQuery("[id=crypto_msg_ul]").append(
													"<li>Your wallet do not have sufficient '<?php echo $this->nft_name; ?>'. <br>Required: <strong><?php echo $this->nft_count; ?></strong> <br><strong>Account restricted.</strong> </li>"
												)
												.fadeIn("normal");

										} else {
											console.log("sufficient");
										}

									<?php
									} else {
									?>
										const formattedResult = web3.utils.fromWei(nft_count, "wei");
										//      console.log(count + " Balance is " + formattedResult + " -- " + count / 100000000);
										jQuery("[id=crypto_msg_ul]").empty();
										jQuery("[id=crypto_msg_ul]").append("<li>NFT Found: <strong>" +
											formattedResult +
											"</strong></li>").fadeIn("normal");
										console.log(formattedResult);

										if (formattedResult < <?php echo $this->nft_count; ?>) {
											// console.log("zero domain");
											jQuery("[id=crypto_msg_ul]").append(
													"<li>Your wallet do not have sufficient '<?php echo $this->nft_name; ?>'. <br>Required: <strong><?php echo $this->nft_count; ?></strong> <br><strong>Account restricted.</strong> </li>"
												)
												.fadeIn("normal");

										} else {
											console.log("sufficient");
										}


									<?php
									}
									?>

									create_link_crypto_connect_login('<?php echo sanitize_key($nonce); ?>', '',
										'savenft',
										account, '', formattedResult);

									setTimeout(function() {
										jQuery('#crypto_connect_ajax_process').trigger('click');
									}, 1000);
									// console.log(contract);

								};

								connectWallet();
								const nft_contractAddress = '<?php echo $this->contract; ?>';
								console.log("NFT Contract address: " + nft_contractAddress);
								connectContract(contractAbi, nft_contractAddress);



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
						<span class="fl-tag fl-is-primary"><?php echo "." . $this->nft_name; ?> holder</span>
					</div>
				<?php
				} else {
				?>

					<div class="fl-tags fl-has-addons">
						<span class="fl-tag">Account Status (<?php echo $current_user->user_login; ?>)</span>
						<span class="fl-tag fl-is-danger"><?php echo "." . $this->nft_name; ?>: <?php echo "." . $this->nft_count; ?>
							required</span>
					</div>
				<?php
				}
				?>
				<div class="fl-message fl-is-dark">
					<div class="fl-message-body">
						Some content or pages on the site is accessible only to the selected member who owns
						<strong><?php echo $this->nft_name; ?></strong>
					</div>
				</div>
				<div class="fl-message" id="crypto_msg">
					<div class="fl-message-header">
						<p>Available domains into network ID : <b><?php echo $arr[$this->chainid]; ?></b></p>
					</div>
					<div class="fl-message-body" id="crypto_msg_body">
						<ul id="crypto_msg_ul">

						</ul>
					</div>
				</div>
				<div>
					<a href="#" id="check_domain" onclick="location.reload();" class="fl-button fl-is-link fl-is-light">Check again for
						:
						<?php echo $this->nft_name; ?></a>
				</div>

				<br>

				<br>

			<?php
			} else {
				echo " <div class='fl-message-body'>NFT & Crypto access is disabled. Enable it from settings</div>";
			}
		} else {
			?>
			<br>
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
new Crypto_Access_NFT();
