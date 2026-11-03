<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);

if (strlen($_SESSION['nbid']) == 0) {
    header('location:logout.php');
    exit;
}   

$msg = "";

if (isset($_POST['submit'])) {
    $adminid = $_SESSION['cvmsaid'];
    $cpassword = md5($_POST['currentpassword']);
    $newpassword = md5($_POST['newpassword']);

    $query = mysqli_query($con, "SELECT ID FROM tbladmin WHERE ID='$adminid' AND Password='$cpassword'");
    if (mysqli_num_rows($query) > 0) {
        mysqli_query($con, "UPDATE tbladmin SET Password='$newpassword' WHERE ID='$adminid'");
        $msg = "Password successfully changed.";
    } else {
        $msg = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .main-content {
        margin-left: 240px; /* for sidebar */
        padding: 40px 20px;
    }

    .form-container {
        background-color: #ffffff;
        max-width: 500px;
        margin: auto;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    h3 {
        color: #2c3e50;
        font-weight: 600;
    }

    label {
        font-weight: 500;
        color: #34495e;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px;
        font-size: 16px;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary {
        border-radius: 12px;
        padding: 12px;
        font-size: 16px;
        font-weight: 500;
        background: linear-gradient(90deg, #007bff, #0056d2);
        border: none;
        transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #0056d2, #003bb1);
    }

    .alert {
        border-radius: 12px;
        font-weight: 500;
    }

    @media screen and (max-width: 768px) {
        .main-content {  
            margin-left: 0;
            padding: 20px;
        }

        .form-container {
            width: 100%;
            padding: 20px;
        }
    }
</style>

</head>
<body>

<?php include_once('includes/admin_sidebar.php'); ?>

<!-- Main content starts here -->
<div class="main-content">

    <?php if ($msg): ?>
        <div class="alert alert-info text-center"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="form-container">
    <h4 class="text-center mb-4">Change Admin Password</h4>

    <form method="POST" id="changePasswordForm">
            <div class="mb-3">
                <label for="currentpassword" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="currentpassword" name="currentpassword" required>
            </div>
            <div class="mb-3">
                <label for="newpassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newpassword" name="newpassword" required>
            </div>
            <div class="mb-3">
                <label for="confirmpassword" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Change Password</button>
        </form>
    </div>
</div>

<!-- JS scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#changePasswordForm').on('submit', function () {
        const newPassword = $('#newpassword').val();
        const confirmPassword = $('#confirmpassword').val();

        if (newPassword !== confirmPassword) {
            alert("New Password and Confirm Password do not match.");
            $('#confirmpassword').focus();
            return false;
        }
        return true;
    });
</script>

</body>
</html>
