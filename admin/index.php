<?php
require_once __DIR__ . '/../config.php';
requireAdmin();
require_once __DIR__ . '/../includes/admin_header.php';

$post_count = $pdo->query("SELECT COUNT(*) AS c FROM posts")->fetch()['c'] ?? 0;
$user_count = $pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c'] ?? 0;
$comment_count = $pdo->query("SELECT COUNT(*) AS c FROM comments")->fetch()['c'] ?? 0;
$cat_count = $pdo->query("SELECT COUNT(*) AS c FROM categories")->fetch()['c'] ?? 0;
?>

<div class="grid cols-2">
  <div class="card"><h3>Total Posts</h3><p class="muted"><?= $post_count ?></p></div>
  <div class="card"><h3>Total Users</h3><p class="muted"><?= $user_count ?></p></div>
  <div class="card"><h3>Total Comments</h3><p class="muted"><?= $comment_count ?></p></div>
  <div class="card"><h3>Total Categories</h3><p class="muted"><?= $cat_count ?></p></div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
