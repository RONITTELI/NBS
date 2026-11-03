<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include('includes/dbconnection.php');

$adminid = $_SESSION['nbid'];
$ret = mysqli_query($con, "SELECT AdminName, Email FROM tbladmin WHERE ID='$adminid'");
$row = mysqli_fetch_array($ret);
$name = $row['AdminName'];
$email = $row['Email'];

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Notice Board</title>

  <!-- Bootstrap & Tailwind -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body { background: #f8fafc; }
    .sidebar {
      width: 240px;
      height: 100vh;
      background: linear-gradient(180deg, #1e293b, #0f172a);
      color: white;
      position: fixed;
      left: 0;
      top: 0;
      z-index: 40;
      display: flex;
      flex-direction: column;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    }
    .sidebar-header { border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
    .sidebar-logo { background-color: white; padding: 0.5rem; border-radius: 0.5rem; }
    .nav-link {
      color: #cbd5e1;
      border-radius: 8px;
      padding: 0.8rem 1rem;
      margin-bottom: 0.4rem;
      display: flex;
      align-items: center;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .nav-link:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #ffffff;
      transform: translateX(4px);
    }
    .nav-link.active {
      background: #4f46e5;
      color: white;
      box-shadow: 0 2px 6px rgba(79, 70, 229, 0.5);
    }
    .nav-icon {
      font-size: 1.4rem;
      color: inherit;
      transition: transform 0.3s;
    }

    .admin-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    @media (max-width: 992px) {
      .sidebar { display: none; }
      .mobile-navbar { display: flex; }
      main { margin-left: 0 !important; padding-bottom: 80px; }
    }

    .mobile-navbar {
      display: none;
      position: fixed;
      bottom: 10px;
      left: 10px;
      right: 10px;
      background: #1e293b;
      z-index: 50;
      padding: 5px;
      border-radius: 15px;
      box-shadow: 0 -2px 15px rgba(0,0,0,0.2);
    }

    .mobile-nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #cbd5e1;
      text-decoration: none;
      padding: 6px 10px;
      font-size: 0.75rem;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .mobile-nav-item.active {
      color: #ffffff;
      background: #4f46e5;
      box-shadow: 0 2px 6px rgba(79, 70, 229, 0.5);
    }

    .mobile-nav-icon {
      font-size: 1.4rem;
      margin-bottom: 0.25rem;
    }

    .animate-fade-in {
      animation: fadeIn 0.25s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-center p-4">
      <div class="sidebar-logo me-2">
        <img alt="Logo" width="28" height="28" class="img-fluid"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuA5qcnOT8AWiEW4NxN7DGjUIoYEZl5xV4_4UkOl-7V0nbCywnYOLBf4Fm34DvKhXSSDJey4TV9IfsqxzXxNzyS1GRCWJCCgbxPUI77ijPPE3qdoPVM3Soq-XHr9XkV8oVumvOfSaYK0xvQNSLekagkbYC0o4jhuRSX9n1nqfpsyFRhYAjQQ_uSCTrWgpQSc2Qw1BLjLj2y8SEz8jNboYA58aNksrhUijPgkmYYJEQrG7ZX_kfGGZRHqZ0N0o2KeSjl6KJoLkww">
      </div>
      <span class="fw-semibold" style="font-size:1rem;">Notice Board</span>
    </div>

    <nav class="flex-grow-1 p-3">
      <div class="nav flex-column">
        <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">dashboard</i>
          <span class="ms-3">Dashboard</span>
        </a>
        <a href="add-notices.php" class="nav-link <?php echo ($current_page == 'add-notices.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">add_circle_outline</i>
          <span class="ms-3">Add Notice</span>
        </a>
        <a href="manage-notices.php" class="nav-link <?php echo ($current_page == 'manage-notices.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">manage_search</i>
          <span class="ms-3">Manage Notices</span>
        </a>
        <a href="category.php" class="nav-link <?php echo ($current_page == 'category.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">category</i>
          <span class="ms-3">Categories</span>
        </a>
        <a href="bwdates-reports.php" class="nav-link <?php echo ($current_page == 'bwdates-reports.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">filter_list</i>
          <span class="ms-3">Date Filter</span>
        </a>
        <a href="view_Inquiries.php" class="nav-link <?php echo ($current_page == 'view_Inquiries.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">question_answer</i>
          <span class="ms-3">View Inquiries</span>
        </a>
        <a href="admin-polls.php" class="nav-link <?php echo ($current_page == 'admin-polls.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">poll</i>
          <span class="ms-3">Create Poll/Survey</span>
        </a>
        <a href="admin-events.php" class="nav-link <?php echo ($current_page == 'admin-events.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">event</i>
          <span class="ms-3">Events & Holidays</span>
        </a>
        <!-- <a href="import-events.php" class="nav-link <?php echo ($current_page == 'import-events.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">file_upload</i>
          <span class="ms-3">Import Events (CSV)</span>
        </a> -->
        <a href="events-calendar.php" class="nav-link <?php echo ($current_page == 'events-calendar.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">calendar_month</i>
          <span class="ms-3">Events Calendar</span>
        </a>
      </div>
    </nav>

    <!-- Admin Dropdown -->
    <div class="sidebar-footer p-3 relative">
      <div class="relative group w-full">
        <button onclick="toggleDropdown()" class="w-full bg-transparent text-left flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-700 transition">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWdbj-rVS02zykaY7kWiZ-0iqgsbUMLFVbMPDiouS9roU5FdK9nJMD2Pdp4h3HJQ4l5x7lKhoXzAms5guLYA5lLrku6f0zY1H2Y-g9a9h9xzzRlI0Hb-HlkpJI1TgX3qGVs0BzUNyX9WZ74dpDVIuuQYCjZ295O5MG6KvVQmwRLulmMp1Rp3R39e5MNlj7J9micgI5Ph5dXoZpdGXESnrQZJfbrQkxRUxO9ozHPONO1MifjZ_nW460iOYrgzLLZXgfR_CBgDM"
               class="admin-avatar" alt="Admin" />
          <div class="flex-grow">
            <p class="mb-0 text-sm fw-semibold text-white"><?php echo htmlentities($name); ?></p>
            <p class="mb-0 text-xs text-slate-300"><?php echo htmlentities($email); ?></p>
          </div>
          <i class="material-icons text-slate-300">expand_more</i>
        </button>

        <div id="adminDropdown" class="absolute bottom-14 left-0 w-full bg-white text-gray-800 rounded-xl shadow-xl border border-gray-200 hidden z-50 animate-fade-in">
          <a href="admin-profile.php" class="block px-4 py-3 hover:bg-gray-100 flex items-center gap-2">
            <i class="bi bi-person text-primary"></i> Profile
          </a>
          <a href="change-password.php" class="block px-4 py-3 hover:bg-gray-100 flex items-center gap-2">
            <i class="bi bi-key text-warning"></i> Change Password
          </a>
          <div class="border-t"></div>
          <a href="logout.php" class="block px-4 py-3 hover:bg-red-50 text-red-600 flex items-center gap-2">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </aside>

  <!-- Mobile Navbar -->
  <nav class="mobile-navbar justify-around">
    <a href="dashboard.php" class="mobile-nav-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
      <i class="material-icons mobile-nav-icon">dashboard</i>
      <span>Dashboard</span>
    </a>
    <a href="add-notices.php" class="mobile-nav-item <?php echo ($current_page == 'add-notices.php') ? 'active' : ''; ?>">
      <i class="material-icons mobile-nav-icon">add</i>
      <span>Add</span>
    </a>
    <a href="manage-notices.php" class="mobile-nav-item <?php echo ($current_page == 'manage-notices.php') ? 'active' : ''; ?>">
      <i class="material-icons mobile-nav-icon">search</i>
      <span>Manage</span>
    </a>
    <a href="category.php" class="mobile-nav-item <?php echo ($current_page == 'category.php') ? 'active' : ''; ?>">
      <i class="material-icons mobile-nav-icon">category</i>
      <span>Categories</span>
    </a>
    <a href="view_Inquiries.php" class="mobile-nav-item <?php echo ($current_page == 'view_Inquiries.php') ? 'active' : ''; ?>">
      <i class="material-icons mobile-nav-icon">question_answer</i>
      <span>Inquiries</span>
    </a>
  </nav>

  <script>
    function toggleDropdown() {
      document.getElementById('adminDropdown').classList.toggle('hidden');
    }
    document.addEventListener('click', function (e) {
      const dropdown = document.getElementById('adminDropdown');
      const trigger = document.querySelector('.sidebar-footer');
      if (!trigger.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });
  </script>
</body>
</html>
