<?php
define('BAD_RESPONSE', 500);
define('GOOD_RESPONSE', 200);
define('INVALID_METHOD', 'Unexpected Request Method');
define('VALIDATION_FAILURE','Invalid details please try after sometime.');
define('EXECUTION_FAILURE', 'Oops! something went wrong. Please try again later.');

function isValid(?string $username = "skip", ?string $password = "skip", 
                        ?string $name = "skip", ?string $email = "skip") {

    $name_regex = '/^[A-Z][a-z]*( [A-Z][a-z]*)*$/';
    $username_regex = '/^[A-Za-z][A-Za-z0-9_]{7,29}$/';
    $password_regex = '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}/';

    if ($name !== "skip" && ($name === null || empty($name) || 
        !preg_match($name_regex, $name))) return false;

    if ($username !== "skip" && ($username === null || empty($username) ||
        !preg_match($username_regex, $username))) return false;

    if ($email !== "skip" && ($email === null || empty($email) || 
        !filter_var($email, FILTER_VALIDATE_EMAIL))) return false;

    if ($password !== "skip" && ($password === null || empty($password) ||
        !preg_match($password_regex, $password))) return false;

    return true;
}

function EXIT_WITH_JSON(int $status_code, ?string $message = null, string $redirect = null) {
    $response = array(
        'message' => $message,
        'redirect' => $redirect,
        'status' => $status_code
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>