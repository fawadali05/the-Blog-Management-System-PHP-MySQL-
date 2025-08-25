<?php
require_once __DIR__ . '/../config.php';
requireAdmin();
require_once __DIR__ . '/../includes/admin_header.php';

$posts = $pdo->query("SELECT p.id, p.title, p.created_at, c.name AS category, u.name AS author
                      FROM posts p
                      LEFT JOIN categories c ON c.id=p.category_id
                      LEFT JOIN users u ON u.id=p.user_id
                      ORDER BY p.created_at DESC")->fetchAll();
?>
<div class="card">
  <div class="actions">
    <a class="btn" href="post-create.php">+ New Post</a>
  </div>
  <table>
    <thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($posts as $p): ?>
        <tr>
          <td><?= e($p['title']) ?></td>
          <td><?= e($p['category'] ?? '—') ?></td>
          <td><?= e($p['author'] ?? '—') ?></td>
          <td><?= date('Y-m-d', strtotime($p['created_at'])) ?></td>
          <td>
            <a href="post-edit.php?id=<?= $p['id'] ?>">Edit</a> |
            <a onclick="return confirm('Delete this post?')" href="post-delete.php?id=<?= $p['id'] ?>">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
