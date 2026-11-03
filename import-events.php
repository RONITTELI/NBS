<?php
// import-events.php
session_start();
include('includes/dbconnection.php');

$msg = '';
if (isset($_POST['import'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file = $_FILES['file']['tmp_name'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if ($ext == 'csv') {
            $handle = fopen($file, 'r');
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if ($row == 0) { $row++; continue; } // skip header
                $title = $data[0];
                $event_date = $data[1];
                $description = $data[2];
                $notice_id = !empty($data[3]) ? $data[3] : NULL;
                $stmt = $con->prepare("INSERT INTO events (title, event_date, description, notice_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $title, $event_date, $description, $notice_id);
                $stmt->execute();
                $stmt->close();
                $row++;
            }
            fclose($handle);
            $msg = "CSV imported successfully!";
        } else {
            $msg = "Only CSV files are supported.";
        }
    } else {
        $msg = "File upload error.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import Events (CSV)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('includes/sidebar.php'); ?>
<div class="container py-5" style="margin-left:260px;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card p-4">
                <h3 class="mb-3">Import Events from CSV</h3>
                <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">CSV File</label>
                        <input type="file" name="file" class="form-control" accept=".csv" required>
                    </div>
                    <button type="submit" name="import" class="btn btn-primary">Import</button>
                </form>
                <div class="mt-3">
                    <strong>CSV Format:</strong>
                    <pre>title,event_date,description,notice_id (optional)</pre>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
