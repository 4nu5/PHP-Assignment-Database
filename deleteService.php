<?php

session_start();
require 'connect.php';

if(!isset($_SESSION['username']) || strtolower($_SESSION['role'] !== 'admin')){
    die("Unauthorized");
}

$service_id = $_GET['id'] ?? NULL;
if($service_id){
    try{
        $stmt = $cool -> prepare("DELETE FROM Community_services WHERE Service_id = :id");
        $stmt -> execute([
            'id' => $service_id
        ]);
    }catch(PDOException $e){
        die("Database Error: Cannot Delete This Service" . $e->getMessage());
    }
}
header("Location:adminDashboard.php");
exit();

?>