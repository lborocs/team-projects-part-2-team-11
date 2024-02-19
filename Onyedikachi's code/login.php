<?php
session_start(); // Start session if not already started

// Set up the connection to the database
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$servername = "localhost";
$dbname = "team011";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error)));
}

// Prepare and bind parameters
$stmt = $conn->prepare("SELECT * FROM Users_Details WHERE TRIM(user_email) = ?");
$stmt->bind_param("s", $email);

// Escape user input for security
$email = $_POST['email'];
$password = trim($_POST['password']);

// Debug statement to echo the email and password being used
$debug_info = json_encode(array("email" => $email, "password" => $password));

// Log the email and password being used
error_log("Email: " . $email . ", Password: " . $password);

// Check if email or password is empty
if (empty($email) || empty($password)) {
    // Debug statement to log empty email or password
    error_log("Email or password is empty. Email: " . $email . ", Password: " . $password);
    die(json_encode(array("success" => false, "message" => "Email or password is empty")));
}

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if there's an error during query execution
if (!$result) {
    die(json_encode(array("success" => false, "message" => "Query Error: " . $conn->error)));
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedPassword = $row['password']; // Retrieve password from the database

    // Debugging: Log retrieved password from the database and entered password
    error_log("Retrieved password: " . $storedPassword);
    error_log("Entered password: " . $password);

    // Include retrieved password and entered password in debug info
    $debug_info = json_encode(array(
        "email" => $email,
        "password" => $password,
        "retrieved_password" => $storedPassword,
        "entered_password" => $password,
    ));

    // Verify the password
    if ($password == $storedPassword) {
        // Password is correct, handle successful login
        $_SESSION['valid']=true;
        $_SESSION['timeout']=time();
        $_SESSION['email'] = $email; // Store email in session for further use

        
        $role_query = "SELECT role FROM Users_Details WHERE TRIM(user_email) = ?";
        $role_stmt = $conn->prepare($role_query);
        $role_stmt->bind_param("s", $email);
        $role_stmt->execute();
        $role_result = $role_stmt->get_result();
    
        if ($role_result->num_rows > 0) {
            $role_row = $role_result->fetch_assoc();
            $role = $role_row['role'];

            $_SESSION['role'] = $role;// store the role of the user for further use.
    
            // Include role information in the JSON response
            echo json_encode(array("success" => true, "message" => "Login successful", "role" => $role));
        } else {
            // Role not found
            echo json_encode(array("success" => true, "message" => "Login successful. Role not found"));
        }
    
        $role_stmt->close();
    } else {
        // Password is incorrect
        echo json_encode(array("success" => false, "message" => "Incorrect password", "debug_info" => $debug_info));
    }    
} else {
    // User not found
    $debug_info = json_encode(array(
        "email" => $email,
        "password" => $password,
        "retrieved_password" => null,
        "entered_password" => $password,
    ));
    echo json_encode(array("success" => false, "message" => "User not found", "debug_info" => $debug_info));
}

// Close the prepared statement
$stmt->close();

// Close database connection
$conn->close();
?>
