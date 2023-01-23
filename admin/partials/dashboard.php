<div class="wrap about-wrap">
    <h1><?php echo __('Welcome to', 'crypto') . ' ' . __('Crypto', 'crypto') . ' ' . CRYPTO_VERSION; ?></h1>
    <div class="crypto-badge-logo"></div>
    <nav class="nav-tab-wrapper">
        <?php
        //Get the active tab from the $_GET param
        $default_tab = 'intro';
        $get_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : $default_tab;

        $tabs = array();
        $tabs = apply_filters('crypto_dashboard_tab', $tabs);

        foreach ($tabs as $key => &$val) {

            if ($key == $get_tab) {
                $active_tab = 'nav-tab-active';
            } else {
                $active_tab = '';
            }
            echo '<a href="?page=crypto&tab=' . esc_attr($key) . '" class="nav-tab ' . esc_attr($active_tab) . '">' . esc_attr($val) . '</a>';
        }

        ?>
    </nav>
    <div class="tab-content">
        <?php do_action('crypto_dashboard_tab_content') ?>
    </div>
</div>