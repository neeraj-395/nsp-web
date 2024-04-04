<?php
define('GOOD_RESPONSE', 200);
define('DATA_RESPONSE', 300);
define('BAD_RESPONSE', 500);
define('HOME_PAGE', '/index.html');
define('LOGIN_PAGE','/pages/auth/login.html');
define('PROFILE_PAGE', '/pages/user/profile.html');
define('INVALID_METHOD', 'Unexpected Request Method');
define('VALIDATION_FAILURE','Given inputs are invalid according to our backend.');
define('USER_NOT_FOUND', "User Not Found!\nit appears that you are not currently logged in. "
      ."Please log in first and try again.\nWe apologize for any inconvenience [' . ']");

function EXIT_WITH_JSON(int $status_code, ?string $message = null,
                        ?string $redirect = null, ?array $data = null) {
    $response = array(
        'message' => $message,
        'redirect' => $redirect,
        'status' => $status_code,
        'data' => $data
    );
    header('content-type: application/json');
    echo json_encode($response);
    exit;
}

function ExceptionHandler($error){
    $err_msg = "Our backend is currently experiencing issues.\n" 
             . "Please try again later. Thank You [' . ']\n"
             . "Error Message: " . $error->getMessage() . "\n"
             . "Line: " . $error->getLine() . "\n"
             . "File: " . $error->getFile();
  
    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}