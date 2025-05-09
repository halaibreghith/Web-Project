<?php
require_once 'db.php.inc';
session_start();

// Validate Project Leader
if ($_SESSION['role'] !== 'Project Leader') {
    die("Access denied. Only Project Leaders can add tasks.");
}

// Add Task
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $taskName = $_POST['task_name'];
        $description = $_POST['description'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $priority = $_POST['priority'];
		$effort = $_POST['effort'];
		$status = $_POST['status'];
        $projectId = $_POST['project_id'];

        // check dates
        if (strtotime($startDate) > strtotime($endDate)) {
            throw new Exception("Start date cannot be after end date.");
        }

        // add task to data base
        $stmt = $pdo->prepare("INSERT INTO tasks (task_name, description, start_date, end_date, priority, effort, status, project_id) 
                               VALUES (:task_name, :description, :start_date, :end_date, :priority, :effort, :status, :project_id)");
        $stmt->execute([
            ':task_name' => $taskName,
            ':description' => $description,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':priority' => $priority,
			':effort' => $effort,
			':status' => $status,
            ':project_id' => $projectId
        ]);

        $successMessage = "Task added successfully!";
    } catch (Exception $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <div class="container">
        <h1>Add Task</h1>
        <?php if (isset($successMessage)) : ?>
            <p class="message"><?= htmlspecialchars($successMessage) ?></p>
        <?php elseif (isset($errorMessage)) : ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <form method="POST" action="add_task.php">
            <label for="task_name">Task Name:</label>
            <input type="text" id="task_name" name="task_name" required><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea><br>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required><br>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required><br>

            <label for="priority">Priority:</label>
            <select id="priority" name="priority">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select><br>

            <label for="project_id">Select Project:</label>
            <select id="project_id" name="project_id" required>
                <option value="">-- Select Project --</option>
                <?php foreach ($projects as $project) : ?>
                    <option value="<?= htmlspecialchars($project['project_id']) ?>"><?= htmlspecialchars($project['title']) ?></option>
                <?php endforeach; ?>
            </select><br>

            <input type="submit" value="Add Task">
        </form>
    </div>
</body>

</html>


