<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include("../includes/admin_db.php");

include("../includes/admin_header.php");



// Lấy ID sản phẩm từ URL
$id = $_GET['id'] ?? 0;

// Lấy thông tin sản phẩm
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "<p style='color:red; text-align:center;'>Sản phẩm không tồn tại!</p>";
    include("../includes/admin_footer.php");
    exit;
}

// Nếu form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $price    = $_POST['price'];
    $category = $_POST['category'];
    $qty    = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;
$action = $_POST['action'] ?? 'import';

if ($action === 'import') {
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, stock=stock+? WHERE id=?");
} else {
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, stock=stock-? WHERE id=?");
}
$stmt->bind_param("sisii", $name, $price, $category, $qty, $id);


    if ($stmt->execute()) {
      $action_text = ($action === 'import') ? "nhập thêm vào kho" : "xuất khỏi kho";
echo "<p style='color:green; text-align:center;'>✅ Cập nhật sản phẩm thành công! Số lượng {$qty} đã được {$action_text}.</p>";

        echo "<p style='text-align:center;'><a href='manage_products.php'>Quay lại danh sách sản phẩm</a></p>";
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Có lỗi xảy ra khi cập nhật!</p>";
    }
}

?>
<link rel="stylesheet" href="../css/edit_product.css">

<div class="container" style="max-width:500px; margin:auto;">
 <h2 class="form-title">✏️ Sửa sản phẩm</h2>

<form method="post" class="product-form">
  <label for="name">Tên sản phẩm:</label>
  <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

<label>Giá:</label>
<input type="number" name="price" class="price-input" value="<?= $product['price'] ?>" required>


  <label for="category">Loại sản phẩm:</label>
  <input type="text" id="category" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>

  <label for="qty">Số lượng:</label>
  <input type="number" id="qty" name="qty" min="0" value="0">

  <label for="action">Hành động:</label>
  <select id="action" name="action">
    <option value="import">Nhập kho</option>
    <option value="export">Xuất kho</option>
  </select>

  <button type="submit" class="btn-update">Cập nhật</button>
</form>


</div>
<?php include("../includes/admin_footer.php"); ?>
