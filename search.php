<?phpinclude 'db.php';$search = '';if (isset($_GET['search'])) {  $search = $_GET['search'];}$sql = "SELECT * FROM listings WHERE title LIKE '%$search%' OR description LIKE '%$search%'";$result = $conn->query($sql);?><!DOCTYPE html><html lang="en"><head>  <meta charset="UTF-8">  <meta name="viewport" content="width=device-width, initial-scale=1.0">  <title>Search Listings</title></head><body>  <h1>Search Listings</h1>  <form action="search.php" method="GET">    <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search..."><br>    <input type="submit" value="Search">  </form>  <?php  if ($result->num_rows > 0) {    while($row = $result->fetch_assoc()) {      echo "<h2>" . $row['title'] . "</h2>";      echo "<p>" . $row['description'] . "</p>";    }  } else {    echo "No listings found";  }  ?>  <a href="dashboard.php">Back to Dashboard</a></body></html><?php$conn->close();?>