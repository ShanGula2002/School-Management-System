<?php 
include '../db_connection.php'; 
include 'teacher_auth.php'; 

if(!isset($_SESSION['teacher_id'])){ header("Location: ../index.php"); exit(); }

$teacher_id = $_SESSION['teacher_id'];

// 1. Get Teacher and Assigned Class Details
$query = "SELECT t.*, c.class_id, c.grade_level, c.section_name 
          FROM teachers t 
          LEFT JOIN classes c ON t.teacher_id = c.teacher_id 
          WHERE t.teacher_id = '$teacher_id'";
$teacher_data = mysqli_fetch_assoc(mysqli_query($conn, $query));

$class_id = $teacher_data['class_id'] ?? 0;
$grade_level = $teacher_data['grade_level'] ?? '';
$assigned_class = ($teacher_data['section_name']) ? "Grade " . $grade_level . " - " . $teacher_data['section_name'] : "No Class Assigned";

// 2. Objective: Count Total Enrolled Students
$student_count_query = "SELECT COUNT(*) as total FROM students WHERE class_id = '$class_id'";
$student_count = mysqli_fetch_assoc(mysqli_query($conn, $student_count_query))['total'];

// 3. Objective: Document Monitoring (Identify students with missing files)
// This checks students who don't have at least 3 records (adjust number based on your requirements)
$missing_docs_query = "SELECT COUNT(DISTINCT s.student_id) as incomplete 
                       FROM students s 
                       LEFT JOIN student_records r ON s.student_id = r.student_id 
                       WHERE s.class_id = '$class_id' 
                       GROUP BY s.student_id 
                       HAVING COUNT(r.record_id) < 3";
$missing_result = mysqli_query($conn, $missing_docs_query);
$incomplete_count = mysqli_num_rows($missing_result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard | SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --deped-blue: #0038a8; --sidebar-width: 260px; }
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; }
        #main-content { margin-left: var(--sidebar-width); padding: 30px; }
        .welcome-card { background: linear-gradient(135deg, #0038a8 0%, #0056b3 100%); color: white; border: none; border-radius: 20px; }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; background: white; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div id="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark">Hello, <?php echo explode(' ', $teacher_data['full_name'])[0]; ?>! 👋</h4>
                <p class="text-muted small mb-0">Academic Year: 2025-2026</p>
            </div>
            <div class="bg-white p-2 px-3 rounded-pill shadow-sm border">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                <span class="small fw-medium"><?php echo date('F j, Y'); ?></span>
            </div>
        </div>

        <div class="card welcome-card p-4 mb-4 shadow-sm border-0">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-1"><?php echo $assigned_class; ?></h2>
                    <p class="mb-0 opacity-75">Your student management objectives for today:</p>
                    <div class="mt-3">
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 me-2">
                            <i class="bi bi-check2-circle me-1"></i> Check missing SF9s
                        </span>
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2">
                            <i class="bi bi-clock-history me-1"></i> Update Focus Metrics
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="bi bi-shield-check" style="font-size: 5rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4 text-center">
            <div class="col-md-4">
                <div class="card stat-card shadow-sm p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                    <h3 class="mb-0 fw-bold"><?php echo $student_count; ?></h3>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Enrolled Students</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card shadow-sm p-4 border-bottom border-warning border-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-file-earmark-x-fill text-warning fs-4"></i>
                    </div>
                    <h3 class="mb-0 fw-bold text-warning"><?php echo $incomplete_count; ?></h3>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Incomplete Records</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card shadow-sm p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-cpu text-success fs-4"></i>
                    </div>
                    <h3 class="mb-0 fw-bold text-success">Good</h3>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Class Focus Status</small>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Record Management Tips</h5>
                        <a href="my_class.php" class="btn btn-sm btn-link text-decoration-none p-0 text-primary">View All Students</a>
                    </div>
                    <div class="alert alert-light border rounded-3 p-3 mb-3">
                        <div class="d-flex">
                            <i class="bi bi-lightbulb text-warning me-3 fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Kindergarten Objective</h6>
                                <p class="small text-muted mb-0">Ensure all Kinder students have their <strong>ECD Checklist</strong> uploaded. This is a CHED/DepEd requirement for graduation eligibility.</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-light border rounded-3 p-3">
                        <div class="d-flex">
                            <i class="bi bi-info-circle text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1">SF10 (Permanent Record)</h6>
                                <p class="small text-muted mb-0">Students coming from other schools must submit their original SF10 before the end of the 2nd Quarter.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3 text-dark">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="enroll_student.php" class="btn btn-primary text-start p-3 rounded-3 shadow-sm border-0">
                            <i class="bi bi-person-plus-fill me-2"></i> Enroll New Student
                        </a>
                        <a href="my_class.php" class="btn btn-outline-secondary text-start p-3 rounded-3 border-dashed">
                            <i class="bi bi-file-earmark-arrow-up me-2"></i> Bulk Upload Records
                        </a>
                        <button class="btn btn-outline-dark text-start p-3 rounded-3" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i> Print Masterlist
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>