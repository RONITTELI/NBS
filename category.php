<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);

if (strlen($_SESSION['nbid']) == 0) {
    header('location:logout.php');
    exit;
}   

// Add Category
if (isset($_POST['submit'])) {
    $catname = $_POST['catname'];
    $stmt = $con->prepare("INSERT INTO tblcategory (categoryName) VALUES (?)");
    $stmt->bind_param("s", $catname);

    if ($stmt->execute()) {
        echo "<script>alert('Category added'); window.location.href='category.php';</script>";
    } else {
        echo "<script>alert('Something went wrong'); window.location.href='category.php';</script>";
    }
    $stmt->close();
}

// Delete Category
if (isset($_GET['catid'])) {
    $catid = intval($_GET['catid']);
    $stmt = $con->prepare("DELETE FROM tblcategory WHERE id = ?");
    $stmt->bind_param("i", $catid);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Category deleted'); window.location.href='category.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-content {
            margin-left: 240px;
            padding: 30px;
        }

        .form-container {
            max-width: 500px;
            margin-bottom: 40px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        @media screen and (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<?php include_once('includes/admin_sidebar.php'); ?>

<div class="main-content">
    <div class="form-container">
        <h4 class="mb-4">Add New Category</h4>
        <form method="post">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="catname" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Add</button>
        </form>
    </div>

    <h4 class="mb-3">All Categories</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>S.No</th>
                    <th>Category Name</th>
                    <th>Creation Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ret = mysqli_query($con, "SELECT * FROM tblcategory");
                $cnt = 1;
                if (mysqli_num_rows($ret) > 0) {
                    while ($row = mysqli_fetch_assoc($ret)) {
                        echo "<tr>
                            <td>{$cnt}</td>
                            <td>{$row['categoryName']}</td>
                            <td>{$row['creationDate']}</td>
                            <td>
                                <a href='category.php?catid={$row['id']}' 
                                   onclick=\"return confirm('Are you sure?');\" 
                                   class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                        </tr>";
                        $cnt++;
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
