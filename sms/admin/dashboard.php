<?php 
include '../db_connection.php'; 
include 'admin_auth.php'; 

$school_count_query  = mysqli_query($conn, "SELECT school_id FROM schools");
$total_schools       = mysqli_num_rows($school_count_query);
$teacher_count_query = mysqli_query($conn, "SELECT teacher_id FROM teachers");
$total_teachers      = mysqli_num_rows($teacher_count_query);

$student_count_query = mysqli_query($conn, "SELECT student_id FROM students");
$total_students      = mysqli_num_rows($student_count_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root { --deped-blue: #0038a8; }
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; font-size: larger;}
        #main-wrapper {
            margin-left: var(--sidebar-width);
            padding: 25px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }   
        .stat-card { border: none; border-radius: 15px; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-box { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }
        .table thead th { background-color: #f8f9fa; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; border: none; }
        .navbar { border-radius: 15px; }
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

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card stat-card shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 rounded-4 text-primary me-3">
                            <i class="bi bi-building fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Schools</p>
                            <h3 class="fw-bold mb-0"><?php echo $total_schools; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success bg-opacity-10 rounded-4 text-success me-3">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Active Teachers</p>
                            <h3 class="fw-bold mb-0"><?php echo $total_teachers; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-info bg-opacity-10 rounded-4 text-info me-3">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Students</p>
                            <h3 class="fw-bold mb-0"><?php echo $total_students; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 border-0 shadow-sm p-4 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-0">System Activity Logs</h5>
                    <p class="small text-muted mb-0">Tracking real-time updates across the infrastructure</p>
                </div>
                <a href="logs.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Full Audit</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Action Type</th>
                            <th>Involved User</th>
                            <th>Affiliated School</th>
                            <th>Timestamp</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-file-earmark-arrow-up me-1"></i> Form 137 Upload
                                </span>
                            </td>
                            <td><span class="fw-medium">Teacher Maria</span></td>
                            <td>Central Pilot HS</td>
                            <td class="text-muted small"><?php echo date('M d, Y | h:i A'); ?></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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