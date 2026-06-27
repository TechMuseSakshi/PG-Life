<?php
session_start();
require "includes/database_connect.php";

// If user isn't logged in, redirect them
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// SQL JOIN to get only the properties the user has marked as interested
$query = "SELECT p.* FROM properties p
          INNER JOIN interested_users_properties iup ON p.id = iup.property_id
          WHERE iup.user_id = $user_id";

$result = mysqli_query($conn, $query);
$interested_properties = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist | PG Life</title>
    <?php include "includes/head_links.php"; ?>
    <link href="css/property_list.css" rel="stylesheet" />
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div class="page-container">
        <h1>My Interested Properties</h1>
        <?php if (count($interested_properties) == 0): ?>
            <p>You haven't added any properties to your wishlist yet.</p>
        <?php else: ?>
            <?php foreach ($interested_properties as $property): ?>
                <div class="property-card row clearfix">
                    <div class="content-container col-md-12">
                        <div class="property-name"><?= htmlspecialchars($property['name']); ?></div>
                        <div class="property-address"><?= htmlspecialchars($property['address']); ?></div>
                        <a href="property_detail.php?property_id=<?= $property['id']; ?>" class="btn btn-primary">View Property</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include "includes/footer.php"; ?>
</body>
</html>