<?php

namespace MyAccountPagePlugin;

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
			wp_update_user( $userData );
			echo 'User profile updated.';
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

	public function wp_llog($value) {
		file_put_contents(plugin_dir_path( dirname( __FILE__ ) ) . 'public/' . rand(99, 999) . '.txt', print_r($value, true));
	}
}