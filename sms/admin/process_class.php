<?php
include '../db_connection.php';
include 'admin_auth.php';

if(isset($_POST['add_class'])){
    // 1. Capture Inputs
    $grade = mysqli_real_escape_string($conn, $_POST['grade_level']); 
    $section = mysqli_real_escape_string($conn, $_POST['section_name']);
    $school_id = mysqli_real_escape_string($conn, $_POST['school_id']);
    
    // Default class_name to something descriptive if not provided in a form
    $class_name = ($grade == 0) ? "Preschool" : "Basic Education";
    
    // 2. Auto-generate School Year (e.g., "2025-2026")
    $currentYear = date('Y');
    $currentMonth = date('m');

    // If month is June (06) or later, SY is CurrentYear - NextYear
    if ($currentMonth >= 6) {
        $school_year = $currentYear . "-" . ($currentYear + 1);
    } else {
        // If month is Jan-May, we are still in the SY that started last year
        $school_year = ($currentYear - 1) . "-" . $currentYear;
    }

    $teacher_id = !empty($_POST['teacher_id']) ? "'".mysqli_real_escape_string($conn, $_POST['teacher_id'])."'" : "NULL";

    // 3. Insert into Database
    // Note: I included class_name and school_year based on your table columns
    $sql = "INSERT INTO classes (school_id, teacher_id, class_name, section_name, grade_level, school_year) 
            VALUES ('$school_id', $teacher_id, '$class_name', '$section', '$grade', '$school_year')";

    if(mysqli_query($conn, $sql)){
        header("Location: manage_classes.php?msg=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>