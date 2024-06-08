<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $reviewer_id = $_SESSION['user_id'];
  $listing_id = $_POST['listing_id'];
  $rating = $_POST['rating'];
  $comment = $_POST['comment'];

  $sql = "INSERT INTO reviews (listing_id, reviewer_id, rating, comment) VALUES ('$listing_id', '$reviewer_id', '$rating', '$comment')";

  if ($conn->query($sql) === TRUE) {
    header("Location: listing.php?id=$listing_id");
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>

