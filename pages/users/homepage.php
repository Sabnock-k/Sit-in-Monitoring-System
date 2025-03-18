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
    <link rel="stylesheet" href="../public/css/all.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#D29C00',
                        secondary: '#5E3B73',
                        light: '#F8FAFC',
                        dark: '#2c3e50',
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-gradient-to-r from-primary to-secondary text-white shadow-lg z-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center py-3">
                <div class="flex items-center mb-4 md:mb-0">
                    <img src="../../public/pictures/ccs-logo.png" alt="CCS Logo" class="h-10 w-10 bg-white rounded-full mr-3">
                    <span class="text-xl font-semibold">CCS Sit-in Monitoring</span>
                </div>
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <a href="homepage.php" class="text-white hover:bg-white/10 px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center md:justify-start active:bg-white/20">
                        <i class="fas fa-home mr-2"></i> Home
                    </a>
                    <a href="edit-profile.php" class="text-white hover:bg-white/10 px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center md:justify-start">
                        <i class="fas fa-user-edit mr-2"></i> Edit Profile
                    </a>
                    <a href="history.php" class="text-white hover:bg-white/10 px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center md:justify-start">
                        <i class="fas fa-history mr-2"></i> History
                    </a>
                    <a href="reservation.php" class="text-white hover:bg-white/10 px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center md:justify-start">
                        <i class="fas fa-calendar-plus mr-2"></i> Reservation
                    </a>
                    <a href="../../logout.php" class="text-white hover:bg-white/10 px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center md:justify-start">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pt-24 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Student Information Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-primary to-secondary text-white p-4">
                    <div class="flex items-center">
                        <i class="fas fa-user-graduate text-xl mr-2"></i>
                        <h2 class="text-lg font-semibold">Student Information</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-center mb-6">
                        <img src="../../public/pictures/profile.png" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                        <div class="font-medium text-gray-700">ID Number:</div>
                        <div><?php echo $_SESSION['idno']; ?></div>

                        <div class="font-medium text-gray-700">Name:</div>
                        <div><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['midname'] . ' ' . $_SESSION['lastname']; ?></div>

                        <div class="font-medium text-gray-700">Course:</div>
                        <div><?php echo $_SESSION['course']; ?></div>

                        <div class="font-medium text-gray-700">Year Level:</div>
                        <div><?php echo $_SESSION['year_level']; ?></div>
                        
                        <div class="font-medium text-gray-700">Sessions Left:</div>
                        <div class="font-bold text-primary"><?php echo $_SESSION['sessionno']; ?></div>
                    </div>
                </div>
            </div>

            <!-- Announcements Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-primary to-secondary text-white p-4">
                    <div class="flex items-center">
                        <i class="fas fa-bullhorn text-xl mr-2"></i>
                        <h2 class="text-lg font-semibold">Announcements</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                        <div class="border-l-4 border-primary bg-primary/5 p-4 rounded-r">
                            <h4 class="font-semibold mb-1">CCS Admin | 2025-Feb-25</h4>
                            <p class="text-gray-700">UC did it again.</p>
                        </div>
                        <div class="border-l-4 border-primary bg-primary/5 p-4 rounded-r">
                            <h4 class="font-semibold mb-1">CCS Admin | 2025-Feb-03</h4>
                            <p class="text-gray-700">The College of Computer Studies will open the registration of students for the Sit-in privilege starting tomorrow. Thank you! Lab Supervisor</p>
                        </div>
                        <div class="border-l-4 border-primary bg-primary/5 p-4 rounded-r">
                            <h4 class="font-semibold mb-1">CCS Admin | 2024-May-08</h4>
                            <p class="text-gray-700">Important Announcement We are excited to announce the launch of our new website! 🎉 Explore our latest products and services now!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rules and Regulations Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden md:col-span-2 lg:col-span-1">
                <div class="bg-gradient-to-r from-primary to-secondary text-white p-4">
                    <div class="flex items-center">
                        <i class="fas fa-clipboard-list text-xl mr-2"></i>
                        <h2 class="text-lg font-semibold">Rules and Regulations</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="max-h-[400px] overflow-y-auto pr-2">
                        <div class="text-center mb-4">
                            <div class="font-bold">University of Cebu</div>
                            <div class="font-bold">COLLEGE OF INFORMATION & COMPUTER STUDIES</div>
                            <div class="font-bold">LABORATORY RULES AND REGULATIONS</div>
                        </div>
                        <p class="mb-3">To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
                        <ol class="list-decimal pl-5 space-y-2 mb-4">
                            <li>Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans and other personal pieces of equipment must be switched off.</li>
                            <li>Games are not allowed inside the lab. This includes computer-related games, card games and other games that may disturb the operation of the lab.</li>
                            <li>Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</li>
                            <li>Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</li>
                            <li>Deleting computer files and changing the set-up of the computer is a major offense.</li>
                            <li>Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</li>
                            <li>Observe proper decorum while inside the laboratory.
                                <ul class="list-disc pl-5 mt-2">
                                    <li>Do not get inside the lab unless the instructor is present.</li>
                                    <li>All bags, knapsacks, and the likes must be deposited at the counter.</li>
                                    <li>Follow the seating arrangement of your instructor.</li>
                                    <li>At the end of class, all software programs must be closed.</li>
                                    <li>Return all chairs to their proper places after using.</li>
                                </ul>
                            </li>
                            <li>Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</li>
                            <li>Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</li>
                            <li>Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</li>
                            <li>For serious offense, the lab personnel may call the Civil Security Office (CSU) for assistance.</li>
                            <li>Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant or instructor immediately.</li>
                        </ol>
                        <div class="mt-6">
                            <div class="font-bold mb-2">DISCIPLINARY ACTIONS</div>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</li>
                                <li>Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>