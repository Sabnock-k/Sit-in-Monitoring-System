<?php
session_start();
include('../../../conn/db.php');

// Ensure we're returning JSON
header('Content-Type: application/json');

// Check if user is logged in as admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['username'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if student ID is provided
if (isset($_POST['student_id']) && !empty($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    
    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE idno = ?");
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit();
    }
    
    $stmt->bind_param("s", $student_id);
    
    if ($stmt->execute()) {
        // Successful deletion
        echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
    } else {
        // Error in deletion
        echo json_encode(['success' => false, 'message' => 'Error deleting student: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    // No student ID provided
    echo json_encode(['success' => false, 'message' => 'No student ID provided']);
}

$conn->close();
exit();
?>
