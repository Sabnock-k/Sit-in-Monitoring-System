<?php
session_start();
include('../../conn/db.php'); 
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
    } else {
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
    <title>Register - CCS Sit-in Monitoring</title>
    <!-- Add Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="../../public/css/all.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0056b3',
                        secondary: '#343a40',
                        accent: '#ffc107',
                        light: '#f8f9fa',
                        dark: '#212529',
                    },
                    boxShadow: {
                        'custom': '0 4px 6px rgba(0, 0, 0, 0.1)',
                        'custom-hover': '0 10px 15px rgba(0, 0, 0, 0.1)',
                    }
                },
            },
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        
        .heading-font {
            font-family: 'Montserrat', sans-serif;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #0056b3;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #0043a0;
        }
        
        .register-animation {
            animation: fadeInUp 0.8s ease-out;
        }
        
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0% { transform: translateX(0); }
            10% { transform: translateX(-10px); }
            20% { transform: translateX(8px); }
            30% { transform: translateX(-6px); }
            40% { transform: translateX(4px); }
            50% { transform: translateX(-2px); }
            60% { transform: translateX(1px); }
            70% { transform: translateX(-1px); }
            80% { transform: translateX(0); }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 py-8 flex flex-col items-center">
        <div class="register-animation w-full max-w-2xl">
            <!-- Brand Logo & Name -->
            <div class="flex items-center justify-center mb-6">
                <div class="mr-3">
                    <img src="../../public/pictures/ccs-logo.png" alt="CCS Logo" class="h-16 w-16 rounded-md">
                </div>
                <span class="heading-font font-semibold text-2xl text-primary">CCS <span class="text-secondary">Sit-in</span> Monitoring</span>
            </div>
            
            <!-- Success Message -->
            <?php if ($registration_success): ?>
            <div id="successMessage" class="bg-green-50 border border-green-200 text-green-600 p-6 rounded-lg shadow-custom mb-6 animate__animated animate__fadeIn">
                <div class="flex flex-col items-center text-center">
                    <i class="fas fa-check-circle text-4xl mb-3"></i>
                    <h3 class="text-xl font-semibold heading-font mb-2">Registration Successful!</h3>
                    <p class="mb-4">Your account has been created. You can now log in.</p>
                    <a href="../../login.php" class="bg-primary text-white py-2 px-6 rounded-md transition duration-300 hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary font-medium flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Go to Login
                    </a>
                </div>
            </div>
            <?php else: ?>
            
            <!-- Registration Card -->
            <div id="registration-form" class="bg-white rounded-lg border border-gray-200 shadow-custom hover:shadow-custom-hover transition-shadow duration-300 overflow-hidden">
                <!-- Header -->
                <div class="bg-primary text-white p-6 border-b border-primary/20">
                    <h1 class="heading-font text-xl font-semibold text-center">Create an Account</h1>
                    <p class="text-center text-sm text-white/80 mt-1">Please fill out the form to register</p>
                </div>
                
                <!-- Form -->
                <div class="p-6">
                    <?php if ($confirm_password_err): ?>
                        <div class="bg-red-50 text-red-600 p-3 rounded-md mb-4 shake">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span><?php echo $confirm_password_err; ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="register.php" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Column -->
                            <div>
                                <label for="idno" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-id-card mr-2 text-primary/70"></i>IDNO
                                </label>
                                <input 
                                    type="text" 
                                    id="idno" 
                                    name="idno" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required 
                                >
                            </div>
                            
                            <div>
                                <label for="lastname" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-user mr-2 text-primary/70"></i>Lastname
                                </label>
                                <input 
                                    type="text" 
                                    id="lastname" 
                                    name="lastname" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required 
                                >
                            </div>
                            
                            <div>
                                <label for="firstname" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-user mr-2 text-primary/70"></i>Firstname
                                </label>
                                <input 
                                    type="text" 
                                    id="firstname" 
                                    name="firstname" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required 
                                >
                            </div>
                            
                            <div>
                                <label for="midname" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-user mr-2 text-primary/70"></i>Midname (optional)
                                </label>
                                <input 
                                    type="text" 
                                    id="midname" 
                                    name="midname" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                >
                            </div>
                            
                            <div>
                                <label for="course" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-graduation-cap mr-2 text-primary/70"></i>Course
                                </label>
                                <select 
                                    id="course" 
                                    name="course" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required
                                >
                                    <option value="" disabled selected>-- Select Course --</option>
                                    <option value="Computer Science">Computer Science</option>
                                    <option value="Information Technology">Information Technology</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="year-level" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-layer-group mr-2 text-primary/70"></i>Year Level
                                </label>
                                <select 
                                    id="year-level" 
                                    name="year-level" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required
                                >
                                    <option value="" disabled selected>-- Select Year Level --</option>
                                    <option value="First year">1</option>
                                    <option value="Second year">2</option>
                                    <option value="Third year">3</option>
                                    <option value="Fourth year">4</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-envelope mr-2 text-primary/70"></i>Email
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required 
                                >
                            </div>
                            
                            <div>
                                <label for="address" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-primary/70"></i>Address
                                </label>
                                <input 
                                    type="text" 
                                    id="address" 
                                    name="address" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required 
                                >
                            </div>
                            
                            <div>
                                <label for="username" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-user mr-2 text-primary/70"></i>Username
                                </label>
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required 
                                >
                            </div>
                            
                            <div>
                                <label for="password" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-lock mr-2 text-primary/70"></i>Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required 
                                >
                            </div>
                            
                            <div>
                                <label for="confirm_password" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-lock mr-2 text-primary/70"></i>Confirm Password
                                </label>
                                <input 
                                    type="password" 
                                    id="confirm_password" 
                                    name="confirm_password" 
                                    class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                    required 
                                >
                            </div>
                        </div>
                        
                        <button 
                            type="submit" 
                            class="w-full mt-6 bg-primary text-white py-3 px-4 rounded-md transition duration-300 hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary font-medium flex justify-center items-center"
                        >
                            <i class="fas fa-user-plus mr-2"></i>
                            Register
                        </button>
                    </form>
                    
                    <div class="mt-5 text-center">
                        <a href="../../login.php" class="text-primary hover:text-primary/80 transition duration-300 text-sm font-medium flex justify-center items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Already have an account? Login here
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Footer -->
            <div class="text-center mt-6 text-gray-600 text-sm">
                <p>&copy; 2025 University of Cebu - College of Computer Studies</p>
                <p class="text-xs mt-1">Developed by Rafael B. Patiño</p>
            </div>
        </div>
    </div>
    
    <!-- Optional: Background decoration -->
    <div class="fixed bottom-0 right-0 pointer-events-none opacity-10">
        <img src="../../public/pictures/uc-logo.png" alt="UC Logo" class="w-64">
    </div>

    <script>
        // Password match validation
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords don't match");
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
            
            if (password && confirmPassword) {
                password.addEventListener('change', validatePassword);
                confirmPassword.addEventListener('keyup', validatePassword);
            }
        });
    </script>
</body>
</html>