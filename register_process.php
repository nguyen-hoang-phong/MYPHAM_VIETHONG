<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("includes/admin_db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($fullname) || empty($email) || empty($password)) {
        die("Vui lòng nhập đầy đủ thông tin!");
    }

    // Mã hóa mật khẩu
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra email tồn tại
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Email đã tồn tại!");
    }

    // Thêm user
    $stmt = $conn->prepare(
        "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $fullname, $email, $hashPassword);

    if ($stmt->execute()) {
        header("Location: login.php?success=1");
        exit;
    } else {
        echo "Lỗi SQL: " . $stmt->error;
    }
} else {
    echo "Truy cập không hợp lệ!";
}