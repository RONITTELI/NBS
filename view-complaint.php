<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('includes/dbconnection.php');

// Check if the user is a principal
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    // Redirect to the login page or an error page
    header('location: login.php');
    exit();
}

// Get the complaint ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to the manage complaints page if the ID is not valid
    header('location: manage-complaints.php');
    exit();
}
$complaint_id = $_GET['id'];

// Fetch the complaint details
$sql = "SELECT c.*, s.student_name, s.email FROM complaints c LEFT JOIN students s ON c.student_id = s.id WHERE c.id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $complaint_id);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Complaint</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include('includes/admin_sidebar.php'); ?>
    <main class="pl-64 p-6 overflow-y-auto">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Complaint Details</h1>

                <?php if ($complaint) {
                    ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-700 mb-2">Subject</h2>
                            <p><?php echo $complaint['subject']; ?></p>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-700 mb-2">Category</h2>
                            <p><?php echo $complaint['category']; ?></p>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-700 mb-2">Status</h2>
                            <p><?php echo $complaint['status']; ?></p>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-700 mb-2">Date</h2>
                            <p><?php echo $complaint['created_at']; ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <h2 class="text-lg font-bold text-gray-700 mb-2">Description</h2>
                            <p><?php echo nl2br($complaint['description']); ?></p>
                        </div>
                        <?php if (!$complaint['is_anonymous']) {
                            ?>
                            <div>
                                <h2 class="text-lg font-bold text-gray-700 mb-2">Submitted by</h2>
                                <p><?php echo $complaint['student_name']; ?> (<?php echo $complaint['email']; ?>)</p>
                            </div>
                        <?php } else {
                            ?>
                            <div>
                                <h2 class="text-lg font-bold text-gray-700 mb-2">Submitted by</h2>
                                <p>Anonymous</p>
                            </div>
                        <?php } ?>
                    </div>

                    
                <?php } else {
                    ?>
                    <p>Complaint not found.</p>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php include('includes/footer.php'); ?>
</body>
</html>