<?php
require_once "../backend/utils.inc.php";
set_error_handler('HANDLE_EXCEPTIONS'); // for any unexpected error

$servername = "localhost";
$username = "root";
$password = "Nsingh(+)061";
$dbname = "nsp_db";

try {
    $conn = new mysqli($servername, $username, $password);

    if($conn->connect_error){
        $err_msg = "MySQL connection failed: " . $conn->connect_error;
        EXIT_WITH_JSON(500, $err_msg, null, $conn);
    }
} catch (Exception $e) {
    $err_msg = "Having trouble connecting to our database server.\nPlease try again later :-)";
    EXIT_WITH_JSON(500, $err_msg);
}

// creating database if not exist;
$file_path = __DIR__."\\create_db.sql";
$sql_commands = file_get_contents($file_path);

if ($conn->multi_query($sql_commands)) {
    do {
        if ($result = $conn->store_result()) $result->free();
    } while ($conn->more_results() && $conn->next_result());
} else {
    if($conn->query("DROP DATABASE IF EXISTS nsp_db;")) {
        if ($result = $conn->store_result()) $result->free();
        $err_msg = "Couldn't connect to database please try again later.";
        EXIT_WITH_JSON(500, $err_msg, null, $conn);
    } else {
        $err_msg = "Couldn't connect to database please try again later.(remove nsp_db if exist)";
        EXIT_WITH_JSON(500, $err_msg, null, $conn);
    }
}