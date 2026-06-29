<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | CommunityConnect</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        input[type="email"] { width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #0056b3; border: none; border-radius: 4px; color: white; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #004494; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <p>Enter your registered email address to receive a password reset link.</p>
        <form action="forgot_password_process.php" method="POST">
            <input type="email" name="email" required placeholder="Enter your email">
            <button type="submit">Send Reset Link</button>
        </form>
        <br>
        <a href="login.php" style="color: #0056b3; text-decoration: none;">← Back to Login</a>
    </div>
</body>
</html>