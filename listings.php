<?php
session_start();
include 'db.php';
include 'data_library.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$searchConditions = [];
$queryParams = [];

if (isset($_GET['city']) && !empty($_GET['city'])) {
    $searchConditions[] = "cities LIKE ?";
    $queryParams[] = '%' . $_GET['city'] . '%';
}

if (isset($_GET['state']) && !empty($_GET['state'])) {
    $searchConditions[] = "state = ?";
    $queryParams[] = $_GET['state'];
}

if (isset($_GET['media_type']) && !empty($_GET['media_type'])) {
    $searchConditions[] = "media_type = ?";
    $queryParams[] = $_GET['media_type'];
}

if (isset($_GET['buy_sell']) && !empty($_GET['buy_sell'])) {
    $searchConditions[] = "buy_sell = ?";
    $queryParams[] = $_GET['buy_sell'];
}

$query = "SELECT id, title, description, image_paths, state, cities, media_type, buy_sell FROM listings";
if (!empty($searchConditions)) {
    $query .= " WHERE " . implode(" AND ", $searchConditions);
}

$stmt = $conn->prepare($query);

if (!empty($queryParams)) {
    $types = str_repeat('s', count($queryParams));
    $stmt->bind_param($types, ...$queryParams);
}

$stmt->execute();
$result = $stmt->get_result();

$listings = [];
while ($row = $result->fetch_assoc()) {
    $listings[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listings</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .search-panel {
            width: 20%;
            float: left;
            padding: 10px;
            box-sizing: border-box;
        }
        .listings {
            width: 75%;
            float: right;
            padding: 10px;
            box-sizing: border-box;
        }
        .listing {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .listing img {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
        }
        .listing .details {
            display: inline-block;
            vertical-align: top;
            max-width: calc(100% - 120px);
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
    <div class="search-panel">
        <form action="listings.php" method="get">
            <h4>Search Listings</h4>
            <label for="search_city">City:</label>
            <input type="text" id="search_city" name="city" value="<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>">
            <label for="search_state">State:</label>
            <input type="text" id="search_state" name="state" value="<?php echo isset($_GET['state']) ? htmlspecialchars($_GET['state']) : ''; ?>">
            <label for="search_media_type">Media Type:</label>
            <select id="search_media_type" name="media_type">
                <option value="">Select Media Type</option>
                <?php foreach ($mediaTypes as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>" <?php echo (isset($_GET['media_type']) && $_GET['media_type'] === $type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type); ?></option>
                <?php endforeach; ?>
            </select>
            <label for="search_buy_sell">Buy/Sell:</label>
            <select id="search_buy_sell" name="buy_sell">
                <option value="">Select Type</option>
                <option value="buying" <?php echo (isset($_GET['buy_sell']) && $_GET['buy_sell'] === 'buying') ? 'selected' : ''; ?>>Buying</option>
                <option value="selling" <?php echo (isset($_GET['buy_sell']) && $_GET['buy_sell'] === 'selling') ? 'selected' : ''; ?>>Selling</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="listings">
        <h3>Your Listings</h3>
        <?php if (empty($listings)): ?>
            <p>No listings found.</p>
        <?php else: ?>
            <?php foreach ($listings as $listing): ?>
                <div class="listing">
                    <div class="image">
                        <?php if ($listing['image_paths']): ?>
                            <?php $images = explode(',', $listing['image_paths']); ?>
                            <?php foreach ($images as $image): ?>
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Listing Image">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="details">
                        <h4><?php echo htmlspecialchars($listing['title']); ?></h4>
                        <p><?php echo htmlspecialchars($listing['description']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($listing['state'] . ', ' . $listing['cities']); ?></p>
                        <p><strong>Media Type:</strong> <?php echo htmlspecialchars($listing['media_type']); ?></p>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($listing['buy_sell']); ?></p>
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
