<?php
session_start();
include '../db_connection.php';

if (isset($_POST['promote_btn']) && !empty($_POST['student_ids'])) {
    $target_class_id = mysqli_real_escape_string($conn, $_POST['target_class_id']);
    $student_ids = $_POST['student_ids']; // Array of IDs

    // Convert array to a comma-separated string for the SQL 'IN' clause
    $id_list = implode(',', array_map('intval', $student_ids));

    // Update the students to the new class
    $update_sql = "UPDATE students SET class_id = '$target_class_id' WHERE student_id IN ($id_list)";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: my_class.php?success=promoted");
    } else {
        echo "Error updating records: " . mysqli_error($conn);
    }
} else {
    header("Location: my_class.php?error=no_selection");
}