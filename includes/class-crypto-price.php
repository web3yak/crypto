<?php
class Crypto_Price
{
	private $help = ' <a style="text-decoration: none;" href="#" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>';

	private $curr;
	private $cache;
	private $theme;
	private $theme_color;

	public function __construct()
	{
		$this->curr = strtoupper(crypto_get_option('base_curr', 'crypto_price_settings', 'USD'));
		$this->cache = crypto_get_option('price_cache', 'crypto_price_settings', '600');
		$this->theme = crypto_get_option('theme', 'crypto_price_settings', 'style1');
		$this->theme_color = crypto_get_option('theme_color', 'crypto_price_settings', 'fl-is-primary');

		add_shortcode('crypto-price', array($this, 'crypto_price_shortcode'));

		add_filter('crypto_settings_tabs', array($this, 'add_tabs'));
		add_filter('crypto_settings_sections', array($this, 'add_section'));
		add_filter('crypto_settings_fields', array($this, 'add_fields'));
		add_filter('crypto_dashboard_tab', array($this, 'dashboard_add_tabs'));
		add_action('crypto_dashboard_tab_content', array($this, 'dashboard_add_content'));
		add_action('init', array($this, 'create_block_crypto_token_price'));
	}


	//add_filter flexi_settings_tabs
	public function add_tabs($new)
	{

		$tabs = array(
			'price'   => __('Price', 'crypto'),

		);
		$new  = array_merge($new, $tabs);

		return $new;
	}


	//Add Section title
	public function add_section($new)
	{

		$sections = array(
			array(
				'id' => 'crypto_price_settings',
				'title' => __('Crypto Price Box', 'crypto'),
				'description' => __('Show latest price of your desired cryptocurrency.', 'crypto') . "<br>" . "<b>Shortcode examples</b><br><code> [crypto-price symbol=\"BTC\"] </code><br><code>[crypto-price symbol=\"MATIC,BTC,XRP\" style=\"style1\"]</code><br><code>[crypto-price symbol=\"BTC\" style=\"style1\" currency=\"INR\" color=\"fl-is-warning\"]</code>",
				'tab' => 'price',
			),
		);
		$new = array_merge($new, $sections);

		return $new;
	}

