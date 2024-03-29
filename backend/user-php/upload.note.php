<?php
require_once('../../database/connect.db.php');
require_once('../inc-php/upload.inc.php');

/* ERROR MESSAGES */
define('INVALID_POST_DATA', 'Invalid inputs and files (message from backend).');
define('INVALID_FILE_EXTENSION', 'Invalid file type. Please ensure you are using the correct file extension.');
define('NOTE_DIR_FAILURE', 'Error: Failed to locate or create note directory');
define('COVER_DIR_FAILURE', 'Error: Failed to locate or create cover directory');
define('FILE_MOVE_FAILED', 'Error: Failed to move uploaded file to uploads directory.');

/* SUCCESS MESSAGES AND CONSTANTS */
define('NOTE_UPLOAD_SUCCESS', 'Your note has been successfully uploaded!');
define('ABS_NOTE_DIR_PATH', __DIR__.DIRECTORY_SEPARATOR.'../../uploads/notes/');
define('ABS_COVER_DIR_PATH', __DIR__.DIRECTORY_SEPARATOR.'../../uploads/covers/');
define('REL_NOTE_DIR_PATH', '/uploads/notes/');
define('REL_COVER_DIR_PATH', '/uploads/covers/');
define('NOTE_TYPE', 'application/pdf');
define('NOTE_FILE_EXTENSION', '.pdf');
define('COVER_FILE_EXTENSION', '.png');
define('IMG_TYPE', 'image/png');

/* START THE SESSION VARIABLE TO UPLOAD NOTE WITH USER_ID */
session_start();

try {

    if($_SERVER['REQUEST_METHOD'] !== 'POST') EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);

    /* CHECK IF USER IS LOGGED IN BY VERIFYING THE PRESENCE OF 'USER_ID' IN SESSION. */
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    /* IF USER IS NOT LOGGED IN, EXIT WITH JSON RESPONSE INDICATING 
            BAD RESPONSE AND REDIRECT TO LOGIN PAGE. */
    if(!$user_id) EXIT_WITH_JSON(BAD_RESPONSE, USER_NOT_FOUND, LOGIN_PAGE);

    $title = (isset($_POST['title'])) ? trim($_POST['title']) : null;
    $desc = (isset($_POST['desc'])) ? trim($_POST['desc']) : null;
    $note = (isset($_FILES['note-file'])) ? $_FILES['note-file'] : null;
    $cover = (isset($_FILES['cover-img'])) ? $_FILES['cover-img'] : null;

    if(!$title || !$desc || !$note || !$cover) EXIT_WITH_JSON(BAD_RESPONSE, INVALID_POST_DATA);

    foreach ([$note, $cover] as $key => $file) {
      if ($file['error'] !== UPLOAD_ERR_OK) {
          $errMsg = UploadErrHandler($file['error']);
          EXIT_WITH_JSON(BAD_RESPONSE, $errMsg);
      }
      switch($file['type']){
        case NOTE_TYPE: break;
        case IMG_TYPE: break;
        default: 
        EXIT_WITH_JSON(BAD_RESPONSE, INVALID_FILE_EXTENSION);
        break;
      }
    }

    /*CHECK IF THE UPLOAD DIRECTORY EXISTS AND IS WRITABLE, IF NOT CREATE ONE OR MAKE IT WRITABLE*/
    if(!checkDirectory(ABS_NOTE_DIR_PATH)){
        EXIT_WITH_JSON(BAD_RESPONSE, NOTE_DIR_FAILURE);
    }

    if(!checkDirectory(ABS_COVER_DIR_PATH)){
        EXIT_WITH_JSON(BAD_RESPONSE, COVER_DIR_FAILURE);
    }

    /* GENERATE A UNIQUE FILENAME TO PREVENT OVERWRITING EXISTING FILES */
    $uniqueNoteName = uniqid() . NOTE_FILE_EXTENSION;
    $uniqueCoverName = uniqid() . COVER_FILE_EXTENSION;

    /* SPECIFY THE FULL PATH TO THE FILE ON THE SERVER */
    $absNotePath = ABS_NOTE_DIR_PATH . $uniqueNoteName; // ABSOLUTE NOTE PATH
    $absCoverPath = ABS_COVER_DIR_PATH . $uniqueCoverName; // ABSOLUTE COVER PATH

    if (!move_uploaded_file($note['tmp_name'], $absNotePath)) {
        EXIT_WITH_JSON(BAD_RESPONSE, FILE_MOVE_FAILED);
    }

    if (!move_uploaded_file($cover['tmp_name'], $absCoverPath)) {
        EXIT_WITH_JSON(BAD_RESPONSE, FILE_MOVE_FAILED);
    }

    /* UPLOAD FILES META-DATA INTO THE DATABASE */
    $sql = "INSERT INTO ". NOTE_DATA_TABLE . " (
                user_id,
                title,
                description,
                note_path,
                cover_path
            ) VALUES (:user_id, :title, :description, :note_path, :cover_path)";
    
    /* PREPARE INSERTION STATEMENT */
    $stmt = $pdo->prepare($sql);

    /* BIND VARIABLES TO THE PREPARED STATEMENT AS PARAMETER. */
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':description', $desc, PDO::PARAM_STR);
    $stmt->bindParam(':note_path', $relNotePath, PDO::PARAM_STR);
    $stmt->bindParam(':cover_path', $relCoverPath, PDO::PARAM_STR);

    /* SET RELATIVE PATH TO STORE INTO THE DATABASE */
    $relNotePath = REL_NOTE_DIR_PATH . $uniqueNoteName; // RELATIVE NOTE PATH
    $relCoverPath = REL_COVER_DIR_PATH . $uniqueCoverName; // RELATIVE COVER PATH

    $stmt->execute();
    
    EXIT_WITH_JSON(GOOD_RESPONSE, NOTE_UPLOAD_SUCCESS);

} catch (PDOException $error){
    /* HANDLE EXCEPTIONS */
    ExceptionHandler($error);
}