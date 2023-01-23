<?php
class Crypto_Admin_Dashboard_Intro
{
    public function __construct()
    {
        add_filter('crypto_dashboard_tab', array($this, 'add_tabs'));
        add_action('crypto_dashboard_tab_content', array($this, 'add_content'));
    }

    public function add_tabs($tabs)
    {

        $extra_tabs = array("intro" => 'Introduction');

        // combine the two arrays
        $new = array_merge($tabs, $extra_tabs);
        //crypto_log($new);
        return $new;
    }

    public function add_content()
    {
        if (!isset($_GET['tab'])) {
            echo wp_kses_post($this->crypto_dashboard_content());
        }

        if (isset($_GET['tab']) && 'intro' == sanitize_text_field($_GET['tab'])) {
            echo wp_kses_post($this->crypto_dashboard_content());
        }
    }

    public function crypto_dashboard_content()
    {
        ob_start();
?>
<div class="changelog section-getting-started">
    <div class="feature-section">
        <h2>Blockchain Tools</h2>
        <div class="wrap">

            <div>
                We are working towards creating a comprehensive set of cryptocurrency tools. Gradually, we will be
                adding all the major and frequently used tools that will aid in the development of Web3 platforms.
            </div>

            <br>
            <b>Support: </b> <a href="<?php echo esc_url('https://wordpress.org/support/plugin/crypto/'); ?>">Wordpress
                Forum</a><br>
            <b>Telegram : </b> <a href="<?php echo esc_url('https://t.me/web3yak'); ?>">@Web3Yak</a><br>
            <b>Twitter: </b> <a href="<?php echo esc_url('https://twitter.com/web3yak'); ?>">@Web3Yak</a><br>
        </div>
    </div>
</div>
<?php
        $content = ob_get_clean();
        return $content;
    }
}
$add_tabs = new Crypto_Admin_Dashboard_Intro();