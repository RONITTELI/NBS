<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/dbconnection.php';

// -------------------------
// PHPMailer manual include (no composer, no "use")
// -------------------------
$phpmailer_available = false;
$phpmailer_dir = __DIR__ . '/PHPMailer';
if (file_exists($phpmailer_dir . '/PHPMailer.php') &&
    file_exists($phpmailer_dir . '/SMTP.php') &&
    file_exists($phpmailer_dir . '/Exception.php')) {
    require_once $phpmailer_dir . '/PHPMailer.php';
    require_once $phpmailer_dir . '/SMTP.php';
    require_once $phpmailer_dir . '/Exception.php';
    if (class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
        $phpmailer_available = true;
    }
}

// Load mail config if exists
$mail_config_exists = false;
if (file_exists(__DIR__ . '/includes/mail_config.php')) {
    require_once __DIR__ . '/includes/mail_config.php';
    $mail_config_exists = true;
}

// -------------------------
// Auth check + inquiry id
// -------------------------
if (strlen($_SESSION['nbid'] ?? '') == 0) {
    header('location:logout.php');
    exit();
}

$inquiry_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($inquiry_id <= 0) {
    header('location:view_inquiries.php');
    exit();
}

// Fetch inquiry (basic info for sending)
$stmt_pre = $con->prepare("SELECT email, full_name, subject FROM tblinquiries WHERE id = ?");
$stmt_pre->bind_param("i", $inquiry_id);
$stmt_pre->execute();
$pre_result = $stmt_pre->get_result();
$inquiry_pre_data = $pre_result->fetch_assoc();
$stmt_pre->close();

if (empty($inquiry_pre_data)) {
    header('location:view_inquiries.php?error=notfound');
    exit();
}

// -------------------------
// Update status handler
// -------------------------
if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'] ?? 'Pending';
    $stmt = $con->prepare("UPDATE tblinquiries SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $inquiry_id);
    if ($stmt->execute()) {
        $msg = "Status updated successfully!";
        $msg_class = "alert-success";
    } else {
        $msg = "Error updating status: " . htmlspecialchars($stmt->error);
        $msg_class = "alert-danger";
    }
    $stmt->close();
}

// -------------------------
// Send reply handler
// -------------------------
if (isset($_POST['send_reply'])) {
    $reply_subject = trim($_POST['reply_subject'] ?? '');
    $reply_message = trim($_POST['reply_message'] ?? '');

    $htmlBody = "<html><body style='font-family: Arial, sans-serif; line-height:1.6;'>
        <p>Hello <strong>" . htmlspecialchars($inquiry_pre_data['full_name']) . "</strong>,</p>
        <p>This is a reply to your inquiry: <strong>\"" . htmlspecialchars($inquiry_pre_data['subject']) . "\"</strong></p>
        <div style='padding:10px; background:#f1f1f1; border-left:4px solid #0d6efd; margin:10px 0;'>" .
            nl2br(htmlspecialchars($reply_message)) .
        "</div>
        <p>Regards,<br>Admin Team</p>
        </body></html>";

    $sent = false;
    $error_info = '';

    // Try PHPMailer with SMTP
    if ($phpmailer_available && $mail_config_exists) {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE; // 'tls' or 'ssl'
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(SMTP_USERNAME, 'Notice Board Admin');
            $mail->addAddress($inquiry_pre_data['email'], $inquiry_pre_data['full_name']);
            $mail->addReplyTo(SMTP_USERNAME, 'Notice Board Admin');

            $mail->isHTML(true);
            $mail->Subject = $reply_subject;
            $mail->Body    = $htmlBody;

            $mail->send();
            $sent = true;
            $msg = "Reply sent successfully (SMTP) and status updated to 'Answered'.";
            $msg_class = "alert-success";
        } catch (\Exception $e) {
            $error_info = 'PHPMailer error: ' . $e->getMessage();
            $sent = false;
        }
    }

    // Fallback: PHP mail()
    if (!$sent) {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $from_email = defined('SMTP_USERNAME') ? SMTP_USERNAME : 'no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $headers .= 'From: Notice Board Admin <' . $from_email . ">\r\n";

        if (@mail($inquiry_pre_data['email'], $reply_subject, $htmlBody, $headers)) {
            $sent = true;
            $msg = "Reply sent using PHP mail() and status updated to 'Answered'.";
            $msg_class = "alert-warning";
        } else {
            $msg = "Failed to send email. " . htmlspecialchars($error_info);
            $msg_class = "alert-danger";
        }
    }

    if ($sent) {
        $stmt_update = $con->prepare("UPDATE tblinquiries SET status = 'Answered' WHERE id = ?");
        $stmt_update->bind_param("i", $inquiry_id);
        $stmt_update->execute();
        $stmt_update->close();
    }
}

