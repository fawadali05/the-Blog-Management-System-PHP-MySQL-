<?php
// config.php — DB connection, session, and helpers
session_start();

// Update these if your MySQL credentials differ (XAMPP defaults work):
$DB_HOST = 'localhost';
$DB_NAME = 'blog_db';
$DB_USER = 'root';
$DB_PASS = '';

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
function isLoggedIn() { return isset($_SESSION['user']); }
function isAdmin() { return isLoggedIn() && ($_SESSION['user']['role'] ?? '') === 'admin'; }
function requireLogin() { if (!isLoggedIn()) { header('Location: ../public/login.php'); exit; } }
function requireAdmin() { if (!isAdmin()) { header('Location: ../public/index.php'); exit; } }
?>
