<?php
    session_start(); // Starting the session.
    include("database.php"); // Includes the file which makes the databse connection

    $errors = $_SESSION['errors'] ?? []; // If there are any errors already stored in this session store them otherwise create a new array to store user errors.
    unset($_SESSION['errors']);  // After storing errors in a new array, removes the variable storing errors from the session

    $old = $_SESSION['old'] ?? [];  // If the user had entered any data previously and got redirected to this page, there data will get stored here. So it can be displayed.
    unset($_SESSION['old']); 

    $success = $_SESSION['success'] ?? ""; // If the registration is successful, the message will get stored in this array
    unset($_SESSION['success']); 

    if($_SERVER["REQUEST_METHOD"] == "POST"){  // If the user presses the submit button and the form submits only then this code will run

        // Store user data in temporary variables
        $username = trim($_POST["username"] ?? "");  // Ternary operator is being used. If username has any data store that or store empty string. 
        $email = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? "");

        if(empty($username)){ // Function to check if a variable is emopty or not
            $errors[] = "Username is required."; // Shorthard way of appending an element to an array in php
        } elseif(!preg_match("/^[a-zA-Z0-9_]{3,50}$/", $username)){ // pre_match() is like the re library in python. It matches a patter with a string. / -> Everything between a pair of / is regex pattern. ^ -> Start of pattern. $ -> End of pattern. [] - Everything between here is allowed. {} -> Length og the string.
            $errors[] = "Name must be 3-50 characters and only contain letters, numbers and underscores.";
        }

        if(empty($email)){
            $errors[] = "Email is required.";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){ // filter_var() is a php function to validate data.
            $errors[] = "Invalid email address.";
        } else {
            $statement = $conn->prepare("SELECT id FROM users WHERE email = ?"); // Tells the db that a sql statement is about to be executed. $statemnt -> An object that stores the sql statemnt. ? -> Placeholder like %s in C
            $statement->bind_param("s", $email); // Place $email in place of ?. It is secure as whatever is written in email is taken as string so even if someone types some code in this, it is still treated as string.
            $statement->execute(); // Execute the statement
            $statement->store_result(); // The result set gets stored in $statement variable
            if($statement->num_rows > 0){ // If there is some info that got returned means the email already exist in the database. So we should ask the user to login and not register.
                $errors[] = "Email is already registered";
            }
            $statement->close(); // Closing the statement so the resource can be freed up.
        }

        if(empty($password)){ // Validate password
            $errors[] = "Password is required.";
        } 

        if(!empty($errors)){ // If there are some errors, store it in $_SESSION sgv so we can display them to user
            $_SESSION["errors"] = $errors; // Also store the old data so that can be re-displayed to the user
            $_SESSION["old"] = ["username" => $username, "email" => $email];
            header("Location: register.php"); // Redirect the user to the same page
            exit(); // Stop the further execution of code.
        }

        if(empty($errors)){
            $hash = password_hash($password, PASSWORD_DEFAULT); // Using a php function to convert normal password into hash version so its more secure. PASSWORD_DEFAULT is the name of the hashing algorithm used.

            $statement = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $statement->bind_param("sss", $username, $email, $hash);

            if($statement->execute()){
                $_SESSION["success"] = "Registration successful!";
                header("Location: register.php");
                exit();
            } else {
                $errors[] = "Database error: " . $statement->error;
            }
            $statement->close();
        }
    }


    mysqli_close($conn); // Close the sql connection 
?>

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
        <label>Choose Username:</label>
        <input type="text" name="username" placeholder="Choose username" required
        value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>"><br> <!--If the user had entered details but there was an error show the old data to the user-->
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required
        value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"><br>
        <label>Choose Password:</label>
        <input type="password" name="password" id="password" placeholder="Choose password" required><br>
        <input type="checkbox" id="show_passwords">Show passwords <!--Checkbox to toggle show password settings--> 

        <?php if(!empty($errors)): ?> <!--If there is an error, show it to the user.-->
                    <div class="error-messages">
                        <?php foreach($errors as $error): ?> <!--Using a foreach loop to iterate over each error stored in errors array-->
                            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>  <!--Output is secured by converting any html tag into html entities-->
                        <?php endforeach; ?>
                    </div>
        <?php endif; ?>

        <?php if(!empty($success)): ?> <!--If registration is successful, display message-->
                    <div class="success-message">
                        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
                    </div>
        <?php endif; ?>

        <button type="submit" name="register">Sign up</button>
    </form>
    <p class="form-message">Already have an account? <a href="index.php">Login here</a></p>
    <script src="register.js"></script> <!--Link the js file-->
</body>
</html>
