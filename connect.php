<?php

$host = 'localhost' ;
$dbname = 'AssignmentWebDev';
$username = 'root';
$password = '';

try{
    $cool = new PDO("mysql: host=$host; dbname=$dbname; charset=utf8mb4", $username, $password);
    $cool-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
}catch(PDOException $e){
    die("Database Connection Failed: " . $e -> getMessage()); 
}

?>