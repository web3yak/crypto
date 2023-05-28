<?php
class crypto_connect_ajax_process
{
    private $user;

    //Refresh function of specific position after some action
    public function __construct()
    {
        add_action("wp_ajax_crypto_connect_ajax_process", array($this, "crypto_connect_ajax_process"));
        add_action("wp_ajax_nopriv_crypto_connect_ajax_process", array($this, "crypto_connect_ajax_process"));
    }

    public function crypto_connect_ajax_process()
    {
        $id = $_REQUEST["id"];
        $nonce = $_REQUEST["nonce"];
        $param1 = $_REQUEST["param1"];
        $param2 = $_REQUEST["param2"];
        $param3 = $_REQUEST["param3"];
        $method_name = $_REQUEST["method_name"];

        $response = array(
            'error' => false,
            'msg' => 'No Message',
            'count' => '0',
        );

        $system_nonce = wp_create_nonce('crypto_ajax');
        //  flexi_log($nonce . "---" . $system_nonce);

        if (wp_verify_nonce($nonce, 'crypto_ajax') || $method_name == 'register'  || $method_name == 'check') {

            $msg = $this->$method_name($id, $param1, $param2, $param3);
            // flexi_log("PASSED");
        } else {
            $msg = "System error";
            //  flexi_log("FAIL " . $method_name);
        }
        $response['msg'] = $msg;
        echo wp_json_encode($response);

        die();
    }

    public function get_userid_by_meta($key, $value)
    {

        //First check if same username = wallet address
        if ($user = get_user_by('login', $value)) {
            return $user->ID;
        } else {
            //look into linked database if username not matched with wallet address
            global $wpdb;
            $users = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '$key' AND meta_value = '$value'");
            if ($users) {
                foreach ($users as $user) {
                    return $user->user_id;
                }
            } else {
                return 0;
            }
        }
    }

    public function check($id, $param1, $param2, $param3)
    {
        //flexi_log("ame hree ---" . $param1);
        //Check if user is logged in
        if (is_user_logged_in()) {

            //Check if this wallet is already linked with other account
            $the_user_id = $this->get_userid_by_meta('crypto_wallet', trim($param1));

            if ($the_user_id != 0) {
                //User is found with same wallet address.
                //Delete the wallet and link with current user
                delete_user_meta($the_user_id, 'crypto_wallet');
                //Assign this wallet to current user
                update_user_meta(get_current_user_id(), 'crypto_wallet', trim($param1));
                //flexi_log("old found and replaced " . $param1);
            } else {
                //Assign this wallet to current user
                update_user_meta(get_current_user_id(), 'crypto_wallet', trim($param1));
                // flexi_log("new added : " . $param1);
            }
        }

        return "done";
    }

    public function register($id, $param1, $param2, $param3)
    {
        //flexi_log("ame hree" . $param1);

        if (!is_user_logged_in()) {
            $user_login = trim($param1);

            //Check if this wallet is already linked with other account
            $the_user_id = $this->get_userid_by_meta('crypto_wallet', trim($param1));

            if ($the_user_id != 0) {
                //This wallet is already assigned to one of the user
                //Log that user in
                $user = get_user_by('id', $the_user_id);
                return $this->log_in($user->user_login);
            } else {

                $existing_user_id = username_exists($user_login);

                if ($existing_user_id) {
                    //echo __('Username already exists.', 'crypto_connect_login');
                    // flexi_log("Username already exists " . $user_login);
                    return $this->log_in($user_login);
                } else {
                    //  flexi_log("NEw User " . $user_login);
                    if (is_multisite()) {
                        // Is this obsolete or not???
                        // https://codex.wordpress.org/WPMU_Functions says it is?
                        // But then, the new REST api uses it. What is going on?
                        $user_id = wpmu_create_user($user_login, wp_generate_password(), '');
                        if (!$user_id) {
                            return 'error';
                        }
                    } else {
                        $user_id = wp_create_user($user_login, wp_generate_password());
                        if (is_wp_error($user_id)) {
                            // echo $user_id;
                            // flexi_log(" AM into regiseter " . $param1);
                        }
                    }
                    update_user_meta($user_id, 'crypto_wallet', trim($param1));
                    return $this->log_in($user_login);
                }
            }
        }
    }

    public function log_in($username)
    {
        //---------------------Automatic login--------------------

        if (!is_user_logged_in()) {

            if ($user = get_user_by('login', $username)) {

                clean_user_cache($user->ID);
                wp_clear_auth_cookie();
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID, true, is_ssl());

                $user = get_user_by('id', $user->ID);
                update_user_caches($user);

                do_action('wp_login', $user->user_login, $user);

                if (is_user_logged_in()) {

                    return "success";
                } else {
                    return "fail";
                }
            }
        } else {
            return "wrong";
        }
    }

    public function savenft($id, $param1, $param2, $param3)
    {

        if (is_user_logged_in()) {
            // flexi_log($param2);

            $str_arr = preg_split("/\,/", $param2);

            update_user_meta(
                get_current_user_id(),
                'domain_names',
                $str_arr
            );

            update_user_meta(
                get_current_user_id(),
                'domain_count',
                $param3
            );
            //  crypto_log($id . "-" . $param1 . "-" . $param2 . "-" . $param3);
            $saved_array = get_user_meta(get_current_user_id(),  'domain_names');

            $this->checknft(get_current_user_id(),  $saved_array);
        }
    }

    public function checknft($user_id, $saved_array)
    {
        $default_access = crypto_get_option('select_access_control', 'crypto_access_settings_start', 'web3domain');
        if ($default_access == 'web3domain') {


            $check = crypto_get_option('domain_name', 'crypto_access_settings', 'yak');
            //  crypto_log("Counting...");
            // crypto_log(get_user_meta(get_current_user_id(),  'domain_count'));
            // crypto_log($saved_array);
            if (is_array($saved_array) && !empty($saved_array[0])) {
                $matches  = preg_grep('/.' . $check . '$/', $saved_array[0]);
                // crypto_log($matches);
                //if (in_array($check, $saved_array[0])) {
                if (count($matches) > 0) {
                    //crypto_log("login...");
                    update_user_meta(
                        get_current_user_id(),
                        'domain_block',
                        'false'
                    );
                } else {
                    // crypto_log("block...");
                    update_user_meta(
                        get_current_user_id(),
                        'domain_block',
                        'true'
                    );
                }
            }
        } else {
            $nft_count = get_user_meta(get_current_user_id(),  'domain_count')[0];

            $system_nft_count_value = crypto_get_option('nft_count', 'crypto_access_other', '1');
            // flexi_log($nft_count . " u...s " . $system_nft_count_value);
            if ($nft_count >=   $system_nft_count_value) {
                update_user_meta(
                    get_current_user_id(),
                    'domain_block',
                    'false'
                );
            } else {
                update_user_meta(
                    get_current_user_id(),
                    'domain_block',
                    'true'
                );
            }
        }
    }

    public function crypto_delete_json($id, $param1, $param2, $param3)
    {
        // crypto_log($id . "-" . $param1 . "-" . $param2 . "-" . $param3);
        $uploaddir = wp_upload_dir();
        $base_path =  $uploaddir['basedir'] . "/yak/" . $param1 . '_edit.json'; //upload dir.
        //  crypto_log($base_path);
        if (file_exists($base_path)) {
            unlink($base_path);
        }
    }

    //Logout user
    public function logout($id, $param1, $param2, $param3)
    {
        wp_logout();
    }
}
$process = new crypto_connect_ajax_process();
