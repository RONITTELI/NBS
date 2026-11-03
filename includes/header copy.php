<?php
session_start();
include('includes/dbconnection.php');

$adminid = $_SESSION['nbid'];
$ret = mysqli_query($con, "SELECT AdminName FROM tbladmin WHERE ID='$adminid'");
$row = mysqli_fetch_array($ret);
$name = $row['AdminName'];
?>

<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">



<style>
    .navbar-custom {
        background-color: #1f2d3d;
        padding: 0.75rem 1.5rem;
    }

    .navbar-custom .btn {
        border: none;
        padding: 0.45rem 0.85rem;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .btn-profile {
        background-color: #3498db;
        color: white;
    }

    .btn-profile:hover {
        background-color: #2980b9;
    }

    .btn-password {
        background-color: #8e44ad;
        color: white;
    }

    .btn-password:hover {
        background-color: #732d91;
    }

    .btn-logout {
        background-color: #e74c3c;
        color: white;
    }

    .btn-logout:hover {
        background-color: #c0392b;
    }
</style>

<!-- Navigation Header -->
<nav class="navbar navbar-custom d-flex justify-content-end">
    <div class="d-flex gap-2">
        <a href="admin-profile.php" class="btn btn-profile d-flex align-items-center gap-2">
            <i class="bi bi-person-circle"></i> <?php echo htmlentities($name); ?>
        </a>
        <a href="change-password.php" class="btn btn-password d-flex align-items-center gap-2">
            <i class="bi bi-key-fill"></i> Change Password
        </a>
        <a href="logout.php" class="btn btn-logout d-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</nav>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>