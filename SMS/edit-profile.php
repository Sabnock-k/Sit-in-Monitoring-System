<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
include('conn/db.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $idno = $_SESSION['idno'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $midname = $_POST['middlename'];
    $course = $_POST['course'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql = "UPDATE users SET lastname = ?, firstname = ?, midname = ?, course = ?, email = ?, address = ? WHERE idno = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssss", $lastname, $firstname, $midname, $course, $email, $address, $idno);
        $stmt->execute();
        $stmt->close();

        // Update session variables
        $_SESSION['lastname'] = $lastname;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['midname'] = $midname;
        $_SESSION['course'] = $course;
        $_SESSION['email'] = $email;
        $_SESSION['address'] = $address;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - CCS Sit-in Monitoring</title>
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/all.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #D29C00 0%, #5E3B73 100%);
            --shadow-elegant: 0 10px 20px rgba(0,0,0,0.1), 0 6px 6px rgba(0,0,0,0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            background: #F8FAFC;
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
            color: #f5f7fa;
        }

        .nav-logo {
            height: 40px;
            margin-right: 10px;
            background: #f4f4f4;
            border-radius: 50%;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-item {
            color: #f5f7fa;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .edit-profile-container {
            max-width: 800px;
            margin: 100px auto 20px;
            padding: 20px;
        }

        .dashboard-card {
            background: #f4f4f4;
            border-radius: 16px;
            box-shadow: var(--shadow-elegant);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            background: var(--primary-gradient);
            color: #f5f7fa;
            padding: 15px;
            font-weight: 600;
        }

        .card-content {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .submit-btn {
            background: var(--primary-gradient);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.2s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
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
            
            .edit-profile-container {
                margin-top: 200px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <img src="pictures/ccs-logo.png" alt="CCS Logo" class="nav-logo">
            <span>CCS Sit-in Monitoring</span>
        </div>
        <div class="nav-links">
            <a href="homepage.php" class="nav-item"><i class="fas fa-home"></i> Home</a>
            <a href="edit-profile.php" class="nav-item active"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <a href="history.php" class="nav-item"><i class="fas fa-history"></i> History</a>
            <a href="reservation.php" class="nav-item"><i class="fas fa-calendar-plus"></i> Reservation</a>
            <a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <div class="edit-profile-container">
        <div class="dashboard-card">
            <div class="card-header">
                <i class="fas fa-user-edit"></i> Edit Profile
            </div>
            <div class="card-content">
                <form action="edit-profile.php" method="POST">
                    <div class="form-group">
                        <label for="idno">ID Number</label>
                        <input type="text" id="idno" name="idno" value="<?php echo $_SESSION['idno']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo $_SESSION['firstname']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo $_SESSION['lastname']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" id="middlename" name="middlename" value="<?php echo $_SESSION['midname']; ?>">    
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="course">course</label>
                        <input type="text" id="course" name="course" value="<?php echo $_SESSION['course']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?php echo $_SESSION['address']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">New Password (leave blank to keep current)</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <button type="submit" class="submit-btn">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
