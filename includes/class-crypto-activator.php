<?php

/**
 * Fired during plugin activation
 *
 * @link       https://odude.com/
 * @since      1.0.0
 *
 * @package    Crypto
 * @subpackage Crypto/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Crypto
 * @subpackage Crypto/includes
 * @author     ODude <navneet@odude.com>
 */
class Crypto_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $wpdb;
		if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'search-domain'", 'ARRAY_A')) {

			$current_user = wp_get_current_user();

			// create post object
			$page = array(
				'post_title'  => __('Search Domain'),
				'post_status' => 'publish',
				'post_author' => $current_user->ID,
				'post_type'   => 'page',
				'post_content' => '<!-- wp:shortcode -->
			  [crypto-domain-search]
			  <!-- /wp:shortcode -->
			  
			  <!-- wp:shortcode -->
			  [crypto-connect label="Connect" class="fl-button fl-is-info fl-is-light"]
			  <!-- /wp:shortcode -->'
			);

			// insert the post into the database
			$aid = wp_insert_post($page);

			crypto_set_option('search_page', 'crypto_marketplace_settings', $aid);
		}

		if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'my-domain'", 'ARRAY_A')) {

			$current_user = wp_get_current_user();

			// create post object
			$page = array(
				'post_title'  => __('My Domain'),
				'post_status' => 'publish',
				'post_author' => $current_user->ID,
				'post_type'   => 'page',
				'post_content' => '<!-- wp:paragraph -->
				<p>[crypto-domain-market]</p>
				<!-- /wp:paragraph -->
				
				<!-- wp:paragraph -->
				<p>[crypto-connect label="Connect to Login" class="fl-button fl-is-info fl-is-light"]</p>
				<!-- /wp:paragraph -->'
			);

			// insert the post into the database
			$aid = wp_insert_post($page);

			crypto_set_option('market_page', 'crypto_marketplace_settings', $aid);
		}

		if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'domain-url'", 'ARRAY_A')) {

			$current_user = wp_get_current_user();

			// create post object
			$page = array(
				'post_title'  => __('Domain Redirect'),
				'post_status' => 'publish',
				'post_author' => $current_user->ID,
				'post_type'   => 'page',
				'post_content' => '<!-- wp:shortcode -->
				[crypto-domain-url]
				<!-- /wp:shortcode -->
				
				<!-- wp:shortcode -->
				[crypto-connect label="Connect Metamask" class="fl-button fl-is-info fl-is-light"]
				<!-- /wp:shortcode -->'
			);

			// insert the post into the database
			$aid = wp_insert_post($page);

			crypto_set_option('url_page', 'crypto_marketplace_settings', $aid);
		}

		if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'domain-url'", 'ARRAY_A')) {

			$current_user = wp_get_current_user();

			// create post object
			$page = array(
				'post_title'  => __('Domain Information'),
				'post_status' => 'publish',
				'post_author' => $current_user->ID,
				'post_type'   => 'page',
				'post_content' => '<!-- wp:shortcode -->
				[crypto-domain-info]
				<!-- /wp:shortcode -->
				
				<!-- wp:shortcode -->
				[crypto-connect label="Connect Metamask" class="fl-button fl-is-info fl-is-light"]
				<!-- /wp:shortcode -->'
			);

			// insert the post into the database
			$aid = wp_insert_post($page);

			crypto_set_option('info_page', 'crypto_marketplace_settings', $aid);
		}
		crypto_set_option('primary_domain', 'crypto_marketplace_settings', 'usa');
		crypto_set_option('price_ether', 'crypto_marketplace_settings', '1');
		crypto_set_option('chainid', 'crypto_login_metamask', '0');
		flush_rewrite_rules();
	}
}