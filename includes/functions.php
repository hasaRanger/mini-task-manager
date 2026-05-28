<?php
require_once __DIR__ . '/db.php';

function getAllTasks($pdo)
{
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addTask($pdo, $title, $description, $priority)
{
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description, priority) VALUES (:title, :description, :priority)");
    return $stmt->execute(['title' => $title, 'description' => $description, 'priority' => $priority]);
}

function updateTask($pdo, $id, $title, $description, $priority)
{
    $stmt = $pdo->prepare("UPDATE tasks SET title = :title, description = :description, priority = :priority WHERE id = :id");
    return $stmt->execute([
        'title' => $title,
        'description' => $description,
        'priority' => $priority,
        'id' => $id,
    ]);
}

function updateTaskStatus($pdo, $id)
{
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'Completed' WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function deleteTask($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
?>