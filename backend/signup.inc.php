<?php
require_once "../database/connect.php";
/* ERROR MESSAGES */
define('USERNAME_EXIST','This username is already taken.');

/* GOOD RESPONSE MESSAGES AND CONSTANTS */
define('SIGNUP_SUCCESS', "Congratulations! Your signup was successful.\nThank you for joining us!");
define('LOGIN_PAGE','/pages/auth/login.html');


try {
    if($_SERVER["REQUEST_METHOD"] !== "POST") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD, null, $conn);

    $name = (isset($_POST['name'])) ? trim($_POST['name']) : null;
    $email = (isset($_POST['email'])) ? trim($_POST['email']) : null;
    $username = (isset($_POST['username'])) ? trim($_POST['username']) : null;
    $password = (isset($_POST['password'])) ? trim($_POST['password']) : null;

    if(!isValid($username, $password, $name, $email)) {
        EXIT_WITH_JSON(BAD_RESPONSE, $err_msg, null, $conn);
    }
    // Prepare a select statement for username availablity check.
    $sql = "SELECT user_id FROM user_data WHERE username = ?";
    // Preparing mysql connection and sql statement.
    $stmt = $conn->prepare($sql);

    if(!$stmt) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE, null, $conn);

    $stmt->bind_param("s", $username);

    if(!$stmt->execute()) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE, null, $conn, $stmt);

    $stmt->store_result();

    if($stmt->num_rows() === 1) EXIT_WITH_JSON(500, USERNAME_EXIST, null, $conn, $stmt);
    else $stmt->close(); // closing current statement.

    $sql = "INSERT INTO user_data (
                username,
                name, 
                password, 
                email_id
            ) VALUES (?,?,?,?)";
    // Preparing mysql connection and sql statement.
    $stmt = $conn->prepare($sql);

    if(!$stmt) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE, null, $conn);

    // Bind variables to the prepared statement as parameter.
    $stmt->bind_param("ssss", $username, $name, $hash_password, $email);

    // ENCRYPT PASSWORD
    $hash_password = password_hash($password, PASSWORD_BCRYPT);

    if(!$stmt->execute()) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE, null, $conn, $stmt);

    EXIT_WITH_JSON(GOOD_RESPONSE, SIGNUP_SUCCESS, LOGIN_PAGE, $conn, $stmt);

} catch (Exception $error) {
    $message = "An unexpected error has occurred.\n"
             . "Please disregard the following error and try again later:\n"
             . $error->getMessage();
    EXIT_WITH_JSON(BAD_RESPONSE, $message);
}