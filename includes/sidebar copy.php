<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Board</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .sidebar {
            width: 240px;
            height: 100vh;
            background-color: #1f2937;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 40;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            border-bottom: 1px solid #374151;
        }
        
        .sidebar-logo {
            background-color: white;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }
        
        .nav-link {
            color: #9ca3af;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(55, 65, 81, 0.5);
            color: white;
        }
        
        .nav-link.active {
            background-color: #4f46e5;
            color: white;
        }
        
        .nav-icon {
            font-size: 1.25rem;
        }
        
        .sidebar-footer {
            border-top: 1px solid #374151;
        }
        
        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <!-- Logo and Title -->
        <div class="sidebar-header d-flex align-items-center justify-content-center p-4">
            <div class="sidebar-logo me-3">
                <img alt="Notice Board System Logo" class="img-fluid" width="32" height="32" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA5qcnOT8AWiEW4NxN7DGjUIoYEZl5xV4_4UkOl-7V0nbCywnYOLBf4Fm34DvKhXSSDJey4TV9IfsqxzXxNzyS1GRCWJCCgbxPUI77ijPPE3qdoPVM3Soq-XHr9XkV8oVumvOfSaYK0xvQNSLekagkbYC0o4jhuRSX9n1nqfpsyFRhYAjQQ_uSCTrWgpQSc2Qw1BLjLj2y8SEz8jNboYA58aNksrhUijPgkmYYJEQrG7ZX_kfGGZRHqZ0N0o2KeSjl6KJoLkww">
            </div>
            <h1 class="h5 m-0 fw-bold">Notice Board</h1>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow-1 p-3">
            <div class="nav flex-column">
                <a href="dashboard.php" class="nav-link active mb-2">
                    <i class="material-icons nav-icon">dashboard</i>
                    <span class="ms-3">Dashboard</span>
                </a>
                <a href="add-notices.php" class="nav-link mb-2">
                    <i class="material-icons nav-icon">add_circle_outline</i>
                    <span class="ms-3">Add Notice</span>
                </a>
                <a href="manage-notices.php" class="nav-link mb-2">
                    <i class="material-icons nav-icon">manage_search</i>
                    <span class="ms-3">Manage Notices</span>
                </a>
                <a href="category.php" class="nav-link mb-2">
                    <i class="material-icons nav-icon">category</i>
                    <span class="ms-3">Categories</span>
                </a>
                <a href="bwdates-reports.php" class="nav-link mb-2">
                    <i class="material-icons nav-icon">filter_list</i>
                    <span class="ms-3">Date Filter</span>
                </a>
            </div>
        </nav>

        <!-- Footer -->
        <div class="sidebar-footer p-3">
            <div class="d-flex align-items-center">
                <img alt="Admin avatar" class="admin-avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWdbj-rVS02zykaY7kWiZ-0iqgsbUMLFVbMPDiouS9roU5FdK9nJMD2Pdp4h3HJQ4l5x7lKhoXzAms5guLYA5lLrku6f0zY1H2Y-g9a9h9xzzRlI0Hb-HlkpJI1TgX3qGVs0BzUNyX9WZ74dpDVIuuQYCjZ295O5MG6KvVQmwRLulmMp1Rp3R39e5MNlj7J9micgI5Ph5dXoZpdGXESnrQZJfbrQkxRUxO9ozHPONO1MifjZ_nW460iOYrgzLLZXgfR_CBgDM">
                <div class="ms-3">
                    <p class="mb-0 fw-semibold">Admin</p>
                    <p class="mb-0 small text-gray-400">admin@example.com</p>
                </div>
                <a href="logout.php" class="ms-auto text-gray-400 hover:text-white">
                    <i class="material-icons">logout</i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>