<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connect.php';

echo "<h3>Checking the Session Vault:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
die();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $title =trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $service_address = trim($_POST['service_address'] ?? '');
    $sdgTarget=trim($_POST['sdgTarget'] ?? '');
    $eventDate=trim($_POST['eventDate'] ?? '');
    $lat = trim($_POST['latitude'] ?? '' );
    $lng = trim($_POST['longitude'] ?? '' );

    if($lat !== NULL && $lng !== NULL){
        $lat=round((float)$lat,8);
        $lng=round((float)$lng,8);
    }

    try{
    $sql = "INSERT INTO Community_services(title, description, sdgTarget, eventDate, createdBy, service_address, latitude, longitude) VALUES (:title, :description, :sdgTarget, :eventDate, :createdBy, :service_address, :lat, :lng)";

    $insert_st = $cool -> prepare($sql);
    $insert_st -> execute([
        'title' => $title,
        'description' => $description,
        'sdgTarget' => $sdgTarget,
        'eventDate' => $eventDate,
        'createdBy' => $_SESSION['user_id'],
        'service_address' => $service_address,
        'lat' => $lat,
        'lng' => $lng
    ]);
        echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
        echo "<h2 style='color: green;'>✅ Service Successfully Created!</h2>";
        echo "<p>Your event <strong>" . htmlspecialchars($title) . "</strong> is now live on the community board.</p>";
        echo "<a href='dashboard.php' style='display: inline-block; padding: 10px 20px; background: #0056b3; color: white; text-decoration: none; border-radius: 5px;'>Return to Dashboard</a>";
        echo "</div>";
    }catch(PDOException $e){
        die("<h3 style='color: red;'>Database Error: </h3>" . $e->getMessage());
    }
}else{
    header("Location: add_service.php");
    exit();
}
?>