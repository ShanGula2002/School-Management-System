<?php
include '../db_connection.php';
include 'admin_auth.php';

if(isset($_POST['add_school'])){
    $name = mysqli_real_escape_string($conn, $_POST['school_name']);
    $id_code = mysqli_real_escape_string($conn, $_POST['school_id_code']);
    $type = mysqli_real_escape_string($conn, $_POST['school_type']);
    $address = mysqli_real_escape_string($conn, $_POST['school_address']);

    // Updated query with the new columns
    $sql = "INSERT INTO schools (school_name, school_id_code, school_type, school_address) 
            VALUES ('$name', '$id_code', '$type', '$address')";

    if(mysqli_query($conn, $sql)){
        header("Location: manage_schools.php?msg=success");
    } else {
        // Handy for debugging while you develop
        echo "Error: " . mysqli_error($conn);
    }
}
?>