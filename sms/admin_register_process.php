<?php
include 'db_connection.php';

if (isset($_POST['reg_admin_btn'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $school_id = mysqli_real_escape_string($conn, $_POST['school_id']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing

    // Check if username already exists in admins table
    $check = mysqli_query($conn, "SELECT username FROM admins WHERE username = '$username'");
    
    if (mysqli_num_rows($check) > 0) {
        header("Location: admin_register.php?error=username_taken");
    } else {
        $sql = "INSERT INTO admins (school_id, username, password, full_name) 
                VALUES ('$school_id', '$username', '$password', '$full_name')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?success=admin_created");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>