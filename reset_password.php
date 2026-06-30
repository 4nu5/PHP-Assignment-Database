<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
require 'connect.php';

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$message = '';
$isTokenValid = false;

if(empty($token)){
    $message = "Error: Invalid or missing Token";
}

try{
    $stmt = $cool -> prepare("SELECT email FROM reset_password WHERE token= :token AND expires_at > NOW() LIMIT 1");
    $stmt -> execute(['token' => $token]);
    $resetRequest = $stmt -> fetch(PDO::FETCH_ASSOC);

    if($resetRequest){
        $isTokenValid = true;
        $email = $resetRequest['email'];
    }else{
        $message = "This password link has expired or is invalid. Please Renter a new one";
    }
}catch(PDOException $e){
    die("Database Verification Failed: " . $e-> getMessage());
}

if($_SERVER["REQUEST_METHOD"] == "POST" && $isTokenValid){
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if(strlen($newPassword) < 8){
        $message = "Password must be 8 characters long";
    }elseif($newPassword !== $confirmPassword){
        $message = "Password doesnt match";
    }else{
        try{
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $updateStmt = $cool -> prepare("UPDATE users SET passwordHash = :password WHERE email = :email");
            $updateStmt -> execute([
                'password' => $hashedPassword,
                'email' => $email
            ]);
            if($updateStmt -> rowCount() > 0){
                 $deleteStmt = $cool -> prepare("DELETE FROM reset_password WHERE email = :email");
                $deleteStmt -> execute([
                'email' => $email
            ]);
            $message = "Something went right";
            $isTokenValid = false;
            }else{
                $message = "Somethin went wrong";
            }
        }catch(PDOException $e){
            die("System Update Error" . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | CommunityConnect</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        input[type="password"] { width: 100%; padding: 10px; margin: 10px 0 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #28a745; border: none; border-radius: 4px; color: white; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .alert { text-align: center; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Password</h2>
        
        <?php if (!empty($message)): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($isTokenValid): ?>
            <p>Please enter your new secure password below.</p>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required minlength="6">
                
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                
                <button type="submit">Update Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>