<?php

include 'admin_auth.php';

?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <div class="logo-wrapper d-flex align-items-center">
            <i class="bi bi-mortarboard-fill fs-3 text-warning"></i>
            <span class="logo-text ms-2 fw-bold text-white">LMS ADMIN</span>
        </div>
    </div>
    
    <ul class="nav flex-column mt-4">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link" title="Dashboard">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_schools.php" class="nav-link" title="Schools">
                <i class="bi bi-building"></i>
                <span class="link-text">Schools</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_teachers.php" class="nav-link" title="Teachers">
                <i class="bi bi-person-badge"></i>
                <span class="link-text">Teachers</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_students.php" class="nav-link" title="Students">
                <i class="bi bi-people"></i>
                <span class="link-text">Students</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="manage_classes.php" class="nav-link" title="Classes">
                <i class="bi bi-collection-fill"></i>
                <span class="link-text">Manage Classes</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="reports.php" class="nav-link" title="Reports">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span class="link-text">Reports</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="../logout.php" class="nav-link text-danger" title="Logout">
            <i class="bi bi-box-arrow-left"></i>
            <span class="link-text">Logout</span>
        </a>
    </div>
</div>