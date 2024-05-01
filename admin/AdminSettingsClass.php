<?php

namespace MyAccountAdmin;

use Exception;
use WP_Post;

class AdminSettingsClass {

	/**
	 * @throws Exception
	 */
	public static function handleSaving($postData) {
		self::validateInputData($postData);

		if (!self::isPluginTableExists()) {
			self::createDBTable();
		}

		self::checkPublicPage($postData);
	}

	public static function checkPublicPage($postData) {
		
		if ( self::getSettingUrl() !== null && self::getSettingUrl() !== $postData['mya_url']) {
			$oldPluginPublicPage = self::getPluginPublicPage(self::getSettingUrl());
			wp_delete_post($oldPluginPublicPage->ID, true);
		}

		self::save($postData);
		self::createMyAccountPagePublic();
	}

	/**
	 * @return bool
	 */
	public static function isPluginTableExists() {

		global $wpdb;
		$table_name = $wpdb->base_prefix.'my_account_page_plugin';
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

		if ( ! $wpdb->get_var( $query ) == $table_name ) {
			return false;
		} else {
			return true;
		}
	}

	static private function save($postData) {

		$fields_allowed = $postData['show_user_fields_checkbox'] ?? '';
		$data = array(
			'user_page_url' => $postData['mya_url'],
			'fields_allowed_json' => json_encode($fields_allowed)
		);

		global $wpdb;
		$tablename = $wpdb->prefix."my_account_page_plugin";
		$wpdb->update(
			$tablename,
			$data,
			array('ID' => 1),
			array('%s','%s'),
			array('%d')
		);
	}

	/**
	 * @throws Exception
	 */
	static private function validateInputData($postData) {
		if (empty($postData['mya_url'])) {
			throw new Exception('Please enter mya_url');
		}
	}

	/**
	 * @return void
	 */
	public static function getMyAccountSettingsPage() {

		$pluginData = [];
		$fields_allowed_array = [];

		if (self::isPluginTableExists()) {
			global $wpdb;
			$tablename = $wpdb->prefix."my_account_page_plugin";
			$sql = /** @lang text */
				"SELECT * FROM " . $tablename . " WHERE id = '1'";
			$results = $wpdb->get_results($sql);

			$pluginData = $results[0] ?? [];

			if (!empty($pluginData)) {
				$fields_allowed_array = json_decode($pluginData->fields_allowed_json) !== ''
					? json_decode($pluginData->fields_allowed_json)
					: [];
			}
		}

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/AdminSettingsPage.php';
	}

	public static function getSettingFieldsAllowedJson() {

		global $wpdb;
		$tablename = $wpdb->prefix."my_account_page_plugin";
		$sql = /** @lang text */
			"SELECT `fields_allowed_json` FROM `" . $tablename . "` WHERE id = '1'";
		$results = $wpdb->get_results($sql);

		$pluginData = $results[0] ?? [];

		return json_decode($pluginData->fields_allowed_json) !== ''
			? json_decode($pluginData->fields_allowed_json)
			: [];
	}

	/**
	 * @return string|null
	 */
	public static function getSettingUrl() {

		$url = 'my-account';

		if (self::isPluginTableExists()) {
			global $wpdb;
			$tablename = $wpdb->prefix."my_account_page_plugin";
			$sql = /** @lang text */
				"SELECT `user_page_url` FROM `" . $tablename . "` WHERE id = '1'";
			$results = $wpdb->get_results($sql);

			$pluginData = $results[0] ?? null;

			if (!empty($pluginData) && !empty($pluginData->user_page_url)) {
				$url = $pluginData->user_page_url;
			}

		}

		return $url;
	}

	public static function createDBTable()
	{
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix."my_account_page_plugin";

		$sql = /** @lang text */
			"CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_page_url varchar(255) NOT NULL,
			fields_allowed_json varchar(255) NULL,
			is_comments_allowed tinyint NULL,
			is_users_allowed tinyint NULL,
			PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		self::insertEmptyData();
	}

	private static  function insertEmptyData() {
		global $wpdb;
		$tablename = $wpdb->prefix."my_account_page_plugin";
		$wpdb->insert(
			$tablename,
			array(
				'user_page_url' => 'my-account',
				'fields_allowed_json' => json_encode('')
			),
			array('%s','%s'),
		);
	}

	public static function createMyAccountPagePublic()
	{
		$postDataUrl = self::getSettingUrl();

		$check_page_exist = self::getPluginPublicPage($postDataUrl);
		if($check_page_exist === null) {
			wp_insert_post(
				array(
					'comment_status' => 'close',
					'ping_status'    => 'close',
					'post_author'    => 1,
					'post_title'     => ucwords('my-account'),
					'post_name'      => strtolower(str_replace(' ', '-', trim($postDataUrl))),
					'post_status'    => 'publish',
					'post_content'   => '',
					'post_type'      => 'page'
				)
			);
		}
	}

	/**
	 * @param $postDataUrl
	 * @return WP_Post|null
	 */
	public static function getPluginPublicPage($postDataUrl) {
		return get_page_by_path($postDataUrl);
	}
}