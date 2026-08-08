<?php

if( isset( $_POST[ 'Change' ] ) ) {
    // Get input
    $pass_new  = $_POST[ 'password_new' ];
    $pass_conf = $_POST[ 'password_conf' ];

    // SECURE FIX: Verify CAPTCHA server-side response token
    $resp = null;

    if( isset( $_POST[ 'g-recaptcha-response' ] ) ) {
        // Verify token with reCAPTCHA API or internal handler
        $resp = recaptcha_check_answer(
            $_DVWA[ 'recaptcha_private_key' ],
            $_SERVER[ 'REMOTE_ADDR' ],
            $_POST[ 'g-recaptcha-response' ]
        );
    }

    // Check if CAPTCHA response is valid
    if( ( isset( $resp ) && $resp->is_valid ) || ( isset( $_POST[ 'recaptcha_challenge_field' ] ) && check_captcha() ) ) {
        if( $pass_new == $pass_conf ) {
            // Update password securely using prepared statements
            $pass_hash = md5( $pass_new );
            $user = $_SESSION['user'];

            $query  = "UPDATE `users` SET password = ? WHERE user = ?;";
            $stmt   = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
            mysqli_stmt_bind_param($stmt, "ss", $pass_hash, $user);
            mysqli_stmt_execute($stmt);

            echo "<pre>Password changed.</pre>";
        } else {
            echo "<pre>Passwords did not match.</pre>";
        }
    } else {
        echo "<pre>The CAPTCHA was not entered correctly. Please try again.</pre>";
    }
}

?>