	//Add section fields
	public function add_fields($new)
	{
		$fields = array(
			'crypto_price_settings' => array(
				array(
					'name' => 'base_curr',
					'label' => __('Currency', 'crypto'),
					'description' => __('Select your primary currency', 'crypto'),
					'type' => 'select',
					'options' => array(
						'USD' => __('United States Dollar ($) USD', 'crypto'),
						'ALL' => __('Albanian Lek (L)', 'crypto'),
						'DZD' => __('Algerian Dinar (د.ج)	DZD', 'crypto'),
						'ARS' => __('Argentine Peso ($)	ARS', 'crypto'),
						'AMD' => __('Armenian Dram (֏)	AMD', 'crypto'),
						'AUD' => __('Autralian Dollar ($)	AUD', 'crypto'),
						'AZN' => __('Azerbaijani Manat (₼)	AZN', 'crypto'),
						'BHD' => __('Bahraini Dinar (.د.ب)	BHD', 'crypto'),
						'BDT' => __('Bangladeshi Taka (৳)	BDT', 'crypto'),
						'BYN' => __('Belarusian Ruble (Br)	BYN', 'crypto'),
						'BMD' => __('Bermudan Dollar ($)	BMD', 'crypto'),
						'BOB' => __('Bolivian Boliviano (Bs.)	BOB', 'crypto'),
						'BAM' => __('Bosnia-Herzegovina Convertible Mark (KM)	BAM', 'crypto'),
						'BRL' => __('Brazilian Real (R$)	BRL', 'crypto'),
						'BGN' => __('Bulgarian Lev (лв)	BGN', 'crypto'),
						'KHR' => __('Cambodian Riel (៛)	KHR', 'crypto'),
						'CAD' => __('Canadian Dollar ($)	CAD', 'crypto'),
						'CLP' => __('Chilean Peso ($)	CLP', 'crypto'),
						'CNY' => __('Chinese Yuan (¥)	CNY', 'crypto'),
						'COP' => __('Colombian Peso ($)	COP', 'crypto'),
						'CRC' => __('Costa Rican Colón (₡)	CRC', 'crypto'),
						'HRK' => __('Croatian Kuna (kn)	HRK', 'crypto'),
						'CUP' => __('Cuban Peso ($)	CUP', 'crypto'),
						'CZK' => __('Czech Koruna (Kč)	CZK', 'crypto'),
						'DKK' => __('Danish Krone (kr)	DKK', 'crypto'),
						'DOP' => __('Dominican Peso ($)	DOP', 'crypto'),
						'EGP' => __('Egyptian Pound (£)	EGP', 'crypto'),
						'EUR' => __('Euro (€)	EUR', 'crypto'),
						'EUR' => __('Georgian Lari (₾)	GEL', 'crypto'),
						'GHS' => __('Ghanaian Cedi (₵)	GHS', 'crypto'),
						'GTQ' => __('Guatemalan Quetzal (Q)	GTQ', 'crypto'),
						'HNL' => __('Honduran Lempira (L)	HNL', 'crypto'),
						'HKD' => __('Hong Kong Dollar ($)	HKD', 'crypto'),
						'HUF' => __('Hungarian Forint (Ft)	HUF', 'crypto'),
						'ISK' => __('Icelandic Króna (kr)	ISK', 'crypto'),
						'INR' => __('Indian Rupee (₹)	INR', 'crypto'),
						'IDR' => __('Indonesian Rupiah (Rp)	IDR', 'crypto'),
						'IRR' => __('Iranian Rial (﷼)	IRR', 'crypto'),
						'IQD' => __('Iraqi Dinar (ع.د)	IQD', 'crypto'),
						'ILS' => __('Israeli New Shekel (₪)	ILS', 'crypto'),
						'JMD' => __('Jamaican Dollar ($)	JMD', 'crypto'),
						'JPY' => __('Japanese Yen (¥)	JPY', 'crypto'),
						'JOD' => __('Jordanian Dinar (د.ا)	JOD', 'crypto'),
						'KZT' => __('Kazakhstani Tenge (₸)	KZT', 'crypto'),
						'KES' => __('Kenyan Shilling (Sh)	KES', 'crypto'),
						'KWD' => __('Kuwaiti Dinar (د.ك)	KWD', 'crypto'),
						'KGS' => __('Kyrgystani Som (с)	KGS', 'crypto'),
						'LBP' => __('Lebanese Pound (ل.ل)	LBP', 'crypto'),
						'MKD' => __('Macedonian Denar (ден)	MKD', 'crypto'),
						'MYR' => __('Malaysian Ringgit (RM)	MYR', 'crypto'),
						'MUR' => __('Mauritian Rupee (₨)	MUR', 'crypto'),
						'MXN' => __('Mexican Peso ($)	MXN', 'crypto'),
						'MDL' => __('Moldovan Leu (L)	MDL', 'crypto'),
						'MNT' => __('Mongolian Tugrik (₮)	MNT', 'crypto'),
						'MAD' => __('Moroccan Dirham (د.م.)	MAD', 'crypto'),
						'MMK' => __('Myanma Kyat (Ks)	MMK', 'crypto'),
						'NAD' => __('Namibian Dollar ($)	NAD', 'crypto'),
						'NPR' => __('Nepalese Rupee (₨)	NPR', 'crypto'),
						'TWD' => __('New Taiwan Dollar (NT$)	TWD', 'crypto'),
						'NZD' => __('New Zealand Dollar ($)	NZD', 'crypto'),
						'NIO' => __('Nicaraguan Córdoba (C$)	NIO', 'crypto'),
						'NGN' => __('Nigerian Naira (₦)	NGN', 'crypto'),
						'NOK' => __('Norwegian Krone (kr)	NOK', 'crypto'),
						'OMR' => __('Omani Rial (ر.ع.)	OMR', 'crypto'),
						'PKR' => __('Pakistani Rupee (₨)	PKR', 'crypto'),
						'PAB' => __('Panamanian Balboa (B/.)	PAB', 'crypto'),
						'PEN' => __('Peruvian Sol (S/.)	PEN', 'crypto'),
						'PHP' => __('Philippine Peso (₱)	PHP', 'crypto'),
						'PLN' => __('Polish Złoty (zł)	PLN', 'crypto'),
						'GBP' => __('Pound Sterling (£)	GBP', 'crypto'),
						'QAR' => __('Qatari Rial (ر.ق)	QAR', 'crypto'),
						'RON' => __('Romanian Leu (lei)	RON', 'crypto'),
						'RUB' => __('Russian Ruble (₽)	RUB', 'crypto'),
						'SAR' => __('Saudi Riyal (ر.س)	SAR', 'crypto'),
						'RSD' => __('Serbian Dinar (дин.)	RSD', 'crypto'),
						'SGD' => __('Singapore Dollar (S$)	SGD', 'crypto'),
						'ZAR' => __('South African Rand (R)	ZAR', 'crypto'),
						'KRW' => __('South Korean Won (₩)	KRW', 'crypto'),
						'SSP' => __('South Sudanese Pound (£)	SSP', 'crypto'),
						'VES' => __('Sovereign Bolivar (Bs.)	VES', 'crypto'),
						'LKR' => __('Sri Lankan Rupee (Rs)	LKR', 'crypto'),
						'SEK' => __('Swedish Krona ( kr)	SEK', 'crypto'),
						'CHF' => __('Swiss Franc (Fr)	CHF', 'crypto'),
						'THB' => __('Thai Baht (฿)	THB', 'crypto'),
						'TTD' => __('Trinidad and Tobago Dollar ($)	TTD', 'crypto'),
						'TND' => __('Tunisian Dinar (د.ت)	TND', 'crypto'),
						'TRY' => __('Turkish Lira (₺)	TRY', 'crypto'),
						'UGX' => __('Ugandan Shilling (Sh)	UGX', 'crypto'),
						'UAH' => __('Ukrainian Hryvnia (₴)	UAH', 'crypto'),
						'AED' => __('United Arab Emirates Dirham (د.إ)	AED', 'crypto'),
						'UYV' => __('Uruguayan Peso ($)	UYU', 'crypto'),
						'UZS' => __('Uzbekistan Som	UZS', 'crypto'),
						'VND' => __('Vietnamese Dong (₫)	VND', 'crypto'),
					),
				),

				array(
					'name' => 'price_api',
					'label' => __('CoinMarketCap API', 'crypto'),
					'description' => __('Get free API key from CoinMarketCap', 'crypto') . " <a href='https://pro.coinmarketcap.com/signup/' target='_blank'>Click Here </a>",
					'type' => 'text',
					'sanitize_callback' => 'sanitize_key',
				),
				array(
					'name' => 'price_cache',
					'label' => __('Crypto Data Caching', 'crypto'),
					'description' => __('Enter cache time for crypto data in seconds. It saves API limit and speed up results.', 'crypto'),
					'type' => 'number',
					'size' => 'small',
					'sanitize_callback' => 'intval',
				),
				array(
					'name'              => 'theme',
					'label'             => __('Theme Style', 'flexi'),
					'description'       => '',
					'type'              => 'radio',
					'options'           => array(
						'none'   => __('None', 'flexi'),
						'style1' => __('Style 1', 'flexi'),
					),
					'sanitize_callback' => 'sanitize_key',
				),
				array(
					'name'              => 'theme_color',
					'label'             => __('Theme Color', 'flexi'),
					'description'       => '',
					'type'              => 'radio',
					'options'           => array(
						''   => __('Default', 'flexi'),
						'fl-is-primary' => __('Primary', 'flexi'),
						'fl-is-link'     => __('Link', 'flexi'),
						'fl-is-info'     => __('Information', 'flexi'),
						'fl-is-success'     => __('Success', 'flexi'),
						'fl-is-warning'     => __('Warning', 'flexi'),
						'fl-is-danger'     => __('Danger', 'flexi'),

					),
					'sanitize_callback' => 'sanitize_key',
				),

			),
		);
		$new = array_merge($new, $fields);

		return $new;
	}


