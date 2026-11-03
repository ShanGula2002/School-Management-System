<?php
session_start();
include '../db_connection.php';
include 'teacher_auth.php';

if (isset($_POST['enroll_btn'])) {
    // 1. Sanitize Basic Inputs
    $lrn           = mysqli_real_escape_string($conn, $_POST['lrn']);
    $first_name    = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name   = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $last_name     = mysqli_real_escape_string($conn, $_POST['last_name']);
    $gender        = mysqli_real_escape_string($conn, $_POST['gender']);
    $grade_level   = mysqli_real_escape_string($conn, $_POST['grade_level']);
    $birthdate     = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $class_id      = mysqli_real_escape_string($conn, $_POST['class_id']);
    $school_id     = mysqli_real_escape_string($conn, $_POST['school_id']);
    
    // Safety check for Option 2: Ensure session teacher_id is valid
    $teacher_id    = $_SESSION['teacher_id'] ?? null; 
    $status        = "Active";

    if (!$teacher_id) {
        die("Error: Session expired or Teacher ID not found. Please log in again.");
    }

    // 2. Insert into students table first
    $student_sql = "INSERT INTO students (school_id, class_id, lrn, first_name, middle_name, last_name, gender, birthdate, status, created_at) 
                    VALUES ('$school_id', '$class_id', '$lrn', '$first_name', '$middle_name', '$last_name', '$gender', '$birthdate', '$status', NOW())";

    if (mysqli_query($conn, $student_sql)) {
        $new_student_id = mysqli_insert_id($conn); // Get the ID of the student we just created

        // 3. Define the possible documents
        $files_to_process = [
            'psa_doc' => 'PSA Birth Certificate',
            'ecd_checklist' => 'ECD Checklist',
            'progress_report' => 'Progress Report Card',
            'lit_num_checklist' => 'Literacy Numeracy Checklist',
            'moving_up_cert' => 'Moving Up Certificate',
            'sf5' => 'School Form 5',
            'sf9' => 'School Form 9',
            'sf10' => 'School Form 10'
        ];

        $upload_dir = "../uploads/documents/";
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

        foreach ($files_to_process as $input_name => $doc_type) {
            if (!empty($_FILES[$input_name]['name'])) {
                $file_ext = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
                $clean_filename = $lrn . "_" . str_replace(' ', '_', $doc_type) . "_" . time() . "." . $file_ext;
                $target_path = $upload_dir . $clean_filename;

                if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $target_path)) {
                    // 4. Insert into student_records table
                    // Database now correctly checks this against the 'teachers' table
                    $record_sql = "INSERT INTO student_records (student_id, uploaded_by, grade_level, document_type, file_name, file_path, date_uploaded) 
                                   VALUES ('$new_student_id', '$teacher_id', '$grade_level', '$doc_type', '$clean_filename', '$target_path', NOW())";
                    
                    if (!mysqli_query($conn, $record_sql)) {
                        // Log error but don't stop the whole loop
                        error_log("Failed to insert record for $doc_type: " . mysqli_error($conn));
                    }
                }
            }
        }

        header("Location: my_class.php?success=enrolled");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}