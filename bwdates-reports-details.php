<?php
session_start();
include('includes/dbconnection.php');
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>

    <!-- Bootstrap & Icons -->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f1f5f8;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        /* Sidebar Style */
        .menu-sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background-color: #2d3e50;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: 0.3s;
            padding-top: 20px;
        }

        .menu-sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            font-size: 16px;
            transition: 0.2s;
        }

        .menu-sidebar a:hover {
            background-color: #4e73df;
            border-radius: 4px;
        }

        /* Main Content Layout */
        .dashboard-content {
            margin-left: 250px;
            padding-top: 60px;
        }

        .page-container {
            display: flex;
        }

        /* Navbar Style */
        .navbar {
            background-color: #4e73df;
            padding: 15px 30px;
            color: #fff;
            border-bottom: 2px solid #e3e9f3;
        }

        .navbar-brand {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .main-content {
            margin-left: 250px;
            padding-top: 20px;
            width: 100%;
        }

        /* Card & Table Design */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .card-header {
            background-color: #4e73df;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 25px;
        }

        .table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e3e9f3;
        }

        .table th {
            background-color: #f8f9fa;
            color: #333;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #eef2f7;
        }

        .table th, .table td {
            padding: 12px;
            vertical-align: middle;
            text-align: center;
        }

        .badge {
            font-size: 14px;
        }

        .badge-danger {
            background-color: #e74a3b;
        }

        .badge-success {
            background-color: #1cc88a;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .menu-sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .page-container {
                flex-direction: column;
            }

            .navbar {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include_once('includes/admin_sidebar.php'); ?>

<!-- Navbar -->


<!-- Page Container -->
<div class="page-container mt-4">

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4 class="mb-0">Between Dates Reports</h4>
                        </div>
                        <div class="card-body">
                            <?php
                            $fdate = $_POST['fromdate'];
                            $tdate = $_POST['todate'];
                            echo "<h5 class='text-info'>Report from $fdate to $tdate</h5><hr>";
                            ?>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Title</th>
                                            <th>Created By</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody> w   
                                        <?php
                                        $sql = "SELECT * FROM notices WHERE DATE(created_at) BETWEEN '$fdate' AND '$tdate' ORDER BY created_at DESC";
                                        $result = $con->query($sql);
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $statusBadge = $row['status'] == 'inactive'
                                                ? "<span class='badge badge-danger'>Inactive</span>"
                                                : "<span class='badge badge-success'>Active</span>";

                                            echo "<tr>
                                                <td>{$cnt}</td>
                                                <td>{$row['category']}</td>
                                                <td>{$row['title']}</td>
                                                <td>{$row['created_by']}</td>
                                                <td>$statusBadge</td>
                                                <td>
                                                    <a href='edit_notice.php?id={$row['id']}' class='btn btn-sm btn-primary'>Edit</a>
                                                    <a href='delete_notice.php?id={$row['id']}' onclick='return confirm(\"Do you really want to delete?\");' class='btn btn-sm btn-danger'>Delete</a>
                                                </td>
                                            </tr>";
                                            $cnt++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- <?php include_once('includes/footer.php'); ?> -->

</div>

<!-- JS -->
<script src="vendor/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
</body>
</html>
