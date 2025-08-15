<?php
    // TiDB / PlanetScale connection details
    $host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com"; // Replace with your host
    $port = 4000; // TiDB default
    $user = "rknz6vUhoNWWYZk.root"; // Replace with your username
    $pass = "TeglVd1TMZiWLwzq"; // Replace with your password
    $dbname = "test"; // Replace with your database name

    // Initialize MySQLi with SSL
    $conn = mysqli_init();
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

    // Connect using SSL
    if (!mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_close($conn);
?>
