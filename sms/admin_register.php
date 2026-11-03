<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration | LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --deped-blue: #0038a8; --deped-dark: #002673; }
        body { 
            background: linear-gradient(rgba(0, 56, 168, 0.8), rgba(0, 38, 115, 0.8)), 
                        url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }
        .reg-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .reg-header {
            background: var(--deped-blue);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .btn-register {
            background: var(--deped-blue);
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-register:hover {
            background: var(--deped-dark);
            color: white;
            transform: translateY(-2px);
        }
        .form-control:focus {
            border-color: var(--deped-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 56, 168, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card reg-card">
                <div class="reg-header">
                    <i class="bi bi-shield-lock-fill fs-1 mb-2"></i>
                    <h3 class="fw-bold mb-0">Admin Registration</h3>
                    <p class="small opacity-75 mb-0">System Infrastructure Access</p>
                </div>
                <div class="card-body p-4 p-lg-5 bg-white">
                    <form action="admin_register_process.php" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">FULL NAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person text-primary"></i></span>
                                <input type="text" name="full_name" class="form-control" placeholder="Juan Dela Cruz" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">ASSIGNED SCHOOL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-building text-primary"></i></span>
                                <select name="school_id" class="form-select" required>
                                    <option value="" selected disabled>Select School...</option>
                                    <?php
                                    include 'db_connection.php';
                                    $schools = mysqli_query($conn, "SELECT * FROM schools");
                                    while($s = mysqli_fetch_assoc($schools)) {
                                        echo "<option value='".$s['school_id']."'>".$s['school_name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">USERNAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-at text-primary"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="admin_username" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">PASSWORD</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key text-primary"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" name="reg_admin_btn" class="btn btn-register w-100 mb-3 shadow">
                            CREATE ADMIN ACCOUNT
                        </button>
                        
                        <div class="text-center">
                            <a href="index.php" class="text-decoration-none small text-muted">
                                <i class="bi bi-arrow-left me-1"></i> Back to Landing Page
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>