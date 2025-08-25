<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/public_header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // check duplicate email
        $exists = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $exists->execute([':email' => $email]);
        if ($exists->fetch()) {
            $error = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:n,:e,:p,'user')");
            $stmt->execute([':n'=>$name, ':e'=>$email, ':p'=>$hash]);
            $_SESSION['user'] = ['id'=>$pdo->lastInsertId(),'name'=>$name,'email'=>$email,'role'=>'user'];
            header('Location: index.php'); exit;
        }
    }
}
?>

<div class="card">
  <h2>Create Account</h2>
  <form method="post" class="grid">
    <div>
      <label>Name</label>
      <input type="text" name="name" required>
    </div>
    <div>
      <label>Email</label>
      <input type="email" name="email" required>
    </div>
    <div>
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <div>
      <label>Confirm Password</label>
      <input type="password" name="confirm" required>
    </div>
    <?php if ($error): ?><p class="muted" style="color:#ffb4b4"><?= e($error) ?></p><?php endif; ?>
    <div class="actions">
      <button class="btn" type="submit">Register</button>
      <a class="btn secondary" href="login.php">I have an account</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
