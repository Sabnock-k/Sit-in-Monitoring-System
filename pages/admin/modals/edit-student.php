<!-- Edit Student Modal -->
<div id="EditStudentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-blue-500 text-white px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold heading-font">Edit Student Info</h3>
            <button id="closeEditStudentModal" class="text-white hover:text-gray-200 focus:outline-none">
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
            
            <form id="EditStudentForm" method="POST" action="">
                <!-- Student Information -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Student ID:</label>
                    <input type="text" id="studentIdDisplay" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="<?= htmlspecialchars($studentId) ?>">
                    <input type="hidden" id="studentId" name="studentId" value="<?= htmlspecialchars($studentId) ?>">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Student Name:</label>
                    <input type="text" id="studentName" name="studentName" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="<?= htmlspecialchars($student_name) ?>">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Course & Year:</label>
                    <input type="text" id="studentCourseYear" name="studentCourseYear" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="<?= htmlspecialchars($student_curse_year) ?>">
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button id="sbtn" type="submit" name="EditStudent" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Sit-in Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>