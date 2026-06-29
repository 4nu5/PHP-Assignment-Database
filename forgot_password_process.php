<?php

require 'connect.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST['email']);

    $stmt = $cool ->prepare("SELECT id FROM users WHERE email = :email");
    $stmt -> execute(['email' => $email]);
    $user = $stmt -> fetch(PDO::FETCH_ASSOC);

    if $user{
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $insert_stmt = $cool -> prepare("INSERT INTO password_reset(email, token, expires_at) VALUES (:email, :token, :expires_at)");
        $insert_stmt -> execute([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expires_at
        ]);
        $reset_link = "http://localhost/AssignmentWebDev/reset_password.php?token=" . token;
    }else{
        
    }
}

?>