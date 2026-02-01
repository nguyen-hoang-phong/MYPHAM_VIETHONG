<link rel="stylesheet" href="css/style.css">

<?php
include("includes/db.php"); // đảm bảo $conn đã kết nối

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT id, name, price, description, image, category FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Sản phẩm không tồn tại.</p>";
} else {
    $product = $result->fetch_assoc();
    ?>
    <div class="product-detail">
      <div class="product-image">
       <img src="uploads/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="300">

      </div>
      <div class="product-info">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p><strong>Giá:</strong> <?php echo number_format((float)$product['price'], 0, ',', '.'); ?> đ</p>
        <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($product['category']); ?></p>

<form method="post" action="cart.php?action=add">
  <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
  <input type="number" name="quantity" value="1" min="1" max="<?= isset($product['stock']) ? $product['stock'] : 99 ?>">
  <button type="submit">Thêm vào giỏ</button>
  
</form>


      </div>
    </div>
    <?php
}
$stmt->close();
?>


