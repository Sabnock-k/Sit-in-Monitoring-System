<?php
session_start();
include '../../conn/db.php';
include 'modals/feedback.php';

// Get the user's sit-in records
$user_id = $_SESSION['idno'];
$sql = "SELECT * FROM sit_ins WHERE student_id = $user_id";
$sit_in_record = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - CCS Sit-in Monitoring</title>
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
                    <a href="homepage.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center font-medium text-secondary hover:bg-gray-100">
                        <i class="fas fa-home mr-2"></i> 
                        <span>Dashboard</span>
                    </a>
                    <a href="edit-profile.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                        <i class="fas fa-user-edit mr-2"></i> 
                        <span>Profile</span>
                    </a>
                    <a href="history.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center text-primary bg-blue-50">
                        <i class="fas fa-history mr-2"></i> 
                        <span>History</span>
                    </a>
                    <a href="reservation.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                        <i class="fas fa-calendar-plus mr-2"></i> 
                        <span>Reserve</span>
                    </a>
                    <a href="../../logout.php" class="ml-2 px-4 py-2 rounded-md transition duration-300 flex items-center text-white bg-primary hover:bg-primary/90">
                        <i class="fas fa-sign-out-alt mr-2"></i> 
                        <span>Log Out</span>
                    </a>
                </div>
                <div class="md:hidden">
                    <button type="button" class="text-gray-500 hover:text-secondary focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile menu - Hidden by default -->
    <div class="fixed inset-0 z-40 bg-black bg-opacity-25 transform transition-opacity duration-300 opacity-0 pointer-events-none md:hidden" id="mobile-menu-overlay">
        <div class="absolute right-0 top-0 h-full w-64 bg-white shadow-lg transform transition-transform duration-300 translate-x-full">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="heading-font font-semibold text-primary">Menu</span>
                    <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none" id="close-mobile-menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="py-2">
                <a href="homepage.php" class="block px-4 py-2 text-secondary hover:bg-gray-100">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="edit-profile.php" class="block px-4 py-2 text-secondary hover:bg-gray-100">
                    <i class="fas fa-user-edit mr-2"></i> Profile
                </a>
                <a href="history.php" class="block px-4 py-2 text-primary bg-blue-50">
                    <i class="fas fa-history mr-2"></i> History
                </a>
                <a href="reservation.php" class="block px-4 py-2 text-secondary hover:bg-gray-100">
                    <i class="fas fa-calendar-plus mr-2"></i> Reserve
                </a>
                <a href="../../logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pt-24 pb-8 flex-grow">
        <div class="">
            <!-- Feedback Success Message -->
            <?php if (isset($feedback_message)): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-md">
                <p><?php echo $feedback_message; ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Feedback Error Message -->
            <?php if (isset($feedback_error)): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-md">
                <p><?php echo $feedback_error; ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Sit-ins List -->
            <div class="md:col-span-2 bg-white rounded-lg border border-gray-300 shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                    <h2 class="heading-font text-lg font-semibold text-gray-800">Sit-in Records</h2>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feedback</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="activeSitInsList">
                                <!-- Active sit-ins will be listed here -->
                                <?php if ($sit_in_record->num_rows == 0): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No records of sit-ins available.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php while ($sit_in = $sit_in_record->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($sit_in['purpose']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo date('h:i A', strtotime($sit_in['check_in_time'])); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($sit_in['check_in_date'])); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if (!empty($sit_in['check_out_time'])): ?>
                                                <div class="text-sm text-gray-900"><?php echo date('h:i A', strtotime($sit_in['check_out_time'])); ?></div>
                                            <?php else: ?>
                                                <div class="text-sm text-yellow-600">Not checked out</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if (!empty($sit_in['check_out_time']) && empty($sit_in['feedback'])): ?>
                                                <button 
                                                    data-id="<?php echo $sit_in['student_id']; ?>" 
                                                    class="feedback-btn px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 focus:outline-none transition duration-200 flex items-center">
                                                    <i class="fas fa-comment mr-1"></i> Add Feedback
                                                </button>
                                            <?php elseif (!empty($sit_in['feedback'])): ?>
                                                <div class="text-sm text-gray-900">
                                                    <div class="flex text-yellow-400">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <?php if($i <= $sit_in['rating']): ?>
                                                                <i class="fas fa-heart"></i>
                                                            <?php else: ?>
                                                                <i class="far fa-heart"></i>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="text-xs text-gray-600"><?php echo htmlspecialchars($sit_in['feedback']); ?></span>
                                                </div>
                                            <?php elseif (empty($sit_in['check_out_time'])): ?>
                                                <div class="text-sm text-gray-500">Complete session first</div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
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

    <script>
        // Handle mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        
        if (mobileMenuButton && mobileMenuOverlay && closeMobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenuOverlay.classList.toggle('opacity-0');
                mobileMenuOverlay.classList.toggle('pointer-events-none');
                mobileMenuOverlay.querySelector('div').classList.toggle('translate-x-full');
            });
            
            closeMobileMenu.addEventListener('click', () => {
                mobileMenuOverlay.classList.add('opacity-0');
                mobileMenuOverlay.classList.add('pointer-events-none');
                mobileMenuOverlay.querySelector('div').classList.add('translate-x-full');
            });
        }
        
        // Handle feedback modal
        const feedbackBtns = document.querySelectorAll('.feedback-btn');
        const feedbackModal = document.getElementById('feedbackModal');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        
        feedbackBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                feedbackModal.classList.remove('hidden');
            });
        });
        
        if (closeModal) {
            closeModal.addEventListener('click', () => {
                feedbackModal.classList.add('hidden');
            });
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                feedbackModal.classList.add('hidden');
            });
        }
        
        // Handle refresh button
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                window.location.reload();
            });
        }
        
        // Handle export button
        // Note: This is a placeholder. Server-side implementation would be needed for actual export functionality.
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                alert('Export functionality will be implemented soon!');
            });
        }
    </script>
</body>
</html>