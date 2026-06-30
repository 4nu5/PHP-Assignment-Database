<?php

session_start();
require 'connect.php';

if(isset($_SESSION['user_id']) && isset($_GET['id'])){
    try{
        $stmt = $cool -> prepare("UPDATE notification SET is_read = 1 WHERE id = :id AND user_id = :uid");
        $stmt -> execute([
            'id' => $_GET['id'],
            'uid' => $_SESSION['user_id']
        ]);
    }catch(PDOException $e){
        die("Error Clearing Notification" . $e-> getMessage());
        }
    }
header("Location: dashboardtest.php");
exit();
?>