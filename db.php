<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ad_connect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create users table if not exists
$createUsersTable = "
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";

if ($conn->query($createUsersTable) === FALSE) {
    die("Error creating users table: " . $conn->error);
}

// Create listings table if not exists
$createListingsTable = "
CREATE TABLE IF NOT EXISTS `listings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `code` VARCHAR(20) NOT NULL UNIQUE,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `state` VARCHAR(50) NOT NULL,
    `cities` TEXT,
    `media_type` TEXT,
    `image_paths` TEXT,
    `nationwide` TINYINT(1) DEFAULT 0,
    `buy_sell` VARCHAR(10) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;
";

if ($conn->query($createListingsTable) === FALSE) {
    die("Error creating listings table: " . $conn->error);
}

// Create table for media types if not exists
$createMediaTypesTable = "
CREATE TABLE IF NOT EXISTS `media_types` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;
";

if ($conn->query($createMediaTypesTable) === FALSE) {
    die("Error creating media types table: " . $conn->error);
}

// Insert default media types if not exists
$defaultMediaTypes = ["Billboards", "Word of Mouth", "Bumper Stickers", "Wearing Shirts", "Influencer Recommendations"];
foreach ($defaultMediaTypes as $type) {
    $stmt = $conn->prepare("INSERT IGNORE INTO `media_types` (`type`) VALUES (?)");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $stmt->close();
}

?>
