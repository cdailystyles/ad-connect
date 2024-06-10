<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$original_id = intval($_GET['id']);

// Fetch original listing
$stmt = $conn->prepare("SELECT title, description, state, cities, media_type, image_paths, nationwide, buy_sell FROM listings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $original_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$listing = $result->fetch_assoc();
$stmt->close();

if ($listing) {
    $code = generateCode();
    $stmt = $conn->prepare("INSERT INTO listings (user_id, code, title, description, state, cities, media_type, image_paths, nationwide, buy_sell) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssisi", $user_id, $code, $listing['title'], $listing['description'], $listing['state'], $listing['cities'], $listing['media_type'], $listing['image_paths'], $listing['nationwide'], $listing['buy_sell']);
    if ($stmt->execute()) {
        header('Location: profile.php');
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
}

$conn->close();

function generateCode($length = 10) {
    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, $length);
}
?>
