<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
include 'data_library.php'; // Ensure this file contains the arrays for statesCities and mediaTypes

function generateCode($length = 10) {
    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, $length);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = generateCode();
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $cities = isset($_POST['cities']) ? implode(',', $_POST['cities']) : '';
    $media_type = isset($_POST['media_type']) ? implode(',', $_POST['media_type']) : '';
    $buy_sell = mysqli_real_escape_string($conn, $_POST['buy_sell']);
    $user_id = $_SESSION['user_id'];
    $nationwide = isset($_POST['nationwide']) ? 1 : 0;
    
    $image_paths = [];
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/listing_images/";
            $image_path = $target_dir . time() . basename($_FILES["images"]["name"][$key]);
            if (move_uploaded_file($tmp_name, $image_path)) {
                $image_paths[] = $image_path;
            }
        }
    }
    $image_paths = implode(',', $image_paths);

    $sql = "INSERT INTO listings (user_id, code, title, description, state, cities, media_type, image_paths, nationwide, buy_sell) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("issssssisi", $user_id, $code, $title, $description, $state, $cities, $media_type, $image_paths, $nationwide, $buy_sell);
    if ($stmt->execute()) {
        header("Location: listings.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Listing</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .inline {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
        }
        #selected_cities div {
            margin-bottom: 5px;
            background-color: lightgrey;
            padding: 5px;
            border-radius: 5px;
        }
        .remove-icon {
            color: red;
            cursor: pointer;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="logo">Ad Connect</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="listings.php">Listings</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="create_listing.php" class="btn">Create Listing</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="container">
        <h1>Create New Listing</h1>
        <form action="create_listing.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <div class="inline">
                <label for="state">State:</label>
                <select id="state" name="state" onchange="populateCities(this.value);" required>
                    <option value="">Select State</option>
                    <?php foreach ($statesCities as $state => $cities): ?>
                        <option value="<?php echo htmlspecialchars($state); ?>"><?php echo htmlspecialchars($state); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="cities">Cities:</label>
                <select id="cities" name="cities[]" multiple onchange="updateSelectedCities();">
                </select>
            </div>

            <label for="selected_cities">Selected Cities:</label>
            <div id="selected_cities"></div>

            <label for="media_type">Media Type:</label>
            <select id="media_type" name="media_type[]" multiple>
                <?php foreach ($mediaTypes as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="buy_sell">Buying/Selling:</label>
            <select id="buy_sell" name="buy_sell" required>
                <option value="buying">Buying</option>
                <option value="selling">Selling</option>
            </select>

            <label for="images">Images:</label>
            <input type="file" id="images" name="images[]" multiple>

            <label for="nationwide">
                <input type="checkbox" id="nationwide" name="nationwide"> Nationwide
            </label>

            <button type="submit" class="btn">Create Listing</button>
        </form>
    </div>
</main>
<footer>
    <p>&copy; 2024 Ad Connect. All rights reserved.</p>
</footer>
<script>
const statesCities = <?php echo json_encode($statesCities); ?>;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('cities').addEventListener('change', updateSelectedCities);
});

function populateCities(state) {
    const citiesDropdown = document.getElementById('cities');
    citiesDropdown.innerHTML = '';
    const cities = statesCities[state] || [];
    cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        citiesDropdown.appendChild(option);
    });
}

function updateSelectedCities() {
    const selectedCitiesDiv = document.getElementById('selected_cities');
    const selectedOptions = Array.from(document.getElementById('cities').selectedOptions);
    selectedOptions.forEach(option => {
        if (!document.getElementById('city_' + option.value)) {
            const div = document.createElement('div');
            div.id = 'city_' + option.value;
            div.innerHTML = `<span class="remove-icon" onclick="removeCity('${option.value}')">&#x2716;</span>${option.textContent}, ${document.getElementById('state').value}`;
            selectedCitiesDiv.appendChild(div);
        }
    });
}

function removeCity(city) {
    const cityDiv = document.getElementById('city_' + city);
    if (cityDiv) {
        cityDiv.remove();
        const cityOption = Array.from(document.getElementById('cities').options).find(option => option.value === city);
        if (cityOption) {
            cityOption.selected = false;
        }
    }
}
</script>
</body>
</html>
