<?php 
session_start();
include("../includes/admin_db.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include("../includes/admin_header.php"); 
?>
<div class="container">
  <h2 style="text-align:center;">🌸 Chào mừng đến Admin Dashboard</h2>
  <p style="text-align:center;">Hãy chọn chức năng ở menu để quản lý hệ thống.</p>
</div>
<?php include("../includes/admin_footer.php"); ?>
