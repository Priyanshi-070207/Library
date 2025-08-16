<?php
    session_start(); // Starting the session.
    include("database.php"); // Includes the file which makes the databse connection

    $errors = $_SESSION['errors'] ?? []; // If there are any errors already stored in this session store them otherwise create a new array to store user errors.
    unset($_SESSION['errors']);  // After storing errors in a new array, removes the variable storing errors from the session

    $old = $_SESSION['old'] ?? [];  // If the user had entered any data previously and got redirected to this page, there data will get stored here. So it can be displayed.
    unset($_SESSION['old']); 

    
    if($_SERVER["REQUEST_METHOD"] == "POST"){ // If the user presses the submit button and the form submits only then this code will run
        
        // Store user data in temporary variables
        $username_or_email = trim($_POST["username_or_email"] ?? "");  // Ternary operator is being used. If username has any data store that or store empty string. 
        $password = trim($_POST["password"] ?? "");

        if(empty($username_or_email)){ // Function to check if a variable is emopty or not
            $errors[] = "Username/Email is required."; // Shorthard way of appending an element to an array in php
        } elseif((!preg_match("/^[a-zA-Z0-9_]{3,50}$/", $username_or_email)) && (!filter_var($username_or_email, FILTER_VALIDATE_EMAIL))){ 
        // Here we are using (A)' && (B)' => If the doesn't matches name and it doesn;t match email then only show the error.  
            $errors[] = "Name must be 3-50 characters and only contain letters, numbers and underscores.";
        }

        if(empty($password)){ // Validate password
            $errors[] = "Password is required.";
        } 

        // Check if the user exists
        $statement = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?"); // Tells the db that a sql statement is about to be executed.
        $statement->bind_param("ss", $username_or_email, $username_or_email); 
        $statement->execute(); // Execute the statement
        $result = $statement->get_result(); // Get username, password from the database
        $statement->close();

        $user_info = null; // Create a variable to store user info. Created this outside the if block so that we can access it outside of there.

        if($result->num_rows === 0){ // Check if user exists already in the database
            $errors[] = "Invalid username or password."; // If the user doesn't exist show error.
        } else {
            $user_info = $result->fetch_assoc(); // Creates an associative array of the columns => values of the users tables 
            if(!password_verify($password, $user_info["password"])){ // Function to compare string against hash
                $errors[] = "Invalid username or password."; // If the password is wrong show error.
            }        
        }

        if(!empty($errors)){ // If there are some errors, store it in $_SESSION sgv so we can display them to user
            $_SESSION["errors"] = $errors; // Also store the old data so that can be re-displayed to the user
            $_SESSION["old"] = ["username_or_email" => $username_or_email];
            header("Location: login.php"); // Redirect the user to the same page if there is some error
            exit(); // Stop the further execution of code.
        }

        
        $_SESSION['user'] = $user_info['username'];  // If no error, save the information in session
        $_SESSION['user_id'] = $user_info['id'];  // If no error, save the information in session
        header("Location: dashboard.php"); // After successful login, redirect to dashboard
        exit();
    }
    mysqli_close($conn); // Close the sql connection
?>

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
        <input type="text" name="username_or_email" placeholder="Username/Email" required
        value="<?php echo htmlspecialchars($old['username_or_email'] ?? ''); ?>"><br>
        <label>Password:</label><br>
        <input type="password" name="password" id="password" placeholder="Password" required><br>
        <!--Style this too PRIYANSHI!!-->
        <input type="checkbox" id="show_passwords">Show password <!--Checkbox to toggle show password settings--> 
        <?php if(!empty($errors)): ?>    <!--If there is an error, show it to the user.-->
            <?php foreach($errors as $error): ?>  <!--Using a foreach loop to iterate over each error stored in errors array-->
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p> <!--Output is secured by converting any html tag into html entities-->
            <?php endforeach; ?>     
        <?php endif; ?>
        <button type="submit" name="login">Login</button>
    </form>
    <script src="register.js"></script> <!--Link the js file-->
</body>
</html>