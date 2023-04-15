<?php
/*
Plugin Name: Pluggable function plugin
Description: This plugin is used to test the pluggable function feature of WordPress.
Plugin URI: https://github.com/sucrexu/pluggable-functions
Author: Sucre Xu
Author uri: https://github.com/sucrexu
Version: 1.0
*/
function wp_logout() {
    $user_id = get_current_user_id();

    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_set_current_user( 0 );
    myplugin_custom_logout();
    /**
     * Fires after a user is logged out.
     *
     * @since 1.5.0
     * @since 5.5.0 Added the `$user_id` parameter.
     *
     * @param int $user_id ID of the user that was logged out.
     */
    do_action( 'wp_logout', $user_id );
}

//customize logout function
function myplugin_custom_logout() {
}

// validate phone number
function myplugin_is_phone_number($phone_number) {
    //check if empty
    if(empty($phone_number)) return false;

    //check format
    if(!preg_match('/^\(?[0-9]{3})\)?([.-]?)([0-9]{3})([.-]?)([0-9]{4})$/', $phone_number)) return false;

    // all good!
    return true;
}

// display form
function myplugin_form_phone_number() {
    ?>
    <form action="" method="post">
        <p><label for="phone">Phone Number</label></p>
        <p><input type="tel" name="myplugin_phone_number" id="phone" /></p>
        <p><input type="submit" value="Submit" /></p>
    </form>
    <?php
}

// process submitted form
function myplugin_process_phone_number() {
    if(isset($_POST['myplugin-phone-number'])){
        $phone_number = $_POST['myplugin-phone-number'];
        if (myplugin_is_phone_number($phone_number)) {
            echo '<p> Thank you for your phone number: ' . $phone_number . '</p>';
        }else{
            echo '<p> Please enter a valid phone number </p>';
        }
    }
}

$str = sanitize_text_field($str);
$email = sanitize_email($email);
$user = sanitize_user($user);
filter_var($var, FILTER_VALIDATE_BOOLEAN);
filter_var($var, FILTER_VALIDATE_INT);
filter_var($var, FILTER_VALIDATE_IP);
filter_var($var, FILTER_VALIDATE_MAC);
filter_var($var, FILTER_VALIDATE_URL);
filter_var($var, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[a-z]+$/')));

// explain what is nonce? what is nonce? codewhisper tell me!
// https://codewhisper.com/understanding-wp-nonce/
wp_nonce_field();