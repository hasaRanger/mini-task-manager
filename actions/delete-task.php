<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$csrf = $_POST['csrf'] ?? '';

if (empty($id) || empty($csrf) || $csrf !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(400);
    echo 'Invalid request';
    exit;
}

if (deleteTask($pdo, $id)) {
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Failed';
}
exit;
?>