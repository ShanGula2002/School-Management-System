<?php 
include '../db_connection.php'; 
include 'teacher_auth.php'; 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: my_class.php");
    exit();
}

$student_id = mysqli_real_escape_string($conn, $_GET['id']);
$teacher_id = $_SESSION['teacher_id'];

$grade_map = [
    "0" => "Kinder",
    "1" => "Grade 1", "2" => "Grade 2", "3" => "Grade 3", "4" => "Grade 4", "5" => "Grade 5", "6" => "Grade 6",
    "7" => "Grade 7", "8" => "Grade 8", "9" => "Grade 9", "10" => "Grade 10", "11" => "Grade 11", "12" => "Grade 12"
];

$query = "SELECT s.*, c.section_name, c.grade_level 
          FROM students s 
          LEFT JOIN classes c ON s.class_id = c.class_id 
          WHERE s.student_id = '$student_id' LIMIT 1";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

// Calculate Age
$birthdate = new DateTime($student['birthdate']);
$today = new DateTime('today');
$age = $birthdate->diff($today)->y;

$gender_icon = ($student['gender'] == 'Male') ? 'bi-gender-male text-info' : 'bi-gender-female text-danger';

// --- UPDATED showDoc Function ---
function showDoc($title, $docs_array, $selected_grade) {
    $isUploaded = isset($docs_array[$title]);
    $path = $isUploaded ? $docs_array[$title] : "#";
    
    echo '<div class="doc-item d-flex justify-content-between align-items-center border p-3 mb-2 rounded-3 bg-white">';
    echo '<div><h6 class="mb-0 fw-bold">'.$title.'</h6>';
    echo '<small class="'.($isUploaded ? 'text-success' : 'text-danger').' font-monospace">';
    echo $isUploaded ? '<i class="bi bi-check-circle-fill me-1"></i>Uploaded' : '<i class="bi bi-exclamation-circle-fill me-1"></i>Missing';
    echo '</small></div>';
    
    echo '<div class="d-flex gap-2">';
    if($isUploaded) {
        echo '<a href="'.$path.'" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>';
        // Re-upload Button
        echo '<button type="button" class="btn btn-sm btn-warning rounded-pill px-3" onclick="openUploadModal(\''.$title.'\', \''.$selected_grade.'\')">Update</button>';
    } else {
        // Initial Upload Button
        echo '<button type="button" class="btn btn-sm btn-success rounded-pill px-3" onclick="openUploadModal(\''.$title.'\', \''.$selected_grade.'\')"><i class="bi bi-upload me-1"></i>Upload</button>';
    }
    echo '</div>';
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --deped-blue: #0038a8; --sidebar-width: 260px; }
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; }
        #main-wrapper { margin-left: var(--sidebar-width); padding: 30px; }
        .profile-card { border: none; border-radius: 15px; }
        .avatar-circle { width: 100px; height: 100px; background-color: #eef2ff; color: var(--deped-blue); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; border-radius: 50%; margin: 0 auto; font-weight: bold; }
        .info-label { font-size: 0.8rem; text-transform: uppercase; color: #6c757d; font-weight: 600; margin-bottom: 2px; }
        .info-value { font-size: 1rem; color: #212529; font-weight: 500; }
        .doc-item { transition: 0.2s; }
        .doc-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div id="main-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="my_class.php" class="text-decoration-none text-muted mb-2 d-inline-block">
                    <i class="bi bi-arrow-left me-1"></i> Back to Class List
                </a>
                <h3 class="fw-bold mb-0 text-dark">Student Profile</h3>
            </div>
            <div>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Print Profile
                </button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-5">
                <div class="card profile-card shadow-sm mb-4">
                    <div class="card-body text-center p-4 border-bottom">
                        <div class="avatar-circle mb-3">
                            <?php echo strtoupper(substr($student['first_name'] ?? 'S', 0, 1) . substr($student['last_name'] ?? 'P', 0, 1)); ?>
                        </div>
                        <h4 class="fw-bold mb-1"><?php echo $student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']; ?></h4>
                        <p class="text-muted mb-2">LRN: <span class="fw-bold text-dark"><?php echo $student['lrn']; ?></span></p>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2"><?php echo $student['status']; ?></span>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary mb-3">Academic Details</h6>
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="info-label">Current Grade</div>
                                <div class="info-value"><?php echo $grade_map[$student['grade_level']] ?? "N/A"; ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Section</div>
                                <div class="info-value"><?php echo $student['section_name'] ?? 'Unassigned'; ?></div>
                            </div>
                        </div>
                        <h6 class="fw-bold text-primary mb-3">Personal Details</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="info-label">Gender</div>
                                <div class="info-value"><i class="bi <?php echo $gender_icon; ?> me-1"></i><?php echo $student['gender']; ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Age</div>
                                <div class="info-value"><?php echo $age; ?> yrs old</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card profile-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">Submitted Documents</h5>
                            <form method="GET" class="d-flex align-items-center">
                                <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                                <select name="grade_filter" onchange="this.form.submit()" class="form-select form-select-sm rounded-pill px-3" style="width: auto; background-color: #f8fafc;">
                                    <?php 
                                    $selected_grade = $_GET['grade_filter'] ?? $student['grade_level'];
                                    foreach ($grade_map as $val => $label) {
                                        $active = ($selected_grade == $val) ? 'selected' : '';
                                        echo "<option value='$val' $active>$label</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>

                        <div id="document-list">
                            <?php 
                            $record_query = "SELECT * FROM student_records WHERE student_id = '$student_id' AND grade_level = '$selected_grade'";
                            $record_res = mysqli_query($conn, $record_query);
                            $docs = [];
                            while($r = mysqli_fetch_assoc($record_res)) { $docs[$r['document_type']] = $r['file_path']; }

                            if ($selected_grade == "0") {
                                showDoc("PSA Birth Certificate", $docs, $selected_grade);
                                showDoc("ECD Checklist", $docs, $selected_grade);
                                showDoc("Progress Report Card", $docs, $selected_grade);
                                showDoc("Literacy Numeracy Checklist", $docs, $selected_grade);
                                showDoc("Moving Up Certificate", $docs, $selected_grade);
                            } else {
                                showDoc("PSA Birth Certificate", $docs, $selected_grade);
                                showDoc("School Form 5", $docs, $selected_grade);
                                showDoc("School Form 9", $docs, $selected_grade);
                                showDoc("School Form 10", $docs, $selected_grade);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="upload_doc_process.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header border-0">
                        <h5 class="fw-bold">Upload <span id="docTypeName" class="text-primary"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Select a file (PDF or Image) to attach to this student's record for <strong><?php echo $grade_map[$selected_grade]; ?></strong>.</p>
                        <input type="file" name="student_file" class="form-control" required>
                        
                        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                        <input type="hidden" name="grade_level" id="modalGradeLevel">
                        <input type="hidden" name="doc_type" id="modalDocType">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="upload_btn" class="btn btn-primary rounded-pill px-4">Upload File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openUploadModal(docType, gradeLevel) {
            document.getElementById('docTypeName').innerText = docType;
            document.getElementById('modalDocType').value = docType;
            document.getElementById('modalGradeLevel').value = gradeLevel;
            
            var myModal = new bootstrap.Modal(document.getElementById('uploadModal'));
            myModal.show();
        }
    </script>
</body>
</html>