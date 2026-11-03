<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['nbid']) == 0) {
    header('location:logout.php');
    exit();
}

if (isset($_POST['submit'])) {
    $adminid = $_SESSION['nbid'];
    $AName = $_POST['adminname'];
    $mobno = $_POST['mobilenumber'];
    $email = $_POST['email'];

    $query = mysqli_query($con, "UPDATE tbladmin SET AdminName='$AName', MobileNumber='$mobno', Email='$email' WHERE ID='$adminid'");
    $msg = $query ? "Admin profile has been updated." : "Something went wrong. Please try again.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Edit Admin Profile</title>
    <!-- <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet"> -->
     <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

   
       
</head>
<body>

<style>
    body, html {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        height: 100%;
        background: linear-gradient(135deg, #e3f2fd, #f1f1f1);
    }

     .sidebar a {
            color: #fff;
        
            text-decoration: none;
        }
        
    .background {
        display: flex;
        min-height: 100vh;
    }

    .main-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .main-content {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-left: 240px;
        padding: 40px 20px;
    }

    .form-box {
        background: #ffffff;
        padding: 35px 30px;
        border-radius: 16px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.07);
        width: 100%;
        max-width: 500px;
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-box h4 {
        font-weight: 600;
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    .form-group label {
        font-weight: 500;
        color: #444;
        margin-bottom: 6px;
    }

    input.form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
        transition: all 0.2s;
    }

    input.form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.15rem rgba(0,123,255,.25);
    }

    input[readonly] {
        background-color: #f9f9f9;
        cursor: not-allowed;
    }

    .btn-custom {
        background-color: #006aff;
        color: white;
        font-weight: 500;
        border-radius: 8px;
        padding: 10px;
        width: 100%;
        margin-top: 10px;
        transition: background 0.3s;
    }

    .btn-custom:hover {
        background-color: #0056d2;
    }

    .alert-info {
        border-radius: 8px;
        font-size: 15px;
        padding: 10px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        .form-box {
            margin: 0 10px;
        }
    }



    
    
</style>


<div class="background">
    <?php include_once('includes/admin_sidebar.php'); ?>

    <div class="main-wrapper">
        <?php include_once('includes/header.php'); ?>

        <div class="main-content">
            <div class="form-box">
                <h4>Edit Admin Profile</h4>

                <?php if (isset($msg)) { ?>
                    <div class="alert alert-info text-center"><?php echo $msg; ?></div>
                <?php } ?>

                <?php
                $adminid = $_SESSION['nbid'];
                $ret = mysqli_query($con, "SELECT * FROM tbladmin WHERE ID='$adminid'");
                $row = mysqli_fetch_array($ret);
                ?>

                <form method="post">
                    <div class="form-group">
                        <label>Admin Name</label>
                        <input type="text" name="adminname" class="form-control" value="<?php echo htmlentities($row['AdminName']); ?>" required pattern="[A-Za-z\s]{3,50}">
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlentities($row['Email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" name="mobilenumber" class="form-control" value="<?php echo htmlentities($row['MobileNumber']); ?>" required pattern="[6-9]{1}[0-9]{9}" maxlength="10">
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" value="<?php echo htmlentities($row['UserName']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Registration Date</label>
                        <input type="text" class="form-control" value="<?php echo htmlentities($row['AdminRegdate']); ?>" readonly>
                    </div>

                    <button type="submit" name="submit" class="btn btn-custom">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
