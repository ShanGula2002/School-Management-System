<?php 
include '../db_connection.php'; 
include 'admin_auth.php'; 

// Handle Student Deletion
if(isset($_GET['delete'])){
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM students WHERE student_id = '$id'");
    header("Location: manage_students.php?msg=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root { --deped-blue: #0038a8; --sidebar-width: 260px; --sidebar-collapsed-width: 80px; }
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; overflow-x: hidden; font-size: larger; }

        #sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; left: 0; top: 0; transition: all 0.3s ease; z-index: 1000; }
        #main-wrapper { margin-left: var(--sidebar-width); padding: 25px; transition: all 0.3s ease; min-height: 100vh; }
        #sidebar.collapsed { width: var(--sidebar-collapsed-width); }
        #main-wrapper.expanded { margin-left: var(--sidebar-collapsed-width); }

        .navbar { border-radius: 15px; }
        .student-card { border: none; border-radius: 20px; }
        .btn-add { background-color: var(--deped-blue); color: white; border-radius: 12px; font-weight: 600; transition: 0.3s; }
        .btn-add:hover { background-color: #002673; color: white; transform: translateY(-2px); }
        .table thead th { background-color: #f8f9fa; color: #6c757d; font-size: 0.75rem; text-transform: uppercase; border: none; letter-spacing: 1px; }
        .lrn-code { font-family: 'Courier New', Courier, monospace; font-weight: bold; color: #0038a8; background: #eef2ff; padding: 2px 8px; border-radius: 5px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div id="main-wrapper">
        <nav class="navbar navbar-expand bg-white shadow-sm mb-4 px-3">
            <button class="btn btn-light rounded-circle shadow-sm" id="toggle-btn">
                <i class="bi bi-list fs-4 text-primary"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <div class="text-end me-3 d-none d-md-block">
                    <p class="mb-0 small fw-bold"><?php echo $_SESSION['full_name']; ?></p>
                    <p class="mb-0 text-muted" style="font-size: 0.7rem;">System Administrator</p>
                </div>
                <div class="vr me-3 opacity-25"></div>
                <button class="btn btn-primary rounded-pill px-3 btn-sm fw-bold">
                    <i class="bi bi-shield-check me-1"></i> Verified
                </button>
            </div>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Student Registry</h3>
                <p class="text-muted small mb-0">Manage K-12 student enrollment and records</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-add px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="bi bi-person-plus-fill me-2"></i>Register New Student
                </button>
                <button class="btn btn-success px-4 py-2 shadow-sm rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#enrollModal">
                    <i class="bi bi-person-check-fill me-2"></i>Assign to Class
                </button>
            </div>
        </div>

        <div class="card student-card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
<table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Full Name</th>
                                <th>LRN</th>
                                <th>Class / Section</th>
                                <th>Assigned School</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // UPDATED SQL: Joined with 'classes' table to get grade and section
                            $sql = "SELECT st.*, s.school_name, c.grade_level, c.section_name 
                                    FROM students st 
                                    LEFT JOIN schools s ON st.school_id = s.school_id 
                                    LEFT JOIN classes c ON st.class_id = c.class_id
                                    ORDER BY st.last_name ASC";
                            $result = mysqli_query($conn, $sql);

                            while($row = mysqli_fetch_assoc($result)) {
                                $full_name = $row['last_name'] . ", " . $row['first_name'] . " " . substr($row['middle_name'], 0, 1) . ".";
                                
                                // Format Grade Level
                                $grade = "Unassigned";
                                if(isset($row['grade_level'])){
                                    $grade = ($row['grade_level'] == 0) ? "Kinder" : "Grade " . $row['grade_level'];
                                    $grade .= " - " . $row['section_name'];
                                }
                                ?>
                                <tr>
                                    <td class='ps-4'>
                                        <div class='fw-bold text-dark'><?php echo $full_name; ?></div>
                                        <div class='text-muted extra-small' style="font-size: 0.75rem;">DOB: <?php echo date('M d, Y', strtotime($row['birthdate'])); ?></div>
                                    </td>
                                    <td><span class="lrn-code"><?php echo $row['lrn']; ?></span></td>
                                    <td>
                                        <span class="<?php echo ($row['class_id']) ? 'class-badge' : 'text-muted italic small'; ?>">
                                            <?php echo $grade; ?>
                                        </span>
                                    </td>
                                    <td class='text-muted small'><?php echo $row['school_name'] ?? 'Not Assigned'; ?></td>
                                    <td>
                                        <span class='badge <?php echo ($row['status'] == 'Active') ? 'bg-success' : 'bg-warning'; ?> bg-opacity-10 text-<?php echo ($row['status'] == 'Active') ? 'success' : 'warning'; ?> px-3 py-2 rounded-pill'>
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td class='text-end pe-4'>
                                        <div class='btn-group shadow-sm rounded-3'>
                                            <button class='btn btn-white btn-sm border-end text-primary'><i class='bi bi-eye'></i></button>
                                            <a href='manage_students.php?delete=<?php echo $row['student_id']; ?>' 
                                               onclick="return confirm('Archive student record?')" 
                                               class='btn btn-white btn-sm text-danger'><i class='bi bi-trash'></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Register New Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="process_student.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">FIRST NAME</label>
                                <input type="text" name="first_name" class="form-control bg-light border-0 p-3" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">MIDDLE NAME</label>
                                <input type="text" name="middle_name" class="form-control bg-light border-0 p-3">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">LAST NAME</label>
                                <input type="text" name="last_name" class="form-control bg-light border-0 p-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">LRN (12 Digits)</label>
                                <input type="text" name="lrn" maxlength="12" class="form-control bg-light border-0 p-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">BIRTHDATE</label>
                                <input type="date" name="birthdate" class="form-control bg-light border-0 p-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">GENDER</label>
                                <select name="gender" class="form-select bg-light border-0 p-3">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">SCHOOL</label>
                                <select name="school_id" class="form-select bg-light border-0 p-3" required>
                                    <option value="" disabled selected>Select School...</option>
                                    <?php
                                    $schools = mysqli_query($conn, "SELECT school_id, school_name FROM schools");
                                    while($s = mysqli_fetch_assoc($schools)) {
                                        echo "<option value='".$s['school_id']."'>".$s['school_name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="enroll_student" class="btn btn-primary px-4 rounded-pill shadow">Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="enrollModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-success text-white p-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-check me-2"></i>Assign to Class</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="process_enrollment.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">SELECT STUDENT</label>
                            <select name="student_id" class="form-select bg-light border-0 p-3" required>
                                <option value="" disabled selected>Choose student...</option>
                                <?php
                                // Only show students who aren't enrolled yet
                                $students = mysqli_query($conn, "SELECT student_id, first_name, last_name FROM students WHERE class_id IS NULL");
                                while($st = mysqli_fetch_assoc($students)) {
                                    echo "<option value='".$st['student_id']."'>".$st['first_name']." ".$st['last_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">ASSIGN TO CLASS / SECTION</label>
                            <select name="class_id" class="form-select bg-light border-0 p-3" required>
                                <option value="" selected disabled>Select Grade & Section...</option>
                                <?php
                                $classes = mysqli_query($conn, "SELECT class_id, grade_level, section_name FROM classes ORDER BY grade_level ASC");
                                while($cl = mysqli_fetch_assoc($classes)) {
                                    $g = ($cl['grade_level'] == 0) ? 'Kinder' : 'Grade ' . $cl['grade_level'];
                                    echo "<option value='".$cl['class_id']."'>$g - ".$cl['section_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="enroll_now" class="btn btn-success px-4 rounded-pill shadow">Confirm Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggle-btn').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const wrapper = document.getElementById('main-wrapper');
            sidebar.classList.toggle('collapsed');
            wrapper.classList.toggle('expanded');
        });
    </script>
</body>
</html>