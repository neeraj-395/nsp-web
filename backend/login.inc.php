<?php
require_once "../database/connect.php";

/* ERROR MESSAGES AND CONSTANTS */
define('HOME_PAGE', '/index.html');
define('LOGIN_ERROR','Invalid username or password.');

try{
    if($_SERVER["REQUEST_METHOD"] !== "POST") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD, null, $conn);

    $username = (isset($_POST['username'])) ? trim($_POST['username']) : null;
    $password = (isset($_POST['password'])) ? trim($_POST['password']) : null;

    if(!isValid($username, $password)) EXIT_WITH_JSON(BAD_RESPONSE, VALIDATION_FAILURE, null, $conn);

    // Prepare a select statement
    $sql = "SELECT * FROM user_data WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if(!$stmt) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE, null, $conn);

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $username);

    // Attempt to execute the prepared statement
    if(!$stmt->execute()) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE, null, $conn, $stmt);

    $user = $stmt->get_result();

    if($user->num_rows != 1) EXIT_WITH_JSON(BAD_RESPONSE, LOGIN_ERROR, null, $conn, $stmt);
    
    $user_data = $user->fetch_assoc();
    
    if(!password_verify($password, $user_data['password'])) 
        EXIT_WITH_JSON(BAD_RESPONSE, LOGIN_ERROR, null, $conn, $stmt);

    session_start();

    foreach ($user_data as $key => $value) {
        if($key !== "password") $_SESSION[$key] = $value;
    }

    $_SESSION["isLoggedIn"] = true;

    EXIT_WITH_JSON(GOOD_RESPONSE, null, HOME_PAGE, $conn, $stmt);

} catch (Exception $error) {
    $err_msg = "An unexpected error has occurred.\n"
             . "Please disregard the following error and try again later:\n"
             . $error->getMessage();
    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}