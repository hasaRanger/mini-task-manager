<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (empty($_POST['csrf']) || ($_POST['csrf'] !== ($_SESSION['csrf_token'] ?? ''))) {
        header("Location: ../index.php?error=csrf");
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = $_POST['priority'] ?? '';

    if(strlen($title) >= 3 && in_array($priority, ['Low','Medium','High'])) {
        if(addTask($pdo, $title, $description, $priority)) {
            header("Location: ../index.php?success=1");
        } else {
            header("Location: ../index.php?error=1");
        }
    } else {
        header("Location: ../index.php?error=1");
    }
    exit;
}
?>