<?php 
include_once('includes/student_header.php'); 

$uid = $_SESSION['student_uid'];

// Handle account deletion
if (isset($_POST['delete_account'])) {
    $delete_query = mysqli_query($con, "DELETE FROM students WHERE uid='$uid'");
    if ($delete_query) {
        // Destroy session and log out
        session_unset();
        session_destroy();
        // Redirect with a message
        header('Location: student_login.php?message=Account+deleted+successfully.');
        exit();
    } else {
        $msg = "Error deleting account. Please try again.";
    }
}

if (isset($_POST['update'])) {
    $name = $_POST['student_name'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    $query = mysqli_query($con, "UPDATE students SET student_name='$name', email='$email', class='$class',course='$course', phone='$phone', address='$address', dob='$dob', gender='$gender' WHERE uid='$uid'");
    $msg = $query ? "Profile updated successfully." : "Update failed.";
}

$result = mysqli_query($con, "SELECT * FROM students WHERE uid='$uid'");
$row = mysqli_fetch_assoc($result);

// Fetch categories for dropdown
$cat_result = mysqli_query($con, "SELECT categoryName FROM tblcategory ORDER BY categoryName");
$categories = [];
while ($cat = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $cat['categoryName'];
}

?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Student Profile</h2>
            <?php if (isset($msg)) { echo "<div class='bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-md'>$msg</div>"; } ?>
            <form method="post">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Student Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-user text-gray-400"></i>
                            </span>
                            <input type="text" name="student_name" class="pl-10 form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="<?php echo htmlentities($row['student_name']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </span>
                            <input type="email" name="email" class="pl-10 form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="<?php echo htmlentities($row['email']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">UID</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </span>
                            <input type="text" class="pl-10 form-input w-full rounded-lg bg-gray-100 border-gray-300" value="<?php echo htmlentities($row['uid']); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Class</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-chalkboard-teacher text-gray-400"></i>
                            </span>
                            <input type="text" name="class" class="pl-10 form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="<?php echo htmlentities($row['class']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Course</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-book text-gray-400"></i>
                            </span>
                            <select name="course" class="pl-10 form-select w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <option value="" disabled>Select Course</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlentities($cat); ?>" <?php if($row['course'] == $cat) echo 'selected'; ?>>
                                        <?php echo htmlentities($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-phone text-gray-400"></i>
                            </span>
                            <input type="text" name="phone" class="pl-10 form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="<?php echo htmlentities($row['phone']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pt-3">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </span>
                            <textarea name="address" class="pl-10 form-textarea w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"><?php echo htmlentities($row['address']); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Date of Birth</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </span>
                            <input type="date" name="dob" class="pl-10 form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="<?php echo htmlentities($row['dob']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-2">Gender</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-venus-mars text-gray-400"></i>
                            </span>
                            <select name="gender" class="pl-10 form-select w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="Male" <?php if($row['gender']=='Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if($row['gender']=='Female') echo 'selected'; ?>>Female</option>
                                <option value="Other" <?php if($row['gender']=='Other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end items-center gap-4">
                    <button type="submit" name="update" class="btn-gradient px-8 py-3 rounded-full text-white font-bold flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Update Profile</span>
                    </button>
                    <button type="submit" name="delete_account" class="bg-red-600 hover:bg-red-700 px-8 py-3 rounded-full text-white font-bold flex items-center space-x-2" onclick="return confirm('Are you sure you want to delete your account? This action is permanent and cannot be undone.');">
                        <i class="fas fa-trash-alt"></i>
                        <span>Delete Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>