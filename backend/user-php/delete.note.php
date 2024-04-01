<?php
/* TO DELETE USER NOTE META-DATA TUPLE FROM THE DATABASE */
require_once('../../database/connect.db.php');
require_once('../inc-php/upload.inc.php');

/* ERROR MESSAGES */
define('UNKNOWN_NOTE_ID', 'Error: No data was provided in the request');
define('FILE_DELETE_ERR', 'Error: Unable to delete the file.');
define('UPLOAD_BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR . '../..');

session_start();

try {
	/* CHECK REQUEST METHOD */
	if ($_SERVER["REQUEST_METHOD"] !== "GET") EXIT_WITH_JSON(BAD_RESPONSE, INVALID_METHOD);

	/* RETRIEVE NOTE ID FROM GET PARAMETERS */
	$note_id = isset($_GET['note_id']) ? $_GET['note_id'] : null;

	/* HANDLE MISSING NOTE ID */
	if (!$note_id) EXIT_WITH_JSON(BAD_RESPONSE, UNKNOWN_NOTE_ID);

	/* SELECT NOTE PATH AND COVER PATH FROM DATABASE */
	$sql = "SELECT note_path, cover_path FROM " . NOTE_DATA_TABLE . " WHERE note_id = :note_id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':note_id', $note_id, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	/* DELETE FILES FROM SERVER */
	if (!fileDelete(UPLOAD_BASE_PATH . $result['note_path'])) {
		EXIT_WITH_JSON(BAD_RESPONSE, FILE_DELETE_ERR);
	}

	if (!fileDelete(UPLOAD_BASE_PATH . $result['cover_path'])) {
		EXIT_WITH_JSON(BAD_RESPONSE, FILE_DELETE_ERR);
	}

	/* DELETE NOTE META-DATA TUPLE FROM DATABASE */
	$sql = "DELETE FROM " . NOTE_DATA_TABLE . " WHERE note_id = :note_id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':note_id', $note_id, PDO::PARAM_INT);
	$stmt->execute();

	/* CLEAR SESSION DATA */
	unset($_SESSION['UNT_JSON']);

	/* RETURN SUCCESS MESSAGE */
	EXIT_WITH_JSON(GOOD_RESPONSE, "The note with id $note_id has been successfully deleted.");
} catch (PDOException $error) {
	/* HANDLE EXCEPTIONS */
	ExceptionHandler($error);
}
