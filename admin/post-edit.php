<?php
require_once __DIR__ . '/../config.php';
requireAdmin();
require_once __DIR__ . '/../includes/admin_header.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { echo "<p>Invalid post.</p>"; require_once __DIR__ . '/../includes/footer.php'; exit; }

$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
$stmt->execute([':id'=>$id]);
$post = $stmt->fetch();
if (!$post) { echo "<p>Post not found.</p>"; require_once __DIR__ . '/../includes/footer.php'; exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body  = trim($_POST['body'] ?? '');
    $cat   = intval($_POST['category_id'] ?? 0);
    $imageName = $post['image'];

    if ($title === '' || $body === '') {
        $error = 'Title and content are required.';
    } else {
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Invalid image type.';
            } else {
                $imageName = time() . '_' . preg_replace('/\s+/', '_', basename($_FILES['image']['name']));
                $dest = __DIR__ . '/../public/assets/uploads/' . $imageName;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $error = 'Failed to upload image.';
                }
            }
        }
    }

    if ($error === '') {
        $stmt = $pdo->prepare("UPDATE posts SET category_id=:cid, title=:t, body=:b, image=:img WHERE id=:id");
        $stmt->execute([
            ':cid' => $cat ?: null,
            ':t'   => $title,
            ':b'   => $body,
            ':img' => $imageName,
            ':id'  => $id
        ]);
        header('Location: posts.php'); exit;
    }
}
?>

<div class="card">
  <h2>Edit Post</h2>
  <?php if ($error): ?><p class="muted" style="color:#ffb4b4"><?= e($error) ?></p><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="grid">
    <div>
      <label>Title</label>
      <input type="text" name="title" value="<?= e($post['title']) ?>" required>
    </div>
    <div>
      <label>Category</label>
      <select name="category_id">
        <option value="0">— None —</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?= $c['id'] ?>" <?= intval($post['category_id'])===intval($c['id'])?'selected':''; ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Image</label>
      <?php if (!empty($post['image'])): ?>
        <p>Current: <span class="muted"><?= e($post['image']) ?></span></p>
      <?php endif; ?>
      <input type="file" name="image" accept="image/*">
    </div>
    <div>
      <label>Content</label>
      <textarea name="body" rows="10"><?= e($post['body']) ?></textarea>
    </div>
    <div class="actions">
      <button class="btn" type="submit">Save</button>
      <a class="btn secondary" href="posts.php">Cancel</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
