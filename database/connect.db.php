<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'../backend/inc-php/utils.inc.php';

/* DATABASE CREDENTIALS */
define('HOST_NAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Nsingh(+)061');
define('DB_NAME', 'nsp_db');

/* ADDITIONAL CONSTANTS */
define('USER_DATA_TABLE', 'user_data');
define('NOTE_DATA_TABLE', 'note_data');
define('SOCIAL_SET_TABLE', 'user_social_set');
define('USER_PROFILE_VIEW', 'user_profile');
define('CREATE_DB_SCRIPT',  'create.db.sql');
define('VIEW_TABLE_SCRIPT', 'up_view.db.sql');

try {
    /* CONNECT TO DATABASE SERVER */
    $pdo = new PDO('mysql:host='.HOST_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* SCRIPT TO CREATE A NEW DATABASE AND RELATED TABLES IF NOT EXISTING */
    $script_path = __DIR__.DIRECTORY_SEPARATOR.CREATE_DB_SCRIPT;
    $sql_script = file_get_contents($script_path);
    $pdo->exec($sql_script);

    /* CHECK WETHER OR NOT USER PROFILE VIEW EXIST */
    $sql = "SELECT * FROM information_schema.views WHERE table_schema = :dbname AND table_name = :viewName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':dbname', DB_NAME, PDO::PARAM_STR);
    $stmt->bindValue(':viewName', USER_PROFILE_VIEW, PDO::PARAM_STR);
    $stmt->execute();
    $view_result = $stmt->fetch(PDO::FETCH_ASSOC);

    /* IF USER_PROFILE_VIEW DOES NOT EXIST, CREATE ONE */
    if(!$view_result) {
        $script_path = __DIR__.DIRECTORY_SEPARATOR.VIEW_TABLE_SCRIPT;
        $sql_script = file_get_contents($script_path);
        $pdo->exec($sql_script);
    }

} catch (PDOException $error) {
    /* HANDLE EXCEPTIONS */
    ExceptionHandler($error);
}