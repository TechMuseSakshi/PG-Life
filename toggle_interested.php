<?php
session_start();
require("../includes/database_connect.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Please login first!"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$property_id = $_POST['property_id'];

// Check if already interested
$check_query = "SELECT * FROM interested_users_properties WHERE user_id = $user_id AND property_id = $property_id";
$result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($result) > 0) {
    // Already interested, so remove it (Toggle Off)
    $delete_query = "DELETE FROM interested_users_properties WHERE user_id = $user_id AND property_id = $property_id";
    mysqli_query($conn, $delete_query);
    echo json_encode(["success" => true, "is_interested" => false]);
} else {
    // Not interested, so add it (Toggle On)
    $insert_query = "INSERT INTO interested_users_properties (user_id, property_id) VALUES ($user_id, $property_id)";
    mysqli_query($conn, $insert_query);
    echo json_encode(["success" => true, "is_interested" => true]);
}
exit;
?>