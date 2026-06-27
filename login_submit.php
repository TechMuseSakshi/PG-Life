<?php
// 1. Always start the session at the very top to track the user
session_start();

// 2. Connect to your database
require("../includes/database_connect.php");

// 3. Get and sanitize input from the login form
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$password = sha1($password); 

// 4. Query the database for the user
$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($conn, $query);

// 5. Handle potential database connection errors
if (!$result) {
    echo json_encode(["success" => false, "message" => "Database error!"]);
    exit;
}

// 6. Check if a user with these credentials exists
if (mysqli_num_rows($result) == 0) {
    echo json_encode(["success" => false, "message" => "Login failed! Invalid email or password."]);
    exit;
}

// 7. Success! Retrieve user information
$row = mysqli_fetch_assoc($result);

// 8. CRITICAL: Store these into the SESSION
// Updated to use 'full_name' to match your header.php
$_SESSION['user_id'] = $row['id'];
$_SESSION['full_name'] = $row['full_name']; 
$_SESSION['email'] = $row['email'];

// 9. Send success message back to the browser's JavaScript
echo json_encode(["success" => true, "message" => "Login successful!"]);
?>