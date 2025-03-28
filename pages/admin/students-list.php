<?php
session_start();
include('../../conn/db.php');
include('modals/search.php');

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

// Get all record of sit-ins
$sql = "SELECT idno, lastname, firstname, midname, course, year_level, sessionno FROM users";
$result = $conn->query($sql);
$sit_in_record = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sit-in - CCS Sit-in Monitoring</title>
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
                        <button class="dropdown-button px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                            <i class="fas fa-clipboard-list mr-2"></i><span>Sit-in</span>
                            <i class="icon fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="dropdown-content absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden">
                            <a href="sit-inManage.php" class="block px-4 py-2 text-sm text-secondary hover:bg-gray-100">Manage Sit-in</a>
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
            <!-- Active Sit-ins List -->
            <div class="md:col-span-2 bg-white rounded-lg border border-gray-300 shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                    <h2 class="heading-font text-lg font-semibold text-gray-800">Student List</h2>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Firstname</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lastname</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Middle Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session no</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="activeSitInsList">
                                <!-- Active sit-ins will be listed here -->
                                <?php if (empty($sit_in_record)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No records of sit-ins available.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($sit_in_record as $sit_in): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($sit_in['firstname']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($sit_in['lastname']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars(!empty($sit_in['midname']) ? $sit_in['midname'] : "N/A"); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($sit_in['course']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($sit_in['year_level']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($sit_in['sessionno']); ?></div>
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
