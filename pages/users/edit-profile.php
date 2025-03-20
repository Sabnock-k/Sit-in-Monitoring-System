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

include('../../conn/db.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $idno = $_SESSION['idno'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $midname = $_POST['middlename'];
    $course = $_POST['course'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    
    // Check if password is being updated
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET lastname = ?, firstname = ?, midname = ?, course = ?, email = ?, address = ?, password = ? WHERE idno = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssss", $lastname, $firstname, $midname, $course, $email, $address, $password, $idno);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $sql = "UPDATE users SET lastname = ?, firstname = ?, midname = ?, course = ?, email = ?, address = ? WHERE idno = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $lastname, $firstname, $midname, $course, $email, $address, $idno);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Update session variables
    $_SESSION['lastname'] = $lastname;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['midname'] = $midname;
    $_SESSION['course'] = $course;
    $_SESSION['email'] = $email;
    $_SESSION['address'] = $address;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - CCS Sit-in Monitoring</title>
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
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-50 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3">
                <div class="flex items-center">
                    <div class="mr-3">
                        <img src="../../public/pictures/ccs-logo.png" alt="CCS Logo" class="h-10 w-10 rounded-md">
                    </div>
                    <span class="heading-font font-semibold text-lg text-primary">CCS <span class="text-secondary">Sit-in</span> Monitoring</span>
                </div>
                <div class="hidden md:flex space-x-1">
                    <a href="homepage.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-home mr-2"></i> 
                        <span>Dashboard</span>
                    </a>
                    <a href="edit-profile.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center font-medium text-primary bg-blue-50">
                        <i class="fas fa-user-edit mr-2"></i> 
                        <span>Profile</span>
                    </a>
                    <a href="history.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-history mr-2"></i> 
                        <span>History</span>
                    </a>
                    <a href="reservation.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-calendar-plus mr-2"></i> 
                        <span>Reserve</span>
                    </a>
                    <a href="../../logout.php" class="ml-2 px-4 py-2 rounded-md transition duration-300 flex items-center text-white bg-primary hover:bg-primary/90">
                        <i class="fas fa-sign-out-alt mr-2"></i> 
                        <span>Log Out</span>
                    </a>
                </div>
                <div class="md:hidden">
                    <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu, show/hide based on menu state -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200">
                <a href="dashboard.php" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="edit-profile.php" class="flex items-center px-3 py-2 rounded-md text-primary bg-blue-50 font-medium">
                    <i class="fas fa-user-edit mr-2"></i>Profile
                </a>
                <a href="history.php" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-history mr-2"></i>History
                </a>
                <a href="reservation.php" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-calendar-plus mr-2"></i>Reserve
                </a>
                <a href="../../logout.php" class="flex items-center px-3 py-2 rounded-md text-white bg-primary hover:bg-primary/90">
                    <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pt-24 pb-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg border border-gray-200 shadow-custom hover:shadow-custom-hover transition-shadow duration-300">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="heading-font text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-edit mr-2 text-primary"></i> Edit Profile
                    </h2>
                </div>
                <div class="p-6">
                    <form action="edit-profile.php" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="idno" class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                                <input type="text" id="idno" name="idno" value="<?php echo $_SESSION['idno']; ?>" class="bg-gray-100 w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" readonly>
                            </div>

                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" class="bg-gray-100 w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" readonly>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" id="firstname" name="firstname" value="<?php echo $_SESSION['firstname']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label for="middlename" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" id="middlename" name="middlename" value="<?php echo $_SESSION['midname']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" id="lastname" name="lastname" value="<?php echo $_SESSION['lastname']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label for="course" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                                <select id="course" name="course" class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="Computer Science" <?php echo ($_SESSION['course'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                                    <option value="Information Technology" <?php echo ($_SESSION['course'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" id="address" name="address" value="<?php echo $_SESSION['address']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div class="mt-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password (leave blank to keep current)</label>
                            <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div class="mt-8 flex justify-center">
                            <button type="submit" class="bg-primary hover:bg-primary/90 text-white font-medium py-2 px-6 rounded-md transition-colors duration-300 flex items-center">
                                <i class="fas fa-save mr-2"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4 mt-6">
        <div class="container mx-auto px-4 text-center text-gray-600 text-sm">
            <p>&copy; 2025 University of Cebu - College of Computer Studies</p>
            <p class="text-xs mt-1">Developed by Rafael B. Patiño</p>
            <p class="mt-1 text-xs">System Version 1</p>
        </div>
    </footer>
    
    <!-- Simple script for mobile menu toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            menuButton.addEventListener('click', function() {
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.remove('hidden');
                } else {
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>