<?php
session_start();
require_once 'db.php.inc';

// Variables to store input and error messages
$username = $password = '';
$username_err = $password_err = $login_err = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST['username']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST['password']);
    }

    // Check input errors before querying the database
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT username, password, role, user_id, name FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':username', $param_username);

            $param_username = $username;

            if ($stmt->execute()) {
                // Check if username exists, if yes then verify password
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $hashed_password = $row['password'];
                        if ($password === $row['password']) {
    
                            $_SESSION['name'] = $row['name'];
                            $_SESSION['username'] = $username;
                            $_SESSION['loggedin'] = true;
                            $_SESSION['role'] = $row['role'];
                            $_SESSION['user_id'] = $row['user_id'];

                            header("location: profile.php");
                            exit();
        } else {
                $login_err = "Invalid username or password.";
}

                    }
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                $login_err = "Oops! Something went wrong. Please try again later.";
            }

            unset($stmt);
        }
    }
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Task Allocator Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <?php require_once 'helper.php';
    generateHeader(); ?>

    <div class="container">
        <nav class="sidebar">
    <ul>
        <!-- Home link -->
        <li><a href="dashboard.php">Home</a></li>

        
        <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) : ?>
            <li><a href="login.php">Sign up / in</a></li>
        <?php endif; ?>

        <li><a href="profile.php">Profile</a></li>
        <li><a href="about_us.php">About Us</a></li>

        
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Manager') : ?>
            <li>
                <p>Manager Options:</p>
                <ul>
                    <li><a href="addProject.php">Add Project</a></li>
                    <li><a href="manage_projects.php">Manage Projects</a></li>
                    <li><a href="view_all_users.php">View All Users</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Project Leader') : ?>
            <li>
                <p>Project Leader Options:</p>
                <ul>
                    <li><a href="addTask.php">Add Task</a></li>
                    <li><a href="view_tasks.php">View Tasks</a></li>
                    <li><a href="assign_team_members.php">Assign Team Members</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Team Member') : ?>
            <li>
                <p>Team Member Options:</p>
                <ul>
                    <li><a href="view_assigned_tasks.php">My Tasks</a></li>
                    <li><a href="submit_task_progress.php">Submit Task Progress</a></li>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</nav>


        <main class="content">
            <h2>Login</h2>
            <?php
            // Display login error message if set
            if (!empty($login_err)) {
                echo '<div class="alert">' . $login_err . '</div>';
            }
            ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
                <p>Don't have an account? <a href="register.php">Sign up.</a>.</p>
            </form>
        </main>
    </div>

    <?php generateFooter(); ?>
</body>

</html>