<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "nbs"); // Change "your_database_name"

// Handle image upload
if (isset($_POST['upload'])) {
    $imageName = $_FILES['image']['name'];
    $imageData = addslashes(file_get_contents($_FILES['image']['tmp_name']));

    $sql = "INSERT INTO images (name, image) VALUES ('$imageName', '$imageData')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Image uploaded successfully.</p>";
    } else {
        echo "<p style='color:red;'>Failed to upload image.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload and Display Image</title>
</head>
<body>
    <h2>Upload Image</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="submit" name="upload" value="Upload">
    </form>

    <hr>

    <h2>Uploaded Images</h2>
    <?php
    $result = mysqli_query($conn, "SELECT * FROM images ORDER BY id DESC");

    while ($row = mysqli_fetch_array($result)) {
        echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" height="200"/><br><br>';
    }
    ?>
</body>
</html>
