<?php
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] === true){
    EXIT_WITH_JSON(300, "", "/index.html");
    exit;
}

require_once "../database/connect.php"; 
set_error_handler('HANDLE_EXCEPTIONS'); // for any unexpected error

if($_SERVER["REQUEST_METHOD"] === "POST")
{
    $username = (isset($_POST['username'])) ? trim($_POST['username']) : null;
    $password = (isset($_POST['password'])) ? trim($_POST['password']) : null;

    // Checking Validity. Although it already checked by the javascript logic
    if(!isValid($username, $password)) {
        $err_msg = "Did you bypass the pattern rules of this login page?";
        EXIT_WITH_JSON(500, $err_msg, null, $conn);
    }

    // Prepare a select statement
    $sql = "SELECT user_id, username, name, password FROM user_data WHERE username = ?";

    if($stmt = $conn->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $username);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Store result
            $stmt->store_result();
            
            // Check if username exists, if yes then verify password
            if($stmt->num_rows == 1){                    
                // Bind result variables
                $stmt->bind_result($id, $username, $name, $hashed_password);

                if($stmt->fetch()){
                    if(password_verify($password, $hashed_password)){
                        // Store data in session variables
                        $_SESSION["user_id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["name"] = $name;                           
                        $_SESSION["isLoggedIn"] = true;
                        
                        EXIT_WITH_JSON(300, "", "/index.html", $conn, $stmt);
                    } else  {
                        // Password is not valid, display a generic error message
                        $login_err = "Invalid username or password.";
                        EXIT_WITH_JSON(500, $login_err, null, $conn, $stmt);
                    }
                }
            } else{
                // Username doesn't exist, display a generic error message
                $login_err = "Invalid username or password.";
                EXIT_WITH_JSON(500, $login_err, null, $conn, $stmt);
            }
        } else{
            $err_msg = "Oops! something went wrong. Please try again later.\n";
            EXIT_WITH_JSON(500, $err_msg, null, $conn, $stmt);
        }
    } else {
        $err_msg = "Oops! something went wrong. Please try again later.\n";
        EXIT_WITH_JSON(500, $err_msg, null, $conn);
    }
} else exit(500);