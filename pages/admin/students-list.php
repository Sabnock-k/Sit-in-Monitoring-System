<?php
session_start();
include('../../conn/db.php');
include('modals/search.php');
include('modals/edit-student.php');
include('modals/delete-student-modal.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../../login.php");
    exit();
} else if ($_SESSION['username'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// Set default records per page
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Get total number of records
$count_sql = "SELECT COUNT(*) as total FROM users";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Get records with pagination
$sql = "SELECT idno, lastname, firstname, midname, course, year_level, sessionno FROM users LIMIT $offset, $records_per_page";
$result = $conn->query($sql);
$list_of_students = $result->fetch_all(MYSQLI_ASSOC);
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
                    <a href="homepage.php" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                        <i class="fas fa-home mr-2"></i><span>Dashboard</span>
                    </a>
                    
                    <div class="relative group">
                        <a href="students-list.php" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-primary bg-blue-50">
                            <i class="fas fa-users mr-2"></i><span>Students</span>
                        </a>
                    </div>
                    
                    <div class="relative group">
                        <a href="#" class="open-search-modal px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                            <i class="fas fa-search mr-2"></i><span>Search</span>
                        </a>
                    </div>
                    
                    <div class="relative group">
                        <button class="dropdown-button px-3 py-2 rounded-md transition duration-300 flex items-center font-medium text-secondary hover:bg-gray-100">
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
                        <a href="feedback.php" class="px-3 py-2 rounded-md transition duration-300 flex items-center text-secondary hover:bg-gray-100">
                            <i class="fas fa-comment-alt mr-2"></i><span>Feedback</span>
                        </a>
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
            <!-- Active Sit-ins List -->
            <div class="md:col-span-2 bg-white rounded-lg border border-gray-300 shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                    <h2 class="heading-font text-lg font-semibold text-gray-800">List of Students</h2>
                    <div class="flex space-x-2">
                        <div class="flex items-center">
                            <label for="recordsPerPage" class="text-sm text-gray-600 mr-2">Show:</label>
                            <select id="recordsPerPage" class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-primary">
                                <option value="10" <?php echo $records_per_page == 10 ? 'selected' : ''; ?>>10</option>
                                <option value="25" <?php echo $records_per_page == 25 ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?php echo $records_per_page == 50 ? 'selected' : ''; ?>>50</option>
                                <option value="100" <?php echo $records_per_page == 100 ? 'selected' : ''; ?>>100</option>
                            </select>
                        </div>
                        <button id="refreshBtn" class="px-3 py-1.5 text-sm bg-gray-100 text-secondary rounded-md hover:bg-gray-200 focus:outline-none transition duration-200 flex items-center">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="activeSitInsList">
                                <!-- Active sit-ins will be listed here -->
                                <?php if (empty($list_of_students)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No records of sit-ins available.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($list_of_students as $student): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($student['firstname']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($student['lastname']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($student['midname']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($student['course']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($student['year_level']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($student['sessionno']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <!-- put them side by side -->
                                            <div class="flex space-x-2">
                                                <div class="text-sm text-gray-900">
                                                    <button class="edit_studentBtn px-2 py-1 text-sm bg-gray-100 text-secondary rounded-md hover:bg-gray-200 focus:outline-none transition duration-200 flex items-center"
                                                    student_idno="<?php echo htmlspecialchars($student['idno']); ?>"
                                                    student_firstname="<?php echo htmlspecialchars($student['firstname']); ?>"
                                                    student_lastname="<?php echo htmlspecialchars($student['lastname']); ?>"
                                                    student_midname="<?php echo htmlspecialchars($student['midname']); ?>"
                                                    student_course="<?php echo htmlspecialchars($student['course']); ?>"
                                                    student_year_level="<?php echo htmlspecialchars($student['year_level']); ?>"
                                                    >Edit</button>
                                                </div>
                                                <button class="delete_studentBtn px-2 py-1 text-sm bg-red-100 text-red-600 rounded-md hover:bg-red-200 focus:outline-none transition duration-200 flex items-center"
                                                    data-student-name="<?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>"
                                                    data-student-id="<?php echo htmlspecialchars($student['idno']); ?>">
                                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                                </button>
                                                <div class="text-sm text-gray-900">
                                                    <button class="px-2 py-1 text-sm bg-gray-100 text-secondary rounded-md hover:bg-gray-200 focus:outline-none transition duration-200 flex items-center">Reset</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="flex justify-between items-center mt-4 px-6 py-3">
                            <div class="text-sm text-gray-600">
                                Showing <?php echo min(($page-1)*$records_per_page+1, $total_records); ?> to <?php echo min($page*$records_per_page, $total_records); ?> of <?php echo $total_records; ?> entries
                            </div>
                            <div class="flex space-x-1">
                                <?php if($page > 1): ?>
                                    <a href="?page=1&records_per_page=<?php echo $records_per_page; ?>" class="px-3 py-1 text-sm bg-gray-100 text-secondary rounded-md hover:bg-gray-200">First</a>
                                    <a href="?page=<?php echo $page-1; ?>&records_per_page=<?php echo $records_per_page; ?>" class="px-3 py-1 text-sm bg-gray-100 text-secondary rounded-md hover:bg-gray-200">Previous</a>
                                <?php endif; ?>
                                
                                <?php 
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                
                                for($i = $start_page; $i <= $end_page; $i++): 
                                ?>
                                    <a href="?page=<?php echo $i; ?>&records_per_page=<?php echo $records_per_page; ?>" 
                                    class="px-3 py-1 text-sm <?php echo $i == $page ? 'bg-primary text-white' : 'bg-gray-100 text-secondary hover:bg-gray-200'; ?> rounded-md">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if($page < $total_pages): ?>
                                    <a href="?page=<?php echo $page+1; ?>&records_per_page=<?php echo $records_per_page; ?>" class="px-3 py-1 text-sm bg-gray-100 text-secondary rounded-md hover:bg-gray-200">Next</a>
                                    <a href="?page=<?php echo $total_pages; ?>&records_per_page=<?php echo $records_per_page; ?>" class="px-3 py-1 text-sm bg-gray-100 text-secondary rounded-md hover:bg-gray-200">Last</a>
                                <?php endif; ?>
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

        // Handle edit student button
        const student_editBtn = document.querySelectorAll('.edit_studentBtn');
        const EditStudentModal = document.getElementById('EditStudentModal');
        const closeModal = document.getElementById('closeEditStudentModal');
        
        student_editBtn.forEach(btn => {
            btn.addEventListener('click', () => {
                // Get the data from the button
                const student_idno = btn.getAttribute('student_idno');
                const student_firstname = btn.getAttribute('student_firstname');
                const student_lastname = btn.getAttribute('student_lastname');
                const student_middlename = btn.getAttribute('student_midname');
                const student_course = btn.getAttribute('student_course');
                const student_year_level = btn.getAttribute('student_year_level');

                // Set the values in the form
                document.getElementById('studentIdDisplay').value = student_idno;
                document.getElementById('firstName').value = student_firstname;
                document.getElementById('lastName').value = student_lastname;
                document.getElementById('middleName').value = student_middlename;
                document.getElementById('course').value = student_course;
                document.getElementById('yearLevel').value = student_year_level;
                
                // Show the modal
                EditStudentModal.classList.remove('hidden');
            });
        });
        
        // Close edit student modal
        closeModal.addEventListener('click', () => {
            EditStudentModal.classList.add('hidden');
        });

        const cancelDeleteStudent = document.getElementById('cancelDeleteStudent');
        const deleteStudentBtns = document.querySelectorAll('.delete_studentBtn');
        const deleteStudentModal = document.getElementById('deleteStudentModal');
        const closeDeleteStudentModal = document.getElementById('closeDeleteStudentModal');
        const confirmDeleteStudent = document.getElementById('confirmDeleteStudent');
        const studentNameToDelete = document.getElementById('studentNameToDelete');
        const studentIdToDelete = document.getElementById('studentIdToDelete');
        let currentStudentId = null;

        // Open delete confirmation modal
        deleteStudentBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const studentId = btn.getAttribute('data-student-id');
                const studentName = btn.getAttribute('data-student-name');
                
                // Set the values
                currentStudentId = studentId;
                studentNameToDelete.textContent = studentName;
                studentIdNoToDelete.textContent = studentId;
                studentIdToDelete.value = studentId;
                
                // Show the modal
                deleteStudentModal.classList.remove('hidden');
            });
        });


        // Close delete modal
        closeDeleteStudentModal.addEventListener('click', () => {
            deleteStudentModal.classList.add('hidden');
            currentStudentId = null;
        });

        // Handle delete confirmation
        confirmDeleteStudent.addEventListener('click', () => {
            if (currentStudentId) {
                // Create form data
                const formData = new FormData();
                formData.append('student_id', currentStudentId);
                
                // Send delete request
                fetch('actions/delete-student.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Check if response is valid JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, get the text and throw an error
                        return response.text().then(text => {
                            throw new Error('Received non-JSON response: ' + text);
                        });
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Reload the page to show updated list
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the student: ' + error.message);
                })
                .finally(() => {
                    // Close the modal
                    deleteStudentModal.classList.add('hidden');
                    currentStudentId = null;
                });
            }
        });


        // Cancel delete
        cancelDeleteStudent.addEventListener('click', () => {
            deleteStudentModal.classList.add('hidden');
            currentStudentId = null;
        });

        
        // Refresh button
        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });
        
        // Handle records per page dropdown
        document.getElementById('recordsPerPage').addEventListener('change', function() {
            const recordsPerPage = this.value;
            window.location.href = `?page=1&records_per_page=${recordsPerPage}`;
        });
    </script>
</body>
</html>



