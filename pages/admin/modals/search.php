<?php
// Include database connection
include('../../conn/db.php');

// Initialize search results variable
$searchResults = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchQuery'])) {
    // Sanitize search input
    $searchQuery = $conn->real_escape_string(trim($_POST['searchQuery']));
    
    if (!empty($searchQuery)) {
        // Create SQL query for search
        $sql = "SELECT id, idno, lastname, firstname, midname, course, year_level, email 
                FROM users 
                WHERE idno LIKE '%$searchQuery%' 
                OR lastname LIKE '%$searchQuery%' 
                OR firstname LIKE '%$searchQuery%' 
                OR course LIKE '%$searchQuery%'
                OR year_level LIKE '%$searchQuery%'
                OR CONCAT(firstname, ' ', lastname) LIKE '%$searchQuery%'
                OR CONCAT(lastname, ', ', firstname) LIKE '%$searchQuery%'
                ORDER BY lastname ASC
                LIMIT 50";
        
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $students = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $students = [];
        }
    } else {
        $students = null;
    }
}
?>

<!-- Search Student Modal -->
<div id="searchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-primary text-white px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold heading-font">Search Student</h3>
            <button id="closeSearchModal" class="text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-6">
                <form id="searchForm" method="POST" action="">
                    <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
                        <input type="text" id="studentSearch" name="searchQuery" placeholder="Search"
                               class="w-full px-4 py-2 focus:outline-none">
                        <button type="submit" id="searchButton" class="bg-primary text-white px-4 py-2 flex items-center">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Search Results Section -->
            <div id="searchResults" class="mt-4">
                <?php if (isset($students)): ?>
                    <?php if (!empty($students)): ?>
                        <div class="bg-blue-50 p-3 rounded-md mb-3">
                            <p class="text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-2"></i>Found <?= count($students) ?> student(s) matching
                                "<span class="font-semibold"><?= htmlspecialchars($searchQuery) ?></span>"
                            </p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Number</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($students as $row): ?>
                                        <?php
                                        $fullName = $row['lastname'] . ', ' . $row['firstname'];
                                        if (!empty($row['midname'])) {
                                            $fullName .= ' ' . $row['midname'][0] . '.';
                                        }
                                        ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm"><?= htmlspecialchars($row['idno']) ?></td>
                                            <td class="px-4 py-2 text-sm"><?= htmlspecialchars($fullName) ?></td>
                                            <td class="px-4 py-2 text-sm"><?= htmlspecialchars($row['course']) ?></td>
                                            <td class="px-4 py-2 text-sm"><?= htmlspecialchars($row['year_level']) ?></td>
                                            <td class="px-4 py-2 text-sm">
                                                <div class="flex space-x-2">
                                                    <a href="view_student.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit_student.php?id=<?= $row['id'] ?>" class="text-green-600 hover:text-green-800" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="bg-yellow-50 p-4 rounded-md text-center">
                            <p class="text-yellow-700 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>No students found.
                            </p>
                            <p class="text-sm text-gray-600">Try a different search term or check your spelling.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-blue-50 p-4 rounded-md text-center">
                        <p class="text-blue-700">
                            <i class="fas fa-search mr-2"></i>Search for students by ID, name, or course
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Submit form without page reload using AJAX
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const searchQuery = document.getElementById('studentSearch').value;
    const resultsContainer = document.getElementById('searchResults');
    
    // Create FormData
    const formData = new FormData();
    formData.append('searchQuery', searchQuery);
    
    // Show loading state
    resultsContainer.innerHTML = '<div class="flex justify-center p-4"><i class="fas fa-circle-notch fa-spin text-primary text-2xl"></i></div>';
    
    // AJAX request
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        // Parse the returned HTML to extract only the search results section
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newResults = doc.getElementById('searchResults');
        
        if(newResults) {
            resultsContainer.innerHTML = newResults.innerHTML;
        } else {
            resultsContainer.innerHTML = '<div class="bg-red-50 p-4 rounded-md text-center"><p class="text-red-700"><i class="fas fa-exclamation-circle mr-2"></i>An error occurred while processing your search.</p></div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultsContainer.innerHTML = '<div class="bg-red-50 p-4 rounded-md text-center"><p class="text-red-700"><i class="fas fa-exclamation-circle mr-2"></i>An error occurred while processing your search.</p></div>';
    });
});

// Clear search results when closing modal
document.getElementById('closeSearchModal').addEventListener('click', function() {
    document.getElementById('searchModal').classList.add('hidden');
    document.getElementById('studentSearch').value = '';
    document.getElementById('searchResults').innerHTML = '<div class="bg-blue-50 p-4 rounded-md text-center"><p class="text-blue-700"><i class="fas fa-search mr-2"></i>Search for students by ID, name, or course</p></div>';
});
</script>