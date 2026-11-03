<?php
include '../db_connection.php';
include 'teacher_auth.php';

if (isset($_POST['upload_btn'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $grade_level = mysqli_real_escape_string($conn, $_POST['grade_level']);
    $doc_type = mysqli_real_escape_string($conn, $_POST['doc_type']);
    $teacher_id = $_SESSION['teacher_id']; 

    // 1. Define Paths
    // We use ../ so PHP can find the folder from the /teacher/ directory
    $target_dir = "../uploads/documents/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // 2. File Naming
    $file_extension = strtolower(pathinfo($_FILES["student_file"]["name"], PATHINFO_EXTENSION));
    $new_filename = "STU_" . $student_id . "_" . str_replace(' ', '_', $doc_type) . "_G" . $grade_level . "_" . time() . "." . $file_extension;
    
    // This is for PHP to move the file:
    $target_file = $target_dir . $new_filename; 
    
    // IMPORTANT: This is what the browser sees in student_profile.php
    // We save it as ../uploads/... so the "View" link works from the /teacher/ folder
    $db_path = "../uploads/documents/" . $new_filename; 

    // 3. Validation
    $allowed_types = ['pdf', 'jpg', 'jpeg', 'png', 'docx', 'doc'];
    if (!in_array($file_extension, $allowed_types)) {
        header("Location: student_profile.php?id=$student_id&error=Invalid file type");
        exit();
    }

    // 4. Upload and Database Sync
    if (move_uploaded_file($_FILES["student_file"]["tmp_name"], $target_file)) {
        
        $check_query = "SELECT record_id FROM student_records 
                        WHERE student_id = '$student_id' 
                        AND grade_level = '$grade_level' 
                        AND document_type = '$doc_type'";
        $check_res = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_res) > 0) {
            $update_query = "UPDATE student_records SET 
                             file_path = '$db_path', 
                             date_uploaded = NOW(),
                             uploaded_by = '$teacher_id' 
                             WHERE student_id = '$student_id' 
                             AND grade_level = '$grade_level' 
                             AND document_type = '$doc_type'";
            mysqli_query($conn, $update_query);
        } else {
            $insert_query = "INSERT INTO student_records (student_id, grade_level, document_type, file_path, date_uploaded, uploaded_by) 
                             VALUES ('$student_id', '$grade_level', '$doc_type', '$db_path', NOW(), '$teacher_id')";
            mysqli_query($conn, $insert_query);
        }

        header("Location: student_profile.php?id=$student_id&success=File uploaded successfully");
    } else {
        header("Location: student_profile.php?id=$student_id&error=Upload failed");
    }
} else {
    header("Location: my_class.php");
}
?>