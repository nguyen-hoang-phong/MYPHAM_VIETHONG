<?php
session_start();
include("../includes/admin_db.php");
 // kết nối CSDL
include("../includes/admin_header.php"); // header admin nếu có

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $price       = (int)$_POST['price'];
    $category    = trim($_POST['category']);
    $image = "";
if (!empty($_FILES['image']['name'])) {
    $target_dir = "../uploads/images/";
    $image = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // ảnh đã upload thành công
    } else {
        $message = "❌ Lỗi khi upload ảnh.";
    }
}

    $description = trim($_POST['description']);
    $stock       = (int)$_POST['stock'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, category, image, description, stock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssi", $name, $price, $category, $image, $description, $stock);

    if ($stmt->execute()) {
        $message = "✅ Thêm sản phẩm thành công!";
    } else {
        $message = "❌ Lỗi khi thêm sản phẩm: " . $conn->error;
    }
}
?>
<link rel="stylesheet" href="../css/add_product.css">

<div style="max-width:500px;margin:20px auto;">
  <h2 class="form-title">➕ Thêm sản phẩm mới</h2>

<form method="post" enctype="multipart/form-data" class="product-form">

  <label for="name">Tên sản phẩm</label>
  <input type="text" id="name" name="name" required>

  <label for="price">Giá (VNĐ)</label>
  <input type="number" id="price" name="price" required>

  <label for="category">Loại sản phẩm</label>
  <input type="text" id="category" name="category">

 <label for="image">Ảnh sản phẩm:</label>
<input type="file" id="image" name="image">


  <label for="description">Mô tả</label>
  <textarea id="description" name="description"></textarea>

  <label for="stock">Số lượng tồn kho</label>
  <input type="number" id="stock" name="stock" value="0">

  <button type="submit" class="btn-add">Thêm sản phẩm</button>
</form>

<p class="message"><?= $message ?></p>

  <p style="color:green;"><?= $message ?></p>
</div>

<?php include("../includes/admin_footer.php"); ?>
