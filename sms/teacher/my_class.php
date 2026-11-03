<?php 
include '../db_connection.php'; 
include 'teacher_auth.php'; 

$teacher_id = $_SESSION['teacher_id'];

// 1. Fetch the class assigned to this teacher
$class_query = "SELECT c.class_id, c.grade_level, c.section_name 
                FROM classes c 
                WHERE c.teacher_id = '$teacher_id' LIMIT 1";
$class_result = mysqli_query($conn, $class_query);
$class_info = mysqli_fetch_assoc($class_result);

$current_class_id = $class_info['class_id'] ?? null;
$my_grade_level = $class_info['grade_level'] ?? null;

// Helper for display
$grade_labels = [
    "0" => "Kinder", "1" => "Grade 1", "2" => "Grade 2", "3" => "Grade 3", "4" => "Grade 4", 
    "5" => "Grade 5", "6" => "Grade 6", "7" => "Grade 7", "8" => "Grade 8", "9" => "Grade 9", 
    "10" => "Grade 10", "11" => "Grade 11", "12" => "Grade 12"
];

$display_class = ($class_info) ? ($grade_labels[$my_grade_level] ?? "Grade " . $my_grade_level) . " - " . $class_info['section_name'] : "No Class Assigned";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Class | SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --deped-blue: #0038a8; --sidebar-width: 260px; }
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; }
        #main-wrapper { margin-left: var(--sidebar-width); padding: 30px; }
        .student-card { border: none; border-radius: 15px; overflow: hidden; }
        .table thead th { background-color: #f8f9fa; color: #6c757d; font-size: 0.8rem; text-transform: uppercase; border: none; }
        .lrn-badge { background: #eef2ff; color: #0038a8; font-weight: bold; padding: 4px 10px; border-radius: 6px; font-family: monospace; }
        .gender-icon-male { color: #0ea5e9; }
        .gender-icon-female { color: #ec4899; }
        .doc-link { font-size: 0.75rem; text-decoration: none; font-weight: 600; display: inline-block; margin-bottom: 2px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div id="main-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">My Class List</h3>
                <p class="text-muted mb-0"><?php echo $display_class; ?></p>
            </div>
            <div class="d-flex gap-2">
                <?php if($current_class_id): ?>
                <button class="btn btn-outline-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#promoteModal">
                    <i class="bi bi-arrow-up-circle me-2"></i>Import Students
                </button>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#enrollModal">
                    <i class="bi bi-person-plus-fill me-2"></i>New Enroll
                </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> 
                <?php 
                    echo ($_GET['success'] == 'promoted') ? "Students successfully imported to your class!" : "Student successfully enrolled.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card student-card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No.</th>
                                <th>Student Name</th>
                                <th>LRN</th>
                                <th>Gender</th>
                                <th class="text-end pe-4">Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($current_class_id) {
                                $student_sql = "SELECT * FROM students WHERE class_id = '$current_class_id' ORDER BY last_name ASC";
                                $student_result = mysqli_query($conn, $student_sql);
                                $count = 1;

                                if (mysqli_num_rows($student_result) > 0) {
                                    while($row = mysqli_fetch_assoc($student_result)) {
                                        $gender_icon = ($row['gender'] == 'Male') ? 'bi-gender-male gender-icon-male' : 'bi-gender-female gender-icon-female';
                                        ?>
                                        <tr>
                                            <td class="ps-4 text-muted small"><?php echo $count++; ?></td>
                                            <td><div class="fw-bold text-dark"><?php echo strtoupper($row['last_name']) . ", " . $row['first_name']; ?></div></td>
                                            <td><span class="lrn-badge"><?php echo $row['lrn']; ?></span></td>
                                            <td><i class="bi <?php echo $gender_icon; ?> me-2"></i><?php echo $row['gender']; ?></td>
                                            <td class="text-end pe-4">
                                                <a href="student_profile.php?id=<?php echo $row['student_id']; ?>" class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm">
                                                    View Profile
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'>No students in this section. Use 'Import' or 'New Enroll'.</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="promoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="promotion_process.php" method="POST">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 p-4">
                        <h5 class="fw-bold mb-0">Import Students from Previous Grade</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 pt-0">
                        <p class="text-muted small mb-3">Select students moving up to your current grade level (<?php echo $grade_labels[$my_grade_level] ?? ''; ?>).</p>
                        <input type="hidden" name="target_class_id" value="<?php echo $current_class_id; ?>">
                        
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Student Name</th>
                                        <th>LRN</th>
                                        <th>Prev. Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Logic: Find students not in your class, potentially from the grade below
                                    $prev_grade = $my_grade_level - 1;
                                    $search_sql = "SELECT s.*, c.grade_level as prev_grade_label 
                                                   FROM students s 
                                                   LEFT JOIN classes c ON s.class_id = c.class_id
                                                   WHERE s.class_id != '$current_class_id' 
                                                   AND (c.grade_level = '$prev_grade' OR s.class_id IS NULL)
                                                   LIMIT 50";
                                    $search_res = mysqli_query($conn, $search_sql);
                                    
                                    while($s = mysqli_fetch_assoc($search_res)): ?>
                                    <tr>
                                        <td><input type="checkbox" name="student_ids[]" value="<?php echo $s['student_id']; ?>"></td>
                                        <td class="small fw-bold"><?php echo $s['last_name'] . ", " . $s['first_name']; ?></td>
                                        <td class="small"><?php echo $s['lrn']; ?></td>
                                        <td><span class="badge bg-secondary"><?php echo $grade_labels[$s['prev_grade_label']] ?? 'N/A'; ?></span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" name="promote_btn" class="btn btn-primary rounded-pill px-4">Confirm Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="enrollModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="enroll_process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 p-4">
                        <h5 class="fw-bold mb-0">New Student Enrollment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">LRN</label>
                                <input type="text" name="lrn" class="form-control rounded-3" required maxlength="12">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">GRADE LEVEL</label>
                                <select name="grade_level" id="gradeLevelSelect" class="form-select rounded-3" required>
                                    <option value="" selected disabled>Select Grade Level</option>
                                    <?php foreach($grade_labels as $val => $label): ?>
                                        <option value="<?php echo $val; ?>"><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">FIRST NAME</label>
                                <input type="text" name="first_name" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">MIDDLE NAME</label>
                                <input type="text" name="middle_name" class="form-control rounded-3">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">LAST NAME</label>
                                <input type="text" name="last_name" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">GENDER</label>
                                <select name="gender" class="form-select rounded-3" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">BIRTHDATE</label>
                                <input type="date" name="birthdate" class="form-control rounded-3" required>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold text-primary mb-3">Upload Requirements</h6>
                                <div id="kinderDocs" class="row g-3 d-none">
                                    <div class="col-md-4"><label class="small">PSA Cert</label><input type="file" name="psa_doc" class="form-control form-control-sm"></div>
                                    <div class="col-md-4"><label class="small">ECD Checklist</label><input type="file" name="ecd_checklist" class="form-control form-control-sm"></div>
                                    <div class="col-md-4"><label class="small">Progress Card</label><input type="file" name="progress_report" class="form-control form-control-sm"></div>
                                </div>
                                <div id="gradeDocs" class="row g-3 d-none">
                                    <div class="col-md-4"><label class="small">SF5</label><input type="file" name="sf5" class="form-control form-control-sm"></div>
                                    <div class="col-md-4"><label class="small">SF9</label><input type="file" name="sf9" class="form-control form-control-sm"></div>
                                    <div class="col-md-4"><label class="small">SF10</label><input type="file" name="sf10" class="form-control form-control-sm"></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="class_id" value="<?php echo $current_class_id; ?>">
                        <input type="hidden" name="school_id" value="<?php echo $_SESSION['school_id'] ?? '1'; ?>">
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" name="enroll_btn" class="btn btn-primary rounded-pill px-4">Register Student</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Select All Checkbox logic
        document.getElementById('selectAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Toggle Document Uploads
        document.getElementById('gradeLevelSelect').addEventListener('change', function() {
            const val = this.value;
            document.getElementById('kinderDocs').classList.toggle('d-none', val !== "0");
            document.getElementById('gradeDocs').classList.toggle('d-none', val === "0" || val === "");
        });
    </script>
</body>
</html>