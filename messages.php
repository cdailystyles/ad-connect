<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit();
}

include 'db.php';

// Fetch user messages
$user_id = $_SESSION['user_id'];
$sql = "SELECT m.*, u.username AS sender_username FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = '$user_id' ORDER BY m.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h1 class="my-4">Messages</h1>
    <a href="dashboard.php" class="btn btn-secondary mb-4">Back to Dashboard</a>
    <?php
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<div class='card mb-4'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>From: " . $row['sender_username'] . "</h5>";
        echo "<p class='card-text'>" . $row['message'] . "</p>";
        echo "<p class='card-text'><small class='text-muted'>Sent on " . $row['created_at'] . "</small></p>";
        echo "</div>";
        echo "</div>";
      }
    } else {
      echo "<p>No messages found</p>";
    }
    ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
