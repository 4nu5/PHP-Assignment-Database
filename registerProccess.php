<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'connect.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $user = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $check_st = $cool -> prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $check_st -> execute([$user,$email]);
    $existsUE = $check_st -> fetchColumn();

    if($existsUE > 0){
        die("Error: That Username or Email already exists. <a href = 'register.php'> Try Again </a>");
    }
    $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

    try{
        $insert_st = $cool -> prepare("INSERT INTO users (username, email, passwordHash) VALUES (?, ?, ?)");
        $insert_st ->execute([$user, $email, $hashed_password]);

        $last_id = $cool->lastInsertId();

        echo "<h2>System Telemetry Confirmed!</h2>";
        echo "Registration Successful!<br>";
        echo "Row successfully added to database with User ID number: <strong>" . $last_id . "</strong><br>";
        echo "<a href='login.php'>Proceed to Log In</a>";

    }catch(PDOException $e){
        die("Registration Unsuccesfull: " . $e -> getMessage());
    }
}else{
    header("Location: register.php");
    exit();
}
?>