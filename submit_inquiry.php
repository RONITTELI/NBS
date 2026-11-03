<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('includes/dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Input Sanitization
    $noticeID = isset($_POST['NoticeID']) ? intval($_POST['NoticeID']) : 0;
    $userID   = isset($_POST['UserID']) ? intval($_POST['UserID']) : 0;

    $fullName = trim(filter_input(INPUT_POST, 'FullName', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email    = trim(filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL));
    $mobile   = trim(filter_input(INPUT_POST, 'MobileNumber', FILTER_SANITIZE_STRING));
    $subject  = trim(filter_input(INPUT_POST, 'Subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $message  = trim(filter_input(INPUT_POST, 'Message', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $errors = [];

    // === Validation ===
    if (empty($fullName) || strlen($fullName) < 2) {
        $errors[] = "Full Name must be at least 2 characters.";
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    }

    if (empty($subject) || strlen($subject) < 3) {
        $errors[] = "Subject must be at least 3 characters.";
    }

    if (empty($message) || strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters.";
    }

    // === If Validation Fails ===
    if (!empty($errors)) {
        echo "<div style='padding:15px; background:#f8d7da; color:#721c24; border-radius:5px;'>";
        echo "<h5>Form Errors:</h5><ul>";
        foreach ($errors as $err) {
            echo "<li>" . htmlspecialchars($err) . "</li>";
        }
        echo "</ul><a href='javascript:history.back()'>Go Back</a>";
        echo "</div>";
        exit;
    }

    // === Fetch Notice Category ===
    $category = '';
    $catStmt = $con->prepare("SELECT category FROM notices WHERE id = ?");
    $catStmt->bind_param("i", $noticeID);
    $catStmt->execute();
    $catStmt->bind_result($category);
    $catStmt->fetch();
    $catStmt->close();

    if (empty($category)) {
        die("<div style='padding:15px; background:#f8d7da; color:#721c24; border-radius:5px;'>
                <strong>Error:</strong> Notice not found or invalid.
                <br><a href='javascript:history.back()'>Go Back</a>
            </div>");
    }

    // === Insert into tblinquiries ===
    $stmt = $con->prepare("INSERT INTO tblinquiries (notice_id, category, user_id, full_name, email, mobile_number, subject, message, created_at)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        die("<div style='padding:15px; background:#f8d7da; color:#721c24; border-radius:5px;'>
                <strong>Database Error:</strong> " . $con->error . "
                <br><a href='javascript:history.back()'>Go Back</a>
            </div>");
    }

    $stmt->bind_param("isisssss", $noticeID, $category, $userID, $fullName, $email, $mobile, $subject, $message);

    if ($stmt->execute()) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Inquiry Submitted</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <style>
            body {
                background-color: #f4f8f6;
                font-family: 'Segoe UI', sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }

            .success-card {
                background: #d1e7dd;
                color: #0f5132;
                border: 1px solid #badbcc;
                border-radius: 12px;
                padding: 40px 30px;
                max-width: 500px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                text-align: center;
            }

            .success-icon {
                font-size: 48px;
                margin-bottom: 20px;
            }

            .btn-back {
                background-color: #0f5132;
                border: none;
                color: #fff;
                margin-top: 20px;
            }

            .btn-back:hover {
                background-color: #0b3d26;
            }
        </style>
    </head>
    <body>
        <div class="success-card">
            <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
            <h3>Inquiry Submitted!</h3>
            <p>Thank you, your inquiry has been received. We will get back to you shortly.</p>
            <a href="notice-details.php?nid=<?= $noticeID ?>" class="btn btn-back btn-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Notice
            </a>
        </div>
    </body>
    </html>
    <?php
    exit();
}
else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Inquiry Failed</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <style>
            body {
                background-color: #fff4f4;
                font-family: 'Segoe UI', sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }

            .error-card {
                background: #f8d7da;
                color: #842029;
                border: 1px solid #f5c2c7;
                border-radius: 12px;
                padding: 40px 30px;
                max-width: 500px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                text-align: center;
            }

            .error-icon {
                font-size: 48px;
                margin-bottom: 20px;
            }

            .btn-back {
                background-color: #842029;
                border: none;
                color: #fff;
                margin-top: 20px;
            }

            .btn-back:hover {
                background-color: #6c1a22;
            }
        </style>
    </head>
    <body>
        <div class="error-card">
            <div class="error-icon"><i class="bi bi-x-circle-fill"></i></div>
            <h3>Submission Failed</h3>
            <p>Oops! Something went wrong while submitting your inquiry.</p>
            <p>Please try again or contact support.</p>
            <a href="javascript:history.back()" class="btn btn-back btn-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Go Back
            </a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

    $stmt->close();

} else {
    header("Location: index.php");
    exit;
}
?>
