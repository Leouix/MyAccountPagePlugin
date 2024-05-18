<?php
/**
 * @var stdClass $userData
 */
?>

<h2>User Info</h2>

<form id="user-data-form" enctype="multipart/form-data">

    <?php if (isset($userData->user_nicename)) { ?>
        <div class="input-area">
            <label for="user-nicename">User Nicename:</label>
            <input
                    id="user-nicename"
                    name="user_nicename"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->user_nicename); ?>"
                    value="<?php echo esc_html($userData->user_nicename); ?>"
            >
        </div>
    <?php } ?>

	<?php if (isset($userData->user_email)) { ?>
        <div class="input-area">
            <label for="user-email">User Email:</label>
            <input
                    id="user-email"
                    name="user_email"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->user_email); ?>"
                    value="<?php echo esc_html($userData->user_email); ?>"
            >
        </div>
	<?php } ?>


	<?php if (isset($userData->user_registered)) { ?>
        <div class="input-area">
            <label for="user-registered">User Registered:</label>
            <input
                    id="user-registered"
                    name="user_registered"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->user_registered); ?>"
                    value="<?php echo esc_html($userData->user_registered); ?>"
            >
        </div>
	<?php } ?>

	<?php if (isset($userData->display_name)) { ?>
        <div class="input-area">
            <label for="display-name">Display Name:</label>
            <input
                    id="display-name"
                    name="display_name"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->display_name); ?>"
                    value="<?php echo esc_html($userData->display_name); ?>"
            >
        </div>
	<?php } ?>

	<?php if (isset($userData->user_url)) { ?>
        <div class="input-area">
            <label for="user-url">Web-Site:</label>
            <input
                    id="user-url"
                    name="user_url"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->user_url); ?>"
                    value="<?php echo esc_html($userData->user_url); ?>"
            >
        </div>
	<?php } ?>

	<?php if (isset($userData->nickname)) { ?>
        <div class="input-area">
            <label for="nickname">Nickname:</label>
            <input
                    id="nickname"
                    name="nickname"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->nickname); ?>"
                    value="<?php echo esc_html($userData->nickname); ?>"
            >
        </div>
	<?php } ?>

	<?php if (isset($userData->first_name)) { ?>
        <div class="input-area">
            <label for="first-name">First Name:</label>
            <input
                    id="first-name"
                    name="first_name"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->first_name); ?>"
                    value="<?php echo esc_html($userData->first_name); ?>"
            >
        </div>
	<?php } ?>

	<?php if (isset($userData->last_name)) { ?>
        <div class="input-area">
            <label for="last-name">Last Name:</label>
            <input
                    id="last-name"
                    name="last_name"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->last_name); ?>"
                    value="<?php echo esc_html($userData->last_name); ?>"
            >
        </div>
	<?php } ?>

	<?php if (isset($userData->description)) { ?>
        <div class="input-area">
            <label for="description">Description:</label>
            <input
                    id="description"
                    name="description"
                    oninput="editingUserData(this)"
                    data-orig="<?php echo esc_html($userData->description); ?>"
                    value="<?php echo esc_html($userData->description); ?>"
        </div>
	<?php } ?>

    <input type="hidden" name="ID" value="<?php echo esc_html($userData->ID); ?>">

    <input id="form-user-button" type="submit" value="Save">

</form>

<style>
    #user-data-form .input-area {
        display: flex;
        flex-direction: column;
        max-width: 550px;
    }
    #user-data-form #form-user-button {
        display: none;
    }
</style>