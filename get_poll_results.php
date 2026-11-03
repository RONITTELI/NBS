<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

header('Content-Type: application/json');

if (isset($_GET['poll_id'])) {
    $pollId = intval($_GET['poll_id']);

    // Fetch poll question
    $pollQuery = $con->prepare("SELECT question FROM polls WHERE id = ?");
    $pollQuery->bind_param("i", $pollId);
    $pollQuery->execute();
    $pollResult = $pollQuery->get_result();
    $poll = $pollResult->fetch_assoc();
    $pollQuery->close();

    if ($poll) {
        // Fetch options and their vote counts
        $optionsQuery = $con->prepare("SELECT po.id, po.option_text, COUNT(pv.id) as votes 
                                       FROM poll_options po 
                                       LEFT JOIN poll_votes pv ON po.id = pv.option_id 
                                       WHERE po.poll_id = ? 
                                       GROUP BY po.id 
                                       ORDER BY po.id");
        $optionsQuery->bind_param("i", $pollId);
        $optionsQuery->execute();
        $optionsResult = $optionsQuery->get_result();
        $options = [];
        $totalVotes = 0;
        while ($row = $optionsResult->fetch_assoc()) {
            $options[] = $row;
            $totalVotes += $row['votes'];
        }
        $optionsQuery->close();

        echo json_encode([
            'question' => $poll['question'],
            'options' => $options,
            'total_votes' => $totalVotes
        ]);
    } else {
        echo json_encode(['error' => 'Poll not found.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>