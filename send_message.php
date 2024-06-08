<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $sender_id = $_SESSION['user_id'];
  $receiver_id = $_POST['receiver_id'];
  $listing_id = $_POST['listing_id'];
  $message = $_POST['message'];

  $sql = "INSERT INTO messages (sender_id, receiver_id, listing_id, message) VALUES ('$sender_id', '$receiver_id', '$listing_id', '$message')";

  if ($conn->query($sql) === TRUE) {
    header("Location: listing.php?id=$listing_id");
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>
