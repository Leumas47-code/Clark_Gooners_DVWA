<?php

if( isset( $_POST[ 'Change' ] ) ) {
    // SECURE FIX: Enforce reCAPTCHA validation server-side
    $resp = recaptcha_check_answer(
        $_DVWA[ 'recaptcha_private_key' ],
        $_SERVER[ 'REMOTE_ADDR' ],
        $_POST[ 'recaptcha_challenge_field' ],
        $_POST[ 'recaptcha_response_field' ]
    );

    if( $resp->is_valid ) {
        // Validate and update password only if CAPTCHA check succeeded
        $pass_new = $_POST[ 'password_new' ];
        $pass_conf = $_POST[ 'password_conf' ];

        if( $pass_new === $pass_conf ) {
            $pass_new = ((is_null($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $pass_new ) : ((&$___mysqli_ston) ? mysqli_real_escape_string($___mysqli_ston, $pass_new ) : false));
            $pass_new = md5( $pass_new );

            $query  = "UPDATE users SET password = '$pass_new' WHERE user = '" . dvwaCurrentUser() . "';";
            $result = mysqli_query($GLOBALS["___mysqli_ston"],  $query );

            echo "<pre>Password changed.</pre>";
        } else {
            echo "<pre>Passwords did not match.</pre>";
        }
    } else {
        echo "<pre>reCAPTCHA was incorrect.</pre>";
    }
}

?>