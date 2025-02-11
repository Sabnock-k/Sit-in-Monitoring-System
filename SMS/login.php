<?php
session_start();
include('conn/db.php'); 
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepare query to fetch user details
    $sql = "SELECT id, idno, firstname, lastname, course, year_level, password_hash FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $idno, $firstname, $lastname, $course, $year_level, $password_hash);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $password_hash)) {
                // Store user details in session
                $_SESSION['user_id'] = $id;
                $_SESSION['idno'] = $idno;
                $_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;
                $_SESSION['course'] = $course;
                $_SESSION['year_level'] = $year_level;
                $_SESSION['loggedin'] = true;

                // Redirect to dashboard or homepage
                header("Location: homepage.php");
                exit();
            } else {
                $error = "Invalid username or password!"; 
            }
        } else {
            $error = "No user found! Try again!";  
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal</title>
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/all.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #D29C00 0%, #5E3B73 100%);
            --secondary-gradient: linear-gradient(135deg, #ff6a88 0%, #ff9a8b 100%);
            --shadow-elegant: 0 10px 20px rgba(0,0,0,0.1), 0 6px 6px rgba(0,0,0,0.05);
        }

        #globe {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            bottom: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }

        .login-card {
            background: #f5f7fa;
            border-radius: 16px;
            box-shadow: var(--shadow-elegant);
            overflow: hidden;
        }

        .login-header {
            background: var(--primary-gradient);
            color: #f5f7fa;
            text-align: center;
            padding: 20px;
        }

        .login-header h2 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .login-form {
            padding: 30px;
            background: #f5f7fa;
        }

        .w3-input {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .w3-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #363795;
            transition: all 0.3s ease;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            color: #2c3e50;
        }

        .login-button {
            background: var(--primary-gradient);
            color: #f5f7fa;
            border-radius: 8px;
            padding: 12px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.5s ease;
        }

        .login-button:hover {
            transform: translateY(-3px);
            transition: all 0.5s ease;
        }

        .reg-button{
            transition: all 0.5s ease;
        }
        
        .reg-button:hover{
            transform: translateY(-3px);
            transition: all 0.5s ease;
        }

        .fas {
            margin-right: 10px;
        }

        .error{
            color: #E52020;
            display: flex;
            justify-content: center;
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0% { transform: translate(0, 0) rotate(0deg); }
                0% { transform: translateX(0); }
                10% { transform: translateX(-10px); }
                20% { transform: translateX(15px); }
                30% { transform: translateX(-20px); }
                40% { transform: translateX(10px); }
                50% { transform: translateX(-15px); }
                60% { transform: translateX(20px); }
                70% { transform: translateX(-10px); }
                80% { transform: translateX(15px); }
                90% { transform: translateX(-5px); }
                100% { transform: translateX(0); }
        }
    </style>

    <script>
        function triggerShake() {
            var form = document.getElementById("login-form");
            form.classList.add("shake");
            setTimeout(() => form.classList.remove("shake"), 300);
        }
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            VANTA.GLOBE({
            el: "#globe",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            color: 0xD29C00,
            backgroundColor: 0x0e2f60
            })
        });
    </script>
</head>
<body>
    <div id="globe"></div>
    <img src="pictures/ccs-logo.png" style="z-index: 2; margin: 16px;" alt="Description of image">
    <div class="login-container">
        <div class="w3-card-4 login-card" id="login-form">
            <div class="login-header">
                <h2><i class="fas fa-chair"></i>CCS Sit-in Monitoring System</h2>
            </div>
            <form class="login-form w3-container" method="POST" action="login.php">
                <?php if ($error): ?>
                    <p class="error"><?php echo $error; ?></p>
                    <script>triggerShake();</script>
                <?php endif; ?>

                <label><i class="fas fa-user"></i> Username</label>
                <input class="w3-input w3-border" type="text" name="username" required>
                
                <label><i class="fas fa-key"></i> Password</label>
                <input class="w3-input w3-border" type="password" name="password" required>
                
                <button class="w3-button w3-block w3-margin-top login-button" type="submit">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                <div class="reg-button w3-margin">
                <a href="register.php" style="text-decoration: none" >No account? Click here to register</a>
                </div>
            </form>
        </div>
        <footer style="text-align: center; margin: 10px; color: #f4f4f4;">
            <p>&copy; 2025 Patino, Rafael B. All rights reserved.</p>
        </footer>
    </div>
    <img src="pictures/uc-logo.png" style="z-index: 2; margin: 16px;" alt="Description of image" width="220" height="200">
</body>
</html>
