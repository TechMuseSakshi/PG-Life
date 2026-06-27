<?php
session_start();
require "includes/database_connect.php";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$city_name = isset($_GET['city']) ? mysqli_real_escape_string($conn, $_GET['city']) : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : ''; // Capture gender filter

// 1. Fetch the city details
$city_query = "SELECT * FROM cities WHERE name = '$city_name'";
$city_result = mysqli_query($conn, $city_query);
if (!$city_result || mysqli_num_rows($city_result) == 0) {
    echo "Sorry! We do not provide services in this city currently.";
    exit;
}
$city = mysqli_fetch_assoc($city_result);
$city_id = $city['id'];

// 2. Fetch properties (With Filter Logic)
$properties_query = "SELECT * FROM properties WHERE city_id = $city_id";
if ($gender == "male") {
    $properties_query .= " AND gender = 'male'";
} elseif ($gender == "female") {
    $properties_query .= " AND gender = 'female'";
}
$properties_result = mysqli_query($conn, $properties_query);
$properties = mysqli_fetch_all($properties_result, MYSQLI_ASSOC);

// 3. Fetch interested properties
$interested_users_properties = [];
if ($user_id) {
    $interested_query = "SELECT * FROM interested_users_properties WHERE user_id = $user_id";
    $interested_result = mysqli_query($conn, $interested_query);
    if ($interested_result) {
        $interested_data = mysqli_fetch_all($interested_result, MYSQLI_ASSOC);
        foreach ($interested_data as $data) {
            $interested_users_properties[] = $data['property_id'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PGs in <?= htmlspecialchars($city_name); ?> | PG Life</title>
    <?php include "includes/head_links.php"; ?>
    <link href="css/property_list.css" rel="stylesheet" />
</head>
<body>
    <?php include "includes/header.php"; ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($city_name); ?></li>
        </ol>
    </nav>

    <div class="page-container filter-bar row justify-content-around">
        <div class="col-auto filter-item">
            <img src="img/filter.png" alt="filter" />
            <span>Filter</span>
        </div>
        <a href="property_list.php?city=<?= htmlspecialchars($city_name); ?>" class="col-auto filter-item <?= ($gender == '') ? 'active' : ''; ?>">
            <img src="img/unisex.png" alt="unisex" />
            <span>All</span>
        </a>
        <a href="property_list.php?city=<?= htmlspecialchars($city_name); ?>&gender=male" class="col-auto filter-item <?= ($gender == 'male') ? 'active' : ''; ?>">
            <img src="img/male.png" alt="male" />
            <span>Boys</span>
        </a>
        <a href="property_list.php?city=<?= htmlspecialchars($city_name); ?>&gender=female" class="col-auto filter-item <?= ($gender == 'female') ? 'active' : ''; ?>">
            <img src="img/female.png" alt="female" />
            <span>Girls</span>
        </a>
    </div>

    <div class="page-container">
        <?php if (count($properties) == 0): ?>
            <div class="no-prop-container">
                <p>No PG found in this city!</p>
            </div>
        <?php endif; ?>

        <?php foreach ($properties as $property): ?>
            <div class="property-card row clearfix">
                <div class="image-container col-md-4">
                    <img src="img/properties/<?= $property['id']; ?>/1.jpg" alt="property-image" class="img-fluid" />
                </div>
                <div class="content-container col-md-8">
                    <div class="row no-gutters justify-content-between">
                        <div class="star-container" title="<?= $property['rating_clean']; ?>">
                            <?php
                            $rating = $property['rating_clean'];
                            for ($i = 0; $i < 5; $i++) {
                                echo ($rating >= $i + 0.8) ? '<i class="fas fa-star"></i>' : (($rating >= $i + 0.3) ? '<i class="fas fa-star-half-alt"></i>' : '<i class="far fa-star"></i>');
                            }
                            ?>
                        </div>
                        <div class="interested-container">
                            <?php $is_interested = in_array($property['id'], $interested_users_properties); ?>
                            <i class="is-interested-image <?= $is_interested ? 'fas' : 'far'; ?> fa-heart" property_id="<?= $property['id']; ?>"></i>
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
    </div>

    <?php include "includes/login_modal.php"; ?>
    <?php include "includes/signup_modal.php"; ?>
    <?php include "includes/footer.php"; ?>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
</body>
</html>