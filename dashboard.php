<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit();
}

include 'db.php';

// Fetch user information
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Fetch listings
$listings_sql = "SELECT l.*, u.username AS poster_username FROM listings l JOIN users u ON l.user_id = u.id";
$listings_result = $conn->query($listings_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="states_cities.js"></script>
  <style>
    .card img {
      max-height: 200px;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="my-4">Welcome, <?php echo $user['username']; ?>!</h1>
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
        <label for="state">State:</label>
        <select class="form-control" id="state" name="state" required>
          <option value="" disabled selected>Select a state</option>
          <option value="California">California</option>
          <option value="Texas">Texas</option>
          <!-- Add more states as needed -->
        </select>
      </div>
      <div class="form-group">
        <label for="cities">Cities:</label>
        <select class="form-control" id="cities" name="cities[]" multiple required></select>
      </div>
      <div class="form-group">
        <label for="media_type">Media Type:</label>
        <select class="form-control" id="media_type" name="media_type" required>
          <option value="billboard">Billboard</option>
          <option value="word_of_mouth">Word of Mouth</option>
          <option value="bumper_sticker">Bumper Sticker</option>
          <option value="shirt_wearing">Shirt Wearing</option>
          <option value="influencer_recommendation">Influencer Recommendation</option>
          <option value="social_media">Social Media</option>
          <option value="radio">Radio</option>
          <option value="television">Television</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div class="form-group">
        <label for="image">Upload Image:</label>
        <input type="file" class="form-control-file" id="image" name="image">
      </div>
      <div class="form-group">
        <label for="nationwide">Nationwide:</label>
        <input type="checkbox" id="nationwide" name="nationwide">
      </div>
      <button type="submit" class="btn btn-primary">Post Listing</button>
    </form>

    <h2 class="my-4">Listings</h2>
    <?php
    if ($listings_result->num_rows > 0) {
      while($row = $listings_result->fetch_assoc()) {
        echo "<div class='card mb-4'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . $row['title'] . "</h5>";
        echo "<p class='card-text'>" . $row['description'] . "</p>";
        echo "<p class='card-text'><strong>Posted by:</strong> " . $row['poster_username'] . "</p>";
        echo "<p class='card-text'><strong>State:</strong> " . $row['state'] . "</p>";
        echo "<p class='card-text'><strong>Cities:</strong> " . $row['cities'] . "</p>";
        echo "<p class='card-text'><strong>Media Type:</strong> " . ucfirst($row['media_type']) . "</p>";
        if ($row['nationwide']) {
          echo "<p class='card-text'><strong>Nationwide:</strong> Yes</p>";
        }
        if ($row['image_path']) {
          echo "<img src='" . $row['image_path'] . "' class='img-fluid' alt='Listing Image'>";
        }
        echo "</div>";
        echo "</div>";
      }
    } else {
      echo "<p>No listings found</p>";
    }
    ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
