<?php
session_start();
require "includes/database_connect.php";

$property_id = isset($_GET['property_id']) ? (int)$_GET['property_id'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$property_id) {
    echo "Invalid property!";
    exit;
}

// 1. Fetch Property
$query = "SELECT * FROM properties WHERE id = $property_id";
$result = mysqli_query($conn, $query);
$property = mysqli_fetch_assoc($result);

if (!$property) {
    echo "Property not found!";
    exit;
}

// 2. Fetch Amenities
$sql_amenities = "SELECT a.* FROM amenities a 
                  INNER JOIN properties_amenities pa ON a.id = pa.amenity_id 
                  WHERE pa.property_id = $property_id";
$result_amenities = mysqli_query($conn, $sql_amenities);
$amenities = mysqli_fetch_all($result_amenities, MYSQLI_ASSOC);

// 3. Fetch Testimonials
$sql_testimonials = "SELECT * FROM testimonials WHERE property_id = $property_id";
$result_testimonials = mysqli_query($conn, $sql_testimonials);
$testimonials = mysqli_fetch_all($result_testimonials, MYSQLI_ASSOC);

// 4. Check Interested Status
$is_interested = false;
if ($user_id) {
    $interested_query = "SELECT * FROM interested_users_properties WHERE user_id = $user_id AND property_id = $property_id";
    $interested_result = mysqli_query($conn, $interested_query);
    if ($interested_result && mysqli_num_rows($interested_result) > 0) {
        $is_interested = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($property['name']); ?> | PG Life</title>
    <?php include "includes/head_links.php"; ?>
    <link href="css/property_detail.css" rel="stylesheet" />
</head>
<body>
    <?php include "includes/header.php"; ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($property['name']); ?></li>
        </ol>
    </nav>

    <div id="property-images" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php for ($i = 1; $i <= 3; $i++): 
                $img_path = "img/properties/" . $property['id'] . "/" . $i . ".jpg";
                if (file_exists($img_path)): ?>
                <div class="carousel-item <?= ($i === 1) ? 'active' : ''; ?>">
                    <img class="d-block w-100" src="<?= $img_path ?>" alt="Slide <?= $i ?>">
                </div>
            <?php endif; endfor; ?>
        </div>
        <a class="carousel-control-prev" href="#property-images" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </a>
        <a class="carousel-control-next" href="#property-images" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </a>
    </div>

    <div class="page-container main-details">
        <div class="row justify-content-between">
            <div>
                <h1><?= htmlspecialchars($property['name']); ?></h1>
                <p class="text-muted"><?= htmlspecialchars($property['address']); ?></p>
            </div>
            <div class="price-box text-right">
                <h2 class="text-primary">₹ <?= number_format($property['rent']); ?>/-</h2>
                <div class="interested-container">
                    <i class="is-interested-image <?= $is_interested ? 'fas' : 'far'; ?> fa-heart" property_id="<?= $property['id']; ?>"></i>
                </div>
            </div>
        </div>

        <hr/>
        <h3>Amenities</h3>
        <div class="row my-4 text-center">
            <?php foreach ($amenities as $amenity): ?>
                <div class="col-3 amen-item">
                    <i class="<?= $amenity['icon']; ?> text-success fa-2x"></i>
                    <p><?= $amenity['name']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <hr/>
        <h3>About the Property</h3>
        <p class="my-3 text-justify"><?= nl2br(htmlspecialchars($property['description'])); ?></p>

        <?php if (count($testimonials) > 0): ?>
            <hr/>
            <h3>What residents say</h3>
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-card">
                    <p class="text-muted">"<?= htmlspecialchars($testimonial['content']); ?>"</p>
                    <p><strong>- <?= htmlspecialchars($testimonial['user_name']); ?></strong></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <hr/>
        <div class="review-section">
            <h4>Leave a Review</h4>
            <form id="review-form" action="api/submit_review.php" method="POST">
                <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
                <div class="form-group">
                    <label>Rating (1-5):</label>
                    <input type="number" name="rating" min="1" max="5" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Your Review:</label>
                    <textarea name="content" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
    </div>

    <?php include "includes/login_modal.php"; ?>
    <?php include "includes/signup_modal.php"; ?>
    <?php include "includes/footer.php"; ?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
</body>
</html>