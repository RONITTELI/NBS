<?php session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['nbid']==0)) {
  header('location:logout.php');
  } else{


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM notices WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
echo "<script>alert('Notice deleted');</script>"; 
echo "<script>window.location.href = 'manage-notices.php'</script>";  
}
}
?>
