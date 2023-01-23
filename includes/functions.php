<?php
function crypto_get_option($field_name, $section = 'flexi_icon_settings', $default = '')
{
    //Example
    //flexi_get_option('field_name', 'setting_name', 'default_value');

    $options = (array) get_option($section);

    if (isset($options[$field_name])) {
        return $options[$field_name];
    } else {
        //Set the default value if not found
        crypto_set_option($field_name, $section, $default);
    }

    return $default;
}

//Set options in settings
function crypto_set_option($field_name, $section = 'flexi_icon_settings', $default = '')
{
    //Example
    //flexi_set_option('field_name', 'setting_name', 'default_value');
    $options              = (array) get_option($section);
    $options[$field_name] = $default;
    update_option($section, $options);

    return;
}

// log_me('This is a message for debugging purposes. works if debug is enabled.');
function crypto_log($message)
{
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }

        error_log('------------------------------------------');
    }
}


function crypto_file_get_contents_ssl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000); // 3 sec.
    curl_setopt($ch, CURLOPT_TIMEOUT, 10000); // 10 sec.
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function crypto_split_domain($title, $part)
{
    //eg.navneet.crypto
    if ($part == "subdomain") {
        $subdomain = explode('.', $title, 2);
        if (isset($subdomain[0])) {
            return $subdomain[0]; //navneet
        }
    } else if ($part == "primary") {
        $subdomain = explode('.', $title, 2);
        if (isset($subdomain[1])) {
            return $subdomain[1]; //crypto
        }
    } else {
        return $title;
    }
}


//Validate domain name
function crypto_is_valid_domain_name($domain_name)
{
    $dot_count = substr_count($domain_name, '.');
    if ($dot_count > 1)
        return false;

    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
        && preg_match("/^.{1,253}$/", $domain_name) //overall length check
        && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)); //length of each label
}