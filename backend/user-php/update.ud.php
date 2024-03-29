<?php
/* update.ud.php => UPDATE USERDATA PHP */
require_once ('../../database/connect.db.php');

/* ERROR MESSAGES */
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
    'email' => isset($_POST['email']) ? trim($_POST['email']) : null,
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

  $sql = "UPDATE ".USER_DATA_TABLE." SET $data_to_set WHERE user_id = :user_id";

  $stmt = $pdo->prepare($sql);

  $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
  
  foreach ($user_data as $key => $value) { 
    $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
  }

  $stmt->execute();

  /* UPDATE VALUES IN SESSION VARIABLE */
  foreach ($user_data as $key => $value) {
    $_SESSION[$key] = $value;
  }

  EXIT_WITH_JSON(GOOD_RESPONSE, UPDATE_SUCCESS,  PROFILE_PAGE);
                
} catch (PDOException $error) {
  /* HANDLE EXCEPTIONS */
  ExceptionHandler($error);
}