	public function crypto_price_info($coin_symbol = 'BTC', $curr = "USD")
	{
		$data_option_name = $coin_symbol . '_market_data_' . $curr;
		$timestamp_option_name = $coin_symbol . '_market_timestamp_' . $curr;
		$current_timestamp = date('Y-m-d\TH:i:s' . substr((string)microtime(), 1, 4) . '\Z');
		$cache_time = $this->cache;
		if ($cache_time == false) {
			$cache_time = 600;
		}
		if (get_option($timestamp_option_name) && (strtotime($current_timestamp) - strtotime(get_option($timestamp_option_name))) < $cache_time) {
			return get_option($data_option_name);
		} else {
			$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
			$parameters = [
				'symbol' => $coin_symbol,
				'convert' => $curr,
			];

			$qs = http_build_query($parameters); // query string encode the parameters
			$request = "{$url}?{$qs}"; // create the request URL
			$args = array(
				'headers' => array(
					'Accepts' => 'application/json',
					'X-CMC_PRO_API_KEY' => crypto_get_option('price_api', 'crypto_price_settings', ''),
				)
			);

			$response = wp_remote_retrieve_body(wp_remote_get($request, $args));
			update_option($data_option_name, $response);
			update_option($timestamp_option_name, $current_timestamp);
			return $response;
		}
	}