// -------------------------
// Fetch full inquiry for display
// -------------------------
$stmt = $con->prepare("SELECT i.*, n.title as notice_title 
                       FROM tblinquiries i 
                       LEFT JOIN notices n ON i.notice_id = n.id 
                       WHERE i.id = ?");
$stmt->bind_param("i", $inquiry_id);
$stmt->execute();
$result = $stmt->get_result();
$inquiry = $result->fetch_assoc();
$stmt->close();

if (!$inquiry) {
    echo "Inquiry not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inquiry Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8fafc; }
        .content { margin-left: 250px; padding: 20px; }
        .details-card { border-radius: 12px; background: white; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .detail-item { margin-bottom: 1rem; }
        .detail-label { font-weight: 600; color: #555; }
        .detail-value { color: #222; }
        @media (max-width: 992px) {
            .content { margin-left: 0; padding-top: 70px; }
        }
    </style>
</head>
<body>

<?php include_once('includes/admin_sidebar.php'); ?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary">Inquiry Details</h3>
        <a href="view_inquiries.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <?php if (isset($msg)): ?>
        <div class="alert <?= $msg_class ?>"><?= $msg ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="details-card">
                <h5 class="mb-4 border-bottom pb-2">Inquiry #<?= htmlspecialchars($inquiry['id']) ?></h5>
                <div class="row">
                    <div class="col-md-6 detail-item"><div class="detail-label">Full Name:</div><div class="detail-value"><?= htmlspecialchars($inquiry['full_name']) ?></div></div>
                    <div class="col-md-6 detail-item"><div class="detail-label">Email:</div><div class="detail-value"><a href="mailto:<?= htmlspecialchars($inquiry['email']) ?>"><?= htmlspecialchars($inquiry['email']) ?></a></div></div>
                    <div class="col-md-6 detail-item"><div class="detail-label">Mobile Number:</div><div class="detail-value"><?= htmlspecialchars($inquiry['mobile_number']) ?: 'N/A' ?></div></div>
                    <div class="col-md-6 detail-item"><div class="detail-label">Date Submitted:</div><div class="detail-value"><?= date('d-M-Y h:i A', strtotime($inquiry['created_at'])) ?></div></div>
                    <div class="col-md-6 detail-item"><div class="detail-label">Related Notice:</div>
                        <div class="detail-value">
                            <?php if ($inquiry['notice_id']): ?>
                                <a href="notice-details.php?nid=<?= $inquiry['notice_id'] ?>" target="_blank">
                                    <?= htmlspecialchars($inquiry['notice_title']) ?> (ID: <?= $inquiry['notice_id'] ?>)
                                </a>
                            <?php else: ?>N/A<?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 detail-item"><div class="detail-label">Category:</div><div class="detail-value"><?= htmlspecialchars($inquiry['category']) ?></div></div>
                </div>
                <hr class="my-3">
                <div class="detail-item"><div class="detail-label">Subject:</div><div class="detail-value fs-5 fw-bold"><?= htmlspecialchars($inquiry['subject']) ?></div></div>
                <div class="detail-item"><div class="detail-label">Message:</div><div class="detail-value bg-light p-3 rounded" style="white-space: pre-wrap;"><?= htmlspecialchars($inquiry['message']) ?></div></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="details-card">
                <h5 class="mb-3">Update Status</h5>
                <form method="post">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Pending" <?= ($inquiry['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="Answered" <?= ($inquiry['status'] == 'Answered') ? 'selected' : '' ?>>Answered</option>
                            <option value="Closed" <?= ($inquiry['status'] == 'Closed') ? 'selected' : '' ?>>Closed</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-success w-100"><i class="bi bi-check-circle"></i> Update Status</button>
                </form>
            </div>

            <div class="details-card mt-4">
                <h5 class="mb-3">Reply via Email</h5>
                <form method="post">
                    <div class="mb-3">
                        <label for="reply_subject" class="form-label">Subject</label>
                        <input type="text" name="reply_subject" id="reply_subject" class="form-control" value="Re: <?= htmlspecialchars($inquiry['subject']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="reply_message" class="form-label">Message</label>
                        <textarea name="reply_message" id="reply_message" class="form-control" rows="6" placeholder="Type your reply here..." required></textarea>
                    </div>
                    <button type="submit" name="send_reply" class="btn btn-primary w-100"><i class="bi bi-send"></i> Send Reply</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
