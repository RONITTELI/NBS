<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include(__DIR__ . '/dbconnection.php');

if (!isset($_SESSION['nbid']) || strlen($_SESSION['nbid']) == 0) {
    header('location:login.php'); // Redirect to admin login page
    exit();
}

$adminid = $_SESSION['nbid'];
$ret = mysqli_query($con, "SELECT AdminName, Email, role FROM tbladmin WHERE ID='$adminid'");
$row = mysqli_fetch_array($ret);
$name = $row['AdminName'] ?? ''; // Use null coalescing operator to prevent errors if $row is null
$email = $row['Email'] ?? '';   // Use null coalescing operator to prevent errors if $row is null
$_SESSION['role'] = $row['role'] ?? '';

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Notice Board</title>

  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Google Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Tailwind (CDN) -->
<script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap & Tailwind -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   
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
      font-size: 0.85rem !important;
      transition: all 0.3s ease;
    }
    .nav-link:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #ffffff;
    }
    .nav-link.active {
      background: #4f46e5;
      color: white;
      /* box-shadow: 0 2px 6px rgba(79, 70, 229, 0.5); */
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
    #adminDropdown {
      transition: opacity 0.2s ease-out, transform 0.2s ease-out;
      opacity: 0;
      transform: translateY(10px) scale(0.95);
    }
    #dropdown-icon {
        transition: transform 0.3s;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-center p-4">
      <div class="sidebar-logo me-2">
        <img alt="Logo" width="28" height="28" class="img-fluid"
             src="images/jai_hind.jpeg" />
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
        <?php if ($_SESSION['role'] == 'principal') { ?>
        <a href="manage-complaints.php" class="nav-link <?php echo ($current_page == 'manage-complaints.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">report_problem</i>
          <span class="ms-3">Manage Complaints</span>
        </a>
        <?php } ?>
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
        <!-- <a href="events-calendar.php" class="nav-link <?php echo ($current_page == 'events-calendar.php') ? 'active' : ''; ?>">
          <i class="material-icons nav-icon">calendar_month</i>
          <span class="ms-3">Events Calendar</span>
        </a> -->
      </div>
    </nav>

    <!-- Admin Dropdown -->
    <div class="sidebar-footer p-3 relative">
      <div class="relative group w-full">
        <button onclick="toggleDropdown()" class="w-full bg-transparent text-left flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-700/50 transition-all duration-300">
          <img src="images/icon/avatar-01.jpg"
               class="admin-avatar" alt="Admin" />
          <div class="flex-grow overflow-hidden">
            <p class="mb-0 text-sm font-semibold text-white truncate"><?php echo htmlentities($name); ?></p>
            <p class="mb-0 text-xs text-slate-400 truncate"><?php echo htmlentities($email); ?></p>
          </div>
          <i class="material-icons text-slate-400" id="dropdown-icon">expand_more</i>
        </button>

        <div id="adminDropdown" class="absolute bottom-full mb-2 left-0 w-full dark:bg-slate-800 text-gray-800 dark:text-slate-200 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 hidden z-50" style="transform-origin: bottom center;">
          <div class="p-2">
            <a href="admin-profile.php" class="block px-3 py-2.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center gap-3">
              <i class="bi bi-person-circle text-primary"></i> Profile
            </a>
            <a href="change-password.php" class="block px-3 py-2.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center gap-3">
              <i class="bi bi-shield-lock text-yellow-500"></i> Change Password
            </a>
            <div class="border-t my-2 dark:border-slate-700"></div>
            <a href="logout.php" class="block px-3 py-2.5 text-sm rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center gap-3">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </div>
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

  <!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById('adminDropdown');
      const icon = document.getElementById('dropdown-icon');
      
      if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        requestAnimationFrame(() => {
          dropdown.style.opacity = '1';
          dropdown.style.transform = 'translateY(0) scale(1)';
        });
        icon.style.transform = 'rotate(180deg)';
      } else {
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(10px) scale(0.95)';
        icon.style.transform = 'rotate(0deg)';
        setTimeout(() => {
          dropdown.classList.add('hidden');
        }, 200);
      }
    }

    document.addEventListener('click', function (e) {
      const dropdown = document.getElementById('adminDropdown');
      const trigger = document.querySelector('.sidebar-footer');
      if (trigger && !trigger.contains(e.target) && dropdown && !dropdown.classList.contains('hidden')) {
        const isButtonClicked = trigger.contains(e.target) && e.target.tagName === 'BUTTON';
        if(!isButtonClicked) {
            toggleDropdown();
        }
      }
    }, true);
  </script>
</body>
</html>