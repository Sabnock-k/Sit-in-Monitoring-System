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
                        accent: '#8A2BE2',
                        dark: '#1A1A2E',
                        glow: '#FFD700',
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    },
                    backdropBlur: {
                        xs: '2px',
                    },
                    boxShadow: {
                        'neon': '0 0 5px theme("colors.primary"), 0 0 20px theme("colors.primary")',
                        'neon-purple': '0 0 5px theme("colors.secondary"), 0 0 20px theme("colors.secondary")',
                    }
                },
            },
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Rajdhani:wght@300;400;500;600;700&display=swap');
        
        .font-future {
            font-family: 'Orbitron', sans-serif;
        }
        
        .font-future-body {
            font-family: 'Rajdhani', sans-serif;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(26, 26, 46, 0.1);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #D29C00, #5E3B73);
            border-radius: 10px;
        }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        /* Animated border */
        .border-animated::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid transparent;
            border-radius: inherit;
            background: linear-gradient(90deg, #D29C00, #5E3B73, #D29C00) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            animation: rotate 4s linear infinite;
        }
        
        @keyframes rotate {
            0% {
                background-position: 0% center;
            }
            100% {
                background-position: 200% center;
            }
        }
        
        /* Neon text effect */
        .text-neon {
            text-shadow: 0 0 5px #D29C00, 0 0 10px #D29C00;
        }
        
        .text-neon-purple {
            text-shadow: 0 0 5px #5E3B73, 0 0 10px #5E3B73;
        }
        
        /* Background with subtle hex pattern */
        .hex-bg {
            background-color: #0a0a1a;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='49' viewBox='0 0 28 49'%3E%3Cg fill-rule='evenodd'%3E%3Cg id='hexagons' fill='%235E3B73' fill-opacity='0.05' fill-rule='nonzero'%3E%3Cpath d='M13.99 9.25l13 7.5v15l-13 7.5L1 31.75v-15l12.99-7.5zM3 17.9v12.7l10.99 6.34 11-6.35V17.9l-11-6.34L3 17.9zM0 15l12.98-7.5V0h-2v6.35L0 12.69v2.3zm0 18.5L12.98 41v8h-2v-6.85L0 35.81v-2.3zM15 0v7.5L27.99 15H28v-2.31h-.01L17 6.35V0h-2zm0 49v-8l12.99-7.5H28v2.31h-.01L17 42.15V49h-2z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="hex-bg min-h-screen font-future-body text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 glass border-b border-primary/30 z-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center py-3">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="relative mr-3">
                        <div class="absolute inset-0 bg-primary rounded-full blur-sm animate-pulse-slow"></div>
                        <img src="../../public/pictures/ccs-logo.png" alt="CCS Logo" class="h-10 w-10 bg-white rounded-full relative z-10">
                    </div>
                    <span class="text-xl font-future font-semibold text-neon">CCS <span class="text-primary">SIT-IN</span> MONITORING</span>
                </div>
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-1 w-full md:w-auto">
                    <a href="dashboard.php" class="px-4 py-2 rounded-lg transition duration-300 flex items-center justify-center md:justify-start hover:bg-primary/20 border border-transparent hover:border-primary/50 backdrop-blur-xs group">
                        <i class="fas fa-home mr-2 group-hover:text-neon"></i> 
                        <span class="font-future text-sm">HOME</span>
                    </a>
                    <a href="edit-profile.php" class="px-4 py-2 rounded-lg transition duration-300 flex items-center justify-center md:justify-start hover:bg-primary/20 border border-transparent hover:border-primary/50 backdrop-blur-xs group">
                        <i class="fas fa-user-edit mr-2 group-hover:text-neon"></i> 
                        <span class="font-future text-sm">PROFILE</span>
                    </a>
                    <a href="history.php" class="px-4 py-2 rounded-lg transition duration-300 flex items-center justify-center md:justify-start hover:bg-primary/20 border border-transparent hover:border-primary/50 backdrop-blur-xs group">
                        <i class="fas fa-history mr-2 group-hover:text-neon"></i> 
                        <span class="font-future text-sm">HISTORY</span>
                    </a>
                    <a href="reservation.php" class="px-4 py-2 rounded-lg transition duration-300 flex items-center justify-center md:justify-start hover:bg-primary/20 border border-transparent hover:border-primary/50 backdrop-blur-xs group">
                        <i class="fas fa-calendar-plus mr-2 group-hover:text-neon"></i> 
                        <span class="font-future text-sm">RESERVE</span>
                    </a>
                    <a href="../../logout.php" class="ml-0 md:ml-2 px-4 py-2 rounded-lg transition duration-300 flex items-center justify-center md:justify-start bg-gradient-to-r from-secondary/80 to-secondary hover:from-secondary hover:to-secondary border border-secondary/50 group">
                        <i class="fas fa-sign-out-alt mr-2 group-hover:text-neon-purple"></i> 
                        <span class="font-future text-sm">EXIT</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pt-24 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Student Information Card -->
            <div class="relative overflow-hidden rounded-xl border border-primary/30 glass hover:shadow-neon transition-shadow duration-300 group">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/10 rounded-xl"></div>
                <div class="bg-gradient-to-r from-primary to-secondary h-1 w-full"></div>
                <div class="p-4 border-b border-primary/30 flex items-center">
                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center mr-3">
                        <i class="fas fa-user-graduate text-primary"></i>
                    </div>
                    <h2 class="text-lg font-future font-semibold">STUDENT_PROFILE</h2>
                    <div class="ml-auto flex space-x-1">
                        <div class="h-2 w-2 rounded-full bg-primary animate-pulse"></div>
                        <div class="h-2 w-2 rounded-full bg-secondary animate-pulse delay-150"></div>
                    </div>
                </div>
                <div class="p-6 relative">
                    <div class="flex justify-center mb-6 group-hover:animate-float transition-all duration-300">
                        <div class="relative">
                            <div class="absolute inset-0 bg-primary/30 rounded-full blur-md group-hover:blur-lg transition-all duration-300"></div>
                            <img src="../../public/pictures/profile.png" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover border-2 border-primary/50 relative z-10">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                        <div class="font-future text-xs text-primary uppercase tracking-wider">ID_NUMBER:</div>
                        <div class="font-mono"><?php echo $_SESSION['idno']; ?></div>

                        <div class="font-future text-xs text-primary uppercase tracking-wider">NAME:</div>
                        <div class="font-mono"><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['midname'] . ' ' . $_SESSION['lastname']; ?></div>

                        <div class="font-future text-xs text-primary uppercase tracking-wider">COURSE:</div>
                        <div class="font-mono"><?php echo $_SESSION['course']; ?></div>

                        <div class="font-future text-xs text-primary uppercase tracking-wider">LEVEL:</div>
                        <div class="font-mono"><?php echo $_SESSION['year_level']; ?></div>
                        
                        <div class="font-future text-xs text-primary uppercase tracking-wider">SESSIONS_LEFT:</div>
                        <div class="font-future font-bold text-primary text-neon"><?php echo $_SESSION['sessionno']; ?></div>
                    </div>
                    <div class="absolute bottom-3 right-3 opacity-20 text-2xl">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                </div>
            </div>

            <!-- Announcements Card -->
            <div class="relative overflow-hidden rounded-xl border border-primary/30 glass hover:shadow-neon transition-shadow duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/10 rounded-xl"></div>
                <div class="bg-gradient-to-r from-primary to-secondary h-1 w-full"></div>
                <div class="p-4 border-b border-primary/30 flex items-center">
                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center mr-3">
                        <i class="fas fa-bullhorn text-primary"></i>
                    </div>
                    <h2 class="text-lg font-future font-semibold">ANNOUNCEMENTS</h2>
                    <div class="ml-auto flex space-x-1">
                        <div class="h-2 w-2 rounded-full bg-primary animate-pulse"></div>
                        <div class="h-2 w-2 rounded-full bg-secondary animate-pulse delay-150"></div>
                    </div>
                </div>
                <div class="p-6 relative">
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                        <div class="backdrop-blur-sm bg-primary/5 p-4 rounded-lg border border-primary/30 hover:border-primary/50 transition-all duration-300">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-future text-sm">CCS_ADMIN</h4>
                                <span class="text-xs opacity-70 font-mono">2025-02-25T14:30:00Z</span>
                            </div>
                            <p class="text-gray-300">UC did it again.</p>
                        </div>
                        <div class="backdrop-blur-sm bg-primary/5 p-4 rounded-lg border border-primary/30 hover:border-primary/50 transition-all duration-300">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-future text-sm">CCS_ADMIN</h4>
                                <span class="text-xs opacity-70 font-mono">2025-02-03T09:15:00Z</span>
                            </div>
                            <p class="text-gray-300">The College of Computer Studies will open the registration of students for the Sit-in privilege starting tomorrow. Thank you! Lab Supervisor</p>
                        </div>
                        <div class="backdrop-blur-sm bg-primary/5 p-4 rounded-lg border border-primary/30 hover:border-primary/50 transition-all duration-300">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-future text-sm">CCS_ADMIN</h4>
                                <span class="text-xs opacity-70 font-mono">2024-05-08T13:45:00Z</span>
                            </div>
                            <p class="text-gray-300">Important Announcement We are excited to announce the launch of our new website! 🎉 Explore our latest products and services now!</p>
                        </div>
                    </div>
                    <div class="absolute bottom-3 right-3 opacity-20 text-2xl">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                </div>
            </div>

            <!-- Rules and Regulations Card -->
            <div class="relative overflow-hidden rounded-xl border border-primary/30 glass hover:shadow-neon transition-shadow duration-300 md:col-span-2 lg:col-span-1">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/10 rounded-xl"></div>
                <div class="bg-gradient-to-r from-primary to-secondary h-1 w-full"></div>
                <div class="p-4 border-b border-primary/30 flex items-center">
                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center mr-3">
                        <i class="fas fa-clipboard-list text-primary"></i>
                    </div>
                    <h2 class="text-lg font-future font-semibold">SYSTEM_PROTOCOLS</h2>
                    <div class="ml-auto flex space-x-1">
                        <div class="h-2 w-2 rounded-full bg-primary animate-pulse"></div>
                        <div class="h-2 w-2 rounded-full bg-secondary animate-pulse delay-150"></div>
                    </div>
                </div>
                <div class="p-6 relative">
                    <div class="max-h-[400px] overflow-y-auto pr-2">
                        <div class="text-center mb-6">
                            <div class="font-future font-bold text-primary mb-1">UNIVERSITY OF CEBU</div>
                            <div class="font-future text-xs tracking-wider mb-1">COLLEGE OF INFORMATION & COMPUTER STUDIES</div>
                            <div class="font-future text-sm text-primary mb-3">LABORATORY PROTOCOLS</div>
                            <div class="h-0.5 w-24 mx-auto bg-gradient-to-r from-transparent via-primary to-transparent"></div>
                        </div>
                        
                        <p class="mb-5 text-gray-300">To maintain optimal system integrity and user cooperation in laboratory environments, adhere to the following protocols:</p>
                        
                        <div class="space-y-4">
                            <div class="backdrop-blur-sm bg-primary/5 p-3 rounded-lg border border-primary/20">
                                <div class="flex items-start">
                                    <div class="font-future text-primary mr-2">01</div>
                                    <p class="text-gray-300">Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans and other personal pieces of equipment must be switched off.</p>
                                </div>
                            </div>
                            
                            <div class="backdrop-blur-sm bg-primary/5 p-3 rounded-lg border border-primary/20">
                                <div class="flex items-start">
                                    <div class="font-future text-primary mr-2">02</div>
                                    <p class="text-gray-300">Games are not allowed inside the lab. This includes computer-related games, card games and other games that may disturb the operation of the lab.</p>
                                </div>
                            </div>
                            
                            <div class="backdrop-blur-sm bg-primary/5 p-3 rounded-lg border border-primary/20">
                                <div class="flex items-start">
                                    <div class="font-future text-primary mr-2">03</div>
                                    <p class="text-gray-300">Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</p>
                                </div>
                            </div>
                            
                            <div class="group cursor-pointer relative">
                                <div class="absolute -right-2 -top-2 bg-primary text-black text-xs rounded-full h-5 w-5 flex items-center justify-center font-future">+</div>
                                <div class="backdrop-blur-sm bg-primary/5 p-3 rounded-lg border border-primary/20 group-hover:border-primary/50 transition-all duration-300">
                                    <p class="text-gray-400 text-sm">View full laboratory protocols...</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 mb-2">
                            <div class="font-future text-sm text-primary mb-3">DISCIPLINARY MATRIX</div>
                            <div class="backdrop-blur-sm bg-primary/5 p-3 rounded-lg border border-primary/20">
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 rounded-full bg-primary mr-2"></div>
                                        <p class="text-gray-300">First Violation: Suspension from system access - requires administrative override</p>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 rounded-full bg-secondary mr-2"></div>
                                        <p class="text-gray-300">Subsequent Violations: Elevated security response - extended restriction period</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-3 right-3 opacity-20 text-2xl">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative elements -->
    <div class="fixed bottom-5 left-5 text-primary opacity-30 text-xs font-mono">SYS.VER.2025.03</div>
    
    <!-- Subtle particle effect using a simple script -->
    <div id="particles" class="fixed inset-0 pointer-events-none"></div>
    <script>
        // Simple particle effect
        const particlesContainer = document.getElementById('particles');
        
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            
            // Random size between 1-3px
            const size = Math.random() * 2 + 1;
            
            // Styling
            particle.style.position = 'absolute';
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.backgroundColor = Math.random() > 0.5 ? '#D29C00' : '#5E3B73';
            particle.style.borderRadius = '50%';
            particle.style.opacity = (Math.random() * 0.5 + 0.1).toString();
            
            // Random position
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            
            // Animation
            particle.style.animation = `float ${5 + Math.random() * 10}s ease-in-out infinite`;
            particle.style.animationDelay = `${Math.random() * 5}s`;
            
            particlesContainer.appendChild(particle);
        }
    </script>
</body>
</html>