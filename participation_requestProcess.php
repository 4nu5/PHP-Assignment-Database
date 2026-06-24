<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require 'connect.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $userId = $_SESSION['user_id'] ?? NULL;
    $serviceId = $_POST['service_id'] ?? null;
    
    if(!$userId || !$serviceId){
        die("<h3 style='color: red;'>Error: Invalid Request Data");
    }

    try{
        $checkSQL = "SELECT request_id FROM participation_Requests where user_id = :user_id and service_id = :service_id";
        $checkStmt = $cool -> prepare($checkSQL);
        $checkStmt -> execute([':user_id' => $userId, ':service_id' => $serviceId]);
    
    if($checkStmt -> rowCount() > 0){
        echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
            echo "<h2 style='color: orange;'>⚠️ Already Requested</h2>";
            echo "<p>You have already submitted a request to join this event.</p>";
            echo "<a href='participation_request.php' style='display: inline-block; padding: 10px 20px; background: #0056b3; color: white; text-decoration: none; border-radius: 5px;'>Go Back</a>";
            echo "</div>";
            exit();
    }
    $insertSQL = "INSERT INTO participation_Requests (user_id, service_id,status,request_date)
                    VALUES (:user_id, :service_id, 'Pending', NOW())";
    $insertStmt = $cool -> prepare($insertSQL);
    $insertStmt -> execute([
        ':user_id' => $userId,
        ':service_id' => $serviceId
    ]);
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
        echo "<h2 style='color: green;'>✅ Request Submitted Successfully!</h2>";
        echo "<p>Your request to participate is now pending approval.</p>";
        echo "<a href='participation_request.php' style='display: inline-block; padding: 10px 20px; background: #0056b3; color: white; text-decoration: none; border-radius: 5px;'>Return to Events Board</a>";
        echo "</div>";
    }catch(PDOException $e){
        die("<h3 style='color: red;'>Database Error: </h3>" . $e->getMessage());
    }
    }else{
        header("Location: participation_request.php");
        exit();
    }
?>