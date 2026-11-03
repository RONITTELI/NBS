<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);

if (strlen($_SESSION['nbid']) == 0) {
    header('location:logout.php');
    exit;
}

// Stats
$totalCategories = mysqli_num_rows(mysqli_query($con, "SELECT id FROM tblcategory"));
$totalNotices = mysqli_num_rows(mysqli_query($con, "SELECT id FROM notices"));
$inactiveNotices = mysqli_num_rows(mysqli_query($con, "SELECT id FROM notices WHERE status='inactive'"));
$activeNotices = mysqli_num_rows(mysqli_query($con, "SELECT id FROM notices WHERE status='active'"));

// Recent Notices
$recentNotices = mysqli_query($con, "SELECT title, category, created_at, status FROM notices ORDER BY created_at DESC LIMIT 3");

// Chart A: Notices Created Per Day
$noticeDates = [];
$noticeCounts = [];
$result1 = mysqli_query($con, "SELECT DATE(created_at) as date, COUNT(*) as total FROM notices GROUP BY DATE(created_at) ORDER BY date ASC");
while ($row = mysqli_fetch_assoc($result1)) {
    $noticeDates[] = $row['date'];
    $noticeCounts[] = $row['total'];
}

// Chart D: Top 5 Most Used Categories
$topCategories = [];
$topCounts = [];
$result2 = mysqli_query($con, "
    SELECT category, COUNT(*) as total 
    FROM notices 
    GROUP BY category 
    ORDER BY total DESC 
    LIMIT 5
");
while ($row = mysqli_fetch_assoc($result2)) {
    $topCategories[] = $row['category'];
    $topCounts[] = $row['total'];
}

// Chart B: Top Notices by Views
$topNotices = [];
$topViews = [];
$result3 = mysqli_query($con, "
    SELECT title, views 
    FROM notices 
    ORDER BY views DESC 
    LIMIT 5
");
while ($row = mysqli_fetch_assoc($result3)) {
    $topNotices[] = $row['title'];
    $topViews[] = $row['views'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notice Board Dashboard</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen overflow-x-hidden">

<?php include_once('includes/admin_sidebar.php'); ?>

<main class="pl-64 p-6 overflow-y-auto">
  <h2 class="text-xl font-semibold text-gray-800 mb-6">Dashboard Overview</h2>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
      <div>
        <p class="text-xs text-gray-500">Total Notices</p>
        <p class="text-xl font-bold text-gray-800"><?php echo $totalNotices; ?></p>
      </div>
      <span class="material-icons text-blue-500 text-2xl">campaign</span>
    </div>
    <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
      <div>
        <p class="text-xs text-gray-500">Active</p>
        <p class="text-xl font-bold text-gray-800"><?php echo $activeNotices; ?></p>
      </div>
      <span class="material-icons text-green-500 text-2xl">check_circle</span>
    </div>
    <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
      <div>
        <p class="text-xs text-gray-500">Inactive</p>
        <p class="text-xl font-bold text-gray-800"><?php echo $inactiveNotices; ?></p>
      </div>
      <span class="material-icons text-yellow-500 text-2xl">hourglass_empty</span>
    </div>
    <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
      <div>
        <p class="text-xs text-gray-500">Categories</p>
        <p class="text-xl font-bold text-gray-800"><?php echo $totalCategories; ?></p>
      </div>
      <span class="material-icons text-purple-500 text-2xl">widgets</span>
    </div>
  </div>

  <!-- Recent Notices -->
  <div class="bg-white p-6 rounded-xl shadow mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Notices</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-left">
        <thead>
          <tr class="text-gray-500 border-b">
            <th class="py-2 px-4">Title</th>
            <th class="py-2 px-4">Category</th>
            <th class="py-2 px-4">Date</th>
            <th class="py-2 px-4">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($recentNotices)) { ?>
            <tr class="border-b">
              <td class="py-3 px-4 font-medium text-gray-800"><?php echo htmlspecialchars($row['title']); ?></td>
              <td class="py-3 px-4 text-gray-600"><?php echo htmlspecialchars($row['category']); ?></td>
              <td class="py-3 px-4 text-gray-600"><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))); ?></td>
              <td class="py-3 px-4">
                <?php if($row['status'] === 'active') { ?>
                  <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Active</span>
                <?php } elseif($row['status'] === 'inactive') { ?>
                  <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">Inactive</span>
                <?php } else { ?>
                  <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded-full"><?php echo ucfirst($row['status']); ?></span>
                <?php } ?>

              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Charts -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl shadow">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Notices Created Per Day</h3>
      <canvas id="noticesPerDayChart" class="w-full h-[300px]"></canvas>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 Notice Categories</h3>
      <canvas id="topCategoriesChart" class="w-full h-[300px]"></canvas>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
  <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 Most Viewed Notices</h3>
  <canvas id="topViewsChart" class="w-full h-[300px]"></canvas>
</div>

  </div>

  


  <?php include_once('includes/footer.php'); ?>
</main>

<script>
  // Chart A: Notices Per Day
  new Chart(document.getElementById('noticesPerDayChart').getContext('2d'), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($noticeDates); ?>,
      datasets: [{
        label: 'Notices',
        data: <?php echo json_encode($noticeCounts); ?>,
        backgroundColor: 'rgba(59, 130, 246, 0.7)',
        borderRadius: 5
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Chart D: Top 5 Categories
  new Chart(document.getElementById('topCategoriesChart').getContext('2d'), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($topCategories); ?>,
      datasets: [{
        label: 'No. of Notices',
        data: <?php echo json_encode($topCounts); ?>,
        backgroundColor: 'rgba(99, 102, 241, 0.7)',
        borderRadius: 5
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      scales: {
        x: { beginAtZero: true }
      }
    }
  });

  // Chart B: Top Viewed Notices
new Chart(document.getElementById('topViewsChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($topNotices); ?>,
    datasets: [{
      label: 'Views',
      data: <?php echo json_encode($topViews); ?>,
      backgroundColor: 'rgba(16, 185, 129, 0.7)', // teal color
      borderRadius: 5
    }]
  },
  options: {
    indexAxis: 'y',
    responsive: true,
    scales: {
      x: { beginAtZero: true }
    }
  }
});


</script>
</body>
</html>
