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
  $city = $_POST['city'];
  $media_type = $_POST['media_type'];
  $user_id = $_SESSION['user_id'];

  // Handle file upload
  $image_path = NULL;
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target_dir = "uploads/";
    $image_path = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
  }

  $sql = "INSERT INTO listings (user_id, title, description, city, media_type, image_path) VALUES ('$user_id', '$title', '$description', '$city', '$media_type', '$image_path')";

  if ($conn->query($sql) === TRUE) {
    header("Location: dashboard.php");
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>
