<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('includes/dbconnection.php');
error_reporting(0);

// Optional session check (uncomment if only logged-in users should access this)
// if (strlen($_SESSION['nbid']) == 0) {
//     header('location:logout.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notice Board</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .notice-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.2s ease;
            background: #fff;
            border-radius: 0.75rem;
        }
        .notice-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .badge-category {
            background-color: #e9f3ff;
            color: #0d6efd;
            font-size: 0.75rem;
            padding: 0.25em 0.5em;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-primary"><i class="bi bi-megaphone-fill"></i> Notice Board</h3>
        <div>
            <a href="index.php" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-house-door"></i> Home</a>
            <a href="login.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-person-lock"></i> Admin Login</a>
        </div>
    </div>

    <!-- Filter Form -->
    <form class="row mb-4 g-2" method="GET">
        <div class="col-md-4">
            <input type="date" name="date" class="form-control" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>">
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php
                $catResult = $con->query("SELECT DISTINCT category FROM notices WHERE status='active'");
                while ($cat = $catResult->fetch_assoc()) {
                    $selected = (isset($_GET['category']) && $_GET['category'] == $cat['category']) ? 'selected' : '';
                    echo "<option value=\"".htmlspecialchars($cat['category'])."\" $selected>".htmlspecialchars($cat['category'])."</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4 d-flex">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter-circle"></i> Filter</button>
        </div>
    </form>

    <!-- Notices -->
    <div class="row">
        <?php 
        // Filter logic
        $conditions = ["status='active'"];
        if (!empty($_GET['category'])) {
            $category = $con->real_escape_string($_GET['category']);
            $conditions[] = "category='$category'";
        }
        if (!empty($_GET['date'])) {
            $date = $con->real_escape_string($_GET['date']);
            $conditions[] = "DATE(created_at) = '$date'";
        }

        $where = implode(" AND ", $conditions);
        $sql = "SELECT * FROM notices WHERE $where ORDER BY created_at DESC";
        $result = $con->query($sql);

        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex">
            <div class="card notice-card shadow-sm w-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
                        <span class="badge badge-category"><?= htmlspecialchars($row['category']); ?></span>
                        <p class="text-muted small mt-2"><i class="bi bi-calendar-event"></i> <?= date('M d, Y', strtotime($row['created_at'])); ?></p>
                    </div>
                    <a href="notice-details.php?nid=<?= $row['id']; ?>" class="btn btn-primary btn-sm mt-3 align-self-start"><i class="bi bi-eye"></i> View Details</a>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
        <div class="col-12 text-center text-secondary py-5">
            <i class="bi bi-exclamation-circle display-4 mb-3"></i>
            <h5>No notices found for the selected filters.</h5>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
