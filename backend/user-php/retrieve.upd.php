<?php
/* retrieve.upd.php => RETRIEVE USER PROFILE DATA PHP */
require_once('../inc-php/utils.inc.php');

define('LOGIN_PAGE','/pages/auth/login.html');

session_start();

if(isset($_SESSION['user_id'])){
  $userProfileData = [];

  foreach($_SESSION as $key => $value){
    $userProfileData[$key] = $value;
  }

  $data = json_encode($userProfileData);

  EXIT_WITH_JSON(DATA_RESPONSE, null, null, $data);

} else {
  EXIT_WITH_JSON(BAD_RESPONSE, USER_NOT_FOUND, LOGIN_PAGE);
}
exit;