	public function style($style = "none", $data = '0.0', $curr = "USD", $id = "BTC", $img = '', $color = 'fl-is-primary')
	{
		$theme = '<div class="fl-column fl-is-narrow">';
		if ($style == 'style1') {
			$theme .= '<div class="fl-notification ' . $color . ' fl-pb-0 fl-pt-0">';
			$theme .= '<p class="fl-is-size-4 fl-has-text-centered fl-mb-1"><strong>' . $data . '</strong> </p>';
			$theme .= '<p class="fl-is-size-6 fl-has-text-centered"> <img src="' . $img . '" width="16"> ' . $id . '/' . $curr . '</p>';
			$theme .= "</div>";
		} else if ($style == 'style2') {
			$theme .= '<div class="fl-tag ' . $color . ' ">';
			$theme .= '<img src="' . $img . '" width="16">&nbsp;';
			$theme .= $data;
			$theme .= "</div>";
		} else {
			$theme .= $id . ' <strong>' . $data . '</strong> ' . $curr;
		}
		$theme .= '</div>';
		return $theme;
	}

	public function crypto_price_shortcode($atts)
	{


		$put = "";
		ob_start();

		extract(shortcode_atts(array(
			'symbol' => 'xxx',
			'style' => 'none',
			'currency' => 'none',
			'color' => 'none',
		), $atts));
		if ($symbol == 'xxx') {
			return 'Please add a coin symbol to fetch its data. For example, [crypto-price symbol="BTC"].';
		} else {
			if ($currency == 'none') {
				$curr = $this->curr;
			} else {
				$curr = $currency;
			}

			if ($color == 'none') {
				$color = $this->theme_color;
			}

			$output = "<div class=\"fl-columns\">";
			$token_ids = explode(",", strval($symbol));

			foreach ($token_ids as $tid) {
				$data = json_decode($this->crypto_price_info($tid, $curr));
				if (isset($data->data->$tid->quote->$curr->price)) {
					$data_result = round($data->data->$tid->quote->$curr->price, 2);
				} else {
					$data_result = 'ERROR';
					crypto_set_option('price_cache', 'crypto_price_settings', '1');
				}
				$img = 'https://s2.coinmarketcap.com/static/img/coins/64x64/' . $data->data->$tid->id . '.png';
				$output .= $this->style($style, $data_result, $curr, $tid, $img, $color);
			}
			$output .= "</div>";
			return $output;
		}
		$put = ob_get_clean();

		return $put;
	}

	public function dashboard_add_tabs($tabs)
	{

		$extra_tabs = array("price" => 'Price Display');

		// combine the two arrays
		$new = array_merge($tabs, $extra_tabs);
		//crypto_log($new);
		return $new;
	}

	public function dashboard_add_content()
	{
		if (isset($_GET['tab']) && 'price' == sanitize_text_field($_GET['tab'])) {
			echo wp_kses_post($this->crypto_dashboard_content());
		}
	}

	public function crypto_dashboard_content()
	{
		ob_start();
?>
		<div class="changelog section-getting-started">
			<div class="feature-section">
				<h2>Price Display</h2>
				<div class="wrap">
					<b>The "Crypto" plugin enables users to display current cryptocurrency prices in various currencies.</b>
					<br><br><a class="button button-primary" href="<?php echo admin_url('admin.php?page=crypto_settings&tab=price&section=crypto_price_settings'); ?>">Price
						Display Settings</a>
					<a class="button button-primary" target="_blank" href="https://w3d.name/reseller/domain-search/">Live
						Demo</a>
					<br><br>
					<b>Tips</b>
					<ul>
						<li>* Obtain an API key from CoinMarketCap.com, which is free to acquire.</li>
						<li>* Initially set the 'Crypto Data Caching' time to 1 second. Once it is working well, increase it as
							needed. This will save bandwidth and improve speed.</li>
						<li>* To display prices within an article, use the 'none' style. This will not disrupt the paragraph's
							formatting.</li>
					</ul>

				</div>
			</div>
		</div>
<?php
		$content = ob_get_clean();
		return $content;
	}

	//Block editor
	//add block editor
	public function create_block_crypto_token_price()
	{
		register_block_type(CRYPTO_BASE_DIR . 'block/build/token-price', array(
			'render_callback' => [$this, 'add_token_price'],
			'attributes' => array(
				'symbol' => array(
					'default' => 'BTC',
					'type'    => 'string'
				),
				'currency' => array(
					'default' => 'USD',
					'type'    => 'string'
				),
				'style' => array(
					'default' => 'style1',
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

	public function add_token_price($attributes)
	{

		$short = '[crypto-price symbol="' . $attributes['symbol'] . '" style="' . $attributes['style'] . '" currency="' . $attributes['currency'] . '" color="' . $attributes['color'] . ' ' . $attributes['size'] . ' ' . $attributes['theme'] . '"]';
		return do_shortcode($short);
		//  return $short;
	}
}
$price_page = new Crypto_Price();
