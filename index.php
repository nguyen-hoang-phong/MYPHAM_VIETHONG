<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
require_once("includes/db.php");
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


</div>
   <div class="slider">
    <img src="uploads/images/slider_1.png" class="slide active">
    <img src="uploads/images/slider_2.png" class="slide">
    <img src="uploads/images/slider_3.png" class="slide">
</div>
  <p class="slogan">Chăm sóc làn da Việt – Tỏa sáng cùng Việt Hồng</p>

  <!-- Nội dung trang sản phẩm -->
   <?php
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>

    <div class="product-list">

<?php while($product = $result->fetch_assoc()) { ?>

  <div class="product">

    <img src="uploads/images/<?php echo htmlspecialchars($product['image']); ?>"
         alt="<?php echo htmlspecialchars($product['name']); ?>">

    <h3><?php echo htmlspecialchars($product['name']); ?></h3>

    <p>
      Giá:
      <?php echo number_format($product['price'],0,',','.'); ?>đ
    </p>

    <a href="product_detail.php?id=<?php echo (int)$product['id']; ?>">
      Xem chi tiết
    </a>

  </div>

<?php } ?>

</div>
  <!-- Footer chung -->
  <?php include("includes/footer.php"); ?>
</body>

<script>
let slides = document.querySelectorAll('.slide');
let index = 0;

setInterval(() => {

    slides[index].classList.remove('active');

    index++;

    if(index >= slides.length){
        index = 0;
    }

    slides[index].classList.add('active');

}, 3000);
</script>
</html>
