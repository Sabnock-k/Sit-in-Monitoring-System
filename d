<?php
session_start();
include('../../conn/db.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../login.php");
    exit();
} else if ($_SESSION['username'] !== 'admin') {
    header("Location: ../users/homepage.php");
    exit();
}

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_announcement'])) {
    // Get form data
    $title = $conn->real_escape_string(trim($_POST['title']));
    $content = $conn->real_escape_string(trim($_POST['content']));
    $created_by = $_SESSION['username'];
    
    // Validate data
    if (empty($title) || empty($content)) {
        $error_message = "Title and content are required fields.";
    } else {
        // Create SQL query
        if ($expiry_date !== NULL) {
            $sql = "INSERT INTO announcements (title, content, created_by) 
                    VALUES ('$title', '$content', '$created_by')";
        } else {
            $sql = "INSERT INTO announcements (title, content) 
                    VALUES ('$title', '$content', '$created_by')";
        }
        
        // Execute query
        if ($conn->query($sql) === TRUE) {
            $success_message = "Announcement created successfully!";
            // Clear form data
            $_POST = array();
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement - CCS Admin Panel</title>
    <!-- Add Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="../../public/css/all.css">
    <!-- TinyMCE for rich text editor -->
    <script src="https://cdn.tiny.cloud/1/79559g884klo3hq6j8ac9wqxvlcn4aq5oy8in5fs0jirep4y/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: 'textarea',
        plugins: [
        // Core editing features
        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
        // Your account includes a free trial of TinyMCE premium features
        // Try the most popular premium features until Apr 6, 2025:
        'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
    });
    </script>
    
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

<body class="bg-gray-50">
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
                    <a href="homepage.php" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-home mr-2"></i><span>Dashboard</span>
                    </a>
                    
                    <div class="relative group">
                        <a href="#" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-users mr-2"></i><span>Students</span>
                        </a>
                    </div>
                    
                    <div class="relative group">
                        <a href="#" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-search mr-2"></i><span>Search</span>
                        </a>
                    </div>
                    
                    <div class="relative group">
                        <button class="dropdown-button px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-clipboard-list mr-2"></i><span>Sit-in</span>
                            <i class="icon fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="dropdown-content absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Manage Sit-in</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Records</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Reports</a>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <button class="announce-dropdown-button px-3 py-2 rounded-md transition duration-300 flex items-center font-medium text-primary bg-blue-50">
                            <i class="fas fa-bullhorn mr-2"></i><span>Announcements</span>
                            <i class="icon fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="announce-dropdown-content absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50">
                            <a href="create_announcement.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-medium text-primary">Create Announcement</a>
                            <a href="manage_announcements.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Manage Announcements</a>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <button class="px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-calendar-alt mr-2"></i><span>Reservation</span>
                        </button>
                    </div>
                    
                    <div class="relative group">
                        <button class="px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
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
    <div class="pt-16 px-4 md:px-6 lg:px-8 container mx-auto max-w-6xl">
        <div class="py-6">
            <h1 class="text-2xl font-bold heading-font text-gray-800 mb-6">Create Announcement</h1>
            
            <?php if(!empty($success_message)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p><i class="fas fa-check-circle mr-2"></i> <?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($error_message)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p><i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-lg shadow-custom p-6">
                <form id="announcementForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-6">
                    <!-- Title Field -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Announcement Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                    </div>
                    
                    <!-- Content Field -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Announcement Content <span class="text-red-500">*</span></label>
                        <textarea id="content" name="content" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" rows="6" required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" id="clearBtn" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200">
                            Clear Form
                        </button>
                        <button type="submit" name="create_announcement" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200">
                            Create Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#content',
            height: 300,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Inter,Arial,sans-serif; font-size:16px }'
        });

        // Handle Form Clear
        document.getElementById('clearBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to clear the form?')) {
                document.getElementById('announcementForm').reset();
                tinymce.get('content').setContent('');
            }
        });

        // Dropdown functionality for the announcement menu
        const announceButton = document.querySelector('.announce-dropdown-button');
        const announceDropdown = document.querySelector('.announce-dropdown-content');

        announceButton.addEventListener('click', () => {
            announceDropdown.classList.toggle('hidden');
        });

        // Sit-in dropdown functionality 
        const sitInButton = document.querySelector('.dropdown-button');
        const sitInDropdown = document.querySelector('.dropdown-content');

        sitInButton.addEventListener('click', () => {
            sitInDropdown.classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!announceButton.contains(event.target) && !announceDropdown.contains(event.target)) {
                announceDropdown.classList.add('hidden');
            }
            
            if (!sitInButton.contains(event.target) && !sitInDropdown.contains(event.target)) {
                sitInDropdown.classList.add('hidden');
            }
        });

        // Form validation
        document.getElementById('announcementForm').addEventListener('submit', function(event) {
            let title = document.getElementById('title').value.trim();
            
            if (title === '') {
                event.preventDefault();
                alert('Please enter an announcement title');
                return false;
            }
            
            // Get content from TinyMCE
            let content = tinymce.get('content').getContent().trim();
            
            if (content === '') {
                event.preventDefault();
                alert('Please enter announcement content');
                return false;
            }
            
            // Additional validation can be added here
            
            return true;
        });
    </script>
</body>
</html>