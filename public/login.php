<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/public_header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role']];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
?>

<div class="card">
  <h2>Login</h2>
  <form method="post" class="grid">
    <div>
      <label>Email</label>
      <input type="email" name="email" required>
    </div>
    <div>
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <?php if ($error): ?><p class="muted" style="color:#ffb4b4"><?= e($error) ?></p><?php endif; ?>
    <div class="actions">
      <button class="btn" type="submit">Login</button>
      <a class="btn secondary" href="register.php">Create account</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
