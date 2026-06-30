<?php
session_start();
require 'connect.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(!empty($username) && !empty($password)){
        try{
            $stmt = $cool->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt -> execute(['username' => $username]);
            $user = $stmt -> fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password,$user['passwordHash'])){
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                 $dbRole = $user['role'] ?? $user['Role'] ?? 'none';
                 $_SESSION['role'] = trim(strtolower($dbRole));
                $_SESSION['latitude'] = $user['latitude'];
                $_SESSION['longitude'] = $user['longitude'];

                
    echo "<h3>Debug Session Roles:</h3>";
    echo "Raw DB Role Entry: Data Type Summary below:<br><pre>";
    print_r($user);
    echo "</pre>";
    echo "Assigned Session Role: <strong>" . $_SESSION['role'] . "</strong><br>";
    echo "<a href='dashboardtest.php'>Proceed to Dashboard</a>";
    exit();
    
    
                echo "login successfull";
                header("Location: dashboardtest.php");
                exit();
            }else{
                echo "Invalid Authorization";
            }
        }catch(PDOException $e){
            die("Authentication Error Encountered: " . $e -> getMessage());
        }
    }
}

?>