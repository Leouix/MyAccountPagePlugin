<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://weal.cloud
 * @since      1.0.0
 *
 * @package    My_Account_Page
 * @subpackage My_Account_Page/public/partials
 */
?>

<div id="main-tabs">
    <div id="tab-button-1"
         class="main-tabs-item active"
         onclick="clickHandle(this)">
        <div class="text">My Comments</div>
    </div>
    <div id="tab-button-2"
         class="main-tabs-item"
         onclick="clickHandle(this)">
        <div class="text">Users</div>
    </div>
    <div id="tab-button-3"
         class="main-tabs-item"
         onclick="clickHandle(this)">
        <div class="text">Info</div>
    </div>
</div>

