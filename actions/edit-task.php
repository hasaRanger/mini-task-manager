<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$priority = $_POST['priority'] ?? '';
$csrf = $_POST['csrf'] ?? '';

if (empty($id) || empty($csrf) || $csrf !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(400);
    echo 'Invalid request';
    exit;
}

if (strlen($title) < 3 || !in_array($priority, ['Low', 'Medium', 'High'], true)) {
    http_response_code(422);
    echo 'Validation failed';
    exit;
}

if (updateTask($pdo, $id, $title, $description, $priority)) {
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Failed';
}
exit;