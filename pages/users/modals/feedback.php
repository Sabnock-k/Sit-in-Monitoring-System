<?php
// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $rating = $_POST['rating'];
    $feedback_text = $_POST['feedback_text'];
    
    // Insert feedback into database
    $insert_feedback = "UPDATE sit_ins SET
                        rating = '$rating',
                        feedback = '$feedback_text'
                        WHERE student_id = $user_id";
    
    if ($conn->query($insert_feedback) === TRUE) {
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
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">How was your experience?</label>
            </div>
            <div class="mb-4">
                <!-- Modified this section to display label and rating stars side by side -->
                <div class="flex items-center justify-between mb-1">
                    <label for="feedback_text" class="block text-sm font-medium text-gray-700">
                        Your comments
                    </label>
                    <div class="rating">
                        <input type="radio" name="rating" value="5" id="star5"><label for="star5"></label>
                        <input type="radio" name="rating" value="4" id="star4"><label for="star4"></label>
                        <input type="radio" name="rating" value="3" id="star3"><label for="star3"></label>
                        <input type="radio" name="rating" value="2" id="star2"><label for="star2"></label>
                        <input type="radio" name="rating" value="1" id="star1"><label for="star1"></label>
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
/* Additional CSS for the star rating to ensure proper display */
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-start;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    width: 25px;
    height: 25px;
    background-image: url('path/to/empty-heart.svg'); /* Replace with your heart icon */
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    background-image: url('path/to/filled-heart.svg'); /* Replace with your filled heart icon */
}
</style>