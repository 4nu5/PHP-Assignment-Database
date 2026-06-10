<?php

session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connect.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $title =trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $service_address = trim($_POST['service_address'] ?? '');
    $sdgTarget=$_POST['sdgTarget'] ?? '';
    $eventDate=$_POST['eventdate'] ?? '';
    $lat = trim($_POST['latitude'] ?? '' );
    $lng = trim($_POST['longitude'] ?? '' );

    try{
    $sql = "INSERT INTO Community_services(title, description, sdgTarget, eventdate, service_address, latitude, longitude) VALUES (:title, :description, :sdgTarget, :eventdate, :address, :lat, :lng)";

    $insert_st = $cool -> prepare($sql);
    $insert_st -> execute([
        'title' => $title,
        'description' => $description,
        'sdgTarget' => $sdgTarget,
        'eventdate' => $eventDate,
        'service_address' => $service_address,
        'latitude' => $lat,
        'longitude' => $lng
    ]);
        echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
        echo "<h2 style='color: green;'>✅ Service Successfully Created!</h2>";
        echo "<p>Your event <strong>" . htmlspecialchars($title) . "</strong> is now live on the community board.</p>";
        echo "<a href='dashboard.php' style='display: inline-block; padding: 10px 20px; background: #0056b3; color: white; text-decoration: none; border-radius: 5px;'>Return to Dashboard</a>";
        echo "</div>";
    }catch(PDOException $e){
        die("<h3> style='color: red;'>Database Error: </h3>" . $e->getMessage());
    }
}else{
    header("Location: add_service.php");
    exit();
}
?>