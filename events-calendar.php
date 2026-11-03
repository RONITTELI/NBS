<?php 
include_once('includes/student_header.php'); 
$events = $con->query("SELECT e.*, n.title AS notice_title FROM events e LEFT JOIN notices n ON e.notice_id = n.id");
$eventData = [];
while($e = $events->fetch_assoc()) {
    $eventData[] = [
        'title' => $e['title'],
        'start' => $e['event_date'],
        'description' => $e['description'],
        'notice_id' => $e['notice_id'],
        'notice_title' => $e['notice_title']
    ];
}
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Events & Holidays Calendar</h2>
            <div id="calendar"></div>
        </div>
    </div>
</div>

<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
        <div class="p-6 border-b border-gray-200">
            <h3 id="eventModalTitle" class="text-2xl font-bold text-gray-800"></h3>
        </div>
        <div class="p-6">
            <p id="eventModalDesc" class="text-gray-600 mb-4"></p>
            <a id="eventModalLink" href="#" class="btn-gradient px-6 py-3 rounded-full text-white font-semibold hidden">View Notice</a>
        </div>
        <div class="p-6 bg-gray-50 rounded-b-2xl text-right">
            <button id="closeModal" class="text-gray-500 hover:text-gray-800">Close</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?php echo json_encode($eventData); ?>,
            eventClick: function(info) {
                var event = info.event.extendedProps;
                document.getElementById('eventModalTitle').innerText = info.event.title;
                document.getElementById('eventModalDesc').innerText = event.description || '';
                
                var link = document.getElementById('eventModalLink');
                if (event.notice_id) {
                    link.href = 'notice-details.php?nid=' + event.notice_id;
                    link.classList.remove('hidden');
                } else {
                    link.classList.add('hidden');
                }
                
                document.getElementById('eventModal').classList.remove('hidden');
            }
        });
        calendar.render();

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('eventModal').classList.add('hidden');
        });
    });
</script>
</body>
</html>
