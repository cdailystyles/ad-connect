<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ad Connect - Login/Register</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <header>
    <nav>
      <div class="logo">Ad Connect</div>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="listings.html">Listings</a></li>
        <li><a href="create_listing.html" class="btn btn-primary">Create Listing</a></li>
      </ul>
    </nav>
  </header>
  <main>
    <section class="login-register">
      <div class="form-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>
      </div>
      <div class="form-container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
          <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Register</button>
        </form>
      </div>
    </section>
  </main>
  <footer>
    <p>&copy; 2024 Ad Connect. All rights reserved.</p>
  </footer>
</body>
</html>
