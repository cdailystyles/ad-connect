<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();
$stmt->close();

$sql = "SELECT * FROM listings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$listingResult = $stmt->get_result();
$listings = [];
while ($row = $listingResult->fetch_assoc()) {
    $listings[] = $row;
}
$stmt->close();
$conn->close();

function generateCode($length = 10) {
    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, $length);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-header, .listings-section {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .profile-header h2, .listings-section h2 {
            margin-top: 0;
        }
        .listing {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
        }
        .listing img {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
        }
        .listing .details {
            flex-grow: 1;
        }
        .listing .actions {
            text-align: right;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="logo">Ad Connect</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="create_listing.php">Create Listing</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="profile-header">
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <a href="edit_profile.php">Edit Profile</a>
    </div>
    <div class="listings-section">
        <h2>Your Listings</h2>
        <?php if (empty($listings)): ?>
            <p>No listings found.</p>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
                <div class="listing">
                    <div class="details">
                        <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                        <p><?php echo htmlspecialchars($listing['description']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($listing['state'] . ', ' . $listing['cities']); ?></p>
                        <p><strong>Media Type:</strong> <?php echo htmlspecialchars($listing['media_type']); ?></p>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($listing['buy_sell']); ?></p>
                    </div>
                    <div class="actions">
                        <form action="delete_listing.php" method="post" style="display:inline;">
                            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                        <form action="create_copy.php" method="post" style="display:inline;">
                            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                            <button type="submit">Create Copy</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<footer>
    <p>&copy; 2024 Ad Connect. All rights reserved.</p>
</footer>
</body>
</html>
