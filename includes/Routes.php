<?php

namespace MyAccountRoutes;

use MyAccountAdmin\AdminSettingsClass;
use MyAccountPublic\InfoTabClass;
use WP_REST_Response;
use Exception;

class Routes {

	/**
	 * @var mixed
	 */
	private $current_user;

	public function routeReg() {

		register_rest_route( 'my-account/v1', '/switchTabAjax/', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'switchTabAjax' ],
			'permission_callback' => '__return_true',
			'login_user_id'       => get_current_user_id(),
		) );

		register_rest_route( 'my-account/v1', '/info-tab/', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'savePublicUserInfo' ],
			'permission_callback' => '__return_true',
			'login_user_id'       => get_current_user_id(),
		) );

		register_rest_route( 'my-account/v1', '/admin-save-page-settings/', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'adminSavePageSettings' ],
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 * @throws Exception
	 */
	public function adminSavePageSettings($request) {
		$postData = $request->get_params();
		AdminSettingsClass::handleSaving($postData);
	}

	/**
	 * @throws Exception
	 */
	public function savePublicUserInfo( $request ) {
		$this->userLoggedValidate($request);
		$postData = $request->get_params();
		$infoTabClass = new InfoTabClass($this->current_user);
		$infoTabClass->handleUserSaving($postData);
	}

	/**
	 * @throws Exception
	 */
	public function switchTabAjax($request) {

		$this->userLoggedValidate($request);
		$this->tabNameExistsValidate($request);

		$postData = $request->get_params();

		ob_start();

		switch ($postData['tabName']) {
			case "users":
				$this->usersTab();
				break;
			case "my-comments":
				$this->myCommentsTab();
				break;
			case "info":
				$this->infoTab();
				break;
			default:
				return false;
		}

		$data = ob_get_contents();
		ob_end_clean();

		return new WP_REST_Response( array(
			'html' => $data,
		) );
	}

	private function usersTab() {
		return include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/tab-users.php';
	}

	private function myCommentsTab() {
		return include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/tab-my-comments.php';
	}

	private function infoTab() {
		$infoTabClass = new InfoTabClass($this->current_user);
		return $infoTabClass->getUserData();
	}

	/**
	 * @throws Exception
	 */
	private function userLoggedValidate( $request ) {
		$attrs =  $request->get_attributes();

		if ( empty($attrs['login_user_id']) ) {
			throw new Exception('Login User ID is required.');
		}

		$this->current_user = $attrs['login_user_id'];
	}

	/**
	 * @throws Exception
	 */
	private function tabNameExistsValidate($request) {
		$postData = $request->get_params();
		if (empty($postData['tabName'])) {
			throw new Exception('Tab Name is required.');
		}
	}
}