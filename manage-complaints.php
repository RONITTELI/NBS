<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);

if (strlen($_SESSION['nbid']) == 0) {
    header('location:logout.php');
    exit;
}

// Handle status update
if (isset($_POST['update_status'])) {
    $complaint_id = intval($_POST['complaint_id']);
    $new_status = $_POST['status'];
    $stmt = $con->prepare("UPDATE complaints SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $complaint_id);
    $stmt->execute();
    $stmt->close();
}

// Handle quick "Mark as Resolved"
if (isset($_POST['mark_resolved']) && isset($_POST['complaint_id'])) {
    $complaint_id = intval($_POST['complaint_id']);
    $stmt = $con->prepare("UPDATE complaints SET status='Resolved' WHERE id=?");
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $stmt->close();
}

// Filter logic
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$allowed_statuses = ['New', 'Progress', 'Resolved', 'Rejected'];

if ($status_filter && in_array($status_filter, $allowed_statuses)) {
    $stmt = $con->prepare("SELECT * FROM complaints WHERE status=? ORDER BY id DESC");
    $stmt->bind_param("s", $status_filter);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $con->query("SELECT * FROM complaints ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Complaints</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-content {
            margin-left: 240px;
            padding: 30px;
        }
        @media screen and (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>
<body>

<?php include_once('includes/admin_sidebar.php'); ?>

<div class="main-content">
    <h3 class="mb-4">Manage Complaints</h3>

    <!-- Filter Form -->
    <form method="get" class="form-inline mb-4">
        <label for="status" class="mr-2 font-weight-bold">Filter by Status:</label>
        <select name="status" id="status" class="form-control mr-2" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="New" <?php if($status_filter=='New') echo 'selected'; ?>>New</option>
            <option value="Progress" <?php if($status_filter=='Progress') echo 'selected'; ?>>Progress</option>
            <option value="Resolved" <?php if($status_filter=='Resolved') echo 'selected'; ?>>Resolved</option>
            <option value="Rejected" <?php if($status_filter=='Rejected') echo 'selected'; ?>>Rejected</option>
        </select>
        <?php if($status_filter): ?>
            <a href="manage-complaints.php" class="btn btn-danger btn-sm">Clear Filter</a>
        <?php endif; ?>
    </form>

    <?php if($status_filter): ?>
        <div class="mb-3 text-muted">
            Showing complaints with status: 
            <span class="font-weight-bold text-primary"><?php echo htmlspecialchars($status_filter); ?></span>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Change Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <?php
                                $badgeClass = 'badge-primary';
                                if ($row['status'] == 'Resolved') $badgeClass = 'badge-success';
                                elseif ($row['status'] == 'Progress') $badgeClass = 'badge-warning';
                                elseif ($row['status'] == 'Rejected') $badgeClass = 'badge-danger';
                            ?>
                            <span class="badge <?php echo $badgeClass; ?> status-badge">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-control form-control-sm mr-2">
                                    <option value="New" <?php if($row['status']=='New') echo 'selected'; ?>>New</option>
                                    <option value="Progress" <?php if($row['status']=='Progress') echo 'selected'; ?>>Progress</option>
                                    <option value="Resolved" <?php if($row['status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                                    <option value="Rejected" <?php if($row['status']=='Rejected') echo 'selected'; ?>>Rejected</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary btn-sm mr-2">Update</button>
                                <?php if($row['status'] != 'Resolved'): ?>
                                    <button type="submit" name="mark_resolved" value="1" class="btn btn-success btn-sm">Mark as Resolved</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No complaints found for this status.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
