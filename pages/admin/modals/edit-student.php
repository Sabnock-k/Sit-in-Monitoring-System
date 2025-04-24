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
                <!-- Student ID -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Student IDNO:</label>
                    <input type="text" id="studentIdDisplay" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="" readonly>
                </div>
                
                <!-- First Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">First Name:</label>
                    <input type="text" id="firstName" name="firstName" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="">
                </div>
                
                <!-- Last Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="">
                </div>
                
                <!-- Middle Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="">
                </div>
                
                <!-- Course -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Course:</label>
                    <input type="text" id="course" name="course" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="">
                </div>
                
                <!-- Year Level -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Year Level:</label>
                    <select id="yearLevel" name="yearLevel" class="bg-gray-100 p-2 w-full rounded border border-gray-300 mb-2" value="">
                        <option value="1" <?= (isset($yearLevel) && $yearLevel == '1') ? 'selected' : '' ?>>1st Year</option>
                        <option value="2" <?= (isset($yearLevel) && $yearLevel == '2') ? 'selected' : '' ?>>2nd Year</option>
                        <option value="3" <?= (isset($yearLevel) && $yearLevel == '3') ? 'selected' : '' ?>>3rd Year</option>
                        <option value="4" <?= (isset($yearLevel) && $yearLevel == '4') ? 'selected' : '' ?>>4th Year</option>
                    </select>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button id="sbtn" type="submit" name="EditStudent" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>