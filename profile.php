<?php
session_start();
include 'db.php';  // Ensure your database connection file is correctly included

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch user listings
$stmt = $conn->prepare("SELECT id, code, title, description, created_at FROM listings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$listings = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
    <nav>
        <div class="logo">Ad Connect</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="listings.php">Listings</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="container">
        <div class="profile-header">
            <h1><?php echo htmlspecialchars($user['username']); ?>'s Profile</h1>
            <p>Member since: <?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
            <a href="edit_profile.php" class="btn">Edit Profile</a>
        </div>
        
        <div class="profile-section">
            <h2>Account Details</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <div class="profile-section">
            <h2>Your Listings</h2>
            <?php if ($listings->num_rows > 0): ?>
                <ul>
                    <?php while ($listing = $listings->fetch_assoc()): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                            <p><?php echo htmlspecialchars($listing['description']); ?></p>
                            <p>Listing Code: <?php echo htmlspecialchars($listing['code']); ?></p>
                            <p>Posted on: <?php echo date("F j, Y", strtotime($listing['created_at'])); ?></p>
                            <a href="delete_listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-danger">Delete</a>
                            <a href="create_copy.php?id=<?php echo $listing['id']; ?>" class="btn">Create New from Copy</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>You have no listings yet.</p>
            <?php endif; ?>
        </div>

        <div class="profile-section">
            <h2>Account Management</h2>
            <a href="change_password.php" class="btn">Change Password</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</main>
<footer>
    <p>&copy; 2024 Ad Connect. All rights reserved.</p>
</footer>
</body>
</html>
