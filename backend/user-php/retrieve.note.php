<?php
/* TO RETRIEVE USER NOTE META-DATA TUPLES */
/* UNT => user_note_tuples */
require_once('../../database/connect.db.php');

session_start();

try {
    /* CHECK REQUEST METHOD */
    if ($_SERVER["REQUEST_METHOD"] !== "GET") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);

    /* RETRIEVE USER ID FROM SESSION */
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    /* HANDLE MISSING USER ID */
    if (!$user_id) EXIT_WITH_JSON(BAD_RESPONSE, USER_NOT_FOUND, LOGIN_PAGE);

    /* RETURN CACHED USER NOTE TUPLES IF AVAILABLE */
    if (isset($_SESSION['UNT_JSON'])) {
        EXIT_WITH_JSON(DATA_RESPONSE, null, null, json_encode($_SESSION['UNT_JSON']));
    }

    /* QUERY DATABASE FOR USER NOTE META-DATA TUPLES */
    $sql = "SELECT note_id, title, upload_date, upload_time, note_path 
            FROM note_data WHERE user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $user_note_tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* CACHE USER NOTE TUPLES IN SESSION */
    $_SESSION['UNT_JSON'] = $user_note_tuples;

    /* RETURN USER NOTE TUPLES */
    EXIT_WITH_JSON(DATA_RESPONSE, null, null, json_encode($_SESSION['UNT_JSON']));

} catch (PDOException $error) {
    /* HANDLE EXCEPTIONS */
    ExceptionHandler($error);
}
