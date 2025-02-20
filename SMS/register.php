<?php
session_start();
include('conn/db.php'); 
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $idno = trim($_POST['idno']);
    $lastname = trim($_POST['lastname']);
    $firstname = trim($_POST['firstname']);
    $midname = trim($_POST['midname']) ?: NULL;
    $course = $_POST['course'];
    $year_level = $_POST['year-level'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        $error = "Passwords doesn't match!";
    }else{
        // Hash password for security
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Database insert query
        $sql = "INSERT INTO users (idno, lastname, firstname, midname, course, year_level, username, password_hash) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssss", $idno, $lastname, $firstname, $midname, $course, $year_level, $username, $password_hash);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "Error executing query: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Portal</title>
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/all.css">
    <script src="scripts/register-script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
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

        .register-container {
            width: 100%;
            max-width: 500px;
            z-index: 2;
        }

        .register-card {
            background: #f5f7fa;
            border-radius: 16px;
            box-shadow: var(--shadow-elegant);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .register-header {
            background: var(--primary-gradient);
            color: #f5f7fa;
            text-align: center;
            padding: 20px;
        }

        .register-header h2 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .register-form {
            padding: 20px;
            background: #f5f7fa;
            height: 50vh;
            overflow-y: auto;
        }

        .w3-input {
            border-radius: 8px;
            padding: 8px 12px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
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
            font-size: 0.9rem;
        }

        .reg-button {
            background: var(--primary-gradient);
            color: #f5f7fa;
            border-radius: 8px;
            padding: 10px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 15px;
            transition: all 0.5s ease;
        }

        .reg-button:hover {
            transform: translateY(-2px);
            transition: all 0.5s ease;
        }
        .login-button{
            transition: all 0.5s ease;
        }
        .login-button:hover{
            transform: translateY(-2px);
            transition: all 0.5s ease;
        }

        .fas {
            margin-right: 8px;
        }

        footer {
            margin-top: auto;
            text-align: center;
            padding: 10px;
            color: black;
        }

        /* The entire scrollbar */
        ::-webkit-scrollbar {
            width: 10px; 
            height: 12px; 
        }

        /* The draggable part of the scrollbar */
        ::-webkit-scrollbar-thumb {
            background-color: #888; 
            border-radius: 10px;
            border: 2px solid #ccc;
        }

        /* The scrollbar track (background) */
        ::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            border-radius: 10px;
        }

        /* The scrollbar when hovering over the thumb */
        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        #error-message{
            margin-bottom:0px;
            color: #f1f1f1;
            display:flex;
            justify-content: center;
        }
        
        .success-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {transform: translateY(-100px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
        }

        .success-icon {
            text-align: center;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .modal-body {
            padding: 20px;
            text-align: center;
        }

        .modal-footer {
            padding: 15px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 0 0 8px 8px;
        }

        .green-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .green-btn:hover {
            background-color: #45a049;
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
</head>
<body>
    <div id="globe"></div>
    <img src="pictures/ccs-logo.png" style="z-index: 2; margin: 16px;" alt="Description of image">
    <div class="register-container">
        <div class="register-card" id="register-form-card">
            <div class="register-header">
                <h2><i class="fa fa-address-card"></i> Registration</h2>
                <?php if ($error): ?>
                    <h3 id="error-message"><?php echo $error; ?></h3>
                    <script>
                        setTimeout(function() {
                            document.getElementById('error-message').style.transition = 'opacity 1s ease-out';
                            document.getElementById('error-message').style.opacity = '0';
                            setTimeout(function() {
                                document.getElementById('error-message').style.display = 'none';
                            }, 1000);
                        }, 3000);
                        triggerShake();
                    </script>
                <?php endif; ?>
            </div>
            <form class="register-form" action="register.php" method="POST">
                    <label><i class="fas fa-id-card"></i> IDNO</label>
                    <input class="w3-input w3-border" type="text" name="idno" required>

                    <label><i class="fas fa-user"></i> Lastname</label>
                    <input class="w3-input w3-border" type="text" name="lastname" required>

                    <label><i class="fas fa-user"></i> Firstname</label>
                    <input class="w3-input w3-border" type="text" name="firstname" required>

                    <label><i class="fas fa-user"></i> Midname</label>
                    <input class="w3-input w3-border" type="text" name="midname" placeholder="(optional)">

                    <label><i class="fas fa-user"></i> Course</label>
                    <select class="w3-input w3-border" name="course" required>
                        <option value="" disabled selected>-- Select Course --</option>
                        <option value="computer-science">Computer Science</option>
                        <option value="information-technology">Information Technology</option>
                    </select>

                    <label><i class="fas fa-user"></i> Year Level</label>
                    <select class="w3-input w3-border" name="year-level" required>
                        <option value="" disabled selected>-- Select Year Level --</option>
                        <option value="first-year">1</option>
                        <option value="second-year">2</option>
                        <option value="third-year">3</option>
                        <option value="fourth-year">4</option>
                    </select>

                    <label><i class="fas fa-user"></i> Username</label>
                    <input class="w3-input w3-border" type="text" name="username" required>

                    <label><i class="fas fa-key"></i> Password</label>
                    <input class="w3-input w3-border" type="password" name="password" id="password" required>
                    <label><i class="fas fa-key"></i> Confirm Password</label>
                    <input class="w3-input w3-border" type="password" name="confirm_password" id="confirm_password" required>
                    
                    <button class="w3-button w3-block reg-button" type="submit">
                        <i class="fas fa-sign-in-alt"></i> Register
                    </button>
            </form>
            <div class="login-button w3-margin ">
                <a href="login.php" style="text-decoration: none" >Already have an Account? Click here to Login</a>
            </div>
        </div>
        <footer style="text-align: center; padding: 20px; color: #f4f4f4;">
            <p>&copy; 2025 Patino, Rafael B. All rights reserved.</p>
        </footer>
    </div>

    <div id="successModal" class="success-modal">
        <div class="modal-content w3-animate-top">
            <div class="success-icon">
                <i class="w3-xxxlarge">✔</i>
            </div>
            <div class="modal-body">
                <h3 class="w3-text-dark-grey">Registration Successful!</h3>
                <p class="w3-text-grey">Your account has been created successfully.</p>
                <p class="w3-text-grey w3-small">Please check your email for verification.</p>
            </div>
            <div class="modal-footer">
                <button class="green-btn" onclick="closeModal()">Continue</button>
            </div>
        </div>
    </div>

    <img src="pictures/uc-logo.png" style="z-index: 2; margin: 16px;" alt="Description of image" width="220" height="200">
</body>
</html>
