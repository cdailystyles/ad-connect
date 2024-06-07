<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit();
}

include 'db.php';

// Fetch listings from the database
$sql = "SELECT * FROM listings";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h1 class="my-4">Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <a href="logout.php" class="btn btn-danger mb-4">Logout</a>

    <h2>Post a New Listing</h2>
    <form action="create_listing.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" required>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
      </div>
      <div class="form-group">
        <label for="city">City:</label>
        <input type="text" class="form-control" id="city" name="city" required>
      </div>
      <div class="form-group">
        <label for="media_type">Media Type:</label>
        <select class="form-control" id="media_type" name="media_type" required>
          <option value="billboard">Billboard</option>
          <option value="word_of_mouth">Word of Mouth</option>
          <option value="bumper_sticker">Bumper Sticker</option>
          <option value="shirt_wearing">Shirt Wearing</option>
          <option value="influencer_recommendation">Influencer Recommendation</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div class="form-group">
        <label for="image">Upload Image:</label>
        <input type="file" class="form-control-file" id="image" name="image">
      </div>
      <button type="submit" class="btn btn-primary">Post Listing</button>
    </form>

    <h2 class="my-4">Listings</h2>
    <?php
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<div class='card mb-4'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . $row['title'] . "</h5>";
        echo "<p class='card-text'>" . $row['description'] . "</p>";
        echo "<p class='card-text'><strong>City:</strong> " . $row['city'] . "</p>";
        echo "<p class='card-text'><strong>Media Type:</strong> " . ucfirst($row['media_type']) . "</p>";
        if ($row['image_path']) {
          echo "<img src='" . $row['image_path'] . "' class='img-fluid' alt='Listing Image'>";
        }
        echo "</div>";
        echo "</div>";
      }
    } else {
      echo "<p>No listings found</p>";
    }
    $conn->close();
    ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
