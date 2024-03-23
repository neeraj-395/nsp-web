<?php
require_once "../../database/connect.db.php";

/* ERROR MESSAGES AND CONSTANTS */
define('HOME_PAGE', '/index.html');
define('LOGIN_ERROR','Invalid username or password.');

try{
    if($_SERVER["REQUEST_METHOD"] !== "POST") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);

    $username = (isset($_POST['username'])) ? trim($_POST['username']) : null;
    $password = (isset($_POST['password'])) ? trim($_POST['password']) : null;

    if(!isValid($username, $password)) EXIT_WITH_JSON(BAD_RESPONSE, VALIDATION_FAILURE);

    // Prepare a select statement
    $sql = "SELECT * FROM user_data WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    if(!$stmt) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE);

    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Attempt to execute the prepared statement
    if(!$stmt->execute()) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE);

    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user_data) EXIT_WITH_JSON(BAD_RESPONSE, LOGIN_ERROR);

    if(!password_verify($password, $user_data['password'])) 
        EXIT_WITH_JSON(BAD_RESPONSE, LOGIN_ERROR);

    session_start();
    
    $_SESSION['user_id'] = $user_data['user_id'];
    $_SESSION['name'] = $user_data['name'];
    $_SESSION["isLoggedIn"] = true;

    EXIT_WITH_JSON(GOOD_RESPONSE, null, HOME_PAGE);

} catch (Exception $error) {
    $err_msg = "An unexpected error has occurred.\n"
             . "Please disregard the following error and try again later:\n"
             . $error->getMessage()
             . "\nLine: ".$error->getLine()
             . "\nFile: ".$error->getFile();

    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}