<?php
session_start();
require 'connect.php';
if(isset($_SESSION['latitude']) && isset($_SESSION['longitude'])){
    $userLatitude = $_SESSION['latitude'];
    $userLongitude = $_SESSION['longitude'];
}else{
    echo "Please enter home Address";
    exit();
}
$earthsize = 6371;
$sql = "SELECT Service_id, title, description, service_address, ($earthsize * acos(cos(radians(:userLat)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:userLng)) + sin(radians(:userLat)) * sin(radians(latitude)))) AS distance
        from Community_services
        Having distance < 5
        ORDER by distance ASC";

        try{
            $stmt = $cool -> prepare($sql);
            $stmt -> execute([
                'userLat' => $userLatitude,
                'userLng' => $userLongitude
            ]);
            $fetchnearbyServices = $stmt -> fetchAll(PDO::FETCH_ASSOC);

            if($fetchnearbyServices){
                echo "<h3> Communit Services Withing 5KM: </h3>";
                echo "<ul>";
                foreach($fetchnearbyServices as $service){
                    $formattedDistance = number_format($service['distance'], 2);
                    echo "<li><strong>" . htmlspecialchars($service['title']) . "</strong> (" . $formattedDistance . " KM away)<br>";
                    echo htmlspecialchars($service['service_address']) . "</li><br>";
                } 
                echo "</ul>";
            }else{
                echo "No Community services in your area";
            }
        }catch(PDOException $e){
            echo "Query Error: " . $e -> getMessage();
        }
?>