<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up E-Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Create your E-Library Account</h2>
    <form action="register.php" method="POST">
        <label>Choose Username/Email:</label>
        <input type="text" name="username" placeholder="Choose username/email" required><br>
        <label>Choose Password:</label>
        <input type="password" name="password" placeholder="Choose password" required><br>
        <button type="submit" name="register">Sign up</button>
    </form>
    <p>Already have an account? <a href="index.php">Login here</a></p>
</body>
</html>