<?php
session_start();
include("../includes/admin_db.php");
include("../includes/admin_header.php");

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin đơn hàng
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn hàng
$stmt = $conn->prepare("
  SELECT p.name, oi.quantity, oi.price
  FROM order_items oi
  JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div style="max-width:800px; margin:20px auto;">
  <h2>🧾 Chi tiết đơn hàng #<?= $order_id ?></h2>
  <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
  <p><strong>SĐT:</strong> <?= htmlspecialchars($order['phone']) ?></p>
  <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
  <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
  <p><strong>Ngày tạo:</strong> <?= $order['created_at'] ?></p>

  <table border="1" width="100%" cellpadding="8" style="margin-top:20px;">
    <tr>
      <th>Sản phẩm</th>
      <th>Số lượng</th>
      <th>Đơn giá</th>
      <th>Thành tiền</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= $row['quantity'] ?></td>
      <td><?= number_format($row['price']) ?> VNĐ</td>
      <td><?= number_format($row['price'] * $row['quantity']) ?> VNĐ</td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

<?php include("../includes/admin_footer.php"); ?>
