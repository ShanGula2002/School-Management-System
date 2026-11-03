<?php
// Get the current page filename to set the 'active' class
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar" class="bg-white shadow-sm d-flex flex-column">
    <div class="sidebar-header p-4 text-center">
        <img src="../logo.jpg" alt="Logo" style="width: 50px; border-radius: 8px;" class="mb-2 shadow-sm">
        <h6 class="fw-bold text-dark mb-0">Teacher Portal</h6>
        <small class="text-muted" style="font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase;">DepEd System</small>
    </div>
    
    <div class="list-group list-group-flush px-3 flex-grow-1">
        <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="bi bi-grid-1x2-fill me-3"></i>Dashboard
        </a>

        <a href="my_class.php" class="nav-link <?php echo ($current_page == 'my_class.php' || $current_page == 'student_profile.php') ? 'active' : ''; ?>">
            <i class="bi bi-people-fill me-3"></i>My Students
        </a>

        <a href="#" class="nav-link text-muted" style="cursor: not-allowed;">
            <i class="bi bi-calendar-event me-3"></i>Schedule
        </a>

        <hr class="mx-2 my-3 text-secondary opacity-25">
        
        <a href="../logout.php" class="nav-link text-danger mt-auto mb-3">
            <i class="bi bi-box-arrow-left me-3"></i>Logout
        </a>
    </div>

    <div class="sidebar-footer p-3 border-top bg-light-subtle">
        <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px; font-size: 0.8rem;">
                T
            </div>
            <div class="ms-2 overflow-hidden">
                <p class="mb-0 fw-bold small text-dark text-truncate"><?php echo $_SESSION['teacher_name'] ?? 'Teacher'; ?></p>
                <p class="mb-0 text-muted" style="font-size: 0.65rem;">Academic Year 2025-2026</p>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --deped-blue: #0038a8;
        --sidebar-width: 260px;
    }

    #sidebar { 
        width: var(--sidebar-width); 
        height: 100vh; 
        position: fixed; 
        left: 0; 
        top: 0; 
        z-index: 1000; 
        border-right: 1px solid #eef0f3; 
    }

    /* Modern Nav Link Styling */
    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 18px;
        text-decoration: none;
        color: #64748b;
        font-weight: 500;
        font-size: 0.9rem;
        border-radius: 10px;
        margin-bottom: 5px;
        transition: all 0.2s ease;
    }

    .nav-link i {
        font-size: 1.1rem;
    }

    /* Hover State */
    .nav-link:hover {
        background-color: #f8fafc;
        color: var(--deped-blue);
        transform: translateX(5px);
    }

    /* Active State */
    .nav-link.active {
        background-color: #eef2ff;
        color: var(--deped-blue);
        font-weight: 700;
        box-shadow: inset 4px 0 0 var(--deped-blue); /* Visual indicator on the left */
    }

    /* Hide sidebar on print */
    @media print {
        #sidebar { display: none !important; }
    }
</style>