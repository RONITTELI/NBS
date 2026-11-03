<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('includes/dbconnection.php');

// ---------- Handle inquiry form submission ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquiry_submit'])) {
    $nid        = intval($_POST['notice_id'] ?? 0);
    $fullName   = $con->real_escape_string($_POST['full_name'] ?? '');
    $email      = $con->real_escape_string($_POST['email'] ?? '');
    $mobile     = $con->real_escape_string($_POST['mobile_number'] ?? '');
    $subject    = $con->real_escape_string($_POST['subject'] ?? '');
    $message    = $con->real_escape_string($_POST['message'] ?? '');

    // Fetch category from notices table
    $catRes = $con->query("SELECT category FROM notices WHERE id='$nid' LIMIT 1");
    $category = ($catRes && $catRes->num_rows > 0) ? $catRes->fetch_assoc()['category'] : null;

    $sql = "INSERT INTO tblinquiries 
            (notice_id, category, user_id, full_name, email, mobile_number, subject, message, status, created_at) 
            VALUES 
            ('$nid', '$category', 0, '$fullName', '$email', '$mobile', '$subject', '$message', 'Pending', NOW())";

    if ($con->query($sql)) {
        $successMsg = "✅ Thank you! Your inquiry has been submitted successfully.";
    } else {
        $errorMsg = "❌ Error: " . $con->error;
    }
}

// ---------- Fetch Notice ----------
$nid = intval($_GET['nid'] ?? 0);

// Track views only once per session
if (!isset($_SESSION['visited_notices'])) {
    $_SESSION['visited_notices'] = [];
}
if (!in_array($nid, $_SESSION['visited_notices'])) {
    $con->query("UPDATE notices SET views = views + 1 WHERE id = '$nid'");
    $_SESSION['visited_notices'][] = $nid;
}

