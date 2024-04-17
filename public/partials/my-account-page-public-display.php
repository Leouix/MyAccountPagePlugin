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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<style>
    #main-tabs {
        display: flex;

    }

    #main-tabs .main-tabs-item {
        width: 45px;
        height: 110px;
        border: 1px solid #fff;
        border-radius: 4px;
        background: #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #main-tabs .main-tabs-item.active {
        background: #4e84ff;
        height: 130px;
    }

    #main-tabs .main-tabs-item .text {
        rotate: -90deg;
        width: 100%;
        margin-bottom: -35px;
    }

</style>

<div id="main-tabs">
    <div class="main-tabs-item active"><div class="text">My Comments</div></div>
    <div class="main-tabs-item"><div class="text">Users</div></div>
    <div class="main-tabs-item"><div class="text">Info</div></div>
</div>