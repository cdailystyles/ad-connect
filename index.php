<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Connect Home</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
    <nav>
        <div class="logo">Ad Connect</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="listings.php">Listings</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login_register.php">Login/Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
    <section class="intro">
        <h1>Welcome to Ad Connect</h1>
        <p>Your gateway to finding and offering advertising services.</p>
    </section>
</main>
<footer>
    <p>&copy; 2024 Ad Connect. All rights reserved.</p>
</footer>
</body>
</html>
