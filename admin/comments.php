<?php
require_once __DIR__ . '/../config.php';
requireAdmin();
require_once __DIR__ . '/../includes/admin_header.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM comments WHERE id=:id")->execute([':id'=>$id]);
}

if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $row = $pdo->prepare("SELECT status FROM comments WHERE id=:id");
    $row->execute([':id'=>$id]);
    if ($r = $row->fetch()) {
        $new = $r['status'] === 'approved' ? 'pending' : 'approved';
        $pdo->prepare("UPDATE comments SET status=:s WHERE id=:id")->execute([':s'=>$new, ':id'=>$id]);
    }
}

$comments = $pdo->query("SELECT c.id, c.body, c.status, c.created_at, p.title AS post_title, u.name AS author
                         FROM comments c
                         LEFT JOIN posts p ON p.id=c.post_id
                         LEFT JOIN users u ON u.id=c.user_id
                         ORDER BY c.created_at DESC LIMIT 200")->fetchAll();
?>
<div class="card">
  <h3>Comments</h3>
  <table>
    <thead><tr><th>Post</th><th>Author</th><th>Excerpt</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($comments as $c): ?>
        <tr>
          <td><?= e($c['post_title'] ?? '—') ?></td>
          <td><?= e($c['author'] ?? '—') ?></td>
          <td><?= e(mb_strimwidth($c['body'],0,60,'…')) ?></td>
          <td><span class="tag"><?= e($c['status']) ?></span></td>
          <td><?= date('Y-m-d H:i', strtotime($c['created_at'])) ?></td>
          <td>
            <a href="comments.php?toggle=<?= $c['id'] ?>">Toggle</a> |
            <a onclick="return confirm('Delete comment?')" href="comments.php?delete=<?= $c['id'] ?>">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
