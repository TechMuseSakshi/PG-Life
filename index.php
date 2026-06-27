<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | PG Life</title>
    <?php include "includes/head_links.php"; ?>
    <link href="css/home.css" rel="stylesheet" />
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div class="banner-container">
        <div class="search-container">
            <h1 class="white text-center">Happiness is a find away.</h1>
            <form id="search-form" action="property_list.php" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control input-city" id="city" name="city" placeholder="Enter your city to search for PGs" />
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="page-container">
        <h1 class="city-heading">Major Cities</h1>
        <div class="row">
            <div class="city-card-container col-md">
                <a href="property_list.php?city=Delhi">
                    <div class="city-card rounded-circle">
                        <img src="img/delhi.png" class="city-img" alt="Delhi" />
                    </div>
                </a>
            </div>
            <div class="city-card-container col-md">
                <a href="property_list.php?city=Mumbai">
                    <div class="city-card rounded-circle">
                        <img src="img/mumbai.png" class="city-img" alt="Mumbai" />
                    </div>
                </a>
            </div>
            <div class="city-card-container col-md">
                <a href="property_list.php?city=Bengaluru">
                    <div class="city-card rounded-circle">
                        <img src="img/bangalore.png" class="city-img" alt="Bengaluru" />
                    </div>
                </a>
            </div>
            <div class="city-card-container col-md">
                <a href="property_list.php?city=Hyderabad">
                    <div class="city-card rounded-circle">
                        <img src="img/hyderabad.png" class="city-img" alt="Hyderabad" />
                    </div>
                </a>
            </div>
        </div>
    </div>

    <?php include "includes/login_modal.php"; ?>
    <?php include "includes/signup_modal.php"; ?>

    <div id="loading"></div>
<?php include "includes/footer.php"; ?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
</body>
</html>