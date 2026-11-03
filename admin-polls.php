<?php
// admin-polls.php
session_start();
include('includes/dbconnection.php');

// Handle poll deletion
if (isset($_GET['delete_poll_id'])) {
    $delete_poll_id = intval($_GET['delete_poll_id']);
    // Delete votes first to maintain referential integrity
    $stmt = $con->prepare("DELETE FROM poll_votes WHERE poll_id = ?");
    $stmt->bind_param("i", $delete_poll_id);
    $stmt->execute();
    $stmt->close();

    // Delete options
    $stmt = $con->prepare("DELETE FROM poll_options WHERE poll_id = ?");
    $stmt->bind_param("i", $delete_poll_id);
    $stmt->execute();
    $stmt->close();

    // Delete poll
    $stmt = $con->prepare("DELETE FROM polls WHERE id = ?");
    $stmt->bind_param("i", $delete_poll_id);
    $stmt->execute();
    $stmt->close();

    header('Location: admin-polls.php?msg=' . urlencode("Poll deleted successfully!"));
    exit();
}

// Handle poll creation
if (isset($_POST['create_poll'])) {
    $question = $_POST['question'];
    $notice_id = !empty($_POST['notice_id']) ? $_POST['notice_id'] : NULL;
    $options = array_filter($_POST['options'], function($opt) { return trim($opt) !== ''; });
    if ($question && count($options) >= 2) {
        $stmt = $con->prepare("INSERT INTO polls (question, notice_id) VALUES (?, ?)");
        $stmt->bind_param("si", $question, $notice_id);
        $stmt->execute();
        $poll_id = $stmt->insert_id;
        $stmt->close();
        foreach ($options as $opt) {
            $stmt = $con->prepare("INSERT INTO poll_options (poll_id, option_text) VALUES (?, ?)");
            $stmt->bind_param("is", $poll_id, $opt);
            $stmt->execute();
            $stmt->close();
        }
        $msg = "Poll created successfully!";
    } else {
        $msg = "Please enter a question and at least two options.";
    }
    // Redirect to prevent form resubmission on refresh
    header('Location: admin-polls.php?msg=' . urlencode($msg));
    exit();
}

// Display message if redirected
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

// Fetch all polls
$polls = $con->query("SELECT p.*, n.title AS notice_title FROM polls p LEFT JOIN notices n ON p.notice_id = n.id ORDER BY p.created_at DESC");
// Fetch all notices for linking
$notices = $con->query("SELECT id, title FROM notices ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Polls & Surveys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once('includes/admin_sidebar.php'); ?>
<div class="container py-5" style="margin-left:260px;">
    <style>
         .sidebar a {
            color: #fff;
            text-decoration: none;
        }

         @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }
        }
        
    </style>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 mb-4">
                <h3 class="mb-3">Create Poll / Survey</h3>
                <?php if (isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <input type="text" name="question" class="form-control" required>
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
                    <div class="mb-3">
                        <label class="form-label">Options</label>
                        <div id="options-list">
                            <input type="text" name="options[]" class="form-control mb-2" placeholder="Option 1" required>
                            <input type="text" name="options[]" class="form-control mb-2" placeholder="Option 2" required>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addOption()">Add Option</button>
                    </div>
                    <button type="submit" name="create_poll" class="btn btn-success">Create Poll</button>
                </form>
            </div>
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">All Polls & Surveys</h3>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Created At</th>
                            <th>Linked Notice</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($p = $polls->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlentities($p['question']); ?></td>
                            <td><?php echo htmlentities($p['created_at']); ?></td>
                            <td>
                                <?php if ($p['notice_id'] && $p['notice_title']) {
                                    echo '<a href="notice-details.php?id=' . $p['notice_id'] . '">' . htmlentities($p['notice_title']) . '</a>';
                                } else {
                                    echo '-';
                                } ?>
                            </td>
<td class="d-flex justify-content-center gap-2"> 
    <a href="admin-polls.php?delete_poll_id=<?php echo $p['id']; ?>" 
       class="btn btn-danger btn-sm" 
       onclick="return confirm('Are you sure you want to delete this poll and all its votes?');">
       Delete
    </a>

    <button type="button" 
            class="btn btn-info btn-sm" 
            onclick="viewPollResults(<?php echo $p['id']; ?>)">
            View Results
    </button>
</td>

                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Poll Results Modal -->
<div class="modal fade" id="pollResultsModal" tabindex="-1" aria-labelledby="pollResultsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pollResultsModalLabel">Poll Results</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4 id="pollQuestion" class="mb-3"></h4>
        <div id="pollResults"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function addOption() {
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.className = 'form-control mb-2';
    input.placeholder = 'Option';
    document.getElementById('options-list').appendChild(input);
}

function viewPollResults(pollId) {
    $.ajax({
        url: 'get_poll_results.php', // A new PHP file to fetch results
        type: 'GET',
        data: { poll_id: pollId },
        success: function(data) {
            console.log("Parsed data:", data); // Add this line for debugging
            $('#pollQuestion').text(data.question);
            let resultsHtml = '';
            data.options.forEach(option => {
                const percentage = data.total_votes > 0 ? Math.round((option.votes / data.total_votes) * 100) : 0;
                resultsHtml += `
                    <div class="mb-2">
                        <p class="mb-1">${option.option_text} (${option.votes} votes)</p>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" style="width: ${percentage}%;" aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100">${percentage}%</div>
                        </div>
                    </div>
                `;
            });
            $('#pollResults').html(resultsHtml);
            var modalElement = document.getElementById('pollResultsModal');
            console.log("Modal element:", modalElement); // Check if element is found
            if (modalElement) {
                var pollResultsModal = new bootstrap.Modal(modalElement);
                pollResultsModal.show();
            } else {
                console.error("Modal element not found!");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching poll results:", error);
            alert("Failed to fetch poll results. Server response: " + xhr.responseText);
        }
    });
}
</script>
</body>
</html>
