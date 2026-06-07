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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['latitude'] = $user['latitude'];
                $_SESSION['longitude'] = $user['longitude'];

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