<?php /* includes/public_header.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Blog</title>
  <link rel="stylesheet" href="./assets/style.css"/>
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1><a href="index.php">My Blog</a></h1>
      <nav>
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION['user'])): ?>
          <span class="welcome">Hi, <?= e($_SESSION['user']['name']) ?></span>
          <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
            <a href="../admin/index.php">Admin</a>
          <?php endif; ?>
          <a href="logout.php">Logout</a>
        <?php else: ?>
          <a href="login.php">Login</a>
          <a href="register.php">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="container">
