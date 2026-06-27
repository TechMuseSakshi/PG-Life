<?php
session_start();
require "../includes/database_connect.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "message" => "Please login first!"));
    return;
}

$user_id = $_SESSION['user_id'];
$property_id = $_GET['property_id'];

// Check if user has already marked this property as interested
$check_query = "SELECT * FROM interested_users_properties WHERE user_id = '$user_id' AND property_id = '$property_id'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo json_encode(array("success" => false, "message" => "Already added to interests!"));
    return;
}

// Insert the record
$query = "INSERT INTO interested_users_properties (user_id, property_id) VALUES ('$user_id', '$property_id')";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(array("success" => true, "message" => "Added to interests!"));
} else {
    echo json_encode(array("success" => false, "message" => "Failed to add interest."));
}

mysqli_close($conn);
?>