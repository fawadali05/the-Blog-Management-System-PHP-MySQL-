<?php
require_once __DIR__ . '/../config.php';
requireAdmin();
require_once __DIR__ . '/../includes/admin_header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $error = 'Name is required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (:n, :s)");
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
        $stmt->execute([':n'=>$name, ':s'=>$slug]);
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM categories WHERE id=:id")->execute([':id'=>$id]);
}

$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<div class="grid cols-2">
  <div class="card">
    <h3>Add Category</h3>
    <?php if ($error): ?><p class="muted" style="color:#ffb4b4"><?= e($error) ?></p><?php endif; ?>
    <form method="post">
      <label>Name</label>
      <input type="text" name="name" required>
      <div class="actions"><button class="btn" type="submit">Add</button></div>
    </form>
  </div>

  <div class="card">
    <h3>All Categories</h3>
    <table>
      <thead><tr><th>Name</th><th>Slug</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($cats as $c): ?>
          <tr>
            <td><?= e($c['name']) ?></td>
            <td><span class="tag"><?= e($c['slug']) ?></span></td>
            <td><a onclick="return confirm('Delete?')" href="categories.php?delete=<?= $c['id'] ?>">Delete</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
