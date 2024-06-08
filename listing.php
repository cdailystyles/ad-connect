<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit();
}

$listing_id = $_GET['id'];

// Fetch listing details
$listing_sql = "SELECT * FROM listings WHERE id='$listing_id'";
$listing_result = $conn->query($listing_sql);
$listing = $listing_result->fetch_assoc();

// Fetch reviews
$reviews_sql = "SELECT r.*, u.username AS reviewer_username FROM reviews r JOIN users u ON r.reviewer_id = u.id WHERE r.listing_id='$listing_id' ORDER BY r.created_at DESC";
$reviews_result = $conn->query($reviews_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listing Details</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h1 class="my-4"><?php echo $listing['title']; ?></h1>
    <a href="dashboard.php" class="btn btn-secondary mb-4">Back to Dashboard</a>

    <div class="card mb-4">
      <div class="card-body">
        <p class="card-text"><?php echo $listing['description']; ?></p>
        <p class="card-text"><strong>City:</strong> <?php echo $listing['city']; ?></p>
        <p class="card-text"><strong>Media Type:</strong> <?php echo ucfirst($listing['media_type']); ?></p>
        <?php if ($listing['image_path']) { ?>
          <img src="<?php echo $listing['image_path']; ?>" class="img-fluid" alt="Listing Image">
        <?php } ?>
      </div>
    </div>

    <h2>Send a Message</h2>
    <form action="send_message.php" method="POST">
      <input type="hidden" name="receiver_id" value="<?php echo $listing['user_id']; ?>">
      <input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>">
      <div class="form-group">
        <label for="message">Message:</label>
        <textarea class="form-control" id="message" name="message" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Send Message</button>
    </form>

    <h2 class="my-4">Reviews</h2>
    <?php
    if ($reviews_result->num_rows > 0) {
      while($row = $reviews_result->fetch_assoc()) {
        echo "<div class='card mb-4'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . $row['reviewer_username'] . "</h5>";
        echo "<p class='card-text'>" . $row['comment'] . "</p>";
        echo "<p class='card-text'><strong>Rating:</strong> " . $row['rating'] . "/5</p>";
        echo "<p class='card-text'><small class='text-muted'>Reviewed on " . $row['created_at'] . "</small></p>";
        echo "</div>";
        echo "</div>";
      }
    } else {
      echo "<p>No reviews found</p>";
    }
    ?>

    <h2>Submit a Review</h2>
    <form action="submit_review.php" method="POST">
      <input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>">
      <div class="form-group">
        <label for="rating">Rating:</label>
        <select class="form-control" id="rating" name="rating" required>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
      </div>
      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea class="form-control" id="comment" name="comment" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
