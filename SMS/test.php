<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = ""; // XAMPP default has no password
$dbname = "user_registration";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Initialize variables
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";
$registration_success = false;

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
         
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_email, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Registration successful
                $registration_success = true;
                
                // Clear form data after successful registration
                $username = $email = $password = $confirm_password = "";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration System</title>
    <!-- W3.CSS -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Add animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 80px auto;
            padding: 20px;
        }
        .form-header {
            position: relative;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-header::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 25%;
            right: 25%;
            height: 2px;
            background: #2196F3;
        }
        .input-container {
            position: relative;
            margin-bottom: 25px;
        }
        .input-container i {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #2196F3;
        }
        .input-field {
            padding-left: 40px !important;
            border-radius: 4px !important;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #2196F3 !important;
            box-shadow: 0 0 8px rgba(33, 150, 243, 0.4);
        }
        .error-text {
            color: #f44336;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
            transition: all 0.3s ease;
        }
        .success-message {
            display: none;
            border-radius: 4px;
        }
        .submit-btn {
            transition: all 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #0d8bf0 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .password-match-indicator {
            transition: all 0.3s ease;
            height: 5px;
            margin-top: 5px;
            border-radius: 2px;
            width: 100%;
        }
        .shake {
            animation: shakeX 0.75s;
        }
        @keyframes shakeX {
            from, to { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .pulse {
            animation: pulse 1s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="w3-card-4 w3-round-large w3-white w3-animate-top">
            <!-- Success message -->
            <div id="successMessage" class="w3-panel w3-green w3-round-large w3-padding-16 w3-center success-message <?php echo $registration_success ? 'w3-show animate__animated animate__fadeIn' : ''; ?>">
                <i class="fas fa-check-circle fa-2x"></i>
                <h3>Registration Successful!</h3>
                <p>Your account has been created. You can now log in.</p>
                <a href="#" class="w3-button w3-blue w3-round-large w3-margin-top">Go to Login</a>
            </div>

            <!-- Registration form -->
            <div id="registrationForm" class="w3-container w3-padding-16" <?php echo $registration_success ? 'style="display:none;"' : ''; ?>>
                <div class="form-header">
                    <h2 class="w3-text-blue"><i class="fas fa-user-plus"></i> Create an Account</h2>
                </div>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registerForm">
                    <!-- Username field -->
                    <div class="input-container">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" class="w3-input w3-border input-field" placeholder="Username" value="<?php echo $username; ?>">
                        <span class="error-text <?php echo (!empty($username_err)) ? 'animate__animated animate__fadeIn' : ''; ?>"><?php echo $username_err; ?></span>
                    </div>
                    
                    <!-- Email field -->
                    <div class="input-container">
                        <i class="fas fa-envelope"></i>
                        <input type="text" name="email" class="w3-input w3-border input-field" placeholder="Email" value="<?php echo $email; ?>">
                        <span class="error-text <?php echo (!empty($email_err)) ? 'animate__animated animate__fadeIn' : ''; ?>"><?php echo $email_err; ?></span>
                    </div>
                    
                    <!-- Password field -->
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="w3-input w3-border input-field" placeholder="Password" value="<?php echo $password; ?>">
                        <span class="error-text <?php echo (!empty($password_err)) ? 'animate__animated animate__fadeIn' : ''; ?>"><?php echo $password_err; ?></span>
                    </div>
                    
                    <!-- Confirm Password field -->
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" id="confirm_password" class="w3-input w3-border input-field" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>">
                        <div class="password-match-indicator" id="passwordIndicator"></div>
                        <span class="error-text <?php echo (!empty($confirm_password_err)) ? 'animate__animated animate__fadeIn shake' : ''; ?>" id="confirmPasswordError"><?php echo $confirm_password_err; ?></span>
                    </div>
                    
                    <!-- Submit button -->
                    <div class="w3-center">
                        <button type="submit" class="w3-button w3-blue w3-round-large w3-padding submit-btn w3-ripple">
                            <i class="fas fa-user-plus"></i> Register
                        </button>
                    </div>
                </form>
                
                <div class="w3-padding-16 w3-center">
                    <p>Already have an account? <a href="#" class="w3-text-blue">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables for password matching animation
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirm_password');
        const passwordIndicator = document.getElementById('passwordIndicator');
        const confirmPasswordError = document.getElementById('confirmPasswordError');
        
        // Function to check password match
        function checkPasswordMatch() {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;
            
            if (confirmPassword === '') {
                // When confirm field is empty
                passwordIndicator.style.backgroundColor = '#e0e0e0';
                confirmPasswordError.textContent = '';
                confirmPasswordError.classList.remove('shake');
                return;
            }
            
            if (password === confirmPassword) {
                // When passwords match
                passwordIndicator.style.backgroundColor = '#4CAF50';
                passwordIndicator.classList.add('animate__animated', 'animate__pulse');
                confirmPasswordError.textContent = '';
                confirmPasswordField.style.borderColor = '#4CAF50';
                setTimeout(() => {
                    passwordIndicator.classList.remove('animate__animated', 'animate__pulse');
                }, 1000);
            } else {
                // When passwords don't match
                passwordIndicator.style.backgroundColor = '#f44336';
                confirmPasswordError.textContent = 'Passwords do not match';
                confirmPasswordError.classList.add('animate__animated', 'animate__fadeIn', 'shake');
                confirmPasswordField.style.borderColor = '#f44336';
                setTimeout(() => {
                    confirmPasswordError.classList.remove('shake');
                }, 500);
            }
        }
        
        // Event listeners for checking password match
        confirmPasswordField.addEventListener('input', checkPasswordMatch);
        passwordField.addEventListener('input', function() {
            if (confirmPasswordField.value !== '') {
                checkPasswordMatch();
            }
        });
        
        // Add animation to form submission
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function(event) {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;
            
            if (password !== confirmPassword) {
                event.preventDefault();
                confirmPasswordError.textContent = 'Passwords do not match';
                confirmPasswordError.classList.add('shake');
                confirmPasswordField.classList.add('w3-border-red');
                setTimeout(() => {
                    confirmPasswordError.classList.remove('shake');
                }, 500);
            }
        });
        
        <?php if($registration_success): ?>
        // Show success message with animation if registration was successful
        document.getElementById('successMessage').style.display = 'block';
        document.getElementById('registrationForm').style.display = 'none';
        <?php endif; ?>
    });
    </script>
</body>
</html>