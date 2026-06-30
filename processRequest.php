<?php

session_start();
require 'connect.php';

if(!isset($_SESSION['username']) || strtolower($_SESSION['role'] !== 'admin')){
    die("Unathourized");
}
$req_id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;
$user_id = $_GET['uid'] ?? NULL;
$title = $_GET['title'] ?? 'an event';

if (!empty($req_id) && !empty($action) && !empty($user_id)){
    try{
        $stmt = $cool -> prepare("UPDATE participation_Requests SET status= :status WHERE request_id = :id");
        $stmt -> execute(['status' => $action, 'id' => $req_id]);

        $message = "Your Request to Join" . htmlspecialchars($title) . "has been" . $action . ".";
        $notifStmt = $cool -> prepare("INSERT INTO notification(user_id, message) VALUES (:uid, :msg)");
        $notifStmt -> execute([
            'uid' => $user_id,
            'msg' => $message
            ]);
    }catch(PDOException $e){
        die("Error Processing Request" . $e -> getMessage());
    }
}
header("Location: adminDashboard.php");
exit();
?>