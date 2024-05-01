<?php

/**
 * @var array $fields_allowed_array
 * @var array $pluginData
 */

?>

<div class="au-container">
	<form id="admin-user-account-form">

		<div class="url-label-area">
			<label for="adu-form-input">URL страницы пользователя:</label>
            <div class="input-url-wrapper">
	            <span><?php echo get_bloginfo('url'); ?>/</span><input id="adu-form-input" type="text" name="mya_url" value="<?php echo $pluginData->user_page_url ?? 'my-account'; ?>">
            </div>
		</div>

		<h4>Какие поля показывать в кабинете пользователя?</h4>

		<div class="label-area">
			<input type="checkbox" id="adu-user-nicename" name="show_user_fields_checkbox[]" value="user_nicename"  <?php echo in_array("user_nicename", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-user-nicename"> user_nicename</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-user-email" name="show_user_fields_checkbox[]" value="user_email"  <?php echo in_array("user_email", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-user-email"> user_email</label><br>
		</div>


		<div class="label-area">
			<input type="checkbox" id="adu-user-registered" name="show_user_fields_checkbox[]" value="user_registered"  <?php echo in_array("user_registered", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-user-registered">user_registered</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-display_name" name="show_user_fields_checkbox[]" value="display_name" <?php echo in_array("display_name", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-display_name">display_name</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-user_url" name="show_user_fields_checkbox[]" value="user_url" <?php echo in_array("user_url", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-user_url">user_url</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-nickname" name="show_user_fields_checkbox[]" value="nickname" <?php echo in_array("nickname", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-nickname">nickname</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-first_name" name="show_user_fields_checkbox[]" value="first_name" <?php echo in_array("first_name", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-first_name">first_name</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-last_name" name="show_user_fields_checkbox[]" value="last_name" <?php echo in_array("last_name", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-last_name">last_name</label><br>
		</div>

		<div class="label-area">
			<input type="checkbox" id="adu-description" name="show_user_fields_checkbox[]" value="description" <?php echo in_array("description", $fields_allowed_array) ? "checked" : ''?>>
			<label for="adu-description">description</label><br>
		</div>

		<input type="submit" value="Save And Create Public Page">

	</form>
</div>

<style>

</style>