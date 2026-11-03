<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | DepEd Learner Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --deped-blue: #0038a8; --deped-dark: #002673; --deped-gold: #ffcd00; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        
        /* Fixed Navbar */
        .navbar { background: rgba(255, 255, 255, 0.95); border-bottom: 3px solid var(--deped-blue); backdrop-filter: blur(10px); z-index: 1030; }
        .navbar-brand { color: var(--deped-blue) !important; font-size: 1.5rem; }

        /* Full Screen Hero with Blue Transparent Overlay */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            padding-top: 80px;
            position: relative; /* Essential for overlay */

            /* 🚀 THE FIX: A deep blue gradient (high to low opacity) over a background image */
            background: linear-gradient(
                rgba(0, 38, 115, 0.90), /* Deep Blue at 90% opacity (stronger contrast) */
                rgba(0, 38, 115, 0.85)  /* Slightly lighter blue at the bottom */
            ), 
            url(bago.jpg);
            
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Parallax effect */
        }

        .btn-start {
            background-color: var(--deped-gold);
            color: #000;
            font-weight: 800;
            padding: 18px 45px;
            border-radius: 50px;
            transition: 0.3s all ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-start:hover { background-color: #e6b800; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); color: #000; }

        /* Stats Bar */
        .stats-bar { margin-top: -50px; position: relative; z-index: 10; }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }

        /* Features */
        .feature-icon-circle {
            width: 80px; height: 80px;
            background: rgba(0, 56, 168, 0.1);
            color: var(--deped-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
        }
        
        /* Modal Customization */
        .modal-content { border: none; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .modal-header { background: var(--deped-blue); color: white; border: none; padding: 25px; }
        .btn-login { background: var(--deped-blue); color: white; font-weight: 600; border-radius: 10px; padding: 12px; transition: 0.3s; }
        .btn-login:hover { background: var(--deped-dark); color: white; transform: scale(1.02); }

        .footer { background: #1a1a1a; color: #888; padding: 60px 0; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-mortarboard-fill me-2"></i>LMS PORTAL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link px-3 fw-medium" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3 fw-medium" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link px-3 fw-medium" href="#about">About</a></li>
                <li class="nav-item ms-lg-3">
                    <button class="btn btn-outline-primary px-4 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Sign In
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero-section">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <span class="badge rounded-pill bg-light text-primary px-3 py-2 mb-3 fw-bold">OFFICIAL SCHOOL MANAGEMENT SYSTEM</span>
                <h1 class="display-2 fw-bold mb-4">Empowering Learner Data for a Better Future</h1>
                <p class="lead mb-5 opacity-75 fs-4">A comprehensive digital infrastructure designed to track student progress from Kindergarten through Grade 12.</p>
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-start shadow-lg" data-bs-toggle="modal" data-bs-target="#loginModal">
                        GET STARTED <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="stats-bar container">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card stat-card shadow-lg p-4">
                <h2 class="fw-bold text-primary mb-1">100%</h2>
                <p class="text-muted mb-0 fw-medium uppercase">Data Security</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-lg p-4">
                <h2 class="fw-bold text-primary mb-1">K-12</h2>
                <p class="text-muted mb-0 fw-medium">Universal Coverage</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-lg p-4">
                <h2 class="fw-bold text-primary mb-1">Real-time</h2>
                <p class="text-muted mb-0 fw-medium">Analytics & Tracking</p>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5 mt-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase">Core Capabilities</h6>
            <h2 class="fw-bold display-5">Designed for Modern Educators</h2>
        </div>
        <div class="row g-5 text-center">
            <div class="col-md-4">
                <div class="feature-icon-circle"><i class="bi bi-shield-lock"></i></div>
                <h4 class="fw-bold">Secure Vault</h4>
                <p class="text-muted">Encrypted storage for SF10, Form 137, and sensitive student permanent records.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon-circle"><i class="bi bi-graph-up-arrow"></i></div>
                <h4 class="fw-bold">Progress Tracking</h4>
                <p class="text-muted">Visualized academic growth across all grade levels with automated report generation.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon-circle"><i class="bi bi-cpu"></i></div>
                <h4 class="fw-bold">Automated Admin</h4>
                <p class="text-muted">Streamlined school management tools for principals and administrative staff.</p>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Portal Login</h4>
                    <p class="small mb-0 opacity-75">Enter your credentials to access the system</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-5">
                <form action="login_process.php" method="POST">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">USERNAME</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-primary"></i></span>
                            <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="e.g. admin_main" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">PASSWORD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-primary"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                        </div>
                    </div>
                    <button type="submit" name="login_btn" class="btn btn-login w-100 py-3 shadow">
                        SIGN IN TO SYSTEM <i class="bi bi-box-arrow-in-right ms-2"></i>
                    </button>
                </form>
            </div>
            <div class="modal-footer justify-content-center border-0 bg-light py-3">
                <span class="small text-muted"><i class="bi bi-info-circle me-1"></i> For technical support, contact the District IT Admin.</span>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <h5 class="text-white fw-bold mb-3">LMS PORTAL</h5>
                <p class="small">A Next-Generation Learner Management System built for modern education standards.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small">&copy; 2026 Learner Management System. Philippines.</p>
                <div class="mt-2">
                    <a href="#" class="text-muted me-3 text-decoration-none small">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none small">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>