<?php
session_start();
include('../../conn/db.php');
include('modals/search.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../login.php");
    exit();
} else if ($_SESSION['username'] !== 'admin') {
    header("Location: ../users/homepage.php");
    exit();
}

// Get all number of students
$sql = "SELECT COUNT(*) AS total_students FROM users";
$result = $conn->query($sql);
$total_students = $result->fetch_assoc()['total_students'];

// Call all announcements from the database
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$announcements = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
        $sql = "INSERT INTO announcements (title, content, created_by) 
                VALUES ('$title', '$content', '$created_by')";
        
        // Execute query
        if ($conn->query($sql) === TRUE) {
            $success_message = "Announcement created successfully!";
            // Clear form data
            $_POST = array();

            // Refresh the announcements list
            $sql = "SELECT * FROM announcements ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            $announcements = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

// Edit announcement form
$edit_success_message = '';
$edit_error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_announcement'])) {
    // Validate and sanitize input
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = $conn->real_escape_string(trim($_POST['title'] ?? ''));
    $content = $conn->real_escape_string(trim($_POST['content'] ?? ''));

    // Check for empty fields
    if ($id <= 0 || empty($title) || empty($content)) {
        $edit_error_message = "All fields are required.";
    } else {
        // Prepare and execute the query securely using prepared statements
        $stmt = $conn->prepare("UPDATE announcements SET title = ?, content = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ssi", $title, $content, $id);
            
            if ($stmt->execute()) {
                $edit_success_message = "Announcement updated successfully!";
                $_POST = array();
                $stmt->close();
                
                // Refresh the announcements list
                $sql = "SELECT * FROM announcements ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);
                $announcements = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $error_message = "Error updating announcement: " . $stmt->error;
                $stmt->close();
            }
        } else {
            $error_message = "Database error: " . $conn->error;
        }
    }
}

// Delete an announcement
$delete_success_message = '';
$delete_error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_announcement'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $delete_success_message = "Announcement deleted successfully!";
                $_POST = array();

                // Refresh the announcements list
                $sql = "SELECT * FROM announcements ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);
                $announcements = mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                $delete_error_message = "Error deleting announcement: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $delete_error_message = "Database error: " . $conn->error;
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
    <title>Dashboard - CCS Sit-in Monitoring</title>
    <!-- Add Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="../../public/css/all.css">
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

