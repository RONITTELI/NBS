<?php
// poll_widget.php
include_once('includes/dbconnection.php');
// Get latest poll
$poll = $con->query("SELECT * FROM polls ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
if ($poll) {
    $options = $con->query("SELECT * FROM poll_options WHERE poll_id=" . intval($poll['id']));
    // Check if user voted (by session or IP)
    $user_id = isset($_SESSION['student_uid']) ? $_SESSION['student_uid'] : $_SERVER['REMOTE_ADDR'];
    $voted = $con->query("SELECT id FROM poll_votes WHERE poll_id=" . intval($poll['id']) . " AND user_id='" . $con->real_escape_string($user_id) . "'")->num_rows > 0;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poll_vote']) && !$voted) {
        $option_id = intval($_POST['poll_vote']);
        $stmt = $con->prepare("INSERT INTO poll_votes (poll_id, option_id, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $poll['id'], $option_id, $user_id);
        $stmt->execute();
        $stmt->close();
        $voted = true;
    }
    // Get results
    $results = [];
    $total_votes = 0;
    $res = $con->query("SELECT option_id, COUNT(*) as votes FROM poll_votes WHERE poll_id=" . intval($poll['id']) . " GROUP BY option_id");
    while ($row = $res->fetch_assoc()) {
        $results[$row['option_id']] = $row['votes'];
        $total_votes += $row['votes'];
    }
}
?>
<?php if ($poll): ?>
<div class="bg-white rounded-xl shadow-lg p-6 mb-10">
    <h2 class="text-2xl font-bold text-purple-700 mb-4 flex items-center">
        <span class="material-icons text-3xl mr-2">poll</span> Student Poll
    </h2>
    <form method="post">
        <p class="mb-3 font-semibold text-gray-800"><?php echo htmlspecialchars($poll['question']); ?></p>
        <?php foreach ($options as $opt): ?>
            <div class="mb-2 flex items-center">
                <?php if ($voted): ?>
                    <input type="radio" disabled <?php echo (isset($_POST['poll_vote']) && $_POST['poll_vote'] == $opt['id']) ? 'checked' : ''; ?> />
                <?php else: ?>
                    <input type="radio" name="poll_vote" value="<?php echo $opt['id']; ?>" required />
                <?php endif; ?>
                <span class="ms-2 text-gray-700"><?php echo htmlspecialchars($opt['option_text']); ?></span>
                <?php if ($voted): ?>
                    <span class="ms-2 text-xs text-gray-500">
                        <?php $votes = isset($results[$opt['id']]) ? $results[$opt['id']] : 0; ?>
                        <?php echo $total_votes ? round($votes * 100 / $total_votes) : 0; ?>% (<?php echo $votes; ?>)
                    </span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if (!$voted): ?>
            <button type="submit" class="btn-primary mt-3">Vote</button>
        <?php else: ?>
            <div class="mt-3 text-success">Thank you for voting!</div>
        <?php endif; ?>
    </form>
</div>
<?php endif; ?>
