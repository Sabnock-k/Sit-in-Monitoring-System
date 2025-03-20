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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CCS Sit-in Monitoring</title>
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
                    <a href="dashboard.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center font-medium text-primary bg-blue-50">
                        <i class="fas fa-home mr-2"></i> 
                        <span>Dashboard</span>
                    </a>
                    <a href="edit-profile.php" class="px-4 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
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
                <a href="dashboard.php" class="flex items-center px-3 py-2 rounded-md text-primary bg-blue-50 font-medium">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="edit-profile.php" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Welcome Card -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-custom hover:shadow-custom-hover transition-shadow duration-300">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="heading-font text-lg font-semibold text-gray-800">Welcome Back</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="mr-4">
                            <img src="../../public/pictures/profile.png" alt="Profile Picture" class="w-16 h-16 rounded-full object-cover border-2 border-primary">
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg"><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?></h3>
                            <p class="text-sm text-gray-600"><?php echo $_SESSION['course']; ?> - <?php echo $_SESSION['year_level']; ?></p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Available Sessions:</span>
                            <span class="font-semibold text-primary text-lg"><?php echo $_SESSION['sessionno']; ?></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 uppercase">ID Number</p>
                            <p class="font-medium"><?php echo $_SESSION['idno']; ?></p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 uppercase">Email Address</p>
                            <p class="font-medium"><?php echo $_SESSION['email']; ?></p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="reservation.php" class="block text-center bg-primary text-white py-2 px-4 rounded-md hover:bg-primary/90 transition-colors duration-300">
                            <i class="fas fa-calendar-plus mr-2"></i>Reserve a Session
                        </a>
                    </div>
                </div>
            </div>

            <!-- Announcements Card -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-custom hover:shadow-custom-hover transition-shadow duration-300">
                <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                    <h2 class="heading-font text-lg font-semibold text-gray-800">Announcements</h2>
                    <span class="bg-primary/10 text-primary text-xs font-medium px-2 py-1 rounded-full">3 New</span>
                </div>
                <div class="p-4">
                    <div class="space-y-4 max-h-[380px] overflow-y-auto pr-2">
                        <div class="border-l-4 border-primary bg-blue-50 p-4 rounded-r-lg">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium">CCS Admin</h4>
                                <span class="text-xs text-gray-500">Feb 25, 2025</span>
                            </div>
                            <p class="text-gray-700">UC did it again.</p>
                        </div>
                        <div class="border-l-4 border-primary bg-blue-50 p-4 rounded-r-lg">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium">CCS Admin</h4>
                                <span class="text-xs text-gray-500">Feb 3, 2025</span>
                            </div>
                            <p class="text-gray-700">The College of Computer Studies will open the registration of students for the Sit-in privilege starting tomorrow. Thank you! Lab Supervisor</p>
                        </div>
                        <div class="border-l-4 border-gray-300 bg-gray-50 p-4 rounded-r-lg">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium">CCS Admin</h4>
                                <span class="text-xs text-gray-500">May 8, 2024</span>
                            </div>
                            <p class="text-gray-700">Important Announcement: We are excited to announce the launch of our new website! 🎉 Explore our latest products and services now!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laboratory Rules Card -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-custom hover:shadow-custom-hover transition-shadow duration-300 md:col-span-2 lg:col-span-1">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="heading-font text-lg font-semibold text-gray-800">Laboratory Rules & Regulations</h2>
                </div>
                <div class="p-4">
                    <div class="max-h-[380px] overflow-y-auto pr-2">
                        <div class="text-center mb-4 bg-gray-50 p-3 rounded-lg">
                            <div class="heading-font font-semibold mb-1">UNIVERSITY OF CEBU</div>
                            <div class="text-xs text-gray-600 mb-1">COLLEGE OF INFORMATION & COMPUTER STUDIES</div>
                            <div class="text-sm font-medium text-primary">LABORATORY RULES AND REGULATIONS</div>
                        </div>
                        
                        <p class="mb-4 text-gray-700">To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
                        
                        <div class="space-y-3">
                            <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                <div class="flex items-start">
                                    <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">1</div>
                                    <p class="text-gray-700">Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans and other personal pieces of equipment must be switched off.</p>
                                </div>
                            </div>
                            
                            <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                <div class="flex items-start">
                                    <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">2</div>
                                    <p class="text-gray-700">Games are not allowed inside the lab. This includes computer-related games, card games and other games that may disturb the operation of the lab.</p>
                                </div>
                            </div>
                            
                            <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                <div class="flex items-start">
                                    <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">3</div>
                                    <p class="text-gray-700">Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</p>
                                </div>
                            </div>
                            
                            <div class="p-3 rounded-lg border border-gray-200 bg-blue-50 hover:bg-blue-100 transition-colors cursor-pointer" id="view-protocols-btn">
                                <div class="text-center text-primary">
                                    <i class="fas fa-chevron-down mr-1" id="protocols-icon"></i> <span id="protocols-text">View complete laboratory protocols</span>
                                </div>
                            </div>

                            <div id="complete-protocols" class="mt-4 hidden">
                                <div class="space-y-3">
                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">4</div>
                                            <p class="text-gray-700">Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">5</div>
                                            <p class="text-gray-700">Deleting computer files and changing the set-up of the computer is a major offense.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">6</div>
                                            <p class="text-gray-700">Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">7</div>
                                            <div>
                                                <p class="text-gray-700">Observe proper decorum while inside the laboratory.</p>
                                                <ul class="list-disc pl-5 mt-2 text-gray-700 text-sm">
                                                    <li>Do not get inside the lab unless the instructor is present.</li>
                                                    <li>All bags, knapsacks, and the likes must be deposited at the counter.</li>
                                                    <li>Follow the seating arrangement of your instructor.</li>
                                                    <li>At the end of class, all software programs must be closed.</li>
                                                    <li>Return all chairs to their proper places after using.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">8</div>
                                            <p class="text-gray-700">Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</p>
                                        </div>
                                    </div>

                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">9</div>
                                            <p class="text-gray-700">Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</p>
                                        </div>
                                    </div>

                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">10</div>
                                            <p class="text-gray-700">Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</p>
                                        </div>
                                    </div>

                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">11</div>
                                            <p class="text-gray-700">For serious offense, the lab personnel may call the Civil Security Office (CSU) for assistance.</p>
                                        </div>
                                    </div>

                                    <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-primary text-white rounded-full h-5 w-5 flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">12</div>
                                            <p class="text-gray-700">Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant or instructor immediately.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h3 class="heading-font font-medium mb-2 text-gray-800">DISCIPLINARY ACTION</h3>
                            <div class="p-3 rounded-lg border border-gray-200 bg-gray-50">
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 rounded-full bg-yellow-500 mr-2"></div>
                                        <p class="text-gray-700">First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</p>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 rounded-full bg-red-500 mr-2"></div>
                                        <p class="text-gray-700">Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            
            // Add the new functionality for the protocols button
            const protocolsBtn = document.getElementById('view-protocols-btn');
            const completeProtocols = document.getElementById('complete-protocols');
            const protocolsIcon = document.getElementById('protocols-icon');
            const protocolsText = document.getElementById('protocols-text');
            
            protocolsBtn.addEventListener('click', function() {
                if (completeProtocols.classList.contains('hidden')) {
                    // Show the complete protocols
                    completeProtocols.classList.remove('hidden');
                    // Add a subtle animation
                    completeProtocols.classList.add('animate-fadeIn');
                    // Change the icon to up arrow
                    protocolsIcon.classList.remove('fa-chevron-down');
                    protocolsIcon.classList.add('fa-chevron-up');
                    // Change the text
                    protocolsText.textContent = 'Hide complete laboratory protocols';
                    // Change button background
                    protocolsBtn.classList.remove('bg-blue-50');
                    protocolsBtn.classList.add('bg-blue-100');
                } else {
                    // Hide the complete protocols
                    completeProtocols.classList.add('hidden');
                    // Change the icon back to down arrow
                    protocolsIcon.classList.remove('fa-chevron-up');
                    protocolsIcon.classList.add('fa-chevron-down');
                    // Change the text back
                    protocolsText.textContent = 'View complete laboratory protocols';
                    // Change button background back
                    protocolsBtn.classList.remove('bg-blue-100');
                    protocolsBtn.classList.add('bg-blue-50');
                }
            });
        });
    </script>
</body>
</html>