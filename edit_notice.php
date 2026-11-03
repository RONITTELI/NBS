<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['nbid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch notice
        $sql = "SELECT * FROM notices WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $notice = $result->fetch_assoc();

        // Update notice
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $status = $_POST['status'];
            $category = $_POST['category'];
            $file_path = $notice['file_path']; // default to existing file

            // Handle file upload
            if (isset($_FILES['notice_file']) && $_FILES['notice_file']['error'] == 0) {
                $uploadDir = "uploads/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = basename($_FILES["notice_file"]["name"]);
                $targetFile = $uploadDir . time() . "_" . $fileName;

                if (move_uploaded_file($_FILES["notice_file"]["tmp_name"], $targetFile)) {
                    $file_path = $targetFile;
                }
            }

            $sql = "UPDATE notices SET title = ?, content = ?, status = ?, category = ?, file_path = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('sssssi', $title, $content, $status, $category, $file_path, $id);
            $stmt->execute();

            echo "<script>alert('Notice updated successfully');</script>"; 
            echo "<script>window.location.href = 'manage-notices.php'</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notice Board System | Edit Notice</title>
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body class="animsition">
<div class="page-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="page-container">
        

        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Edit</strong> Notice
                            </div>
                            <div class="card-body card-block">
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    
                                    <!-- Category -->
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Category</label>
                                        <div class="col-md-9">
                                            <select name="category" class="form-control" required>
                                                <option value="<?php echo $notice['category'] ?>"><?php echo $notice['category'] ?></option>
                                                <?php
                                                $ret = mysqli_query($con, "SELECT * FROM tblcategory ORDER BY categoryName");
                                                while ($row = mysqli_fetch_array($ret)) {
                                                ?>
                                                    <option value="<?php echo $row['categoryName']; ?>"><?php echo $row['categoryName']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Notice Title</label>
                                        <div class="col-md-9">
                                            <input type="text" name="title" value="<?php echo $notice['title'] ?>" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Notice Description</label>
                                        <div class="col-md-9">
                                            <textarea name="content" rows="8" class="form-control" required><?php echo $notice['content'] ?></textarea>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Status</label>
                                        <div class="col-md-9">
                                            <select name="status" class="form-control" required>
                                                <option value="active" <?php echo $notice['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                <option value="inactive" <?php echo $notice['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- File Upload -->
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Attach File (optional)</label>
                                        <div class="col-md-9">
                                            <input type="file" name="notice_file" class="form-control-file">
                                            <?php if (!empty($notice['file_path'])): ?>
                                                <small>Current File: <a href="<?php echo $notice['file_path']; ?>" target="_blank">View</a></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="card-footer text-center">
                                        <button type="submit" name="submit" class="btn btn-primary btn-sm">Update Notice</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <?php include_once('includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="vendor/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
</body>
</html>

<?php } ?>
