<?php
// bdconnection.php

// ---- Database connection ----
$con = mysqli_connect("127.0.0.1:3306", "root", "", "nbs");
// alternative: mysqli_connect("127.0.0.1:3308", "root", "", "nbs");
// hosting example: mysqli_connect("sql_host", "username", "password", "dbname");

$apiKey = ""; // Your OpenAI API key here


if (mysqli_connect_errno()) {
    echo "Connection Fail: " . mysqli_connect_error();
    exit;
}


  
