<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include_once(__DIR__ . '/dbconnection.php');

if (!isset($_SESSION['nbid']) || strlen($_SESSION['nbid']) == 0) {
    // No header redirect here, the parent file should handle it
} else {
    $adminid = $_SESSION['nbid'];
    $ret = mysqli_query($con, "SELECT AdminName, Email, role FROM tbladmin WHERE ID='$adminid'");
    $row = mysqli_fetch_array($ret);
    $name = $row['AdminName'] ?? '';
    $email = $row['Email'] ?? '';
    $_SESSION['role'] = $row['role'] ?? '';
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
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
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'principal') { ?>
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
      </div>
    </nav>

    <!-- Admin Dropdown -->
    <div class="sidebar-footer p-3 relative">
      <div class="relative group w-full">
        <button onclick="toggleDropdown()" class="w-full bg-transparent text-left flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-700/50 transition-all duration-300">
          <img src="images/icon/avatar-01.jpg"
               class="admin-avatar" alt="Admin" />
          <div class="flex-grow overflow-hidden">
            <p class="mb-0 text-sm font-semibold text-white truncate"><?php echo isset($name) ? htmlentities($name) : ''; ?></p>
            <p class="mb-0 text-xs text-slate-400 truncate"><?php echo isset($email) ? htmlentities($email) : ''; ?></p>
          </div>
          <i class="material-icons text-slate-400" id="dropdown-icon">expand_more</i>
        </button>

        <div id="adminDropdown" class="absolute bottom-full mb-2 left-0 w-full bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 hidden z-50" style="transform-origin: bottom center;">
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