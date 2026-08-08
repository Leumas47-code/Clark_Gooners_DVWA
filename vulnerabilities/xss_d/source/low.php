<?php

// SECURE FIX: Whitelist valid language options to prevent DOM-based XSS
$allowed_languages = array('English', 'French', 'Spanish', 'German');

if (!array_key_exists("default", $_GET) || $_GET['default'] == NULL) {
    header("location: ?default=English");
    exit;
} else {
    $default = $_GET['default'];
    
    // If the provided parameter is not in the allowed list, default back to English
    if (!in_array($default, $allowed_languages, true)) {
        header("location: ?default=English");
        exit;
    }
}

?>
