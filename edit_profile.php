<?php
session_start();
include 'db.php';  // Ensure your database connection file is correctly included

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch user details
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (password_verify($current_password, $user['password'])) {
        if (!empty($new_password) && $new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $email, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $email, $user_id);
        }
        
        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
        } else {
            $message = "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    } else {
        $message = "Current password is incorrect.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
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
        <h1>Edit Profile</h1>
        <p><?php echo $message; ?></p>
        <form action="edit_profile.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">

            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
</main>
<footer>
    <p>&copy; 2024 Ad Connect. All rights reserved.</p>
</footer>
</body>
</html>
