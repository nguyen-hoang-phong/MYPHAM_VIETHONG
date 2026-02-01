<?php

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <!-- Gọi CSS ngoài admin -->
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-header">
  <div class="logo">
    <img src="../uploads/images/logo VIETHONG.png" alt="Logo Mỹ Phẩm Việt Hồng" width="180">
  </div>
  <div class="branding">
    <h2>Admin Panel 🌸</h2>
    <nav>
      <a href="dashboard.php">Dashboard</a> |
      <a href="manage_products.php">Tồn Kho</a> |
      <a href="orders.php">Đơn hàng</a> |
      <a href="users.php">Người dùng</a> |
      <a href="logout_admin.php">Đăng xuất</a>
    </nav>
  </div>
</div>
<hr>
