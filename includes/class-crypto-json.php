<?php
class Crypto_Generate_Json
{

	public function __construct()
	{

		add_action('crypto_ipfs_upload', array($this, 'get_json_from_w3d'), 10, 1);
	}

	public function get_json_from_w3d($domain)
	{
		$url = 'https://w3d.name/api/v1/index.php?domain=' . $domain;

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

	public function create_json($domain, $edit = false, $notes = "")
	{
		$uploaddir = wp_upload_dir();
		$base_path =  $uploaddir['basedir'] . "/yak/"; //upload dir.
		//crypto_log($base_path);
		//crypto_log($uploaddir['basedir']);
		if (!is_dir($base_path)) {
			mkdir($base_path);
		}

		$info = array();
		$info['name'] = strtolower($domain);
		$info['description'] = $notes;
		$info['image'] = '';
		$info['attributes'][0]['trait_type'] = 'domain';
		$info['attributes'][0]['value'] = $domain;
		$info['attributes'][1]['trait_type'] = 'level';
		$info['attributes'][1]['value'] = '2';
		$info['attributes'][2]['trait_type'] = 'length';
		$info['attributes'][2]['value'] = strlen($domain);
		$info['records'][50]['type'] = 'web_url';
		$info['records'][50]['value'] = get_site_url();
		$info['records'][51]['type'] = 'web3_url';
		$info['records'][51]['value'] = "";


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
		}

		$save_path = $base_path . '/' . $file_name;
		$f = @fopen($save_path, "w") or die(print_r(error_get_last(), true)); //if json file doesn't gets saved, uncomment this to check for errors
		fwrite($f, $data);
		fclose($f);
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
		//var_dump($json);
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
