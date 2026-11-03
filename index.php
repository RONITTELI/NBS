<?php 
session_start();
include('includes/dbconnection.php'); 
?>
<?php
$limit = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Base condition
$conditions = ["status='active'"];

// Category filter
if (!empty($_GET['category'])) {
    $category = $con->real_escape_string($_GET['category']);
    $conditions[] = "category='$category'";
}

// Class filter
if (isset($_GET['class']) && $_GET['class'] !== '') {
    $class = $con->real_escape_string($_GET['class']);
    $conditions[] = "`class`='$class'";
}

// Date filter
if (!empty($_GET['date'])) {
    $date = $con->real_escape_string($_GET['date']);
    $conditions[] = "DATE(created_at) = '$date'";
}

$where = implode(" AND ", $conditions);

// Pagination count
$countSql = "SELECT COUNT(*) as total FROM notices WHERE $where";
$countResult = $con->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch notices
$sql = "SELECT * FROM notices WHERE $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NoticeBoard - Modern Campus Communication</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'slide-up': 'slideUp 0.6s ease-out forwards',
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'bounce-slow': 'bounce 3s infinite',
                        'pulse-slow': 'pulse 4s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(102, 126, 234, 0.5); }
            to { box-shadow: 0 0 30px rgba(102, 126, 234, 0.8); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 120px; height: 120px; top: 20%; right: 10%; animation-delay: 2s; }
        .shape:nth-child(3) { width: 60px; height: 60px; bottom: 20%; left: 20%; animation-delay: 4s; }
        .shape:nth-child(4) { width: 100px; height: 100px; bottom: 10%; right: 20%; animation-delay: 1s; }
        
        .notice-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        
        .notice-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        }
        
        .filter-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }
        
        .nav-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-modern {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .input-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
            background: rgba(255, 255, 255, 1);
        }
        
        .tag-modern {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
            color: white;
            padding: 6px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(10px);
        }
        
        .pagination-modern {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 8px;
        }
        
        .page-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
            font-weight: 600;
        }
        
        .page-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }
        
        .page-btn.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            margin-bottom: 40px;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <?php 
