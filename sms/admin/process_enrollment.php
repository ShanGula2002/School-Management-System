<?php
// Include your database connection and authentication
include '../db_connection.php';
include 'admin_auth.php';

// Check if the form was actually submitted
if(isset($_POST['enroll_now'])){
    
    // Clean the inputs to prevent SQL injection
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);

    // Ensure neither field is empty just in case
    if(!empty($student_id) && !empty($class_id)) {
        
        // Update the student record to include the new class_id
        $sql = "UPDATE students SET class_id = '$class_id' WHERE student_id = '$student_id'";

        if(mysqli_query($conn, $sql)){
            // Redirect back to the main page with a success flag in the URL
            header("Location: manage_students.php?msg=class_assigned");
            exit();
        } else {
            // Display an error if the query fails
            echo "<div style='font-family: sans-serif; padding: 20px; color: red;'>";
            echo "<strong>Error:</strong> Failed to assign student to class.<br>";
            echo "MySQL Error: " . mysqli_error($conn);
            echo "</div>";
        }
    } else {
        // Redirect back if fields were missing
        header("Location: manage_students.php?msg=missing_fields");
        exit();
    }
} else {
    // If someone tries to access this file directly by typing the URL, kick them back
    header("Location: manage_students.php");
    exit();
}
?>