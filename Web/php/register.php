<?php
session_start();
require_once 'db.php.inc'; 

function usernameExists($pdo, $username) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetchColumn() > 0;
}

if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 1;
}

$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $step = isset($_POST['step']) ? intval($_POST['step']) : 1;

    if ($step == 1) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['flat'] = $_POST['flat'];
        $_SESSION['street'] = $_POST['street'];
        $_SESSION['city'] = $_POST['city'];
        $_SESSION['country'] = $_POST['country'];
        $_SESSION['dob'] = $_POST['dob'];
        $_SESSION['idnumber'] = $_POST['idnumber'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['telephone'] = $_POST['telephone'];
        $_SESSION['role'] = $_POST['role'];
        $_SESSION['qualification'] = $_POST['qualification'];
        $_SESSION['skills'] = $_POST['skills'];
        $_SESSION['step'] = 2;

        header("Location: register.php");
        exit();
    } elseif ($step == 2) {
        $username = $_POST['username'];
        if (usernameExists($pdo, $username)) {
            $_SESSION['error_message'] = "Username already taken. Please choose a different username.";
            $_SESSION['step'] = 2;
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $_SESSION['step'] = 3;
        }
        header("Location: register.php");
        exit();
    } elseif ($step == 3) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, flat, street, city, country, dob, idnumber, email, telephone, role, qualification, skills, username, password) 
                                   VALUES (:name, :flat, :street, :city, :country, :dob, :idnumber, :email, :telephone, :role, :qualification, :skills, :username, :password)");

            $stmt->execute([
                ':name' => $_SESSION['name'],
                ':flat' => $_SESSION['flat'],
                ':street' => $_SESSION['street'],
                ':city' => $_SESSION['city'],
                ':country' => $_SESSION['country'],
                ':dob' => $_SESSION['dob'],
                ':idnumber' => $_SESSION['idnumber'],
                ':email' => $_SESSION['email'],
                ':telephone' => $_SESSION['telephone'],
                ':role' => $_SESSION['role'],
                ':qualification' => $_SESSION['qualification'],
                ':skills' => $_SESSION['skills'],
                ':username' => $_SESSION['username'],
                ':password' => $_SESSION['password']
            ]);

            $userId = $pdo->lastInsertId();
            session_unset(); // تنظيف الجلسة بعد الإكمال
            echo "Registration Complete! Your User ID is: $userId. <a href='login.php'>Go to Login</a>";
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration | Task Allocator Pro</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <?php require_once 'helper.php'; generateHeader(); ?>
    <div class="container">
        <main class="content">
            <!-- Step 1: Customer Information -->
            <form method="POST" action="register.php">
                <div <?php echo ($_SESSION['step'] == 1) ? '' : 'class="hidden"'; ?>>
                    <h2>Step 1: Customer Information</h2>
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo $_SESSION['name'] ?? ''; ?>" required><br>
                    <label>Address:</label>
                    <input type="text" name="flat" placeholder="Flat/House No" value="<?php echo $_SESSION['flat'] ?? ''; ?>" required>
                    <input type="text" name="street" placeholder="Street" value="<?php echo $_SESSION['street'] ?? ''; ?>" required>
                    <input type="text" name="city" placeholder="City" value="<?php echo $_SESSION['city'] ?? ''; ?>" required>
                    <input type="text" name="country" placeholder="Country" value="<?php echo $_SESSION['country'] ?? ''; ?>" required><br>
                    <label>Date of Birth:</label>
                    <input type="date" name="dob" value="<?php echo $_SESSION['dob'] ?? ''; ?>" required><br>
                    <label>ID Number:</label>
                    <input type="text" name="idnumber" value="<?php echo $_SESSION['idnumber'] ?? ''; ?>" required><br>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo $_SESSION['email'] ?? ''; ?>" required><br>
                    <label>Telephone:</label>
                    <input type="tel" name="telephone" value="<?php echo $_SESSION['telephone'] ?? ''; ?>" required><br>
                    <label>Role:</label>
                    <select name="role" required>
                        <option value="">Select Role</option>
                        <option value="Manager" <?php echo ($_SESSION['role'] ?? '') === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                        <option value="Project Leader" <?php echo ($_SESSION['role'] ?? '') === 'Project Leader' ? 'selected' : ''; ?>>Project Leader</option>
                        <option value="Team Member" <?php echo ($_SESSION['role'] ?? '') === 'Team Member' ? 'selected' : ''; ?>>Team Member</option>
                    </select><br>
                    <label>Qualification:</label>
                    <input type="text" name="qualification" value="<?php echo $_SESSION['qualification'] ?? ''; ?>" required><br>
                    <label>Skills:</label>
                    <input type="text" name="skills" value="<?php echo $_SESSION['skills'] ?? ''; ?>" required><br>
                    <button type="submit" name="step" value="1">Next</button>
                </div>
            </form>

            <!-- Step 2: Create Account -->
            <form method="POST" action="register.php">
                <div <?php echo ($_SESSION['step'] == 2) ? '' : 'class="hidden"'; ?>>
                    <?php if ($error_message) echo "<p class='error'>$error_message</p>"; ?>
                    <h2>Step 2: Create E-Account</h2>
                    <label>Username:</label>
                    <input type="text" name="username" minlength="6" maxlength="13" value="<?php echo $_SESSION['username'] ?? ''; ?>" required><br>
                    <label>Password:</label>
                    <input type="password" name="password" minlength="8" maxlength="12" required><br>
                    <button type="submit" name="step" value="2">Next</button>
                </div>
            </form>

            <!-- Step 3: Confirmation -->
            <form method="POST" action="register.php">
                <div <?php echo ($_SESSION['step'] == 3) ? '' : 'class="hidden"'; ?>>
                    <h2>Step 3: Confirmation</h2>
                    <p><strong>Name:</strong> <?php echo $_SESSION['name'] ?? 'N/A'; ?></p>
                    <p><strong>Address:</strong> <?php echo ($_SESSION['flat'] ?? 'N/A') . ', ' . ($_SESSION['street'] ?? 'N/A') . ', ' . ($_SESSION['city'] ?? 'N/A') . ', ' . ($_SESSION['country'] ?? 'N/A'); ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo $_SESSION['dob'] ?? 'N/A'; ?></p>
                    <p><strong>ID Number:</strong> <?php echo $_SESSION['idnumber'] ?? 'N/A'; ?></p>
                    <p><strong>Email:</strong> <?php echo $_SESSION['email'] ?? 'N/A'; ?></p>
                    <p><strong>Role:</strong> <?php echo $_SESSION['role'] ?? 'N/A'; ?></p>
                    <button type="submit" name="step" value="3">Confirm</button>
                </div>
            </form>
        </main>
    </div>
    <?php generateFooter(); ?>
</body>
</html>
