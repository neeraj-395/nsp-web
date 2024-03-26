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

    /* PREPARE SELECT STATEMENT */
    $sql = "SELECT user_id, username, password FROM ".USER_DATA_TABLE." WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    $stmt->execute();

    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user_data) EXIT_WITH_JSON(BAD_RESPONSE, LOGIN_ERROR);

    if(!password_verify($password, $user_data['password'])) 
        EXIT_WITH_JSON(BAD_RESPONSE, LOGIN_ERROR);

    /* RETRIEVE USER PROFILE DATA AND STORE IT INTO THE SESSION */
    $user_id = $user_data['user_id'];

    $sql = "SELECT *FROM ".USER_PROFILE_VIEW." WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);

    $stmt->execute();

    $user_profile_data = $stmt->fetch(PDO::FETCH_ASSOC);

    /* START SESSION STORE USER PROFILE DATA */
    session_start();

    foreach($user_profile_data as $key => $value) {
        $_SESSION[$key] = $value;
    }

    EXIT_WITH_JSON(GOOD_RESPONSE, null, HOME_PAGE);

} catch (PDOException $error) {
    $err_msg = "Our backend is currently experiencing issues.\n" 
             . "Please try again later. Thank You ['.']\n"
             . "Error Message: " . $error->getMessage() . "\n"
             . "Line: " . $error->getLine() . "\n"
             . "File: " . $error->getFile();
    
    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}