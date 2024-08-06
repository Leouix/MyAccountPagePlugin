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
		// $this->validateInputData($postData);


//        $exampleVar = array('key1' => 'value1', 'key2' => 'value2');
//        $this->log_variable($exampleVar, 'Example Array');

		if ( ! $this->isPluginTableExists() ) {
			$this->createDBTable();
		}

		$this->checkPublicPage( $postData );
	}

	public function checkPublicPage( $postData ) {

		if ( $this->adminSettings["user_page_url"] !== null
		     && ! empty( $postData['mya_url'] )
		     &&  $this->adminSettings["user_page_url"] !== $postData['mya_url']
		) {
			$oldPluginPublicPage = $this->getPluginPublicPage(  $this->adminSettings["user_page_url"] );
			wp_delete_post( $oldPluginPublicPage->ID, true );
		}

		$this->save( $postData );
		$this->createMyAccountPagePublic($postData['mya_url']);
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
		if ( empty( $postData['mya_url'] ) ) {
			throw new Exception( 'Please enter mya_url' );
		}
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
        var_dump('222222222222222222222222222222222222222222222');
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

}