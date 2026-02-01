<?php
// admin/includes/admin_db.php

$host = "localhost";       // hoặc 127.0.0.1
$user = "root";            // user MySQL
$pass = "";                // mật khẩu MySQL (nếu có)
$db   = "viethong-db"; // tên database của bạn

$conn = new mysqli($host, $user, $pass, $db);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>


