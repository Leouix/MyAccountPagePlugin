=== Manage User Page ===
Contributors: Leouix
Donate link: https://weal.cloud/
Tags: manage-user-page, user-profile, user-account, account-page
Tested up to: 6.0
Requires PHP: 5.6
Requires at least: 5.6
Stable tag: 1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Plugin creates a page /my-account for manage user info.

== Description ==

The plugin creates:

- Admin page for manage plugin, Item in the menu of the admin panel "My Account Page"
- Public page with the address /my-account

To admin.
At /wp-admin/admin.php?page=my-account-page-admin are configuring:

    *   URL of the user’s personal account, by default “/my-account”;
    *   Fields that are allowed for the public page "/my-account"
        - user_nicename
        - user_email
        - user_registered
        - display_name
        - user_url
        - nickname
        - first_name
        - last_name
        - description

On the public page (/my-account)

    User can:
    *   Edit him-self data;
    *   See him-self comments and links to posts;

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= How can I access another user’s page? =

At the moment, the user can only see and manage his own information, and cannot access the pages of other users. It is planned to add such a feature in the next update.

== Screenshots ==

1. `/assets/Screenshot-1.png`
2. `/assets/Screenshot-2.png`
3. `/assets/Screenshot-3.png`

== Frequently Asked Questions ==

== Changelog ==

= 1.0 =

== Upgrade Notice ==

= 1.0 =

`<?php code(); // goes in backticks ?>`