if (isset($_SESSION['student_id']) && $_SESSION['student_id'] != '') {
    // Student is logged in, show student navbar
?>
    <!-- Student Navigation -->
  <?php  include('includes/student_header.php'); ?>
<?php 

} else {
    // Student is not logged in, show public navbar
?>
    <!-- Public Navigation -->
    <nav class="nav-glass sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center animate-glow">
                        <i class="fas fa-bullhorn text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">NoticeBoard</h1>
                        <p class="text-sm text-gray-200 opacity-80">Campus Communication Hub</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="index.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="student_login.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-user-graduate"></i>
                        <span>Student Login</span>
                    </a>
                    <!-- <a href="polls.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-poll"></i>
                        <span>Polls</span>
                    </a>
                    <a href="events-calendar.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a> -->
                    <a href="login.php" class="btn-gradient px-6 py-3 rounded-full text-white font-semibold flex items-center space-x-2">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin Login</span>
                    </a>
                </div>
                
                <button class="md:hidden text-white text-2xl" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-4 py-6 space-y-4">
                <a href="index.php" class="block text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-3 py-2">
                    <i class="fas fa-home w-5"></i>
                    <span>Home</span>
                </a>
                <a href="student_login.php" class="block text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-3 py-2">
                    <i class="fas fa-user-graduate w-5"></i>
                    <span>Student Portal</span>
                </a>
                <a href="polls.php" class="block text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-3 py-2">
                    <i class="fas fa-poll w-5"></i>
                    <span>Polls</span>
                </a>
                <a href="events-calendar.php" class="block text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-3 py-2">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Events</span>
                </a>
                <a href="login.php" class="btn-gradient px-6 py-3 rounded-full text-white font-semibold flex items-center justify-center space-x-2 mt-4">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin Login</span>
                </a>
            </div>
        </div>
    </nav>
<?php }
?>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- <?php if (file_exists('includes/events_widget.php')) include('includes/events_widget.php'); ?> -->

        <!-- Hero Section -->
        <div class="hero-section p-8 mb-12 animate-fade-in">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-black text-white mb-4 animate-slide-up">
                    Latest <span class="gradient-text">Notices</span>
                </h1>
                <p class="text-xl text-gray-200 mb-8 animate-slide-up" style="animation-delay: 0.2s;">
                    Stay connected with the latest campus announcements and updates
                </p>
                
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="stats-card p-6 text-center">
                        <div class="text-3xl font-bold text-white mb-2"><?php echo $totalRows; ?></div>
                        <div class="text-gray-200">Active Notices</div>
                    </div>
                    <div class="stats-card p-6 text-center">
                        <div class="text-3xl font-bold text-white mb-2"><?php echo $totalPages; ?></div>
                        <div class="text-gray-200">Total Pages</div>
                    </div>
                    <div class="stats-card p-6 text-center">
                        <div class="text-3xl font-bold text-white mb-2"><?php echo date('M d'); ?></div>
                        <div class="text-gray-200">Today's Date</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card p-8 mb-12 animate-slide-up" style="animation-delay: 0.4s;">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-3 flex items-center space-x-2">
                        <i class="fas fa-calendar-day text-blue-300"></i>
                        <span>Filter by Date</span>
                    </label>
                    <input type="date" name="date" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>" 
                           class="input-modern w-full px-4 py-3 text-gray-700 font-medium">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-3 flex items-center space-x-2">
                        <i class="fas fa-folder text-blue-300"></i>
                        <span>Category</span>
                    </label>
                    <select name="category" class="input-modern w-full px-4 py-3 text-gray-700 font-medium">
                        <option value="">All Categories</option>
                        <?php
                        $catResult = $con->query("SELECT DISTINCT category FROM notices WHERE status='active'");
                        while ($cat = $catResult->fetch_assoc()) {
                            $selected = (isset($_GET['category']) && $_GET['category'] == $cat['category']) ? 'selected' : '';
                            echo "<option value=\"{$cat['category']}\" $selected>{$cat['category']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label class="block text-white font-semibold mb-3 flex items-center space-x-2">
                        <i class="fas fa-graduation-cap text-blue-300"></i>
                        <span>Class</span>
                    </label>
                    <select name="class" class="input-modern w-full px-4 py-3 text-gray-700 font-medium">
                        <option value="">All Classes</option>
                        <?php
                        $classResult = $con->query("SELECT DISTINCT class FROM notices WHERE status='active'");
                        while ($cls = $classResult->fetch_assoc()) {
                            $selected = (isset($_GET['class']) && $_GET['class'] == $cls['class']) ? 'selected' : '';
                            echo "<option value=\"{$cls['class']}\" $selected>{$cls['class']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn-gradient w-full px-6 py-4 rounded-2xl text-white font-bold flex items-center justify-center space-x-3">
                        <i class="fas fa-search"></i>
                        <span>Apply Filters</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Notices Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <?php if ($result && $result->num_rows > 0): 
                $delay = 0.1;
                while ($row = $result->fetch_assoc()): ?>
            <div class="notice-card card-hover p-8 animate-slide-up" style="animation-delay: <?= $delay ?>s;">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 leading-tight">
                        <?= htmlspecialchars($row['title']); ?>
                    </h2>
                    
                    <div class="flex flex-wrap gap-3 mb-6">
                        <span class="tag-modern">
                            <i class="fas fa-folder text-xs"></i>
                            <?= htmlspecialchars($row['category']); ?>
                        </span>
                        <?php if (!empty($row['Class'])): ?>
                        <span class="tag-modern">
                            <i class="fas fa-graduation-cap text-xs"></i>
                            <?= htmlspecialchars($row['Class']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <p class="text-gray-600 text-base mb-6 leading-relaxed">
                        <?= htmlspecialchars(mb_strimwidth(strip_tags($row['content']), 0, 150, '...')); ?>
                    </p>
                </div>
                
                <div class="border-t border-gray-100 pt-6">
                    <div class="flex items-center text-gray-500 text-sm mb-6">
                        <i class="fas fa-clock mr-3 text-blue-500"></i>
                        <span class="font-medium"><?= date('F j, Y • g:i A', strtotime($row['created_at'])); ?></span>
                    </div>
                    <a href="notice-details.php?nid=<?= $row['id']; ?>" 
                       class="btn-gradient w-full px-6 py-4 rounded-2xl text-white font-bold flex items-center justify-center space-x-3 transition-all duration-300">
                        <i class="fas fa-eye"></i>
                        <span>Read Full Notice</span>
                    </a>
                </div>
            </div>
            <?php 
                $delay += 0.1; 
                endwhile; 
            else:
            ?>
            <div class="col-span-full text-center py-20 animate-fade-in">
                <div class="glass-effect rounded-3xl p-16 max-w-md mx-auto">
                    <i class="fas fa-search text-6xl text-white mb-6 opacity-60"></i>
                    <h3 class="text-3xl font-bold text-white mb-4">No Notices Found</h3>
                    <p class="text-gray-200 text-lg leading-relaxed">
                        No notices match your current filters. Try adjusting your search criteria.
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-center animate-fade-in" style="animation-delay: 0.6s;">
            <div class="pagination-modern flex items-center space-x-2">
                <a href="?page=<?= max(1, $page - 1); ?><?= !empty($_GET['category']) ? '&category=' . urlencode($_GET['category']) : ''; ?><?= !empty($_GET['class']) ? '&class=' . urlencode($_GET['class']) : ''; ?><?= !empty($_GET['date']) ? '&date=' . urlencode($_GET['date']) : ''; ?>" 
                   class="page-btn <?= $page == 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-white hover:bg-opacity-20'; ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
                
                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <a href="?page=<?= $i; ?><?= !empty($_GET['category']) ? '&category=' . urlencode($_GET['category']) : ''; ?><?= !empty($_GET['class']) ? '&class=' . urlencode($_GET['class']) : ''; ?><?= !empty($_GET['date']) ? '&date=' . urlencode($_GET['date']) : ''; ?>" 
                   class="page-btn <?= $page == $i ? 'active' : ''; ?>">
                    <?= $i; ?>
                </a>
                <?php endfor; ?>
                
                <a href="?page=<?= min($totalPages, $page + 1); ?><?= !empty($_GET['category']) ? '&category=' . urlencode($_GET['category']) : ''; ?><?= !empty($_GET['class']) ? '&class=' . urlencode($_GET['class']) : ''; ?><?= !empty($_GET['date']) ? '&date=' . urlencode($_GET['date']) : ''; ?>" 
                   class="page-btn <?= $page == $totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-white hover:bg-opacity-20'; ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->



<footer class="w-full bg-white bg-opacity-70 shadow-inner border-t border-gray-200 mt-16">
    <div class="glass-effect max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row md:justify-between items-center gap-4 rounded-t-2xl">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-400 rounded-xl flex items-center justify-center shadow">
                <i class="fas fa-bullhorn text-white text-lg"></i>
            </div>
            <span class="font-bold text-lg text-gray-900">NoticeBoard</span>
        </div>
        <div class="flex space-x-4 text-sm">
            <a href="index.php" class="hover:text-purple-600 font-medium">Home</a>
            <a href="student_login.php" class="hover:text-purple-600 font-medium">Student Portal</a>
            <a href="polls.php" class="hover:text-purple-600 font-medium">Polls</a>
            <a href="events-calendar.php" class="hover:text-purple-600 font-medium">Events</a>
            <a href="login.php" class="hover:text-purple-600 font-medium">Admin Login</a>
        </div>
        <div class="text-xs text-gray-500 mt-2 md:mt-0">
            © <?php echo date('Y'); ?> NoticeBoard App. All rights reserved.
        </div>
    </div>
</footer>


    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Smooth scroll for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add loading states to form submission
        const filterForm = document.querySelector('form');
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Filtering...';
            submitBtn.disabled = true;
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-slide-up');
                }
            });
        }, observerOptions);

        // Observe cards for animation
        document.querySelectorAll('.notice-card').forEach(card => {
            observer.observe(card);
        });

        // Add parallax effect to floating shapes
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const shapes = document.querySelectorAll('.shape');
            shapes.forEach((shape, index) => {
                const speed = 0.5 + (index * 0.1);
                shape.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    </script>
    
</body>
</html>