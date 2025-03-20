<?php
session_start();
include('conn/db.php');

// check if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['username'] == 'admin') {
    header("Location: pages/admin/homepage.php");
    exit();
} elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: pages/users/homepage.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepare query to fetch user details
    $sql = "SELECT id, idno, lastname, firstname, midname, course, year_level, email, address, username, password_hash, sessionno FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $idno, $lastname, $firstname, $midname, $course, $year_level, $email, $address, $username, $password_hash, $sessionno);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $password_hash)) {
                // Store user details in session
                $_SESSION['user_id'] = $id;
                $_SESSION['idno'] = $idno;
                $_SESSION['firstname'] = $firstname;
                $_SESSION['midname'] = $midname;
                $_SESSION['lastname'] = $lastname;
                $_SESSION['course'] = $course;
                $_SESSION['year_level'] = $year_level;
                $_SESSION['email'] = $email;
                $_SESSION['address'] = $address;
                $_SESSION['username'] = $username;
                $_SESSION['sessionno'] = $sessionno;
                $_SESSION['loggedin'] = true;

                // Redirect to homepage
                header("Location: pages/users/homepage.php");
                exit();
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            if ($username == "admin" && $password == "adminpassword") {
                $_SESSION['user_id'] = '1';
                $_SESSION['username'] = 'admin';
                $_SESSION['loggedin'] = true;

                header("Location: pages/admin/homepage.php");
                exit();
            } else {
                $error = "Invalid username or password!";
            }
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
    <title>Login - CCS Sit-in Monitoring</title>
    <!-- Add Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="public/css/all.css">
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
        
        .login-animation {
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
    <div class="container mx-auto px-4 py-10 flex flex-col items-center">
        <div class="login-animation w-full max-w-md">
            <!-- Brand Logo & Name -->
            <div class="flex items-center justify-center mb-6">
                <div class="mr-3">
                    <img src="public/pictures/ccs-logo.png" alt="CCS Logo" class="h-16 w-16 rounded-md">
                </div>
                <span class="heading-font font-semibold text-2xl text-primary">CCS <span class="text-secondary">Sit-in</span> Monitoring</span>
            </div>
            
            <!-- Login Card -->
            <div id="login-form" class="bg-white rounded-lg border border-gray-200 shadow-custom hover:shadow-custom-hover transition-shadow duration-300 overflow-hidden">
                <!-- Header -->
                <div class="bg-primary text-white p-6 border-b border-primary/20">
                    <h1 class="heading-font text-xl font-semibold text-center">Welcome Back</h1>
                    <p class="text-center text-sm text-white/80 mt-1">Please log in to access your account</p>
                </div>
                
                <!-- Form -->
                <div class="p-6">
                    <?php if ($error): ?>
                        <div class="bg-red-50 text-red-600 p-3 rounded-md mb-4 shake">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span><?php echo $error; ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="login.php">
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-user mr-2 text-primary/70"></i>Username
                            </label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="w-full px-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                required 
                                autocomplete="username"
                            >
                        </div>
                        
                        <div class="mb-6">
                            <label for="password" class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-lock mr-2 text-primary/70"></i>Password
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full px-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all duration-300" 
                                required
                                autocomplete="current-password"
                            >
                        </div>
                        
                        <button 
                            type="submit" 
                            class="w-full bg-primary text-white py-3 px-4 rounded-md transition duration-300 hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary font-medium flex justify-center items-center"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Log In
                        </button>
                    </form>
                    
                    <div class="mt-5 text-center">
                        <a href="pages/users/register.php" class="text-primary hover:text-primary/80 transition duration-300 text-sm font-medium flex justify-center items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            No account? Register here
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-6 text-gray-600 text-sm">
                <p>&copy; 2025 University of Cebu - College of Computer Studies</p>
                <p class="text-xs mt-1">Developed by Rafael B. Patiño</p>
            </div>
        </div>
    </div>
    
    <!-- Optional: Background decoration -->
    <div class="fixed bottom-0 right-0 pointer-events-none opacity-10">
        <img src="public/pictures/uc-logo.png" alt="UC Logo" class="w-64">
    </div>
</body>
</html>