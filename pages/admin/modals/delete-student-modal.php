<!-- Delete Student Confirmation Modal -->
<div id="deleteStudentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Confirm Deletion</h3>
            <button id="closeDeleteStudentModal" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-6">
            <p class="text-gray-700 mb-2">Are you sure you want to delete this student?</p>
            <div class="bg-gray-100 p-3 rounded-md mb-2">
                <p class="font-medium">Name: <span id="studentNameToDelete" class="font-normal"></span></p>
                <p class="font-medium">ID Number: <span id="studentIdNoToDelete" class="font-normal"></span></p>
            </div>
            <p class="text-red-600 text-sm mt-2">This action cannot be undone.</p>
            <input type="hidden" id="studentIdToDelete">
        </div>
        
        <div class="flex justify-end space-x-3">
            <button id="cancelDeleteStudent" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition duration-200">
                Cancel
            </button>
            <button id="confirmDeleteStudent" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                Delete
            </button>
        </div>
    </div>
</div>
