<?php
include '../db_connection.php';

if(isset($_POST['teacher_id'])){
    $id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    
    // 1. Get Teacher Info
    $t_sql = "SELECT t.*, s.school_name FROM teachers t 
              LEFT JOIN schools s ON t.school_id = s.school_id 
              WHERE t.teacher_id = '$id'";
    $t_res = mysqli_query($conn, $t_sql);
    $teacher = mysqli_fetch_assoc($t_res);

    // 2. Get Assigned Classes
    $c_sql = "SELECT * FROM classes WHERE teacher_id = '$id' ORDER BY grade_level ASC";
    $c_res = mysqli_query($conn, $c_sql);

    if($teacher){
        echo '<div class="text-center mb-4">';
        echo '<div class="avatar-circle mx-auto mb-2" style="width:80px; height:80px; font-size: 2rem;">'.strtoupper(substr($teacher['full_name'], 0, 1)).'</div>';
        echo '<h4 class="fw-bold mb-0">'.$teacher['full_name'].'</h4>';
        echo '<p class="text-muted">'.$teacher['school_name'].'</p>';
        echo '</div>';

        echo '<h6 class="fw-bold text-primary mb-3"><i class="bi bi-journal-bookmark-fill me-2"></i>Assigned Advisory Classes</h6>';
        
        if(mysqli_num_rows($c_res) > 0){
            echo '<div class="list-group border-0">';
            while($class = mysqli_fetch_assoc($c_res)){
                $g = ($class['grade_level'] == 0) ? 'Kinder' : 'Grade ' . $class['grade_level'];
                echo '<div class="list-group-item d-flex justify-content-between align-items-center border-0 bg-light rounded-3 mb-2 p-3">';
                echo '<div><span class="fw-bold">'.$g.'</span> - '.$class['section_name'].'</div>';
                echo '<span class="badge bg-primary rounded-pill">S.Y. '.$class['school_year'].'</span>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<div class="text-center py-4 text-muted border rounded-4 border-dashed">';
            echo '<i class="bi bi-exclame-circle fs-2 d-block mb-2"></i>';
            echo 'No advisory classes assigned yet.';
            echo '</div>';
        }
    }
}
?>