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

<?php wp_head(); ?>


<div class="au-container">
    <div id="main-tabs">
        <div id="tab-button-1"
             class="main-tabs-item"
             onclick="switchTab(this)">
            <div class="text">Comments</div>
        </div>
        <div id="tab-button-3"
             class="main-tabs-item"
             onclick="switchTab(this)">
            <div class="text">My Info</div>
        </div>
    </div>

    <div id="container-results"></div>
</div>



<?php wp_footer();
