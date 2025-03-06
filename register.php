<?php
session_start();
include('conn/db.php'); 
$confirm_password_err = "";
$registration_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $idno = trim($_POST['idno']);
    $lastname = trim($_POST['lastname']);
    $firstname = trim($_POST['firstname']);
    $midname = trim($_POST['midname']) ?: NULL;
    $course = $_POST['course'];
    $year_level = $_POST['year-level'];
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST["confirm_password"];


    if ($password !== $confirm_password) {
        $confirm_password_err = "Password did not match.";
    }else{
        // Hash password for security
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Database insert query
        $sql = "INSERT INTO users (idno, lastname, firstname, midname, course, year_level, email, address, username, password_hash, sessionno) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 30)";

        if ($stmt = $conn->prepare($sql)) {
            // Update bind_param to match the number of parameters in SQL query
            $stmt->bind_param("ssssssssss", $idno, $lastname, $firstname, $midname, $course, $year_level, $email, $address, $username, $password_hash);

            if ($stmt->execute()) {
                $registration_success = true;
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
    <link rel="stylesheet" href="css/register-styles.css">
    <script src="scripts/register-script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
</head>
<body>
    <div id="globe"></div>
    <img src="pictures/ccs-logo.png" style="z-index: 2; margin: 16px;" alt="Description of image">
    <div class="register-container">
        <!-- Success message -->
        <div id="successMessage" class="success-message w3-panel w3-round-large w3-padding-16 w3-center<?php echo $registration_success ? 'w3-show animate__animated animate__fadeIn' : ''; ?>">
            <i class="fas fa-check-circle fa-2x"></i>
            <h3>Registration Successful!</h3>
            <p>Your account has been created. You can now log in.</p>
            <a href="login.php" class="w3-button w3-blue w3-round-large w3-margin-top">Go to Login</a>
        </div>

        <!-- Registration form -->
        <div class="register-card" id="registrationForm" <?php echo $registration_success ? 'style="display:none;"' : ''; ?>>
            <div class="register-header">
                <h2><i class="fa fa-address-card"></i> Registration</h2>
            </div>
            <form id="registerForm" class="register-form" action="register.php" method="POST">
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
                        <option value="Computer Science">Computer Science</option>
                        <option value="Information Technology">Information Technology</option>
                    </select>

                    <label><i class="fas fa-user"></i> Year Level</label>
                    <select class="w3-input w3-border" name="year-level" required>
                        <option value="" disabled selected>-- Select Year Level --</option>
                        <option value="First year">1</option>
                        <option value="Second year">2</option>
                        <option value="Third year">3</option>
                        <option value="Fourth year">4</option>
                    </select>

                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input class="w3-input w3-border" type="email" name="email" required>

                    <label><i class="fas fa-map-marker-alt"></i> Address</label>
                    <input class="w3-input w3-border" type="text" name="address" required>

                    <label><i class="fas fa-user"></i> Username</label>
                    <input class="w3-input w3-border" type="text" name="username" required>

                    <label><i class="fas fa-key"></i> Password</label>
                    <input class="w3-input w3-border" type="password" name="password" id="password" required>

                    <label><i class="fas fa-key"></i> Confirm Password</label>
                    <input class="w3-input w3-border" type="password" name="confirm_password" id="confirm_password" required>
                    <div class="password-match-indicator" id="passwordIndicator"></div>
                    <span class="error-text <?php echo (!empty($confirm_password_err)) ? 'animate__animated animate__fadeIn shake' : ''; ?>" id="confirmPasswordError"><?php echo $confirm_password_err; ?></span>
                    
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

    <img src="pictures/uc-logo.png" style="z-index: 2; margin: 16px;" alt="Description of image" width="220" height="200">
</body>
<script>
    <?php if($registration_success): ?>
        // Show success message with animation if registration was successful
        document.getElementById('successMessage').style.display = 'block';
        document.getElementById('registrationForm').style.display = 'none';
    <?php endif; ?>
</script>
</html>
