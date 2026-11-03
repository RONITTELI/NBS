<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $contactno = $_SESSION['contactno'];
    $email = $_SESSION['email'];
    $password = md5($_POST['newpassword']);

    $query = mysqli_query($con, "UPDATE tbladmin SET Password='$password' WHERE Email='$email' AND MobileNumber='$contactno'");
    
    if ($query) {
        echo "<script>alert('Password successfully changed');</script>";
        session_destroy();
    } else {
        echo "<script>alert('Error updating password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <script>
    function checkPasswordMatch() {
      var password = $("#newpassword").val();
      var confirmPassword = $("#confirmpassword").val();
      if (password !== confirmPassword) {
        alert("New Password and Confirm Password do not match!");
        return false;
      }
      return true;
    }
  </script>
</head>
<body>



    <div class="col-md-9 mt-5">
      <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-header text-center">
          <h4>Reset Your Password</h4>
        </div>
        <div class="card-body">
          <form method="post" onsubmit="return checkPasswordMatch();">
            <div class="form-group">
              <label for="newpassword">New Password</label>
              <input type="password" class="form-control" id="newpassword" name="newpassword" required>
            </div>
            <div class="form-group">
              <label for="confirmpassword">Confirm Password</label>
              <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-block">Reset Password</button>
          </form>
        </div>
        <div class="card-footer text-center">
          <a href="index.php">Back to Sign In</a>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
