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

	public function create_json($domain)
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
		$info['description'] = '';
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

		$svg_data = '<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
		width="100%" height="100%" viewBox="0 0 300.000000 300.000000"
		preserveAspectRatio="xMidYMid meet" class="signid_body">
		   <style>
			   .signid_body {
				   background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
				   background-size: 400% 400%;
				   animation: fgradient 15s ease infinite;
				   height: 100vh;
			   }
	   
			   @keyframes fgradient {
				   0% {
					   background-position: 0% 50%;
				   }
	   
				   50% {
					   background-position: 100% 50%;
				   }
	   
				   100% {
					   background-position: 0% 50%;
				   }
			   }
		   </style>
	   <g transform="translate(0.000000,300.000000) scale(0.100000,-0.100000)"
	   fill="#000000" stroke="none">
	   <path d="M2693 2406 c-85 -59 -130 -163 -203 -471 -52 -218 -86 -317 -126
	   -363 l-32 -37 -83 0 c-69 0 -108 7 -214 39 -129 39 -131 40 -186 99 -31 32
	   -59 55 -62 51 -3 -5 -17 -54 -31 -109 -15 -55 -38 -118 -51 -141 -53 -90 -132
	   -131 -233 -121 -104 11 -163 78 -208 237 -29 103 -40 130 -53 130 -5 0 -26
	   -23 -47 -50 -37 -49 -42 -52 -164 -92 -106 -35 -138 -41 -212 -42 -132 -1
	   -136 5 -238 343 -92 305 -166 461 -243 510 -17 11 -32 19 -34 18 -1 -1 29 -89
	   67 -194 78 -216 85 -250 100 -488 10 -157 14 -175 42 -237 36 -80 113 -158
	   184 -188 92 -40 215 -36 353 12 30 10 55 17 55 16 9 -20 57 -233 58 -260 1
	   -21 6 -38 10 -38 13 0 68 91 68 112 0 10 4 18 10 18 11 0 6 -96 -16 -274 -8
	   -65 -12 -121 -9 -124 3 -3 25 14 49 37 24 24 46 39 49 34 3 -4 13 -37 22 -73
	   29 -111 54 -143 141 -176 38 -14 51 -15 84 -5 78 23 147 107 171 206 6 28 13
	   51 14 53 2 2 24 -18 50 -45 l47 -48 -6 35 c-14 88 -26 222 -26 294 l0 80 25
	   -51 c35 -70 52 -83 60 -46 3 15 17 75 30 133 13 58 26 117 29 132 l6 27 93
	   -33 c120 -44 163 -50 234 -36 161 33 246 119 304 306 27 88 42 171 78 429 17
	   121 42 262 56 314 14 52 24 95 23 96 -2 2 -17 -7 -35 -19z"/>
	   </g>
		  <g id="Group-6" transform="translate(95.000000, 24.000000)">
				   <g id="Group" transform="translate(5.000000, 43.000000)">
					   <rect x="-15" y="0" width="130" height="34" stroke="#363636" stroke-width="2.112px" rx="17" />
					   <text dominant-baseline="middle" text-anchor="middle" font-size="16" font-weight="bold" fill="#ffffff" font-family="system-ui, -apple-system, BlinkMacSystemFont, Roboto, Ubuntu, Oxygen, Cantarell, sans-serif">
						   <tspan x="16%" y="20">.' . $primary . '</tspan>
					   </text>
				   </g>
				   <text text-anchor="middle" id="domain" font-family="system-ui, -apple-system, BlinkMacSystemFont, Roboto, Ubuntu,Oxygen, Cantarell, sans-serif" font-size="24" font-weight="bold" fill="#ffffff">
					   <tspan x="18%" y="26">' . $second . '</tspan>
				   </text>
			   </g>
	   </svg>
	   ';


		$info['image_data'] = $svg_data;
		$data = json_encode($info);
		$file_name = strtolower($domain) . '.json';
		$save_path = $base_path . '/' . $file_name;
		$f = @fopen($save_path, "w") or die(print_r(error_get_last(), true)); //if json file doesn't gets saved, uncomment this to check for errors
		fwrite($f, $data);
		fclose($f);
	}
}
$gen_json = new Crypto_Generate_Json();
