<?php
require_once ('../../database/connect.db.php');
require_once ('../inc-php/auth.inc.php');

/* ERROR MESSAGES */
define('USERNAME_EXIST','This username is already taken.');

/* GOOD RESPONSE MESSAGES AND CONSTANTS */
define('SIGNUP_SUCCESS', 'Congratulations! Your signup was successful. Thank you for joining us!');
define('LOGIN_PAGE','/pages/auth/login.html');


try {
    
    if($_SERVER["REQUEST_METHOD"] !== "POST") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);

    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    if(!isValid($username, $password, $name, $email)) {
        EXIT_WITH_JSON(BAD_RESPONSE, VALIDATION_FAILURE);
    }

    /* PREPARE SELECTION STATEMENT */
    $sql = "SELECT user_id FROM ".USER_DATA_TABLE." WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':username', $username, PDO::PARAM_STR);

    $stmt->execute();

    $userdata = $stmt->fetch(PDO::FETCH_ASSOC);

    if($userdata) EXIT_WITH_JSON(BAD_RESPONSE, USERNAME_EXIST);

    $sql = "INSERT INTO ".USER_DATA_TABLE." (
                username,
                name, 
                password, 
                email
            ) VALUES (:username, :name, :password, :email)";

    /* PREPARE INSERTION STATEMENT */
    $stmt = $pdo->prepare($sql);

    /* BIND VARIABLES TO THE PREPARED STATEMENT AS PARAMETER. */
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hash_password, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);

    // ENCRYPT PASSWORD
    $hash_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt->execute();

    /* GET THE LAST INSERTED USER ID */
    $user_id = (int)$pdo->lastInsertId();

    /* INTIALIZE USER SOCIAL SET TUPLE WITH NULL VALUES */
    $sql = "INSERT INTO ".SOCIAL_SET_TABLE." (user_id) VALUES (:user_id)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();

    EXIT_WITH_JSON(GOOD_RESPONSE, SIGNUP_SUCCESS, LOGIN_PAGE);

} catch (PDOException $error) {
    /* HANDLE EXCEPTIONS */
    ExceptionHandler($error);
}