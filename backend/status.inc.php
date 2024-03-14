<?php
session_start();

header('Content-Type: application/json');
if(isset($_SESSION['isLoggedIn']))
    echo json_encode(array('isLoggedIn' => $_SESSION['isLoggedIn']));
else 
    echo json_encode(array('isLoggedIn' => false));
exit;