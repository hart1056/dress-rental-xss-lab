<?php

// clean up user input so script tags won't run
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// check if password has at least 8 characters with upper, lower, and number
function validatePassword($password) {
    if (strlen($password) < 8) {
        return false;
    }

    $hasLower = false;
    $hasUpper = false;
    $hasNumber = false;

    for ($i = 0; $i < strlen($password); $i++) {
        if (ctype_lower($password[$i])) {
            $hasLower = true;
        } elseif (ctype_upper($password[$i])) {
            $hasUpper = true;
        } elseif (ctype_digit($password[$i])) {
            $hasNumber = true;
        }
    }

    if ($hasLower && $hasUpper && $hasNumber) {
        return true;
    }

    return false;
}

?>
