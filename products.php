<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<?php
// Kết nối CSDL
include("includes/db.php"); 
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

  <div class="container">
    <h1 style="text-align:center;">Danh sách sản phẩm 🌸</h1>


  <form method="get" style="text-align:center; margin-bottom:20px;">
  <input type="text" name="search_name" placeholder="🔍 Tìm theo tên sản phẩm..." 
         value="<?= isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : '' ?>" 
         style="padding:8px; width:250px;">
  <button type="submit" style="padding:8px 12px;">Tìm kiếm</button>
</form>


    <?php
    // Lấy dữ liệu từ bảng products
  $where = "";

if (!empty($_GET['search_name'])) {
    $search_name = $conn->real_escape_string(trim($_GET['search_name']));
    $where = "WHERE name LIKE '%$search_name%'";
}

$sql = "SELECT * FROM products $where ORDER BY id DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0): ?>
      <div class="product-list" style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px;">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="product" style="width:220px; border:1px solid #ccc; padding:10px; text-align:center;">
            <!-- ✅ Hiển thị ảnh sản phẩm -->
            <img src="uploads/images/<?= htmlspecialchars($row['image']) ?>" 
                 alt="<?= htmlspecialchars($row['name']) ?>" 
                 style="width:100%; height:auto;">
            
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p>Giá: <?= number_format($row['price']) ?> VNĐ</p>
            <p>Tồn kho: <?= $row['stock'] ?></p>

            <!-- ✅ Nút thêm vào giỏ -->

            <a href="product_detail.php?id=<?php echo (int)$row['id']; ?>">Chi tiết</a>

          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p style="text-align:center;">Không có sản phẩm nào trong hệ thống.</p>
    <?php endif; ?>
  </div>

  <!-- Footer chung -->
  <?php include("includes/footer.php"); ?>
</body>
</html>
