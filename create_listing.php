<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $state = $_POST['state'];
  $cities = implode(',', $_POST['cities']);
  $media_type = $_POST['media_type'];
  $user_id = $_SESSION['user_id'];
  $nationwide = isset($_POST['nationwide']) ? 1 : 0;

  // Handle file upload
  $image_path = NULL;
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target_dir = "uploads/";
    $image_path = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
      $image_path = $target_dir . basename($_FILES["image"]["name"]);
    } else {
      echo "Error uploading file.";
    }
  } else {
    echo "No file uploaded or upload error.";
  }

  $sql = "INSERT INTO listings (user_id, title, description, state, cities, media_type, image_path, nationwide) VALUES ('$user_id', '$title', '$description', '$state', '$cities', '$media_type', '$image_path', '$nationwide')";

  if ($conn->query($sql) === TRUE) {
    header("Location: dashboard.php");
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>
