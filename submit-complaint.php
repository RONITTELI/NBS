<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $student_id = $_SESSION['student_id'] ?? null;
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;

    if ($is_anonymous) {
        $student_id = null;
    }

    $sql = "INSERT INTO complaints (student_id, subject, description, category, is_anonymous) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isssi", $student_id, $subject, $description, $category, $is_anonymous);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $msg = "Complaint submitted successfully.";
    } else {
        $msg = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit a Complaint</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include('includes/student_header.php'); ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Submit a Complaint</h1>

            <?php if (isset($msg)) { ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $msg; ?></span>
                </div>
            <?php } ?>

            <form method="post">
                <div class="mb-6">
                    <label for="subject" class="block text-gray-700 font-bold mb-2">Subject</label>
                    <input type="text" name="subject" id="subject" class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:border-blue-500" required>
                </div>

                <div class="mb-6">
                    <label for="category" class="block text-gray-700 font-bold mb-2">Category</label>
                    <select name="category" id="category" class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:border-blue-500" required>
                        <option value="">Select a category</option>
                        <option value="Teachers">Teachers</option>
                        <option value="Academic">Academic</option>
                        <option value="Infrastructure">Infrastructure</option>
                        <option value="Faculty">Faculty</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea name="description" id="description" rows="6" class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:border-blue-500" required></textarea>
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_anonymous" value="1" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Submit anonymously</span>
                    </label>
                </div>

                <div>
                    <button type="submit" name="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Submit Complaint
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>