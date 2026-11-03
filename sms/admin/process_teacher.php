<?php
include '../db_connection.php';
include 'admin_auth.php';

if(isset($_POST['add_teacher'])){
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing
    $school_id = mysqli_real_escape_string($conn, $_POST['school_id']);
    $status    = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO teachers (school_id, username, password, full_name, status) 
            VALUES ('$school_id', '$username', '$password', '$full_name', '$status')";

    if(mysqli_query($conn, $sql)){
        header("Location: manage_teachers.php?msg=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>