<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../login.php");
    exit();
} else if ($_SESSION['username'] !== 'admin') {
    header("Location: ../users/homepage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<style>
    :root {
        /* Gradients */
        --primary-gradient: linear-gradient(135deg, #D29C00 0%, #5E3B73 100%);

        /* Shadows */
        --shadow-elegant: 0 10px 20px rgba(0, 0, 0, 0.1), 0 6px 6px rgba(0, 0, 0, 0.05);

        /* Colors */
        --body-bg: #F8FAFC;
        --card-bg: #f4f4f4;
        --text-dark: #2c3e50;
        --text-light: #f5f7fa;
        --border-highlight: #D29C00;
        --announcement-bg: rgba(210, 156, 0, 0.1);
        --nav-hover: rgba(255, 255, 255, 0.1);
        --nav-active: rgba(255, 255, 255, 0.2);
    }

    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 20px;
        min-height: 100vh;
        background: var(--body-bg);
    }

    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: var(--primary-gradient);
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow-elegant);
        z-index: 1000;
    }

    .nav-brand {
        display: flex;
        align-items: center;
        color: var(--text-light);
    }

    .nav-logo {
        height: 40px;
        margin-right: 10px;
        background: var(--card-bg);
        border-radius: 50%;
    }

    .logout-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--primary-gradient);
        color: var(--text-light);
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        z-index: 100;
    }

    .nav-links {
        display: flex;
        gap: 10px;
    }

    .nav-item {
        color: var(--text-light);
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .nav-item:hover {
        background: var(--nav-hover);
        transform: translateY(-2px);
    }

    .nav-item.active {
        background: var(--nav-active);
    }

    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            padding: 10px;
        }

        .nav-links {
            flex-direction: column;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }

        .nav-item {
            padding: 10px;
        }

        .dashboard-container {
            margin-top: 200px;
        }
    }
</style>

<body>
    <nav class="navbar">
        <div class="nav-brand">
            <img src="../../public/pictures/ccs-logo.png" alt="CCS Logo" class="nav-logo">
            <span>CCS Admin Panel</span>
        </div>
        <div class="nav-links">
            <a href="homepage.php" class="nav-item active"><i class="fas fa-home"></i> Home</a>
            <a href="#" class="nav-item"><i class="fas fa-user-edit"></i> Search</a>
            <a href="#" class="nav-item"><i class="fas fa-history"></i> Students</a>
            <a href="#" class="nav-item"><i class="fas fa-calendar-plus"></i> Sit-in</a>
            <a href="#" class="nav-item"><i class="fas fa-calendar-plus"></i> View Sit-in Records</a>
            <a href="#" class="nav-item"><i class="fas fa-calendar-plus"></i> Sit-in Reports</a>
            <a href="#" class="nav-item"><i class="fas fa-calendar-plus"></i> Feedback</a>
            <a href="#" class="nav-item"><i class="fas fa-calendar-plus"></i> Reports</a>
            <a href="#" class="nav-item"><i class="fas fa-calendar-plus"></i> Reservation</a>
            <a href="../../logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
</body>

</html>