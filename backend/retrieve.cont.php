<?php
/* RETRIEVE CONTENT FROM DATABASE */
require_once('../database/connect.db.php');
define('NUM_TUPLE_REQ','6');
define('REQUIRE_FIELDS','title, description, upload_date, upload_time, cover_path, note_path');

session_start();

try{
  if(isset($_GET['query'])){
    $query = $_GET['query'];
    $sql = "SELECT ".REQUIRE_FIELDS." FROM ".NOTE_DATA_TABLE
         ." WHERE title LIKE :query OR description LIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query',"%$query%",PDO::PARAM_STR);
    $stmt->execute();
  } else {
    $sql = "SELECT ".REQUIRE_FIELDS." FROM ".NOTE_DATA_TABLE
         ." ORDER BY upload_date DESC, upload_time DESC LIMIT ".NUM_TUPLE_REQ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
  }

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  EXIT_WITH_JSON(300, null,  null, $result);

} catch(PDOException $error) {
  /* HANDLE EXCEPTION */
  ExceptionHandler($error);
}