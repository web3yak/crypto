<?php

/**
 * Settings
 *
 * @link    https://odude.com
 * @since   1.0.0
 *
 * @package crypto
 */

// Exit if accessed directly
if (!defined('WPINC')) {
    die;
}

/**
 * crypto_Admin_Settings class.
 *
 * @since 1.0.0
 */
class crypto_Admin_Settings
{

    /**
     * Settings tabs array.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $tabs = array();

    /**
     * Settings sections array.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $sections = array();

    /**
     * Settings fields array
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $fields = array();

    /**
     * Add a settings menu for the plugin.
     *
     * @since 1.0.0
     */
    public function admin_menu()
    {
        add_submenu_page(
            'crypto',
            __('crypto', 'crypto') . ' - ' . __('Settings', 'crypto'),
            __('Settings', 'crypto'),
            'manage_options',
            'crypto_settings',
            array($this, 'gallery_settings_form')
        );
    }

    public function allowed_html($html)
    {
        $allowed_html = array(
            'input' => array(
                'type' => array(),
                'name' => array(),
                'value' => array(),
                'checked' => array(),
                'class' => array(),
                'id' => array(),
                'min' => array(),
                'max' => array(),
                'step' => array(),
                'checked' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'pre' => array(
                'class' => array(),
            ),
            'select' => array(
                'class' => array(),
                'name' => array(),
                'id' => array(),
            ),
            'option' => array(
                'value' => array(),
                'selected' => array(),
            ),
            'a' => array(
                'href' => array(),
                'title' => array(),
                'style' => array(),
                'target' => array(),
            ),
            'textarea' => array(
                'rows' => array(),
                'id' => array(),
                'class' => array(),
                'cols' => array(),
                'name' => array(),
            ),
            'span' => array(
                'class' => array(),
            ),
            'label' => array(
                'for' => array(),
            ),
            'img' => array(
                'title' => array(),
                'src' => array(),
                'alt' => array(),
                'class' => array(),
                'id' => array(),
                'size' => array(),
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
            'fieldset' => array(),
        );
        return wp_kses($html, $allowed_html);
    }

    /**
     * gallery settings form.
     *
     * @since 1.0.0
     */
    public function gallery_settings_form()
    {
        require CRYPTO_PLUGIN_DIR . 'admin/partials/settings.php';
    }

    /**
     * Initiate settings.
     *
     * @since 1.0.0
     */
    public function admin_init()
    {
        $this->tabs = $this->get_tabs();
        $this->sections = $this->get_sections();
        $this->fields = $this->get_fields();

        // Initialize settings
        $this->initialize_settings();
    }

    /**
     * Get settings tabs.
     *
     * @since  1.0.0
     * @return array $tabs Setting tabs array.
     */
    public function get_tabs()
    {
        $tabs = array(
            'general' => __('General', 'crypto'),
            'login' => __('Login', 'crypto'),

        );

        return apply_filters('crypto_settings_tabs', $tabs);
    }

    /**
     * Get settings sections.
     *
     * @since  1.0.0
     * @return array $sections Setting sections array.
     */
    public function get_sections()
    {
        $sections = array(
            array(
                'id' => 'crypto_general_settings',
                'title' => __('General settings', 'crypto'),
                'tab' => 'general',
            ),
            array(
                'id' => 'crypto_general_login',
                'title' => __('Login settings', 'crypto'),
                'tab' => 'login',
            ),
        );

        return apply_filters('crypto_settings_sections', $sections);
    }

    /**
     * Get settings fields.
     *
     * @since  1.0.0
     * @return array $fields Setting fields array.
     */
    public function get_fields()
    {

        $edit_help = ' <a style="text-decoration: none;" href="https://odude.com/docs/crypto-gallery/tutorial/modify-submission-form/" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>';

        //Import layout page link
        $layout_page = admin_url('admin.php?page=crypto');
        $layout_page = add_query_arg('tab', 'layout', $layout_page);

        //User dashboard link
        $user_dashboard_page_link = admin_url('admin.php?page=crypto_settings&tab=general&section=crypto_user_dashboard_settings');

        $fields = array(

            'crypto_general_settings' => array(
                array(
                    'name' => 'flexi_style_text_color',
                    'label' => __('Information', 'crypto'),
                    'description' => __('Choose the relevant tabs above and review the sub-tabs within them.', 'crypto'),
                    'type' => 'html',

                ),

            ),
            'crypto_general_login' => array(),

        );

        return apply_filters('crypto_settings_fields', $fields);
    }

    /**
     * Initialize and registers the settings sections and fields to WordPress.
     *
     * @since 1.0.0
     */
    public function initialize_settings()
    {
        // Register settings sections & fields
        foreach ($this->sections as $section) {
            $page_hook = $section['id'];

            // Sections
            if (false == get_option($section['id'])) {
                add_option($section['id']);
            }

            if (isset($section['description']) && !empty($section['description'])) {
                $callback = array($this, 'settings_section_callback');
            } elseif (isset($section['callback'])) {
                $callback = $section['callback'];
            } else {
                $callback = null;
            }

            add_settings_section($section['id'], $section['title'], $callback, $page_hook);

            // Fields
            $fields = $this->fields[$section['id']];

            foreach ($fields as $option) {
                $name = $option['name'];
                $type = isset($option['type']) ? $option['type'] : 'text';
                $label = isset($option['label']) ? $option['label'] : '';
                $callback = isset($option['callback']) ? $option['callback'] : array($this, 'callback_' . $type);
                $args = array(
                    'id' => $name,
                    'class' => isset($option['class']) ? $option['class'] : $name,
                    'label_for' => "{$section['id']}[{$name}]",
                    'description' => isset($option['description']) ? $option['description'] : '',
                    'name' => $label,
                    'section' => $section['id'],
                    'size' => isset($option['size']) ? $option['size'] : null,
                    'options' => isset($option['options']) ? $option['options'] : '',
                    'sanitize_callback' => isset($option['sanitize_callback']) ? $option['sanitize_callback'] : '',
                    'type' => $type,
                    'placeholder' => isset($option['placeholder']) ? $option['placeholder'] : '',
                    'min' => isset($option['min']) ? $option['min'] : '',
                    'max' => isset($option['max']) ? $option['max'] : '',
                    'step' => isset($option['step']) ? $option['step'] : '',
                    'name2' => isset($option['name2']) ? $option['name2'] : '',
                    'label_1' => isset($option['label_1']) ? $option['label_1'] : '',
                    'label_2' => isset($option['label_2']) ? $option['label_2'] : '',
                    'type_2' => isset($option['type_2']) ? $option['type_2'] : '',
                );

                add_settings_field("{$section['id']}[{$name}]", $label, $callback, $page_hook, $section['id'], $args);
            }

            // Creates our settings in the options table
            register_setting($page_hook, $section['id'], array($this, 'sanitize_options'));
        }
    }

    /**
     * gallerys a section description.
     *
     * @since 1.0.0
     * @param array $args Settings section args.
     */
    public function settings_section_callback($args)
    {
        foreach ($this->sections as $section) {
            if ($section['id'] == $args['id']) {
                printf('<div class="inside">%s</div>', '<div class="crypto_card">' . $section['description'] . '</div>');
                break;
            }
        }
    }

    /**
     * gallerys a text field for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_text($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], ''));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $type = isset($args['type']) ? $args['type'] : 'text';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="' . $args['placeholder'] . '"';

        $html = sprintf('<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder);
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    //Image display
    public function callback_image($args)
    {

        $src = CRYPTO_ROOT_URL;
        $html = sprintf('<img src="' . $src . '%1$s" class="%2$s" id="%3$s" size="%4$s"/>', $args['id'], $args['class'], $args['id'], $args['size']);
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * gallerys a url field for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_url($args)
    {
        $this->callback_text($args);
    }

    /**
     * gallerys a number field for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_number($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], 0));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $type = isset($args['type']) ? $args['type'] : 'number';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="' . $args['placeholder'] . '"';
        $min = empty($args['min']) ? '' : ' min="' . $args['min'] . '"';
        $max = empty($args['max']) ? '' : ' max="' . $args['max'] . '"';
        $step = empty($args['max']) ? '' : ' step="' . $args['step'] . '"';

        $html = sprintf('<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder, $min, $max, $step);
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    public function callback_double_input($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], 0));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $type = isset($args['type']) ? 'text' : 'number';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="' . $args['placeholder'] . '"';
        $min = empty($args['min']) ? '' : ' min="' . $args['min'] . '"';
        $max = empty($args['max']) ? '' : ' max="' . $args['max'] . '"';
        $step = empty($args['max']) ? '' : ' step="' . $args['step'] . '"';
        $name2 = empty($args['name2']) ? '' : $args['name2'];
        $label_1 = empty($args['label_1']) ? '' : $args['label_1'];
        $label_2 = empty($args['label_2']) ? '' : $args['label_2'];
        $type_2 = empty($args['type_2']) ? '' : $args['type_2'];

        $t_width = crypto_get_option($args['id'], $args['section'], 0);
        $t_height = crypto_get_option($name2, $args['section'], 0);

        $html = $label_1 . " " . sprintf('<input type="%1$s" class="%2$s-number" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/> ', $type_2, $size, $args['section'], $args['id'], $t_width, $placeholder, $min, $max, $step);
        $html .= $label_2 . " " . sprintf('<input type="%1$s" class="%2$s-number" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/> ', $type_2, $size, $args['section'], $name2, $t_height, $placeholder, $min, $max, $step);
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * gallerys a checkbox for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_checkbox($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], 0));

        $html = '<fieldset>';
        $html .= sprintf('<label for="%1$s[%2$s]">', $args['section'], $args['id']);
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="0" />', $args['section'], $args['id']);
        $html .= sprintf('<input type="checkbox" class="checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="1" %3$s />', $args['section'], $args['id'], checked($value, 1, false));
        $html .= sprintf('%1$s</label>', $args['description']);
        $html .= '</fieldset>';
        echo $this->allowed_html($html);
    }

    /**
     * gallerys a multicheckbox for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_multicheck($args)
    {
        $value = $this->get_option($args['id'], $args['section'], array());

        $html = '<fieldset>';
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id']);
        foreach ($args['options'] as $key => $label) {
            $checked = in_array($key, $value) ? 'checked="checked"' : '';
            $html .= sprintf('<label for="%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key);
            $html .= sprintf('<input type="checkbox" class="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, $checked);
            $html .= sprintf('%1$s</label><br>', $label);
        }
        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';

        echo $this->allowed_html($html);
    }

    /**
     * gallerys a radio button for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_radio($args)
    {
        $value = $this->get_option($args['id'], $args['section'], '');

        $html = '<fieldset>';
        foreach ($args['options'] as $key => $label) {
            $html .= sprintf('<label for="%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key);
            $html .= sprintf('<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked($value, $key, false));
            $html .= sprintf('%1$s</label><br>', $label);
        }
        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';

        echo $this->allowed_html($html);
    }

    /**
     * gallerys a selectbox for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_select($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], ''));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';

        $html = sprintf('<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id']);
        foreach ($args['options'] as $key => $label) {
            $html .= sprintf('<option value="%s"%s>%s</option>', $key, selected($value, $key, false), $label);
        }
        $html .= sprintf('</select>');
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * gallerys a textarea for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_textarea($args)
    {
        $value = esc_textarea($this->get_option($args['id'], $args['section'], ''));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $placeholder = empty($args['placeholder']) ? 'mmddd' : ' placeholder="' . $args['placeholder'] . '"';

        $html = sprintf('<textarea rows="15" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" %4$s >%5$s</textarea>', $size, $args['section'], $args['id'], $placeholder, $value);
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * gallerys the html for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_html($args)
    {
        echo $this->get_field_description($args);
    }

    /**
     * gallerys a rich text textarea for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_wysiwyg($args)
    {
        $value = $this->get_option($args['id'], $args['section'], '');
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : '500px';

        echo '<div style="max-width: ' . $size . ';">';
        $editor_settings = array(
            'teeny' => true,
            'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
            'textarea_rows' => 10,
        );
        if (isset($args['options']) && is_array($args['options'])) {
            $editor_settings = array_merge($editor_settings, $args['options']);
        }
        wp_editor($value, $args['section'] . '-' . $args['id'], $editor_settings);
        echo '</div>';
        echo $this->get_field_description($args);
    }

    /**
     * gallerys a file upload field for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_file($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], ''));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $id = $args['section'] . '[' . $args['id'] . ']';
        $label = isset($args['options']['button_label']) ? $args['options']['button_label'] : __('Choose File', 'crypto');

        $html = sprintf('<input type="text" class="%1$s-text crypto-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value);
        $html .= '<input type="button" class="button crypto-browse" value="' . $label . '" />';
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * gallerys a password field for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_password($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], ''));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';

        $html = sprintf('<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value);
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * gallerys a color picker field for a settings field.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_color($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], '#ffffff'));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';

        $html = sprintf('<input type="text" class="%1$s-text crypto-color-picker" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, '#ffffff');
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * gallerys a select box for creating the pages select box.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_pages($args)
    {
        $dropdown_args = array(
            'show_option_none' => '-- ' . __('Select a page', 'crypto') . ' --',
            'option_none_value' => '',
            'selected' => esc_attr($this->get_option($args['id'], $args['section'], '')),
            'name' => $args['section'] . '[' . $args['id'] . ']',
            'id' => $args['section'] . '[' . $args['id'] . ']',
            'echo' => 0,
        );

        $html = wp_dropdown_pages($dropdown_args);
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * List categories
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function callback_category($args)
    {
        $dropdown_args = array(
            'show_option_none' => '-- ' . __('Select category', 'crypto') . ' --',
            'option_none_value' => '',
            'selected' => esc_attr($this->get_option($args['id'], $args['section'], '')),
            'name' => $args['section'] . '[' . $args['id'] . ']',
            'id' => $args['section'] . '[' . $args['id'] . ']',
            'echo' => 0,
            'show_count' => 1,
            'hierarchical' => 1,
            'taxonomy' => 'crypto_category',
            'value_field' => 'slug',
            'hide_empty' => 0,

        );

        $html = wp_dropdown_categories($dropdown_args);
        $html .= $this->get_field_description($args);

        echo $this->allowed_html($html);
    }

    /**
     * Get field description for gallery.
     *
     * @since 1.0.0
     * @param array $args Settings field args.
     */
    public function get_field_description($args)
    {
        if (!empty($args['description'])) {
            if ('wysiwyg' == $args['type']) {
                $description = sprintf('<pre>%s</pre>', $args['description']);
            } else {
                $description = sprintf('<p class="description">%s</p>', $args['description']);
            }
        } else {
            $description = '';
        }

        return $description;
    }

    /**
     * Sanitize callback for Settings API.
     *
     * @since  1.0.0
     * @param  array $options The unsanitized collection of options.
     * @return                The collection of sanitized values.
     */
    public function sanitize_options($options)
    {
        if (!$options) {
            return $options;
        }

        foreach ($options as $option_slug => $option_value) {
            $sanitize_callback = $this->get_sanitize_callback($option_slug);

            // If callback is set, call it
            if ($sanitize_callback) {
                $options[$option_slug] = call_user_func($sanitize_callback, $option_value);
                continue;
            }
        }

        return $options;
    }

    /**
     * Get sanitization callback for given option slug.
     *
     * @since  1.0.0
     * @param  string $slug Option slug.
     * @return mixed        String or bool false.
     */
    public function get_sanitize_callback($slug = '')
    {
        if (empty($slug)) {
            return false;
        }

        // Iterate over registered fields and see if we can find proper callback
        foreach ($this->fields as $section => $options) {
            foreach ($options as $option) {
                if ($option['name'] != $slug) {
                    continue;
                }

                // Return the callback name
                return isset($option['sanitize_callback']) && is_callable($option['sanitize_callback']) ? $option['sanitize_callback'] : false;
            }
        }

        return false;
    }

    /**
     * Get the value of a settings field.
     *
     * @since  1.0.0
     * @param  string $option  Settings field name.
     * @param  string $section The section name this field belongs to.
     * @param  string $default Default text if it's not found.
     * @return string
     */
    public function get_option($option, $section, $default = '')
    {
        $options = get_option($section);

        if (!empty($options[$option])) {
            return $options[$option];
        }

        return $default;
    }
}