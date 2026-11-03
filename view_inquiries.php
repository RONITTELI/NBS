<?php
session_start();
include_once('includes/dbconnection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sidebar user info
$adminid = $_SESSION['nbid'] ?? 0;
$ret = mysqli_query($con, "SELECT AdminName, Email FROM tbladmin WHERE ID='$adminid'");
$row = mysqli_fetch_array($ret);
$name = $row['AdminName'] ?? 'Admin';
$email = $row['Email'] ?? 'admin@example.com';

// Sidebar active page
$current_page = basename($_SERVER['PHP_SELF']);

// Filter dropdowns
$categoryOptions = [];
$catQuery = $con->query("SELECT DISTINCT category FROM tblinquiries WHERE category IS NOT NULL AND category != ''");
while ($catRow = $catQuery->fetch_assoc()) {
    $categoryOptions[] = $catRow['category'];
}

// Filters
$notice_id = isset($_GET['notice_id']) ? intval($_GET['notice_id']) : 0;
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Inquiries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8fafc; }
        .content { margin-left: 250px; padding: 20px; }
        .filter-form { border-radius: 12px; background: white; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        th { background: #1e293b; color: white; }
        @media (max-width: 992px) {
            .content { margin-left: 0; padding-top: 70px; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include_once('includes/admin_sidebar.php'); ?>

<!-- Main Content -->
<div class="content">
    <h3 class="text-primary mb-4">View Inquiries</h3>

    <!-- Filter Form -->
    <form method="get" class="filter-form mb-4 row g-3">
        <div class="col-md-3">
            <label class="form-label">Notice ID</label>
            <input type="number" name="notice_id" class="form-control" value="<?= htmlspecialchars($notice_id); ?>" placeholder="Enter Notice ID">
        </div>
        <div class="col-md-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                <option value="">-- All Categories --</option>
                <?php foreach ($categoryOptions as $cat): ?>
                    <option value="<?= htmlspecialchars($cat); ?>" <?= ($category == $cat) ? 'selected' : ''; ?>><?= htmlspecialchars($cat); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">-- All Statuses --</option>
                <option value="Pending" <?= ($status_filter == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Answered" <?= ($status_filter == 'Answered') ? 'selected' : ''; ?>>Answered</option>
                <option value="Closed" <?= ($status_filter == 'Closed') ? 'selected' : ''; ?>>Closed</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
        </div>
    </form>

    <!-- Inquiries Table -->
    <?php
    $sql = "SELECT * FROM tblinquiries WHERE 1=1";
    $params = [];
    $types = '';

    if ($notice_id > 0) {
        $sql .= " AND notice_id = ?";
        $types .= 'i';
        $params[] = $notice_id;
    }
    if ($category != '') {
        $sql .= " AND category = ?";
        $types .= 's';
        $params[] = $category;
    }
    if ($status_filter != '') {
        $sql .= " AND status = ?";
        $types .= 's';
        $params[] = $status_filter;
    }

    $sql .= " ORDER BY created_at DESC";
    $stmt = $con->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
    ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Notice Title</th>
                    <th>Category</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $noticeTitle = '';
                if (!empty($row['notice_id'])) {
                    $ntRes = $con->query("SELECT title FROM notices WHERE id=" . intval($row['notice_id']));
                    if ($ntRow = $ntRes->fetch_assoc()) {
                        $noticeTitle = $ntRow['title'];
                    }
                }
                ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($noticeTitle); ?></td>
                    <td><?= htmlspecialchars($row['category']); ?></td>
                    <td><?= htmlspecialchars($row['subject']); ?></td>
                    <td>
                        <?php 
                        $status = htmlspecialchars($row['status']);
                        $badge_class = 'bg-secondary';
                        if ($status == 'Answered') $badge_class = 'bg-success';
                        if ($status == 'Closed') $badge_class = 'bg-dark';
                        if ($status == 'Pending') $badge_class = 'bg-warning text-dark';
                        ?>
                        <span class="badge <?= $badge_class ?>"><?= $status ?></span>
                    </td>
                    <td><?= date('d-M-Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <a href="view_inquiry_details.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No inquiries found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>