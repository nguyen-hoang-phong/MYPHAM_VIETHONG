<?php
session_start();
include("includes/db.php"); // kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Truy vấn người dùng theo email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra mật khẩu
    if ($user && password_verify($password, $user['password'])) {
        // Lưu thông tin vào session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'fullname' => $user['fullname'],
            'role' => $user['role']
        ];

        // Chuyển hướng theo role
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        // Sai thông tin đăng nhập
        echo "<p style='color:red; text-align:center;'>Sai email hoặc mật khẩu!</p>";
        echo "<p style='text-align:center;'><a href='login.php'>Thử lại</a></p>";
    }
}
?>
