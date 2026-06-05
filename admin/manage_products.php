<?php
session_start();
include("../includes/admin_db.php");
 // kết nối CSDL
include("../includes/admin_header.php"); // header admin nếu có
?>
<?php

// Cập nhật VAT
if ($_SERVER['REQUEST_METHOD'] == 'POST'
    && isset($_POST['tax_percent'])) {

    $id = (int)$_POST['id'];

    $tax_percent = (float)$_POST['tax_percent'];

    // Giới hạn VAT từ 0 -> 100
    if ($tax_percent < 0) {
        $tax_percent = 0;
    }

    if ($tax_percent > 100) {
        $tax_percent = 100;
    }

    // Update database
    $stmt = $conn->prepare("
        UPDATE products
        SET tax_percent = ?
        WHERE id = ?
    ");

    $stmt->bind_param("di", $tax_percent, $id);

    $stmt->execute();

    $stmt->close();

    // Reload lại trang
    header("Location: manage_products.php");

    exit;
}
?>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/product_admin.css">

<?php

$where = [];

if (!empty($_GET['search_name'])) {
    $search_name = trim($conn->real_escape_string($_GET['search_name']));
    $where[] = "name LIKE '%$search_name%'";
}

if (!empty($_GET['search_category'])) {
    $search_category = trim($conn->real_escape_string($_GET['search_category']));
    $where[] = "category LIKE '%$search_category%'";
}

$sql = "SELECT * FROM products";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);


?>

<h2>📋 Danh sách sản phẩm</h2>
<div style="margin-bottom: 10px;"></div>

<div class="product-actions">

    <div style="margin-bottom:10px;">
        <a href="add_product.php" class="btn-add">
            ➕ Thêm sản phẩm mới
        </a>
    </div>

  <form method="get" class="search-form">
    <input type="text" name="search_name" placeholder="Tìm theo tên sản phẩm..." 
           value="<?= isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : '' ?>">
    <select name="search_category">
      <option value="">-- Loại sản phẩm --</option>
      <option value="KEM" <?= isset($_GET['search_category']) && $_GET['search_category']=='KEM' ? 'selected' : '' ?>>KEM</option>
      <option value="SERUM" <?= isset($_GET['search_category']) && $_GET['search_category']=='SERUM' ? 'selected' : '' ?>>SERUM</option>
      <option value="Son" <?= isset($_GET['search_category']) && $_GET['search_category']=='Son' ? 'selected' : '' ?>>Son</option>
      <option value="Chống nắng" <?= isset($_GET['search_category']) && $_GET['search_category']=='Chống nắng' ? 'selected' : '' ?>>Chống nắng</option>
      <option value="Collagen" <?= isset($_GET['search_category']) && $_GET['search_category']=='Collagen' ? 'selected' : '' ?>>Collagen</option>
    </select>
    <button type="submit">🔍 Tìm kiếm</button>
  </form>
</div>


<table border="1" width="100%" cellspacing="0" cellpadding="5">
  <tr style="background:#f2f2f2;">
    <th>ID</th>
    <th>Tên</th>
    <th>Giá</th>
    <th>Thuế (%)</th>
    <th>Loại</th>
    <th>Ảnh</th>
    <th>Tồn kho</th>
    <th>Hành động</th>
  </tr>

  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>

    <?php
   $tax_percent = isset($row['tax_percent'])
                  ? $row['tax_percent']
                  : 0;
?>

      <tr>
        <td><?= $row['id'] ?></td>

        <td><?= htmlspecialchars($row['name']) ?></td>

        <td><?= number_format($row['price']) ?> VNĐ</td>

      <td class="tax-cell">

    <form action="" method="post" class="tax-form">

        <input type="hidden"
               name="id"
               value="<?= $row['id'] ?>">

        <input type="number"
       name="tax_percent"
       value="<?= $tax_percent ?>"
       min="0"
       max="100"
       class="tax-input">

        <button type="submit">
            OK
        </button>

    </form>

</td>

        <td><?= htmlspecialchars($row['category']) ?></td>

        <td>
          <?php if (!empty($row['image'])): ?>
            <img src="../uploads/images/<?= htmlspecialchars($row['image']) ?>" 
                 alt="<?= htmlspecialchars($row['name']) ?>" 
                 width="60">
          <?php else: ?>
            Không có ảnh
          <?php endif; ?>
        </td>

        <td><?= (int)$row['stock'] ?></td>

        <td>
          <a href="edit_product.php?id=<?= $row['id'] ?>">✏️ Sửa</a> |
          <a href="delete_product.php?id=<?= $row['id'] ?>" 
             onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
             🗑️ Xóa
          </a>
        </td>
      </tr>

    <?php endwhile; ?>

  <?php else: ?>

    <tr>
      <td colspan="8" style="text-align:center;">
        Không tìm thấy sản phẩm phù hợp
      </td>
    </tr>

  <?php endif; ?>
</table>

<?php include("../includes/admin_footer.php"); ?>
