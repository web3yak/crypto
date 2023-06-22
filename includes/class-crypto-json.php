<?php
class Crypto_Generate_Json
{

	public function __construct()
	{

		add_action('crypto_ipfs_upload', array($this, 'get_json_from_w3d'), 10, 1);
	}

	public function get_json_from_w3d($domain)
	{
		$url = 'https://w3d.name/api/v1/index.php?domain=' . $domain . '&' . rand();

		$uploaddir = wp_upload_dir();
		$base_path =  $uploaddir['basedir'] . "/yak/" . $domain . '.json'; //upload dir.
		//crypto_log($base_path);
		//crypto_log($uploaddir['basedir']);
		if (!is_dir($uploaddir['basedir'] . "/yak/")) {
			mkdir($uploaddir['basedir'] . "/yak/");
		}

		// Use file_get_contents() function to get the file
		// from url and use file_put_contents() function to
		// save the file by using base name
		if (file_put_contents($base_path, file_get_contents($url))) {

			$decoded_json = json_decode(file_get_contents($base_path), false);
			if (isset($decoded_json->error)) {
				$this->create_json($domain);
			}
			// echo "File downloaded successfully";
		} else {
			//echo "File downloading failed.";
			$this->create_json($domain);
		}
	}

	public function create_json($domain, $edit = false, $crypto_profile_name = "", $crypto_email = "", $crypto_website_url = "", $crypto_desp = "", $crypto_addr = "")
	{
		$uploaddir = wp_upload_dir();
		$base_path =  $uploaddir['basedir'] . "/yak/"; //upload dir.
		//crypto_log($base_path);
		//crypto_log($uploaddir['basedir']);
		if (!is_dir($base_path)) {
			mkdir($base_path);
		}

		if ($crypto_website_url == "") {
			$crypto_website_url = get_site_url();
		}

		if ($crypto_profile_name == "") {
			$crypto_profile_name = $domain;
		}

		$default_nft_image =    esc_url(CRYPTO_PLUGIN_URL . '/public/img/yak.png');
		$nft_image = crypto_get_option('nft_image', 'crypto_marketplace_settings', '');
		if ($nft_image == '') {
			$nft_image = $default_nft_image;
		}

		$nft_desp = crypto_get_option('nft_desp', 'crypto_marketplace_settings', '');

		$info = array();
		$info['name'] = strtolower($domain);
		$info['description'] = $nft_desp;
		$info['image'] = $nft_image;
		$info['attributes'][0]['trait_type'] = 'domain';
		$info['attributes'][0]['value'] = $domain;
		$info['attributes'][1]['trait_type'] = 'level';
		$info['attributes'][1]['value'] = '2';
		$info['attributes'][2]['trait_type'] = 'length';
		$info['attributes'][2]['value'] = strlen($domain);
		$info['records'][1]['type'] = 'name';
		$info['records'][1]['value'] = $crypto_profile_name;
		$info['records'][2]['type'] = 'email';
		$info['records'][2]['value'] = $crypto_email;
		$info['records'][3]['type'] = 'notes';
		$info['records'][3]['value'] = $crypto_desp;

		$crypto = array();
		$crypto['matic'] = $crypto_addr;
		$crypto['eth'] = $crypto_addr;
		$crypto['bsc'] = $crypto_addr;

		$info['records'][4]['type'] = 'crypto';
		$info['records'][4]['value'] = $crypto;

		$info['records'][50]['type'] = 'web_url';
		$info['records'][50]['value'] = '';
		$info['records'][51]['type'] = 'web3_url';
		$info['records'][51]['value'] = $crypto_website_url;


		$primary = crypto_split_domain($domain, 'primary');
		$second = crypto_split_domain($domain, 'subdomain');

		if ($primary == '') {
			$primary = $second;
			$second = "";
		}
		$data = json_encode($info);
		$file_name = strtolower($domain) . '.json';
		if ($edit) {
			$file_name = strtolower($domain) . '_edit.json';

			$file_name_pending = strtolower($domain) . '_pending.json';
			$save_path = $base_path . '/' . $file_name_pending;
			$f = @fopen($save_path, "w") or die(print_r(error_get_last(), true)); //if json file doesn't gets saved, uncomment this to check for errors
			fwrite($f, 'ipfs_hashcode');
			fclose($f);
		}

		$save_path = $base_path . '/' . $file_name;
		$f = @fopen($save_path, "w") or die(print_r(error_get_last(), true)); //if json file doesn't gets saved, uncomment this to check for errors
		fwrite($f, $data);
		fclose($f);

		//Link to IPFS
		$nft_storage_api = crypto_get_option('nft_storage_api', 'crypto_marketplace_settings', '');
		if ($nft_storage_api != '') {
			$file_local_full = $base_path . '/' . $domain . '_edit.json';
			if (file_exists($file_local_full)) {
				$this->upload_ipfs(strtolower($domain), '');
			}
		}


		return "success";
	}