$sql = "SELECT * FROM notices WHERE status='active' AND id='$nid'";
$result = $con->query($sql);
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Detail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-color: #333;
            --light-bg: #f9f9f9;
            --border-radius: 8px;
            --box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        * {margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
        body {background-color: #f5f7fa; color: var(--text-color); line-height: 1.6;}
        .container {max-width: 1200px; margin: 0 auto; padding: 20px;}
        .notice-header {background-color: white; padding: 30px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); margin-bottom: 30px;}
        .notice-title {color: var(--primary-color); font-size: 2.2rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee;}
        .notice-meta {display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; color: #666;}
        .meta-item {display: flex; align-items: center; gap: 8px;}
        .meta-item i {color: var(--secondary-color);}
        .notice-main {background-color: white; padding: 30px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);}
        .section-title {color: var(--primary-color); font-size: 1.4rem; margin-bottom: 15px; padding-bottom: 5px; border-bottom: 1px solid #eee;}
        .notice-description, .notice-summary {line-height: 1.8;}
        .notice-actions {display: flex; gap: 15px; margin-top: 30px;}
        .btn {padding: 12px 24px; border-radius: var(--border-radius); border: none; cursor: pointer; font-weight: 600; transition: all 0.3s ease;}
        .btn-primary {background-color: var(--secondary-color); color: white;}
        .btn-primary:hover {background-color: #2980b9;}
        .btn-danger {background-color: var(--accent-color); color: white;}
        .btn-danger:hover {background-color: #c0392b;}
        .inquiry-container {display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;}
        .inquiry-form {background-color: white; padding: 30px; border-radius: var(--border-radius); width: 100%; max-width: 600px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);}
        .form-header {display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee;}
        .form-title {color: var(--primary-color); font-size: 1.4rem;}
        .close-btn {background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #666;}
        .form-group {margin-bottom: 20px;}
        .form-label {display: block; margin-bottom: 8px; font-weight: 600; color: #555;}
        .form-input, .form-textarea {width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--border-radius);}
        .form-textarea {min-height: 120px; resize: vertical;}
        .submit-btn {background-color: var(--secondary-color); color: white; padding: 12px 24px; border: none; border-radius: var(--border-radius); cursor: pointer; font-weight: 600; width: 100%;}
        .btn-download {display: inline-flex; align-items: center; gap: 6px; background-color: var(--secondary-color); color: white; padding: 10px 20px; border-radius: var(--border-radius); font-weight: 600; text-decoration: none; transition: background-color 0.3s ease;}
        .btn-download:hover {background-color: #2980b9; text-decoration: none;}
        .alert-success {background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px;}
        .alert-error {background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 15px;}
    </style>
</head>
<body>
<div class="container">
    <?php if (isset($successMsg)): ?>
        <div class="alert-success"><?= $successMsg ?></div>
    <?php endif; ?>
    <?php if (isset($errorMsg)): ?>
        <div class="alert-error"><?= $errorMsg ?></div>
    <?php endif; ?>

    <?php if ($row): ?>
        <article class="notice-header">
            <h1 class="notice-title"><?= htmlspecialchars($row['title']); ?></h1>
            <div class="notice-meta">
                <div class="meta-item"><i class="bi bi-calendar-event"></i> Created: <?= date('F d, Y', strtotime($row['created_at'])); ?></div>
                <div class="meta-item"><i class="bi bi-tags"></i> Category: <?= htmlspecialchars($row['category']); ?></div>
                <div class="meta-item"><i class="bi bi-eye"></i> Views: <?= (int)$row['views']; ?></div>
            </div>
        </article>

        <main class="notice-main">
            <section class="notice-summary">
                <h2 class="section-title">Summary</h2>
                <p><?= nl2br(htmlspecialchars(substr($row['content'], 0, 200))); ?>...</p>
            </section>

            <section class="notice-description">
                <h2 class="section-title">Full Description</h2>
                <div class="notice-description"><?= nl2br(htmlspecialchars($row['content'])); ?></div>
            </section>

            <?php if (!empty($row['file_path']) && file_exists($row['file_path'])): ?>
                <div style="margin-bottom: 15px;">
                    <a href="<?= htmlspecialchars($row['file_path']); ?>" class="btn-download" download>
                        <i class="bi bi-download"></i> Download File
                    </a>
                </div>
            <?php endif; ?>

            <div class="notice-actions">
                <button class="btn btn-primary" onclick="window.print()">Print Notice</button>
                <button class="btn btn-danger" id="inquiryBtn">Send Inquiry</button>
            </div>
        </main>
    <?php else: ?>
        <div class="alert-error">Notice not found or has been removed.</div>
    <?php endif; ?>
</div>

<!-- Inquiry Modal -->
<div class="inquiry-container" id="inquiryModal">
    <div class="inquiry-form">
        <div class="form-header">
            <h2 class="form-title">Notice Inquiry Form</h2>
            <button class="close-btn" id="closeModal">×</button>
        </div>
        <form id="inquiryForm" method="POST" action="">
            <input type="hidden" name="notice_id" value="<?= $nid; ?>">
            <input type="hidden" name="inquiry_submit" value="1">

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile_number" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Your Message</label>
                <textarea name="message" class="form-textarea" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Submit Inquiry</button>
        </form>
    </div>
</div>

<script>
const inquiryBtn = document.getElementById('inquiryBtn');
const inquiryModal = document.getElementById('inquiryModal');
const closeModal = document.getElementById('closeModal');

inquiryBtn.addEventListener('click', () => {
    inquiryModal.style.display = 'flex'; 
    document.body.style.overflow = 'hidden';
});
closeModal.addEventListener('click', () => {
    inquiryModal.style.display = 'none'; 
    document.body.style.overflow = 'auto';
});
inquiryModal.addEventListener('click', (e) => {
    if (e.target === inquiryModal) {
        inquiryModal.style.display = 'none'; 
        document.body.style.overflow = 'auto';
    }
});
</script>
</body>
</html>
