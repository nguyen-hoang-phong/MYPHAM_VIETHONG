<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Mỹ Phẩm Việt Hồng</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
 
   <!-- Header chung -->
  <?php include("includes/header.php"); ?>

  <!-- Nội dung trang chủ -->
   <div style="text-align: center;">
  <h1>Chào mừng đến với Mỹ Phẩm Việt Hồng 🌸</h1>
  <p class="slogan">Chăm sóc làn da Việt – Tỏa sáng cùng Việt Hồng</p>




  <!-- Nội dung trang sản phẩm -->
 
    <div class="product-list">
    <div class="product">
      <img src="uploads/images/hinh3.png" alt="Sản phẩm 3">
      <h3>Kem Tắm Trắng Body Collagen x 3</h3>
      <p>Giá: 500.000đ</p>
      <a href="product_detail.php?id=4">Xem chi tiết</a>
    </div>
  <div class="product">
      <img src="uploads/images/hinh4.png" alt="Sản phẩm 4">
      <h3>Kem Kích TRắng Body Collagen x 3 </h3>
      <p>Giá: 450.000đ</p>
      <a href="product_detail.php?id=5">Xem chi tiết</a>
    </div>
      <div class="product">
      <img src="uploads/images/hinh9.png" alt="Sản phẩm 9">
      <h3>COLLAGEN X3 Chiết Xuất Sữa Ong Chúa</h3>
      <p>Giá: 600.000đ</p>
      <a href="product_detail.php?id=6">Xem chi tiết</a>
    </div>
     <div class="product">
      <img src="uploads/images/hinh10.png" alt="Sản phẩm 10">
      <h3>Serum Face Collagen Đông Trùng Hạ Thảo</h3>
      <p>Giá: 450.000đ</p>
      <a href="product_detail.php?id=7">Xem chi tiết</a>
    </div>
       <div class="product">
      <img src="uploads/images/hinh5.png" alt="Sản phẩm 5">
      <h3>Chống Nắng SPF 50+ UVA, UVB</h3>
      <p>Giá: 300.000đ</p>
      <a href="product_detail.php?id=8">Xem chi tiết</a>
    </div>
    
   
    
    <div class="product">
      <img src="uploads/images/hinh7.png" alt="Sản phẩm 7">
      <h3>Combo Face Chống Lão Hóa</h3>
      <p>Giá: 250.000đ</p>
      <a href="product_detail.php?id=9">Xem chi tiết</a>
    </div>
    <div class="product">
      <img src="uploads/images/hinh1.png" alt="Sản phẩm 1">
      <h3>Serum Trị Mụn Collagne x3</h3>
      <p>Giá: 250.000đ</p>
      <a href="product_detail.php?id=10">Xem chi tiết</a>
    </div>

    

    <!-- Thêm sản phẩm khác tại đây -->
     
    
    
    <div class="product">
      <img src="uploads/images/hinhsonhong.png" alt="Sản phẩm 6">
      <h3>Bộ 3 son Hồng </h3>
      <p>Giá: 350.000đ</p>
      <a href="product_detail.php?id=12">Xem chi tiết</a>
    </div>
    <div class="product">
      <img src="uploads/images/hinh2.png" alt="Sản phẩm 2">
      <h3>Kem Face Trắng Da collagen x 3</h3>
      <p>Giá: 450.000đ</p>
      <a href="product_detail.php?id=13">Xem chi tiết</a>
    </div>
 
    <div class="product">
      <img src="uploads/images/hinhdtht.png" alt="Sản phẩm 8">
      <h3>Kem Đông Trùng Hạ Thảo</h3>
      <p>Giá: 450.000đ</p>
      <a href="product_detail.php?id=14">Xem chi tiết</a>
    </div>
    
  </div>
</div>
  <!-- Footer chung -->
  <?php include("includes/footer.php"); ?>
</body>
</html>