	//Upload to NFT.Storage
	public function upload_ipfs($filename, $location)
	{
		$uploaddir = wp_upload_dir();
		$base_path =  $uploaddir['basedir'] . "/yak/"; //upload dir.
		if ($location != '') {
			$file_local_full = $location; //upload dir.
		} else {
			$file_local_full = $base_path . '/' . $filename . '_edit.json';
		}
		// flexi_log("--" . $file_local_full);
		$content_type = mime_content_type($file_local_full);

		$headers = array(
			"Content-Type: $content_type", // or whatever you want
		);


		$filesize = filesize($file_local_full);
		$stream = fopen($file_local_full, 'r');
		$nft_storage_api = crypto_get_option('nft_storage_api', 'crypto_marketplace_settings', '');
		$curl_opts = array(
			CURLOPT_URL => "https://api.nft.storage/upload",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_PUT => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_INFILE => $stream,
			CURLOPT_INFILESIZE => $filesize,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $nft_storage_api,
			),
		);

		$curl = curl_init();
		curl_setopt_array($curl, $curl_opts);

		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($httpCode == 200) {

			$data = json_decode($response, true);

			// print_r($data);
			//crypto_log($data);
			fclose($stream);

			if (curl_errno($curl)) {
				$error_msg = curl_error($curl);
				throw new \Exception($error_msg);
			}

			curl_close($curl);

			if (isset($data['value']['cid'])) {
				$cid = $data['value']['cid'];
				$file_name_cid = $base_path . '/' . $filename . '_cid.txt';
				$f = @fopen($file_name_cid, "w") or die(print_r(error_get_last(), true)); //if json file doesn't gets saved, uncomment this to check for errors
				fwrite($f, $cid);
				fclose($f);
				//crypto_log("xx = " . $cid);
				return $cid;
			}
		}
		return '';
	}

	public function get_lookup_file($domain)
	{
		$uploaddir = wp_upload_dir();
		$base_path =  $uploaddir['basedir'] . "/yak/" . $domain . '.json'; //upload dir.
		if (file_exists($base_path)) {

			//Check if edited file available
			$base_edited_file =  $uploaddir['basedir'] . "/yak/" . $domain . '_edit.json'; //Edited file
			if (file_exists($base_edited_file)) {

				//get record from edited file
				$lookup_file = $base_edited_file;
			} else {
				//get record from original file
				$lookup_file = $base_path;
			}

			return $lookup_file;
		} else {
			$this->create_json($domain);
			return $base_path;
		}
	}


	public function fetch($domain_name, $domain_key)
	{

		$json = file_get_contents($this->get_lookup_file($domain_name));

		//crypto_log($json);
		$json_data = json_decode($json, true);
		//echo  $json_data['records']['50']['value'];
		if (isset($json_data['error'])) {
			return "error";
		}


		$crypto = array("eth", "bsc", "zil", "sol", "matic", "btc", "fil");
		$social = array("facebook", "twitter", "telegram", "youtube", "instagram", "discord");
		$others = array("notes", "website", "name", "email", "phone", "tg_bot", "web_url");



		$array_key = '';
		$output = '';

		if (in_array($domain_key, $crypto)) {
			$array_key = 'crypto';
		} else if (in_array($domain_key, $social)) {
			$array_key = 'social';
		} else if (in_array($domain_key, $others)) {
			$array_key = 'others';
		} else {
			$array_key = 'x';
			return 'x';
		}



		if ($array_key != '') {
			if ($array_key == 'crypto' || $array_key == 'social') {
				//echo "-----";

				for ($i = 1; $i <= 20; $i++) {
					if (isset($json_data['records'][$i]['type'])) {

						//check in array of specific type
						$record_array = $json_data['records'][$i]['type'];
						//var_dump($record_array);

						//get value from specific array
						if ($record_array == $array_key) {
							$got_array = $json_data['records'][$i]['value'];
							//var_dump($got_array);
							if (isset($got_array[$domain_key]))
								$output = $got_array[$domain_key];
							break;
						}
						//echo "-" . $i . "-";
					}
				}
			} else {

				for ($i = 1; $i <= 20; $i++) {
					if (isset($json_data['records'][$i]['type'])) {
						$type = $json_data['records'][$i]['type'];
						if ($domain_key == $type) {
							if (isset($json_data['records'][$i]['value']))
								$output = $json_data['records'][$i]['value'];
						}
					}
				}
			}
		}

		if ($domain_key == "tg_bot" && $output == "") {
			$output = $json_data['records'][1]['value'] . "\n\n<a href='http://" . $domain_name . ".w3d.name/'>" . $domain_name . '.w3d.name</a>';
		}


		if ($domain_key == "web_url" && $output == "") {

			if (isset($json_data['records'][51]['value']) && $json_data['records'][51]['value'] != "") {
				$output = $json_data['records'][51]['value'];
			} else {
				$output = "https://gateway.ipfs.io/ipfs/" . $json_data['records'][50]['value'];
			}
		}

		return  $output;
	}
}
$gen_json = new Crypto_Generate_Json();
