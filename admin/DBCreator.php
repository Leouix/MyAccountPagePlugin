<?php

namespace MyAccountAdmin;

class DBCreator
{

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
                'fields_allowed_json' => wp_json_encode( ["display_name","user_url","nickname","description"] )
            ),
            array( '%s', '%s' ),
        );
    }
}