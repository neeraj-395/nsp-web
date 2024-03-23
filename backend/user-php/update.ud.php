<?php
/* update.ud.php => UPDATE USERDATA PHP */
require_once "../../database/connect.db.php";

/* ERROR MESSAGES */
define('USER_NOT_FOUND',"USER NOT FOUND!!\nIt appears that you are not currently logged in. "
      ."Please log in first and try again.\nWe apologize for any inconvenience :(");
define('EMPTY_REQUEST','Please provide the required information in the form before proceeding');

/* IMPORTANT CONSTANTS AND SUCCESS MESSAGES */
define('LOGIN_PAGE','/pages/auth/login.html');
define('PROFILE_PAGE', '/pages/user/profile.html');
define('UPDATE_SUCCESS','Changes saved successfully.');

session_start();

try {

  if($_SERVER["REQUEST_METHOD"] !== "POST") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);

  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

  if(!$user_id) EXIT_WITH_JSON(BAD_RESPONSE, USER_NOT_FOUND, LOGIN_PAGE);

  $user_data = [
    'name' => isset($_POST['name']) ? trim($_POST['name']) : null,
    'email_id' => isset($_POST['email']) ? trim($_POST['email']) : null,
    'contact' => isset($_POST['contact']) ? trim($_POST['contact']) : null,
    'profession' => isset($_POST['profession']) ? trim($_POST['profession']) : null
  ];
  
  $user_data = array_filter($user_data, function($value){
    return $value != null;
  });

  if(empty($user_data)) EXIT_WITH_JSON(BAD_RESPONSE, EMPTY_REQUEST);

  $data_to_set = implode(', ', array_map(function($key) {
    return "$key = :$key";
  }, array_keys($user_data)));

  $sql = "UPDATE user_data SET $data_to_set WHERE user_id = $user_id";

  $stmt = $pdo->prepare($sql);

  if(!$stmt) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE);

  // Bind parameters
  foreach ($user_data as $key => $value) {
    $stmt->bindParam(":$key", $value, PDO::PARAM_STR);
  }

  if(!$stmt->execute()) EXIT_WITH_JSON(BAD_RESPONSE, EXECUTION_FAILURE);

  foreach ($user_data as $key => $value) {
    $_SESSION[$key] = $value;
  }

  EXIT_WITH_JSON(GOOD_RESPONSE, UPDATE_SUCCESS,  PROFILE_PAGE);
                
} catch (PDOException $error){
  $err_msg = "An unexpected error has occurred.\n"
             . "Please disregard the following error and try again later:\n"
             . $error->getMessage()
             . "\nLine: ".$error->getLine()
             . "\nFile: ".$error->getFile();

  EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}