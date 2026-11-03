<?php 
include '../db_connection.php'; 
include 'admin_auth.php'; 

// Handle School Deletion
if(isset($_GET['delete'])){
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM schools WHERE school_id = '$id'");
    header("Location: manage_schools.php?msg=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Manage Schools</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root { --deped-blue: #0038a8; --sidebar-width: 260px; --sidebar-collapsed-width: 80px; }
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; overflow-x: hidden; font-size: larger;}

        /* 🚀 Alignment Logic (Same as Dashboard) */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        #main-wrapper {
            margin-left: var(--sidebar-width);
            padding: 25px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        #sidebar.collapsed { width: var(--sidebar-collapsed-width); }
        #main-wrapper.expanded { margin-left: var(--sidebar-collapsed-width); }

        /* UI Styling */
        .navbar { border-radius: 15px; }
        .school-card { border: none; border-radius: 20px; }
        .btn-add { background-color: var(--deped-blue); color: white; border-radius: 12px; font-weight: 600; transition: 0.3s; }
        .btn-add:hover { background-color: #002673; color: white; transform: translateY(-2px); }
        .table thead th { background-color: #f8f9fa; color: #6c757d; font-size: 0.75rem; text-transform: uppercase; border: none; letter-spacing: 1px; }
        .icon-box-sm { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; }
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
                <h3 class="fw-bold mb-0 text-dark">Manage Schools</h3>
                <p class="text-muted small mb-0">Monitor and update school-level infrastructure</p>
            </div>
            <button class="btn btn-add px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                <i class="bi bi-plus-lg me-2"></i>Register New School
            </button>
        </div>

        <div class="card school-card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">School Name</th>
                                <th>School ID</th>
                                <th>Location</th>
                                <th>School Type</th>
                                <th>Staffing</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT s.*, (SELECT COUNT(*) FROM teachers t WHERE t.school_id = s.school_id) as teacher_count 
                                    FROM schools s ORDER BY s.school_name ASC";
                            $result = mysqli_query($conn, $sql);

                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td class='ps-4'>
                                        <div class='d-flex align-items-center py-2'>
                                            <div class='icon-box-sm bg-primary bg-opacity-10 text-primary me-3'>
                                                <i class='bi bi-building'></i>
                                            </div>
                                            <span class='fw-bold'>".$row['school_name']."</span>
                                        </div>
                                    </td>
                                    <td><span class='badge bg-light text-primary border px-3'>".$row['school_id_code']."</span></td>
                                    <td class='text-muted small'>".$row['school_address']."</td>
                                     <td class='text-muted small'>".$row['school_type']."</td>
                                    <td>
                                        <span class='badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill'>
                                            <i class='bi bi-person-badge me-1'></i>".$row['teacher_count']." Teachers
                                        </span>
                                    </td>
                                    <td class='text-end pe-4'>
                                        <div class='btn-group shadow-sm rounded-3'>
                                            <button class='btn btn-white btn-sm border-end text-primary'><i class='bi bi-pencil-square'></i></button>
                                            <a href='manage_schools.php?delete=".$row['school_id']."' 
                                               onclick=\"return confirm('Confirm deletion? This will affect linked data.')\" 
                                               class='btn btn-white btn-sm text-danger'><i class='bi bi-trash'></i></a>
                                        </div>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSchoolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white p-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-building-add me-2"></i>Register Institution</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="process_school.php" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">OFFICIAL SCHOOL NAME</label>
                            <input type="text" name="school_name" class="form-control bg-light border-0 p-3" placeholder="e.g. Roxas City National High School" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">SCHOOL ID CODE</label>
                            <input type="text" name="school_id_code" class="form-control bg-light border-0 p-3" placeholder="e.g. 302514" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">SCHOOL TYPE</label>
                            <select name="school_type" class="form-select bg-light border-0 p-3" required style="height: 58px;">
                                <option value="" selected disabled>Select type...</option>
                                <option value="Public">Public</option>
                                <option value="Private">Private</option>
                                <option value="Sate College/University">Sate College/University</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">COMPLETE ADDRESS</label>
                            <textarea name="school_address" class="form-control bg-light border-0 p-3" rows="3" placeholder="Street, Barangay, City, Province" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_school" class="btn btn-add px-4 rounded-pill shadow">
                        <i class="bi bi-check-lg me-1"></i> Save School
                    </button>
                </div>
            </form>
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
</html>