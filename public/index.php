<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/public_header.php';

// Fetch categories
$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Filters
$search = trim($_GET['q'] ?? '');
$cat_id = intval($_GET['cat'] ?? 0);

$sql = "SELECT p.*, c.name AS category_name, u.name AS author 
        FROM posts p
        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN users u ON u.id = p.user_id
        WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (p.title LIKE :q OR p.body LIKE :q)";
    $params[':q'] = "%{$search}%";
}
if ($cat_id > 0) {
    $sql .= " AND p.category_id = :cat";
    $params[':cat'] = $cat_id;
}
$sql .= " ORDER BY p.created_at DESC LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>

<div class="card">
  <form method="get" class="grid cols-2">
    <div>
      <label for="q">Search</label>
      <input type="text" id="q" name="q" value="<?= e($search) ?>" placeholder="Search posts...">
    </div>
    <div>
      <label for="cat">Category</label>
      <select id="cat" name="cat">
        <option value="0">All</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $cat_id==intval($c['id'])?'selected':''; ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="actions">
      <button class="btn" type="submit">Filter</button>
      <?php if (isAdmin()): ?>
        <a class="btn secondary" href="../admin/post-create.php">+ New Post</a>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php if (!$posts): ?>
  <p class="muted">No posts found.</p>
<?php endif; ?>

<?php foreach ($posts as $p): ?>
  <article class="card">
    <?php if (!empty($p['image'])): ?>
      <img class="post-thumb" src="./assets/uploads/<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>">
    <?php endif; ?>
    <h2><a href="post.php?id=<?= $p['id'] ?>"><?= e($p['title']) ?></a></h2>
    <p class="muted">In <span class="tag"><?= e($p['category_name'] ?? 'Uncategorized') ?></span> • by <?= e($p['author'] ?? 'Unknown') ?> • <?= date('M j, Y', strtotime($p['created_at'])) ?></p>
    <p><?= e(mb_strimwidth(strip_tags($p['body']), 0, 220, '…')) ?></p>
    <a class="btn secondary" href="post.php?id=<?= $p['id'] ?>">Read more</a>
  </article>
<?php endforeach; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
