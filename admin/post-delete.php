<?php
require_once __DIR__ . '/../config.php';
requireAdmin();
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id=:id");
    $stmt->execute([':id'=>$id]);
}
header('Location: posts.php'); exit;
