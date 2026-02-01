<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="header">
  <!-- Logo bên trái -->
  <div class="logo">
    <img src="uploads/images/logo VIETHONG.png" alt="Logo Mỹ Phẩm Việt Hồng" width="180">
  </div>

  <!-- Nội dung bên phải -->
  <div class="branding">
    <h2>Mỹ Phẩm Việt Hồng 🌸</h2>
    <nav>
  <a href="index.php">Trang chủ</a> |
  <a href="products.php">Sản phẩm</a> |
  <a href="about.php">Giới thiệu</a> |
  <a href="contact.php">Liên hệ</a> |
 
  <?php
if (isset($_SESSION['user']))
 { echo '| <a href="logout.php">Đăng xuất</a>';
  
 } else { echo '| <a href="login.php">Đăng nhập</a>'; 
  echo '| <a href="register.php">Đăng ký</a>'; }
?>

 
</nav>

  </div>
  <div class="product-ad">
  <img src="uploads/images/hinhdtht.png" alt="Sản phẩm quảng cáo" width="250" height="150">
</div>

   <div class="product-ad">
  <img src="uploads/images/combo.jpg" alt="Sản phẩm quảng cáo" width="250" height="150">
</div>

      <div class="video-intro">
  <video width="250" height="150" controls>
    <source src="uploads/video/intro1.mp4" type="video/mp4">
    Trình duyệt của bạn không hỗ trợ video.
  </video>
</div>

</div>


