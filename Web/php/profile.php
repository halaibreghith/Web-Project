<?php
session_start();
require_once 'db.php.inc';

// Check if the customer is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == 0) {
    header("Location: login.php");
    exit;
}

// Fetch customer details
$query = "SELECT user_id, name, flat, street, city, country, dob, idnumber, email, telephone, qualification, role, skills, username FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$customer = $stmt->fetch();

$username_err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = trim($_POST['username']);
    $check_query = "SELECT user_id FROM users WHERE username = :username AND user_id != :user_id";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->execute(['username' => $new_username, 'user_id' => $_SESSION['user_id']]);

    // In case the new username is already taken
    if ($check_stmt->rowCount() > 0) {
        $username_err = "This username is already taken.";
    } else {
        // Update customer details
        $update_query = "UPDATE users SET name = :name, flat = :flat, street = :street, city = :city, country = :country, dob = :dob, idnumber = :idnumber, email = :email, telephone = :telephone, role = :role, qualification = :qualification, skills = :skills, username = :username WHERE user_id = :user_id";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([
            'name' => $_POST['name'],
            'flat' => $_POST['flat'],
            'street' => $_POST['street'],
            'city' => $_POST['city'],
            'country' => $_POST['country'],
            'dob' => $_POST['dob'],
            'idnumber' => $_POST['idnumber'],
            'email' => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'role' => $_POST['role'],
            'qualification' => $_POST['qualification'],
            'skills' => $_POST['skills'],
            'username' => $new_username,
            'user_id' => $_SESSION['user_id']
        ]);

        // Update the session username
        $_SESSION['username'] = $new_username;

        header("Location: profile.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['username']; ?> Profile | Task Allocator Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <?php require_once 'helper.php';
    generateHeader(); ?>

    <div class="container">
         <nav class="sidebar">
        <ul>
            <li><a href="home.php">Home</a></li>

            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) : ?>
                <li><a href="login.php">Sign up / in</a></li>
            <?php endif; ?>

            <li><a href="home.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="profile.php">Profile</a></li>

            <?php if (isset($_SESSION['role'])) : ?>
                <?php if ($_SESSION['role'] == 'Manager') : ?>
                    <li>
                        <p>Manager options:</p>
                    </li>
                    <li><a href="addProject.php">Add Project</a></li>
                    <li><a href="viewProjects.php">View Projects</a></li>
                   

                <?php elseif ($_SESSION['role'] == 'Project Leader') : ?>
                    <li>
                        <p>Project Leader options:</p>
                    </li>
                    <li><a href="assignTask.php">Assign Tasks</a></li>
                    <li><a href="assignTeamMember.php">Assign Team Member</a></li>
                    <li><a href="teamOverview.php">Team Overview</a></li>

                <?php elseif ($_SESSION['role'] == 'Team Member') : ?>
                    <li>
                        <p>Team Member options:</p>
                    </li>
                    <li><a href="viewTasks.php">View Tasks</a></li>
                    <li><a href="submit.php">Submit Progress</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>

        <main class="content">
            <h1>Customer Profile</h1>

            <form action="profile.php" method="POST">
                <span class="help-block"><?php echo $username_err; ?></span>

                <label for="user_id">Customer ID:</label>
                <input type="text" id="user_id" name="user_id" value="<?php echo $customer['user_id']; ?>" readonly>

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $customer['name']; ?>" required>

                <label for="flat">Flat:</label>
                <input type="text" id="flat" name="flat" value="<?php echo $customer['flat']; ?>" required>

                <label for="street">Street:</label>
                <input type="text" id="street" name="street" value="<?php echo $customer['street']; ?>" required>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $customer['city']; ?>" required>

                <label for="country">Country:</label>
                <input type="text" id="country" name="country" value="<?php echo $customer['country']; ?>" required>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo $customer['dob']; ?>" required>

                <label for="idnumber">ID Number:</label>
                <input type="text" id="idnumber" name="idnumber" value="<?php echo $customer['idnumber']; ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $customer['email']; ?>" required>

                <label for="telephone">Telephone:</label>
                <input type="tel" id="telephone" name="telephone" value="<?php echo $customer['telephone']; ?>" required>

                <label for="qualification">qualification:</label>
                <input type="text" id="qualification" name="qualification" value="<?php echo $customer['qualification']; ?>" required>

                <label for="skills">skills:</label>
                <input type="text" id="skills" name="skills" value="<?php echo $customer['skills']; ?>" required>

                <label for="role">role:</label>
                <input type="text" id="role" name="role" value="<?php echo $customer['role']; ?>" required>

            

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $customer['username']; ?>" required>

                <button type="submit">Update Profile</button>
            </form>
        </main>
    </div>
    <?php generateFooter(); ?>
</body>

</html>