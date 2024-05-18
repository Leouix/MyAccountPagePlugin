=== Plugin Name ===
Contributors: Leouix
Donate link: https://weal.cloud/
Tags: plugin, плагин, профили пользователя, личный кабинет
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
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

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`