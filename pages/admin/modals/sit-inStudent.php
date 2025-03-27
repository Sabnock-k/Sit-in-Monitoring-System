<?php
include("../../conn/db.php");

// Get all students
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$students = $result->fetch_all(MYSQLI_ASSOC);

// Messages
$error_message = '';
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerSitIn'])) {
    // Sanitize and validate inputs
    $studentId = $conn->real_escape_string($_POST['studentId'] ?? '');
    $student_name = $conn->real_escape_string($_POST['studentName'] ?? '');
    $student_curse_year = $conn->real_escape_string($_POST['studentCourseYear'] ?? '');
    $laboratory = $conn->real_escape_string($_POST['laboratory'] ?? '');
    $purpose = $conn->real_escape_string($_POST['purpose'] ?? '');
    $check_in_date = date('Y-m-d');
    $check_in_time = date('H:i:s');

    // Validate form inputs
    if (empty($studentId) || empty($laboratory) || empty($purpose)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Check if student has an ongoing sit-in
        $check_sql = "SELECT * FROM sit_ins WHERE student_id = '$studentId' AND check_out_time = NULL";
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows > 0) {
            $error_message = "This student has student has an ongoing sit-in. Please check out the student first.";
        } else {
            // Insert into database
            $sql = "INSERT INTO sit_ins (student_id, laboratory, purpose, check_in_date, check_in_time) 
                    VALUES ('$studentId', '$laboratory', '$purpose', '$check_in_date', '$check_in_time')";
            
            if ($conn->query($sql)) {
                $success_message = "Student sit-in registered successfully!";
                // Update student's status session number
            } else {
                $error_message = "Error registering sit-in: " . $conn->error;
            }
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
                    <input type="text" id="studentIdDisplay" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" readonly value="<?= isset($studentId) ? htmlspecialchars($studentId) : '' ?>">
                    <input type="hidden" id="studentId" name="studentId" value="<?= htmlspecialchars($studentId ?? '') ?>" value="<?= isset($studentId) ? htmlspecialchars($studentId) : '' ?>">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Student Name:</label>
                    <input type="text" id="studentName" name="studentName" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" readonly value="<?= isset($student_name) ? htmlspecialchars($student_name) : '' ?>">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Course & Year:</label>
                    <input type="text" id="studentCourseYear" name="studentCourseYear" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" readonly value="<?= isset($student_curse_year) ? htmlspecialchars($student_curse_year) : '' ?>">
                </div>
                
                <!-- Laboratory Selection -->
                <div class="mb-4">
                    <label for="laboratory" class="block text-gray-700 text-sm font-bold mb-2">Laboratory:</label>
                    <select id="laboratory" name="laboratory" class="p-2 w-full rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Laboratory</option>
                        <option value="524" <?= (isset($laboratory) && $laboratory == '524') ? 'selected' : '' ?>>Laboratory 524</option>
                        <option value="526" <?= (isset($laboratory) && $laboratory == '526') ? 'selected' : '' ?>>Laboratory 526</option>
                        <option value="528" <?= (isset($laboratory) && $laboratory == '528') ? 'selected' : '' ?>>Laboratory 528</option>
                        <option value="530" <?= (isset($laboratory) && $laboratory == '530') ? 'selected' : '' ?>>Laboratory 530</option>
                        <option value="542" <?= (isset($laboratory) && $laboratory == '542') ? 'selected' : '' ?>>Laboratory 542</option>
                    </select>
                </div>
                
                <!-- Purpose Selection -->
                <div class="mb-6">
                    <label for="purpose" class="block text-gray-700 text-sm font-bold mb-2">Purpose:</label>
                    <select id="purpose" name="purpose" class="p-2 w-full rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Purpose</option>
                        <option value="C# Programming" <?= (isset($purpose) && $purpose == 'C# Programming') ? 'selected' : '' ?>>C# Programming</option>
                        <option value="C Programming" <?= (isset($purpose) && $purpose == 'C Programming') ? 'selected' : '' ?>>C Programming</option>
                        <option value="Java Programming" <?= (isset($purpose) && $purpose == 'Java Programming') ? 'selected' : '' ?>>Java Programming</option>
                        <option value="ASP.Net Programming" <?= (isset($purpose) && $purpose == 'ASP.Net Programming') ? 'selected' : '' ?>>ASP.Net Programming</option>
                        <option value="PHP Programming" <?= (isset($purpose) && $purpose == 'PHP Programming') ? 'selected' : '' ?>>PHP Programming</option>
                    </select>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button id="sbtn" type="submit" name="registerSitIn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Register Sit-in
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
    location.href = location.href;
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