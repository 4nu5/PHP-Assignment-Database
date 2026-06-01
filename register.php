<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CommunityConnect</title>
    </head>
<body>

    <h2>Create Resident Account</h2>
    
    <form action="registerProccess.php" method="POST">
        
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="email">Email Address:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required minlength="6"><br><br>
        
        <button type="submit">Register Account</button>
        
    </form>
    
    <p>Already have an account? <a href="login.php">Log in here</a>.</p>

</body>
</html>

