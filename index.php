<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>E-Library Login</h2>
    <form action="index.php" method="POST">
        <label>Username/Email:</label><br>
        <input type="text" name="username" placeholder="Username/Email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>