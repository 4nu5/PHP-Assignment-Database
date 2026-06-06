<?php
session_start();
require 'connect.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // echo "Raw POST Data received from form: <br>";
    // var_dump($_POST);
    // die();
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(!empty($username) && !empty($password)){
        try{
            $stmt = $cool->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt -> execute(['username' => $username]);
            $user = $stmt -> fetch(PDO::FETCH_ASSOC);

// --- TEMPORARY DEBUGGING BLOCK ---
// echo "Typing this password: " . $password . "<br>";
// echo "Database returned this array: <br>";
// echo "Username: " . $username . "<br>"; 
// print_r($user);
// die(); // Kills the script so you can read the output

            if($user && password_verify($password,$user['passwordHash'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                echo "login successfull";
                header("Location: dashboard.php");
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