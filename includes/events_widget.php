<?php
// events_widget.php
include_once('includes/dbconnection.php');
$events = $con->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 5");
?>
<div class="bg-white rounded-xl shadow-lg p-6 mb-10">
    <h2 class="text-2xl font-bold text-blue-700 mb-4 flex items-center">
        <span class="material-icons text-3xl mr-2">calendar_month</span> Upcoming Events & Holidays
    </h2>
    <ul class="space-y-4">
        <?php while($e = $events->fetch_assoc()): ?>
        <li class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($e['title']); ?></span>
                <?php if (!empty($e['description'])): ?>
                    <span class="text-gray-500 text-sm ml-2"><?php echo htmlspecialchars($e['description']); ?></span>
                <?php endif; ?>
            </div>
            <div class="flex items-center mt-2 md:mt-0">
                <span class="material-icons text-base text-blue-500 mr-1">event</span>
                <span class="text-blue-600 font-medium"><?php echo date('M d, Y', strtotime($e['event_date'])); ?></span>
                <?php if ($e['notice_id']): ?>
                    <a href="notice-details.php?nid=<?php echo $e['notice_id']; ?>" class="ml-3 text-blue-500 hover:underline text-sm">View Notice</a>
                <?php endif; ?>
            </div>
        </li>
        <?php endwhile; ?>
        <?php if ($events->num_rows == 0): ?>
        <li class="text-gray-500">No upcoming events or holidays.</li>
        <?php endif; ?>
    </ul>
    <div class="mt-4 text-right">
        <a href="events-calendar.php" class="btn-secondary inline-block">View Full Calendar</a>
    </div>
</div>
