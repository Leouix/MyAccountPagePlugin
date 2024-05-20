<?php

namespace MyAccountAdmin;

use Exception;
use WP_Post;

class AdminSettingsClass {

	/**
	 * @var mixed
	 */
	private $loggedUserId;

	public function __construct( $loggedUserId ) {
		$this->loggedUserId = $loggedUserId;
	}

	/**
	 * @throws Exception
	 */
	public function handleSaving( $postData ) {
		// $this->validateInputData($postData);

		if ( ! $this->isPluginTableExists() ) {
			$this->createDBTable();
		}

		$this->checkPublicPage( $postData );
	}

	public function checkPublicPage( $postData ) {

		if ( $this->getSettingUrl() !== null && ! empty( $postData['mya_url'] ) && $this->getSettingUrl() !== $postData['mya_url'] ) {
			$oldPluginPublicPage = $this->getPluginPublicPage( $this->getSettingUrl() );
			wp_delete_post( $oldPluginPublicPage->ID, true );
		}

		$this->save( $postData );
		$this->createMyAccountPagePublic();
	}

	/**
	 * @return bool
	 */
	public function isPluginTableExists() {

		global $wpdb;
		$table_name = $wpdb->base_prefix . 'my_account_page_plugin';
		$query      = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

		if ( ! $wpdb->get_var( $query ) == $table_name ) {
			return false;
		} else {
			return true;
		}
	}

	private function save($postData) {
		$fields_allowed = $postData['show_user_fields_checkbox'] ?? '';
		$data = array(
			'fields_allowed_json' => wp_json_encode($fields_allowed)
		);

		if (!empty($postData['mya_url'])) {
			$data['user_page_url'] = $postData['mya_url'];
		}

		global $wpdb;
		$tablename = $wpdb->prefix . "my_account_page_plugin";

		$updated = $wpdb->update(
			$tablename,
			$data,
			array('ID' => $this->loggedUserId),
			array('%s', '%s'),
			array('%d')
		);

		if ($updated !== false) {
			$cache_key = 'my_account_page_plugin_settings';
			$cache_group = 'my_account_page_plugin';
			wp_cache_delete($cache_key, $cache_group);
		}
	}


	/**
	 * @throws Exception
	 */
	private function validateInputData( $postData ) {
		if ( empty( $postData['mya_url'] ) ) {
			throw new Exception( 'Please enter mya_url' );
		}
	}

	/**
	 * @return void
	 */
	public function getMyAccountSettingsPage() {
		$pluginData = [];
		$fields_allowed_array = [];

		if ($this->isPluginTableExists()) {
			$cache_key = 'my_account_page_plugin_settings';
			$cache_group = 'my_account_page_plugin';
			$pluginData = wp_cache_get($cache_key, $cache_group);

			if ($pluginData === false) {
				global $wpdb;
				$tablename = $wpdb->prefix . "my_account_page_plugin";
				$sql       = /** @lang text */
					"SELECT * FROM " . $tablename;
				$results = $wpdb->get_results($sql);
				$pluginData = $results[0] ?? [];
				wp_cache_set($cache_key, $pluginData, $cache_group, 3600);
			}

			if (!empty($pluginData)) {
				$fields_allowed_array = json_decode($pluginData->fields_allowed_json) !== ''
					? json_decode($pluginData->fields_allowed_json)
					: [];
			}
		}

		include plugin_dir_path(dirname(__FILE__)) . 'admin/AdminSettingsPage.php';
	}


	public function getSettingFieldsAllowedJson() {

		global $wpdb;
		$tablename = $wpdb->prefix . "my_account_page_plugin";
		$sql       = /** @lang text */
			"SELECT `fields_allowed_json` FROM `" . $tablename . "`";
		$results   = $wpdb->get_results( $sql );

		$pluginData = $results[0] ?? [];

		return json_decode( $pluginData->fields_allowed_json ) !== ''
			? json_decode( $pluginData->fields_allowed_json )
			: [];
	}

	/**
	 * @return string|null
	 */
	public function getSettingUrl() {

		$url = null;

		if ( $this->isPluginTableExists() ) {
			global $wpdb;
			$tablename = $wpdb->prefix . "my_account_page_plugin";
			$sql       = /** @lang text */
				"SELECT `user_page_url` FROM `" . $tablename ."`";
			$results   = $wpdb->get_results( $sql );

			$pluginData = $results[0] ?? null;

			if ( ! empty( $pluginData ) && ! empty( $pluginData->user_page_url ) ) {
				$url = $pluginData->user_page_url;
			}

		}

		return $url;
	}

	public function createDBTable() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . "my_account_page_plugin";

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

		$this->insertEmptyData();
	}

	private function insertEmptyData() {
		global $wpdb;
		$tablename = $wpdb->prefix . "my_account_page_plugin";
		$wpdb->insert(
			$tablename,
			array(
				'user_page_url'       => 'my-account',
				'fields_allowed_json' => wp_json_encode( '' )
			),
			array( '%s', '%s' ),
		);
	}

	public function createMyAccountPagePublic() {
		$postDataUrl = $this->getSettingUrl();

		$check_page_exist = $this->getPluginPublicPage( $postDataUrl );
		if ( $check_page_exist === null ) {
			wp_insert_post(
				array(
					'comment_status' => 'close',
					'ping_status'    => 'close',
					'post_author'    => 1,
					'post_title'     => ucwords( 'my-account' ),
					'post_name'      => strtolower( str_replace( ' ', '-', trim( $postDataUrl ) ) ),
					'post_status'    => 'publish',
					'post_content'   => '',
					'post_type'      => 'page'
				)
			);
		}
	}

	/**
	 * @param $postDataUrl
	 *
	 * @return WP_Post|null
	 */
	public function getPluginPublicPage( $postDataUrl ) {
		return get_page_by_path( $postDataUrl );
	}
}