<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'../backend/inc-php/utils.inc.php';

/* ERROR MESSAGES */
define('DB_CONNECTION_FAILURE', 'MySQL connection failed:');
define('DB_RECREATION_FAILURE',"Having trouble while creating database.\nPlease try again later");

/* DATABASE CREDENTIALS */
define('HOST_NAME', ''); // Setit by your self
define('DB_USERNAME', '');// Set it by your self
define('DB_PASSWORD', ''); // Set it by your self
define('DB_NAME', 'nsp_db'); // Set it by your self

/* ADDITIONAL CONSTANTS */
define('SQL_SCRIPT','create.db.sql');
define('DROP_DB_QUERY','DROP DATABASE IF EXISTS '.DB_NAME);

try {
    $pdo = new PDO('mysql:host='.HOST_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get SQL script to execute
    $script_path = __DIR__.DIRECTORY_SEPARATOR.SQL_SCRIPT;
    $sql_script = file_get_contents($script_path);

    if (!$pdo->exec($sql_script)) {
        $pdo->exec(DROP_DB_QUERY);
        EXIT_WITH_JSON(BAD_RESPONSE, DB_RECREATION_FAILURE, null, $conn);
    }

} catch (PDOException $error) {
    $err_msg = "Having trouble connecting to our database server.\n"
             . "Please disregard the following error code and try again later:\n"
             . "Error: ".$error->getMessage();
    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}