<body>
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
                    <a href="homepage.php" class="px-3 py-2 rounded-md transition duration-300 flex items-center font-medium text-primary bg-blue-50">
                        <i class="fas fa-home mr-2"></i><span>Dashboard</span>
                    </a>
                    
                    <div class="relative group">
                        <a href="#" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-users mr-2"></i><span>Students</span>
                        </a>
                    </div>
                    
                    <div class="relative group">
                        <a href="#" class="open-search-modal px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-search mr-2"></i><span>Search</span>
                        </a>
                    </div>
                    
                    <div class="relative group">
                        <button class="dropdown-button px-3 py-2 rounded-md transition duration-300 flex items-center text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-clipboard-list mr-2"></i><span>Sit-in</span>
                            <i class="icon fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="dropdown-content absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Manage Sit-in</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Records</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Reports</a>
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
            <div class="grid grid-cols-1 grid-rows-0 gap-4 mb-6">
                <div class="bg-white rounded-lg border border-gray-300 shadow-md hover:shadow-xl transition-shadow duration-300 p-4">
                    <h2 class="text-lg font-semibold heading-font text-gray-800 mb-3">Dashboard Overview</h2>
                    <!-- Add your dashboard content here -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Statistics cards or other content -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-medium text-primary">Total Students</h3>
                            <p class="text-2xl font-bold"><?php echo htmlspecialchars($total_students)?></p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-medium text-green-600">Active Sit-ins</h3>
                            <p class="text-2xl font-bold">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcement -->
            <div class="grid grid-cols-2 grid-rows-1 gap-4">
                <div class="bg-white rounded-lg border border-gray-300 shadow-md hover:shadow-xl transition-shadow duration-300 p-4">
                    <h2 class="text-lg font-semibold heading-font text-gray-800 mb-3">Create Announcement</h2>
                    
                    <?php if(!empty($success_message)): ?>
                        <div id="alert-box" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded text-sm" role="alert">
                            <p><i class="fas fa-check-circle mr-1"></i> <?php echo $success_message; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($error_message)): ?>
                        <div id="alert-box" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-sm" role="alert">
                            <p><i class="fas fa-exclamation-circle mr-1"></i> <?php echo $error_message; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form id="announcementForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-4">
                        <!-- Title Field -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-primary focus:border-primary" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                        </div>
                        
                        <!-- Content Field -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                            <textarea id="content" name="content" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-primary focus:border-primary" rows="4"><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" id="clearBtn" class="px-3 py-1.5 text-sm border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200">
                                Clear
                            </button>
                            <button type="submit" name="create_announcement" class="px-3 py-1.5 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200">
                                Post
                            </button>
                        </div>
                    </form>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 shadow-custom hover:shadow-custom-hover transition-shadow duration-300">
                    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                        <h2 class="heading-font text-lg font-semibold text-gray-800">Announcements</h2>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4 max-h-[380px] overflow-y-auto pr-2">
                            <!-- Display message -->
                            <?php if(!empty($delete_success_message)): ?>
                                <div id="alert-box" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded text-sm" role="alert">
                                    <p><i class="fas fa-check-circle mr-1"></i> <?php echo $delete_success_message; ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($delete_error_message)): ?>
                                <div id="alert-box" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-sm" role="alert">
                                    <p><i class="fas fa-exclamation-circle mr-1"></i> <?php echo $delete_error_message; ?></p>
                                </div>
                            <?php endif; ?>
                            <!-- Display all announcements -->
                            <?php foreach ($announcements as $announcement): ?>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-medium"><?php echo htmlspecialchars($announcement['title']); ?></h4>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($announcement['created_at'])); ?></span>
                                            <!-- Edit Button -->
                                            <button 
                                                class="edit-btn px-2 py-1 text-xs bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200"
                                                data-id="<?php echo $announcement['id']; ?>"
                                                data-title="<?php echo htmlspecialchars($announcement['title']); ?>"
                                                data-content="<?php echo htmlspecialchars($announcement['content']); ?>">
                                                Edit
                                            </button>
                                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="inline">
                                                <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">
                                                <button type="submit" name="delete_announcement" class="del-btn px-2 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-200">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class="text-gray-700"><?php echo htmlspecialchars($announcement['content']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Announcement Modal -->
                <div id="editModal" class="<?php echo (!empty($edit_success_message) || !empty($edit_error_message)) ? '' : 'hidden'; ?> fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                        <h3 class="text-lg font-semibold mb-4">Edit Announcement</h3>
                        <!-- Display message -->
                        <?php if(!empty($edit_success_message)): ?>
                            <div id="alert-box" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded text-sm" role="alert">
                                <p><i class="fas fa-check-circle mr-1"></i> <?php echo $edit_success_message; ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($edit_error_message)): ?>
                            <div id="alert-box" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-sm" role="alert">
                                <p><i class="fas fa-exclamation-circle mr-1"></i> <?php echo $edit_error_message; ?></p>
                            </div>
                        <?php endif; ?>
                        <form id="editAnnouncementForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <input type="hidden" id="editId" name="id">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" id="editTitle" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Content</label>
                                <textarea id="editContent" name="content" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" rows="4"></textarea>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" id="closeEditModal" class="px-3 py-1.5 text-sm bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Cancel</button>
                                <button type="submit" name="edit_announcement" class="px-3 py-1.5 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">Save Changes</button>
                            </div>
                        </form>
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
    //Handle sit-in button dropdown
    const sitInButton = document.querySelector('.dropdown-button');
    const sitInDropdown = document.querySelector('.dropdown-content');

    sitInButton.addEventListener('click', () => {
        //show dropdown
        sitInDropdown.classList.toggle('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!sitInButton.contains(event.target) && !sitInDropdown.contains(event.target)) {
            sitInDropdown.classList.add('hidden');
        }
    });

    setTimeout(() => {
        const alertBox = document.getElementById('alert-box');
        if (alertBox) {
            alertBox.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => alertBox.remove(), 500);
        }
    }, 3000); // Hide after 3 seconds

    document.getElementById('clearBtn').addEventListener('click', function () {
        document.getElementById('announcementForm').reset();
    });

    // Open edit modal and populate fields
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('editId').value = this.dataset.id;
            document.getElementById('editTitle').value = this.dataset.title;
            document.getElementById('editContent').value = this.dataset.content;
            document.getElementById('editModal').classList.remove('hidden');
        });
    });

    // Success edit modal
    <?php if(!empty($edit_success_message)): ?>
        setTimeout(() => {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('hideIfMessage').classList.add('hidden');
        }, 3000); // Close modal after 3 seconds
    <?php endif; ?>

    // Close edit modal
    document.getElementById('closeEditModal').addEventListener('click', function () {
        document.getElementById('editModal').classList.add('hidden');
    });

    // Open search modal
    document.querySelectorAll('.open-search-modal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            searchModal.classList.remove('hidden');
        });
    });

    // Add this to the existing script section
    // Close search modal
    document.getElementById('closeSearchModal').addEventListener('click', function() {
        document.getElementById('searchModal').classList.add('hidden');
    });

    // Define searchModal variable for use in the existing code
    const searchModal = document.getElementById('searchModal');

</script>
</html>
