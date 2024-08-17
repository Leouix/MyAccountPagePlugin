<?php

namespace MyAccountAdmin;

use Exception;
use WP_Post;

class AdminSettingsClass {

	/**
	 * @var mixed
	 */
	private $loggedUserId;

	/**
	 * @var array
	 */
	public $adminSettings = [
		"user_page_url" => null,
		"fields_allowed_json" => null,
	];

	public function __construct( $loggedUserId, $adminSettings ) {
		$this->loggedUserId = $loggedUserId;
		$this->adminSettings = $adminSettings;
	}

	/**
	 * @throws Exception
	 */
	public function handleSaving( $postData ) {
		$postData = $this->validateInputData($postData);

		if ( ! $this->isPluginTableExists() ) {
            (new DBCreator)->createDBTable();
		}

		$this->checkPublicPage( $postData );
	}

	public function checkPublicPage( $postData ) {
    if ( $this->adminSettings["user_page_url"] !== null
         && ! empty( $postData['mya_url'] )
         &&  $this->adminSettings["user_page_url"] !== sanitize_text_field($postData['mya_url'])
    ) {
        $oldPluginPublicPage = $this->getPluginPublicPage(  $this->adminSettings["user_page_url"] );
        wp_delete_post( $oldPluginPublicPage->ID, true );
    }

    $this->save( $postData );

	if (!empty( $postData['mya_url'])) {
		$this->createMyAccountPagePublic(sanitize_text_field($postData['mya_url']));
	}
}

	/**
	 * @return bool
	 */
	public function isPluginTableExists() {
		global $wpdb;
		$table_name = $wpdb->base_prefix . 'my_account_page_plugin';
		$cache_key = 'is_plugin_table_exists';
		$cache_group = 'my_account_page_plugin';

		$is_table_exists = wp_cache_get($cache_key, $cache_group);

		if ($is_table_exists === false) {
			$query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
			$result = $wpdb->get_var($query);

			$is_table_exists = ($result == $table_name);

			wp_cache_set($cache_key, $is_table_exists, $cache_group, 3600);
		}

		return $is_table_exists;
	}

	private function save($postData) {
	//	$fields_allowed = $postData['show_user_fields_checkbox'] ?? '';
		$data = array(
			'fields_allowed_json' => wp_json_encode($postData)
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
			$cache_key_settings = 'my_account_page_plugin_settings';
			$cache_key_url = 'my_account_page_plugin_user_page_url';
			$cache_group = 'my_account_page_plugin';
			wp_cache_delete($cache_key_settings, $cache_group);
			wp_cache_delete($cache_key_url, $cache_group);
		}
	}

	/**
	 * @throws Exception
	 */
	private function validateInputData( $postData ) {

		$expectedFields = array(
			'user_nicename',
			'user_email',
			'user_registered',
			'display_name',
			'user_url',
			'nickname',
			'first_name',
			'last_name',
			'description'
		);

		// Check if show_user_fields_checkbox is set and is an array
		if (!isset($postData['show_user_fields_checkbox']) || !is_array($postData['show_user_fields_checkbox'])) {
			throw new Exception('Invalid checkbox data');
		}

		// Check if all selected fields are in the expectedFields array
		foreach ($postData['show_user_fields_checkbox'] as $field) {
			if (!in_array($field, $expectedFields)) {
				throw new Exception(sprintf('Invalid checkbox field: %s', $field));
			}
		}

		// Sanitize the checkbox fields
		return array_map(function ($field) {
			return sanitize_text_field($field);
		}, $postData['show_user_fields_checkbox']);
	}

	/**
	 * @return void
	 */
	public function getMyAccountSettingsPage() {
		$user_page_url = $this->adminSettings["user_page_url"];
		$fields_allowed_array = $this->adminSettings["fields_allowed_json"];
		include plugin_dir_path(dirname(__FILE__)) . 'admin/AdminSettingsPage.php';
	}

	public function getSettingFieldsAllowedJson() {
		return $this->adminSettings["fields_allowed_json"];
	}

	/**
	 * @return string|null
	 */
	public function getSettingUrl() {
		return $this->adminSettings["user_page_url"];
	}

	public function createMyAccountPagePublic($mya_url) {

		$check_page_exist = $this->getPluginPublicPage( $mya_url );
		if ( $check_page_exist === null ) {
			wp_insert_post(
				array(
					'comment_status' => 'close',
					'ping_status'    => 'close',
					'post_author'    => 1,
					'post_title'     => ucwords( 'my-account' ),
					'post_name'      => strtolower( str_replace( ' ', '-', trim( $mya_url ) ) ),
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

    private function log_variable($variable, $label = '')
    {
        // var_dump('222222222222222222222222222222222222222222222');
        // Преобразуем переменную в строку для записи в лог
        $output = print_r($variable, true);

        // Добавляем метку, если она предоставлена
        if (!empty($label)) {
            $output = "[$label] " . $output;
        }

        // Форматируем строку лога с текущей датой и временем
        $logEntry = "[" . date('Y-m-d H:i:s') . "] " . $output . PHP_EOL;

        // Записываем строку в файл file-log.txt
        file_put_contents('file-log.txt', $logEntry, FILE_APPEND);
    }

	public function llog($stringName, $var) {
		echo $stringName . ": ";
		print_r($var);
		echo PHP_EOL;
	}

}