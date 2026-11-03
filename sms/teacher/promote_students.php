<?php
include '../db_connection.php';
include 'teacher_auth.php';

$teacher_id = $_SESSION['teacher_id'];

// Get the current teacher's assigned class
$class_query = "SELECT * FROM classes WHERE teacher_id = '$teacher_id'";
$class_res = mysqli_query($conn, $class_query);
$my_class = mysqli_fetch_assoc($class_res);
$current_class_id = $my_class['class_id'];
$next_grade = $my_class['grade_level']; // The grade they are moving INTO
?>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold"><i class="bi bi-arrow-up-circle me-2 text-primary"></i>Promote Students to <?php echo $next_grade; ?></h5>
        <p class="text-muted small">Select students from the previous grade to import them into your current section.</p>

        <form action="promotion_process.php" method="POST">
            <input type="hidden" name="target_class_id" value="<?php echo $current_class_id; ?>">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Student Name</th>
                            <th>Previous Grade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Logic: Find students who are 1 level below the teacher's current grade
                        $prev_grade = $next_grade - 1; 
                        $search_query = "SELECT * FROM students WHERE status = 'Active' AND class_id != '$current_class_id'";
                        $search_res = mysqli_query($conn, $search_query);

                        while($row = mysqli_fetch_assoc($search_res)): ?>
                        <tr>
                            <td><input type="checkbox" name="student_ids[]" value="<?php echo $row['student_id']; ?>"></td>
                            <td><?php echo $row['last_name'] . ", " . $row['first_name']; ?></td>
                            <td><span class="badge bg-secondary">Grade <?php echo $prev_grade; ?></span></td>
                            <td><span class="text-success small">Ready for Promotion</span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" name="promote_btn" class="btn btn-primary rounded-pill px-4 mt-3">
                Import Selected Students
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('selectAll').onclick = function() {
        var checkboxes = document.getElementsByName('student_ids[]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>