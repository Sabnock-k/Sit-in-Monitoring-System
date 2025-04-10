<?php
include("../../conn/db.php");

// Centralized authentication check
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['username'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// Initialize variables
$error_message = '';
$success_message = '';
$studentId = '';
$student_name = '';
$student_curse_year = '';
$laboratory = '';
$purpose = '';

// Get all students - only if needed for dropdown or display
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$students = $result->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerSitIn'])) {
    // Collect form data
    $studentId = $_POST['studentId'] ?? '';
    $student_name = $_POST['studentName'] ?? '';
    $student_curse_year = $_POST['studentCourseYear'] ?? '';
    $laboratory = $_POST['laboratory'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    
    // Validate inputs
    if (empty($studentId) || empty($laboratory) || empty($purpose)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Use prepared statements for better security
        $stmt = $conn->prepare("SELECT sessionno FROM users WHERE idno = ?");
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $session_result = $stmt->get_result();
        $stmt->close();
        
        if ($session_result->num_rows > 0) {
            $user_data = $session_result->fetch_assoc();
            
            if ($user_data['sessionno'] <= 0) {
                $error_message = "This student has no session left.";
            } else {
                // Check for ongoing sit-in
                $stmt = $conn->prepare("SELECT student_id FROM sit_ins WHERE student_id = ? AND check_out_time IS NULL");
                $stmt->bind_param("s", $studentId);
                $stmt->execute();
                $ongoing_result = $stmt->get_result();
                $stmt->close();
                
                if ($ongoing_result->num_rows > 0) {
                    $error_message = "This student has an ongoing sit-in. Please check out the student first.";
                } else {
                    // Insert new sit-in record
                    $check_in_date = date('Y-m-d');
                    $check_in_time = date('H:i:s');
                    
                    $stmt = $conn->prepare("INSERT INTO sit_ins (student_id, laboratory, purpose, check_in_date, check_in_time) 
                            VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $studentId, $laboratory, $purpose, $check_in_date, $check_in_time);
                    
                    if ($stmt->execute()) {
                        $success_message = "Student sit-in registered successfully!";
                    } else {
                        $error_message = "Error registering sit-in: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
        } else {
            $error_message = "Student not found.";
        }
    }
}
?>

<!-- Sit-in Student Modal -->
<div id="sitInModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-blue-500 text-white px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold heading-font">Register Student for Sit-in</h3>
            <button id="closeSitInModal" class="text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700"><?= htmlspecialchars($success_message) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?= htmlspecialchars($error_message) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <form id="sitInForm" method="POST" action="">
                <!-- Student Information (Read-only) -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Student ID:</label>
                    <input type="text" id="studentIdDisplay" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" readonly value="<?= htmlspecialchars($studentId) ?>">
                    <input type="hidden" id="studentId" name="studentId" value="<?= htmlspecialchars($studentId) ?>">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Student Name:</label>
                    <input type="text" id="studentName" name="studentName" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" readonly value="<?= htmlspecialchars($student_name) ?>">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Course & Year:</label>
                    <input type="text" id="studentCourseYear" name="studentCourseYear" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" readonly value="<?= htmlspecialchars($student_curse_year) ?>">
                </div>
                
                <!-- Laboratory Selection -->
                <div class="mb-4">
                    <label for="laboratory" class="block text-gray-700 text-sm font-bold mb-2">Laboratory:</label>
                    <select id="laboratory" name="laboratory" class="p-2 w-full rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Laboratory</option>
                        <option value="524" <?= ($laboratory == '524') ? 'selected' : '' ?>>Laboratory 524</option>
                        <option value="526" <?= ($laboratory == '526') ? 'selected' : '' ?>>Laboratory 526</option>
                        <option value="528" <?= ($laboratory == '528') ? 'selected' : '' ?>>Laboratory 528</option>
                        <option value="530" <?= ($laboratory == '530') ? 'selected' : '' ?>>Laboratory 530</option>
                        <option value="542" <?= ($laboratory == '542') ? 'selected' : '' ?>>Laboratory 542</option>
                    </select>
                </div>
                
                <!-- Purpose Selection -->
                <div class="mb-6">
                    <label for="purpose" class="block text-gray-700 text-sm font-bold mb-2">Purpose:</label>
                    <select id="purpose" name="purpose" class="p-2 w-full rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Purpose</option>
                        <option value="C# Programming" <?= ($purpose == 'C# Programming') ? 'selected' : '' ?>>C# Programming</option>
                        <option value="C Programming" <?= ($purpose == 'C Programming') ? 'selected' : '' ?>>C Programming</option>
                        <option value="Java Programming" <?= ($purpose == 'Java Programming') ? 'selected' : '' ?>>Java Programming</option>
                        <option value="ASP.Net Programming" <?= ($purpose == 'ASP.Net Programming') ? 'selected' : '' ?>>ASP.Net Programming</option>
                        <option value="PHP Programming" <?= ($purpose == 'PHP Programming') ? 'selected' : '' ?>>PHP Programming</option>
                    </select>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button id="sbtn" type="submit" name="registerSitIn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Sit-in Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Close modal
document.getElementById('closeSitInModal').addEventListener('click', function() {
    document.getElementById('sitInModal').classList.add('hidden');
    // Reload the search.php page to reset the form
    window.location.href = window.location.href;
});

// Ensure modal remains open if a message exists
window.addEventListener("DOMContentLoaded", function () {
    let errorMessage = "<?= addslashes($error_message) ?>";
    let successMessage = "<?= addslashes($success_message) ?>";

    if (errorMessage || successMessage) {
        document.getElementById('sitInModal').classList.remove('hidden');
    }
});

// Function to populate the sit-in form with student data
function populateSitInForm(idno, name, course, year) {
    document.getElementById('studentIdDisplay').value = idno;
    document.getElementById('studentId').value = idno;
    document.getElementById('studentName').value = name;
    document.getElementById('studentCourseYear').value = course + ' - ' + year;
    
    // Ensure modal is visible
    document.getElementById('sitInModal').classList.remove('hidden');
}

// This function can be called from the search.php when a student is selected
window.handleStudentSelection = function(idno, name, course, year) {
    populateSitInForm(idno, name, course, year);
};
</script>