<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('includes/dbconnection.php');
error_reporting(0);

if (strlen($_SESSION['nbid']) == 0) {
    header('location:logout.php');
    exit;
}   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notices</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
       
        .sidebar a {
            color: #fff;
        
            text-decoration: none;
        }
        
        .content {
            margin-left: 240px;
            padding: 30px;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }
        }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
    </style>
</head>
<body>


<?php include_once('includes/admin_sidebar.php'); ?>

<?php include('includes/header.php'); ?>

<!-- Main Content -->
<div class="content">
    <h3 class="mb-4">Manage Notices</h3>
    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Title</th>
                <th>Created By</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM notices ORDER BY created_at DESC";
        $result = $con->query($sql);
        $cnt = 1;
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $cnt++ ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['created_by']) ?></td>
                <td>
                    <span class="badge <?= $row['status'] == 'inactive' ? 'badge-danger' : 'badge-success' ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="edit_notice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="delete_notice.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this notice?');" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>


</body>
</html>
