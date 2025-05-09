<?php
session_start();

function generateHeader()
{
    $isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true;

    echo '<header>
        <div class="logo-container">
            <a href="home.php">
                <img src="../images/task.jpg" alt="logo" class="logo">
            </a>
            <div>
                <h1 class="title-subtitle">Task Allocator Pro</h1>
            </div>
        </div>
        <nav>';
    echo '<a href="home.php">Home</a>';
    echo '<a href="profile.php" class="user-link">Profile</a>';

    if ($isLoggedIn) {
        echo '<a href="logout.php">Logout</a>';
    } else {
        echo '<a href="login.php">Login</a>';
        echo '<a href="register.php">Register</a>';
    }

    echo '</nav>
    </header>';
}

function generateSidebar($role, $currentPage)
{
    echo '<nav class="sidebar">
        <ul>
            <li><a href="home.php" class="' . ($currentPage == 'home.php' ? 'active' : '') . '">Home</a></li>';

    if ($role === 'Manager') {
        echo '
            <li><a href="addProject.php" class="' . ($currentPage == 'add_project.php' ? 'active' : '') . '">Add Project</a></li>
            <li><a href="viewProjects.php" class="' . ($currentPage == 'view_projects.php' ? 'active' : '') . '">View All Projects</a></li>';
    } elseif ($role === 'Project Leader') {
        echo '
            <li><a href="addTask.php" class="' . ($currentPage == 'add_task.php' ? 'active' : '') . '">Add Task</a></li>
            <li><a href="viewTasks.php" class="' . ($currentPage == 'view_tasks.php' ? 'active' : '') . '">View Assigned Tasks</a></li>
            <li><a href="assignTeamMembers.php" class="' . ($currentPage == 'assign_team_members.php' ? 'active' : '') . '">Assign Team Members</a></li>';
    } elseif ($role === 'Team Member') {
        echo '
            <li><a href="viewTasks.php" class="' . ($currentPage == 'viewTasks.php' ? 'active' : '') . '">My Tasks</a></li>
            <li><a href="submit.php" class="' . ($currentPage == 'submit.php' ? 'active' : '') . '">Submit Task Progress</a></li>';
    } else {
        echo '
            <li><a href="login.php" class="' . ($currentPage == 'login.php' ? 'active' : '') . '">Login</a></li>
            <li><a href="register.php" class="' . ($currentPage == 'register.php' ? 'active' : '') . '">Register</a></li>';
    }

    echo '</ul></nav>';
}

function generateFooter()
{
    echo '<footer>
        <div class="contact-info">
            <p>Contact us at: <a href="mailto:hibreghith@gmail.com">Our Gmail</a></p>
            <p>Phone: 0599278784</p>
        </div>
        <div class="copywrite">
            <p>&copy; ' . date('Y') . ' Task Allocator Pro. All rights reserved.</p>
        </div>
        <div>
            <a href="about.php">About Us</a>
        </div>
    </footer>';
}
?>
