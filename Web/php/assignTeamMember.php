<?php
include 'db.php.inc';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    $contribution_percentage = $_POST['contribution_percentage'];

    $stmt = $pdo->prepare("SELECT SUM(contribution_percentage) as total FROM user_tasks WHERE task_id = :task_id");
    $stmt->execute([':task_id' => $task_id]);
    $total = $stmt->fetch()['total'] + $contribution_percentage;

    if ($total > 100) {
        echo "Total contribution exceeds 100%!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO user_tasks (task_id, user_id, role, contribution_percentage) VALUES (:task_id, :user_id, :role, :contribution_percentage)");
        $stmt->execute([
            ':task_id' => $task_id,
            ':user_id' => $user_id,
            ':role' => $role,
            ':contribution_percentage' => $contribution_percentage
        ]);

        echo "Team member assigned successfully!";
    }
}
?>

<?php
// Fetch tasks and team members
$tasks = $pdo->query("SELECT id, name FROM tasks")->fetchAll();
$users = $pdo->query("SELECT id, name FROM users WHERE role = 'Team Member'")->fetchAll();
?>
<form method="POST">
    <select name="task_id" required>
        <option value="">Select Task</option>
        <?php foreach ($tasks as $task): ?>
            <option value="<?= $task['id'] ?>"><?= $task['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <select name="user_id" required>
        <option value="">Select Team Member</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <select name="role" required>
        <option value="Developer">Developer</option>
        <option value="Designer">Designer</option>
        <option value="Tester">Tester</option>
        <option value="Analyst">Analyst</option>
        <option value="Support">Support</option>
    </select>
    <input type="number" name="contribution_percentage" placeholder="Contribution (%)" required>
    <button type="submit">Assign Team Member</button>
</form>
