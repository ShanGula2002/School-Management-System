<?php
include '../db_connection.php';
include 'admin_auth.php';

if(isset($_POST['enroll_student'])){
    $fn = mysqli_real_escape_string($conn, $_POST['first_name']);
    $mn = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $ln = mysqli_real_escape_string($conn, $_POST['last_name']);
    $lrn = mysqli_real_escape_string($conn, $_POST['lrn']);
    $bday = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $school_id = mysqli_real_escape_string($conn, $_POST['school_id']);
    
    // NEW: Capture the class selection from the form
    // We use ternary to handle cases where a class might not be assigned immediately
    $class_id = !empty($_POST['class_id']) ? "'".mysqli_real_escape_string($conn, $_POST['class_id'])."'" : "NULL";

    // Updated SQL to include class_id
    $sql = "INSERT INTO students (school_id, class_id, lrn, first_name, last_name, middle_name, gender, birthdate, status) 
            VALUES ('$school_id', $class_id, '$lrn', '$fn', '$ln', '$mn', '$gender', '$bday', 'Active')";

    if(mysqli_query($conn, $sql)){
        header("Location: manage_students.php?msg=enrolled");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>