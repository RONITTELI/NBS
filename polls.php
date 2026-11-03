<?php 
include_once('includes/student_header.php'); 
$polls = $con->query("SELECT * FROM polls ORDER BY created_at DESC");
?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-16 animate-slide-up">
            <div class="text-left">
                <h1 class="text-5xl font-bold text-white mb-2 drop-shadow-lg">
                    Student Polls & Surveys
                </h1>
                <p class="text-xl text-white opacity-90 max-w-3xl leading-relaxed drop-shadow">
                    Share your voice and help shape our academic community.
                </p>
            </div>
            <!-- <a href="polls.php?view_results=true" class="btn-gradient px-6 py-3 rounded-full text-white font-semibold flex items-center space-x-2"> -->
                <!-- <i class="fas fa-chart-bar"></i> -->
                <!-- <span>View Results</span> -->
            <!-- </a> -->
        </div>

        <!-- Polls Section -->
        <div class="max-w-4xl mx-auto space-y-8">
            <?php if ($polls->num_rows > 0): ?>
                <?php $poll_index = 0; ?>
                <?php while($poll = $polls->fetch_assoc()): ?>
                    <?php
                    $options = $con->query("SELECT * FROM poll_options WHERE poll_id=" . intval($poll['id']));
                    $view_results = isset($_GET['view_results']) && $_GET['view_results'] == 'true';
                    $user_id = isset($_SESSION['student_uid']) ? $_SESSION['student_uid'] : $_SERVER['REMOTE_ADDR'];
                    $has_voted = $con->query("SELECT id FROM poll_votes WHERE poll_id=" . intval($poll['id']) . " AND user_id='" . $con->real_escape_string($user_id) . "'")->num_rows > 0;
                    $voted = $has_voted || $view_results;
                    
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poll_vote_' . $poll['id']]) && !$voted) {
                        $option_id = intval($_POST['poll_vote_' . $poll['id']]);
                        $stmt = $con->prepare("INSERT INTO poll_votes (poll_id, option_id, user_id) VALUES (?, ?, ?)");
                        $stmt->bind_param("iis", $poll['id'], $option_id, $user_id);
                        $stmt->execute();
                        $stmt->close();
                        $voted = true;
                    }
                    
                    $results = [];
                    $total_votes = 0;
                    $res = $con->query("SELECT option_id, COUNT(*) as votes FROM poll_votes WHERE poll_id=" . intval($poll['id']) . " GROUP BY option_id");
                    while ($row = $res->fetch_assoc()) {
                        $results[$row['option_id']] = $row['votes'];
                        $total_votes += $row['votes'];
                    }
                    $poll_index++;
                    ?>
                    
                    <div class="poll-card animate-fade-in" style="animation-delay: <?php echo $poll_index * 0.1; ?>s">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-white flex items-center">
                                    <span class="material-icons text-3xl mr-3">poll</span>
                                    <?php echo htmlspecialchars($poll['question']); ?>
                                </h3>
                                <div class="text-white opacity-75 text-sm">
                                    <span class="material-icons text-sm mr-1">people</span>
                                    <?php echo $total_votes; ?> votes
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <form method="post" class="space-y-4">
                                <?php while($opt = $options->fetch_assoc()): ?>
                                    <?php 
                                    $votes = isset($results[$opt['id']]) ? $results[$opt['id']] : 0;
                                    $percentage = $total_votes ? round($votes * 100 / $total_votes) : 0;
                                    ?>
                                    
                                    <div class="poll-option <?php echo $voted ? 'voted' : ''; ?>">
                                        <div class="flex items-center flex-1">
                                            <?php if ($has_voted): ?>
                                                <input type="radio" disabled class="w-5 h-5 text-blue-600 mr-4" 
                                                       <?php echo ($student_voted_option_id == $opt['id']) ? 'checked' : ''; ?> />
                                            <?php else: ?>
                                                <input type="radio" name="poll_vote_<?php echo $poll['id']; ?>" 
                                                       value="<?php echo $opt['id']; ?>" required 
                                                       class="w-5 h-5 text-blue-600 mr-4 focus:ring-blue-500 focus:ring-2" />
                                            <?php endif; ?>
                                            
                                            <div class="flex-1">
                                                <span class="text-lg font-semibold text-gray-800">
                                                    <?php echo htmlspecialchars($opt['option_text']); ?>
                                                </span>
                                                
                                                <?php if ($voted): ?>
                                                    <!-- Results are hidden for students -->
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                
                                <div class="pt-6 border-t border-gray-100">
                                    <?php if (!$has_voted): ?>
                                        <button type="submit" class="vote-button">
                                            submit 
                                        </button>
                                    <?php else: ?>
                                        <div class="thank-you-message">
                                            <!-- <span class="material-icons text-2xl mr-3">check_circle</span> -->
                                            Thank you for participating! Your voice has been heard.
                                        </div>
                                        <!-- <button type="button" class="btn-gradient px-6 py-3 rounded-full text-white font-semibold flex items-center space-x-2 view-results-btn" data-poll-id="<?php echo $poll['id']; ?>">
                                            <i class="fas fa-chart-bar"></i>
                                            <span>View Results</span>
                                        </button> -->
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="glass-effect rounded-2xl p-12 text-center shadow-2xl animate-fade-in">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-6">
                        <span class="material-icons text-blue-600 text-3xl">poll</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">No Polls Available</h3>
                    <p class="text-gray-600 text-lg">Check back later for new polls and surveys! We're always looking for student feedback.</p>
                </div>
            <?php endif; ?>
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

    <!-- Mobile Menu Toggle Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
        
        // Add smooth scrolling and enhanced interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Animate poll cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.poll-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
            
            // Enhanced radio button interactions
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const pollOptions = this.closest('form').querySelectorAll('.poll-option');
                    pollOptions.forEach(option => {
                        option.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                    });
                    this.closest('.poll-option').classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
                });
            });

            // Handle view results button click
            document.querySelectorAll('.view-results-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const pollId = this.dataset.pollId;
                    viewPollResults(pollId);
                });
            });
        });

        function viewPollResults(pollId) {
            $.ajax({
                url: 'get_poll_results.php',
                type: 'GET',
                data: { poll_id: pollId },
                success: function(data) {
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
                    if (modalElement) {
                        var pollResultsModal = new bootstrap.Modal(modalElement);
                        pollResultsModal.show();
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