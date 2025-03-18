<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../login.php");
    exit();
} elseif ($_SESSION['username'] == 'admin') {
    header("Location: ../admin/homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CCS Sit-in Monitoring</title>
    <link rel="stylesheet" href="../public/css/w3.css">
    <link rel="stylesheet" href="../public/css/all.css">
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
            gap: 20px;
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

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-top: 80px;
        }

        .dashboard-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow-elegant);
            overflow: hidden;
        }

        .card-header {
            background: var(--primary-gradient);
            color: var(--text-light);
            padding: 15px;
            font-weight: 600;
        }

        .card-content {
            padding: 20px;
        }

        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
            border: 4px solid var(--card-bg);
            box-shadow: var(--shadow-elegant);
            background: #fff;
        }

        .student-info {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
            align-items: center;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .announcement {
            border-left: 4px solid var(--border-highlight);
            margin-bottom: 15px;
            padding: 10px;
            background: var(--announcement-bg);
        }

        .announcelist {
            padding: 0;
            max-height: 300px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border-highlight) var(--card-bg);
        }

        .rules-list {
            padding: 0;
            max-height: 300px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border-highlight) var(--card-bg);
        }

        #Title {
            text-align: center;
            display: block;
        }
    </style>
</head>

<body>
    <!-- Add this right after the <body> tag and before the globe div -->
    <nav class="navbar">
        <div class="nav-brand">
            <img src="../../public/pictures/ccs-logo.png" alt="CCS Logo" class="nav-logo">
            <span>CCS Sit-in Monitoring</span>
        </div>
        <div class="nav-links">
            <a href="dashboard.php" class="nav-item active"><i class="fas fa-home"></i> Home</a>
            <a href="edit-profile.php" class="nav-item"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <a href="history.php" class="nav-item"><i class="fas fa-history"></i> History</a>
            <a href="reservation.php" class="nav-item"><i class="fas fa-calendar-plus"></i> Reservation</a>
            <a href="../../logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
    <div class="dashboard-container">
        <!-- Student Information Card -->
        <div class="dashboard-card">
            <div class="card-header">
                <i class="fas fa-user-graduate"></i> Student Information
            </div>
            <div class="card-content">
                <img src="../../public/pictures/profile.png" alt="Profile Picture" class="profile-picture">
                <div class="student-info">
                    <span class="info-label">ID Number:</span>
                    <span><?php echo $_SESSION['idno']; ?></span>

                    <span class="info-label">Name:</span>
                    <span><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['midname'] . ' ' . $_SESSION['lastname']; ?></span>

                    <span class="info-label">Course:</span>
                    <span><?php echo $_SESSION['course']; ?></span>

                    <span class="info-label">Year Level:</span>
                    <span><?php echo $_SESSION['year_level']; ?></span>
                    <span class="info-label">Sessions Left:</span>
                    <span><?php echo $_SESSION['sessionno']; ?></span>
                </div>
            </div>
        </div>

        <!-- Announcements Card -->
        <div class="dashboard-card">
            <div class="card-header">
                <i class="fas fa-bullhorn"></i> Announcements
            </div>
            <div class="card-content">
                <div class="announcelist">
                    <div class="announcement">
                        <h4>CCS Admin | 2025-Feb-25</h4>
                        <p>UC did it again.</p>
                    </div>
                    <div class="announcement">
                        <h4>CCS Admin | 2025-Feb-03</h4>
                        <p>The College of Computer Studies will open the registration of students for the Sit-in
                            privilege starting tomorrow. Thank you! Lab Supervisor</p>
                    </div>
                    <div class="announcement">
                        <h4>CCS Admin | 2024-May-08</h4>
                        <p>Important Announcement We are excited to announce the launch of our new website! 🎉 Explore
                            our latest products and services now!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rules and Regulations Card -->
        <div class="dashboard-card">
            <div class="card-header">
                <i class="fas fa-clipboard-list"></i> Rules and Regulations
            </div>
            <div class="card-content">
                <div class="rules-list">
                    <b id="Title">University of Cebu</b>
                    <b id="Title">COLLEGE OF INFORMATION & COMPUTER STUDIES</b>
                    <b>LABORATORY RULES AND REGULATIONS</b>
                    <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our
                        laboratories, please observe the following:</p>
                    <p>1. Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones,
                        walkmans and other personal pieces of equipment must be switched off.</p>
                    <p>2. Games are not allowed inside the lab. This includes computer-related games, card games and
                        other games that may disturb the operation of the lab.</p>
                    <p>3. Surfing the Internet is allowed only with the permission of the instructor. Downloading and
                        installing of software are strictly prohibited.</p>
                    <p>4. Getting access to other websites not related to the course (especially pornographic and
                        illicit sites) is strictly prohibited.</p>
                    <p>5. Deleting computer files and changing the set-up of the computer is a major offense.</p>
                    <p>6. Observe computer time usage carefully. A fifteen-minute allowance is given for each use.
                        Otherwise, the unit will be given to those who wish to "sit-in".</p>
                    <p>7. Observe proper decorum while inside the laboratory.</p>
                    <ul>
                        <li>Do not get inside the lab unless the instructor is present.</li>
                        <li>All bags, knapsacks, and the likes must be deposited at the counter.</li>
                        <li>Follow the seating arrangement of your instructor.</li>
                        <li>At the end of class, all software programs must be closed.</li>
                        <li>Return all chairs to their proper places after using.</li>
                    </ul>
                    <p>8. Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the
                        lab.</p>
                    <p>9. Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures
                        offensive to the members of the community, including public display of physical intimacy, are
                        not tolerated.</p>
                    <p>10. Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding
                        requests made by lab personnel will be asked to leave the lab.</p>
                    <p>11. For serious offense, the lab personnel may call the Civil Security Office (CSU) for
                        assistance.</p>
                    <p>12. Any technical problem or difficulty must be addressed to the laboratory supervisor, student
                        assistant or instructor immediately.</p>
                    <br><br>
                    <b>DISCIPLINARY ACTIONS</b>
                    <ul>
                        <li>First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a
                            suspension from classes for each offender.</li>
                        <li>Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to
                            the Guidance Center.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>