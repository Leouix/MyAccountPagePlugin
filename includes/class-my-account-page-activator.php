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
        self::createMyAccountPage();
	}

    private static function createDBTable()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix."my_account_page";

        $sql = /** @lang text */
            "CREATE TABLE IF NOT EXISTS $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          name tinytext NOT NULL,
          url varchar(255) DEFAULT '' NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private static function createMyAccountPage()
    {

    }
}
