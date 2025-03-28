<?php
session_start();
include('../../conn/db.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../login.php");
    exit();
} else if ($_SESSION['username'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// Get all number of active sit-ins
$sql = "SELECT COUNT(*) AS count FROM sit_ins WHERE check_out_time IS NULL";
$result = $conn->query($sql);
$active_count = $result->fetch_assoc()['count'];

// Get all number of active sit-ins for today
$today = date('Y-m-d');
$sql = "SELECT COUNT(*) AS count FROM sit_ins WHERE check_in_date = '$today'";
$result = $conn->query($sql);
$today_count = $result->fetch_assoc()['count'];

// Get all active sit-ins
$sql = "SELECT idno, lastname, firstname, purpose, check_in_time, check_in_date, midname, course, year_level FROM users JOIN sit_ins ON idno = student_id WHERE check_out_time IS NULL";
$result = $conn->query($sql);
$active_sit_ins = $result->fetch_all(MYSQLI_ASSOC);

// Handle check student checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['checkout_student'])) {
        $id = $_POST['sit_in_id'];
        $check_out_time = date('H:i:s');
        $sql = "UPDATE sit_ins SET check_out_time = '$check_out_time' WHERE student_id = '$id' AND check_out_time IS NULL";
        
        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Success handling - could add a success message here
            $update_sql = "UPDATE users SET sessionno = CASE WHEN sessionno > 0 THEN sessionno - 1 ELSE 0 END WHERE idno = '$id'";
            $conn->query($update_sql);
            // Redirect to refresh the page

            header("Location: sit-inManage.php");
            exit();
        } else {
            // Error handling
            $error_message = "Error updating record: " . $conn->error;
        }
    }
}
include('modals/search.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sit-in - CCS Sit-in Monitoring</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="../../public/css/all.css">
    <!-- Add Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
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

<body class="min-h-screen flex flex-col">
    <nav class="bg-white border-b border-gray-200 fixed w-full z-50 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3">
                <!-- Logo and Title -->
                <div class="flex items-center">
                    <div class="mr-3">
                        <img src="../../public/pictures/ccs-logo.png" alt="CCS Logo" class="h-10 w-10 rounded-md">
                    </div>
                    <span class="heading-font font-semibold text-lg text-primary">CCS <span class="text-secondary">Admin</span> Panel</span>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-2">
                    <a href="homepage.php" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                        <i class="fas fa-home mr-2"></i><span>Dashboard</span>
                    </a>
                    <div class="relative group">
                        <a href="students-list.php" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                            <i class="fas fa-users mr-2"></i><span>Students</span>
                        </a>
                    </div>
                    
                    <div class="relative group">
                        <a href="#" class="open-search-modal px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                            <i class="fas fa-search mr-2"></i><span>Search</span>
                        </a>
                    </div>
                    
                    <div class="relative group">
                        <button class="dropdown-button px-3 py-2 rounded-md transition duration-300 flex items-center font-medium text-primary bg-blue-50">
                            <i class="fas fa-clipboard-list mr-2"></i><span>Sit-in</span>
                            <i class="icon fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="dropdown-content absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden">
                            <a href="sit-inManage.php" class="block px-4 py-2 text-sm text-primary bg-blue-50 hover:bg-blue-100">Manage Sit-in</a>
                            <a href="sit-inRecords.php" class="block px-4 py-2 text-sm text-secondary hover:bg-gray-100">View Records</a>
                            <a href="#" class="block px-4 py-2 text-sm text-secondary hover:bg-gray-100">Reports</a>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <button class="px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                            <i class="fas fa-calendar-alt mr-2"></i><span>Reservation</span>
                        </button>
                    </div>
                    
                    <div class="relative group">
                        <button class="px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                            <i class="fas fa-comment-alt mr-2"></i><span>Feedback</span>
                        </button>
                    </div>
                    
                    <a href="../../logout.php" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i><span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container mx-auto px-4 pt-24 pb-8 flex-grow">
        <div class="py-6">
            <div class="grid grid-cols-1 grid-rows-0 gap-4 mb-6">
                <div class="bg-white rounded-lg border border-gray-300 shadow-md hover:shadow-xl transition-shadow duration-300 p-4">
                    <h2 class="text-lg font-semibold heading-font text-gray-800 mb-3">Sit-in Management</h2>
                    <!-- Statistics cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-medium text-primary">Active Sit-ins</h3>
                            <p class="text-2xl font-bold"><?php echo $active_count; ?></p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-medium text-green-600">Today's Sit-ins</h3>
                            <p class="text-2xl font-bold"><?php echo $today_count; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Sit-ins List -->
            <div class="md:col-span-2 bg-white rounded-lg border border-gray-300 shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                    <h2 class="heading-font text-lg font-semibold text-gray-800">Active Sit-ins</h2>
                    <div class="flex space-x-2">
                        <button id="refreshBtn" class="px-3 py-1.5 text-sm bg-gray-100 text-secondary rounded-md hover:bg-gray-200 focus:outline-none transition duration-200 flex items-center">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh
                        </button>
                        <button id="exportBtn" class="px-3 py-1.5 text-sm bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none transition duration-200 flex items-center">
                            <i class="fas fa-file-export mr-1"></i> Export
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="activeSitInsList">
                                <!-- Active sit-ins will be listed here -->
                                <?php if (empty($active_sit_ins)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No active sit-ins at the moment.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($active_sit_ins as $sit_in): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($sit_in['lastname'] . ', ' . $sit_in['firstname'] . ' ' . $sit_in['midname']); ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo htmlspecialchars($sit_in['course']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($sit_in['purpose']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo date('h:i A', strtotime($sit_in['check_in_time'])); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($sit_in['check_in_date'])); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-small">
                                            <form method="POST" action="" class="inline">
                                                <input type="hidden" name="sit_in_id" value="<?php echo $sit_in['idno']; ?>">
                                                <button type="submit" name="checkout_student" class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded-md transition duration-200">
                                                    Time Out
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4 mt-6">
        <div class="container mx-auto px-4 text-center text-gray-600 text-sm">
            <p>&copy; 2025 University of Cebu - College of Computer Studies</p>
            <p class="text-xs mt-1">Developed by Rafael B. Patiño</p>
        </div>
    </footer>
</body>
    <script>
        // Handle sit-in button dropdown
        const sitInButton = document.querySelector('.dropdown-button');
        const sitInDropdown = document.querySelector('.dropdown-content');

        sitInButton.addEventListener('click', () => {
            // Show dropdown
            sitInDropdown.classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!sitInButton.contains(event.target) && !sitInDropdown.contains(event.target)) {
                sitInDropdown.classList.add('hidden');
            }
        });

        // Open search modal
        document.querySelectorAll('.open-search-modal').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                searchModal.classList.remove('hidden');
            });
        });

        // Refresh button
        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });

        // Export button (placeholder functionality)
        document.getElementById('exportBtn').addEventListener('click', function() {
            alert('Export functionality will be implemented here');
        });
    </script>
</html>
