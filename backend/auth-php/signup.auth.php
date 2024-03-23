<?php
require_once "../../database/connect.db.php";
/* ERROR MESSAGES */
define('USERNAME_EXIST','This username is already taken.');

/* GOOD RESPONSE MESSAGES AND CONSTANTS */
define('SIGNUP_SUCCESS', "Congratulations! Your signup was successful.\nThank you for joining us!");
define('LOGIN_PAGE','/pages/auth/login.html');


try {
    if($_SERVER["REQUEST_METHOD"] !== "POST") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);

    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    if(!isValid($username, $password, $name, $email)) {
        EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
    }
    // Prepare a select statement for username availablity check.
    $sql = "SELECT user_id FROM user_data WHERE username = :username";
    // Preparing mysql connection and sql statement.
    $stmt = $pdo->prepare($sql);

    if(!$stmt) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE);

    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    if(!$stmt->execute()) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE);

    $userdata = $stmt->fetch(PDO::FETCH_ASSOC);

    if($userdata) EXIT_WITH_JSON(500, USERNAME_EXIST);

    $sql = "INSERT INTO user_data (
                username,
                name, 
                password, 
                email_id
            ) VALUES (:username,:name,:password,:email_id)";
    // Preparing mysql connection and sql statement.
    $stmt = $pdo->prepare($sql);

    if(!$stmt) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE);

    // Bind variables to the prepared statement as parameter.
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hash_password, PDO::PARAM_STR);
    $stmt->bindParam(':email_id', $email, PDO::PARAM_STR);

    // ENCRYPT PASSWORD
    $hash_password = password_hash($password, PASSWORD_BCRYPT);

    if(!$stmt->execute()) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE);

    EXIT_WITH_JSON(GOOD_RESPONSE, SIGNUP_SUCCESS, LOGIN_PAGE);

} catch (Exception $error) {
    $err_msg = "An unexpected error has occurred.\n"
             . "Please disregard the following error and try again later:\n"
             . $error->getMessage()
             . "\nLine: ".$error->getLine()
             . "\nFile: ".$error->getFile();

    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}