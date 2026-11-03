<?php
// admin-events.php
session_start();
include('includes/dbconnection.php');

// Handle form submission
if (isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];
    $description = $_POST['description'];
    $notice_id = !empty($_POST['notice_id']) ? $_POST['notice_id'] : NULL;

    $stmt = $con->prepare("INSERT INTO events (title, event_date, description, notice_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $event_date, $description, $notice_id);
    $success = $stmt->execute();
    $stmt->close();
    $msg = $success ? "Event added successfully!" : "Failed to add event.";
}

// Fetch all events
$events = $con->query("SELECT e.*, n.title AS notice_title FROM events e LEFT JOIN notices n ON e.notice_id = n.id ORDER BY event_date DESC");

// Fetch all notices for linking
$notices = $con->query("SELECT id, title FROM notices ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Events & Holidays</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once('includes/admin_sidebar.php'); ?>
<div class="container py-5" style="margin-left:260px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
                        <div class="card p-4 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="mb-0">Add Event / Holiday</h3>
                                    <a href="import-events.php" class="btn btn-primary">Import CSV</a>
                                </div>
                                <?php if (isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>
                                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link to Notice (optional)</label>
                        <select name="notice_id" class="form-select">
                            <option value="">None</option>
                            <?php while($n = $notices->fetch_assoc()): ?>
                                <option value="<?php echo $n['id']; ?>"><?php echo htmlentities($n['title']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="add_event" class="btn btn-success">Add Event</button>
                </form>
            </div>
            <div class="card p-4">
                <h3 class="mb-3">All Events & Holidays</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Linked Notice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($e = $events->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlentities($e['title']); ?></td>
                            <td><?php echo htmlentities($e['event_date']); ?></td>
                            <td><?php echo htmlentities($e['description']); ?></td>
                            <td>
                                <?php if ($e['notice_id'] && $e['notice_title']) {
                                    echo '<a href="notice-details.php?id=' . $e['notice_id'] . '">' . htmlentities($e['notice_title']) . '</a>';
                                } else {
                                    echo '-';
                                } ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
