<?php
session_start();
include 'db_connection.php';

if (isset($_POST['login_btn'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // 1. CHECK THE ADMINS TABLE FIRST
    $admin_sql = "SELECT * FROM admins WHERE username = '$user' LIMIT 1";
    $admin_result = mysqli_query($conn, $admin_sql);

    if (mysqli_num_rows($admin_result) > 0) {
        $row = mysqli_fetch_assoc($admin_result);

        if (password_verify($pass, $row['password'])) {
            // Set Sessions for Admin
            $_SESSION['admin_id']  = $row['admin_id'];
            $_SESSION['user_id']   = $row['admin_id']; 
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role']      = 'Admin';
            $_SESSION['school_id'] = $row['school_id'];

            header("Location: admin/dashboard.php");
            exit();
        }
    }

    // 2. IF NOT FOUND IN ADMINS, CHECK THE TEACHERS TABLE
    // Added 'status' check to ensure only active teachers can log in
    $teacher_sql = "SELECT * FROM teachers WHERE username = '$user' AND status = 'Active' LIMIT 1";
    $teacher_result = mysqli_query($conn, $teacher_sql);

    if (mysqli_num_rows($teacher_result) > 0) {
        $row = mysqli_fetch_assoc($teacher_result);

        if (password_verify($pass, $row['password'])) {
            // Set Sessions for Teacher
            $_SESSION['teacher_id'] = $row['teacher_id']; // Essential for fetching class data
            $_SESSION['user_id']    = $row['teacher_id']; 
            
            // Logic to handle if 'full_name' column exists or needs concatenation
            if (isset($row['full_name'])) {
                $_SESSION['full_name'] = $row['full_name'];
            } else {
                $_SESSION['full_name'] = $row['first_name'] . " " . $row['last_name'];
            }

            $_SESSION['role']       = 'Teacher';
            $_SESSION['school_id']  = $row['school_id'];

            header("Location: teacher/dashboard.php");
            exit();
        }
    }

    // 3. IF NO MATCH IN EITHER TABLE OR WRONG PASSWORD
    header("Location: login.php?error=invalid_credentials");
    exit();
} else {
    // Redirect if someone tries to access this file directly
    header("Location: login.php");
    exit();
}
?>