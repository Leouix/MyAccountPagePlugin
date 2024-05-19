<?php

namespace MyAccountPublic;

use MyAccountAdmin\AdminSettingsClass;
use stdClass;

class InfoTabClass {

	private $loggedUserId;

	public function __construct($loggedUserId) {
		$this->loggedUserId = $loggedUserId;
	}

	/**
	 * @throws \Exception
	 */
	public function saveUserInfo( $userId, $userFields ) {

		$userMeta = array_filter([
			"nickname" => $userFields["nickname"] ?? null,
			"first_name" => $userFields["first_name"] ?? null,
			"last_name" => $userFields["last_name"] ?? null,
			"description" => $userFields["description"] ?? null,
		], function($value) {
			return !is_null($value);
		});

		$this->updateUserMeta($userId, $userMeta);

		$userData = array_merge([
			'ID' => $this->loggedUserId
		], array_filter([
			'user_nicename' => $userFields["user_nicename"] ?? null,
			'user_email' => $userFields["user_email"] ?? null,
			'user_registered' => $userFields["user_registered"] ?? null,
			'display_name' => $userFields["display_name"] ?? null,
			'user_url' => $userFields["user_url"] ?? null,
		], function($value) {
			return !is_null($value);
		}));

		try {
			return wp_update_user( $userData );
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage(), $e->getCode());
		}
	}

	/**
	 * @throws \Exception
	 */
	public function updateUserMeta($userId, $userMeta) {
		try {
			foreach($userMeta as $key => $value) {
				update_user_meta( $userId, $key, $value );
			}
		} catch (\Exception $e) {
			throw new \Exception( $e->getMessage() );
		}

	}

	public function getUserData() {

		$user_meta = get_user_meta($this->loggedUserId);
		$metaFields = [
			"nickname",
			"first_name",
			"last_name",
			"description"
		];

		$user_data = get_userdata($this->loggedUserId);
		$adminSettings = new AdminSettingsClass($this->loggedUserId);
		$allowedFields = $adminSettings->getSettingFieldsAllowedJson();

		$userData = new stdClass();
		$userData->ID = $user_data->ID;

		foreach ($allowedFields as $allowedField) {
			if (in_array($allowedField, $metaFields)) {
				$userData->$allowedField = $user_meta[$allowedField][0];
			} else {
				$userData->$allowedField = $user_data->$allowedField;
			}
		}

		 return include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/tab-info.php';
	}

	/**
	 * @throws \Exception
	 */
	public function handleUserSaving($postData) {

		$userData = array();
		$userData["user_nicename"]   = $postData["user_nicename"] ?? null;
		$userData["user_email"]      = $postData["user_email"] ?? null;
		$userData["user_registered"] = $postData["user_registered"] ?? null;
		$userData["display_name"]    = $postData["display_name"] ?? null;
		$userData["user_url"]        = $postData["user_url"] ?? null;
		$userData["nickname"]        = $postData["nickname"] ?? null;
		$userData["first_name"]      = $postData["first_name"] ?? null;
		$userData["last_name"]       = $postData["last_name"] ?? null;
		$userData["description"]     = $postData["description"] ?? null;

		return $this->saveUserInfo( $this->loggedUserId, $userData );
	}

}