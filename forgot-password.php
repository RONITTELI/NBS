<?php
session_start();
include('includes/dbconnection.php');

$msg = "";

if (isset($_POST['submit'])) {
    $contactno = $_POST['contactno'];
    $email = $_POST['email'];

    $query = mysqli_query($con, "SELECT ID FROM tbladmin WHERE Email='$email' AND MobileNumber='$contactno'");
    $ret = mysqli_fetch_array($query);

    if ($ret) {
        $_SESSION['contactno'] = $contactno;
        $_SESSION['email'] = $email;
        header('Location: resetpassword.php');
        exit();
    } else {
        $msg = "Invalid Details. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #e0f7fa, #80deea);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.box {
    background: #ffffff;
    padding: 40px 50px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 420px;
    text-align: center;
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

h2 {
    margin-bottom: 25px;
    color: #00796b;
    font-weight: 600;
    font-size: 26px;
}

label {
    float: left;
    margin: 12px 0 6px;
    font-size: 15px;
    color: #333;
}

input[type="text"],
input[type="email"] {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: border-color 0.3s;
    font-size: 15px;
}

input:focus {
    border-color: #26a69a;
    outline: none;
    box-shadow: 0 0 5px rgba(38, 166, 154, 0.2);
}

button {
    background-color: #26a69a;
    color: white;
    border: none;
    padding: 14px;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background 0.3s ease;
}

button:hover {
    background-color: #2bbbad;
}

.message {
    color: #e53935;
    margin-bottom: 15px;
    font-weight: bold;
}

.link {
    margin-top: 20px;
    display: block;
    font-size: 14px;
    color: #00796b;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>

<div class="box">
    <h2>Password Recovery</h2>

    <?php if ($msg): ?>
        <div class="message"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Mobile Number:</label>
        <input type="text" name="contactno" required>

        <button type="submit" name="submit">Reset</button>
    </form>

    <a href="index.php" class="link">Back to Sign In</a>
</div>

</body>
</html>
