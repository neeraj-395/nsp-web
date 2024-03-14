<?php
require_once "../database/connect.php";
set_error_handler('HANDLE_EXCEPTIONS'); // for any unexpected error

if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $name = (isset($_POST['name'])) ? trim($_POST['name']) : null;
    $username = (isset($_POST['username'])) ? trim($_POST['username']) : null;
    $email = (isset($_POST['email'])) ? trim($_POST['email']) : null;
    $password = (isset($_POST['password'])) ? trim($_POST['password']) : null;

    // Checking Validity. Although it already checked by the javascript logic
    if(!isValid($username, $password, $name, $email)) {
        $err_msg = "Did you bypass the pattern rules of this signup page?";
        EXIT_WITH_JSON(500, $err_msg, null, $conn);
    }

    // Prepare a select statement for username availablity check.
    $sql = "SELECT user_id FROM user_data WHERE username = ?";

    // Preparing mysql connection and sql statement.
    if($stmt = $conn->prepare($sql)) {
        // Binding username parameter with sql statement.
        $stmt->bind_param("s", $username);

        // Trying to execute the statement
        if($stmt->execute()){
            // Storing the result.
            $stmt->store_result();

            // If username exist end the script.
            if($stmt->num_rows() === 1) {
                $err_msg = "This username is already taken.";
                EXIT_WITH_JSON(500, $err_msg, null, $conn, $stmt);
            } else {  
                $stmt->close();// Close statement.
            }
        } else {
            $err_msg = "Oops! something went wrong. Please try again later.";
            EXIT_WITH_JSON(500, $err_msg, null, $conn, $stmt);
        }

    } else {
        $err_msg = "Oops! something went wrong. Please try again later.";
        EXIT_WITH_JSON(500, $err_msg, null, $conn);
    }
    

    // Prepare an insert statement.
    $sql = "INSERT INTO user_data (
        username,
        name, 
        password, 
        email_id
    ) VALUES (?,?,?,?)";

    if($stmt = $conn->prepare($sql)){
        // Bind variables to the prepared statement as parameter.
        $stmt->bind_param("ssss", $username, $name, $hash_password, $email);

        // Hashing password.
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        // Attempt to execute the prepared statement.
        if($stmt->execute()){
            $msg = "Congratulations! Your signup was successful.\nThank you for joining us!";
            $redirect_to = "/auth/html/login.html";
            EXIT_WITH_JSON(200, $msg, $redirect_to, $conn, $stmt);
        } else {
            $err_msg = "Oops! something went wrong. Please try again later.";
            EXIT_WITH_JSON(500, $err_msg, null, $conn, $stmt);
        }

    } else {
        $err_msg = "Oops! something went wrong. Please try again later.";
        EXIT_WITH_JSON(500, $err_msg, null, $conn);
    }
} else exit(500);