<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch some basic user details or statistics
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
    <!-- Navigation here -->
</header>
<main>
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
    <div>
        <a href="edit_profile.php">Edit Profile</a>
        <a href="create_listing.php">Create New Listing</a>
        <a href="listings.php">View Listings</a>
        <a href="logout.php">Logout</a>
    </div>
</main>
<footer>
    <p>&copy; 2024 Ad Connect. All rights reserved.</p>
</footer>
</body>
</html>
