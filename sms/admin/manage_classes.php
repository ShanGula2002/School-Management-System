<?php 
include '../db_connection.php'; 
include 'admin_auth.php'; 

// Handle Class Deletion
if(isset($_GET['delete'])){
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM classes WHERE class_id = '$id'");
    header("Location: manage_classes.php?msg=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Manage Classes</title>
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
        .class-card { border: none; border-radius: 20px; }
        .btn-add { background-color: var(--deped-blue); color: white; border-radius: 12px; font-weight: 600; transition: 0.3s; }
        .btn-add:hover { background-color: #002673; color: white; transform: translateY(-2px); }
        .table thead th { background-color: #f8f9fa; color: #6c757d; font-size: 0.75rem; text-transform: uppercase; border: none; letter-spacing: 1px; }
        
        .class-badge { background: #eef2ff; color: #0038a8; font-weight: bold; border: 1px solid #d1d9ff; padding: 4px 12px; border-radius: 8px; }
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
                <h3 class="fw-bold mb-0 text-dark">Class Management</h3>
                <p class="text-muted small mb-0">Organize sections and assign class advisers</p>
            </div>
            <button class="btn btn-add px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addClassModal">
                <i class="bi bi-plus-square-dotted me-2"></i>Create New Class
            </button>
        </div>

        <div class="card class-card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Grade & Section</th>
                                <th>Adviser</th>
                                <th>School</th>
                                <th>S.Y.</th> <th class="text-center">Students</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Joining classes, teachers, and schools
                            $sql = "SELECT c.*, t.full_name as teacher_name, s.school_name,
                                    (SELECT COUNT(*) FROM students st WHERE st.class_id = c.class_id) as student_count
                                    FROM classes c 
                                    LEFT JOIN teachers t ON c.teacher_id = t.teacher_id 
                                    LEFT JOIN schools s ON c.school_id = s.school_id 
                                    ORDER BY c.grade_level ASC, c.section_name ASC";
                            $result = mysqli_query($conn, $sql);

                            while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td class='ps-4'>
                                        <div class="d-flex align-items-center">
                                            <div class="class-badge me-3">
                                                <?php echo ($row['grade_level'] == 0) ? 'K' : 'G' . $row['grade_level']; ?>
                                            </div>
                                            <span class="fw-bold"><?php echo $row['section_name']; ?></span>
                                        </div>
                                    </td>
                                    <td><i class="bi bi-person-workspace me-1 text-muted"></i> <?php echo $row['teacher_name'] ?? 'Not Assigned'; ?></td>
                                    <td class='text-muted small'><?php echo $row['school_name']; ?></td>
                                    
                                    <td><span class="text-dark fw-medium"><?php echo $row['school_year']; ?></span></td>
                                    
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info px-3"><?php echo $row['student_count']; ?> Enrolled</span>
                                    </td>
                                    <td class='text-end pe-4'>
                                        <div class='btn-group shadow-sm rounded-3'>
                                            <button class='btn btn-white btn-sm border-end text-primary'><i class='bi bi-gear'></i></button>
                                            <a href='manage_classes.php?delete=<?php echo $row['class_id']; ?>' 
                                            onclick="return confirm('Delete this class? This will unassign all students.')" 
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

    <div class="modal fade" id="addClassModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title fw-bold">Setup New Class Section</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="process_class.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold text-muted">GRADE LEVEL</label>
                                <select name="grade_level" class="form-select bg-light border-0 p-3" required>
                                    <option value="0">Kindergarten</option>
                                    <?php for($i=1; $i<=12; $i++) echo "<option value='$i'>Grade $i</option>"; ?>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small fw-bold text-muted">SECTION NAME</label>
                                <input type="text" name="section_name" class="form-control bg-light border-0 p-3" placeholder="e.g. Einstein" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">SELECT SCHOOL</label>
                                <select name="school_id" class="form-select bg-light border-0 p-3" required>
                                    <option value="" selected disabled>Select School...</option>
                                    <?php
                                    $schools = mysqli_query($conn, "SELECT school_id, school_name FROM schools");
                                    while($s = mysqli_fetch_assoc($schools)) echo "<option value='".$s['school_id']."'>".$s['school_name']."</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">ASSIGN ADVISER</label>
                                <select name="teacher_id" class="form-select bg-light border-0 p-3">
                                    <option value="" selected>Assign Later</option>
                                    <?php
                                    $teachers = mysqli_query($conn, "SELECT teacher_id, full_name FROM teachers WHERE status='Active'");
                                    while($t = mysqli_fetch_assoc($teachers)) echo "<option value='".$t['teacher_id']."'>".$t['full_name']."</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_class" class="btn btn-add px-4 rounded-pill shadow">Save Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggle-btn').onclick = function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('main-wrapper').classList.toggle('expanded');
        }
    </script>
</body>
</html>