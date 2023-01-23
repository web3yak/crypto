<?php
class Cryoto_Facebook
{
    private $help = ' <a style="text-decoration: none;" href="#" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>';

    public function __construct()
    {

        add_filter('crypto_settings_sections', array($this, 'add_section'));
        add_filter('crypto_settings_fields', array($this, 'add_extension'));
        add_filter('crypto_settings_fields', array($this, 'add_fields'));
    }

    //Add Section title & description
    public function add_section($new)
    {
        $enable_addon = crypto_get_option('enable_facebook', 'crypto_general_login', 1);
        if ("1" == $enable_addon) {
            $sections = array(
                array(
                    'id' => 'crypto_facebook',
                    'title' => __('Facebook Login', 'crypto'),
                    'description' => __('Let users to login via Facebook', 'crypto') . ' ' . $this->help,
                    'tab' => 'login',
                ),
            );
            $new = array_merge($new, $sections);
        }
        return $new;
    }

    //Add enable/disable option at extension tab
    public function add_extension($new)
    {
        $enable_addon = crypto_get_option('enable_facebook', 'crypto_general_login', 1);
        if ("1" == $enable_addon) {

            $description = ' <a style="text-decoration: none;" href="' . admin_url('admin.php?page=crypto_settings&tab=general&section=crypto_conflict_settings') . '"><span class="dashicons dashicons-admin-tools"></span></a>';
        } else {
            $description = '';
        }

        $fields = array(
            'crypto_general_login' => array(
                array(
                    'name' => 'enable_facebook',
                    'label' => __('Enable Facebook Login', 'crypto'),
                    'description' => __('Let users to connect via Facebook.', 'crypto') . ' ' . $this->help . ' ' . $description,
                    'type' => 'checkbox',
                    'sanitize_callback' => 'intval',

                ),
            ),
        );
        $new = array_merge_recursive($new, $fields);

        return $new;
    }

    //Add section fields
    public function add_fields($new)
    {
        $enable_addon = crypto_get_option('enable_facebook', 'crypto_general_login', 1);
        if ("1" == $enable_addon) {
            $fields = array(
                'crypto_facebook' => array(),
            );
            $new = array_merge($new, $fields);
        }
        return $new;
    }
}

//Ultimate Member: Setting at Flexi & Tab at profile page
$conflict = new Cryoto_Facebook();
