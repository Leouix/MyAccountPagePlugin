<?php

namespace AdminSettings;

use Exception;

class AdminSettingsClass {

	/**
	 * @throws Exception
	 */
	static public function handleSaving($postData) {
		self::validateInputData($postData);
		self::save($postData);
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

	static public function getMyAccountSettingsPage() {

		global $wpdb;
		$tablename = $wpdb->prefix."my_account_page_plugin";
		$sql = "SELECT * FROM ".$tablename . " WHERE id = '1'";
		$results = $wpdb->get_results($sql);

		$pluginData = $results[0] ?? [];

		$fields_allowed_array = json_decode($pluginData->fields_allowed_json) !== ''
			? json_decode($pluginData->fields_allowed_json)
			: [];

		include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/AdminSettingsPage.php';
	}
}