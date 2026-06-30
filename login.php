<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tester Login Form</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f9; margin: 0; }
        .login-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        h2 { margin-bottom: 20px; text-align: center; color: #333; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; border: none; border-radius: 4px; color: white; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Account Login</h2>
    <form action="loginProcess.php" method="POST">
        
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus autocomplete="username">
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
       
        <button type="submit">LOGIN</button><br><br>
        <div style="text-align: center; font-size: 14px;">
            <a href="forgot_password.php" style="color: #555; text-decoration: none; margin-right: 15px;">Forgot Password?</a>
            <span style="color: #ccc;">|</span>
            <a href="register.php" style="color: #007bff; text-decoration: none; margin-left: 15px;">Click to Register</a>
        </div>
    </form>
    </form>
</div>

</body>
</html>