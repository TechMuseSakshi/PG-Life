<?php
require("../includes/database_connect.php");

// 1. Get and sanitize inputs
$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = sha1($_POST['password']);
$college_name = mysqli_real_escape_string($conn, $_POST['college_name']);
$gender = mysqli_real_escape_string($conn, $_POST['gender']);

// --- ADDED: Basic Validation ---
if (empty($full_name) || empty($phone) || empty($email) || empty($_POST['password']) || empty($college_name) || empty($gender)) {
    echo json_encode(["success" => false, "message" => "All fields are required!"]);
    exit;
}

if (strlen($phone) < 10) {
    echo json_encode(["success" => false, "message" => "Please enter a valid 10-digit phone number!"]);
    exit;
}
// -------------------------------

// 2. Check if email already exists
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["success" => false, "message" => "Database error!"]);
    exit;
}

$row_count = mysqli_num_rows($result);
if ($row_count > 0) {
    echo json_encode(["success" => false, "message" => "This email is already registered!"]);
    exit;
}

// 3. Insert new user
$sql = "INSERT INTO users (full_name, phone, email, password, college_name, gender) 
        VALUES ('$full_name', '$phone', '$email', '$password', '$college_name', '$gender')";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["success" => false, "message" => "Registration failed!"]);
    exit;
}

// 4. Send back a SUCCESS JSON response only
echo json_encode(["success" => true, "message" => "Your account has been created successfully!"]);
exit;
?>