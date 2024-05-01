<?php

namespace MyAccountPublic;

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

		$userMeta = [
			"nickname" => $userFields["nickname"],
			"first_name" => $userFields["first_name"],
			"last_name" => $userFields["last_name"],
			"description" => $userFields["description"],
		];

		$this->updateUserMeta($userId, $userMeta);

		$userData = array(
			'ID' => $this->loggedUserId,
			'user_nicename' => $userFields["user_nicename"],
			'user_email' => $userFields["user_email"],
			'user_registered' => $userFields["user_registered"],
			'display_name' => $userFields["display_name"],
			'user_url' => $userFields["user_url"],
		);

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
		$user_data = get_userdata($this->loggedUserId);

		$userData = new stdClass();
		$userData->ID              = $user_data->ID;
		$userData->user_nicename   = $user_data->user_nicename;
		$userData->user_email      = $user_data->user_email;
		$userData->user_registered = $user_data->user_registered;
		$userData->display_name    = $user_data->display_name;
		$userData->user_url        = $user_data->user_url;
		$userData->nickname        = $user_meta["nickname"][0];
		$userData->first_name      = $user_meta["first_name"][0];
		$userData->last_name       = $user_meta["last_name"][0];
		$userData->description     = $user_meta["description"][0];

		return include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/tab-info.php';

	}

	/**
	 * @throws \Exception
	 */
	public function handleUserSaving($postData) {

		$userData = array();
		$userData["user_nicename"]   = $postData["user_nicename"];
		$userData["user_email"]      = $postData["user_email"];
		$userData["user_registered"] = $postData["user_registered"];
		$userData["display_name"]    = $postData["display_name"];
		$userData["user_url"]        = $postData["user_url"];
		$userData["nickname"]        = $postData["nickname"];
		$userData["first_name"]      = $postData["first_name"];
		$userData["last_name"]       = $postData["last_name"];
		$userData["description"]     = $postData["description"];

		return $this->saveUserInfo( $this->loggedUserId, $userData );
	}

}