<?php
session_start();

header('Content-Type: application/json');
if(isset($_SESSION['user_id']))
    echo json_encode(array(
        'status' => 300,
        'data' => array(
            'isLoggedIn' => true,
            'name' => $_SESSION['name']
        )
    ));
else 
    echo json_encode(array(
        'status' => 300,
        'data' => array('isLoggedIn' => false)
    ));
    
exit;