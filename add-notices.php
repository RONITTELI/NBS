<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

if (strlen($_SESSION['nbid']) == 0) {
    header('location:logout.php');
    exit();
}

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $class = mysqli_real_escape_string($con, $_POST['class']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $content = mysqli_real_escape_string($con, $_POST['content']);
    $created_by = $_SESSION['nbid'];

    // File upload handling
    $file_path = '';
    if (!empty($_FILES["notice_file"]["name"])) {
        $filename = basename($_FILES["notice_file"]["name"]);
        $target_dir = "uploads/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $target_file = $target_dir . $filename;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check file size (max 5MB)
        if ($_FILES["notice_file"]["size"] > 5000000) {
            echo "<script>alert('Sorry, your file is too large. Maximum 5MB allowed.');</script>";
        } else {
            // Allow certain file formats
            $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["notice_file"]["tmp_name"], $target_file)) {
                    $file_path = $target_file;
                } else {
                    echo "<script>alert('Error uploading file.');</script>";
                }
            } else {
                echo "<script>alert('Sorry, only PDF, DOC, DOCX, JPG, JPEG & PNG files are allowed.');</script>";
            }
        }
    }

    // Insert notice
    $query = mysqli_query($con, "INSERT INTO notices (category, class, status, title, content, created_by, file_path, created_at) 
        VALUES('$category', '$class', '$status', '$title', '$content', '$created_by', '$file_path', NOW())");

    if ($query) {
        $notice_id = mysqli_insert_id($con);
        $emailCount = 0;
        $failedEmails = [];
        
        // Get students with matching courses
        $studentQuery = mysqli_query($con, "SELECT email, student_name FROM students WHERE course LIKE '%$category%'");
        
        if (!$studentQuery) {
            echo "<script>alert('Notice published but error fetching students: ".mysqli_error($con)."');</script>";
        } else {
            while ($student = mysqli_fetch_assoc($studentQuery)) {
                $to = $student['email'];
                $subject = "New Notice: $title";
                $message = "
                <html>
                <head>
                    <title>New Notice: $title</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .notice { background: #f9f9f9; padding: 20px; border-left: 4px solid #4e73df; margin: 20px 0; }
                        .footer { color: #777; font-size: 0.9em; border-top: 1px solid #eee; padding-top: 10px; }
                    </style>
                </head>
                <body>
                    <h2>Hello {$student['student_name']},</h2>
                    <p>A new notice has been published for your course ($category):</p>
                    <div class='notice'>
                        <h3>$title</h3>
                        <p>".nl2br($content)."</p>
                        <p><strong>Class:</strong> $class</p>
                        <p><strong>Status:</strong> ".ucfirst($status)."</p>
                        ".(!empty($file_path) ? "<p><a href='".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/$file_path'>Download Attachment</a></p>" : "")."
                    </div>
                    <p>Please check the notice board for more details.</p>
                    <div class='footer'>
                        <p>This is an automated message. Please do not reply.</p>
                    </div>
                </body>
                </html>
                ";
                
                // Email headers
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                $headers .= "From: Notice Board <notices@".$_SERVER['HTTP_HOST'].">\r\n";
                $headers .= "Reply-To: no-reply@".$_SERVER['HTTP_HOST']."\r\n";
                $headers .= "X-Mailer: PHP/".phpversion();
                
                if (mail($to, $subject, $message, $headers)) {
                    $emailCount++;
                } else {
                    $failedEmails[] = $to;
                }
            }
            
            // Initialize views count
            mysqli_query($con, "UPDATE notices SET views = 0 WHERE id = $notice_id");
            
            // Prepare result message
            $resultMsg = "Notice published successfully. ";
            if ($emailCount > 0) {
                $resultMsg .= "Emails sent to $emailCount students. ";
            }
            if (!empty($failedEmails)) {
                $resultMsg .= "Failed to send to ".count($failedEmails)." students. ";
            }
            if ($emailCount == 0 && empty($failedEmails)) {
                $resultMsg .= "No matching students found for notification.";
            }
            
            echo "<script>alert('$resultMsg'); window.location.href='manage-notices.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error saving notice: ".mysqli_error($con)."');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Notice | Notice Board</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #224abe;
        }
        html, body {
            height: 100%;
            margin: 0;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar-col {
            width: 250px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            bottom: 0;
            z-index: 100;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
        }
        .notice-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            max-width: 960px;
            margin: 0 auto;
        }
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .submit-btn:hover {
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
            transform: translateY(-2px);
        }
        .file-upload-container {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            background-color: #f8f9fc;
            cursor: pointer;
            transition: all 0.3s;
        }
        .file-upload-container:hover {
            background-color: rgba(78, 115, 223, 0.05);
            border-color: #4e73df;
        }
        .file-input {
            display: none;
        }
        .upload-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            display: inline-block;
            cursor: pointer;
            margin-top: 1rem;
            transition: background-color 0.3s;
        }
        .upload-btn:hover {
            background-color: var(--primary-dark);
        }
        @media (max-width: 992px) {
            .sidebar-col {
                position: relative;
                width: 100%;
                height: auto;
                box-shadow: none;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
                width: 100%;
            }
            .notice-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="sidebar-col">
        <?php include_once('includes/admin_sidebar.php'); ?>
    </div>
    <div class="main-content">
        <div class="container-fluid">
            <div class="notice-card">
                <h4 class="mb-4 fw-semibold text-dark">Add Notice</h4>
                <form method="post" enctype="multipart/form-data">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="" disabled selected>Select Category</option>
                                <?php
                                $ret = mysqli_query($con, "SELECT categoryName FROM tblcategory ORDER BY categoryName");
                                while ($row = mysqli_fetch_array($ret)) {
                                    echo "<option value='".htmlspecialchars($row['categoryName'], ENT_QUOTES)."'>".htmlspecialchars($row['categoryName'])."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Class</label>
                            <select name="class" class="form-select" required>
                                <option value="" disabled selected>Select Class</option>
                                <option value="FY">FY</option>
                                <option value="SY">SY</option>
                                <option value="TY">TY</option>
                                <option value="11th">11th</option>
                                <option value="12th">12th</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Notice Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter notice title" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Notice Content</label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Write your notice content here..." required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Attach File (optional - max 5MB)</label>
                        <div class="file-upload-container" onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                            <p class="mt-2 mb-1">Upload or drag & drop file here</p>
                            <div id="fileName" class="text-muted small">No file selected</div>
                            <label class="upload-btn mt-2">
                                <i class="bi bi-upload me-1"></i> Select File
                            </label>
                            <input type="file" name="notice_file" id="fileInput" class="file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <button type="submit" name="submit" class="submit-btn w-100 mt-4">
                        <i class="bi bi-save me-2"></i>Publish Notice
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('fileInput').addEventListener('change', function (e) {
        const fileName = e.target.files[0]?.name || "No file selected";
        document.getElementById('fileName').textContent = fileName;
    });
</script>
</body>
</html>