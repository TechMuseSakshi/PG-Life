<?php
// Ensure session is started to access user data
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-md navbar-light bg-light">
    <a class="navbar-brand" href="index.php">
        <img src="img/logo.png" alt="Logo" style="height: 30px;">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#my-navbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="my-navbar">
        <ul class="navbar-nav ml-auto">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#signup-modal">Signup</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#login-modal">Login</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <span class="nav-link" style="color: #6c757d;">
                        <i class="fas fa-user"></i> Hi, <?php echo $_SESSION['full_name']; ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_properties.php">My Wishlist</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>