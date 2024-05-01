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
}