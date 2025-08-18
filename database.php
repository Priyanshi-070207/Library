<?php
    // TiDB connection 
    $host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com"; // Host name from the website
    $port = 4000; // TiDB port
    $user = "rknz6vUhoNWWYZk.root"; // Username
    $pass = "TeglVd1TMZiWLwzq"; // Password
    $dbname = "test"; // Database name

    $conn = mysqli_init(); // Initialize an object to connect to the sql server
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL); // Use ssl encryption for this connection
    // $conn -> Connecting object, If we wanted to implement client authorization we needed => 2: Key file(client key), 3: Certification file(client certificate), 4: CA File(certificate Authority file => Used to check server security), 5: CA path(path of files containing the certificates), 6: Which encryption algorithm to use => NULL: Default algorithm.

    // Connect using SSL
    if (!mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
