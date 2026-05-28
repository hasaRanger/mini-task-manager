<?php
// First include database connection
require_once 'includes/db.php';
require_once 'includes/functions.php';

include 'header.html';
// Now $pdo is available
$tasks = getAllTasks($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <title>TMS | Task Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container py-4">
        <!-- Add Task Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Add New Task</h5>
            </div>
            <div class="card-body">
                <form id="taskForm" method="POST" action="actions/add-task.php">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <input type="text" name="title" class="form-control" placeholder="Task Title" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <select name="priority" class="form-select" required>
                                <option value="">Select Priority</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">Add Task</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea name="description" class="form-control" rows="2" placeholder="Description (optional)"></textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">All Tasks</h5>
                <div class="d-flex align-items-center gap-2 flex-row">
                    <select id="priorityFilter" class="form-select form-select-sm" style="min-width: 160px;">
                        <option value="all">All priorities</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                    <span class="badge bg-secondary" id="taskCount"><?= count($tasks) ?></span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tasksTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tasks as $task): ?>
                            <tr data-id="<?= $task['id'] ?>" data-priority="<?= htmlspecialchars($task['priority']) ?>" data-title="<?= htmlspecialchars($task['title']) ?>" data-description="<?= htmlspecialchars($task['description'] ?? '') ?>">
                                <td><?= htmlspecialchars($task['title']) ?></td>
                                <td><?= htmlspecialchars($task['description'] ?? '-') ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $task['priority'] == 'High' ? 'bg-danger' : ($task['priority'] == 'Medium' ? 'bg-warning' : 'bg-success') ?>">
                                        <?= $task['priority'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $task['status'] == 'Completed' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= $task['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn me-1" data-bs-toggle="modal" data-bs-target="#editTaskModal">Edit</button>
                                    <?php if($task['status'] == 'Pending'): ?>
                                        <button class="btn btn-sm btn-success update-btn">Complete</button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Task Modal -->
        <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editTaskForm" method="POST" action="actions/edit-task.php">
                        <div class="modal-body">
                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <input type="hidden" name="id" id="editTaskId">

                            <div class="mb-3">
                                <label class="form-label" for="editTaskTitle">Title</label>
                                <input type="text" class="form-control" id="editTaskTitle" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="editTaskPriority">Priority</label>
                                <select class="form-select" id="editTaskPriority" name="priority" required>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="editTaskDescription">Description</label>
                                <textarea class="form-control" id="editTaskDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>