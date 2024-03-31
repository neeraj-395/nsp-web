<?php 
/* retrieve.note.php => TO RETRIEVE NOTE DATA */
require_once('../../database/connect.db.php');
require_once('../inc-php/upload.inc.php');

try {

  if($_SERVER["REQUEST_METHOD"] !== "GET") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);

  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

  if(!$user_id) EXIT_WITH_JSON(BAD_RESPONSE, USER_NOT_FOUND, LOGIN_PAGE);

} catch (PDOException $error){
  /* HANDLE EXCEPTIONS */
  ExceptionHandler($error);
}