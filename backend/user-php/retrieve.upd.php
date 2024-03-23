<?php
/* retrieve.upd.php =>  RETRIEVE USER PROFILE DATA PHP */
require_once "../../database/connect.db.php";

session_start();

try {
  if($_SERVER["REQUEST_METHOD"] !== "GET") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);
} catch (PDOException $error) {
  $err_msg = "An unexpected error has occurred.\n"
             . "Please disregard the following error and try again later:\n"
             . $error->getMessage()
             . "\nLine: ".$error->getLine()
             . "\nFile: ".$error->getFile();

    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}