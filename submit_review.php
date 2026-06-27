<?php
session_start();
require("../includes/database_connect.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Please login!"]);
    exit;
}

$property_id = $_POST['property_id'];
$content = $_POST['content'];
$user_name = $_SESSION['user_name'];

// 1. IMPORTANT: ADD THIS CHECK 
// This prevents the same review from being added if the user accidentally clicks twice
$check_query = "SELECT * FROM testimonials WHERE property_id = '$property_id' AND user_name = '$user_name' AND content = '$content'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo json_encode(["success" => true, "message" => "Review already submitted!"]);
    exit;
}

// 2. Only insert if it doesn't already exist
$query = "INSERT INTO testimonials (property_id, user_name, content) VALUES ('$property_id', '$user_name', '$content')";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(["success" => true, "message" => "Review submitted!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error"]);
}
exit;
?>