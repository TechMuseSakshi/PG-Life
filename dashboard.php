<?php
session_start();
require "includes/database_connect.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch only the properties the user is interested in
$query = "SELECT properties.* FROM properties 
          JOIN interested_users_properties ON properties.id = interested_users_properties.property_id 
          WHERE interested_users_properties.user_id = $user_id";

$result = mysqli_query($conn, $query);
$properties = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | PG Life</title>
    <?php include "includes/head_links.php"; ?>
    <link href="css/property_list.css" rel="stylesheet" />
</head>
<body>
    <?php include "includes/header.php"; ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    <div class="page-container">
        <h1>My Interested Properties</h1>
        
        <?php if (count($properties) == 0): ?>
            <div class="no-prop-container">
                <p>You haven't marked any properties as interested yet!</p>
            </div>
        <?php else: ?>
            <?php foreach ($properties as $property): ?>
                <div class="property-card row clearfix">
                    <div class="image-container col-md-4">
                        <img src="img/properties/<?= $property['id']; ?>/1.jpg" alt="property-image" class="img-fluid" />
                    </div>
                    <div class="content-container col-md-8">
                        <div class="row no-gutters justify-content-between">
                            <div class="star-container">
                                <!-- Optional: Add star rating here to match property_list.php -->
                            </div>
                            <div class="interested-container">
                                <!-- Added the 'fas' class because these are already interested -->
                                <i class="is-interested-image fas fa-heart" property_id="<?= $property['id']; ?>"></i>
                                <div class="interested-text">Interested</div>
                            </div>
                        </div>
                        <div class="detail-container">
                            <div class="property-name"><?= htmlspecialchars($property['name']); ?></div>
                            <div class="property-address"><?= htmlspecialchars($property['address']); ?></div>
                        </div>
                        <div class="row no-gutters text-container align-items-end justify-content-between">
                            <div class="price-container">
                                <div class="price">₹ <?= number_format($property['rent']); ?>/-</div>
                                <div class="per-month">per month</div>
                            </div>
                            <div class="button-container">
                                <a href="property_detail.php?property_id=<?= $property['id']; ?>" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include "includes/footer.php"; ?>
</body>
</html>