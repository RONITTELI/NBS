<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);
include(__DIR__ . '/dbconnection.php');
if (strlen($_SESSION['student_id']) == 0) {
    header('location:student_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - NoticeBoard</title>
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
        .nav-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
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
    </style>
</head>
<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="nav-glass sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bullhorn text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">NoticeBoard</h1>
                        <p class="text-sm text-gray-200 opacity-80">Student Dashboard</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="index.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="polls.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-poll"></i>
                        <span>Polls</span>
                    </a>
                    <a href="events-calendar.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                    <a href="profile.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="submit-complaint.php" class="text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-2">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Complaints</span>
                    </a>
                    <a href="logout.php" class="btn-gradient px-6 py-3 rounded-full text-white font-semibold flex items-center space-x-2">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
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
                <a href="polls.php" class="block text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-3 py-2">
                    <i class="fas fa-poll w-5"></i>
                    <span>Polls</span>
                </a>
                <a href="events-calendar.php" class="block text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-3 py-2">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Events</span>
                </a>
                <a href="profile.php" class="block text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-3 py-2">
                    <i class="fas fa-user w-5"></i>
                    <span>Profile</span>
                </a>
                <a href="submit-complaint.php" class="block text-white hover:text-blue-200 transition-colors duration-300 flex items-center space-x-3 py-2">
                    <i class="fas fa-exclamation-circle w-5"></i>
                    <span>Complaints</span>
                </a>
                <a href="logout.php" class="btn-gradient px-6 py-3 rounded-full text-white font-semibold flex items-center justify-center space-x-2 mt-4">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>