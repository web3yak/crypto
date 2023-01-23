<?php
class CryptoLogin_Widget extends WP_Widget
{

	public function __construct()
	{
		parent::__construct(
			'cryptologin_widget',
			esc_html__('Crypto Login', 'flexi'),
			array('description' => esc_html__('Show Login with Crypto Button', 'flexi')) // Args
		);
	}

	private $widget_fields = array(
		array(
			'label' => 'Hide if user logged-in',
			'id' => 'hide_login',
			//'default' => '1',
			'type' => 'checkbox',
		),
	);

	public function widget($args, $instance)
	{

		if (isset($instance['hide_login']) && '1' == $instance['hide_login'] && is_user_logged_in()) {
			echo '';
		} else {

			echo $args['before_widget'];

			//flexi_log($instance);

			if (!empty($instance['title'])) {
				echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
			}


			$execute = '[crypto-connect]';


			if (isset($instance['hide_login']) && '1' == $instance['hide_login']) {
				if (is_user_logged_in()) {
					$execute = '';
				}
			}

			echo do_shortcode($execute);
			echo $args['after_widget'];
		}
	}

	public function field_generator($instance)
	{
		$output = '';
		foreach ($this->widget_fields as $widget_field) {
			$default = '';
			if (isset($widget_field['default'])) {
				$default = $widget_field['default'];
			}
			$widget_value = !empty($instance[$widget_field['id']]) ? $instance[$widget_field['id']] : esc_html__($default, 'flexi');
			switch ($widget_field['type']) {
				case 'checkbox':
					$output .= '<p>';
					$output .= '<input class="checkbox" type="checkbox" ' . checked($widget_value, true, false) . ' id="' . esc_attr($this->get_field_id($widget_field['id'])) . '" name="' . esc_attr($this->get_field_name($widget_field['id'])) . '" value="1">';
					$output .= ' <label for="' . esc_attr($this->get_field_id($widget_field['id'])) . '">' . esc_attr($widget_field['label'], 'flexi') . '</label>';
					$output .= '</p>';
					break;
				case 'select':
					$output .= '<p>';
					$output .= '<label for="' . esc_attr($this->get_field_id($widget_field['id'])) . '">' . esc_attr($widget_field['label'], 'textdomain') . ':</label> ';
					$output .= '<select class="widefat" id="' . esc_attr($this->get_field_id($widget_field['id'])) . '" name="' . esc_attr($this->get_field_name($widget_field['id'])) . '">';
					foreach ($widget_field['options'] as $option => $value) {
						if ($widget_value == $value) {
							$output .= '<option value="' . $value . '" selected>' . $option . '</option>';
						} else {
							$output .= '<option value="' . $value . '">' . $option . '</option>';
						}
					}
					$output .= '</select>';
					$output .= '</p>';
					break;
				default:
					$output .= '<p>';
					$output .= '<label for="' . esc_attr($this->get_field_id($widget_field['id'])) . '">' . esc_attr($widget_field['label'], 'flexi') . ':</label> ';
					$output .= '<input class="widefat" id="' . esc_attr($this->get_field_id($widget_field['id'])) . '" name="' . esc_attr($this->get_field_name($widget_field['id'])) . '" type="' . $widget_field['type'] . '" value="' . esc_attr($widget_value) . '">';
					$output .= '</p>';
			}
		}
		echo $output;
	}

	public function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$cat = !empty($instance['cat']) ? $instance['cat'] : '';
?>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'flexi'); ?></label>
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
        name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
        value="<?php echo esc_attr($title); ?>">
</p>

<?php
		$this->field_generator($instance);
	}

	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['cat'] = (!empty($new_instance['cat'])) ? strip_tags($new_instance['cat']) : '';
		foreach ($this->widget_fields as $widget_field) {
			switch ($widget_field['type']) {
				default:
					$instance[$widget_field['id']] = (!empty($new_instance[$widget_field['id']])) ? strip_tags($new_instance[$widget_field['id']]) : '';
			}
		}
		return $instance;
	}
}

function register_crypto_login_widget()
{
	register_widget('CryptoLogin_Widget');
}
add_action('widgets_init', 'register_crypto_login_widget');
?>