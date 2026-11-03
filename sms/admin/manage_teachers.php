<?php 
include '../db_connection.php'; 
include 'admin_auth.php'; 

// Handle Teacher Deletion
if(isset($_GET['delete'])){
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM teachers WHERE teacher_id = '$id'");
    header("Location: manage_teachers.php?msg=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Manage Teachers</title>
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
        .teacher-card { border: none; border-radius: 20px; }
        .btn-add { background-color: var(--deped-blue); color: white; border-radius: 12px; font-weight: 600; transition: 0.3s; }
        .btn-add:hover { background-color: #002673; color: white; transform: translateY(-2px); }
        .table thead th { background-color: #f8f9fa; color: #6c757d; font-size: 0.75rem; text-transform: uppercase; border: none; letter-spacing: 1px; }
        .avatar-circle { width: 40px; height: 40px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; color: var(--deped-blue); }
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
        <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">



    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="teacher_profile_content">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Teacher Management</h3>
                <p class="text-muted small mb-0">Authorize and assign educators to schools</p>
            </div>
            <button class="btn btn-add px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                <i class="bi bi-person-plus me-2"></i>Add New Teacher
            </button>
        </div>

        <div class="card teacher-card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Full Name</th>
                                <th>Username</th>
                                <th>Assigned School</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php
    $sql = "SELECT t.*, s.school_name 
            FROM teachers t 
            LEFT JOIN schools s ON t.school_id = s.school_id 
            ORDER BY t.created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $status_class = ($row['status'] == 'Active') ? 'bg-success' : 'bg-secondary';
            $initial = strtoupper(substr($row['full_name'], 0, 1));
            ?>
            <tr>
                <td class='ps-4'>
                    <div class='d-flex align-items-center py-2'>
                        <div class='avatar-circle me-3'><?php echo $initial; ?></div>
                        <button type="button" 
                                class="btn btn-link p-0 fw-bold text-decoration-none text-dark" 
                                onclick="showTeacherProfile(<?php echo $row['teacher_id']; ?>)">
                            <?php echo $row['full_name']; ?>
                        </button>
                    </div>
                </td>
                <td><code class='text-primary'><?php echo $row['username']; ?></code></td>
                <td class='text-muted small'><?php echo $row['school_name'] ?? 'Unassigned'; ?></td>
                <td>
                    <span class='badge <?php echo $status_class; ?> bg-opacity-10 text-<?php echo str_replace('bg-', '', $status_class); ?> px-3 py-2 rounded-pill'>
                        <?php echo $row['status']; ?>
                    </span>
                </td>
                <td class='text-muted small'><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                <td class='text-end pe-4'>
                    <div class='btn-group shadow-sm rounded-3'>
                        <button class='btn btn-white btn-sm border-end text-primary'><i class='bi bi-pencil-square'></i></button>
                        <a href='manage_teachers.php?delete=<?php echo $row['teacher_id']; ?>' 
                           onclick="return confirm('Remove this teacher?')" 
                           class='btn btn-white btn-sm text-danger'><i class='bi bi-trash'></i></a>
                    </div>
                </td>
            </tr>
            <?php 
        } 
    } else {
        echo "<tr><td colspan='6' class='text-center py-4'>No teachers found.</td></tr>";
    }
    ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title fw-bold">Register New Educator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="process_teacher.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">FULL NAME</label>
                                <input type="text" name="full_name" class="form-control bg-light border-0 p-3" placeholder="Enter full name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">USERNAME</label>
                                <input type="text" name="username" class="form-control bg-light border-0 p-3" placeholder="Login username" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">PASSWORD</label>
                                <input type="password" name="password" class="form-control bg-light border-0 p-3" placeholder="Set password" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">ASSIGN TO SCHOOL</label>
                                <select name="school_id" class="form-select bg-light border-0 p-3" required style="height: 58px;">
                                    <option value="" selected disabled>Select School...</option>
                                    <?php
                                    $schools = mysqli_query($conn, "SELECT school_id, school_name FROM schools ORDER BY school_name ASC");
                                    while($s = mysqli_fetch_assoc($schools)) {
                                        echo "<option value='".$s['school_id']."'>".$s['school_name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">ACCOUNT STATUS</label>
                                <select name="status" class="form-select bg-light border-0 p-3" style="height: 58px;">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_teacher" class="btn btn-add px-4 rounded-pill shadow">Register Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-0" id="teacher_profile_content">
                        </div>
                </div>
            </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pop-up function
    function showTeacherProfile(teacherId) {
        const contentArea = document.getElementById('teacher_profile_content');
        
        // Show Loading Spinner
        contentArea.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';

        // Open Modal
        var myModal = new bootstrap.Modal(document.getElementById('profileModal'));
        myModal.show();

        // Fetch Data
        fetch('get_teacher_details.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'teacher_id=' + teacherId
        })
        .then(response => response.text())
        .then(data => {
            contentArea.innerHTML = data;
        })
        .catch(err => {
            contentArea.innerHTML = '<p class="text-danger">Error loading data.</p>';
        });
    }
        // Sidebar Toggle Logic
        document.getElementById('toggle-btn').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const wrapper = document.getElementById('main-wrapper');
            sidebar.classList.toggle('collapsed');
            wrapper.classList.toggle('expanded');
        });

        // Auto-active highlight logic
        document.addEventListener("DOMContentLoaded", function() {
            const path = window.location.pathname.split("/").pop();
            const links = document.querySelectorAll('.nav-link');
            links.forEach(link => {
                if (path === link.getAttribute('href')) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>