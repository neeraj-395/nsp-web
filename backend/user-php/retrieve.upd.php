<?php
/* retrieve.upd.php => RETRIEVE USER PROFILE DATA PHP */
require_once('../inc-php/utils.inc.php');

session_start();

if(isset($_SESSION['user_id'])){

  $userProfileData = array(
    'name' => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'contact' => $_SESSION['contact'],
    'profession' => $_SESSION['profession'],
    'github' => $_SESSION['github'],
    'twitter' => $_SESSION['twitter'],
    'instagram' => $_SESSION['instagram'],
    'reddit' => $_SESSION['reddit']
  );

  $data = json_encode($userProfileData);

  EXIT_WITH_JSON(DATA_RESPONSE, null, null, $data);

} else {
  EXIT_WITH_JSON(BAD_RESPONSE, USER_NOT_FOUND, LOGIN_PAGE);
}
exit;