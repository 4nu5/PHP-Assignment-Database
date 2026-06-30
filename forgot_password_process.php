<?php

require 'connect.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST['email']);

    $stmt = $cool ->prepare("SELECT user_id FROM users WHERE email = :email");
    $stmt -> execute(['email' => $email]);
    $user = $stmt -> fetch(PDO::FETCH_ASSOC);

    if ($user){
        $token = bin2hex(random_bytes(32));
        $insert_stmt = $cool -> prepare("INSERT INTO reset_password(email, token, expires_at) VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
        $insert_stmt -> execute([
            'email' => $email,
            'token' => $token,
        ]);
        $folderPath = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        $reset_link = $folderPath . "/reset_password.php?token=" . $token;

        echo "<p style='color: green; text-align: center;'>A password reset link has been simulated: <a href='$reset_link'>Reset Password</a></p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Email address not found.</p>";
    }
}
?>