<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'../backend/inc-php/utils.inc.php';

/* DATABASE CREDENTIALS */
define('HOST_NAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Nsingh(+)061');
define('DB_NAME', 'nsp_db');

/* ADDITIONAL CONSTANTS */
define('USER_DATA_TABLE', 'user_data');
define('UD_LOGIN_INDEX', 'login_inx');
define('SOCIAL_SET_TABLE', 'user_social_set');
define('USER_PROFILE_VIEW', 'user_profile');
define('CREATE_DB_SCRIPT',  'create.db.sql');
define('VIEW_TABLE_SCRIPT', 'up_view.db.sql');
define('INDEX_VIEW_SCRIPT', 'inx_view.db.sql');

try {
    /* CONNECT TO DATABASE SERVER */
    $pdo = new PDO('mysql:host='.HOST_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* SCRIPT TO CREATE A NEW DATABASE AND RELATED TABLES IF NOT EXISTING */
    $script_path = __DIR__.DIRECTORY_SEPARATOR.CREATE_DB_SCRIPT;
    $sql_script = file_get_contents($script_path);
    $pdo->exec($sql_script);
    
    /* CHECK WHETHER OR NOT UD_LOGIN_INDEX EXIST */
    $sql = "SHOW INDEX FROM ".USER_DATA_TABLE." WHERE Key_name = :indexName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':indexName', UD_LOGIN_INDEX, PDO::PARAM_STR);
    $stmt->execute();
    $inx_result = $stmt->fetch(PDO::FETCH_ASSOC);

    /* IF UD_LOGIN_INDEX DOES NOT EXIST, CREATE ONE */
    if(!$inx_result) {
        $sql = "CREATE INDEX " . UD_LOGIN_INDEX . " ON " . USER_DATA_TABLE
             . " (user_id, username, password)";
        $pdo->exec($sql);
    }

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
    $err_msg = "Our backend is currently experiencing issues.\n" 
             . "Please try again later. Thank You ['.']\n"
             . "Error Message: " . $error->getMessage() . "\n"
             . "Line: " . $error->getLine() . "\n"
             . "File: " . $error->getFile();
    
    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}