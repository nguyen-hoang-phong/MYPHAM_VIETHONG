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

  <style>
    .admin-header{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        flex-wrap: wrap;
        padding: 20px;
    }

    .product-ad img,
    .video-intro video{
        border-radius: 10px;
    }

    .branding{
        width: 100%;
        text-align: center;
        margin-top: 20px;
    }

    .branding nav a{
        text-decoration: none;
        margin: 0 10px;
        font-weight: bold;
    }
  </style>
</head>

<body>

<div class="admin-header">
    <div class="logo">
        <img src="../uploads/images/logo VIETHONG.png"
             alt="Logo Mỹ Phẩm Việt Hồng"
             width="250">
    </div>
    <div class="product-ad">
        <img src="../uploads/images/hinhdtht.png"
             alt="Sản phẩm quảng cáo"
             width="250"
             height="150">
    </div>
    <div class="product-ad">
        <img src="../uploads/images/combo.jpg"
             alt="Sản phẩm quảng cáo"
             width="250"
             height="150">
    </div>
    <div class="video-intro">
        <video width="250" height="150" controls>
            <source src="../uploads/video/intro1.mp4" type="video/mp4">
            Trình duyệt của bạn không hỗ trợ video.
        </video>
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
