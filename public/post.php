<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/public_header.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { echo "<p>Invalid post.</p>"; require_once __DIR__ . '/../includes/footer.php'; exit; }

// Fetch post
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, u.name AS author
                       FROM posts p
                       LEFT JOIN categories c ON c.id = p.category_id
                       LEFT JOIN users u ON u.id = p.user_id
                       WHERE p.id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if (!$post) { echo "<p>Post not found.</p>"; require_once __DIR__ . '/../includes/footer.php'; exit; }

// Handle new comment
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $body = trim($_POST['body'] ?? '');
    if ($body === '') {
        $error = 'Comment cannot be empty.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, body, status) VALUES (:pid, :uid, :body, 'approved')");
        $stmt->execute([
            ':pid' => $id,
            ':uid' => $_SESSION['user']['id'],
            ':body' => $body
        ]);
    }
}

// Fetch comments (approved only)
$comments = $pdo->prepare("SELECT c.*, u.name AS author FROM comments c LEFT JOIN users u ON u.id=c.user_id WHERE c.post_id = :id AND c.status='approved' ORDER BY c.created_at DESC");
$comments->execute([':id' => $id]);
$comments = $comments->fetchAll();
?>

<article class="card">
  <?php if (!empty($post['image'])): ?>
    <img class="post-thumb" src="./assets/uploads/<?= e($post['image']) ?>" alt="<?= e($post['title']) ?>">
  <?php endif; ?>
  <h2><?= e($post['title']) ?></h2>
  <p class="muted">In <span class="tag"><?= e($post['category_name'] ?? 'Uncategorized') ?></span> • by <?= e($post['author'] ?? 'Unknown') ?> • <?= date('M j, Y', strtotime($post['created_at'])) ?></p>
  <div><?= nl2br(e($post['body'])) ?></div>
</article>

<section class="card">
  <h3>Comments (<?= count($comments) ?>)</h3>
  <?php if (!$comments): ?>
    <p class="muted">Be the first to comment.</p>
  <?php else: ?>
    <?php foreach ($comments as $c): ?>
      <div class="card" style="margin-bottom:10px;">
        <p class="muted"><?= e($c['author'] ?? 'User') ?> • <?= date('M j, Y H:i', strtotime($c['created_at'])) ?></p>
        <p><?= nl2br(e($c['body'])) ?></p>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (isLoggedIn()): ?>
    <form method="post">
      <label for="body">Add a comment</label>
      <textarea id="body" name="body" rows="3" placeholder="Write something..."></textarea>
      <?php if ($error): ?><p class="muted" style="color:#ffb4b4"><?= e($error) ?></p><?php endif; ?>
      <div class="actions">
        <button class="btn" type="submit">Post Comment</button>
      </div>
    </form>
  <?php else: ?>
    <p class="muted">Please <a href="login.php">log in</a> to comment.</p>
  <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
