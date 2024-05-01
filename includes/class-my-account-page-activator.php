<?php

/**
 * Fired during plugin activation
 *
 * @link       https://weal.cloud
 * @since      1.0.0
 *
 * @package    My_Account_Page
 * @subpackage My_Account_Page/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    My_Account_Page
 * @subpackage My_Account_Page/includes
 * @author     leouix <nsht22sola@gmail.com>
 */
class My_Account_Page_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::createDBTable();
        self::insertPost();
        self::createMyAccountPagePublic();
	}

    private static function createDBTable()
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
    }

	private static  function insertPost() {
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

    private static function createMyAccountPagePublic()
    {
        $check_page_exist = self::get_page_my_account();
        if($check_page_exist === null) {
            wp_insert_post(
                array(
                    'comment_status' => 'close',
                    'ping_status'    => 'close',
                    'post_author'    => 1,
                    'post_title'     => ucwords('my-account'),
                    'post_name'      => strtolower(str_replace(' ', '-', trim('my-account'))),
                    'post_status'    => 'publish',
                    'post_content'   => '',
                    'post_type'      => 'page'
                )
            );
        }
    }

    private static function get_page_my_account($title="my-account") {
        $pages = get_posts( [
            'title'     => $title,
            'post_type' => 'page',
        ] );

        return ! empty( $pages[0] ) ? get_post( $pages[0]->ID) : null;
    }
}
