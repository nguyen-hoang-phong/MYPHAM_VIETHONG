<?php
session_start();
include("includes/db.php"); // file kết nối CSDL

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullname, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Đăng ký thành công, vui lòng đăng nhập!";
        header("Location: login.php");
    } else {
        echo "Có lỗi xảy ra!";
    }
}
?>
