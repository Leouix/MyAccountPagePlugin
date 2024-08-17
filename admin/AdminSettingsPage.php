<?php

/**
 * @var array $fields_allowed_array
 * @var string $user_page_url
 */

?>

<div class="au-container">
	<form id="admin-user-account-form">

        <h1>Plugin settings page</h1>

		<div class="url-label-area">
			<label for="adu-form-input" class="sub-title">URL user page:</label>
            <div class="input-url-wrapper">
	            <span><?php echo sprintf('%s', esc_url(get_bloginfo('url'))); ?>/</span><input id="adu-form-input"
                       type="text"
                       name="mya_url"
                       value="<?php echo sprintf('%s', esc_html($user_page_url)); ?>"
                       disabled
                ><div id="lock-url"><div id="dashicons-unlock" class="dashicons dashicons-unlock hidden"></div><div id="dashicons-lock" class="dashicons dashicons-lock visible"></div></div>

                <a id="adu-form-input-text"
                   href="<?php echo sprintf('%s', esc_url(get_bloginfo('url'))); ?>/<?php echo sprintf('%s', esc_html($user_page_url)); ?>"
                   target="_blank"
                >
	                <?php echo sprintf('%s', esc_url(get_bloginfo('url'))); ?>/<?php echo sprintf('%s', esc_html($user_page_url)); ?>
                </a>

		</div>

		<div  class="sub-title">Which fields should be shown in the user account?</div>

		<div class="label-area">
			<input type="checkbox" id="adu-display_name" name="show_user_fields_checkbox[]" value="display_name" <?php echo sprintf('%s', in_array("display_name", $fields_allowed_array) ? "checked" : '');
			?>>
			<label for="adu-display_name">display_name</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-user_url" name="show_user_fields_checkbox[]" value="user_url" <?php echo sprintf('%s', in_array("user_url", $fields_allowed_array) ? "checked" : '');
			?>>
			<label for="adu-user_url">user_url</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-nickname" name="show_user_fields_checkbox[]" value="nickname" <?php echo sprintf('%s', in_array("nickname", $fields_allowed_array) ? "checked" : '');?>>
			<label for="adu-nickname">nickname</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-first_name" name="show_user_fields_checkbox[]" value="first_name" <?php echo sprintf('%s', in_array("first_name", $fields_allowed_array) ? "checked" : ''); ?>>
			<label for="adu-first_name">first_name</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-last_name" name="show_user_fields_checkbox[]" value="last_name" <?php echo sprintf('%s', in_array("last_name", $fields_allowed_array) ? "checked" : '');
			?>>
			<label for="adu-last_name">last_name</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-description" name="show_user_fields_checkbox[]" value="description" <?php echo sprintf('%s', in_array("description", $fields_allowed_array) ? "checked" : '');
			?>>
			<label for="adu-description">description</label><br>
		</div>

        <div class="button-area">
            <input id="save-create-button" type="submit" value="Save And Create Public Page">
            <div id="success-notice">Success!</div>
        </div>

	</form>
</div>

<style>

</style>