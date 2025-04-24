<?php
// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $rating = $_POST['rating'];
    $feedback_text = $_POST['feedback_text'];
    $user_id = $_SESSION['idno'];
    $checkin_date = isset($_POST['checkin_date']) ? $_POST['checkin_date'] : null;
    $checkin_time = isset($_POST['checkin_time']) ? $_POST['checkin_time'] : null;
    
    // Use prepared statement to prevent SQL injection
    $insert_feedback = $conn->prepare("UPDATE sit_ins SET rating = ?, feedback = ? WHERE student_id = ? AND check_in_date = ? AND check_in_time = ? AND rating IS NULL AND feedback IS NULL");
    $insert_feedback->bind_param("issss", $rating, $feedback_text, $user_id, $checkin_date, $checkin_time);
    
    if ($insert_feedback->execute()) {
        $feedback_message = "Feedback submitted successfully!";
    } else {
        $feedback_error = "Error submitting feedback: " . $conn->error;
    }
    
    // Refresh the page to show updated data
    header("Location: history.php");
    exit();
}
?>


<!-- Feedback Modal -->
<div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="heading-font text-lg font-semibold text-gray-800">Share Your Feedback</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="feedbackForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" id="checkin_date" name="checkin_date" value="">
            <input type="hidden" id="checkin_time" name="checkin_time" value="">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">How was your experience?</label>
            </div>
            <div class="mb-4">
                <div class="flex items-center justify-between mb-1">
                    <label for="feedback_text" class="block text-sm font-medium text-gray-700">
                        Your comments
                    </label>
                    <div class="heart-rating text-yellow-400">
                        <input type="radio" name="rating" value="5" id="heart5"><label for="heart5" title="Excellent"><i class="far fa-heart" required></i></label>
                        <input type="radio" name="rating" value="4" id="heart4"><label for="heart4" title="Very Good"><i class="far fa-heart" required></i></label>
                        <input type="radio" name="rating" value="3" id="heart3"><label for="heart3" title="Good"><i class="far fa-heart" required></i></label>
                        <input type="radio" name="rating" value="2" id="heart2"><label for="heart2" title="Fair"><i class="far fa-heart" required></i></label>
                        <input type="radio" name="rating" value="1" id="heart1"><label for="heart1" title="Poor"><i class="far fa-heart" required></i></label>
                    </div>
                </div>
                <textarea
                    id="feedback_text"
                    name="feedback_text"
                    rows="4"
                    class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Please share your thoughts about this sit-in session..."
                    required
                ></textarea>
            </div>
            
            <div class="flex justify-end">
                <button
                    type="button"
                    id="cancelBtn"
                    class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button
                    type="submit"
                    name="submit_feedback"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Heart rating system styles */
.heart-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-start;
}

.heart-rating input {
    display: none;
}

.heart-rating label {
    cursor: pointer;
    padding: 0 2px;
    font-size: 24px;
    color: #ccc;
    transition: color 0.2s;
}

.heart-rating label i {
    transition: all 0.2s ease;
}

.heart-rating input:checked ~ label,
.heart-rating label:hover,
.heart-rating label:hover ~ label {
    color: #ff6b6b;
}

.heart-rating input:checked ~ label i,
.heart-rating label:hover i,
.heart-rating label:hover ~ label i {
    font-weight: 900;
    transform: scale(1.1);
    content: "\f004";
    font-family: "Font Awesome 5 Free";
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Heart rating functionality
    const heartLabels = document.querySelectorAll('.heart-rating label');
    
    heartLabels.forEach(label => {
        label.addEventListener('click', function() {
            // Update visual state for all hearts
            const heartId = this.getAttribute('for');
            const heartInput = document.getElementById(heartId);
            
            if (heartInput) {
                heartInput.checked = true;
                
                // Optional: Show a message based on rating
                const ratingValue = heartInput.value;
                console.log(`User selected rating: ${ratingValue}`);
            }
        });
    });
    
    // Modal controls
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const feedbackModal = document.getElementById('feedbackModal');
    
    if (closeModal && cancelBtn && feedbackModal) {
        closeModal.addEventListener('click', function() {
            feedbackModal.classList.add('hidden');
        });
        
        cancelBtn.addEventListener('click', function() {
            feedbackModal.classList.add('hidden');
        });
    }
});
</script>
