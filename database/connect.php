<?php
require_once "../backend/utils.inc.php";

/* ERROR MESSAGES */
define('BAD_RESPONSE', 500);
define('GOOD_RESPONSE', 200);
define('DB_CONNECTION_FAILURE', 'MySQL connection failed:');
define('DB_RECREATION_FAILURE',"Having trouble while creating database.\nPlease try again later");

/* DATABASE CREDENTIALS */
define('HOST_NAME', '');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_NAME', 'nsp_db');

/* ADDITIONAL CONSTANTS */
define('SQL_SCRIPT','create_db.sql');
define('DROP_DB_QUERY','DROP DATABASE IF EXISTS '.DB_NAME);

try {
    $conn = new mysqli(HOST_NAME, DB_USERNAME, DB_PASSWORD);

    if($conn->connect_error)
    EXIT_WITH_JSON(BAD_RESPONSE, DB_CONNECTION_FAILURE.$conn->connect_error, null, $conn);

    // Get SQL script to execute
    $script_path = __DIR__.DIRECTORY_SEPARATOR.SQL_SCRIPT;
    $sql_script = file_get_contents($script_path);

    if ($conn->multi_query($sql_script)) {
        do {
            if ($result = $conn->store_result()) 
                $result->free(); // free unwanted results
        } while ($conn->more_results() && $conn->next_result());
    } else {
        if($conn->query(DROP_DB_QUERY))
            if ($result = $conn->store_result()) $result->free();

        EXIT_WITH_JSON(BAD_RESPONSE, DB_RECREATION_FAILURE, null, $conn);
    }

} catch (Exception $error) {
    $err_msg = "Having trouble connecting to our database server.\n"
             . "Please disregard the following error code and try again later:\n"
             . $error->getMessage();
    EXIT_WITH_JSON(BAD_RESPONSE, $err_msg);
}